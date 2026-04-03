<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'old_password',
        'new_password',
        'password',
        'password_confirmation',
        'token',
        'reset_token',
        'access_token',
        'refresh_token',
        'api_token',
        'authorization',
        'secret',
        'client_secret',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e): JsonResponse|\Illuminate\Http\Response
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return $this->handleApiException($e);
        }

        return parent::render($request, $e);
    }

    private function handleApiException(Throwable $e): JsonResponse
    {
        if ($e instanceof BusinessException) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors'  => ['code' => $e->getErrorCode()],
            ], $e->getCode() >= 100 && $e->getCode() < 600 ? $e->getCode() : 422);
        }

        if ($e instanceof ValidationException) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $e->errors(),
            ], 422);
        }

        if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found.',
                'errors'  => [],
            ], 404);
        }

        if ($e instanceof AuthenticationException) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
                'errors'  => [],
            ], 401);
        }

        if ($e instanceof AccessDeniedHttpException) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden.',
                'errors'  => [],
            ], 403);
        }

        if ($e instanceof TooManyRequestsHttpException) {
            $retryAfter = $e->getHeaders()['Retry-After'] ?? null;
            $message = 'Quá nhiều yêu cầu. Vui lòng thử lại'
                .($retryAfter ? " sau {$retryAfter} giây" : ' sau')
                .'.';

            return response()->json([
                'success' => false,
                'message' => $message,
                'errors'  => ['code' => 'RATE_LIMIT_EXCEEDED'],
            ], 429);
        }

        $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;

        return response()->json([
            'success' => false,
            'message' => config('app.debug') ? $e->getMessage() : 'Internal server error.',
            'errors'  => config('app.debug') ? ['trace' => $e->getTraceAsString()] : [],
        ], $statusCode);
    }
}

