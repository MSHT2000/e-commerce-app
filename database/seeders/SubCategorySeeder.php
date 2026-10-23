<?php

namespace Database\Seeders;

use App\Models\SubCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SubCategory::insert([
            //Clothes Category
            [
                "sub_category_name" => "Winter Clothing",
                "sub_category_slug" => Str::slug("Winter Clothing", "-"),
                "category_id" => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "sub_category_name" => "Summer Clothing",
                "sub_category_slug" => Str::slug("Summer Clothing", "-"),
                "category_id" => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "sub_category_name" => "Space suits",
                "sub_category_slug" => Str::slug("Space suits", "-"),
                "category_id" => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "sub_category_name" => "Swimwear",
                "sub_category_slug" => Str::slug("Swimwear", "-"),
                "category_id" => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            //Books Category
            [
                "sub_category_name" => "Science Fiction Books",
                "sub_category_slug" => Str::slug("Science Fiction Books", "-"),
                "category_id" => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "sub_category_name" => "Romance Novels",
                "sub_category_slug" => Str::slug("Romance Novels", "-"),
                "category_id" => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "sub_category_name" => "Children's Books",
                "sub_category_slug" => Str::slug("Children's Books", "-"),
                "category_id" => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "sub_category_name" => "Poetry Books",
                "sub_category_slug" => Str::slug("Poetry Books", "-"),
                "category_id" => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            //Electronics Category
            [
                "sub_category_name" => "Mobile phones",
                "sub_category_slug" => Str::slug("Mobile phones", "-"),
                "category_id" => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "sub_category_name" => "Laptops",
                "sub_category_slug" => Str::slug("Laptops", "-"),
                "category_id" => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "sub_category_name" => "Kitchen appliances",
                "sub_category_slug" => Str::slug("Kitchen appliances", "-"),
                "category_id" => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "sub_category_name" => "Televisions",
                "sub_category_slug" => Str::slug("Televisions", "-"),
                "category_id" => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            //Food Category
            [
                "sub_category_name" => "Vegetables",
                "sub_category_slug" => Str::slug("Vegetables", "-"),
                "category_id" => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "sub_category_name" => "Fruits",
                "sub_category_slug" => Str::slug("Fruits", "-"),
                "category_id" => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "sub_category_name" => "Juices",
                "sub_category_slug" => Str::slug("Juices", "-"),
                "category_id" => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                "sub_category_name" => "Pastries",
                "sub_category_slug" => Str::slug("Pastries", "-"),
                "category_id" => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],


        ]);
    }
}
