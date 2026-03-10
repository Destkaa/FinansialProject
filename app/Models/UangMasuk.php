<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class UangMasuk extends Model
{
    protected $table = 'uang_masuks';

    protected $fillable = [
        'id_user', 
        'id_saldo', 
        'nominal', 
        'keterangan', 
        'tanggal_uang_masuk', 
        'created_at' // Penting: agar jam bisa diisi manual
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function saldo() {
        return $this->belongsTo(Saldo::class, 'id_saldo');
    }

    public function user() {
        return $this->hasOneThrough(User::class, Saldo::class, 'id', 'id', 'id_saldo', 'id_user');
    }
}