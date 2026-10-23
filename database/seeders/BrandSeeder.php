<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\File;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clean all images from brands directory before seeding
        $this->cleanBrandImagesDirectory();

        Brand::insert([
            [
                'name' => 'TOSHIBA',
                'slug' => Str::slug('toshiba', "-"),
                'logo' => $this->uploadBrandPhoto("C:\\Users\\Eng.MohammedShadi\\Downloads\\toshiba.jpg")
            ],
            [
                'name' => 'Nike',
                'slug' => Str::slug('Nike', "-"),
                'logo' => $this->uploadBrandPhoto("C:\\Users\\Eng.MohammedShadi\\Downloads\\nike.png")
            ],
            [
                'name' => 'SAMSUNG',
                'slug' => Str::slug('samsung', "-"),
                'logo' => $this->uploadBrandPhoto("C:\\Users\\Eng.MohammedShadi\\Downloads\\samsung.png")
            ],
            [
                'name' => 'Apple',
                'slug' => Str::slug('apple', "-"),
                'logo' => $this->uploadBrandPhoto("C:\\Users\\Eng.MohammedShadi\\Downloads\\apple.jpg")
            ],
            [
                'name' => 'Adidas',
                'slug' => Str::slug('adidas', "-"),
                'logo' => $this->uploadBrandPhoto("C:\\Users\\Eng.MohammedShadi\\Downloads\\adidas.png")
            ],


        ]);
    }

    /**
     * Remove all images from storage/public/brands directory
     *
     * @return void
     */
    public function cleanBrandImagesDirectory(): void
    {
        $brandPath = 'brands';

        // Check if directory exists
        if (!Storage::disk('public')->exists($brandPath)) {
            return;
        }

        // Get all files in the brands directory
        $files = Storage::disk('public')->files($brandPath);

        // Delete each file
        foreach ($files as $file) {
            Storage::disk('public')->delete($file);
        }
    }

    /**
     * Upload a brand photo from local path to storage and return the full URL
     *
     * @param string $localPhotoPath - Absolute path to the photo on your PC (e.g., "C:\Users\...\photo.png")
     * @return string - Full URL of the uploaded photo
     * @throws \Exception - If file doesn't exist or upload fails
     */
    public function uploadBrandPhoto(string $localPhotoPath): string
    {
        // Check if file exists
        if (!file_exists($localPhotoPath)) {
            throw new \Exception("File not found at path: {$localPhotoPath}");
        }

        // Generate unique filename
        $filename = Str::random(32) . '.' . pathinfo($localPhotoPath, PATHINFO_EXTENSION);

        // Store file to public/brands directory and get stored path
        $storedPath = Storage::disk('public')->putFileAs('brands', new File($localPhotoPath), $filename);

        // Return full URL for stored path
        return Storage::url($storedPath);
    }
}
