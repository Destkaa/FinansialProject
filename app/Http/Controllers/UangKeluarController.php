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
            $uangkeluar = UangKeluar::with('saldo')
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
        $request->validate([
            'id_saldo' => 'required',
            'nominal' => 'required|numeric',
            'keterangan' => 'required',
            'tanggal_uang_keluar' => 'required|date',
        ]);

        return DB::transaction(function () use ($request) {
            // LOGIKA JAM: Gabungkan tanggal dari input dengan jam menit detik saat ini
            $tanggalInput = $request->tanggal_uang_keluar;
            $waktuSekarang = now()->format('H:i:s');
            $fullDateTime = $tanggalInput . ' ' . $waktuSekarang;

            $uangkeluar = UangKeluar::create([
                'id_user'             => Auth::id(),
                'id_saldo'            => $request->id_saldo,
                'nominal'             => $request->nominal,
                'keterangan'          => $request->keterangan,
                'tanggal_uang_keluar' => $request->tanggal_uang_keluar,
                'created_at'          => $fullDateTime, // Paksa isi created_at dengan jam aktif
            ]);

            $saldo = Saldo::findOrFail($request->id_saldo);
            $saldo->decrement('total', $request->nominal);

            ActivityLog::create([
                'user_id'     => Auth::id(),
                'activity'    => 'Tambah Pengeluaran', 
                'description' => Auth::user()->name . " mencatat pengeluaran '" . $request->keterangan . "' sebesar Rp " . number_format($request->nominal, 0, ',', '.')
            ]);

            return redirect()->route('uangkeluar.index')->with('success', 'Pengeluaran berhasil dicatat!');
        });
    }

    public function show(string $id)
    {
        $uangkeluar = UangKeluar::with(['saldo', 'user'])->findOrFail($id);

        if (Auth::user()->role != 'admin' && $uangkeluar->id_user != Auth::id()) {
            abort(403);
        }

        return view('uangkeluar.show', compact('uangkeluar'));
    }

    public function edit(string $id)
    {
        $uangkeluar = UangKeluar::findOrFail($id);
        
        if (Auth::user()->role != 'admin' && $uangkeluar->id_user != Auth::id()) {
            abort(403, 'Anda tidak diizinkan mengubah data ini.');
        }

        $saldo = Saldo::where('id_user', Auth::id())->get();
        return view('uangkeluar.edit', compact('uangkeluar', 'saldo'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'id_saldo' => 'required|exists:saldos,id',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'required',
            'tanggal_uang_keluar' => 'required|date',
        ]);

        try {
            return DB::transaction(function () use ($request, $id) {
                $uangkeluar = UangKeluar::findOrFail($id);

                if (Auth::user()->role != 'admin' && $uangkeluar->id_user != Auth::id()) {
                    abort(403);
                }

                $nominalLama = $uangkeluar->nominal;
                $nominalBaru = $request->nominal;

                // Ambil jam lama agar saat update tanggal, jam aslinya tidak berubah jadi 00:00
                $jamLama = Carbon::parse($uangkeluar->created_at)->format('H:i:s');
                $tanggalWaktuBaru = $request->tanggal_uang_keluar . ' ' . $jamLama;

                $saldoLama = Saldo::findOrFail($uangkeluar->id_saldo);
                $saldoBaru = Saldo::findOrFail($request->id_saldo);

                // Revert saldo lama
                $saldoLama->total += $nominalLama;

                if ($request->id_saldo == $uangkeluar->id_saldo) {
                    if ($saldoLama->total < $nominalBaru) {
                        return redirect()->back()->with('error', 'Saldo tidak mencukupi!')->withInput();
                    }
                    $saldoLama->total -= $nominalBaru;
                    $saldoLama->save();
                } else {
                    if ($saldoBaru->total < $nominalBaru) {
                        return redirect()->back()->with('error', 'Saldo di akun tujuan tidak mencukupi!')->withInput();
                    }
                    $saldoLama->save(); 
                    $saldoBaru->decrement('total', $nominalBaru);
                }

                $uangkeluar->update([
                    'id_saldo' => $request->id_saldo,
                    'nominal' => $nominalBaru,
                    'keterangan' => $request->keterangan,
                    'tanggal_uang_keluar' => $request->tanggal_uang_keluar,
                    'created_at' => $tanggalWaktuBaru, // Tetap pertahankan jam lama
                ]);

                ActivityLog::create([
                    'user_id'     => Auth::id(),
                    'activity'    => 'Update Pengeluaran',
                    'description' => Auth::user()->name . " mengubah data pengeluaran ID #$id"
                ]);

                return redirect()->route('uangkeluar.index')->with('success', 'Transaksi berhasil diperbarui!');
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        return DB::transaction(function () use ($id) {
            $uangkeluar = UangKeluar::findOrFail($id);

            if (Auth::user()->role != 'admin') {
                abort(403, 'Hanya Admin yang boleh menghapus data.');
            }

            $saldo = Saldo::findOrFail($uangkeluar->id_saldo);
            $saldo->increment('total', $uangkeluar->nominal);

            $uangkeluar->delete();

            ActivityLog::create([
                'user_id'     => Auth::id(),
                'activity'    => 'Hapus Pengeluaran',
                'description' => "Admin menghapus pengeluaran senilai Rp " . number_format($uangkeluar->nominal)
            ]);

            return redirect()->route('uangkeluar.index')->with('success', 'Data dihapus dan saldo dikembalikan!');
        });
    }
}