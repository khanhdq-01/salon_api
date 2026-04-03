<?php

namespace App\Repositories\Eloquent\Owner;

use App\Models\StaffSchedule;
use App\Repositories\Interfaces\Owner\StaffScheduleRepositoryInterface;
use App\Support\TimeFormat;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class StaffScheduleRepository implements StaffScheduleRepositoryInterface
{
    public function __construct(
        protected StaffSchedule $model
    ) {}

    public function findById(int|string $id, array $relations = []): ?StaffSchedule
    {
        return $this->model->newQuery()->with($relations)->find($id);
    }

    public function findForStaffOnDate(string $staffId, string $date): ?StaffSchedule
    {
        return $this->model->newQuery()
            ->where('staff_id', $staffId)
            ->whereDate('work_date', $date)
            ->first();
    }

    public function findApprovedForStaffOnDate(string $staffId, string $date): ?StaffSchedule
    {
        return $this->model->newQuery()
            ->where('staff_id', $staffId)
            ->whereDate('work_date', $date)
            ->where('status', StaffSchedule::STATUS_APPROVED)
            ->first();
    }

    public function getForStaffIdsOnDate(array $staffIds, string $date, ?string $status = null): Collection
    {
        $query = $this->model->newQuery()
            ->whereIn('staff_id', $staffIds)
            ->whereDate('work_date', $date);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->get();
    }

    public function paginateForSalon(string $salonId, array $filters): LengthAwarePaginator
    {
        $status = $filters['status'] ?? null;
        $query = $this->buildSalonQuery($salonId, $filters)
            ->with(['staff:id,name,salon_id', 'approver:id,name']);

        if ($status === StaffSchedule::STATUS_APPROVED) {
            $query->orderByDesc('work_date')->orderBy('start_time');
        } else {
            $query->orderByDesc('created_at');
        }

        return $query->paginate(
            perPage: $filters['per_page'] ?? 10,
            page: $filters['page'] ?? 1,
        );
    }

    public function listForCalendar(string $salonId, array $filters): Collection
    {
        return $this->buildSalonQuery($salonId, array_merge($filters, [
            'status' => $filters['status'] ?? StaffSchedule::STATUS_APPROVED,
        ]))
            ->with(['staff:id,name'])
            ->orderBy('work_date')
            ->orderBy('start_time')
            ->get();
    }

    public function listForStaff(string $staffId, array $filters): Collection
    {
        $query = $this->model->newQuery()->where('staff_id', $staffId);
        $this->applyDateFilters($query, $filters);

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('work_date')->orderBy('start_time')->get();
    }

    public function create(array $data): StaffSchedule
    {
        return $this->model->newQuery()->create([
            'staff_id' => $data['staff_id'],
            'work_date' => $data['work_date'],
            'start_time' => TimeFormat::normalize($data['start_time']),
            'end_time' => TimeFormat::normalize($data['end_time']),
            'status' => $data['status'] ?? StaffSchedule::STATUS_PENDING,
            'submitted_by' => $data['submitted_by'] ?? StaffSchedule::SUBMITTED_BY_OWNER,
            'note' => $data['note'] ?? null,
            'approved_by' => $data['approved_by'] ?? null,
            'approved_at' => $data['approved_at'] ?? null,
        ]);
    }

    public function update(StaffSchedule $schedule, array $data): StaffSchedule
    {
        $payload = array_filter([
            'work_date' => $data['work_date'] ?? null,
            'start_time' => isset($data['start_time']) ? TimeFormat::normalize($data['start_time']) : null,
            'end_time' => isset($data['end_time']) ? TimeFormat::normalize($data['end_time']) : null,
            'status' => $data['status'] ?? null,
            'submitted_by' => $data['submitted_by'] ?? null,
            'note' => array_key_exists('note', $data) ? $data['note'] : null,
            'approved_by' => array_key_exists('approved_by', $data) ? $data['approved_by'] : null,
            'approved_at' => array_key_exists('approved_at', $data) ? $data['approved_at'] : null,
        ], fn ($value) => $value !== null);

        $schedule->update($payload);

        return $schedule->fresh(['staff:id,name', 'approver:id,name']);
    }

    public function delete(StaffSchedule $schedule): bool
    {
        return (bool) $schedule->delete();
    }

    public function countPendingBySalon(string $salonId): int
    {
        return $this->model->newQuery()
            ->forSalon($salonId)
            ->where('status', StaffSchedule::STATUS_PENDING)
            ->count();
    }

    protected function buildSalonQuery(string $salonId, array $filters): Builder
    {
        $query = $this->model->newQuery()->forSalon($salonId);

        if (! empty($filters['staff_id'])) {
            $query->where('staff_id', $filters['staff_id']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['submitted_by'])) {
            $query->where('submitted_by', $filters['submitted_by']);
        }

        $this->applyDateFilters($query, $filters);

        return $query;
    }

    protected function applyDateFilters(Builder $query, array $filters): void
    {
        if (! empty($filters['date'])) {
            $query->whereDate('work_date', $filters['date']);
        }

        if (! empty($filters['from'])) {
            $query->whereDate('work_date', '>=', $filters['from']);
        }

        if (! empty($filters['to'])) {
            $query->whereDate('work_date', '<=', $filters['to']);
        }
    }
}
