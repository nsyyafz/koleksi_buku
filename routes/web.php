<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\JsJqueryController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\VendorDashboardController;
use App\Http\Controllers\VendorMenuController;
use App\Http\Controllers\WilayahController;
use Illuminate\Support\Facades\Route;

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

// Barang (Tag Harga - Modul 3)
    Route::resource('barang', BarangController::class);
    Route::get('barang/cetak/index', [BarangController::class, 'cetakIndex'])->name('barang.cetak.index');
    Route::post('barang/cetak', [BarangController::class, 'cetak'])->name('barang.cetak');
    Route::get('/barang/tag-harga/{id}', [BarangController::class, 'generateTagHarga'])
    ->name('barang.tag-harga');

    // MODUL 4: JavaScript & jQuery
    Route::get('/js-jquery/crud-table', [JsJqueryController::class, 'crudTable'])
        ->name('js-jquery.crud-table');
        
    Route::get('/js-jquery/crud-datatables', [JsJqueryController::class, 'crudDatatables'])
        ->name('js-jquery.crud-datatables');
        
    Route::get('/js-jquery/select', [JsJqueryController::class, 'select'])
        ->name('js-jquery.select');
// MODUL 5
// Halaman tampilan
Route::get('/wilayah/ajax', [WilayahController::class, 'indexAjax'])
    ->name('wilayah.ajax');
    
Route::get('/wilayah/axios', [WilayahController::class, 'indexAxios'])
    ->name('wilayah.axios');
 
// API untuk AJAX request
Route::post('/wilayah/get-kota', [WilayahController::class, 'getKota'])
    ->name('wilayah.get-kota');
    
Route::post('/wilayah/get-kecamatan', [WilayahController::class, 'getKecamatan'])
    ->name('wilayah.get-kecamatan');
    
Route::post('/wilayah/get-kelurahan', [WilayahController::class, 'getKelurahan'])
    ->name('wilayah.get-kelurahan');

// POS Routes
Route::get('/pos/ajax',  [PosController::class, 'indexAjax'])->name('pos.ajax');
Route::get('/pos/axios', [PosController::class, 'indexAxios'])->name('pos.axios');
Route::post('/pos/get-barang', [PosController::class, 'getBarang'])->name('pos.get-barang');
Route::post('/pos/bayar',      [PosController::class, 'bayar'])->name('pos.bayar');
});


// ============================================================================
// PAYMENT GATEWAY - CUSTOMER (TANPA AUTH)
// ============================================================================

Route::get('/order', [CustomerOrderController::class, 'index'])
    ->name('customer.order');

Route::post('/order/get-menus', [CustomerOrderController::class, 'getMenus'])
    ->name('customer.get-menus');

Route::post('/order/create', [CustomerOrderController::class, 'createOrder'])
    ->name('customer.order.create');

Route::get('/order/receipt/{order_number}', [CustomerOrderController::class, 'receipt'])
    ->name('customer.receipt');


// ============================================================================
// PAYMENT GATEWAY - VENDOR (DENGAN AUTH)
// ============================================================================

Route::middleware(['auth'])->prefix('vendor')->name('vendor.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [VendorDashboardController::class, 'index'])
        ->name('dashboard');
    
    // Orders List
    Route::get('/orders', [VendorDashboardController::class, 'orders'])
        ->name('orders');

    Route::get('/qr-reader', [VendorDashboardController::class, 'qrReader'])
    ->name('qr-reader');

Route::get('/order-detail/{order_number}', [VendorDashboardController::class, 'getOrderByQr'])
    ->name('order-by-qr');
    
    Route::get('/orders/{order_number}', [VendorDashboardController::class, 'orderDetail'])
        ->name('orders.detail');
    
    // Menu CRUD
    Route::resource('menu', VendorMenuController::class);
    
    // Toggle menu availability
    Route::post('/menu/{id}/toggle', [VendorMenuController::class, 'toggleAvailability'])
        ->name('menu.toggle');
});

Route::post('/payment/notification', [PaymentController::class, 'notification'])
    ->name('payment.notification');

Route::post('/payment/check-status', [PaymentController::class, 'checkStatus'])
    ->name('payment.check-status');
    
// Barcode Reader
Route::get('/barcode-reader', [BarangController::class, 'barcodeReader'])->name('barcode.reader');
Route::get('/barcode-reader/cari', [BarangController::class, 'cariBarcodeBarang'])->name('barcode.cari');