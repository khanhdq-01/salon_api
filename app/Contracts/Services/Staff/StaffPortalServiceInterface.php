<?php

namespace App\Contracts\Services\Staff;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface StaffPortalServiceInterface
{
    public function getProfile(User $staffUser): array;

    public function updateProfile(User $staffUser, array $data): User;

    public function getDashboard(User $staffUser, array $filters = []): array;

    public function listSchedules(User $staffUser): array;

    public function paginateWorkSchedules(User $staffUser, array $filters): LengthAwarePaginator;

    public function submitSchedules(User $staffUser, array $schedules): array;

    public function getReport(User $staffUser, array $filters): array;

    public function getCalendarDay(User $staffUser, string $date): array;

    public function paginateCalendar(User $staffUser, array $filters): LengthAwarePaginator;

    public function completeAssignedBooking(User $staffUser, string $bookingId): array;
}
