<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk posts.
     */
    public function run(): void
    {
        $user     = User::first();            // ambil user pertama dari UserSeeder
        $category = Category::first();    // ambil kategori pertama dari CategorySeeder

        for ($i = 1; $i <= 10; $i++) {
            $title = "Contoh Post ke-$i";

            Post::create([
                'user_id'      => $user->id,
                'category_id'  => $category?->id, // bisa null kalau kategori kosong
                'title'        => $title,
                'slug'         => Str::slug($title) . '-' . $i, // supaya unik
                'content'      => "Ini adalah isi dari post ke-$i yang dibuat oleh seeder.",
                'image'        => null,
                'published_at' => now()->subDays(rand(0, 30)),
            ]);
        }
    }
}
