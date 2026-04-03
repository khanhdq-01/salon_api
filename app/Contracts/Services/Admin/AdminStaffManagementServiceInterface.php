<?php

namespace App\Contracts\Services\Admin;

interface AdminStaffManagementServiceInterface
{
    public function listStaff(array $filters): mixed;

    public function createStaff(array $data): mixed;

    public function updateStaff(string $id, array $data): mixed;

    public function deleteStaff(string $id): bool;

    public function setActive(string $id, bool $active): mixed;

    public function changeSalon(string $id, string $salonId): mixed;
}
