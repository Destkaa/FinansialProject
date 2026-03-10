<?php

namespace App\Http\Controllers;

use App\Models\Saldo;
use App\Models\UangMasuk;
use App\Models\ActivityLog; 
use App\Exports\UangMasukExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UangMasukController extends Controller
{
    public function export_excel() 
    {
        return Excel::download(new UangMasukExport, 'uang-masuk.xlsx');
    }

    public function index()
    {
        if (Auth::user()->role == 'admin') {
            $uangmasuk = UangMasuk::with(['saldo', 'user'])->latest()->get();
        } else {
            $uangmasuk = UangMasuk::with('saldo')
                ->where('id_user', Auth::id())
                ->latest()
                ->get();
        }

        return view('uangmasuk.index', compact('uangmasuk'));
    }

    public function create()
    {
        $saldo = Saldo::where('id_user', Auth::id())->get();
        return view('uangmasuk.create', compact('saldo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_saldo' => 'required',
            'nominal' => 'required|numeric',
            'keterangan' => 'required',
            'tanggal_uang_masuk' => 'required|date',
        ]);

        return DB::transaction(function () use ($request) {
            // LOGIKA JAM: Menggabungkan tanggal pilihan user dengan waktu detik ini
            $waktuSekarang = now()->format('H:i:s');
            $tanggalWaktuLengkap = $request->tanggal_uang_masuk . ' ' . $waktuSekarang;

            $uangmasuk = UangMasuk::create([
                'id_user'            => Auth::id(),
                'id_saldo'           => $request->id_saldo,
                'nominal'            => $request->nominal,
                'keterangan'         => $request->keterangan,
                'tanggal_uang_masuk' => $request->tanggal_uang_masuk,
                'created_at'         => $tanggalWaktuLengkap, // Menyimpan jam asli ke database
            ]);

            $saldo = Saldo::findOrFail($request->id_saldo);
            $saldo->increment('total', $request->nominal);

            ActivityLog::create([
                'user_id'     => Auth::id(),
                'activity'    => 'Tambah Pemasukan', 
                'description' => Auth::user()->name . " mencatat pemasukan '" . $request->keterangan . "' sebesar Rp " . number_format($request->nominal, 0, ',', '.')
            ]);

            return redirect()->route('uangmasuk.index')->with('success', 'Pemasukan berhasil dicatat!');
        });
    }

    public function edit($id)
    {
        $uangmasuk = UangMasuk::findOrFail($id);
        
        if (Auth::user()->role != 'admin' && $uangmasuk->id_user != Auth::id()) {
            abort(403, 'Anda tidak diizinkan mengubah data ini.');
        }

        $saldo = Saldo::where('id_user', Auth::id())->get(); 
        return view('uangmasuk.edit', compact('uangmasuk', 'saldo'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_saldo' => 'required',
            'nominal' => 'required|numeric',
            'keterangan' => 'required',
            'tanggal_uang_masuk' => 'required|date',
        ]);

        return DB::transaction(function () use ($request, $id) {
            $uangmasuk = UangMasuk::findOrFail($id);
            
            if (Auth::user()->role != 'admin' && $uangmasuk->id_user != Auth::id()) {
                abort(403);
            }

            $nominalLama = $uangmasuk->nominal;
            $nominalBaru = $request->nominal; 

            // Kembalikan saldo sebelum diupdate
            $saldoLama = Saldo::findOrFail($uangmasuk->id_saldo);
            $saldoLama->decrement('total', $nominalLama);

            // LOGIKA JAM: Pertahankan jam menit detik lama agar data tidak reset ke 00:00
            $jamLama = Carbon::parse($uangmasuk->created_at)->format('H:i:s');
            $tanggalWaktuBaru = $request->tanggal_uang_masuk . ' ' . $jamLama;

            $uangmasuk->update([
                'id_saldo' => $request->id_saldo,
                'nominal' => $nominalBaru,
                'keterangan' => $request->keterangan,
                'tanggal_uang_masuk' => $request->tanggal_uang_masuk,
                'created_at' => $tanggalWaktuBaru,
            ]);

            // Update ke saldo yang (mungkin) baru dipilih
            $saldoBaru = Saldo::findOrFail($request->id_saldo);
            $saldoBaru->increment('total', $nominalBaru);

            ActivityLog::create([
                'user_id'     => Auth::id(),
                'activity'    => 'Update Pemasukan',
                'description' => Auth::user()->name . " mengubah data pemasukan ID #$id (Rp " . number_format($nominalLama) . " -> Rp " . number_format($nominalBaru) . ")"
            ]);

            return redirect()->route('uangmasuk.index')->with('success', 'Berhasil update data!');
        });
    }

    public function destroy($id)
    {
        return DB::transaction(function () use ($id) {
            $uangmasuk = UangMasuk::findOrFail($id);
            
            if (Auth::user()->role != 'admin') {
                abort(403, 'Hanya Admin yang boleh menghapus data.');
            }

            $saldo = Saldo::findOrFail($uangmasuk->id_saldo);
            $saldo->decrement('total', $uangmasuk->nominal);

            $infoLog = [
                'user' => $uangmasuk->user->name ?? 'User',
                'nominal' => $uangmasuk->nominal,
                'ket' => $uangmasuk->keterangan
            ];

            $uangmasuk->delete();

            ActivityLog::create([
                'user_id'     => Auth::id(),
                'activity'    => 'Hapus Pemasukan',
                'description' => "Admin menghapus pemasukan milik " . $infoLog['user'] . " sebesar Rp " . number_format($infoLog['nominal']) . " (" . $infoLog['ket'] . ")"
            ]);

            return redirect()->route('uangmasuk.index')->with('success', 'Data berhasil dihapus!');
        });
    }

    public function show($id)
    {
        $uangmasuk = UangMasuk::with(['saldo', 'user'])->findOrFail($id);
        
        if (Auth::user()->role != 'admin' && $uangmasuk->id_user != Auth::id()) {
            abort(403);
        }

        return view('uangmasuk.show', compact('uangmasuk'));
    }
}