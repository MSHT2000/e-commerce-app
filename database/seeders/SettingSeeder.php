<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\File;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->cleanSettingImagesDirectory();

        DB::table('settings')->insert([
            'currency' => 'USD',
            'phone_one' => '+1-415-555-0148',
            'phone_two' => '+1-415-555-0172',
            'main_email' => 'hello@northstarshop.com',
            'support_email' => 'support@northstarshop.com',
            'logo' => $this->uploadSettingImage('C:\\Users\\Eng.MohammedShadi\\Downloads\\setting_logo.jpg'),
            'favicon' => $this->uploadSettingImage('C:\\Users\\Eng.MohammedShadi\\Downloads\\setting_fav_icon.png'),
            'address' => '1200 Market Street, Suite 450, San Francisco, CA 94102, USA',
            'facebook' => 'https://www.facebook.com/e_commerce_app',
            'twitter' => 'https://twitter.com/e_commerce_app',
            'instagram' => 'https://www.instagram.com/e_commerce_app',
            'linkedin' => 'https://www.linkedin.com/company/e_commerce_app',
            'youtube' => 'https://www.youtube.com/e_commerce_app',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Remove all images from storage/public/settings directory.
     */
    public function cleanSettingImagesDirectory(): void
    {
        $settingsPath = 'settings';

        if (! Storage::disk('public')->exists($settingsPath)) {
            return;
        }

        $files = Storage::disk('public')->files($settingsPath);

        foreach ($files as $file) {
            Storage::disk('public')->delete($file);
        }
    }

    /**
     * Upload a settings image from a local path to storage and return the public URL.
     */
    public function uploadSettingImage(string $localImagePath): string
    {
        if (! file_exists($localImagePath)) {
            throw new \Exception("File not found at path: {$localImagePath}");
        }

        $filename = Str::random(32) . '.' . pathinfo($localImagePath, PATHINFO_EXTENSION);
        $storedPath = Storage::disk('public')->putFileAs('settings', new File($localImagePath), $filename);

        return Storage::url($storedPath);
    }
}
