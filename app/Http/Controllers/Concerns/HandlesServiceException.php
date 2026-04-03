<?php

namespace App\Http\Controllers\Concerns;

use App\Exceptions\BusinessException;
use Illuminate\Http\JsonResponse;

trait HandlesServiceException
{
    protected function tryService(callable $callback, string $message = 'Thành công'): JsonResponse
    {
        try {
            $result = $callback();

            if ($result instanceof JsonResponse) {
                return $result;
            }

            return $this->success($result, $message);
        } catch (BusinessException $e) {
            return $this->error(
                $e->getMessage(),
                $e->getCode() >= 100 && $e->getCode() < 600 ? $e->getCode() : 422,
                ['code' => $e->getErrorCode()]
            );
        }
    }
}
