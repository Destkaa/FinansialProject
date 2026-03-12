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
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;

        if ($user->role == 'admin') {
            $totalPemasukan = UangMasuk::sum('nominal');
            $totalPengeluaran = UangKeluar::sum('nominal');
            $totalSaldo = Saldo::sum('total');
            $totalUser = User::count();

            $pemasukan = UangMasuk::with('user')
                ->select('id', 'id_user', 'nominal', 'keterangan', 'tanggal_uang_masuk as tanggal', 'created_at', DB::raw("'Pemasukan' as kategori"))
                ->get();

            $pengeluaran = UangKeluar::with('user')
                ->select('id', 'id_user', 'nominal', 'keterangan', 'tanggal_uang_keluar as tanggal', 'created_at', DB::raw("'Pengeluaran' as kategori"))
                ->get();
        } else {
            $totalPemasukan = UangMasuk::where('id_user', $userId)->sum('nominal');
            $totalPengeluaran = UangKeluar::where('id_user', $userId)->sum('nominal');
            $totalSaldo = Saldo::where('id_user', $userId)->sum('total');
            $totalUser = 0;

            // PERBAIKAN DI SINI: Tambahkan with('user') dan 'id_user' di select
            $pemasukan = UangMasuk::with('user') // Tambahkan ini
                ->where('id_user', $userId)
                ->select('id', 'id_user', 'nominal', 'keterangan', 'tanggal_uang_masuk as tanggal', 'created_at', DB::raw("'Pemasukan' as kategori"))
                ->get();

            $pengeluaran = UangKeluar::with('user') // Tambahkan ini
                ->where('id_user', $userId)
                ->select('id', 'id_user', 'nominal', 'keterangan', 'tanggal_uang_keluar as tanggal', 'created_at', DB::raw("'Pengeluaran' as kategori"))
                ->get();
        }

        $transactions = $pemasukan->concat($pengeluaran)
            ->sortByDesc('created_at') 
            ->take(10);

        return view('home', compact(
            'totalPemasukan', 
            'totalPengeluaran', 
            'totalSaldo', 
            'totalUser',
            'transactions'
        ));
    }
}