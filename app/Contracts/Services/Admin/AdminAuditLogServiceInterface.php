<?php

namespace App\Contracts\Services\Admin;

use App\Models\AuditLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AdminAuditLogServiceInterface
{
    public function listLogs(array $filters): LengthAwarePaginator;

    public function getLog(string $id): AuditLog;

    public function clearAll(): int;
}
