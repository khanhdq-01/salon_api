<?php

namespace App\Contracts\Services\Owner;

use App\Models\User;

interface OwnerDashboardServiceInterface
{
    public function getDashboard(User $owner, array $filters = []): array;
}
