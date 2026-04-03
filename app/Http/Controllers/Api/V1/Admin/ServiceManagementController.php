<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Contracts\Services\Admin\AdminServiceManagementServiceInterface;
use App\Http\Controllers\Concerns\HandlesServiceException;
use App\Http\Controllers\Concerns\PaginatesApiResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\ListAdminServicesRequest;
use App\Http\Requests\Api\V1\Admin\StoreAdminServiceRequest;
use App\Http\Requests\Api\V1\Admin\UpdateAdminServiceRequest;
use App\Http\Requests\Shared\RouteIdRequest;
use App\Http\Resources\Api\V1\Owner\ServiceResource;
use Illuminate\Http\JsonResponse;

class ServiceManagementController extends Controller
{
    use HandlesServiceException, PaginatesApiResource;

    public function __construct(
        protected AdminServiceManagementServiceInterface $serviceManagement
    ) {}

    public function index(ListAdminServicesRequest $request): JsonResponse
    {
        $paginator = $this->serviceManagement->listServices($request->validated());

        return $this->paginatedResource($paginator, ServiceResource::class, 'Lấy danh sách service thành công');
    }

    public function store(StoreAdminServiceRequest $request): JsonResponse
    {
        return $this->tryService(
            fn () => $this->created(
                new ServiceResource($this->serviceManagement->createService($request->validated())),
                'Tạo service thành công'
            )
        );
    }

    public function update(UpdateAdminServiceRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new ServiceResource($this->serviceManagement->updateService($id, $request->validated())),
            'Cập nhật service thành công'
        );
    }

    public function destroy(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => $this->serviceManagement->deleteService($id),
            'Xóa service thành công'
        );
    }

    public function activate(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new ServiceResource($this->serviceManagement->setActive($id, true)),
            'Kích hoạt service thành công'
        );
    }

    public function deactivate(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new ServiceResource($this->serviceManagement->setActive($id, false)),
            'Vô hiệu hóa service thành công'
        );
    }
}
