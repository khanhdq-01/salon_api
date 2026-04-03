<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Contracts\Services\Admin\AdminEmailTemplateManagementServiceInterface;
use App\Http\Controllers\Concerns\HandlesServiceException;
use App\Http\Controllers\Concerns\PaginatesApiResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\ListAdminEmailTemplatesRequest;
use App\Http\Requests\Api\V1\Admin\UpdateAdminEmailTemplateRequest;
use App\Http\Requests\Shared\RouteIdRequest;
use App\Http\Resources\Api\V1\Admin\AdminEmailTemplateResource;
use Illuminate\Http\JsonResponse;

class EmailTemplateManagementController extends Controller
{
    use HandlesServiceException, PaginatesApiResource;

    public function __construct(
        protected AdminEmailTemplateManagementServiceInterface $emailTemplateService
    ) {}

    public function index(ListAdminEmailTemplatesRequest $request): JsonResponse
    {
        $paginator = $this->emailTemplateService->listTemplates($request->validated());

        return $this->paginatedResource($paginator, AdminEmailTemplateResource::class, 'Lấy danh sách email templates thành công');
    }

    public function show(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new AdminEmailTemplateResource($this->emailTemplateService->findOrFail($id)),
            'Lấy email template thành công',
        );
    }

    public function update(UpdateAdminEmailTemplateRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new AdminEmailTemplateResource($this->emailTemplateService->updateTemplate($id, $request->validated())),
            'Cập nhật email template thành công',
        );
    }
}
