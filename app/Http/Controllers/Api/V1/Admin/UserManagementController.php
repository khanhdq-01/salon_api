<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Contracts\Services\Admin\AdminUserManagementServiceInterface;
use App\Http\Controllers\Concerns\HandlesServiceException;
use App\Http\Controllers\Concerns\PaginatesApiResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\AdminResetPasswordRequest;
use App\Http\Requests\Api\V1\Admin\AdminUserPasswordRequest;
use App\Http\Requests\Api\V1\Admin\AdminUserProfileRequest;
use App\Http\Requests\Api\V1\Admin\AdminUserRoleRequest;
use App\Http\Requests\Api\V1\Admin\ListAdminUsersRequest;
use App\Http\Requests\Api\V1\Admin\StoreAdminUserRequest;
use App\Http\Requests\Api\V1\Admin\UpdateAdminUserRequest;
use App\Http\Requests\Shared\RouteIdRequest;
use App\Http\Resources\Api\V1\Admin\AdminUserResource;
use Illuminate\Http\JsonResponse;

class UserManagementController extends Controller
{
    use HandlesServiceException, PaginatesApiResource;

    public function __construct(
        protected AdminUserManagementServiceInterface $userManagementService
    ) {}

    public function index(ListAdminUsersRequest $request): JsonResponse
    {
        $paginator = $this->userManagementService->listUsers($request->validated());

        return $this->paginatedResource($paginator, AdminUserResource::class, 'Lấy danh sách user thành công');
    }

    public function show(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new AdminUserResource($this->userManagementService->getUser($id)),
            'Lấy chi tiết user thành công'
        );
    }

    public function store(StoreAdminUserRequest $request): JsonResponse
    {
        return $this->tryService(
            fn () => $this->created(
                new AdminUserResource($this->userManagementService->createUser($request->validated())),
                'Tạo user thành công'
            )
        );
    }

    public function update(UpdateAdminUserRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new AdminUserResource($this->userManagementService->updateUser($id, $request->validated())),
            'Cập nhật user thành công'
        );
    }

    public function updateProfile(AdminUserProfileRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new AdminUserResource($this->userManagementService->updateProfile($id, $request->validated())),
            'Cập nhật profile thành công'
        );
    }

    public function changePassword(AdminUserPasswordRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new AdminUserResource(
                $this->userManagementService->changePassword($id, $request->validated('password'))
            ),
            'Đổi mật khẩu thành công'
        );
    }

    public function resetPassword(AdminResetPasswordRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new AdminUserResource(
                $this->userManagementService->resetPassword($id, $request->validated('password'))
            ),
            'Reset mật khẩu thành công'
        );
    }

    public function changeRole(AdminUserRoleRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new AdminUserResource(
                $this->userManagementService->changeRole($id, $request->validated('role'))
            ),
            'Đổi role thành công'
        );
    }

    public function lock(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new AdminUserResource($this->userManagementService->lockUser($id)),
            'Khóa user thành công'
        );
    }

    public function unlock(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => new AdminUserResource($this->userManagementService->unlockUser($id)),
            'Mở khóa user thành công'
        );
    }

    public function destroy(RouteIdRequest $request, string $id): JsonResponse
    {
        return $this->tryService(
            fn () => $this->userManagementService->deleteUser($id),
            'Xóa user thành công'
        );
    }
}
