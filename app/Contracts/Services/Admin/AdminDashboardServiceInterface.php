<?php

namespace App\Contracts\Services\Admin;

interface AdminDashboardServiceInterface
{
    public function getDashboard(array $filters = []): mixed;
}
