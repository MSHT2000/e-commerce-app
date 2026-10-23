<?php

namespace Database\Seeders;

use App\Models\Wharehouse;
use Illuminate\Database\Seeder;

class WharehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Wharehouse::insert([
            [
                'name' => 'Main Warehouse',
                'address' => 'Industrial Area, Amman',
                'phone' => '+962790000001',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'North Warehouse',
                'address' => 'Irbid Logistics Zone',
                'phone' => '+962790000002',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'South Warehouse',
                'address' => 'Aqaba Free Zone',
                'phone' => '+962790000003',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Backup Warehouse',
                'address' => 'Zarqa Storage District',
                'phone' => '+962790000004',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
