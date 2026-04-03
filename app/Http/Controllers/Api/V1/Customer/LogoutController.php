<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Contracts\Services\Customer\AuthServiceInterface;
use App\Http\Controllers\Controller;
use App\Support\AuditLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function __construct(
        protected AuthServiceInterface $authService
    ) {}

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user) {
            AuditLogger::log('logout', 'auth', $user->id, 'success', [
                'target_label' => $user->email,
                'portal' => $user->role?->name,
            ], $user->id);
        }

        $this->authService->logout();

        return $this->success([], 'Đăng xuất thành công');
    }

    public function logoutAll(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user) {
            AuditLogger::log('logout', 'auth', $user->id, 'success', [
                'target_label' => $user->email,
                'portal' => $user->role?->name,
                'sub_action' => 'logout_all',
            ], $user->id);
        }

        $this->authService->logoutAllDevices($user);

        return $this->success([], 'Đã đăng xuất tất cả thiết bị');
    }
}
