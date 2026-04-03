<?php

namespace App\Repositories\Interfaces\Admin;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function paginate(array $filters, array $relations, array $withCount): LengthAwarePaginator;

    public function findById(string $id): ?User;

    public function findByIdWithRole(string $id): ?User;

    public function update(User $user, array $data): User;

    public function delete(User $user): bool;

    public function countAll(): int;

    public function countByRoleName(string $roleName): int;

    public function countSuspended(): int;

    public function getSuspendedAlerts(int $limit): Collection;
}
