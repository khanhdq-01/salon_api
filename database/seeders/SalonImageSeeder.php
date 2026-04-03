<?php

namespace Database\Seeders;

use App\Models\SalonImage;
use Database\Seeders\Data\DemoSalonsData;
use Database\Seeders\Support\SalonLookup;
use Illuminate\Database\Seeder;

class SalonImageSeeder extends Seeder
{
    public function run(): void
    {
        foreach (DemoSalonsData::all() as $index => $entry) {
            $salon = SalonLookup::salonAt($index);

            foreach ($entry['gallery_images'] as $imageUrl) {
                SalonImage::query()->create([
                    'salon_id' => $salon->id,
                    'image_url' => $imageUrl,
                ]);
            }
        }
    }
}
