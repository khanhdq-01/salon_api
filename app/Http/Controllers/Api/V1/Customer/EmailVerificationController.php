<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Contracts\Services\Customer\EmailVerificationServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\ResendVerificationEmailRequest;
use App\Http\Requests\Api\V1\Customer\VerifyEmailRequest;
use App\Http\Resources\Api\V1\Customer\EmailVerificationResource;
use Illuminate\Http\JsonResponse;

class EmailVerificationController extends Controller
{
    public function __construct(
        protected EmailVerificationServiceInterface $emailVerificationService
    ) {}

    public function verify(VerifyEmailRequest $request): JsonResponse
    {
        $user = $this->emailVerificationService->verify(
            $request->validated('email'),
            $request->validated('token')
        );

        return $this->success(new EmailVerificationResource($user), 'Email đã được xác thực thành công.');
    }

    public function resend(ResendVerificationEmailRequest $request): JsonResponse
    {
        $this->emailVerificationService->resend($request->validated('email'));

        return $this->success([], 'Nếu email chưa được xác thực, chúng tôi đã gửi email xác thực.');
    }
}
