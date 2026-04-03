<?php

namespace App\Services\Owner;

use App\Contracts\Services\Owner\OwnerSalonSettingsServiceInterface;
use App\Models\SalonSetting;
use App\Models\User;
use App\Repositories\Interfaces\Owner\SalonRepositoryInterface;
use App\Repositories\Interfaces\Owner\SalonSettingsRepositoryInterface;
use App\Services\Shared\AssertsSalonOwnership;
use App\Support\AvailableSlotsCache;

class OwnerSalonSettingsService implements OwnerSalonSettingsServiceInterface
{
    use AssertsSalonOwnership;

    public function __construct(
        protected SalonSettingsRepositoryInterface $settingsRepository,
        protected SalonRepositoryInterface $salonRepository,
    ) {}

    public function getForOwner(User $owner): SalonSetting
    {
        $salonId = $this->resolveOwnerSalonId($this->salonRepository, $owner);

        return $this->ensureForSalon($salonId);
    }

    public function getForSalon(string $salonId): SalonSetting
    {
        return $this->ensureForSalon($salonId);
    }

    public function updateForOwner(User $owner, array $data): SalonSetting
    {
        $salonId = $this->resolveOwnerSalonId($this->salonRepository, $owner);
        $settings = $this->ensureForSalon($salonId);

        $updated = $this->settingsRepository->update($settings, $data);

        if (array_key_exists('booking_interval_minutes', $data)) {
            AvailableSlotsCache::forgetSalonWide($salonId);
        }

        return $updated;
    }

    public function ensureForSalon(string $salonId): SalonSetting
    {
        $settings = $this->settingsRepository->findBySalonId($salonId);

        if ($settings) {
            return $settings;
        }

        return $this->settingsRepository->create(SalonSetting::defaultAttributes($salonId));
    }
}
