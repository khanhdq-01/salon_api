<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceStyleOption;
use Database\Seeders\Data\DemoSalonsData;
use Database\Seeders\Data\DemoStyleOptionsData;
use Database\Seeders\Support\DemoSeederConstants;
use Database\Seeders\Support\SalonLookup;
use Illuminate\Database\Seeder;

class ServiceStyleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (DemoSalonsData::all() as $index => $entry) {
            $salon = SalonLookup::salonAt($index);

            $maleService = Service::query()
                ->where('salon_id', $salon->id)
                ->where('name', DemoSeederConstants::SERVICE_CUT_MALE)
                ->firstOrFail();

            $femaleService = Service::query()
                ->where('salon_id', $salon->id)
                ->where('name', DemoSeederConstants::SERVICE_CUT_FEMALE)
                ->firstOrFail();

            $this->seedStyles($maleService, DemoStyleOptionsData::maleStylesForSalon($index));
            $this->seedStyles($femaleService, DemoStyleOptionsData::femaleStylesForSalon($index));
        }
    }

    /**
     * @param  list<array{name: string, gender: string, image: string, extra_price: int, extra_duration: int, is_featured: bool}>  $styles
     */
    private function seedStyles(Service $service, array $styles): void
    {
        foreach ($styles as $sort => $style) {
            ServiceStyleOption::query()->create([
                'service_id' => $service->id,
                'name' => $style['name'],
                'gender' => $style['gender'],
                'description' => "Kiểu {$style['name']} cho {$service->name}",
                'extra_price' => $style['extra_price'],
                'extra_duration' => $style['extra_duration'],
                'image' => $style['image'],
                'sort_order' => $sort,
                'is_active' => true,
                'is_featured' => $style['is_featured'],
            ]);
        }
    }
}
