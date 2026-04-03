<?php

namespace App\Repositories\Interfaces\Owner;

use App\Models\StaffSchedule;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface StaffScheduleRepositoryInterface
{
    public function findById(int|string $id, array $relations = []): ?StaffSchedule;

    public function findForStaffOnDate(string $staffId, string $date): ?StaffSchedule;

    public function findApprovedForStaffOnDate(string $staffId, string $date): ?StaffSchedule;

    public function getForStaffIdsOnDate(array $staffIds, string $date, ?string $status = null): Collection;

    public function paginateForSalon(string $salonId, array $filters): LengthAwarePaginator;

    public function listForCalendar(string $salonId, array $filters): Collection;

    public function listForStaff(string $staffId, array $filters): Collection;

    public function create(array $data): StaffSchedule;

    public function update(StaffSchedule $schedule, array $data): StaffSchedule;

    public function delete(StaffSchedule $schedule): bool;

    public function countPendingBySalon(string $salonId): int;
}
