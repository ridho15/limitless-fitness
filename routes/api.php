<?php

use App\Mail\RegistrasiKonfirmasiMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

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

Route::get('/send-email', function(){
    $sendEmail = new RegistrasiKonfirmasiMail;
    Mail::to('ralamzah@gmail.com')->send($sendEmail);
    return response()->json([
        'status' => true,
        'message' => 'Berhasil'
    ], 200);
});
