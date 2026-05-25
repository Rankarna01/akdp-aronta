<?php

use Illuminate\Support\Facades\Route;
use App\Models\Rute;
use App\Models\Jadwal;
use App\Models\Armada;

// Import Controller Auth
use App\Http\Controllers\Auth\AuthController;

// Import Controller Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ArmadaController;
use App\Http\Controllers\Admin\SupirController;
use App\Http\Controllers\Admin\RuteController;
use App\Http\Controllers\Admin\KursiController; 
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\PenumpangController;
use App\Http\Controllers\Admin\TiketController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\TiketDigitalController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\LogAktivitasController;
use App\Http\Controllers\Admin\MetodePembayaranMasterController;

// Import Controller Driver
use App\Http\Controllers\Driver\DashboardController as DriverDashboardController;
use App\Http\Controllers\Driver\PerjalananController as DriverPerjalananController;
use App\Http\Controllers\Driver\PenumpangController as DriverPenumpangController;
use App\Http\Controllers\Driver\StatusController as DriverStatusController;
use App\Http\Controllers\Driver\ProfileController as DriverProfileController;

// Import Controller Customer
use App\Http\Controllers\Customer\HomeController as CustomerHomeController;
use App\Http\Controllers\Customer\TiketController as CustomerTiketController;
use App\Http\Controllers\Customer\CheckoutController as CustomerCheckoutController;
use App\Http\Controllers\Customer\TiketSayaController as CustomerTiketSayaController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;

// =================================================================
// 0. LANDING PAGE (WEBSITE PUBLIK)
// =================================================================
Route::get('/', function () {
    // Tarik data dinamis untuk ditampilkan di halaman depan
    $rutePopuler = Rute::where('status', 'Aktif')->limit(6)->get();
    
    $jadwalTerdekat = Jadwal::with(['rute', 'armada'])
        ->where('status', 'Menunggu')
        ->whereDate('tanggal', '>=', now())
        ->orderBy('tanggal', 'asc')
        ->limit(6)->get();
        
    $armadaBus = Armada::where('status', 'Aktif')->get();

    return view('welcome', compact('rutePopuler', 'jadwalTerdekat', 'armadaBus'));
})->name('landing');

// =================================================================
// 1. ROUTE AUTENTIKASI (LOGIN & REGISTER)
// =================================================================
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
    
    // Register (Khusus Customer)
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


// =================================================================
// 2. ROUTE SUPER ADMIN
// =================================================================
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Modul Armada
    Route::get('/armada/data', [ArmadaController::class, 'data'])->name('armada.data');
    Route::resource('armada', ArmadaController::class)->except(['create', 'show']);

    // Modul Supir
    Route::get('/supir/data', [SupirController::class, 'data'])->name('supir.data');
    Route::resource('supir', SupirController::class)->except(['create', 'show']);

    // Modul Rute
    Route::get('/rute/data', [RuteController::class, 'data'])->name('rute.data');
    Route::resource('rute', RuteController::class)->except(['create', 'show']);

    // Modul Kursi (Perhatikan urutan route custom selalu di atas route resource)
    Route::get('/kursi/data', [KursiController::class, 'data'])->name('kursi.data');
    Route::post('/kursi/generate', [KursiController::class, 'generate'])->name('kursi.generate');
    Route::get('/kursi/layout/{armada_id}', [KursiController::class, 'getLayout'])->name('kursi.layout');
    Route::resource('kursi', KursiController::class)->except(['create', 'show']);

    // Modul Metode Pembayaran Master
    Route::get('/metode-pembayaran-master/data', [MetodePembayaranMasterController::class, 'data'])->name('metode-pembayaran-master.data');
    Route::resource('metode-pembayaran-master', MetodePembayaranMasterController::class)->except(['create', 'show']);

    // Modul Jadwal
    Route::get('/jadwal/data', [JadwalController::class, 'data'])->name('jadwal.data');
    Route::resource('jadwal', JadwalController::class)->except(['create', 'show']);

    // Modul Monitoring
    Route::get('/monitoring/data', [MonitoringController::class, 'data'])->name('monitoring.data');
    Route::resource('monitoring', MonitoringController::class)->except(['create', 'show']);

    // Modul Penumpang
    Route::get('/penumpang/data', [PenumpangController::class, 'data'])->name('penumpang.data');
    Route::resource('penumpang', PenumpangController::class)->except(['create', 'show']);

    // Modul Tiket
    Route::get('/tiket/data', [TiketController::class, 'data'])->name('tiket.data');
    Route::get('/tiket/get-kursi/{jadwal_id}', [TiketController::class, 'getKursiAvailable'])->name('tiket.get-kursi');
    Route::resource('tiket', TiketController::class)->except(['create', 'show']);

    // Modul Pembayaran
    Route::get('/pembayaran/data', [PembayaranController::class, 'data'])->name('pembayaran.data');
    Route::resource('pembayaran', PembayaranController::class)->except(['create', 'show']);

    // Modul Tiket Digital
    Route::get('/tiket-digital', [TiketDigitalController::class, 'index'])->name('tiket-digital.index');
    Route::get('/tiket-digital/data', [TiketDigitalController::class, 'data'])->name('tiket-digital.data');
    Route::get('/tiket-digital/{id}/cetak', [TiketDigitalController::class, 'cetak'])->name('tiket-digital.cetak');

    // Modul Laporan
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/perjalanan', [LaporanController::class, 'perjalanan'])->name('perjalanan');
        Route::get('/perjalanan/data', [LaporanController::class, 'perjalananData'])->name('perjalanan.data');
        Route::get('/perjalanan/cetak', [LaporanController::class, 'cetakPerjalanan'])->name('perjalanan.cetak');
        
        Route::get('/transaksi', [LaporanController::class, 'transaksi'])->name('transaksi');
        Route::get('/transaksi/data', [LaporanController::class, 'transaksiData'])->name('transaksi.data');
        Route::get('/transaksi/cetak', [LaporanController::class, 'cetakTransaksi'])->name('transaksi.cetak');
        
        Route::get('/penumpang', [LaporanController::class, 'penumpang'])->name('penumpang');
        Route::get('/penumpang/data', [LaporanController::class, 'penumpangData'])->name('penumpang.data');
        Route::get('/penumpang/cetak', [LaporanController::class, 'cetakPenumpang'])->name('penumpang.cetak');
    });

    // Modul Log Aktivitas
    Route::get('/log-aktivitas', [LogAktivitasController::class, 'index'])->name('log-aktivitas.index');
    Route::get('/log-aktivitas/data', [LogAktivitasController::class, 'data'])->name('log-aktivitas.data');
    Route::delete('/log-aktivitas/clear', [LogAktivitasController::class, 'clearAll'])->name('log-aktivitas.clear');

});


