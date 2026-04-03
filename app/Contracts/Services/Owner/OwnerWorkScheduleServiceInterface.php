<?php

namespace App\Contracts\Services\Owner;

use App\Models\StaffSchedule;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface OwnerWorkScheduleServiceInterface
{
    public function listCalendar(User $owner, array $filters): Collection;

    public function listApproved(User $owner, array $filters): LengthAwarePaginator;

    public function listPending(User $owner, array $filters): LengthAwarePaginator;

    public function listForStaff(User $owner, string $staffId, array $filters): Collection;

    public function create(User $owner, array $data): StaffSchedule;

    public function update(User $owner, int $scheduleId, array $data): StaffSchedule;

    public function delete(User $owner, int $scheduleId): bool;

    public function approve(User $owner, int $scheduleId, ?string $note = null): StaffSchedule;

    public function approveAll(User $owner, array $filters = []): array;

    public function reject(User $owner, int $scheduleId, ?string $note = null): StaffSchedule;
}
