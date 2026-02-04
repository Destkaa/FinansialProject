<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UangKeluar extends Model
{
    // Sesuaikan dengan nama di migrasi: uang_keluars
    protected $table = 'uang_keluars';

    // Sesuaikan foreign key: id_saldo
    protected $fillable = ['id_saldo', 'nominal', 'keterangan', 'tanggal_uang_keluar'];

    public function saldo()
    {
        // Beritahu Laravel foreign key-nya adalah id_saldo
        return $this->belongsTo(Saldo::class, 'id_saldo');
    }
}