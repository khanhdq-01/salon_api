<?php

namespace App\Services\Admin;

use App\Contracts\Services\Admin\AdminAuditLogServiceInterface;
use App\Exceptions\BusinessException;
use App\Models\AuditLog;
use App\Repositories\Interfaces\Admin\AuditLogRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdminAuditLogService implements AdminAuditLogServiceInterface
{
    public function __construct(
        protected AuditLogRepositoryInterface $auditLogRepository
    ) {}

    public function listLogs(array $filters): LengthAwarePaginator
    {
        return $this->auditLogRepository->paginate($filters);
    }

    public function getLog(string $id): AuditLog
    {
        $log = $this->auditLogRepository->findById($id);

        if (! $log) {
            throw new BusinessException('Audit log không tồn tại.', 'AUDIT_LOG_NOT_FOUND', 404);
        }

        return $log;
    }

    public function clearAll(): int
    {
        return $this->auditLogRepository->deleteAll();
    }
}