// =================================================================
// 3. ROUTE DRIVER (SUPIR)
// =================================================================
Route::middleware(['auth', 'role:driver'])->prefix('driver')->name('driver.')->group(function () {
    
    // Halaman Utama & Perjalanan
    Route::get('/dashboard', [DriverDashboardController::class, 'index'])->name('dashboard');
    Route::get('/perjalanan', [DriverPerjalananController::class, 'index'])->name('perjalanan.index');
    
    // Modul Daftar Penumpang (Check-In)
    Route::get('/penumpang', [DriverPenumpangController::class, 'index'])->name('penumpang.index');
    Route::get('/penumpang/data', [DriverPenumpangController::class, 'data'])->name('penumpang.data');
    Route::post('/penumpang/toggle-checkin/{id}', [DriverPenumpangController::class, 'toggleCheckin'])->name('penumpang.toggle-checkin');
    
    // Modul Update Status Lokasi
    Route::get('/status', [DriverStatusController::class, 'index'])->name('status.index');
    Route::post('/status/update', [DriverStatusController::class, 'store'])->name('status.store');
    
    // Modul Profile Akun
    Route::get('/profile', [DriverProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/change-password', [DriverProfileController::class, 'changePassword'])->name('profile.change-password');

});


// =================================================================
// 4. ROUTE CUSTOMER (PENUMPANG)
// =================================================================
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    
    // Halaman Utama
    Route::get('/home', [CustomerHomeController::class, 'index'])->name('home');
    
    // Modul Pencarian & Pemilihan Kursi
    Route::get('/cari-tiket', [CustomerTiketController::class, 'index'])->name('tiket.index');
    Route::get('/hasil-pencarian', [CustomerTiketController::class, 'search'])->name('tiket.search');
    Route::get('/pilih-kursi/{jadwal_id}', [CustomerTiketController::class, 'pilihKursi'])->name('tiket.pilih-kursi');
    
    // Modul Checkout & Pembayaran
    Route::get('/checkout', [CustomerCheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/proses', [CustomerCheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/pembayaran/{tiket_id}', [CustomerCheckoutController::class, 'pembayaran'])->name('checkout.pembayaran');
    Route::post('/pembayaran/{tiket_id}/proses', [CustomerCheckoutController::class, 'prosesPembayaran'])->name('checkout.proses-pembayaran');
    
    // Modul Tiket Saya (E-Ticket)
    Route::get('/tiket-saya', [CustomerTiketSayaController::class, 'index'])->name('tiket-saya.index');
    Route::get('/tiket-saya/data', [CustomerTiketSayaController::class, 'data'])->name('tiket-saya.data');
    Route::get('/tiket-saya/{id}/detail', [CustomerTiketSayaController::class, 'show'])->name('tiket-saya.show');
    Route::get('/tiket-saya/{id}/cetak', [CustomerTiketSayaController::class, 'cetak'])->name('tiket-saya.cetak');
    
    // Modul Profile Akun
    Route::get('/akun', [CustomerProfileController::class, 'index'])->name('akun.index');
    Route::post('/akun/change-password', [CustomerProfileController::class, 'changePassword'])->name('akun.change-password');

});