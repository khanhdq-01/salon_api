<?php

namespace App\Repositories\Interfaces\Owner;

use App\Models\Package;

interface PackageRepositoryInterface
{
    /**
     * @return list<Package>
     */
    public function getActivePlans(): array;

    public function findActiveById(string $id): ?Package;
}
