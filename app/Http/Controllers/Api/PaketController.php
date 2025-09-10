<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderPaket;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    public function checkPaket(Request $request)
    {
        try {
            $request->validate([
                'id_user' => 'required|integer|exists:users,id',
            ], [
                'id_user.required' => 'Data user belum dikirim',
                'id_user.integer' => 'Data user tidak valid !',
                'id_user.exists' => 'User tidak ditemukan'
            ]);

            $userId = $request->input('id_user');
            $today = Carbon::now();

            // Mengacu ke order paket dengan kondisi:
            // 1. status = true (1) 
            // 2. is_active = true (1)
            // 3. expired_date >= hari ini
            // Karena kelas paket belum aktif pada server live, kita cek langsung dari order_paket
            $orderPakets = OrderPaket::with(['paket'])
                ->where('id_user', $userId)
                ->where('status', 1) // status true/1
                ->where('is_active', 1) // is_active true/1  
                ->where('expired_date', '>=', $today) // belum expired
                ->get();

            if ($orderPakets->isNotEmpty()) {
                // Transform data untuk response
                $paketData = $orderPakets->map(function ($order) use ($today) {
                    $expiredDate = Carbon::parse($order->expired_date);
                    $remainingDays = $expiredDate->diffInDays($today);

                    return [
                        'order_paket_id' => $order->id,
                        'paket_id' => $order->id_paket,
                        'paket_name' => $order->paket->nama ?? 'Unknown Package',
                        'paket_type' => $order->paket->tipe ?? null,
                        'paket_durasi' => $order->paket->durasi ?? null,
                        'expired_date' => $order->expired_date,
                        'status' => 'Active', // Pasti active karena sudah difilter
                        'remaining_days' => $remainingDays,
                        'created_at' => $order->created_at,
                        'order_status' => $order->status,
                        'is_active' => $order->is_active
                    ];
                });

                return response()->json([
                    'status' => true,
                    'message' => 'User memiliki paket aktif',
                    'data' => [
                        'user_id' => $userId,
                        'total_active_packages' => $orderPakets->count(),
                        'packages' => $paketData,
                        'check_method' => 'order_paket_based', // Info bahwa pengecekan berdasarkan order_paket
                        'note' => 'Pengecekan berdasarkan order_paket karena kelas_paket belum aktif'
                    ]
                ], 200);
            } else {
                // Jika tidak ada paket aktif, cek apakah user punya order paket sama sekali
                $hasAnyOrders = OrderPaket::where('id_user', $userId)->exists();

                $message = $hasAnyOrders
                    ? 'User tidak memiliki paket aktif (ada order tapi sudah expired atau inactive)'
                    : 'User tidak memiliki order paket sama sekali';

                return response()->json([
                    'status' => false,
                    'message' => $message,
                    'data' => [
                        'user_id' => $userId,
                        'total_active_packages' => 0,
                        'has_any_orders' => $hasAnyOrders,
                        'check_method' => 'order_paket_based'
                    ]
                ], 404);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $th->getMessage()
            ], 500);
        }
    }
}
