<?php

use App\Imports\UsersImport;
use App\Mail\RegistrasiKonfirmasiMail;
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

Route::get('/send-email', function () {
    $sendEmail = new RegistrasiKonfirmasiMail(Auth::user());
    Mail::to('ralamzah@gmail.com')->send($sendEmail);
    return response()->json([
        'status' => true,
        'message' => 'Berhasil'
    ], 200);
});

Route::post('/import-user', function (Request $request) {
    $dataImport = $request->file('data');
    if ($request->hasFile('data')) {
        try {
            Excel::import(new UsersImport, $dataImport);

            return response()->json(['status' => true, 'message' => 'Berhasil import data'], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    } else {
        return response()->json([
            'status' => false,
            'message' => 'Data tidak terdeteksi',
        ], 404);
    }
});
