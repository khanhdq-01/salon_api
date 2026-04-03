<?php

namespace App\Services\Owner;

use App\Contracts\Services\Owner\OwnerWorkScheduleServiceInterface;
use App\Exceptions\BusinessException;
use App\Models\Staff;
use App\Models\StaffSchedule;
use App\Models\User;
use App\Repositories\Interfaces\Owner\SalonRepositoryInterface;
use App\Repositories\Interfaces\Owner\StaffRepositoryInterface;
use App\Repositories\Interfaces\Owner\StaffScheduleRepositoryInterface;
use App\Services\Shared\AssertsSalonOwnership;
use App\Support\AvailableSlotsCache;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class OwnerWorkScheduleService implements OwnerWorkScheduleServiceInterface
{
    use AssertsSalonOwnership;

    public function __construct(
        protected StaffScheduleRepositoryInterface $scheduleRepository,
        protected StaffRepositoryInterface $staffRepository,
        protected SalonRepositoryInterface $salonRepository,
    ) {}

    public function listCalendar(User $owner, array $filters): Collection
    {
        $salon = $this->resolveOwnerSalon($owner);
        $filters = $this->normalizeRangeFilters($filters);

        return $this->scheduleRepository->listForCalendar($salon->id, array_merge($filters, [
            'status' => StaffSchedule::STATUS_APPROVED,
        ]));
    }

    public function listApproved(User $owner, array $filters): LengthAwarePaginator
    {
        $salon = $this->resolveOwnerSalon($owner);
        $filters = $this->normalizeDateRangeFilters($filters);

        return $this->scheduleRepository->paginateForSalon($salon->id, array_merge($filters, [
            'status' => StaffSchedule::STATUS_APPROVED,
        ]));
    }

    public function listPending(User $owner, array $filters): LengthAwarePaginator
    {
        $salon = $this->resolveOwnerSalon($owner);
        $filters = $this->normalizePendingListFilters($filters);

        return $this->scheduleRepository->paginateForSalon($salon->id, array_merge($filters, [
            'status' => StaffSchedule::STATUS_PENDING,
        ]));
    }

    public function listForStaff(User $owner, string $staffId, array $filters): Collection
    {
        $staff = $this->findStaffOrFail($staffId);
        $this->assertCanManageSalon($staff->salon, $owner);

        $filters = $this->normalizeRangeFilters($filters);

        return $this->scheduleRepository->listForStaff($staffId, $filters);
    }

    public function create(User $owner, array $data): StaffSchedule
    {
        $staff = $this->findStaffOrFail($data['staff_id']);
        $this->assertCanManageSalon($staff->salon, $owner);
        $this->assertValidShiftTimes($data);

        $existing = $this->scheduleRepository->findForStaffOnDate($staff->id, $data['work_date']);
        if ($existing && $existing->status === StaffSchedule::STATUS_APPROVED) {
            throw new BusinessException('Ngày này đã có ca được duyệt.', 'SCHEDULE_EXISTS', 422);
        }

        if ($existing) {
            $schedule = $this->scheduleRepository->update($existing, [
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'status' => StaffSchedule::STATUS_APPROVED,
                'submitted_by' => StaffSchedule::SUBMITTED_BY_OWNER,
                'note' => $data['note'] ?? null,
                'approved_by' => $owner->id,
                'approved_at' => now(),
            ]);
            $this->invalidateSlotsForSchedule($schedule, $staff->salon_id);

            return $schedule;
        }

        $schedule = $this->scheduleRepository->create([
            'staff_id' => $staff->id,
            'work_date' => $data['work_date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'status' => StaffSchedule::STATUS_APPROVED,
            'submitted_by' => StaffSchedule::SUBMITTED_BY_OWNER,
            'note' => $data['note'] ?? null,
            'approved_by' => $owner->id,
            'approved_at' => now(),
        ]);
        $this->invalidateSlotsForSchedule($schedule, $staff->salon_id);

        return $schedule;
    }

    public function update(User $owner, int $scheduleId, array $data): StaffSchedule
    {
        $schedule = $this->findScheduleOrFail($scheduleId);
        $this->assertCanManageSalon($schedule->staff?->salon, $owner);
        $salonId = $schedule->staff?->salon_id;
        $previousDate = AvailableSlotsCache::normalizeDate($schedule->work_date);
        $wasApproved = $schedule->status === StaffSchedule::STATUS_APPROVED;

        if (isset($data['start_time'], $data['end_time'])) {
            $this->assertValidShiftTimes($data);
        }

        $updated = $this->scheduleRepository->update($schedule, $data);

        if ($salonId && ($wasApproved || $updated->status === StaffSchedule::STATUS_APPROVED)) {
            AvailableSlotsCache::forgetSalonDates($salonId, [
                $previousDate,
                $updated->work_date,
            ]);
        }

        return $updated;
    }

    public function delete(User $owner, int $scheduleId): bool
    {
        $schedule = $this->findScheduleOrFail($scheduleId);
        $this->assertCanManageSalon($schedule->staff?->salon, $owner);
        $salonId = $schedule->staff?->salon_id;
        $wasApproved = $schedule->status === StaffSchedule::STATUS_APPROVED;
        $workDate = AvailableSlotsCache::normalizeDate($schedule->work_date);

        $deleted = $this->scheduleRepository->delete($schedule);

        if ($deleted && $salonId && $wasApproved && $workDate) {
            AvailableSlotsCache::forgetSalonDate($salonId, $workDate);
        }

        return $deleted;
    }

    public function approve(User $owner, int $scheduleId, ?string $note = null): StaffSchedule
    {
        $schedule = $this->findScheduleOrFail($scheduleId, ['staff.salon']);
        $this->assertCanManageSalon($schedule->staff?->salon, $owner);

        if ($schedule->status !== StaffSchedule::STATUS_PENDING) {
            throw new BusinessException('Chỉ có thể duyệt yêu cầu đang chờ.', 'INVALID_STATUS', 422);
        }

        $workDate = $this->formatWorkDate($schedule->work_date);
        $approvedExists = StaffSchedule::query()
            ->where('staff_id', $schedule->staff_id)
            ->whereDate('work_date', $workDate)
            ->where('status', StaffSchedule::STATUS_APPROVED)
            ->where('id', '!=', $schedule->id)
            ->exists();

        if ($approvedExists) {
            throw new BusinessException('Nhân viên đã có ca được duyệt trong ngày này.', 'SCHEDULE_EXISTS', 422);
        }

        $approved = $this->scheduleRepository->update($schedule, [
            'status' => StaffSchedule::STATUS_APPROVED,
            'note' => $note ?? $schedule->note,
            'approved_by' => $owner->id,
            'approved_at' => now(),
        ]);
        $this->invalidateSlotsForSchedule($approved, $schedule->staff?->salon_id);

        return $approved;
    }

    public function approveAll(User $owner, array $filters = []): array
    {
        $salon = $this->resolveOwnerSalon($owner);

        $query = StaffSchedule::query()
            ->forSalon($salon->id)
            ->where('status', StaffSchedule::STATUS_PENDING)
            ->orderBy('work_date')
            ->orderBy('start_time');

        if (! empty($filters['staff_id'])) {
            $query->where('staff_id', $filters['staff_id']);
        }

        $pending = $query->get();
        $approvedCount = 0;
        $skippedCount = 0;

        foreach ($pending as $schedule) {
            try {
                $this->approve($owner, $schedule->id);
                $approvedCount++;
            } catch (BusinessException) {
                $skippedCount++;
            }
        }

        return [
            'approved_count' => $approvedCount,
            'skipped_count' => $skippedCount,
            'total_pending' => $pending->count(),
        ];
    }

    public function reject(User $owner, int $scheduleId, ?string $note = null): StaffSchedule
    {
        $schedule = $this->findScheduleOrFail($scheduleId, ['staff.salon']);
        $this->assertCanManageSalon($schedule->staff?->salon, $owner);

        if ($schedule->status !== StaffSchedule::STATUS_PENDING) {
            throw new BusinessException('Chỉ có thể từ chối yêu cầu đang chờ.', 'INVALID_STATUS', 422);
        }

        return $this->scheduleRepository->update($schedule, [
            'status' => StaffSchedule::STATUS_REJECTED,
            'note' => $note,
            'approved_by' => $owner->id,
            'approved_at' => now(),
        ]);
    }

    protected function resolveOwnerSalon(User $owner): \App\Models\Salon
    {
        $salonId = $this->resolveOwnerSalonId($this->salonRepository, $owner);

        return $this->findSalonOrFail($this->salonRepository, $salonId);
    }

    protected function findStaffOrFail(string $id): Staff
    {
        $staff = $this->staffRepository->findById($id, ['salon']);

        if (! $staff) {
            throw new BusinessException('Nhân viên không tồn tại.', 'STAFF_NOT_FOUND', 404);
        }

        return $staff;
    }

    protected function findScheduleOrFail(int|string $id, array $relations = ['staff.salon']): StaffSchedule
    {
        $schedule = $this->scheduleRepository->findById($id, $relations);

        if (! $schedule) {
            throw new BusinessException('Ca làm việc không tồn tại.', 'SCHEDULE_NOT_FOUND', 404);
        }

        return $schedule;
    }

    protected function normalizePendingListFilters(array $filters): array
    {
        if (! empty($filters['start_date'])) {
            $filters['from'] = $filters['start_date'];
        }

        if (! empty($filters['end_date'])) {
            $filters['to'] = $filters['end_date'];
        }

        $allowedPerPage = [10, 20, 50];
        $perPage = (int) ($filters['per_page'] ?? 10);
        if (! in_array($perPage, $allowedPerPage, true)) {
            $perPage = 10;
        }

        $filters['per_page'] = $perPage;
        $filters['page'] = max(1, (int) ($filters['page'] ?? 1));

        return $filters;
    }

    protected function normalizeDateRangeFilters(array $filters): array
    {
        if (! empty($filters['start_date'])) {
            $filters['from'] = $filters['start_date'];
        }

        if (! empty($filters['end_date'])) {
            $filters['to'] = $filters['end_date'];
        }

        if (empty($filters['from']) && empty($filters['to']) && empty($filters['date'])) {
            $today = Carbon::today()->toDateString();
            $filters['from'] = $today;
            $filters['to'] = $today;
        }

        $allowedPerPage = [10, 20, 50];
        $perPage = (int) ($filters['per_page'] ?? 10);
        if (! in_array($perPage, $allowedPerPage, true)) {
            $perPage = 10;
        }

        $filters['per_page'] = $perPage;
        $filters['page'] = max(1, (int) ($filters['page'] ?? 1));

        return $filters;
    }

    protected function normalizeRangeFilters(array $filters): array
    {
        if (! empty($filters['start_date'])) {
            $filters['from'] = $filters['start_date'];
        }

        if (! empty($filters['end_date'])) {
            $filters['to'] = $filters['end_date'];
        }

        if (! empty($filters['from']) || ! empty($filters['to'])) {
            return $filters;
        }

        $view = $filters['view'] ?? $filters['period'] ?? 'week';
        $reference = ! empty($filters['date']) ? Carbon::parse($filters['date']) : Carbon::today();

        if ($view === 'day') {
            $filters['date'] = $reference->toDateString();
            unset($filters['from'], $filters['to']);

            return $filters;
        }

        if ($view === 'month') {
            $filters['from'] = $reference->copy()->startOfMonth()->toDateString();
            $filters['to'] = $reference->copy()->endOfMonth()->toDateString();

            return $filters;
        }

        $filters['from'] = $reference->copy()->startOfWeek(Carbon::MONDAY)->toDateString();
        $filters['to'] = $reference->copy()->endOfWeek(Carbon::SUNDAY)->toDateString();

        return $filters;
    }

    protected function assertValidShiftTimes(array $data): void
    {
        $start = substr((string) ($data['start_time'] ?? ''), 0, 5);
        $end = substr((string) ($data['end_time'] ?? ''), 0, 5);

        if (! $start || ! $end || $start >= $end) {
            throw new BusinessException('Giờ làm việc không hợp lệ.', 'INVALID_SCHEDULE', 422);
        }
    }

    protected function formatWorkDate(mixed $value): string
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        return substr((string) $value, 0, 10);
    }

    protected function invalidateSlotsForSchedule(StaffSchedule $schedule, ?string $salonId): void
    {
        if (! $salonId) {
            return;
        }

        AvailableSlotsCache::forgetForStaffSchedule($schedule, $salonId);
    }
}
