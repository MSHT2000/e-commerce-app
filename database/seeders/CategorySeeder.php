<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::insert([
            [
                "category_name" => "Clothes",
                "category_slug" => Str::slug("Clothes", "-"),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "category_name" => "Books",
                "category_slug" => Str::slug("Books", "-"),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "category_name" => "Electronics",
                "category_slug" => Str::slug("Electronics", "-"),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "category_name" => "Foods",
                "category_slug" => Str::slug("Foods", "-"),
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}
