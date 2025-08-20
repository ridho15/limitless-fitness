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
                'id_kelas' => 'required|integer',
            ], [
                'id_user.required' => 'Data user belum dikirim',
                'id_user.integer' => 'Data user tidak valid !',
                'id_user.exists' => 'User tidak ditemukan',
                'id_kelas.required' => "Data kelas belum dikirim",
                'id_kelas.integer' => 'Data kelas tidak valid !'
            ]);

            $userId = $request->input('id_user');
            $kelasId = $request->input('id_kelas');

            $today = Carbon::now();

            $orderPaket = OrderPaket::with(['paket.kelas'])
                ->where('id_user', $userId)
                ->where('is_active', true)
                ->where('expired_date', '>=', $today)
                ->where('status', 1)
                ->get()
                ->filter(function ($order) use ($kelasId) {
                    if (!$order->paket || !$order->paket->kelas) {
                        return false;
                    }
                    return $order->paket->kelas->contains('id', $kelasId);
                })
                ->first();

            if ($orderPaket) {
                return response()->json([
                    'status' => true,
                    'message' => 'Paket tersedia dan sesuai dengan kelas',
                    'data' => [
                        'order_paket_id' => $orderPaket->id,
                        'user_id' => $orderPaket->id_user,
                        'paket_id' => $orderPaket->id_paket,
                        'paket_name' => $orderPaket->paket->nama,
                        'expired_date' => $orderPaket->expired_date,
                        'status' => $orderPaket->status_formatted,
                        'remaining_days' => Carbon::parse($orderPaket->expired_date)->diffInDays($today)
                    ]
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada paket aktif yang sesuai dengan kelas ini'
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
