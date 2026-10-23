<?php

namespace Database\Seeders;

use Database\Seeders\BrandSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            CustomerSeeder::class,
            CategorySeeder::class,
            SubCategorySeeder::class,
            ChildCategorySeeder::class,
            BrandSeeder::class,
            SeoSeeder::class,
            PageSeeder::class,
            WharehouseSeeder::class,
            PickupPointSeeder::class,
            ProductSeeder::class,
            SmtpSeeder::class,
            SettingSeeder::class,
            CouponSeeder::class,
        ]);
    }
}
