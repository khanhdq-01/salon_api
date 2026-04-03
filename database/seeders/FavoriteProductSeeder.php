<?php

namespace Database\Seeders;

use App\Models\FavoriteProduct;
use Database\Seeders\Data\DemoFavoriteProductsData;
use Database\Seeders\Support\SalonLookup;
use Illuminate\Database\Seeder;

class FavoriteProductSeeder extends Seeder
{
    public function run(): void
    {
        $created = 0;

        foreach (DemoFavoriteProductsData::all() as $entry) {
            $customer = SalonLookup::customerByEmail($entry['customer_email']);
            $salon = SalonLookup::salonAt($entry['salon_index']);
            $service = SalonLookup::serviceByName($salon->id, $entry['service_name']);
            $styleOption = SalonLookup::styleByName($service->id, $entry['style_name']);

            if (! $styleOption) {
                continue;
            }

            FavoriteProduct::query()->firstOrCreate([
                'user_id' => $customer->id,
                'product_type' => FavoriteProduct::TYPE_HAIRSTYLE,
                'product_ref' => $styleOption->id,
            ]);

            $created++;
        }

        $this->command?->info("Seeded {$created} favorite hairstyles.");
    }
}
