<?php

namespace App\Repositories\Eloquent\Admin;

use App\Models\SystemSetting;
use App\Repositories\Interfaces\Admin\SystemSettingRepositoryInterface;

class SystemSettingRepository implements SystemSettingRepositoryInterface
{
    public function __construct(
        protected SystemSetting $model
    ) {}

    public function findByKey(string $key): ?SystemSetting
    {
        return $this->model->newQuery()->find($key);
    }

    public function saveByKey(string $key, array $value, ?string $updatedBy): SystemSetting
    {
        $record = $this->model->newQuery()->firstOrNew(['key' => $key]);
        $record->value = $value;
        $record->updated_by = $updatedBy;
        $record->updated_at = now();
        $record->save();

        return $record;
    }
}
