<?php

namespace App\Services\Admin;

use App\Repositories\Interfaces\Owner\StaffRepositoryInterface;
use App\Contracts\Services\Admin\AdminStaffManagementServiceInterface;
use App\Contracts\Services\Owner\StaffServiceInterface;
use App\Exceptions\BusinessException;
use App\Models\Staff;
use App\Models\User;
use App\Support\StaffMapper;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class AdminStaffManagementService implements AdminStaffManagementServiceInterface
{
    public function __construct(
        protected StaffServiceInterface $staffService,
        protected StaffRepositoryInterface $staffRepository,
    ) {}

    public function listStaff(array $filters): LengthAwarePaginator
    {
        return $this->staffService->listStaff($filters, $this->adminUser());
    }

    public function createStaff(array $data): Staff
    {
        return $this->staffService->createStaff($data, $this->adminUser());
    }

    public function updateStaff(string $id, array $data): Staff
    {
        return $this->staffService->updateStaff($id, $data, $this->adminUser());
    }

    public function deleteStaff(string $id): bool
    {
        return $this->staffService->deleteStaff($id, $this->adminUser());
    }

    public function setActive(string $id, bool $active): Staff
    {
        return $this->staffService->updateStaff($id, ['is_active' => $active], $this->adminUser());
    }

    public function changeSalon(string $id, string $salonId): Staff
    {
        $staff = $this->staffRepository->findById($id);
        if (! $staff) {
            throw new BusinessException('Staff không tồn tại.', 'STAFF_NOT_FOUND', 404);
        }

        return $this->staffService->updateStaff($id, ['salon_id' => $salonId], $this->adminUser());
    }

    protected function adminUser(): User
    {
        $user = Auth::user();

        if (! $user instanceof User || ! $user->isAdmin()) {
            throw new BusinessException('Forbidden.', 'FORBIDDEN', 403);
        }

        return $user;
    }
}
