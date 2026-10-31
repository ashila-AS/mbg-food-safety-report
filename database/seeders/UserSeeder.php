<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['Bu Rina', 'pelapor1@mbg.test', 'pelapor'],
            ['Pak Budi', 'pelapor2@mbg.test', 'pelapor'],
            ['Pak Dedi', 'pemantau@mbg.test', 'pemantau'],
            ['Bu Sari', 'koordinator@mbg.test', 'koordinator'],
        ];

        foreach ($users as [$nama, $email, $role]) {
            User::create([
                'nama' => $nama,
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => $role,
            ]);
        }
    }
}