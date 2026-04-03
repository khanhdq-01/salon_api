<?php

namespace App\Services\Admin;

use App\Contracts\Services\Admin\AdminSalonManagementServiceInterface;
use App\Contracts\Services\Owner\SalonServiceInterface;
use App\Exceptions\BusinessException;
use App\Models\Salon;
use App\Models\User;
use App\Repositories\Interfaces\Admin\SalonRepositoryInterface as AdminSalonRepositoryInterface;
use App\Repositories\Interfaces\Admin\UserRepositoryInterface as AdminUserRepositoryInterface;
use App\Services\Admin\SalonSubscriptionProvisioningService;
use App\Support\SubscriptionExpiry;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminSalonManagementService implements AdminSalonManagementServiceInterface
{
    public function __construct(
        protected SalonServiceInterface $salonService,
        protected AdminUserRepositoryInterface $userRepository,
        protected AdminSalonRepositoryInterface $salonRepository,
        protected SalonSubscriptionProvisioningService $subscriptionProvisioningService,
    ) {}

    public function listSalons(array $filters): LengthAwarePaginator
    {
        SubscriptionExpiry::syncExpiredSubscriptions();

        return $this->salonService->listSalons(
            array_merge($filters, ['with_trashed' => true]),
            $this->adminUser()
        );
    }

    public function getSalon(string $id): Salon
    {
        return $this->salonService->getSalonById($id, $this->adminUser());
    }

    public function createSalon(array $data): Salon
    {
        throw new BusinessException('Admin không có quyền tạo salon.', 'FORBIDDEN', 403);
    }

    public function updateSalon(string $id, array $data): Salon
    {
        return $this->salonService->updateSalon($id, $data, $this->adminUser());
    }

    public function updateProfile(string $id, array $data): Salon
    {
        return $this->updateSalon($id, $data);
    }

    public function approveSalon(string $id): Salon
    {
        return DB::transaction(function () use ($id) {
            $salon = $this->salonService->updateSalonStatus($id, [
                'approval_status' => Salon::APPROVAL_APPROVED,
                'is_locked' => false,
            ], $this->adminUser());

            $salon->load('requestedPackage');

            $this->subscriptionProvisioningService->provisionOnSalonApproval(
                $salon,
                $this->adminUser()
            );

            SubscriptionExpiry::syncExpiredSubscriptions();

            return $salon->fresh([
                'owner:id,name,email,phone,status',
                'owner.subscriptions.package',
                'requestedPackage',
            ]);
        });
    }

    public function rejectSalon(string $id): Salon
    {
        return $this->salonService->updateSalonStatus($id, [
            'approval_status' => Salon::APPROVAL_REJECTED,
        ], $this->adminUser());
    }

    public function lockSalon(string $id): Salon
    {
        return $this->salonService->updateSalonStatus($id, [
            'is_locked' => true,
        ], $this->adminUser());
    }

    public function unlockSalon(string $id): Salon
    {
        return $this->salonService->updateSalonStatus($id, [
            'is_locked' => false,
        ], $this->adminUser());
    }

    public function activateSalon(string $id): Salon
    {
        return $this->salonService->updateSalonStatus($id, [
            'approval_status' => Salon::APPROVAL_APPROVED,
            'is_locked' => false,
            'status' => Salon::STATUS_OPEN,
        ], $this->adminUser());
    }

    public function deactivateSalon(string $id): Salon
    {
        return $this->salonService->updateSalonStatus($id, [
            'is_locked' => true,
            'status' => Salon::STATUS_CLOSED,
        ], $this->adminUser());
    }

    public function changeOwner(string $id, string $ownerId): Salon
    {
        $owner = $this->userRepository->findById($ownerId);
        if (! $owner?->isOwner()) {
            throw new BusinessException('owner_id phải là tài khoản owner.', 'INVALID_OWNER', 422);
        }

        if ($this->salonRepository->newOwnerHasOtherSalon($ownerId, $id)) {
            throw new BusinessException('Owner mới đã có salon. MVP chỉ hỗ trợ 1 salon/owner.', 'OWNER_SALON_EXISTS', 422);
        }

        return DB::transaction(function () use ($id, $ownerId) {
            $salon = $this->getSalon($id);
            $this->salonRepository->update($salon, ['owner_id' => $ownerId]);

            return $salon->fresh(['owner:id,name,email,phone']);
        });
    }

    public function restoreSalon(string $id): Salon
    {
        return $this->salonService->restoreSalon($id, $this->adminUser());
    }

    public function deleteSalon(string $id): bool
    {
        return $this->salonService->deleteSalon($id, $this->adminUser());
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
