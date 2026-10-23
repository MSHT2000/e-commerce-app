<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create([
            'name' => 'Mohammed',
            'email' => 'eng.mohammedshadi@gmail.com',
            'password' => Hash::make('2352004'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
