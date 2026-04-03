<?php

namespace App\Services\Owner;

use App\Repositories\Interfaces\Owner\SalonRepositoryInterface;
use App\Repositories\Interfaces\Owner\ServiceRepositoryInterface;
use App\Repositories\Interfaces\Owner\StaffRepositoryInterface;
use App\Contracts\Services\Owner\OwnerPackageLimitServiceInterface;
use App\Contracts\Services\Owner\StaffServiceInterface;
use App\Exceptions\BusinessException;
use App\Models\Staff;
use App\Models\StaffSchedule;
use App\Models\User;
use App\Services\Shared\AssertsSalonOwnership;
use App\Support\AvailableSlotsCache;
use App\Support\StaffAccountProvisioner;
use App\Support\StaffMapper;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class StaffService implements StaffServiceInterface
{
    use AssertsSalonOwnership;

    public function __construct(
        protected StaffRepositoryInterface $staffRepository,
        protected SalonRepositoryInterface $salonRepository,
        protected ServiceRepositoryInterface $serviceRepository,
        protected OwnerPackageLimitServiceInterface $packageLimitService,
    ) {}

    public function listStaff(array $filters, ?User $actor = null): LengthAwarePaginator
    {
        $filters = StaffMapper::normalizeListFilters($filters);

        if ((! $actor || $actor->isCustomer()) && $filters['is_active'] === null) {
            $filters['is_active'] = true;
        }

        if ($actor?->isOwner() && empty($filters['salon_id'])) {
            $filters['salon_id'] = $this->resolveOwnerSalonId($this->salonRepository, $actor);
        }

        return $this->staffRepository->paginate($filters);
    }

    public function createStaff(array $data, User $actor): Staff
    {
        $payload = StaffMapper::normalizeCreate($data);
        $salon = $this->findSalonOrFail($this->salonRepository, $payload['salon_id']);
        $this->assertCanManageSalon($salon, $actor);

        if ($actor->isOwner()) {
            $this->packageLimitService->assertCanAddStaff($actor, $salon->id);
        }

        return DB::transaction(function () use ($payload, $data, $actor) {
            $staff = $this->staffRepository->create($payload);

            if (! empty($data['email']) && ! empty($data['password'])) {
                StaffAccountProvisioner::createForStaff($staff, $actor, [
                    'email' => $data['email'],
                    'password' => $data['password'],
                    'phone' => $data['phone'] ?? null,
                    'name' => $payload['name'],
                ]);
            }

            return $staff->fresh(['services:id,name', 'schedules', 'user:id,name,email,phone']);
        });
    }

    public function getStaffById(string $id): Staff
    {
        return $this->findStaffOrFail($id, ['salon:id,owner_id,name,approval_status,is_locked,status', 'services:id,name', 'schedules', 'user:id,name,email,phone']);
    }

    public function updateStaff(string $id, array $data, User $actor): Staff
    {
        $staff = $this->findStaffOrFail($id, ['salon', 'user']);
        $this->assertCanManageSalon($staff->salon, $actor);

        return DB::transaction(function () use ($staff, $data, $actor) {
            $updated = $this->staffRepository->update($staff, StaffMapper::normalizeUpdate($data));

            StaffAccountProvisioner::syncAccount($updated, $actor, [
                'email' => $data['email'] ?? null,
                'password' => $data['password'] ?? null,
                'phone' => $data['phone'] ?? null,
                'name' => $data['name'] ?? null,
            ]);

            if (array_key_exists('is_active', $data)) {
                AvailableSlotsCache::forgetSalonWide($staff->salon_id);
            }

            return $updated->fresh(['services:id,name', 'schedules', 'user:id,name,email,phone']);
        });
    }

    public function deleteStaff(string $id, User $actor): bool
    {
        $staff = $this->findStaffOrFail($id, ['salon']);
        $this->assertCanManageSalon($staff->salon, $actor);
        $salonId = $staff->salon_id;

        $deleted = $this->staffRepository->delete($staff);

        if ($deleted) {
            AvailableSlotsCache::forgetSalonWide($salonId);
        }

        return $deleted;
    }

    public function updateSchedule(string $id, array $data, User $actor): Staff
    {
        $staff = $this->findStaffOrFail($id, ['salon']);
        $this->assertCanManageSalon($staff->salon, $actor);

        $schedules = collect($data['schedules'] ?? [])->map(function (array $item) {
            return [
                'work_date' => $item['work_date'] ?? $item['date'] ?? null,
                'start_time' => $item['start_time'] ?? $item['start'] ?? null,
                'end_time' => $item['end_time'] ?? $item['end'] ?? null,
            ];
        })->all();

        foreach ($schedules as $schedule) {
            if (! $schedule['work_date'] || ! $schedule['start_time'] || ! $schedule['end_time']) {
                throw new BusinessException('Lịch làm việc không hợp lệ.', 'INVALID_SCHEDULE');
            }
        }

        $previousDates = $staff->schedules()
            ->where('status', StaffSchedule::STATUS_APPROVED)
            ->pluck('work_date')
            ->all();

        $updated = $this->staffRepository->replaceSchedules(
            $staff,
            $schedules,
            StaffSchedule::STATUS_APPROVED,
            StaffSchedule::SUBMITTED_BY_OWNER,
        );

        AvailableSlotsCache::forgetSalonDates(
            $staff->salon_id,
            array_merge(
                $previousDates,
                array_column($schedules, 'work_date')
            )
        );

        return $updated;
    }

    public function assignServices(string $id, array $serviceIds, User $actor): Staff
    {
        $staff = $this->findStaffOrFail($id, ['salon']);
        $this->assertCanManageSalon($staff->salon, $actor);

        $services = $this->serviceRepository->findActiveByIdsForSalon($serviceIds, $staff->salon_id);

        if ($services->count() !== count(array_unique($serviceIds))) {
            throw new BusinessException('Một hoặc nhiều dịch vụ không thuộc salon.', 'INVALID_SERVICE_IDS');
        }

        $updated = $this->staffRepository->syncServices($staff, $serviceIds);
        AvailableSlotsCache::forgetSalonWide($staff->salon_id);

        return $updated;
    }

    protected function findStaffOrFail(string $id, array $relations = []): Staff
    {
        $staff = $this->staffRepository->findById($id, $relations);

        if (! $staff) {
            throw new BusinessException('Nhân viên không tồn tại.', 'STAFF_NOT_FOUND', 404);
        }

        return $staff;
    }
}
