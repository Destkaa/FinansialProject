<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Membuat Akun Admin
        User::create([
            'name'     => 'Admin Ganteng',
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('password123'), // Passwordnya: password123
            'role'     => 'admin', // Pastikan kolom ini sesuai dengan database
        ]);

        // Membuat Akun User Biasa (Opsional, untuk testing)
        User::create([
            'name'     => 'User Biasa',
            'email'    => 'user@gmail.com',
            'password' => Hash::make('password123'),
            'role'     => 'user',
        ]);
    }
}