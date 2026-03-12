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
            $uangmasuk = UangMasuk::with(['saldo', 'user'])
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
        if ($request->has('nominal')) {
            $nominalBersih = str_replace('.', '', $request->nominal);
            $request->merge(['nominal' => $nominalBersih]);
        }

        $request->validate([
            'id_saldo' => 'required|exists:saldos,id',
            'nominal' => 'required|numeric|min:1',
            'keterangan' => 'required',
            'tanggal_uang_masuk' => 'required|date',
        ]);

        return DB::transaction(function () use ($request) {
            $waktuSekarang = now()->format('H:i:s');
            $tanggalWaktuLengkap = $request->tanggal_uang_masuk . ' ' . $waktuSekarang;

            UangMasuk::create([
                'id_user'            => Auth::id(),
                'id_saldo'           => $request->id_saldo,
                'nominal'            => $request->nominal,
                'keterangan'         => $request->keterangan,
                'tanggal_uang_masuk' => $request->tanggal_uang_masuk,
                'created_at'         => $tanggalWaktuLengkap,
            ]);

            $saldo = Saldo::findOrFail($request->id_saldo);
            $saldo->increment('total', $request->nominal);

            // LOG: Nama dihapus agar mengikuti profil user terbaru
            ActivityLog::create([
                'user_id'     => Auth::id(),
                'activity'    => 'Tambah', 
                'description' => "mencatat pemasukan '" . $request->keterangan . "' sebesar Rp " . number_format($request->nominal, 0, ',', '.'),
                'ip_address'  => $request->ip(),
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
        if ($request->has('nominal')) {
            $nominalBersih = str_replace('.', '', $request->nominal);
            $request->merge(['nominal' => $nominalBersih]);
        }

        $request->validate([
            'id_saldo' => 'required|exists:saldos,id',
            'nominal' => 'required|numeric|min:1',
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

            $saldoLama = Saldo::findOrFail($uangmasuk->id_saldo);
            $saldoLama->decrement('total', $nominalLama);

            $jamLama = Carbon::parse($uangmasuk->created_at)->format('H:i:s');
            $tanggalWaktuBaru = $request->tanggal_uang_masuk . ' ' . $jamLama;

            $uangmasuk->update([
                'id_saldo' => $request->id_saldo,
                'nominal' => $nominalBaru,
                'keterangan' => $request->keterangan,
                'tanggal_uang_masuk' => $request->tanggal_uang_masuk,
                'created_at' => $tanggalWaktuBaru,
            ]);

            $saldoBaru = Saldo::findOrFail($request->id_saldo);
            $saldoBaru->increment('total', $nominalBaru);

            // LOG: Nama dihapus
            ActivityLog::create([
                'user_id'     => Auth::id(),
                'activity'    => 'Update',
                'description' => "mengubah data pemasukan (ID #$id)",
                'ip_address'  => $request->ip(),
            ]);

            return redirect()->route('uangmasuk.index')->with('success', 'Berhasil update data!');
        });
    }

    public function destroy(Request $request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $uangmasuk = UangMasuk::findOrFail($id);
            
            if (Auth::user()->role != 'admin' && $uangmasuk->id_user != Auth::id()) {
                abort(403);
            }

            $saldo = Saldo::findOrFail($uangmasuk->id_saldo);
            $saldo->decrement('total', $uangmasuk->nominal);

            // LOG: Catat sebelum dihapus
            ActivityLog::create([
                'user_id'     => Auth::id(),
                'activity'    => 'Hapus',
                'description' => "menghapus pemasukan senilai Rp " . number_format($uangmasuk->nominal, 0, ',', '.'),
                'ip_address'  => $request->ip(),
            ]);

            $uangmasuk->delete();

            return redirect()->route('uangmasuk.index')->with('success', 'Data berhasil dihapus!');
        });
    }
}