<?php

namespace App\Http\Controllers;

use App\Models\Saldo;
use App\Models\UangKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UangKeluarController extends Controller
{
    /**
     * Menampilkan daftar transaksi uang keluar.
     */
    public function index()
    {
        $uangkeluar = UangKeluar::with('saldo')->latest()->get();
        return view('uangkeluar.index', compact('uangkeluar'));
    }

    /**
     * Menampilkan form tambah pengeluaran.
     */
    public function create()
    {
        $saldo = Saldo::all();
        return view('uangkeluar.create', compact('saldo'));
    }

    /**
     * Menyimpan data pengeluaran baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_saldo' => 'required',
            'nominal' => 'required|numeric',
            'keterangan' => 'required',
            'tanggal_uang_keluar' => 'required|date',
        ]);

        return DB::transaction(function () use ($request) {
            $saldo = Saldo::findOrFail($request->id_saldo);

            // Cek apakah saldo cukup
            if ($saldo->total < $request->nominal) {
                return redirect()->back()->with('error', 'Saldo tidak mencukupi!')->withInput();
            }

            // Simpan transaksi
            UangKeluar::create($request->all());

            // Kurangi saldo
            $saldo->total -= $request->nominal;
            $saldo->save();

            return redirect()->route('uangkeluar.index')->with('success', 'Pengeluaran berhasil dicatat!');
        });
    }

    /**
     * Menampilkan detail pengeluaran.
     */
    public function show(string $id)
    {
        $uangkeluar = UangKeluar::with('saldo')->findOrFail($id);
        return view('uangkeluar.show', compact('uangkeluar'));
    }

    /**
     * Menampilkan form edit pengeluaran.
     */
    public function edit(string $id)
    {
        $uangkeluar = UangKeluar::findOrFail($id);
        $saldo = Saldo::all();
        return view('uangkeluar.edit', compact('uangkeluar', 'saldo'));
    }

    /**
     * Memperbarui data pengeluaran dan menyesuaikan saldo.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'id_saldo' => 'required',
            'nominal' => 'required|numeric',
            'keterangan' => 'required',
            'tanggal_uang_keluar' => 'required|date',
        ]);

        return DB::transaction(function () use ($request, $id) {
            $uangkeluar = UangKeluar::findOrFail($id);
            $nominalBaru = $request->nominal;

            // 1. Kembalikan dulu saldo lama (karena ini pengeluaran, maka saldo bertambah kembali)
            $saldoLama = Saldo::findOrFail($uangkeluar->id_saldo);
            $saldoLama->total += $uangkeluar->nominal;
            $saldoLama->save();

            // 2. Ambil saldo baru (bisa e-wallet yang sama atau berbeda)
            $saldoBaru = Saldo::findOrFail($request->id_saldo);

            // 3. Cek apakah saldo baru mencukupi setelah pengembalian saldo lama
            if ($saldoBaru->total < $nominalBaru) {
                // Batalkan transaksi secara manual atau throw error agar DB rollback
                throw new \Exception('Saldo tidak mencukupi untuk memperbarui transaksi.');
            }

            // 4. Update data transaksi
            $uangkeluar->update([
                'id_saldo' => $request->id_saldo,
                'nominal' => $nominalBaru,
                'keterangan' => $request->keterangan,
                'tanggal_uang_keluar' => $request->tanggal_uang_keluar,
            ]);

            // 5. Potong saldo baru
            $saldoBaru->total -= $nominalBaru;
            $saldoBaru->save();

            return redirect()->route('uangkeluar.index')->with('success', 'Transaksi pengeluaran berhasil diperbarui!');
        });
    }

    /**
     * Menghapus data pengeluaran dan mengembalikan saldo.
     */
    public function destroy($id)
    {
        return DB::transaction(function () use ($id) {
            $uangkeluar = UangKeluar::findOrFail($id);
            $saldo = Saldo::findOrFail($uangkeluar->id_saldo);

            // Kembalikan saldo karena pengeluaran dibatalkan/dihapus
            $saldo->total += $uangkeluar->nominal;
            $saldo->save();

            $uangkeluar->delete();

            return redirect()->route('uangkeluar.index')->with('success', 'Data dihapus, saldo telah dikembalikan!');
        });
    }
}