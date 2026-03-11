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
            'nominal' => 'required|numeric|min:1', // Pastikan minimal 1 rupiah
            'keterangan' => 'required',
            'tanggal_uang_keluar' => 'required|date',
        ]);

        return DB::transaction(function () use ($request) {
            // 1. Ambil data saldo yang akan dikurangi
            $saldo = Saldo::findOrFail($request->id_saldo);

            // 2. CEK APAKAH SALDO CUKUP?
            if ($saldo->total < $request->nominal) {
                // Jika tidak cukup, kembali ke halaman sebelumnya dengan pesan error
                return redirect()->back()
                    ->with('error', 'Saldo tidak mencukupi! Sisa saldo Anda: Rp ' . number_format($saldo->total, 0, ',', '.'))
                    ->withInput();
            }

            // 3. Jika cukup, proses data
            $tanggalInput = $request->tanggal_uang_keluar;
            $waktuSekarang = now()->format('H:i:s');
            $fullDateTime = $tanggalInput . ' ' . $waktuSekarang;

            $uangkeluar = UangKeluar::create([
                'id_user'             => Auth::id(),
                'id_saldo'            => $request->id_saldo,
                'nominal'             => $request->nominal,
                'keterangan'          => $request->keterangan,
                'tanggal_uang_keluar' => $request->tanggal_uang_keluar,
                'created_at'          => $fullDateTime,
            ]);

            // 4. Kurangi saldo
            $saldo->decrement('total', $request->nominal);

            ActivityLog::create([
                'user_id'     => Auth::id(),
                'activity'    => 'Tambah Pengeluaran', 
                'description' => Auth::user()->name . " mencatat pengeluaran '" . $request->keterangan . "' sebesar Rp " . number_format($request->nominal, 0, ',', '.')
            ]);

            return redirect()->route('uangkeluar.index')->with('success', 'Pengeluaran berhasil dicatat!');
        });
    }

    // Fungsi show, edit, update, dan destroy tetap menggunakan logika Anda yang sudah bagus...
    // (Logika update Anda sudah memiliki pengecekan saldo, jadi sudah aman)
    
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
            'nominal' => 'required|numeric|min:1',
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

                $jamLama = Carbon::parse($uangkeluar->created_at)->format('H:i:s');
                $tanggalWaktuBaru = $request->tanggal_uang_keluar . ' ' . $jamLama;

                $saldoLama = Saldo::findOrFail($uangkeluar->id_saldo);
                $saldoBaru = Saldo::findOrFail($request->id_saldo);

                // Simulasi: Kembalikan saldo dulu ke kondisi sebelum transaksi ini
                $totalTersediaSesuaiAkun = ($request->id_saldo == $uangkeluar->id_saldo) 
                    ? ($saldoLama->total + $nominalLama) 
                    : $saldoBaru->total;

                if ($totalTersediaSesuaiAkun < $nominalBaru) {
                    return redirect()->back()->with('error', 'Saldo tidak mencukupi untuk update ini!')->withInput();
                }

                // Jalankan update saldo
                $saldoLama->total += $nominalLama;
                $saldoLama->save();
                
                $saldoBaru->refresh(); // Refresh agar sinkron
                $saldoBaru->decrement('total', $nominalBaru);

                $uangkeluar->update([
                    'id_saldo' => $request->id_saldo,
                    'nominal' => $nominalBaru,
                    'keterangan' => $request->keterangan,
                    'tanggal_uang_keluar' => $request->tanggal_uang_keluar,
                    'created_at' => $tanggalWaktuBaru,
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