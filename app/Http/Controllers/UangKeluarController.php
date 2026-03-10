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

class UangKeluarController extends Controller
{
    public function export_excel() {
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
            $uangkeluar = UangKeluar::create([
                'id_user'             => Auth::id(),
                'id_saldo'            => $request->id_saldo,
                'nominal'             => $request->nominal,
                'keterangan'          => $request->keterangan,
                'tanggal_uang_keluar' => $request->tanggal_uang_keluar,
            ]);

            $saldo = Saldo::findOrFail($request->id_saldo);
            $saldo->decrement('total', $request->nominal);

            // SINKRONISASI: Ganti 'action' menjadi 'activity'
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

                // 1. Ambil data saldo lama & baru
                $saldoLama = Saldo::findOrFail($uangkeluar->id_saldo);
                $saldoBaru = Saldo::findOrFail($request->id_saldo);

                // 2. Kembalikan saldo lama dulu secara virtual untuk pengecekan
                $saldoLama->total += $nominalLama;

                // 3. Logika Pengecekan: Jika saldo yang sama, atau saldo baru berbeda
                // Kita cek apakah saldo mencukupi setelah ditambah nominal lama
                if ($request->id_saldo == $uangkeluar->id_saldo) {
                    if ($saldoLama->total < $nominalBaru) {
                        return redirect()->back()->with('error', 'Saldo tidak mencukupi! Sisa saldo + kembalian transaksi ini: Rp ' . number_format($saldoLama->total))->withInput();
                    }
                    // Update saldo yang sama
                    $saldoLama->total -= $nominalBaru;
                    $saldoLama->save();
                } else {
                    // Jika ganti akun saldo
                    if ($saldoBaru->total < $nominalBaru) {
                        return redirect()->back()->with('error', 'Saldo di akun tujuan tidak mencukupi!')->withInput();
                    }
                    // Simpan perubahan di kedua akun saldo
                    $saldoLama->save(); // Simpan pengembalian di akun lama
                    $saldoBaru->decrement('total', $nominalBaru); // Potong di akun baru
                }

                // 4. Update data transaksi
                $uangkeluar->update([
                    'id_saldo' => $request->id_saldo,
                    'nominal' => $nominalBaru,
                    'keterangan' => $request->keterangan,
                    'tanggal_uang_keluar' => $request->tanggal_uang_keluar,
                ]);

                // 5. Activity Log
                ActivityLog::create([
                    'user_id'     => Auth::id(),
                    'activity'    => 'Update Pengeluaran',
                    'description' => Auth::user()->name . " mengubah data pengeluaran ID #$id (Rp " . number_format($nominalLama) . " -> Rp " . number_format($nominalBaru) . ")"
                ]);

                return redirect()->route('uangkeluar.index')->with('success', 'Transaksi pengeluaran berhasil diperbarui!');
            });
        } catch (\Exception $e) {
            // Jika ada error tak terduga, tangkap dan tampilkan dengan manis
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        return DB::transaction(function () use ($id) {
            $uangkeluar = UangKeluar::findOrFail($id);

            if (Auth::user()->role != 'admin') {
                abort(403, 'Hanya Admin yang boleh menghapus data pengeluaran.');
            }

            $saldo = Saldo::findOrFail($uangkeluar->id_saldo);
            $saldo->increment('total', $uangkeluar->nominal);

            $infoLog = [
                'user' => $uangkeluar->user->name ?? 'User',
                'nominal' => $uangkeluar->nominal,
                'ket' => $uangkeluar->keterangan
            ];

            $uangkeluar->delete();

            // SINKRONISASI: Ganti 'action' menjadi 'activity'
            ActivityLog::create([
                'user_id'     => Auth::id(),
                'activity'    => 'Hapus Pengeluaran',
                'description' => "Admin menghapus pengeluaran milik " . $infoLog['user'] . " senilai Rp " . number_format($infoLog['nominal']) . " (" . $infoLog['ket'] . ")"
            ]);

            return redirect()->route('uangkeluar.index')->with('success', 'Data dihapus, saldo telah dikembalikan!');
        });
    }
}