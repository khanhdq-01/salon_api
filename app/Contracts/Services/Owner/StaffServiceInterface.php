<?php

namespace App\Contracts\Services\Owner;

interface StaffServiceInterface
{
    public function listStaff(array $filters, ?\App\Models\User $actor = null): mixed;

    public function createStaff(array $data, \App\Models\User $actor): mixed;

    public function getStaffById(string $id): mixed;

    public function updateStaff(string $id, array $data, \App\Models\User $actor): mixed;

    public function deleteStaff(string $id, \App\Models\User $actor): bool;

    public function updateSchedule(string $id, array $data, \App\Models\User $actor): mixed;

    public function assignServices(string $id, array $serviceIds, \App\Models\User $actor): mixed;
}
