<?php

namespace App\Http\Controllers;

use App\Models\Saldo;
use App\Models\User;
use App\Exports\SaldoExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan ini

class SaldoController extends Controller
{
    public function export_excel()
    {
        return Excel::download(new SaldoExport, 'data-saldo.xlsx');
    }
    
    public function index()
    {
        // 1. Cek siapa yang login
        $user = Auth::user();

        if ($user->role == 'admin') {
            // Admin melihat semua saldo di sistem
            $saldo = Saldo::with('user')->latest()->get();
        } else {
            // User biasa HANYA melihat saldo miliknya sendiri
            $saldo = Saldo::where('id_user', $user->id)
                ->latest()
                ->get();
        }

        return view('saldo.index', compact('saldo'));
    }

    public function create()
    {
        return view('saldo.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_e_wallet' => 'required',
        ]);

        Saldo::create([
            'id_user'       => auth()->id(),
            'nama_e_wallet' => $request->nama_e_wallet,
            'total'         => 0,
        ]);

        return redirect()->route('saldo.index')->with('success', 'Saldo berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        $saldo = Saldo::findOrFail($id);

        // Security Check: User biasa tidak boleh intip saldo orang lain lewat URL
        if (Auth::user()->role != 'admin' && $saldo->id_user != Auth::id()) {
            abort(403);
        }

        return view('saldo.show', compact('saldo'));
    }

    public function edit(string $id)
    {
        $saldo = Saldo::findOrFail($id);

        // Security Check
        if (Auth::user()->role != 'admin' && $saldo->id_user != Auth::id()) {
            abort(403);
        }

        return view('saldo.edit', compact('saldo'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_e_wallet' => 'required',
            'total'         => 'required|numeric',
        ]);

        $saldo = Saldo::findOrFail($id);

        // Security Check
        if (Auth::user()->role != 'admin' && $saldo->id_user != Auth::id()) {
            abort(403);
        }

        $saldo->update([
            'nama_e_wallet' => $request->nama_e_wallet,
            'total'         => $request->total,
        ]);

        return redirect()->route('saldo.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $saldo = Saldo::findOrFail($id);

        // Security Check
        if (Auth::user()->role != 'admin' && $saldo->id_user != Auth::id()) {
            abort(403);
        }

        $saldo->delete();
        return redirect()->route('saldo.index')->with('success', 'Data berhasil dihapus!');
    }
}