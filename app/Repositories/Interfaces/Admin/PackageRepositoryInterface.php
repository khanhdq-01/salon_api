<?php

namespace App\Repositories\Interfaces\Admin;

use App\Models\Package;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PackageRepositoryInterface
{
    public function paginate(array $filters): LengthAwarePaginator;

    public function findById(string $id): ?Package;

    public function findActiveById(string $id): ?Package;

    public function create(array $data): Package;

    public function update(Package $package, array $data): Package;

    public function delete(Package $package): bool;
}
