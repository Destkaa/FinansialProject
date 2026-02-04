<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UangKeluar extends Model
{
     protected $fillable = ['id_saldo', 'nominal', 'keterangan', 'tanggal_uang_keluar'];

    public function saldo()
    {
        return $this->belongsTo(Saldo::class, 'id_saldo');
    }

}
