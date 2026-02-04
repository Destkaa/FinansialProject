<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UangMasuk extends Model
{
    protected $table = 'uang_masuks';

    // Gunakan id_saldo di sini
    protected $fillable = ['id_saldo', 'nominal', 'keterangan', 'tanggal_uang_masuk'];

    public function saldo()
    {
        // Beritahu Laravel kalau foreign key-nya adalah id_saldo
        return $this->belongsTo(Saldo::class, 'id_saldo');
    }
}