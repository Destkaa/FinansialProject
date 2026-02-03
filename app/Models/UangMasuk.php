<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UangMasuk extends Model
{
    // Tambahkan id_saldo ke sini!
    protected $fillable = ['id_saldo', 'nominal', 'keterangan', 'tanggal_uang_masuk'];

    public function saldo()
    {
        return $this->belongsTo(Saldo::class, 'id_saldo');
    }
}