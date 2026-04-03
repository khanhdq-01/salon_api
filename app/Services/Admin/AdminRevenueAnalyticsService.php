<?php

namespace App\Services\Admin;

use App\Contracts\Services\Admin\AdminRevenueAnalyticsServiceInterface;
use App\Models\Subscription;
use App\Repositories\Interfaces\Admin\SubscriptionRepositoryInterface;
use App\Support\DashboardDateRange;
use App\Support\SubscriptionExpiry;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AdminRevenueAnalyticsService implements AdminRevenueAnalyticsServiceInterface
{
    public function __construct(
        protected SubscriptionRepositoryInterface $subscriptionRepository
    ) {}

    public function getAnalytics(array $filters): array
    {
        SubscriptionExpiry::syncExpiredSubscriptions();

        $range = DashboardDateRange::normalize($filters);
        $filterStart = $range['start'];
        $filterEnd = $range['end'];

        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();
        $yearStart = Carbon::now()->startOfYear();
        $yearEnd = Carbon::now()->endOfYear();

        $revenueByPackage = DashboardDateRange::paginateItems(
            $this->buildRevenueByPackage($filterStart, $filterEnd),
            $range['page'],
            $range['per_page'],
        );

        $topPayingSalons = DashboardDateRange::paginateItems(
            $this->buildTopPayingSalons($filterStart, $filterEnd),
            $range['page'],
            $range['per_page'],
        );

        return [
            'kpis' => [
                'platform_revenue_today' => $this->sumApprovedAmount($today->copy()->startOfDay(), $today->copy()->endOfDay()),
                'platform_revenue_this_month' => $this->sumApprovedAmount($monthStart, $monthEnd),
                'platform_revenue_this_year' => $this->sumApprovedAmount($yearStart, $yearEnd),
                'platform_revenue_in_range' => $this->sumApprovedAmount($filterStart, $filterEnd),
                'mrr' => $this->calculateMrr(),
            ],
            'filter' => [
                'start_date' => $range['start_date'],
                'end_date' => $range['end_date'],
            ],
            'revenue_trend' => $this->buildRevenueTrend(),
            'subscription_growth' => $this->buildSubscriptionGrowthTrend(),
            'package_distribution' => $this->buildPackageDistribution(),
            'revenue_by_package' => $revenueByPackage['items'],
            'revenue_by_package_meta' => $revenueByPackage['meta'],
            'top_paying_salons' => $topPayingSalons['items'],
            'top_paying_salons_meta' => $topPayingSalons['meta'],
            'expiring_revenue_risk' => $this->buildExpiringRevenueRisk($today),
        ];
    }

    protected function sumApprovedAmount(Carbon $start, Carbon $end): int
    {
        return $this->subscriptionRepository->sumApprovedAmountBetween($start, $end);
    }

    protected function calculateMrr(): int
    {
        $activeSubscriptions = $this->subscriptionRepository->getActive(
            ['package:id,price,billing_period'],
            ['id', 'package_id']
        );

        return (int) $activeSubscriptions->sum(function (Subscription $subscription) {
            $package = $subscription->package;

            if (! $package) {
                return 0;
            }

            return $package->monthlyEquivalentPrice();
        });
    }

    protected function buildRevenueTrend(): array
    {
        $items = [];

        for ($i = 11; $i >= 0; $i--) {
            $monthStart = Carbon::now()->startOfMonth()->subMonths($i);
            $monthEnd = $monthStart->copy()->endOfMonth();

            $items[] = [
                'month' => $monthStart->format('M/Y'),
                'month_label' => $monthStart->format('M'),
                'revenue' => $this->sumApprovedAmount($monthStart, $monthEnd),
            ];
        }

        return $items;
    }

    protected function buildSubscriptionGrowthTrend(): array
    {
        $items = [];

        for ($i = 11; $i >= 0; $i--) {
            $monthStart = Carbon::now()->startOfMonth()->subMonths($i);
            $monthEnd = $monthStart->copy()->endOfMonth();

            $items[] = [
                'month' => $monthStart->format('M/Y'),
                'month_label' => $monthStart->format('M'),
                'active_subscriptions' => $this->subscriptionRepository->countActiveInDateRange($monthStart, $monthEnd),
            ];
        }

        return $items;
    }

    protected function buildPackageDistribution(): array
    {
        $activeSubscriptions = $this->subscriptionRepository->getActive(
            ['package:id,name'],
            ['id', 'package_id']
        );

        $total = $activeSubscriptions->count();

        if ($total === 0) {
            return [];
        }

        return $activeSubscriptions
            ->groupBy('package_id')
            ->map(function (Collection $group, $packageId) use ($total) {
                $package = $group->first()?->package;
                $count = $group->count();

                return [
                    'package_id' => $packageId,
                    'package_name' => $package?->name ?? '—',
                    'active_subscribers' => $count,
                    'percentage' => round(($count / $total) * 100, 1),
                ];
            })
            ->values()
            ->sortByDesc('active_subscribers')
            ->values()
            ->all();
    }

    protected function buildRevenueByPackage(Carbon $start, Carbon $end): array
    {
        $rows = $this->subscriptionRepository->getApprovedBetweenWithPackage(
            $start,
            $end,
            ['id', 'package_id', 'approved_amount']
        );

        return $rows
            ->groupBy('package_id')
            ->map(function (Collection $group, $packageId) {
                $package = $group->first()?->package;

                return [
                    'package_id' => $packageId,
                    'package' => $package?->name ?? '—',
                    'subscribers' => $group->count(),
                    'revenue' => (int) $group->sum('approved_amount'),
                ];
            })
            ->values()
            ->sortByDesc('revenue')
            ->values()
            ->all();
    }

    protected function buildTopPayingSalons(Carbon $start, Carbon $end): array
    {
        $rows = $this->subscriptionRepository->getApprovedBetweenWithOwnerAndPackage(
            $start,
            $end,
            ['id', 'owner_id', 'package_id', 'approved_amount']
        );

        return $rows
            ->groupBy('owner_id')
            ->map(function (Collection $group) {
                $subscription = $group->sortByDesc('approved_at')->first();
                $salonName = $subscription?->owner?->ownedSalons
                    ?->pluck('name')
                    ->filter()
                    ->first();

                return [
                    'owner_id' => $subscription?->owner_id,
                    'salon' => $salonName ?? $subscription?->owner?->name ?? '—',
                    'current_package' => $subscription?->package?->name ?? '—',
                    'total_platform_revenue' => (int) $group->sum('approved_amount'),
                ];
            })
            ->values()
            ->sortByDesc('total_platform_revenue')
            ->values()
            ->all();
    }

    protected function buildExpiringRevenueRisk(Carbon $today): array
    {
        $threshold = $today->copy()->addDays(7);

        $subscriptions = $this->subscriptionRepository->getActiveExpiringBetweenWithPackage(
            $today,
            $threshold,
            ['id', 'package_id', 'end_date']
        );

        return [
            'count' => $subscriptions->count(),
            'potential_revenue_at_risk' => (int) $subscriptions->sum(function (Subscription $subscription) {
                return $subscription->package?->price ?? 0;
            }),
        ];
    }

    protected function resolveFilterRange(array $filters): array
    {
        $period = $filters['period'] ?? 'this_month';

        return match ($period) {
            'today' => [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()],
            'this_year' => [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()],
            'custom' => [
                Carbon::parse($filters['start_date'])->startOfDay(),
                Carbon::parse($filters['end_date'])->endOfDay(),
            ],
            default => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
        };
    }
}
