<?php

namespace Database\Seeders;

use App\Models\Smtp;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SmtpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Smtp::insert([
            'mailer'=>'smtp',
            'host'=>'smtp.gmail.com',
            'port' =>'587',
            'user_name'=>'eng.mohammedshadi@gmail.com',
            'password'=>'soaj bocw aooh kvfs',
            'mail_from_address'=>'eng.mohammedshadi@gmail.com',
            'mail_from_name'=>'e_commerce_app',
        ]);
    }
}
