<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saldo extends Model
{
    use HasFactory;
    
    // id_user harus masuk fillable agar bisa disimpan
    protected $fillable = ['id_user', 'nama_e_wallet', 'total'];

    /**
     * Relasi ke User
     * Ini sangat penting agar Admin bisa tahu saldo ini milik siapa
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Relasi ke Uang Masuk
     */
    public function uangMasuk()
    {
        return $this->hasMany(UangMasuk::class, 'id_saldo');
    }

    /**
     * Relasi ke Uang Keluar
     */
    public function uangKeluar()
    {
        return $this->hasMany(UangKeluar::class, 'id_saldo');
    }
}