<?php

namespace App\Repositories\Interfaces\Owner;

use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface StaffRepositoryInterface
{
    public function findById(string $id, array $relations = []): ?Staff;

    public function paginate(array $filters): LengthAwarePaginator;

    public function create(array $data): Staff;

    public function update(Staff $staff, array $data): Staff;

    public function delete(Staff $staff): bool;

    public function syncServices(Staff $staff, array $serviceIds): Staff;

    public function replaceSchedules(
        Staff $staff,
        array $schedules,
        string $status = 'approved',
        string $submittedBy = 'owner',
    ): Staff;

    public function staffProvidesServices(Staff $staff, Collection $serviceIds): bool;

    public function countBySalon(string $salonId): int;

    public function countActiveBySalon(string $salonId): int;

    public function getActiveBySalonIds(array $salonIds, array $relations = []): Collection;

    public function getActiveForSalonWithServices(string $salonId): Collection;

    public function getBySalonOrderedByName(string $salonId, array $columns = ['*']): Collection;
}
