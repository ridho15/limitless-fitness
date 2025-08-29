<?php

namespace App\Http\Controllers;

use App\Imports\ImportBookingKelas;
use App\Imports\OrderPaketImport;
use App\Imports\UsersImport;
use App\Mail\RegistrasiKonfirmasiMail;
use App\Models\BookingKelas;
use App\Models\KelasActivity;
use App\Models\OrderPaket;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class HelperController extends Controller
{
    /**
     * Ambil gambar selfie user berdasarkan ID
     */
    public function getUserSelfie($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }
        return response()->json([
            'status' => true,
            'data' => [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'selfie' => $user->selfie,
                'img_base64' => $user->img_base64
            ]
        ], 200);
    }

    /**
     * Ambil gambar selfie semua user
     */
    public function getAllUserSelfies()
    {
        $users = User::select('id', 'name', 'selfie', 'img_base64')->get();
        $result = $users->map(function($user) {
            return [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'selfie' => $user->selfie,
                'img_base64' => $user->img_base64
            ];
        });
        return response()->json([
            'status' => true,
            'data' => $result
        ], 200);
    }

    /**
     * Ambil daftar users dengan pagination
     */
    public function getUsers(Request $request)
    {
        try {
            // Validasi parameter query
            $request->validate([
                'page' => 'nullable|integer|min:1',
                'per_page' => 'nullable|integer|min:1|max:100',
                'search' => 'nullable|string|max:255',
                'role' => 'nullable|string',
                'status' => 'nullable|in:active,inactive'
            ]);

            $perPage = $request->get('per_page', 15);
            $search = $request->get('search');
            $role = $request->get('role');
            $status = $request->get('status');

            // Query builder dengan kondisi pencarian
            $query = User::with('roles');

            // Filter berdasarkan pencarian nama atau email
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%');
                });
            }

            // Filter berdasarkan role
            if ($role) {
                $query->whereHas('roles', function($q) use ($role) {
                    $q->where('name', $role);
                });
            }

            // Filter berdasarkan status
            if ($status === 'active') {
                $query->where('email_verified_at', '!=', null);
            } elseif ($status === 'inactive') {
                $query->whereNull('email_verified_at');
            }

            // Paginate results
            $users = $query->paginate($perPage);

            // Transform data
            $transformedData = $users->getCollection()->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'barcode' => $user->barcode,
                    'phone' => $user->phone ?? null,
                    'alamat' => $user->alamat ?? null,
                    'is_verified' => $user->email_verified_at ? true : false,
                    'has_selfie' => $user->selfie ? true : false,
                    'has_img_base64' => $user->img_base64 ? true : false,
                    'roles' => $user->roles->pluck('name'),
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at
                ];
            });

            return response()->json([
                'status' => true,
                'message' => 'Data users berhasil diambil',
                'data' => $transformedData,
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total(),
                    'from' => $users->firstItem(),
                    'to' => $users->lastItem(),
                    'has_more_pages' => $users->hasMorePages(),
                    'next_page_url' => $users->nextPageUrl(),
                    'prev_page_url' => $users->previousPageUrl()
                ]
            ], 200);

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

    /**
     * Ambil detail lengkap user berdasarkan ID
     */
    public function getUserDetail($id)
    {
        try {
            $user = User::with(['roles', 'orderPaket.paket', 'bookingKelas.kelasActivity.kelas'])
                        ->find($id);
            
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User tidak ditemukan'
                ], 404);
            }

            // Hitung statistik user
            $totalOrderPaket = $user->orderPaket->count();
            $activePaket = $user->orderPaket->where('is_active', true)->where('status', 1)->count();
            $totalBookings = $user->bookingKelas->count();
            $attendedBookings = $user->bookingKelas->where('status_kehadiran', 1)->count();
            $missedBookings = $user->bookingKelas->where('status_kehadiran', 0)->count();

            // Transform data detail
            $userDetail = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'barcode' => $user->barcode,
                'phone' => $user->phone ?? null,
                'alamat' => $user->alamat ?? null,
                'tanggal_lahir' => $user->tanggal_lahir ?? null,
                'jenis_kelamin' => $user->jenis_kelamin ?? null,
                'is_verified' => $user->email_verified_at ? true : false,
                'email_verified_at' => $user->email_verified_at,
                'has_selfie' => $user->selfie ? true : false,
                'has_img_base64' => $user->img_base64 ? true : false,
                'selfie_url' => $user->selfie,
                'img_base64_url' => $user->img_base64,
                'roles' => $user->roles->map(function($role) {
                    return [
                        'id' => $role->id,
                        'name' => $role->name,
                        'guard_name' => $role->guard_name
                    ];
                }),
                'statistics' => [
                    'total_order_paket' => $totalOrderPaket,
                    'active_paket' => $activePaket,
                    'total_bookings' => $totalBookings,
                    'attended_bookings' => $attendedBookings,
                    'missed_bookings' => $missedBookings,
                    'attendance_rate' => $totalBookings > 0 ? round(($attendedBookings / $totalBookings) * 100, 2) : 0
                ],
                'recent_packages' => $user->orderPaket->sortByDesc('created_at')->take(5)->map(function($order) {
                    return [
                        'id' => $order->id,
                        'paket_name' => $order->paket->nama ?? 'Unknown',
                        'status' => $order->status,
                        'is_active' => $order->is_active,
                        'expired_date' => $order->expired_date,
                        'created_at' => $order->created_at
                    ];
                })->values(),
                'recent_bookings' => $user->bookingKelas->sortByDesc('created_at')->take(10)->map(function($booking) {
                    return [
                        'id' => $booking->id,
                        'kelas_name' => $booking->kelasActivity->kelas->nama_kelas ?? 'Unknown',
                        'tanggal' => $booking->kelasActivity->tanggal ?? null,
                        'jam_mulai' => $booking->kelasActivity->jam_mulai ?? null,
                        'jam_selesai' => $booking->kelasActivity->jam_selesai ?? null,
                        'status_kehadiran' => $booking->status_kehadiran,
                        'keterangan' => $booking->keterangan,
                        'created_at' => $booking->created_at
                    ];
                })->values(),
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at
            ];

            return response()->json([
                'status' => true,
                'message' => 'Detail user berhasil diambil',
                'data' => $userDetail
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $th->getMessage()
            ], 500);
        }
    }

    // ... sisanya dari method lama akan di-append
}
