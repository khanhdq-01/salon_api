<?php

namespace App\Contracts\Services\Owner;

use App\Models\SalonSetting;
use App\Models\User;

interface OwnerSalonSettingsServiceInterface
{
    public function getForOwner(User $owner): SalonSetting;

    public function getForSalon(string $salonId): SalonSetting;

    public function updateForOwner(User $owner, array $data): SalonSetting;

    public function ensureForSalon(string $salonId): SalonSetting;
}
