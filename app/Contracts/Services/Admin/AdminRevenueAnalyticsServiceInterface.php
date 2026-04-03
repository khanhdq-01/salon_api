<?php

namespace App\Contracts\Services\Admin;

interface AdminRevenueAnalyticsServiceInterface
{
    public function getAnalytics(array $filters): array;
}
