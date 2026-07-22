<?php

namespace App\Http\Controllers;

use App\Models\CheckInOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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

        $key = "checkin:{$request->id_user}:{$request->id_location}:{$status}";
        $checkInOut = Cache::lock($key, 10)->block(5, function () use ($request, $status) {
            return DB::transaction(function () use ($request, $status) {
                $existing = CheckInOut::query()
                    ->where('id_user', $request->id_user)
                    ->where('id_location', $request->id_location)
                    ->where('status', $status)
                    ->where('created_at', '>=', now()->subMinutes(config('services.middleware.checkin_dedupe_minutes', 5)))
                    ->latest()
                    ->lockForUpdate()
                    ->first();

                return $existing ?: CheckInOut::create([
                    'id_user' => $request->id_user,
                    'jadwal' => $request->jadwal,
                    'status' => $status,
                    'id_location' => $request->id_location,
                    'id_order_paket' => $request->id_order_paket,
                ]);
            });
        });

        return response()->json([
            'message' => $checkInOut->wasRecentlyCreated ? 'Check-in data saved successfully' : 'Duplicate check-in ignored',
            'data' => $checkInOut
        ], $checkInOut->wasRecentlyCreated ? 201 : 200);
    }

    public function history(Request $request, int $userId)
    {
        $request->validate(['from' => 'nullable|date', 'to' => 'nullable|date|after_or_equal:from']);
        $query = CheckInOut::with(['location', 'orderPaket.paket'])->where('id_user', $userId)->latest();
        $query->when($request->from, fn ($q, $from) => $q->where('created_at', '>=', $from));
        $query->when($request->to, fn ($q, $to) => $q->where('created_at', '<=', $to));

        $perPage = min(max((int) $request->integer('per_page', 25), 1), 100);
        return response()->json(['status' => true, 'data' => $query->paginate($perPage)]);
    }
}
