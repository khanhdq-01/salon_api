<?php

namespace App\Repositories\Interfaces\Owner;

use App\Models\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ServiceRepositoryInterface
{
    public function findById(string $id, array $relations = []): ?Service;

    public function findActiveByIdsForSalon(array $ids, string $salonId): Collection;

    public function paginate(array $filters): LengthAwarePaginator;

    public function create(array $data): Service;

    public function update(Service $service, array $data): Service;

    public function delete(Service $service): bool;

    public function incrementBookingsCount(Service $service, int $by = 1): void;

    public function countBySalon(string $salonId): int;

    public function getMinDurationBySalonIds(array $salonIds): Collection;

    public function listPopular(int $limit): Collection;
}
