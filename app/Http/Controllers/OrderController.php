<?php

namespace App\Http\Controllers;

use App\Http\Controllers\HelperController as HelperUtility;
use App\Models\Paket;
use App\Models\User;
use App\Models\OrderPaket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function publicPackages()
    {
        $pakets = Paket::where('is_show_public', 1)->get();
        return view('public-packages', compact('pakets'));
    }

    public function orderPackage($id)
    {
        $paket = Paket::findOrFail($id);
        return view('order-package', compact('paket'));
    }

    public function postOrderPackage(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
        ]);

        try {
            $paket = Paket::findOrFail($id);

            // Cek apakah email sudah terdaftar
            $user = User::where('email', $request->email)->first();

            if ($user) {
                // Jika user sudah ada, update data terbaru
                $user->update([
                    'name' => $request->nama,
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                ]);
            } else {
                // Jika user belum ada, buat user baru
                $user = User::create([
                    'name' => $request->nama,
                    'email' => $request->email,
                    'password' => Hash::make(Str::random(10)), // password random
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                    'tanggal_lahir' => now()->subYears(20), // default tanggal lahir
                    'jenis_kelamin' => 'L', // default jenis kelamin
                    'status' => 0, // belum verifikasi
                ]);
            }

            // Cek apakah user sudah memiliki order paket yang aktif
            $existingOrder = OrderPaket::where('id_user', $user->id)
                ->where('id_paket', $paket->id)
                ->where('status', '!=', 3) // bukan cancelled
                ->where('is_active', 1)
                ->first();

            if ($existingOrder) {
                return redirect()->back()->with('error', 'Anda sudah memiliki paket ini yang masih aktif. Silakan hubungi admin untuk informasi lebih lanjut.');
            }

            // Buat order paket baru
            $expiredDate = now()->addMonths($paket->valid_month ?? 1);
            $order = OrderPaket::create([
                'id_user' => $user->id,
                'id_paket' => $paket->id,
                'harga' => $paket->harga_diskon ?? $paket->harga,
                'status' => 2, // belum lunas
                'is_active' => 0,
                'expired_date' => $expiredDate,
                'public_token' => (string) Str::uuid(),
            ]);

            return redirect()
                ->route('order-payment.show', $order->public_token)
                ->with('success', 'Pemesanan berhasil! Silakan pilih metode pembayaran yang Anda inginkan.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses pemesanan. Silakan coba lagi.');
        }
    }

    public function showOrderPayment($token)
    {
        $order = OrderPaket::with(['paket', 'user'])->where('public_token', $token)->firstOrFail();

    $paymentConfig = config('services.payment');
    $transferTotal = (int) $order->harga + ($order->payment_method === 'transfer' && $order->unix_code ? (int) $order->unix_code : 0);

        return view('order-payment', [
            'order' => $order,
            'paymentConfig' => $paymentConfig,
            'transferTotal' => $transferTotal,
        ]);
    }

    public function processOrderPayment(Request $request, $token)
    {
        $request->validate([
            'payment_method' => 'required|in:manual,transfer',
        ]);

        $order = OrderPaket::where('public_token', $token)->firstOrFail();

        // Hindari perubahan metode ketika pembayaran sudah selesai.
        if ((int) $order->status === 1) {
            return redirect()
                ->route('order-payment.show', $token)
                ->with('info', 'Pembayaran untuk pesanan ini sudah dikonfirmasi.');
        }

        $order->payment_method = $request->payment_method;

        if ($request->payment_method === 'transfer') {
            if (!$order->unix_code) {
                $order->unix_code = HelperUtility::generateUnixKode((int) $order->harga);
            }

            $message = 'Metode transfer berhasil disimpan. Silakan selesaikan pembayaran sesuai instruksi.';
        } else {
            $order->unix_code = null;
            $message = 'Metode verifikasi manual berhasil disimpan. Tim kami akan menghubungi Anda untuk langkah berikutnya.';
        }

        $order->save();

        return redirect()
            ->route('order-payment.show', $order->public_token)
            ->with('success', $message);
    }
}
