<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saldo extends Model
{
    use HasFactory;
    
    protected $fillable = [ 'id', 'nama_e_wallet', 'total'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}