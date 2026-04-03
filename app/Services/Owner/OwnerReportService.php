<?php

namespace App\Services\Owner;

use App\Contracts\Services\Owner\OwnerReportServiceInterface;
use App\Contracts\Services\Owner\SalonServiceInterface;
use App\Models\Booking;
use App\Models\Staff;
use App\Models\User;
use App\Repositories\Interfaces\Owner\BookingRepositoryInterface as OwnerBookingRepositoryInterface;
use App\Repositories\Interfaces\Owner\StaffRepositoryInterface;
use App\Support\DashboardDateRange;
use Carbon\Carbon;

class OwnerReportService implements OwnerReportServiceInterface
{
    public function __construct(
        protected SalonServiceInterface $salonService,
        protected OwnerBookingRepositoryInterface $bookingRepository,
        protected StaffRepositoryInterface $staffRepository,
    ) {}

    public function getReport(User $owner, array $filters): array
    {
        $salon = $this->salonService->getOwnerSalon($owner);
        $salonId = $salon->id;
        $range = DashboardDateRange::normalize($filters);
        $start = $range['start'];
        $end = $range['end'];

        $summary = $this->buildSummary($salonId, $start, $end);
        $chart = $this->buildRangeRevenueChart($salonId, $start, $end);

        $topServices = DashboardDateRange::paginateItems(
            $this->buildTopServices($salonId, $start, $end),
            $range['page'],
            $range['per_page'],
        );

        $topStaff = DashboardDateRange::paginateItems(
            $this->buildTopStaff($salonId, $start, $end),
            $range['page'],
            $range['per_page'],
        );

        return [
            'range' => [
                'start_date' => $range['start_date'],
                'end_date' => $range['end_date'],
            ],
            'summary_range' => [
                'from' => $start->toDateString(),
                'to' => $end->toDateString(),
            ],
            'services_range' => [
                'from' => $start->toDateString(),
                'to' => $end->toDateString(),
            ],
            'staff_range' => [
                'from' => $start->toDateString(),
                'to' => $end->toDateString(),
            ],
            'summary' => $summary,
            'chart' => $chart,
            'top_services' => $topServices['items'],
            'top_services_meta' => $topServices['meta'],
            'top_staff' => $topStaff['items'],
            'top_staff_meta' => $topStaff['meta'],
        ];
    }

    protected function buildRangeRevenueChart(string $salonId, Carbon $start, Carbon $end): array
    {
        $labels = [];
        $revenue = [];
        $cursor = $start->copy()->startOfDay();
        $endDay = $end->copy()->startOfDay();

        while ($cursor->lte($endDay)) {
            $labels[] = $cursor->format('d/m');
            $revenue[] = $this->sumCompletedRevenue($salonId, $cursor, $cursor);
            $cursor->addDay();
        }

        return compact('labels', 'revenue');
    }

    protected function parseReferenceDate(?string $value): Carbon
    {
        $today = Carbon::today();

        if (! $value) {
            return $today;
        }

        $date = Carbon::parse($value)->startOfDay();

        return $date->gt($today) ? $today : $date;
    }

    protected function resolveDateRange(string $rangeType, Carbon $referenceDate): array
    {
        $today = Carbon::today();
        $end = $referenceDate->copy()->endOfDay();

        if ($end->gt($today->endOfDay())) {
            $end = $today->copy()->endOfDay();
        }

        $start = match ($rangeType) {
            'day' => $referenceDate->copy()->startOfDay(),
            'week' => $referenceDate->copy()->startOfWeek(Carbon::MONDAY)->startOfDay(),
            'month' => $referenceDate->copy()->startOfMonth()->startOfDay(),
            'quarter' => $referenceDate->copy()->firstOfQuarter()->startOfDay(),
            'year' => $referenceDate->copy()->startOfYear()->startOfDay(),
            default => $referenceDate->copy()->startOfMonth()->startOfDay(),
        };

        if ($start->gt($end)) {
            $start = $end->copy()->startOfDay();
        }

        return [$start, $end];
    }

    protected function buildSummary(string $salonId, Carbon $start, Carbon $end): array
    {
        $baseQuery = $this->bookingRepository->buildSummaryBaseQuery($salonId, $start, $end);

        $totalBookings = (clone $baseQuery)
            ->where('status', '!=', Booking::STATUS_CANCELLED)
            ->count();

        $cancelledBookings = (clone $baseQuery)
            ->where('status', Booking::STATUS_CANCELLED)
            ->count();

        $totalRevenue = (int) (clone $baseQuery)
            ->where('status', Booking::STATUS_COMPLETED)
            ->sum('total_price');

        $cancelRate = $totalBookings + $cancelledBookings > 0
            ? round(($cancelledBookings / ($totalBookings + $cancelledBookings)) * 100, 1)
            : 0.0;

        $returningCustomers = $this->bookingRepository->countReturningCustomers($salonId, $start, $end);

        return [
            'total_revenue' => $totalRevenue,
            'cancel_rate' => $cancelRate,
            'returning_customers' => $returningCustomers,
            'total_bookings' => $totalBookings,
        ];
    }

    protected function buildRevenueChart(string $salonId, string $period, Carbon $referenceDate): array
    {
        return match ($period) {
            'day' => $this->dailyChart($salonId, $referenceDate, 7),
            'week' => $this->weeklyChart($salonId, $referenceDate, 7),
            'month' => $this->monthlyChart($salonId, $referenceDate, 6),
            'quarter' => $this->quarterlyChart($salonId, $referenceDate, 4),
            'year' => $this->yearlyChart($salonId, $referenceDate),
            default => $this->monthlyChart($salonId, $referenceDate, 6),
        };
    }

