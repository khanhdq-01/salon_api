<?php

namespace App\Repositories\Eloquent\Owner;

use App\Models\Package;
use App\Repositories\Interfaces\Owner\PackageRepositoryInterface;

class PackageRepository implements PackageRepositoryInterface
{
    public function __construct(
        protected Package $model
    ) {}

    public function getActivePlans(): array
    {
        return $this->model->newQuery()
            ->active()
            ->orderBy('price')
            ->orderBy('name')
            ->get()
            ->all();
    }

    public function findActiveById(string $id): ?Package
    {
        return $this->model->newQuery()->active()->find($id);
    }
}
