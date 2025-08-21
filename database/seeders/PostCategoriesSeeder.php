<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Technology', 'slug' => Str::slug('Technology')],
            ['name' => 'Lifestyle', 'slug' => Str::slug('Lifestyle')],
            ['name' => 'Education', 'slug' => Str::slug('Education')],
            ['name' => 'Travel', 'slug' => Str::slug('Travel')],
            ['name' => 'Food', 'slug' => Str::slug('Food')],
        ];

        DB::table('categories')->insert($categories);
    }
}
