<?php

namespace App\Http\Controllers;

use App\Models\CheckInOut;
use App\Models\OrderPaket;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|integer',
            'jadwal' => 'required|date',
            'status' => 'required|string|in:checkin,checkout',
            'id_location' => 'required|integer',
        ]);

        // Cari paket aktif untuk user
        $activePaket = OrderPaket::where('id_user', $request->id_user)
            ->where('is_active', 1)
            ->where('status', 3) // Status 'Aktif'
            ->where('expired_date', '>', now())
            ->first();

        if (!$activePaket) {
            return response()->json([
                'message' => 'No active package found for this user'
            ], 400);
        }

        $status = $request->status === 'checkin' ? 1 : 2;

        $checkInOut = CheckInOut::create([
            'id_user' => $request->id_user,
            'jadwal' => $request->jadwal,
            'status' => $status,
            'id_location' => $request->id_location,
            'id_order_paket' => $activePaket->id,
        ]);

        return response()->json($checkInOut);
    }

    public function index(Request $request)
    {
        $query = CheckInOut::with(['user', 'location', 'orderPaket']);

        if ($request->has('user_id') && $request->user_id) {
            $query->where('id_user', $request->user_id);
        }

        $checkInOuts = $query->paginate($request->get('per_page', 15));

        return response()->json($checkInOuts);
    }
}
