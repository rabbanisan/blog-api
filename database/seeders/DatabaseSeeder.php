<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PostSeeder::class, // Panggil PostSeeder
            UserSeeder::class, // Panggil UserSeeder
            // Anda bisa menambahkan seeder lain di sini
        ]);
    }
}
