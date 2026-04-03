<?php

namespace App\Repositories\Interfaces\Admin;

use App\Models\Salon;
use Illuminate\Support\Collection;

interface SalonRepositoryInterface
{
    public function countAll(): int;

    public function countActive(): int;

    public function countPending(): int;

    public function countLocked(): int;

    public function getPendingAlerts(int $limit): Collection;

    public function getLockedAlerts(int $limit): Collection;

    public function findById(string $id): ?Salon;

    public function lockByOwnerId(string $ownerId): void;

    public function unlockByOwnerId(string $ownerId): void;

    public function newOwnerHasOtherSalon(string $newOwnerId, string $excludeSalonId): bool;

    public function update(Salon $salon, array $data): Salon;
}
