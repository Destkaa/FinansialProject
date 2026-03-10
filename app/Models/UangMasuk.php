<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class UangMasuk extends Model
{
    protected $table = 'uang_masuks';

    protected $fillable = ['id_user','id_saldo', 'nominal', 'keterangan', 'tanggal_uang_masuk'];

    public function saldo()
    {
        return $this->belongsTo(Saldo::class, 'id_saldo');
    }

    /**
     * Relasi ke User melalui Saldo
     * Ini supaya $item->user->name di Dashboard Admin bisa jalan
     */
    public function user()
    {
        return $this->hasOneThrough(
            User::class, 
            Saldo::class, 
            'id',       // Foreign key di tabel saldos (id)
            'id',       // Foreign key di tabel users (id)
            'id_saldo', // Local key di tabel uang_masuks
            'id_user'   // Local key di tabel saldos
        );
    }
}