    protected function dailyChart(string $salonId, Carbon $referenceDate, int $days): array
    {
        $labels = [];
        $revenue = [];
        $start = $referenceDate->copy()->subDays($days - 1);

        for ($i = 0; $i < $days; $i++) {
            $day = $start->copy()->addDays($i);
            if ($day->gt(Carbon::today())) {
                break;
            }
            $labels[] = $day->format('d/m');
            $revenue[] = $this->sumCompletedRevenue($salonId, $day, $day);
        }

        return compact('labels', 'revenue');
    }

    protected function weeklyChart(string $salonId, Carbon $referenceDate, int $weeks): array
    {
        $labels = [];
        $revenue = [];
        $cursor = $referenceDate->copy()->endOfWeek(Carbon::SUNDAY);

        for ($i = $weeks - 1; $i >= 0; $i--) {
            $weekEnd = $cursor->copy()->subWeeks($i);
            $weekStart = $weekEnd->copy()->startOfWeek(Carbon::MONDAY);

            if ($weekStart->gt(Carbon::today())) {
                continue;
            }

            if ($weekEnd->gt(Carbon::today())) {
                $weekEnd = Carbon::today();
            }

            $labels[] = 'Tuần '.$weekStart->format('d/m');
            $revenue[] = $this->sumCompletedRevenue($salonId, $weekStart, $weekEnd);
        }

        return compact('labels', 'revenue');
    }

    protected function monthlyChart(string $salonId, Carbon $referenceDate, int $months): array
    {
        $labels = [];
        $revenue = [];
        $cursor = $referenceDate->copy()->startOfMonth();

        for ($i = $months - 1; $i >= 0; $i--) {
            $month = $cursor->copy()->subMonths($i);
            $monthEnd = $month->copy()->endOfMonth();

            if ($month->gt(Carbon::today()->startOfMonth())) {
                continue;
            }

            if ($monthEnd->gt(Carbon::today())) {
                $monthEnd = Carbon::today();
            }

            $labels[] = 'T'.$month->month.'/'.$month->format('y');
            $revenue[] = $this->sumCompletedRevenue($salonId, $month, $monthEnd);
        }

        return compact('labels', 'revenue');
    }

    protected function quarterlyChart(string $salonId, Carbon $referenceDate, int $quarters): array
    {
        $labels = [];
        $revenue = [];
        $cursor = $referenceDate->copy()->firstOfQuarter();

        for ($i = $quarters - 1; $i >= 0; $i--) {
            $quarterStart = $cursor->copy()->subQuarters($i);
            $quarterEnd = $quarterStart->copy()->lastOfQuarter();

            if ($quarterStart->gt(Carbon::today())) {
                continue;
            }

            if ($quarterEnd->gt(Carbon::today())) {
                $quarterEnd = Carbon::today();
            }

            $labels[] = 'Q'.ceil($quarterStart->month / 3).'/'.$quarterStart->format('Y');
            $revenue[] = $this->sumCompletedRevenue($salonId, $quarterStart, $quarterEnd);
        }

        return compact('labels', 'revenue');
    }

    protected function yearlyChart(string $salonId, Carbon $referenceDate): array
    {
        $labels = [];
        $revenue = [];
        $yearStart = $referenceDate->copy()->startOfYear();
        $today = Carbon::today();

        for ($month = 1; $month <= 12; $month++) {
            $monthStart = $yearStart->copy()->month($month)->startOfMonth();
            $monthEnd = $monthStart->copy()->endOfMonth();

            if ($monthStart->gt($today)) {
                break;
            }

            if ($monthEnd->gt($today)) {
                $monthEnd = $today;
            }

            $labels[] = 'T'.$month;
            $revenue[] = $this->sumCompletedRevenue($salonId, $monthStart, $monthEnd);
        }

        return compact('labels', 'revenue');
    }

    protected function sumCompletedRevenue(string $salonId, Carbon $start, Carbon $end): int
    {
        return $this->bookingRepository->sumCompletedRevenueBetweenDates($salonId, $start, $end);
    }

    protected function buildTopServices(string $salonId, Carbon $start, Carbon $end): array
    {
        return $this->bookingRepository->getTopServicesRows($salonId, $start, $end)
            ->map(function ($row) {
                $serviceName = $row->service_name ?? 'Dịch vụ';
                $displayName = $row->option_name
                    ? $serviceName.' ('.$row->option_name.')'
                    : $serviceName;

                return [
                    'service_id' => $row->service_id,
                    'style_option_id' => $row->service_style_option_id,
                    'name' => $displayName,
                    'bookings' => (int) $row->bookings_count,
                ];
            })
            ->values()
            ->all();
    }

    protected function buildTopStaff(string $salonId, Carbon $start, Carbon $end): array
    {
        $counts = $this->bookingRepository->getStaffBookingCounts($salonId, $start, $end);

        return $this->staffRepository->getBySalonOrderedByName($salonId, ['id', 'name'])
            ->map(fn (Staff $staff) => [
                'staff_id' => $staff->id,
                'name' => $staff->name,
                'bookings' => (int) ($counts[$staff->id] ?? 0),
            ])
            ->sortByDesc('bookings')
            ->values()
            ->all();
    }
}
