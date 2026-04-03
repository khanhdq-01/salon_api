<?php

namespace App\Repositories\Interfaces\Owner;

use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

interface BookingRepositoryInterface
{
    public function countPendingBySalon(string $salonId): int;

    public function countTodayNonCancelledBySalon(string $salonId, Carbon $today): int;

    public function sumTodayCompletedRevenueBySalon(string $salonId, Carbon $today): int;

    public function sumMonthCompletedRevenueBySalon(string $salonId, Carbon $monthStart, Carbon $monthEnd): int;

    public function buildUpcomingQuery(string $salonId): Builder;

    public function countUpcomingBySalon(string $salonId): int;

    public function paginateUpcomingBySalon(string $salonId, int $perPage, int $page, ?Carbon $start = null, ?Carbon $end = null): LengthAwarePaginator;

    public function countDistinctBusyStaffToday(string $salonId, Carbon $today): int;

    public function countNonCancelledOnDateBySalon(string $salonId, Carbon $day): int;

    public function sumCompletedRevenueBetweenDates(string $salonId, Carbon $start, Carbon $end): int;

    public function buildSummaryBaseQuery(string $salonId, Carbon $start, Carbon $end): Builder;

    public function countReturningCustomers(string $salonId, Carbon $start, Carbon $end): int;

    public function getTopServicesRows(string $salonId, Carbon $start, Carbon $end): Collection;

    public function getStaffBookingCounts(string $salonId, Carbon $start, Carbon $end): Collection;

    public function countNonCancelledInPeriod(string $salonId, string $periodStart, string $periodEnd): int;

    public function getActiveBookingsForSalonsOnDate(array $salonIds, string $date): Collection;

    public function getDayBookingsForSalon(string $salonId, string $date): Collection;
}
