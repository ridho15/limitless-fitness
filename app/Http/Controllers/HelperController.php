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

            // Transform data manually
            $items = [];
            foreach ($users->items() as $user) {
                $items[] = [
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
            }

            return response()->json([
                'status' => true,
                'message' => 'Data users berhasil diambil',
                'data' => $items,
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

    public function sendEmail(Request $request)
    {
        $sendEmail = new RegistrasiKonfirmasiMail(Auth::user());
        Mail::to('ralamzah@gmail.com')->send($sendEmail);
        return response()->json([
            'status' => true,
            'message' => 'Berhasil'
        ], 200);
    }

    public function importUser(Request $request)
    {
        $dataImport = $request->file('data');
        if ($request->hasFile('data')) {
            try {
                Excel::import(new UsersImport, $dataImport);

                return response()->json(['status' => true, 'message' => 'Berhasil import data'], 200);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => false,
                    'message' => "Test" . $th->getMessage()
                ], 500);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak terdeteksi',
            ], 404);
        }
    }

    public function importUserPaket(Request $request)
    {
        $dataImport = $request->file('data');
        if ($request->hasFile('data')) {
            try {
                Excel::import(new OrderPaketImport, $dataImport);
                return response()->json(['status' => true, 'message' => 'Berhasil import data'], 200);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak terdeteksi',
            ], 404);
        }
    }

    public function importBookingKelas(Request $request)
    {
        $dataImport = $request->file('data');
        if ($request->hasFile('data')) {
            try {
                Excel::import(new ImportBookingKelas, $dataImport);
                return response()->json(['status' => true, 'message' => 'Berhasil import data'], 200);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak terdeteksi',
            ], 404);
        }
    }

    public function validateFace(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'face_image' => 'required|string'
            ]);

            $user = User::find($request->user_id);
            
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User tidak ditemukan'
                ], 404);
            }

            if (!$user->selfie && !$user->img_base64) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data wajah referensi tidak ditemukan'
                ], 400);
            }

            $faceImage = $request->face_image;
            $referenceImage = $user->img_base64 ?: $user->selfie;

            $similarity = $this->compareFaces($faceImage, $referenceImage);
            
            $threshold = 0.8;
            $isValid = $similarity >= $threshold;

            return response()->json([
                'status' => true,
                'data' => [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'is_valid' => $isValid,
                    'similarity_score' => $similarity,
                    'threshold' => $threshold,
                    'message' => $isValid ? 'Validasi wajah berhasil' : 'Validasi wajah gagal'
                ]
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $th->getMessage()
            ], 500);
        }
    }

    public function registerFace(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'face_image' => 'required|string',
                'image_type' => 'nullable|in:selfie,img_base64'
            ], [
                'user_id.required' => 'Data user belum dikirim',
                'user_id.exists' => 'User tidak ditemukan',
                'face_image.required' => 'Gambar wajah belum dikirim',
                'face_image.string' => 'Format gambar wajah tidak valid',
                'image_type.in' => 'Tipe gambar harus selfie atau img_base64'
            ]);

            $user = User::find($request->user_id);
            
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User tidak ditemukan'
                ], 404);
            }

            // Validate base64 image format
            $faceImage = $request->face_image;
            if (!$this->isValidBase64Image($faceImage)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Format gambar tidak valid. Gunakan format base64 yang benar.'
                ], 400);
            }

            // Determine which field to update
            $imageType = $request->image_type ?: 'img_base64';
            
            // Update user's face data
            $user->update([
                $imageType => $faceImage
            ]);

            // Log activity
            activity()
                ->causedBy($user)
                ->withProperties(['image_type' => $imageType])
                ->log('Melakukan registrasi wajah');

            return response()->json([
                'status' => true,
                'message' => 'Registrasi wajah berhasil',
                'data' => [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'image_type' => $imageType,
                    'registered_at' => now()->toISOString()
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

    private function compareFaces($faceImage1, $faceImage2)
    {
        try {
            $image1Data = $this->extractImageFeatures($faceImage1);
            $image2Data = $this->extractImageFeatures($faceImage2);
            
            if (!$image1Data || !$image2Data) {
                return 0;
            }

            $similarity = $this->calculateSimilarity($image1Data, $image2Data);
            
            return $similarity;
        } catch (\Throwable $th) {
            return 0;
        }
    }

    private function extractImageFeatures($base64Image)
    {
        try {
            $imageData = base64_decode(preg_replace('#^data:image/[^;]+;base64,#', '', $base64Image));
            
            if (!$imageData) {
                return null;
            }

            $tempFile = tempnam(sys_get_temp_dir(), 'face_validation_');
            file_put_contents($tempFile, $imageData);
            
            $imageInfo = getimagesize($tempFile);
            
            if (!$imageInfo) {
                unlink($tempFile);
                return null;
            }

            $features = [
                'width' => $imageInfo[0],
                'height' => $imageInfo[1],
                'size' => filesize($tempFile),
                'hash' => md5($imageData)
            ];

            unlink($tempFile);
            
            return $features;
        } catch (\Throwable $th) {
            return null;
        }
    }

    private function calculateSimilarity($features1, $features2)
    {
        try {
            if ($features1['hash'] === $features2['hash']) {
                return 1.0;
            }

            $sizeRatio = min($features1['size'], $features2['size']) / max($features1['size'], $features2['size']);
            $dimensionRatio = min(
                ($features1['width'] * $features1['height']),
                ($features2['width'] * $features2['height'])
            ) / max(
                ($features1['width'] * $features1['height']),
                ($features2['width'] * $features2['height'])
            );

            $similarity = ($sizeRatio + $dimensionRatio) / 2;
            
            return round($similarity, 3);
        } catch (\Throwable $th) {
            return 0;
        }
    }

    private function isValidBase64Image($base64String)
    {
        try {
            // Check if it's a valid base64 string
            if (!preg_match('/^data:image\/[a-zA-Z]+;base64,/', $base64String)) {
                return false;
            }

            // Extract and decode the base64 data
            $imageData = base64_decode(preg_replace('#^data:image/[^;]+;base64,#', '', $base64String));
            
            if (!$imageData) {
                return false;
            }

            // Create temporary file to validate image
            $tempFile = tempnam(sys_get_temp_dir(), 'face_register_');
            file_put_contents($tempFile, $imageData);
            
            // Check if it's a valid image
            $imageInfo = getimagesize($tempFile);
            
            // Clean up
            unlink($tempFile);
            
            return $imageInfo !== false;
        } catch (\Throwable $th) {
            return false;
        }
    }

    // ... sisanya dari method lama akan di-append
}
