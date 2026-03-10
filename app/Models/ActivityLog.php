<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    // Tambahkan baris ini agar user_id dan activity bisa disimpan
    protected $fillable = [
        'user_id', 
        'activity', 
        'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}