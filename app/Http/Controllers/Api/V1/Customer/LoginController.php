<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Contracts\Services\Customer\AuthServiceInterface;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\LoginRequest;
use App\Http\Resources\Api\V1\Customer\AuthTokenResource;
use App\Models\Role;
use App\Support\AuditLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct(
        protected AuthServiceInterface $authService
    ) {}

    public function __invoke(LoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->safe()->only(['email', 'password']);
            $token = $this->authService->login($credentials);

            /** @var \App\Models\User|null $user */
            $user = auth()->user()?->load('role');

            $portal = $request->validated('portal');
            if ($portal && $user && ! $user->hasRole($portal)) {
                Auth::logout();

                $messages = [
                    Role::CUSTOMER => __('messages.forbidden_portal.customer'),
                    Role::OWNER => __('messages.forbidden_portal.owner'),
                    Role::ADMIN => __('messages.forbidden_portal.admin'),
                    Role::STAFF => __('messages.forbidden_portal.staff'),
                ];

                AuditLogger::log('login', 'auth', $user?->id, 'failed', [
                    'target_label' => $user?->email ?? $credentials['email'],
                    'portal' => $portal,
                ], $user?->id);

                return $this->forbidden($messages[$portal] ?? __('messages.forbidden_portal.default'));
            }

            AuditLogger::log('login', 'auth', $user?->id, 'success', [
                'target_label' => $user?->email,
                'portal' => $user?->role?->name,
            ], $user?->id);

            return $this->success(
                new AuthTokenResource(['token' => $token, 'user' => $user]),
                __('messages.login_success')
            );
        } catch (BusinessException $exception) {
            AuditLogger::log('login', 'auth', null, 'failed', [
                'target_label' => $request->input('email'),
                'portal' => $request->input('portal'),
                'reason' => $exception->getErrorCode(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
                'errors' => ['code' => $exception->getErrorCode()],
            ], $exception->getCode() >= 100 && $exception->getCode() < 600 ? $exception->getCode() : 422);
        } catch (\Throwable) {
            AuditLogger::log('login', 'auth', null, 'failed', [
                'target_label' => $request->input('email'),
                'portal' => $request->input('portal'),
            ]);

            return $this->unauthorized(__('messages.login_failed'));
        }
    }
}
