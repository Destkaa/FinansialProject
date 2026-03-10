<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UangMasuk;
use App\Models\UangKeluar;
use App\Models\Saldo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     */
   public function index()
{
    $userId = auth()->id();

    // 1. Hitung Total untuk Card Dashboard (Variabel yang tadinya hilang)
    $totalPemasukan = \App\Models\UangMasuk::where('id_user', $userId)->sum('nominal');
    $totalPengeluaran = \App\Models\UangKeluar::where('id_user', $userId)->sum('nominal');
    $totalSaldo = \App\Models\Saldo::where('id_user', $userId)->sum('total');

    // 2. Ambil List Transaksi untuk Tabel (Sertakan 'id' agar tidak error destroy)
    $pemasukan = \App\Models\UangMasuk::where('id_user', $userId)
        ->select('id', 'nominal', 'keterangan', 'tanggal_uang_masuk as tanggal', \DB::raw("'Pemasukan' as kategori"))
        ->get();

    $pengeluaran = \App\Models\UangKeluar::where('id_user', $userId)
        ->select('id', 'nominal', 'keterangan', 'tanggal_uang_keluar as tanggal', \DB::raw("'Pengeluaran' as kategori"))
        ->get();

    // Gabungkan dan urutkan transaksi terbaru
    $transactions = $pemasukan->concat($pengeluaran)->sortByDesc('tanggal')->take(10);

    // 3. Kirim SEMUA variabel ke view
    return view('home', compact(
        'totalPemasukan', 
        'totalPengeluaran', 
        'totalSaldo', 
        'transactions'
    ));
}
}