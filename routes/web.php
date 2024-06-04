<?php

use App\Http\Controllers\BaseController;
use App\Http\Controllers\HelperController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use Faker\Factory;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('cache_clear', function () {
    Artisan::command('cache:clear', function () {
        $this->info('cache clear');
    });
    return 'Cache Clear';
});

Route::get('config_clear', function () {
    Artisan::command('config:clear', function () {
        $this->info('config_clear');
    });
    return 'Config Clear';
});

Route::get('view:clear', function () {
    Artisan::command('view:clear');
    return 'View Clear';
});

Route::get('optimize', function () {
    Artisan::command('optimize');
    return 'Optimize';
});

Route::get('migrate', function () {
    Artisan::command('migrate');
    return 'Migrate';
});

Route::get('optimize_clear', function () {
    Artisan::command('optimize:clear');
    return 'Optimize Clear';
});

Route::get('storage_link', function () {
    Artisan::command('storage:link');
    return 'Create storage link';
});

Route::get('generate-password', [HelperController::class, 'generatePassword']);
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
Route::get('/forgot-password', [BaseController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('/post-login', [BaseController::class, 'postLogin'])->name('postLogin');
Route::post('post-forgot-password', [BaseController::class, 'postForgotPassword'])->name('postForgotPassword');
Route::get('/registrasi', [BaseController::class, 'registrasi'])->name('registrasi');
Route::post('/post-registrasi', [BaseController::class, 'postRegistrasi'])->name('postRegistrasi');
Route::get('/registration-success', [BaseController::class, 'registrationSuccess'])->name('registrationSuccess');
Route::get('/check-token', [BaseController::class, 'checkToken'])->name('checkToken');
Route::post('/update-password', [BaseController::class, 'updatePassword'])->name('update-password');
Route::get('/term-and-condition', [BaseController::class, 'termCondition'])->name('term-condition');
Route::get('/konfirm-akun/{id_user}', [BaseController::class, 'konfirmasiAkun'])->name('konfirm-akun');
Route::get('/konfirmasi-kehadiran', [BaseController::class, 'konfirmasiKehadiran'])->name('konfirmasi-kehadiran');
Route::get('/simpan-keterangan/{id_booking_kelas}', [BaseController::class, 'simpanKeterangan'])->name('simpan-keterangan');
Route::prefix('kelas')->group(function () {
    Route::get('jadwal', [KelasController::class, 'kelasJadwal'])->name('kelas-jadwal');
    Route::get('list', [KelasController::class, 'kelasList'])->name('kelas-list');
});
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
        Route::get('/', [ProfileController::class, 'index'])->name('profile');
        Route::get('webcame', [ProfileController::class, 'webcame'])->name('profile.webcame');
        Route::post('upload', [ProfileController::class, 'upload'])->name('profile.upload');
        Route::get('/invoice/{id}', [ProfileController::class, 'invoice'])->name('profile.invoice');
        Route::prefix('booking')->group(function () {
            Route::get('/', [ProfileController::class, 'bookings'])->name('profile.bookings');
            Route::post('/update-kehadiran/{id_booking_kelas}', [ProfileController::class, 'updateKehadiran'])->name('profile.bookings.update-kehadiran');
            Route::post('/batal/{id}', [ProfileController::class, 'batalBookingKelas'])->name('profile.bookings.batal');
        });
        Route::get('/packages', [ProfileController::class, 'packages'])->name('profile.packages');
        Route::get('/memberships', [ProfileController::class, 'memberships'])->name('profile.memberships');
        Route::get('/billing', [ProfileController::class, 'billing'])->name('profile.billing');
        Route::get('/voucher', [ProfileController::class, 'voucher'])->name('profile.voucher');
        Route::get('/notification-settings', [ProfileController::class, 'notificationSettings'])->name('profile.notification-settings');
        Route::get('/waiver-form', [ProfileController::class, 'waiverForm'])->name('profile.waiver-form');
        Route::post('/post-customer-jadwal', [ProfileController::class, 'postCustomerJadwal'])->name('profile.post-customer-jadwal');
    });
    Route::post('upload-bukti-pembayaran/{id}', [BaseController::class, 'uploadBuktiPembayaran'])->name('upload-bukti-pembayaran');
    Route::get('resend-verifikasi-email/{id}', [ProfileController::class, 'resendVerifikasiEmail'])->name('profile.resend-verifikasi-email');
    Route::delete('/hapus-repetitif-time/{id}', [ProfileController::class, 'hapusRepetitifTime'])->name('profile.hapus-repetitif-time');
});
