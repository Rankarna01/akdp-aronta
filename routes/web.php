<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Di sini adalah pusat routing aplikasi kita. Semua route dikelompokkan
| berdasarkan role (Super Admin, Supir, Customer) menggunakan middleware.
|--------------------------------------------------------------------------
*/

// Redirect halaman utama ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// =================================================================
// 1. ROUTE AUTENTIKASI (GUEST & AUTH)
// =================================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


// =================================================================
// 2. ROUTE SUPER ADMIN
// =================================================================
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Admin (Sementara pakai closure sebelum Controller dibuat)
  Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Nanti Controller Module Admin akan masuk di bawah ini:
    // Route::resource('armada', ArmadaController::class);
    // Route::resource('supir', SupirController::class);
    // Route::resource('rute', RuteController::class);
    // Route::resource('kursi', KursiController::class);
    // Route::resource('jadwal', JadwalController::class);
    // Route::resource('penumpang', PenumpangController::class);
    // ... dsb
});


// =================================================================
// 3. ROUTE DRIVER (SUPIR)
// =================================================================
Route::middleware(['auth', 'role:driver'])->prefix('driver')->name('driver.')->group(function () {
    
    // Dashboard Supir (Sementara pakai closure)
    Route::get('/dashboard', function () {
        return "<h1>Selamat Datang di Dashboard Supir</h1><form action='".route('logout')."' method='POST'>".csrf_field()."<button type='submit'>Logout</button></form>";
    })->name('dashboard');

    // Nanti Controller Module Supir akan masuk di bawah ini:
    // Route::get('jadwal', [JadwalDriverController::class, 'index'])->name('jadwal.index');
    // Route::get('penumpang', [PenumpangDriverController::class, 'index'])->name('penumpang.index');
    // ... dsb
});


// =================================================================
// 4. ROUTE CUSTOMER
// =================================================================
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    
    // Home Customer (Sementara pakai closure)
    Route::get('/home', function () {
        return "<h1>Selamat Datang di Halaman Customer</h1><form action='".route('logout')."' method='POST'>".csrf_field()."<button type='submit'>Logout</button></form>";
    })->name('home');

    // Nanti Controller Module Customer akan masuk di bawah ini:
    // Route::get('cari-tiket', [TiketController::class, 'search'])->name('tiket.search');
    // Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    // ... dsb
});