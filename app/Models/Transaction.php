<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {
    // Tambahkan user_id ke dalam array fillable
    protected $fillable = ['user_id', 'tanggal', 'keterangan', 'kategori', 'nominal', 'status'];

    // Tambahkan relasi ini agar Dashboard bisa panggil $item->user->name
    public function user() {
        return $this->belongsTo(User::class);
    }
}