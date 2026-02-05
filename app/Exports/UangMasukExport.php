<?php

namespace App\Exports;

use App\Models\UangMasuk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UangMasukExport implements FromCollection, WithHeadings
{
    public function collection() {
        return UangMasuk::select('id', 'nominal', 'keterangan', 'tanggal_uang_masuk')->get();
    }

    public function headings(): array {
        return ['ID', 'Nominal', 'Keterangan', 'Tanggal'];
    }
}
