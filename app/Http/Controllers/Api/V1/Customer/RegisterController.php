<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Contracts\Services\Customer\AuthServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\RegisterRequest;
use App\Http\Resources\Api\V1\Customer\RegisterSuccessResource;
use App\Support\AuditLogger;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function __construct(
        protected AuthServiceInterface $authService
    ) {}

    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());
        $user = $result['user'] ?? null;

        if ($user) {
            AuditLogger::log('register', 'auth', $user->id, 'success', [
                'target_label' => $user->email,
                'portal' => $user->role?->name ?? 'customer',
                'verification_required' => true,
            ], $user->id);
        }

        return $this->created(new RegisterSuccessResource($result), 'Đăng ký thành công. Vui lòng kiểm tra email để xác thực tài khoản.');
    }
}
