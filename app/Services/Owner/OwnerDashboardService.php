<?php

namespace App\Services\Owner;

use App\Contracts\Services\Owner\OwnerDashboardServiceInterface;
use App\Contracts\Services\Owner\SalonServiceInterface;
use App\Models\Booking;
use App\Models\User;
use App\Repositories\Interfaces\Owner\BookingRepositoryInterface as OwnerBookingRepositoryInterface;
use App\Repositories\Interfaces\Owner\StaffRepositoryInterface;
use App\Repositories\Interfaces\Owner\StaffScheduleRepositoryInterface;
use App\Repositories\Interfaces\Owner\SubscriptionRepositoryInterface;
use App\Support\DashboardDateRange;
use Carbon\Carbon;

class OwnerDashboardService implements OwnerDashboardServiceInterface
{
    public function __construct(
        protected SalonServiceInterface $salonService,
        protected OwnerBookingRepositoryInterface $bookingRepository,
        protected StaffRepositoryInterface $staffRepository,
        protected StaffScheduleRepositoryInterface $scheduleRepository,
        protected SubscriptionRepositoryInterface $subscriptionRepository,
    ) {}

    public function getDashboard(User $owner, array $filters = []): array
    {
        $salon = $this->salonService->getOwnerSalon($owner);
        $salonId = $salon->id;
        $range = DashboardDateRange::normalize($filters);
        $start = $range['start'];
        $end = $range['end'];
        $today = Carbon::today();

        $pendingConfirmationCount = $this->bookingRepository->countPendingBySalon($salonId);
        $pendingWorkSchedulesCount = $this->scheduleRepository->countPendingBySalon($salonId);

        $bookingsInRange = $this->bookingRepository->countNonCancelledInPeriod(
            $salonId,
            $start->toDateString(),
            $end->toDateString(),
        );

        $revenueInRange = $this->bookingRepository->sumCompletedRevenueBetweenDates($salonId, $start, $end);

        $upcomingQuery = $this->bookingRepository->buildUpcomingQuery($salonId);
        if ($start && $end) {
            $upcomingQuery->whereBetween('booking_date', [$start->toDateString(), $end->toDateString()]);
        }
        $upcomingCount = (clone $upcomingQuery)->count();

        $perPage = $range['per_page'];
        $page = $range['page'];

        $upcomingPaginator = $this->bookingRepository->paginateUpcomingBySalon(
            $salonId,
            $perPage,
            $page,
            $start,
            $end,
        );

        $staffAvailable = $this->staffRepository->countActiveBySalon($salonId);

        $busyStaffCount = $this->bookingRepository->countDistinctBusyStaffToday($salonId, $today);

        return [
            'range' => [
                'start_date' => $range['start_date'],
                'end_date' => $range['end_date'],
            ],
            'stats' => [
                'pending_confirmation_count' => $pendingConfirmationCount,
                'pending_work_schedules_count' => $pendingWorkSchedulesCount,
                'bookings_today_count' => $bookingsInRange,
                'revenue_today' => $revenueInRange,
                'revenue_this_month' => $revenueInRange,
                'upcoming_count' => $upcomingCount,
                'staff_available' => $staffAvailable,
                'subscription_days_left' => $this->resolveSubscriptionDaysLeft($owner->id),
            ],
            'upcoming_bookings' => $upcomingPaginator->items(),
            'upcoming_meta' => [
                'current_page' => $upcomingPaginator->currentPage(),
                'last_page' => $upcomingPaginator->lastPage(),
                'per_page' => $upcomingPaginator->perPage(),
                'total' => $upcomingPaginator->total(),
            ],
            'chart_bookings' => $this->buildRangeBookingsChart($salonId, $start, $end),
            'chart_revenue' => $this->buildRangeRevenueChart($salonId, $start, $end),
            'chart_staff' => [
                'available' => max(0, $staffAvailable - $busyStaffCount),
                'busy' => $busyStaffCount,
            ],
        ];
    }

    protected function resolveSubscriptionDaysLeft(string $ownerId): int
    {
        $subscription = $this->subscriptionRepository->findActiveForOwner($ownerId);

        if (! $subscription?->end_date) {
            return 0;
        }

        return max(0, Carbon::today()->diffInDays(Carbon::parse($subscription->end_date), false));
    }

    protected function buildRangeBookingsChart(string $salonId, Carbon $start, Carbon $end): array
    {
        $labels = [];
        $values = [];
        $cursor = $start->copy()->startOfDay();
        $endDay = $end->copy()->startOfDay();

        while ($cursor->lte($endDay)) {
            $labels[] = $this->weekdayLabel($cursor);
            $values[] = $this->bookingRepository->countNonCancelledOnDateBySalon($salonId, $cursor);
            $cursor->addDay();
        }

        return compact('labels', 'values');
    }

    protected function buildRangeRevenueChart(string $salonId, Carbon $start, Carbon $end): array
    {
        $labels = [];
        $values = [];
        $cursor = $start->copy()->startOfDay();
        $endDay = $end->copy()->startOfDay();

        while ($cursor->lte($endDay)) {
            $labels[] = $cursor->format('d/m');
            $values[] = $this->bookingRepository->sumCompletedRevenueBetweenDates($salonId, $cursor, $cursor);
            $cursor->addDay();
        }

        return compact('labels', 'values');
    }

    protected function buildWeeklyBookingsChart(string $salonId): array
    {
        $labels = [];
        $values = [];
        $start = Carbon::today()->subDays(6);

        for ($i = 0; $i < 7; $i++) {
            $day = $start->copy()->addDays($i);
            $labels[] = $this->weekdayLabel($day);
            $values[] = $this->bookingRepository->countNonCancelledOnDateBySalon($salonId, $day);
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    protected function buildMonthlyRevenueChart(string $salonId): array
    {
        $labels = [];
        $values = [];
        $cursor = Carbon::today()->startOfMonth()->subMonths(5);

        for ($i = 0; $i < 6; $i++) {
            $monthStart = $cursor->copy()->addMonths($i)->startOfMonth();
            $monthEnd = $monthStart->copy()->endOfMonth();
            $labels[] = 'T'.$monthStart->month;
            $values[] = $this->bookingRepository->sumCompletedRevenueBetweenDates($salonId, $monthStart, $monthEnd);
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    protected function weekdayLabel(Carbon $date): string
    {
        return match ($date->dayOfWeekIso) {
            1 => 'T2',
            2 => 'T3',
            3 => 'T4',
            4 => 'T5',
            5 => 'T6',
            6 => 'T7',
            default => 'CN',
        };
    }
}
