<?php

namespace App\Repositories\Interfaces\Customer;

use App\Models\SearchHistory;
use App\Models\User;
use Illuminate\Support\Collection;

interface SearchHistoryRepositoryInterface
{
    public function getRecent(User $user, int $limit = 5): Collection;

    public function recordQuery(User $user, string $query): SearchHistory;

    public function recordSalon(User $user, string $salonId): SearchHistory;
}
