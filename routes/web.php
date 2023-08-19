<?php

use App\Filament\Resources\BookingKelasResource;
use App\Filament\Resources\TimetableResource;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\ProfileController;
use App\Mail\RegistrasiKonfirmasiMail;
use App\Mail\SendKonfirmasiKehadiranMail;
use App\Models\BookingKelas;
use App\Models\KelasActivity;
use App\Models\User;
use Carbon\Carbon;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/testing', function () {
    $user = User::whereHas('customerJadwalStatic')
        ->with(['orderPaket' => function ($query) {
            $query->whereDate('expired_date', '>=', now())
                ->where('status', 1)
                ->where('is_active', 1)
                ->where('sisa', '>', 0);
        }])->whereHas('orderPaket')
        ->get();

    $listAdmin = User::whereHas('roles', function ($query) {
        $query->where(function ($query) {
            $query->where('name', 'Admin')
                ->orWhere('name', 'Super Admin');
        });
    })->get();

    foreach ($user as $item) {
        foreach ($item->customerJadwalStatic as $jadwal) {
            $tanggalNextWeek = Carbon::now()->addWeeks(1)
                ->startOfWeek($jadwal->hari + 1)->isoFormat('YYYY-MM-DD');
            $kelasActivity = KelasActivity::whereDate('tanggal', $tanggalNextWeek)
                ->where('jam_mulai', $jadwal->jam)->where('status', 1)->first();
            $jumlahPeserta = 0;

            if ($kelasActivity) {
                foreach ($kelasActivity->bookingKelas as $bookingKelas) {
                    if ($bookingKelas->status_kehadiran == '1' || $bookingKelas->status_kehadiran == '0') {
                        $jumlahPeserta++;
                    }
                }

                if ($kelasActivity->kelas->max_anggota > $jumlahPeserta) {
                    $bookingKelas = BookingKelas::updateOrCreate([
                        'id_user' => $item->id,
                        'id_kelas_activity' => $kelasActivity->id,
                    ], [
                        'id_user' => $item->id,
                        'id_kelas_activity' => $kelasActivity->id,
                        'is_send_email' => 1,
                        'status_kehadiran' => 1
                    ]);

                    foreach ($listAdmin as $admin) {
                        Notification::make()
                            ->title("Booking kelas")
                            ->body($kelasActivity->kelas->nama_kelas . " baru saja di booking oleh " . $item->name . " secara automatis melalui sistem")
                            ->actions([
                                Action::make('view')
                                    ->button()
                                    ->icon('heroicon-o-eye')
                                    ->url(BookingKelasResource::getUrl('view', ['record' => $bookingKelas]))
                            ])->sendToDatabase($admin);
                    }
                }
            }
        }
    }

    return response()->json([
        'status' => true,
        'message' => 'Berhasil'
    ]);
});
Route::get('/', [BaseController::class, 'index'])->name('base');
Route::get('/class', [BaseController::class, 'class'])->name('class');
Route::get('/detail-class/{id}', [BaseController::class, 'detailClass'])->name('detail-class');
Route::get('/packages', [BaseController::class, 'packages'])->name('packages');
Route::get('/detail-packages/{id}', [BaseController::class, 'detailPackages'])->name('detail-packages');
Route::get('/timetable', [BaseController::class, 'timetable'])->name('timetable');
Route::get('/location', [BaseController::class, 'location'])->name('location');
Route::get('/detail-location/{id}', [BaseController::class, 'detailLocation'])->name('detail-location');
Route::get('/contactus', [BaseController::class, 'contactus'])->name('contactus');
Route::get('/login', [BaseController::class, 'login'])->name('login');
Route::post('/post-login', [BaseController::class, 'postLogin'])->name('postLogin');
Route::get('/registrasi', [BaseController::class, 'registrasi'])->name('registrasi');
Route::post('/post-registrasi', [BaseController::class, 'postRegistrasi'])->name('postRegistrasi');
Route::get('/term-and-condition', [BaseController::class, 'termCondition'])->name('term-condition');
Route::get('/konfirm-akun/{id_user}', [BaseController::class, 'konfirmasiAkun'])->name('konfirm-akun');
Route::get('/konfirmasi-kehadiran', [BaseController::class, 'konfirmasiKehadiran'])->name('konfirmasi-kehadiran');
Route::get('/simpan-keterangan/{id_booking_kelas}', [BaseController::class, 'simpanKeterangan'])->name('simpan-keterangan');
Route::middleware('auth')->group(function () {
    Route::prefix('order-kelas')->group(function () {
        Route::get('/', [BaseController::class, 'orderKelas'])->name('order-kelas');
        Route::post('/order-kelas/{id_kelas_activity}', [BaseController::class, 'postOrderKelas'])->name('order-kelas.post');
    });
    Route::post('/update-profile', [BaseController::class, 'updateProfile'])->name('updateProfile');
    Route::post('/logout', [BaseController::class, 'logout'])->name('logout');
    Route::post('/konfirm-pembayaran-paket/{paket}', [BaseController::class, 'confirmPembayaranPaket'])->name('konfirm-pembayaran-paket');
    Route::get('/detail-timetable/{id}', [BaseController::class, 'detailTimetable'])->name('detail-timetable');
    Route::prefix('profile')->group(function () {
        Route::get('/invoice/{id}', [ProfileController::class, 'invoice'])->name('profile.invoice');
        Route::get('/', [ProfileController::class, 'index'])->name('profile');
        Route::prefix('booking')->group(function () {
            Route::get('/', [ProfileController::class, 'bookings'])->name('profile.bookings');
            Route::post('/update-kehadiran/{id_booking_kelas}', [ProfileController::class, 'updateKehadiran'])->name('profile.bookings.update-kehadiran');
            Route::post('/batal/{id}', [ProfileController::class, 'batalBookingKelas'])->name('profile.bookings.batal');
        });
        Route::get('/packages', [ProfileController::class, 'packages'])->name('profile.packages');
        Route::get('/memberships', [ProfileController::class, 'memberships'])->name('profile.memberships');
        Route::get('/billing', [ProfileController::class, 'billing'])->name('profile.billing');
        Route::get('/notification-settings', [ProfileController::class, 'notificationSettings'])->name('profile.notification-settings');
        Route::get('/waiver-form', [ProfileController::class, 'waiverForm'])->name('profile.waiver-form');
        Route::post('/post-customer-jadwal', [ProfileController::class, 'postCustomerJadwal'])->name('profile.post-customer-jadwal');
    });
    Route::post('upload-bukti-pembayaran/{id}', [BaseController::class, 'uploadBuktiPembayaran'])->name('upload-bukti-pembayaran');
    Route::get('resend-verifikasi-email/{id}', [ProfileController::class, 'resendVerifikasiEmail'])->name('profile.resend-verifikasi-email');
});
