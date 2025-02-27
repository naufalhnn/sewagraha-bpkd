<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [AppController::class, 'index'])->name('home');
Route::get('/details/{id}', [AppController::class, 'details'])->name('details');


Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [AppController::class, 'index'])->name('home');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/payment/{id}', [PaymentController::class, 'index'])->name('payment.create');
});

// Dashboard redirect based on role
// Route::get('/admin', function () {
//     if (Auth::user()->role === 'ADMIN') {
//         return redirect()->route('admin');
//     } else {
//         return redirect()->route('/');
//     }
// })->middleware('isAdmin')->name('dashboard');
