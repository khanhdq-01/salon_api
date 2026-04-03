<?php

namespace App\Repositories\Interfaces\Admin;

use App\Models\SystemSetting;

interface SystemSettingRepositoryInterface
{
    public function findByKey(string $key): ?SystemSetting;

    public function saveByKey(string $key, array $value, ?string $updatedBy): SystemSetting;
}
