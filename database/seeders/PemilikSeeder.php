<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PemilikSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Pemilik Apotek',
            'email' => 'pemilik@apotekberkahibu.com', // Ganti dengan email Anda
            'password' => Hash::make('passwordPemilik'), // Ganti dengan password yang kuat
            'role' => 'pemilik',
            'email_verified_at' => now(), // Tandai sebagai terverifikasi
        ]);
    }
}