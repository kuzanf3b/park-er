<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\AreaParkirController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LogController;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard - accessible by all roles
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Transaksi page-based CRUD - admin only
    Route::middleware('role:admin')->group(function () {
        Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
        Route::get('/transaksi/masuk', [TransaksiController::class, 'createMasuk'])->name('transaksi.masuk');
        Route::post('/transaksi/masuk', [TransaksiController::class, 'storeMasuk'])->name('transaksi.store-masuk');
        Route::get('/transaksi/{id}/keluar', [TransaksiController::class, 'showKeluar'])->name('transaksi.keluar');
        Route::post('/transaksi/{id}/keluar', [TransaksiController::class, 'processKeluar'])->name('transaksi.process-keluar');
    });

    // Struk transaksi - admin & petugas
    Route::middleware('role:admin,petugas')->group(function () {
        Route::get('/transaksi/{id}/receipt', [TransaksiController::class, 'receipt'])->name('transaksi.receipt');
    });

    // AJAX operasional untuk petugas (single page workflow)
    Route::middleware('role:petugas')->group(function () {
        Route::get('/operasional/kilat', [TransaksiController::class, 'kilat'])->name('operasional.kilat');
        Route::post('/operasional/kilat', [TransaksiController::class, 'processKilat'])->name('operasional.process-kilat');
        Route::get('/operasional/aktif', [TransaksiController::class, 'aktifJson'])->name('operasional.aktif-json');
        Route::post('/operasional/masuk', [TransaksiController::class, 'storeMasukJson'])->name('operasional.store-masuk-json');
        Route::get('/operasional/{id}/preview-keluar', [TransaksiController::class, 'previewKeluarJson'])->name('operasional.preview-keluar-json');
        Route::post('/operasional/{id}/keluar', [TransaksiController::class, 'processKeluarJson'])->name('operasional.process-keluar-json');
    });

    // Kendaraan - admin, petugas, owner (owner read-only lewat controller + view)
    Route::middleware('role:admin,petugas,owner')->group(function () {
        Route::get('/kendaraan', [KendaraanController::class, 'index'])->name('kendaraan.index');
    });

    // Kendaraan CRUD - admin & petugas
    Route::middleware('role:admin,petugas')->group(function () {
        Route::get('/kendaraan/create', [KendaraanController::class, 'create'])->name('kendaraan.create');
        Route::post('/kendaraan', [KendaraanController::class, 'store'])->name('kendaraan.store');
        Route::get('/kendaraan/{id}/edit', [KendaraanController::class, 'edit'])->name('kendaraan.edit');
        Route::put('/kendaraan/{id}', [KendaraanController::class, 'update'])->name('kendaraan.update');
        Route::delete('/kendaraan/{id}', [KendaraanController::class, 'destroy'])->name('kendaraan.destroy');
    });

    // Area Parkir - admin only
    Route::middleware('role:admin')->group(function () {
        Route::get('/area-parkir', [AreaParkirController::class, 'index'])->name('area-parkir.index');
        Route::get('/area-parkir/create', [AreaParkirController::class, 'create'])->name('area-parkir.create');
        Route::post('/area-parkir', [AreaParkirController::class, 'store'])->name('area-parkir.store');
        Route::get('/area-parkir/{id}/edit', [AreaParkirController::class, 'edit'])->name('area-parkir.edit');
        Route::put('/area-parkir/{id}', [AreaParkirController::class, 'update'])->name('area-parkir.update');
        Route::delete('/area-parkir/{id}', [AreaParkirController::class, 'destroy'])->name('area-parkir.destroy');
    });

    // Tarif - admin only
    Route::middleware('role:admin')->group(function () {
        Route::get('/tarif', [TarifController::class, 'index'])->name('tarif.index');
        Route::get('/tarif/{id}/edit', [TarifController::class, 'edit'])->name('tarif.edit');
        Route::put('/tarif/{id}', [TarifController::class, 'update'])->name('tarif.update');
    });

    // Users - admin only
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // Laporan - admin & owner
    Route::middleware('role:admin,owner')->group(function () {
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    });

    // Log Aktivitas - admin only
    Route::middleware('role:admin')->group(function () {
        Route::get('/log-aktivitas', [LogController::class, 'index'])->name('log.index');
    });
});
