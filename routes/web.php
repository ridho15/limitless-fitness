<?php

use App\Http\Controllers\BaseController;
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

Route::get('/', [BaseController::class, 'index'])->name('base');
Route::get('/class', [BaseController::class, 'class'])->name('class');
Route::get('/packages', [BaseController::class, 'packages'])->name('packages');
Route::get('/timetable', [BaseController::class, 'timetable'])->name('timetable');
Route::get('/login', [BaseController::class, 'login'])->name('login');
Route::post('/post-login', [BaseController::class, 'postLogin'])->name('postLogin');
Route::get('/registrasi', [BaseController::class, 'registrasi'])->name('registrasi');
Route::post('/post-registrasi', [BaseController::class, 'postRegistrasi'])->name('postRegistrasi');
Route::middleware('auth')->group(function(){
    Route::get('/order-kelas', [BaseController::class, 'orderKelas'])->name('order-kelas');
    Route::get('/profile', [BaseController::class, 'profile'])->name('profile');
    Route::post('/update-profile', [BaseController::class, 'updateProfile'])->name('updateProfile');
    Route::post('/logout', [BaseController::class, 'logout'])->name('logout');
});
