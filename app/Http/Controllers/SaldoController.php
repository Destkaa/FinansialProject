<?php

namespace App\Http\Controllers;

use App\Models\Saldo;
use App\Models\User;
use App\Exports\SaldoExport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;
class SaldoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
        public function export_excel()
    {
        return Excel::download(new SaldoExport, 'data-saldo.xlsx');
    }
    
    public function index()
    {
        $saldo = Saldo::all();
        return view('saldo.index', compact('saldo'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('saldo.create');

    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    // 1. Validasi: Jika gagal, Laravel otomatis kembali ke halaman sebelumnya
    $request->validate([
        'nama_e_wallet' => 'required|min:3', // Wajib diisi, minimal 3 karakter
        'total' => 'required|numeric|min:0', // Wajib diisi, harus angka, minimal 0
    ], [
        // Custom pesan error (Opsional)
        'nama_e_wallet.required' => 'Nama E-Wallet harus diisi bos!',
        'total.required' => 'Total saldo tidak boleh kosong!',
        'total.numeric' => 'Isi pakai angka saja ya.',
    ]);

    // 2. Jika lolos validasi, baru simpan
    $saldo = new Saldo();
    $saldo->nama_e_wallet = $request->nama_e_wallet;
    $saldo->total = $request->total;
    $saldo->save();

    return redirect()->route('saldo.index')->with('success', 'Data berhasil ditambah!');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $saldo = \App\Models\Saldo::findOrFail($id);

        return view('saldo.show', compact('saldo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
  public function edit(string $id)
    {
    $saldo = Saldo::findOrFail($id);

    return view('saldo.edit', compact('saldo'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_e_wallet' => 'required',
            'total'         => 'required|numeric',
        ]);

        // 2. Cari data berdasarkan ID
        $saldo = Saldo::findOrFail($id);

        // 3. Update data
        $saldo->nama_e_wallet = $request->nama_e_wallet;
        $saldo->total         = $request->total;
        
        // 4. Simpan ke database
        $saldo->save();

        // 5. Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('saldo.index')->with('success', 'Data berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $saldo = Saldo::findOrFail($id);
    $saldo->delete();

    return redirect()->route('saldo.index')->with('success', 'Data berhasil dihapus!');
    }
}
