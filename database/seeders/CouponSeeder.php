<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Coupon::insert([
            [
                'coupon_code' => 'SUMMER20',
                'valid_date' => '2026-08-31',
                'type' => 1,
                'coupon_amount' => 20,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'coupon_code' => 'WELCOME10',
                'valid_date' => '2026-12-31',
                'type' => 2,
                'coupon_amount' => 10,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'coupon_code' => 'FLASH25',
                'valid_date' => '2026-06-15',
                'type' => 1,
                'coupon_amount' => 25,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'coupon_code' => 'LOYALTY50',
                'valid_date' => '2026-09-30',
                'type' => 2,
                'coupon_amount' => 50,
                'status' => 'inactive',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
