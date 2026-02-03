<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UangMasuk extends Model
{
    use HasFactory;

    protected $fillable = [
        'nominal',
        'keterangan',
        'tanggal_uang_masuk',
        'id_saldo'
    ];

    // Relasi balik ke Model Saldo
    public function saldo()
    {
        return $this->belongsTo(Saldo::class, 'id_saldo');
    }
}