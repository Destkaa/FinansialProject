<?php
use App\Http\Controllers\SaldoController;
use App\Http\Controllers\UangMasukController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('saldo', SaldoController::class);
Route::resource('uangmasuk', UangMasukController::class);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
