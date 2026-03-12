<?php

namespace App\Http\Controllers;

use App\Models\Saldo;
use App\Models\UangKeluar;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Exports\UangKeluarExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UangKeluarController extends Controller
{
    public function export_excel() 
    {
        return Excel::download(new UangKeluarExport, 'uang-keluar.xlsx');
    }

    public function index()
    {
        if (Auth::user()->role == 'admin') {
            $uangkeluar = UangKeluar::with(['saldo', 'user'])->latest()->get();
        } else {
            $uangkeluar = UangKeluar::with(['saldo', 'user'])
                ->where('id_user', Auth::id())
                ->latest()
                ->get();
        }
        
        return view('uangkeluar.index', compact('uangkeluar'));
    }

    public function create()
    {
        $saldo = Saldo::where('id_user', Auth::id())->get();
        return view('uangkeluar.create', compact('saldo'));
    }

    public function store(Request $request)
    {
        if ($request->has('nominal')) {
            $nominalBersih = str_replace('.', '', $request->nominal);
            $request->merge(['nominal' => $nominalBersih]);
        }

        $request->validate([
            'id_saldo' => 'required|exists:saldos,id',
            'nominal' => 'required|numeric|min:1',
            'keterangan' => 'required',
            'tanggal_uang_keluar' => 'required|date',
        ]);

        return DB::transaction(function () use ($request) {
            $saldo = Saldo::findOrFail($request->id_saldo);

            if ($saldo->total < $request->nominal) {
                return redirect()->back()->with('error', 'Saldo tidak mencukupi!')->withInput();
            }

            $waktuSekarang = now()->format('H:i:s');
            $fullDateTime = $request->tanggal_uang_keluar . ' ' . $waktuSekarang;

            $uangkeluar = UangKeluar::create([
                'id_user'             => Auth::id(),
                'id_saldo'            => $request->id_saldo,
                'nominal'             => $request->nominal,
                'keterangan'          => $request->keterangan,
                'tanggal_uang_keluar' => $request->tanggal_uang_keluar,
                'created_at'          => $fullDateTime,
            ]);

            $saldo->decrement('total', $request->nominal);

            // LOG: Nama dihapus agar dinamis mengikuti tabel users
            ActivityLog::create([
                'user_id'     => Auth::id(),
                'activity'    => 'Tambah', 
                'description' => "mencatat pengeluaran '" . $request->keterangan . "' sebesar Rp " . number_format($request->nominal, 0, ',', '.'),
                'ip_address'  => $request->ip(),
            ]);

            return redirect()->route('uangkeluar.index')->with('success', 'Pengeluaran berhasil dicatat!');
        });
    }

    public function destroy(Request $request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $uangkeluar = UangKeluar::findOrFail($id);

            if (Auth::user()->role != 'admin' && $uangkeluar->id_user != Auth::id()) {
                abort(403);
            }

            $saldo = Saldo::findOrFail($uangkeluar->id_saldo);
            $saldo->increment('total', $uangkeluar->nominal);

            // LOG: Sebelum dihapus, catat riwayatnya
            ActivityLog::create([
                'user_id'     => Auth::id(),
                'activity'    => 'Hapus',
                'description' => "menghapus pengeluaran senilai Rp " . number_format($uangkeluar->nominal, 0, ',', '.'),
                'ip_address'  => $request->ip(),
            ]);

            $uangkeluar->delete();

            return redirect()->route('uangkeluar.index')->with('success', 'Data berhasil dihapus!');
        });
    }
}