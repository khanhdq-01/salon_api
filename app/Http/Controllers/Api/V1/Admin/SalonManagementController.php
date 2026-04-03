<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Contracts\Services\Admin\AdminSalonManagementServiceInterface;
use App\Http\Controllers\Concerns\HandlesServiceException;
use App\Http\Controllers\Concerns\PaginatesApiResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\AdminSalonOwnerRequest;
use App\Http\Requests\Api\V1\Admin\ListAdminSalonsRequest;
use App\Http\Requests\Api\V1\Admin\StoreAdminSalonRequest;
use App\Http\Requests\Api\V1\Admin\UpdateAdminSalonRequest;
use App\Http\Requests\Shared\RouteIdRequest;
use App\Http\Resources\Api\V1\Customer\SalonResource;
use Illuminate\Http\JsonResponse;

class SalonManagementController extends Controller
{
    use HandlesServiceException, PaginatesApiResource;

    public function __construct(
        protected AdminSalonManagementServiceInterface $salonManagementService
    ) {}

    public function index(ListAdminSalonsRequest $request): JsonResponse
    {
        $paginator = $this->salonManagementService->listSalons($request->validated());

        return $this->paginatedResource($paginator, SalonResource::class, 'Lấy danh sách salon thành công');
    }

    public function show(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new SalonResource($this->salonManagementService->getSalon($id)),
            'Lấy chi tiết salon thành công'
        );
    }

    public function store(StoreAdminSalonRequest $request): JsonResponse
    {
        return $this->tryService(
            fn () => $this->created(
                new SalonResource($this->salonManagementService->createSalon($request->validated())),
                'Tạo salon thành công'
            )
        );
    }

    public function update(UpdateAdminSalonRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new SalonResource($this->salonManagementService->updateSalon($id, $request->validated())),
            'Cập nhật salon thành công'
        );
    }

    public function updateProfile(UpdateAdminSalonRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new SalonResource($this->salonManagementService->updateProfile($id, $request->validated())),
            'Cập nhật profile salon thành công'
        );
    }

    public function approve(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new SalonResource($this->salonManagementService->approveSalon($id)),
            'Duyệt salon thành công'
        );
    }

    public function reject(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new SalonResource($this->salonManagementService->rejectSalon($id)),
            'Từ chối salon thành công'
        );
    }

    public function activate(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new SalonResource($this->salonManagementService->activateSalon($id)),
            'Kích hoạt salon thành công'
        );
    }

    public function deactivate(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new SalonResource($this->salonManagementService->deactivateSalon($id)),
            'Vô hiệu hóa salon thành công'
        );
    }

    public function lock(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new SalonResource($this->salonManagementService->lockSalon($id)),
            'Khóa salon thành công'
        );
    }

    public function unlock(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new SalonResource($this->salonManagementService->unlockSalon($id)),
            'Mở khóa salon thành công'
        );
    }

    public function changeOwner(AdminSalonOwnerRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new SalonResource(
                $this->salonManagementService->changeOwner($id, $request->validated('owner_id'))
            ),
            'Đổi owner salon thành công'
        );
    }

    public function destroy(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => $this->salonManagementService->deleteSalon($id),
            'Xóa salon thành công'
        );
    }

    public function restore(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new SalonResource($this->salonManagementService->restoreSalon($id)),
            'Khôi phục salon thành công'
        );
    }
}
