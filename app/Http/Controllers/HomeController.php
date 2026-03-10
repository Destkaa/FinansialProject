<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UangMasuk;
use App\Models\UangKeluar;
use App\Models\Saldo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $user = Auth::user();
        $userId = $user->id;

        // 1. Logika Pembeda Admin vs User Biasa
        if ($user->role == 'admin') {
            // Admin melihat total seluruh sistem
            $totalPemasukan = UangMasuk::sum('nominal');
            $totalPengeluaran = UangKeluar::sum('nominal');
            $totalSaldo = Saldo::sum('total');
            $totalUser = User::count(); // Tambahan untuk card selamat datang

            // Query Pemasukan (Sertakan created_at untuk Jam)
            $pemasukan = UangMasuk::with('user')
                ->select('id', 'id_user', 'nominal', 'keterangan', 'tanggal_uang_masuk as tanggal', 'created_at', DB::raw("'Pemasukan' as kategori"))
                ->get();

            // Query Pengeluaran (Sertakan created_at untuk Jam)
            $pengeluaran = UangKeluar::with('user')
                ->select('id', 'id_user', 'nominal', 'keterangan', 'tanggal_uang_keluar as tanggal', 'created_at', DB::raw("'Pengeluaran' as kategori"))
                ->get();
        } else {
            // User biasa hanya melihat miliknya sendiri
            $totalPemasukan = UangMasuk::where('id_user', $userId)->sum('nominal');
            $totalPengeluaran = UangKeluar::where('id_user', $userId)->sum('nominal');
            $totalSaldo = Saldo::where('id_user', $userId)->sum('total');
            $totalUser = 0;

            $pemasukan = UangMasuk::where('id_user', $userId)
                ->select('id', 'nominal', 'keterangan', 'tanggal_uang_masuk as tanggal', 'created_at', DB::raw("'Pemasukan' as kategori"))
                ->get();

            $pengeluaran = UangKeluar::where('id_user', $userId)
                ->select('id', 'nominal', 'keterangan', 'tanggal_uang_keluar as tanggal', 'created_at', DB::raw("'Pengeluaran' as kategori"))
                ->get();
        }

        // 2. Gabungkan dan Urutkan berdasarkan created_at (agar jam juga berpengaruh pada urutan)
        $transactions = $pemasukan->concat($pengeluaran)
            ->sortByDesc('created_at') 
            ->take(10);

        // 3. Kirim variabel ke view
        return view('home', compact(
            'totalPemasukan', 
            'totalPengeluaran', 
            'totalSaldo', 
            'totalUser',
            'transactions'
        ));
    }
}