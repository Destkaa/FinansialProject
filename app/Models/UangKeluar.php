<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UangKeluar extends Model
{
    protected $table = 'uang_keluars';

    protected $fillable = ['id_user','id_saldo', 'nominal', 'keterangan', 'tanggal_uang_keluar'];

    /**
     * Relasi ke Saldo
     */
    public function saldo()
    {
        return $this->belongsTo(Saldo::class, 'id_saldo');
    }

    /**
     * Relasi ke User (Lewat Saldo)
     * Ini sangat penting agar $item->user->name di Dashboard Admin tidak error
     */
    public function user()
    {
        return $this->hasOneThrough(
            User::class, 
            Saldo::class, 
            'id',       // Foreign key di tabel saldos (id)
            'id',       // Foreign key di tabel users (id)
            'id_saldo', // Local key di tabel uang_keluars
            'id_user'   // Local key di tabel saldos
        );
    }
}