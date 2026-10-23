<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Page::insert([
            [
                'page_name' => 'page_name_1',
                'page_title' => 'page_title_1',
                'page_slug' => 'page_slug_1',
                'page_description' => "Lorem 1 Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
                'page_position' => 'page_position_1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page_name' => 'page_name_2',
                'page_title' => 'page_title_2',
                'page_slug' => 'page_slug_2',
                'page_description' => "Lorem 2 Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 2500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 2960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
                'page_position' => 'page_position_2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page_name' => 'page_name_3',
                'page_title' => 'page_title_3',
                'page_slug' => 'page_slug_3',
                'page_description' => "Lorem 3 Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 3500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 3960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
                'page_position' => 'page_position_3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page_name' => 'page_name_4',
                'page_title' => 'page_title_4',
                'page_slug' => 'page_slug_4',
                'page_description' => "Lorem 4 Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 4500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 4960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
                'page_position' => 'page_position_4',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
