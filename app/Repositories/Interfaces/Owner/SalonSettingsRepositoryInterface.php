<?php

namespace App\Repositories\Interfaces\Owner;

use App\Models\SalonSetting;
use Illuminate\Support\Collection;

interface SalonSettingsRepositoryInterface
{
    public function findBySalonId(string $salonId): ?SalonSetting;

    public function getBySalonIds(array $salonIds): Collection;

    public function create(array $data): SalonSetting;

    public function update(SalonSetting $settings, array $data): SalonSetting;
}
