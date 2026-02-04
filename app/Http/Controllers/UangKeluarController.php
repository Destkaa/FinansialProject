<?php

namespace App\Http\Controllers;

use App\Models\Saldo;
use App\Models\UangKeluar;
use Illuminate\Http\Request;

class UangKeluarController extends Controller
{
    public function index()
    {
        $uangkeluar = UangKeluar::with('saldo')->latest()->get();
        return view('uangkeluar.index', compact('uangkeluar'));
    }

    public function create()
    {
        $saldo = Saldo::all();
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

        $saldo = Saldo::findOrFail($request->id_saldo);

        // Proteksi agar saldo tidak minus
        if ($saldo->total < $request->nominal) {
            return redirect()->back()->with('error', 'Saldo tidak mencukupi!')->withInput();
        }

        UangKeluar::create($request->all());

        // KURANGI saldo karena uang keluar
        $saldo->total -= $request->nominal;
        $saldo->save();

        return redirect()->route('uangkeluar.index')->with('success', 'Pengeluaran berhasil dicatat!');
    }

    public function show(string $id)
    {
        // Perbaikan: Variabel harus $uangkeluar agar sinkron dengan compact
        $uangkeluar = UangKeluar::with('saldo')->findOrFail($id);
        return view('uangkeluar.show', compact('uangkeluar'));
    }

    public function edit(string $id)
    {
        $uangkeluar = UangKeluar::findOrFail($id);
        $saldo = Saldo::all(); 
        return view('uangkeluar.edit', compact('uangkeluar', 'saldo'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'id_saldo' => 'required',
            'nominal' => 'required|numeric',
            'keterangan' => 'required',
            'tanggal_uang_keluar' => 'required|date',
        ]);

        $uangkeluar = UangKeluar::findOrFail($id);
        $nominalBaru = $request->nominal; 

        // 1. Kembalikan saldo lama (dibuat seolah-olah transaksi lama tidak pernah ada)
        $saldoLama = Saldo::findOrFail($uangkeluar->id_saldo);
        $saldoLama->total += $uangkeluar->nominal; // Ditambah kembali karena ini uang keluar yang dibatalkan
        $saldoLama->save();

        // 2. Cek apakah saldo baru mencukupi setelah dikembalikan
        $saldoBaru = Saldo::findOrFail($request->id_saldo);
        if ($saldoBaru->total < $nominalBaru) {
            // Jika tidak cukup, kembalikan saldo lama ke kondisi awal sebelum divalidasi
            $saldoLama->total -= $uangkeluar->nominal;
            $saldoLama->save();
            return redirect()->back()->with('error', 'Update gagal! Saldo tidak mencukupi.')->withInput();
        }

        // 3. Update data transaksi
        $uangkeluar->update([
            'id_saldo' => $request->id_saldo,
            'nominal' => $nominalBaru,
            'keterangan' => $request->keterangan,
            'tanggal_uang_keluar' => $request->tanggal_uang_keluar,
        ]);

        // 4. Potong saldo baru
        $saldoBaru->total -= $nominalBaru;
        $saldoBaru->save();

        return redirect()->route('uangkeluar.index')->with('success', 'Data pengeluaran berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $uangkeluar = UangKeluar::findOrFail($id);
        $saldo = Saldo::findOrFail($uangkeluar->id_saldo);

        // KEMBALIKAN saldo (Uang yang keluar dibatalkan/dihapus, jadi saldo harus bertambah)
        $saldo->total += $uangkeluar->nominal; 
        $saldo->save();

        $uangkeluar->delete();
        return redirect()->route('uangkeluar.index')->with('success', 'Data dihapus, saldo bertambah kembali!');
    }
}