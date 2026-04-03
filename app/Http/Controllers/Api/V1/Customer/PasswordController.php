<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Contracts\Services\Customer\AuthServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\ForgotPasswordRequest;
use App\Http\Requests\Api\V1\Customer\ChangePasswordRequest;
use App\Http\Requests\Api\V1\Customer\ResetPasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    public function __construct(
        protected AuthServiceInterface $authService
    ) {}

    public function change(ChangePasswordRequest $request): JsonResponse
    {
        $this->authService->changePassword($request->user(), $request->new_password);

        return $this->success([], 'Đổi mật khẩu thành công');
    }

    public function reset(ResetPasswordRequest $request): JsonResponse
    {
        $this->authService->resetPassword(
            $request->email,
            $request->token,
            $request->password
        );

        return $this->success([], 'Đặt lại mật khẩu thành công');
    }

    public function forgot(ForgotPasswordRequest $request): JsonResponse
    {
        $this->authService->forgotPassword($request->email);

        return $this->success([], 'Nếu Email tồn tại trong hệ thống, chúng tôi đã gửi Email hướng dẫn.');
    }
}
