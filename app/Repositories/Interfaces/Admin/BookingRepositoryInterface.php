<?php

namespace App\Repositories\Interfaces\Admin;

use Carbon\Carbon;

interface BookingRepositoryInterface
{
    public function countAll(): int;

    public function sumCompletedRevenue(): int;

    public function countByDateExcludingCancelled(Carbon $day): int;

    public function countBetweenDatesExcludingCancelled(Carbon $start, Carbon $end): int;

    public function sumCompletedRevenueBetweenDates(Carbon $start, Carbon $end): int;

    public function sumRevenueByDate(Carbon $day): int;
}
