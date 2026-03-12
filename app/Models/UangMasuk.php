<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UangMasuk extends Model
{
    protected $table = 'uang_masuks';

    protected $fillable = [
        'id_user', 
        'id_saldo', 
        'nominal', 
        'keterangan', 
        'tanggal_uang_masuk', 
        'created_at' 
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'tanggal_uang_masuk' => 'date',
    ];

    // Relasi ke Saldo (E-Wallet/Bank)
    public function saldo(): BelongsTo 
    {
        return $this->belongsTo(Saldo::class, 'id_saldo');
    }

    // Relasi ke User (Pemilik Transaksi)
    public function user(): BelongsTo 
    {
        // Langsung hubungkan id_user ke tabel users
        return $this->belongsTo(User::class, 'id_user');
    }
}