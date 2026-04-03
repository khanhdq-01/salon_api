<?php

namespace App\Contracts\Services\Admin;

interface AdminSettingsServiceInterface
{
    public function getSettings(): array;

    public function updateSettings(array $data): array;
}
