<?php

namespace App\Repositories\Eloquent\Concerns;

use App\Exceptions\BusinessException;

trait ThrowsNotImplementedRepository
{
    protected function notImplemented(string $repository, string $method): never
    {
        throw new BusinessException(
            "{$repository}::{$method} chưa được triển khai.",
            'NOT_IMPLEMENTED',
            501
        );
    }
}
