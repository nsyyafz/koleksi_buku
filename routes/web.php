<?php

use App\Http\Controllers\BukuController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategoriController;
use Illuminate\Support\Facades\Route;

// Halaman publik (tidak perlu login)
Route::get('/', function () {
    return redirect('/login');
});

// Route authentication (login/register) - otomatis dari Laravel UI
Auth::routes();

// Route yang WAJIB LOGIN (pakai middleware auth)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // CRUD Kategori
    Route::resource('kategori', KategoriController::class);
    
    // CRUD Buku
    Route::resource('buku', BukuController::class);
});