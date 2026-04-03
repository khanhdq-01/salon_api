<?php

namespace App\Repositories\Interfaces\Admin;

use App\Models\AuditLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface AuditLogRepositoryInterface
{
    public function paginate(array $filters): LengthAwarePaginator;

    public function findById(string $id): ?AuditLog;

    public function deleteAll(): int;

    public function getRecent(int $limit): Collection;
}
