<?php

namespace Database\Seeders;

use Database\Seeders\Data\DemoFavoriteSalonsData;
use Database\Seeders\Support\SalonLookup;
use Illuminate\Database\Seeder;

class FavoriteSalonSeeder extends Seeder
{
    public function run(): void
    {
        $created = 0;

        foreach (DemoFavoriteSalonsData::all() as $entry) {
            $customer = SalonLookup::customerByEmail($entry['customer_email']);

            foreach ($entry['salon_indices'] as $salonIndex) {
                $salon = SalonLookup::salonAt($salonIndex);

                if ($customer->favoriteSalons()->where('salon_id', $salon->id)->exists()) {
                    continue;
                }

                $customer->favoriteSalons()->attach($salon->id);
                $created++;
            }
        }

        $this->command?->info("Seeded {$created} favorite salons.");
    }
}
