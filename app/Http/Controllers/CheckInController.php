<?php

namespace App\Http\Controllers;

use App\Models\CheckInOut;
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
            'id_order_paket' => 'nullable|integer',
        ]);

        $status = $request->status === 'checkin' ? 1 : 2;

        $checkInOut = CheckInOut::create([
            'id_user' => $request->id_user,
            'jadwal' => $request->jadwal,
            'status' => $status,
            'id_location' => $request->id_location,
            'id_order_paket' => $request->id_order_paket,
        ]);

        return response()->json([
            'message' => 'Check-in data saved successfully',
            'data' => $checkInOut
        ], 201);
    }
}
