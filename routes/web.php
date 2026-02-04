<?php
use App\Http\Controllers\SaldoController;
use App\Http\Controllers\UangMasukController;
use App\Http\Controllers\UangKeluarController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('saldo', SaldoController::class);
Route::resource('uangmasuk', UangMasukController::class);
Route::resource('uangkeluar', UangKeluarController::class);
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
