<?php

namespace App\Services\Shared;

use App\Repositories\Interfaces\Owner\SalonRepositoryInterface;
use App\Exceptions\BusinessException;
use App\Models\Salon;
use App\Models\User;

trait AssertsSalonOwnership
{
    protected function findSalonOrFail(SalonRepositoryInterface $repository, string $salonId): Salon
    {
        $salon = $repository->findById($salonId);

        if (! $salon) {
            throw new BusinessException('Salon không tồn tại.', 'SALON_NOT_FOUND', 404);
        }

        return $salon;
    }

    protected function assertCanManageSalon(Salon $salon, User $actor): void
    {
        if ($actor->isAdmin()) {
            return;
        }

        if ($actor->isOwner() && $salon->owner_id === $actor->id) {
            return;
        }

        throw new BusinessException('Không có quyền quản lý salon này.', 'FORBIDDEN', 403);
    }

    protected function resolveOwnerSalonId(SalonRepositoryInterface $repository, User $owner): string
    {
        $salon = $repository->findByOwnerId($owner->id);

        if (! $salon) {
            throw new BusinessException('Owner chưa có salon.', 'OWNER_SALON_NOT_FOUND', 404);
        }

        return $salon->id;
    }
}
