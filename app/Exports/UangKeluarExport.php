<?php

namespace App\Exports;

use App\Models\UangKeluar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UangKeluarExport implements FromCollection, WithHeadings
{
public function collection() {
    // Ganti 'jumlah' menjadi nama kolom yang benar di database kamu
    return UangKeluar::select('id', 'nominal', 'keterangan', 'tanggal_uang_keluar')->get();
}

public function headings(): array {
    return ['ID', 'Nominal', 'Keterangan', 'Tanggal'];
}
}
