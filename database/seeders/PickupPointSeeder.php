<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PickupPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        DB::table('pickup_point')->insert([
            [
                'name' => 'Downtown Pickup Point',
                'address' => '123 Main St, Springfield, IL 62701',
                'phone' => '+1 (217) 555-0134',
                'phone_two' => '+1 (217) 555-0178',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Westside Collection Hub',
                'address' => '456 West Ave, Apt 5B, Columbus, OH 43215',
                'phone' => '+1 (614) 555-0199',
                'phone_two' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Harborview Pickup',
                'address' => '789 Harbor Blvd, San Diego, CA 92101',
                'phone' => '+1 (619) 555-0112',
                'phone_two' => '+1 (619) 555-0144',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Uptown Service Point',
                'address' => '1010 Uptown Rd, Seattle, WA 98101',
                'phone' => '+1 (206) 555-0166',
                'phone_two' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
