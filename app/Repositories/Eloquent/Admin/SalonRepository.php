<?php

namespace App\Repositories\Eloquent\Admin;

use App\Models\Salon;
use App\Repositories\Interfaces\Admin\SalonRepositoryInterface;
use Illuminate\Support\Collection;

class SalonRepository implements SalonRepositoryInterface
{
    public function __construct(
        protected Salon $model
    ) {}

    public function countAll(): int
    {
        return $this->model->newQuery()->count();
    }

    public function countActive(): int
    {
        return $this->model->newQuery()
            ->where('approval_status', Salon::APPROVAL_APPROVED)
            ->where('is_locked', false)
            ->count();
    }

    public function countPending(): int
    {
        return $this->model->newQuery()
            ->where('approval_status', Salon::APPROVAL_PENDING)
            ->count();
    }

    public function countLocked(): int
    {
        return $this->model->newQuery()->where('is_locked', true)->count();
    }

    public function getPendingAlerts(int $limit): Collection
    {
        return $this->model->newQuery()
            ->where('approval_status', Salon::APPROVAL_PENDING)
            ->latest()
            ->limit($limit)
            ->get(['id', 'name', 'created_at']);
    }

    public function getLockedAlerts(int $limit): Collection
    {
        return $this->model->newQuery()
            ->where('is_locked', true)
            ->latest()
            ->limit($limit)
            ->get(['id', 'name', 'created_at']);
    }

    public function findById(string $id): ?Salon
    {
        return $this->model->newQuery()->find($id);
    }

    public function lockByOwnerId(string $ownerId): void
    {
        $this->model->newQuery()
            ->where('owner_id', $ownerId)
            ->update(['is_locked' => true]);
    }

    public function unlockByOwnerId(string $ownerId): void
    {
        $this->model->newQuery()
            ->where('owner_id', $ownerId)
            ->update(['is_locked' => false]);
    }

    public function newOwnerHasOtherSalon(string $newOwnerId, string $excludeSalonId): bool
    {
        return $this->model->newQuery()
            ->where('owner_id', $newOwnerId)
            ->where('id', '!=', $excludeSalonId)
            ->exists();
    }

    public function update(Salon $salon, array $data): Salon
    {
        $salon->update($data);

        return $salon->fresh(['owner:id,name,email,phone']);
    }
}
