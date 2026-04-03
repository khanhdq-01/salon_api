<?php

namespace App\Repositories\Interfaces\Owner;

use App\Models\Salon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface SalonRepositoryInterface
{
    public function findById(string $id, array $relations = [], bool $withTrashed = false): ?Salon;

    public function findByOwnerId(string $ownerId, bool $withTrashed = false): ?Salon;

    public function create(array $data): Salon;

    public function update(Salon $salon, array $data): Salon;

    public function delete(Salon $salon): bool;

    public function paginate(array $filters): LengthAwarePaginator;

    public function getPubliclyVisibleBasic(): Collection;
}
