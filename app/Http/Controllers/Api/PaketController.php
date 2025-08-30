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

            // Ambil semua paket aktif user
            $orderPakets = OrderPaket::with(['paket'])
                ->where('id_user', $userId)
                ->where('is_active', true)
                ->where('expired_date', '>=', $today)
                ->where('status', 1)
                ->get();

            if ($orderPakets->isNotEmpty()) {
                // Transform data untuk response
                $paketData = $orderPakets->map(function($order) use ($today) {
                    return [
                        'order_paket_id' => $order->id,
                        'paket_id' => $order->id_paket,
                        'paket_name' => $order->paket->nama ?? 'Unknown Package',
                        'paket_type' => $order->paket->tipe ?? null,
                        'paket_durasi' => $order->paket->durasi ?? null,
                        'expired_date' => $order->expired_date,
                        'status' => $order->status_formatted ?? 'Active',
                        'remaining_days' => Carbon::parse($order->expired_date)->diffInDays($today),
                        'created_at' => $order->created_at
                    ];
                });

                return response()->json([
                    'status' => true,
                    'message' => 'User memiliki paket aktif',
                    'data' => [
                        'user_id' => $userId,
                        'total_active_packages' => $orderPakets->count(),
                        'packages' => $paketData
                    ]
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'User tidak memiliki paket aktif'
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
