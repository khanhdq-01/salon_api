<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Contracts\Services\Admin\AdminAuditLogServiceInterface;
use App\Http\Controllers\Concerns\HandlesServiceException;
use App\Http\Controllers\Concerns\PaginatesApiResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\ListAdminAuditLogsRequest;
use App\Http\Requests\Shared\RouteIdRequest;
use App\Http\Resources\Api\V1\Admin\AdminAuditLogResource;
use Illuminate\Http\JsonResponse;

class AuditLogController extends Controller
{
    use HandlesServiceException, PaginatesApiResource;

    public function __construct(
        protected AdminAuditLogServiceInterface $auditLogService
    ) {}

    public function index(ListAdminAuditLogsRequest $request): JsonResponse
    {
        $paginator = $this->auditLogService->listLogs($request->validated());

        return $this->paginatedResource($paginator, AdminAuditLogResource::class, 'Lấy audit logs thành công');
    }

    public function show(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new AdminAuditLogResource($this->auditLogService->getLog($id)),
            'Lấy chi tiết audit log thành công',
        );
    }

    public function destroyAll(): JsonResponse
    {
        return $this->tryService(
            fn () => ['deleted' => $this->auditLogService->clearAll()],
            'Đã xóa toàn bộ audit logs',
        );
    }
}
