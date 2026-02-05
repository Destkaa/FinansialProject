<?php
use App\Http\Controllers\SaldoController;
use App\Http\Controllers\UangMasukController;
use App\Http\Controllers\UangKeluarController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// --- TARUH DI SINI (DI ATAS RESOURCE) ---
// Export Routes
Route::get('saldo/export-excel', [SaldoController::class, 'export_excel'])->name('saldo.export_excel');
Route::get('uangmasuk/export-excel', [UangMasukController::class, 'export_excel'])->name('uangmasuk.export_excel');
Route::get('uangkeluar/export-excel', [UangKeluarController::class, 'export_excel'])->name('uangkeluar.export_excel');

// Resource Routes
Route::resource('saldo', SaldoController::class);
Route::resource('uangmasuk', UangMasukController::class);
Route::resource('uangkeluar', UangKeluarController::class);
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');