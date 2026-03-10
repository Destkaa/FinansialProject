<?php

namespace App\Exports;

use App\Models\UangMasuk;
use App\Models\ActivityLog; // Import model log
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UangMasukExport implements FromCollection, WithHeadings
{
    public function collection() {
        // Catat aktivitas ke history sebelum mengembalikan data
        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Export Data',
            'description' => Auth::user()->name . ' mengunduh data Uang Masuk ke Excel',
        ]);

        return UangMasuk::select('id', 'nominal', 'keterangan', 'tanggal_uang_masuk')->get();
    }

    public function headings(): array {
        return ['ID', 'Nominal', 'Keterangan', 'Tanggal'];
    }
}