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
        $request->validate([
            'id_user' => 'required|integer',
            'id_kelas' => 'required|integer',
        ], [
            'id_user.required' => 'Data user belum dikirim',
            'id_user.integer' => 'Data user tidak valid !',
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
            ->where('status', 1) // Optional
            ->get()
            ->filter(function ($order) use ($kelasId) {
                return $order->paket->kelas->contains('id', $kelasId);
            })
            ->first();
        if ($orderPaket) {
            return response()->json([
                'status' => true,
                'message' => 'Paket tersedia dan sesuai.',
                'order_paket' => $orderPaket,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada paket aktif yang sesuai dengan kelas ini.',
            ], 404);
        }
    }
}
