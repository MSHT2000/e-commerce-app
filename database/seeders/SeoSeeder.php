<?php

namespace Database\Seeders;

use App\Models\SEO;
use Illuminate\Database\Seeder;

class SeoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SEO::insert([
            [
                'meta_title' => 'titl_1',
                'meta_author' => 'author_1',
                'meta_tag' => 'tag_1',
                'meta_description' => 'description_1',
                'meta_keyword' => 'keyword_1',
                'google_verification' => 'google_verification_1',
                'google_analytics' => 'google_analytics_1',
                'google_adsense' => 'google_adsense_1',
                'alexa_verification' => 'alexa_verification_1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'meta_title' => 'titl_2',
                'meta_author' => 'author_2',
                'meta_tag' => 'tag_2',
                'meta_description' => 'description_2',
                'meta_keyword' => 'keyword_2',
                'google_verification' => 'google_verification_2',
                'google_analytics' => 'google_analytics_2',
                'google_adsense' => 'google_adsense_2',
                'alexa_verification' => 'alexa_verification_2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'meta_title' => 'titl_3',
                'meta_author' => 'author_3',
                'meta_tag' => 'tag_3',
                'meta_description' => 'description_3',
                'meta_keyword' => 'keyword_3',
                'google_verification' => 'google_verification_3',
                'google_analytics' => 'google_analytics_3',
                'google_adsense' => 'google_adsense_3',
                'alexa_verification' => 'alexa_verification_3',
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'meta_title' => 'titl_4',
                'meta_author' => 'author_4',
                'meta_tag' => 'tag_4',
                'meta_description' => 'description_4',
                'meta_keyword' => 'keyword_4',
                'google_verification' => 'google_verification_4',
                'google_analytics' => 'google_analytics_4',
                'google_adsense' => 'google_adsense_4',
                'alexa_verification' => 'alexa_verification_4',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
