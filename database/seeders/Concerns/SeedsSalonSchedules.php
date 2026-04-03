<?php

namespace Database\Seeders\Concerns;

use App\Models\Salon;
use App\Models\SalonSchedule;

trait SeedsSalonSchedules
{
    protected function seedSalonWeeklySchedule(Salon $salon): void
    {
        for ($day = 0; $day <= 6; $day++) {
            SalonSchedule::query()->create([
                'salon_id' => $salon->id,
                'day_of_week' => $day,
                'open_time' => $salon->open_time,
                'close_time' => $salon->close_time,
                'is_active' => true,
            ]);
        }
    }
}
