<?php

namespace App\Contracts\Services\Admin;

use App\Models\Package;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AdminPackageManagementServiceInterface
{
    public function listPackages(array $filters): LengthAwarePaginator;

    public function createPackage(array $data): Package;

    public function updatePackage(string $id, array $data): Package;

    public function deletePackage(string $id): bool;
}
