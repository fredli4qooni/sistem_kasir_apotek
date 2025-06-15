<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PemilikSeeder::class,
            // Tambahkan seeder lain jika ada
        ]);
    }
}