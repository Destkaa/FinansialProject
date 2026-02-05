<?php

namespace App\Http\Controllers;

use App\Models\Saldo;
use App\Models\UangMasuk;
use App\Exports\UangMasukExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class UangMasukController extends Controller
{

    public function export_excel() {
        return Excel::download(new UangMasukExport, 'uang-masuk.xlsx');
    }

    public function index()
    {
        $uangmasuk = UangMasuk::with('saldo')->latest()->get();
        return view('uangmasuk.index', compact('uangmasuk'));
    }

    public function create()
    {
        $saldo = Saldo::all();
        return view('uangmasuk.create', compact('saldo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_saldo' => 'required|exists:saldos,id',
            'nominal' => 'required|numeric',
            'keterangan' => 'required',
            'tanggal_uang_masuk' => 'required|date',
        ]);

        $saldo = Saldo::findOrFail($request->id_saldo);

        UangMasuk::create([
            'id_saldo' => $request->id_saldo,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'tanggal_uang_masuk' => $request->tanggal_uang_masuk,
        ]);

        // Tambah ke saldo (Gunakan 'total')
        $saldo->total += $request->nominal;
        $saldo->save();

        return redirect()->route('uangmasuk.index')->with('success', 'Data berhasil ditambah!');
    }

    public function edit($id)
    {
        $uangmasuk = UangMasuk::findOrFail($id);
        $saldo = Saldo::all(); // Agar dropdown muncul di edit
        return view('uangmasuk.edit', compact('uangmasuk', 'saldo'));
    }

        public function update(Request $request, $id)
    {
        $uangmasuk = UangMasuk::findOrFail($id);
        
        // Ambil nominal dari input hidden (nominal_asli) agar murni angka
        $nominalBaru = $request->nominal; 

        // 1. Kurangi saldo lama
        $saldoLama = Saldo::findOrFail($uangmasuk->id_saldo);
        $saldoLama->total -= $uangmasuk->nominal;
        $saldoLama->save();

        // 2. Update transaksi
        $uangmasuk->update([
            'id_saldo' => $request->id_saldo,
            'nominal' => $nominalBaru,
            'keterangan' => $request->keterangan,
            'tanggal_uang_masuk' => $request->tanggal_uang_masuk,
        ]);

        // 3. Tambah ke saldo baru
        $saldoBaru = Saldo::findOrFail($request->id_saldo);
        $saldoBaru->total += $nominalBaru;
        $saldoBaru->save();

        return redirect()->route('uangmasuk.index')->with('success', 'Berhasil! Saldo sekarang: Rp ' . number_format($saldoBaru->total, 0, ',', '.'));
    }
    public function destroy($id)
    {
        $uangmasuk = UangMasuk::findOrFail($id);
        $saldo = Saldo::findOrFail($uangmasuk->id_saldo);

        // Kurangi saldo sebelum hapus (Pakai 'total')
        $saldo->total -= $uangmasuk->nominal;
        $saldo->save();

        $uangmasuk->delete();
        return redirect()->route('uangmasuk.index')->with('success', 'Data berhasil dihapus!');
    }

    public function show($id)
    {
        $uangmasuk = UangMasuk::with('saldo')->findOrFail($id);
        return view('uangmasuk.show', compact('uangmasuk'));
    }
}