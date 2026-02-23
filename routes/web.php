<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;

// Redirect root ke login
Route::get('/', function () {
    return redirect('/login');
});

// Laravel UI Auth Routes (login, register, dll)
Auth::routes();

// Google OAuth Routes
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

// OTP Routes
Route::get('otp', [OtpController::class, 'showOtpForm'])->name('otp.form');
Route::post('otp/verify', [OtpController::class, 'verifyOtp'])->name('otp.verify');
Route::get('otp/resend', [OtpController::class, 'resendOtp'])->name('otp.resend');

// Protected Routes (harus login)
Route::middleware(['auth'])->group(function () {
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('kategori', KategoriController::class);
Route::resource('buku', BukuController::class);

 // PDF Routes
Route::get('/pdf', [App\Http\Controllers\PdfController::class, 'index'])->name('pdf.index');
Route::get('/pdf/sertifikat', [App\Http\Controllers\PdfController::class, 'generateSertifikat'])->name('pdf.sertifikat');
Route::get('/pdf/undangan', [App\Http\Controllers\PdfController::class, 'generateUndangan'])->name('pdf.undangan');
});