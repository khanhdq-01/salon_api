<?php

namespace App\Repositories\Eloquent\Owner;

use App\Models\SalonSetting;
use App\Repositories\Interfaces\Owner\SalonSettingsRepositoryInterface;
use Illuminate\Support\Collection;

class SalonSettingsRepository implements SalonSettingsRepositoryInterface
{
    public function __construct(
        protected SalonSetting $model
    ) {}

    public function findBySalonId(string $salonId): ?SalonSetting
    {
        return $this->model->newQuery()
            ->where('salon_id', $salonId)
            ->first();
    }

    public function getBySalonIds(array $salonIds): Collection
    {
        if ($salonIds === []) {
            return collect();
        }

        return $this->model->newQuery()
            ->whereIn('salon_id', $salonIds)
            ->get()
            ->keyBy('salon_id');
    }

    public function create(array $data): SalonSetting
    {
        return $this->model->newQuery()->create($data);
    }

    public function update(SalonSetting $settings, array $data): SalonSetting
    {
        $settings->fill($data);
        $settings->save();

        return $settings->fresh();
    }
}
