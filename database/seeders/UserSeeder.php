<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Data Simple
        $users = [
            [
                'name'     => 'Administrator',
                'email'    => 'admin@gmail.com',
                'password' => Hash::make('123'),
                'role'     => 'admin',
            ],
            [
                'name'     => 'Staff User',
                'email'    => 'kakaviangi58@gmail.com',
                'password' => Hash::make('1310'),
                'role'     => 'user',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(['email' => $user['email']], $user);
        }
    }
}