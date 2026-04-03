<?php

namespace App\Services\Shared;

use App\Exceptions\BusinessException;

trait ThrowsNotImplemented
{
    protected function notImplemented(string $service, string $method): never
    {
        throw new BusinessException(
            "{$service}::{$method} chưa được triển khai.",
            'NOT_IMPLEMENTED',
            501
        );
    }
}
