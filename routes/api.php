<?php

use App\Http\Controllers\Api\PaketController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\HelperController;
use App\Imports\ImportBookingKelas;
use App\Imports\OrderPaketImport;
use App\Imports\UsersImport;
use App\Mail\RegistrasiKonfirmasiMail;
use App\Models\OrderPaket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/send-email', [HelperController::class, 'sendEmail']);

Route::post('/import-user', [HelperController::class, 'importUser']);

Route::post('/import-user-paket', [HelperController::class, 'importUserPaket']);

Route::post('/import-booking-kelas', [HelperController::class, 'importBookingKelas']);

Route::post('/check-paket', [PaketController::class, 'checkPaket']);

Route::post('/validate-face', [HelperController::class, 'validateFace']);


Route::get('/user-selfie/{id}', [HelperController::class, 'getUserSelfie']);
Route::get('/user-selfies', [HelperController::class, 'getAllUserSelfies']);

Route::get('/users', [HelperController::class, 'getUsers']);
Route::get('/user/{id}/detail', [HelperController::class, 'getUserDetail']);

Route::post('/register-face', [HelperController::class, 'registerFace']);

Route::post('/checkin', [CheckInController::class, 'store']);
