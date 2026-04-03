<?php

namespace Database\Seeders;

use App\Models\Service;
use Database\Seeders\Data\DemoSalonsData;
use Database\Seeders\Data\DemoServicePricesData;
use Database\Seeders\Support\DemoServiceCatalog;
use Database\Seeders\Support\SalonLookup;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        foreach (DemoSalonsData::all() as $index => $entry) {
            $salon = SalonLookup::salonAt($index);

            foreach (DemoServiceCatalog::SERVICES as $template) {
                Service::query()->create([
                    'salon_id' => $salon->id,
                    'name' => $template['name'],
                    'price' => DemoServicePricesData::price($index, $template['key']),
                    'duration_minutes' => $template['duration_minutes'],
                    'is_active' => true,
                    'bookings_count' => 0,
                ]);
            }
        }
    }
}
