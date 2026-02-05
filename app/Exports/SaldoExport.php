<?php

namespace App\Exports;

use App\Models\Saldo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SaldoExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Saldo::all();
    }

    // Header Excel
    public function headings(): array
    {
        return [
            'No',
            'Nama E-Wallet',
            'Total Saldo',
        ];
    }

    // Mapping data agar sesuai kolom
    private $rowNumber = 0;
    public function map($saldo): array
    {
        return [
            ++$this->rowNumber,
            $saldo->nama_e_wallet,
            $saldo->total,
        ];
    }
}