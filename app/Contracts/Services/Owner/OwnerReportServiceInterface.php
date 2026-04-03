<?php

namespace App\Contracts\Services\Owner;

use App\Models\User;

interface OwnerReportServiceInterface
{
    public function getReport(User $owner, array $filters): array;
}
