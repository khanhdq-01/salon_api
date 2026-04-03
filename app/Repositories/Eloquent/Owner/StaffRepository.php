<?php

namespace App\Repositories\Eloquent\Owner;

use App\Repositories\Interfaces\Owner\StaffRepositoryInterface;
use App\Models\Staff;
use App\Models\StaffSchedule;
use App\Support\TimeFormat;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class StaffRepository implements StaffRepositoryInterface
{
    public function __construct(
        protected Staff $model
    ) {}

    public function findById(string $id, array $relations = []): ?Staff
    {
        return $this->model->newQuery()->with($relations)->find($id);
    }

    public function paginate(array $filters): LengthAwarePaginator
    {
        $weekStart = now()->startOfWeek()->toDateString();
        $weekEnd = now()->endOfWeek()->toDateString();
        $today = now()->toDateString();

        $query = $this->model->newQuery()
            ->with(['services:id,name', 'user:id,name,email,phone'])
            ->withCount([
                'schedules as weekly_approved_shifts_count' => fn ($scheduleQuery) => $scheduleQuery
                    ->where('status', StaffSchedule::STATUS_APPROVED)
                    ->whereBetween('work_date', [$weekStart, $weekEnd]),
                'schedules as today_approved_shifts_count' => fn ($scheduleQuery) => $scheduleQuery
                    ->where('status', StaffSchedule::STATUS_APPROVED)
                    ->whereDate('work_date', $today),
                'schedules as pending_schedules_count' => fn ($scheduleQuery) => $scheduleQuery
                    ->where('status', StaffSchedule::STATUS_PENDING),
            ]);
        $this->applyFilters($query, $filters);

        return $query
            ->orderBy('name')
            ->paginate(perPage: $filters['per_page'], page: $filters['page']);
    }

    public function create(array $data): Staff
    {
        return $this->model->newQuery()->create($data);
    }

    public function update(Staff $staff, array $data): Staff
    {
        $staff->update($data);

        return $staff->fresh(['services:id,name']);
    }

    public function delete(Staff $staff): bool
    {
        return (bool) $staff->delete();
    }

    public function syncServices(Staff $staff, array $serviceIds): Staff
    {
        $staff->services()->sync($serviceIds);

        return $staff->fresh(['services:id,name,price,duration_minutes']);
    }

    public function replaceSchedules(
        Staff $staff,
        array $schedules,
        string $status = StaffSchedule::STATUS_APPROVED,
        string $submittedBy = StaffSchedule::SUBMITTED_BY_OWNER,
    ): Staff {
        StaffSchedule::query()->where('staff_id', $staff->id)->delete();

        foreach ($schedules as $schedule) {
            StaffSchedule::query()->create([
                'staff_id' => $staff->id,
                'work_date' => $schedule['work_date'],
                'start_time' => TimeFormat::normalize($schedule['start_time']),
                'end_time' => TimeFormat::normalize($schedule['end_time']),
                'status' => $status,
                'submitted_by' => $submittedBy,
            ]);
        }

        return $staff->fresh(['schedules']);
    }

    public function staffProvidesServices(Staff $staff, Collection $serviceIds): bool
    {
        $serviceIds = $serviceIds->filter()->unique()->values();

        if ($serviceIds->isEmpty()) {
            return true;
        }

        $assigned = $staff->services()->whereIn('services.id', $serviceIds->all())->count();

        return $assigned === $serviceIds->count();
    }

    public function countBySalon(string $salonId): int
    {
        return $this->model->newQuery()
            ->where('salon_id', $salonId)
            ->count();
    }

    public function countActiveBySalon(string $salonId): int
    {
        return $this->model->newQuery()
            ->where('salon_id', $salonId)
            ->where('is_active', true)
            ->count();
    }

    public function getActiveBySalonIds(array $salonIds, array $relations = []): Collection
    {
        $query = $this->model->newQuery()
            ->active()
            ->whereIn('salon_id', $salonIds);

        if ($relations !== []) {
            $query->with($relations);
        }

        return $query->get(['id', 'salon_id']);
    }

    public function getActiveForSalonWithServices(string $salonId): Collection
    {
        return $this->model->newQuery()
            ->where('salon_id', $salonId)
            ->active()
            ->with(['services:id'])
            ->get();
    }

    public function getBySalonOrderedByName(string $salonId, array $columns = ['*']): Collection
    {
        return $this->model->newQuery()
            ->where('salon_id', $salonId)
            ->orderBy('name')
            ->get($columns);
    }

    protected function applyFilters(Builder $query, array $filters): void
    {
        if (! empty($filters['salon_id'])) {
            $query->where('salon_id', $filters['salon_id']);
        }

        if ($filters['is_active'] !== null) {
            $query->where('is_active', $filters['is_active']);
        }
    }
}
