<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SaldoController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UangMasukController;
use App\Http\Controllers\UangKeluarController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {

    // --- DASHBOARD ---
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // --- PROFILE ---
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
        Route::delete('/avatar', [ProfileController::class, 'destroyAvatar'])->name('avatar.destroy');
        Route::delete('/delete', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // --- TRANSAKSI & SALDO (Akses Semua Role) ---
    Route::resource('uangmasuk', UangMasukController::class)->except(['destroy']);
    Route::resource('uangkeluar', UangKeluarController::class)->except(['destroy']);
    
    // Saldo sekarang bisa diakses User, tapi tanpa fitur hapus
    Route::resource('saldo', SaldoController::class)->except(['destroy']);

    // --- KHUSUS ADMIN ---
    Route::middleware(['role:admin'])->group(function () {
        // Fitur Hapus Transaksi & Saldo hanya untuk Admin
        Route::delete('/uangmasuk/{uangmasuk}', [UangMasukController::class, 'destroy'])->name('uangmasuk.destroy');
        Route::delete('/uangkeluar/{uangkeluar}', [UangKeluarController::class, 'destroy'])->name('uangkeluar.destroy');
        Route::delete('/saldo/{saldo}', [SaldoController::class, 'destroy'])->name('saldo.destroy');

        // Export Data
        Route::prefix('export')->name('export.')->group(function () {
            Route::get('/saldo', [SaldoController::class, 'export_excel'])->name('saldo'); 
            Route::get('/uang-masuk', [UangMasukController::class, 'export_excel'])->name('uangmasuk');
            Route::get('/uang-keluar', [UangKeluarController::class, 'export_excel'])->name('uangkeluar');
        });

        // Audit & History
        Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
        // --- TAMBAHKAN BARIS DI BAWAH INI ---
        Route::delete('/history/clear', [HistoryController::class, 'clear'])->name('history.clear');
    });
});