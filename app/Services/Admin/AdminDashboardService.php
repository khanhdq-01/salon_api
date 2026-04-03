<?php



namespace App\Services\Admin;



use App\Contracts\Services\Admin\AdminDashboardServiceInterface;

use App\Models\AuditLog;

use App\Models\Role;

use App\Models\Salon;

use App\Models\Subscription;

use App\Models\User;

use App\Repositories\Interfaces\Admin\AuditLogRepositoryInterface;

use App\Repositories\Interfaces\Admin\BookingRepositoryInterface;

use App\Repositories\Interfaces\Admin\SalonRepositoryInterface;

use App\Repositories\Interfaces\Admin\SubscriptionRepositoryInterface;

use App\Repositories\Interfaces\Admin\UserRepositoryInterface;

use App\Support\AuditLogPresenter;

use App\Support\DashboardDateRange;

use App\Support\SubscriptionExpiry;

use Carbon\Carbon;



class AdminDashboardService implements AdminDashboardServiceInterface

{

    public function __construct(

        protected AuditLogRepositoryInterface $auditLogRepository,

        protected BookingRepositoryInterface $bookingRepository,

        protected SalonRepositoryInterface $salonRepository,

        protected SubscriptionRepositoryInterface $subscriptionRepository,

        protected UserRepositoryInterface $userRepository

    ) {}



    public function getDashboard(array $filters = []): array

    {

        SubscriptionExpiry::syncExpiredSubscriptions();



        $range = DashboardDateRange::normalize($filters);



        $systemOverview = $this->buildSystemOverview();

        $businessPerformance = $this->buildBusinessPerformance($range);

        $operationalMonitoring = $this->buildOperationalMonitoring($range);



        return [

            'system_overview' => $systemOverview,

            'business_performance' => $businessPerformance,

            'operational_monitoring' => $operationalMonitoring,

            // Backward compatibility for legacy consumers.

            'stats' => $this->buildLegacyStats($systemOverview, $businessPerformance, $operationalMonitoring),

            'chart' => $businessPerformance['chart'],

            'alerts' => $operationalMonitoring['alerts'],

            'recent_activity' => $operationalMonitoring['recent_activity'],

            'recent_activity_meta' => $operationalMonitoring['recent_activity_meta'],

            'range' => $businessPerformance['range'],

        ];

    }



    protected function buildSystemOverview(): array

    {

        return [

            'total_salons' => $this->salonRepository->countAll(),

            'active_salons' => $this->salonRepository->countActive(),

            'pending_salons' => $this->salonRepository->countPending(),

            'locked_salons' => $this->salonRepository->countLocked(),

            'total_owners' => $this->userRepository->countByRoleName(Role::OWNER),

            'total_customers' => $this->userRepository->countByRoleName(Role::CUSTOMER),

            'total_users' => $this->userRepository->countAll(),

            'active_subscriptions' => $this->subscriptionRepository->countActive(),

            'pending_subscriptions' => $this->subscriptionRepository->countPendingApprovalWithRequestedPackage()
                + $this->subscriptionRepository->countAwaitingPaymentWithRequestedPackage(),

            'last_updated' => now()->toIso8601String(),

        ];

    }



    protected function buildBusinessPerformance(array $range): array

    {

        $start = $range['start'];

        $end = $range['end'];



        return [

            'range' => [

                'start_date' => $range['start_date'],

                'end_date' => $range['end_date'],

            ],

            'total_bookings' => $this->bookingRepository->countBetweenDatesExcludingCancelled($start, $end),

            'booking_revenue' => $this->bookingRepository->sumCompletedRevenueBetweenDates($start, $end),

            'platform_revenue' => $this->subscriptionRepository->sumApprovedAmountBetween($start, $end),

            'new_subscriptions' => $this->subscriptionRepository->countApprovedBetween($start, $end),

            'chart' => $this->buildChartData($start, $end),

        ];

    }



    protected function buildOperationalMonitoring(array $range): array

    {

        $today = Carbon::today();

        $expiringThreshold = $today->copy()->addDays(7);



        $activityPaginator = $this->auditLogRepository->paginate([

            'page' => $range['page'],

            'per_page' => 5,

        ]);



        $alerts = $this->buildAlerts();

        $subscriptionAlerts = $this->buildSubscriptionAlerts();

        $awaitingPaymentAlerts = $this->buildAwaitingPaymentAlerts();



        return [

            'awaiting_payment' => $this->subscriptionRepository->countAwaitingPaymentWithRequestedPackage(),

            'upgrade_requests' => $this->subscriptionRepository->countPendingApprovalWithRequestedPackage(),

            'expiring_subscriptions' => $this->subscriptionRepository->countExpiringBetween($today, $expiringThreshold),

            'expired_subscriptions' => $this->subscriptionRepository->countExpired($today),

            'suspended_users' => $this->userRepository->countSuspended(),

            'alerts' => array_merge($alerts, [

                'subscription_alerts' => $subscriptionAlerts,

                'awaiting_payment_alerts' => $awaitingPaymentAlerts,

            ]),

            'recent_activity' => collect($activityPaginator->items())

                ->map(fn (AuditLog $log) => $this->mapActivityFromAudit($log))

                ->all(),

            'recent_activity_meta' => [

                'current_page' => $activityPaginator->currentPage(),

                'last_page' => $activityPaginator->lastPage(),

                'per_page' => $activityPaginator->perPage(),

                'total' => $activityPaginator->total(),

            ],

            'last_updated' => now()->toIso8601String(),

        ];

    }



    protected function buildLegacyStats(array $systemOverview, array $businessPerformance, array $operationalMonitoring): array

    {

        return [

            'total_users' => $systemOverview['total_users'],

            'total_customers' => $systemOverview['total_customers'],

            'total_owners' => $systemOverview['total_owners'],

            'total_salons' => $systemOverview['total_salons'],

            'active_salons' => $systemOverview['active_salons'],

            'pending_salons' => $systemOverview['pending_salons'],

            'locked_salons' => $systemOverview['locked_salons'],

            'total_bookings' => $businessPerformance['total_bookings'],

            'total_revenue' => $businessPerformance['booking_revenue'],

            'booking_revenue' => $businessPerformance['booking_revenue'],

            'platform_revenue' => $businessPerformance['platform_revenue'],

            'suspended_users' => $operationalMonitoring['suspended_users'],

            'active_subscriptions' => $systemOverview['active_subscriptions'],

            'pending_subscriptions' => $systemOverview['pending_subscriptions'],

            'upgrade_requests' => $operationalMonitoring['upgrade_requests'],

            'subscription_requests_count' => $operationalMonitoring['upgrade_requests'],

            'awaiting_payment' => $operationalMonitoring['awaiting_payment'],

            'awaiting_payment_count' => $operationalMonitoring['awaiting_payment'],

            'new_subscriptions_this_month' => $businessPerformance['new_subscriptions'],

            'expiring_subscriptions' => $operationalMonitoring['expiring_subscriptions'],

            'expired_subscriptions' => $operationalMonitoring['expired_subscriptions'],

        ];

    }



    protected function buildSubscriptionAlerts(): array

    {

        return $this->subscriptionRepository->getPendingApprovalAlerts(5)

            ->map(fn (Subscription $subscription) => $this->mapSubscriptionRequestAlert($subscription))

            ->values()

            ->all();

    }



    protected function buildAwaitingPaymentAlerts(): array

    {

        return $this->subscriptionRepository->getAwaitingPaymentAlerts(5)

            ->map(fn (Subscription $subscription) => $this->mapAwaitingPaymentAlert($subscription))

            ->values()

            ->all();

    }



    protected function mapSubscriptionRequestAlert(Subscription $subscription): array

    {

        return [

            'id' => $subscription->id,

            'salon_name' => $this->resolveSalonName($subscription),

            'current_package' => $subscription->package?->name,

            'requested_package' => $subscription->requestedPackage?->name,

            'requested_at' => $this->formatDateTime($subscription->requested_at),

        ];

    }



    protected function mapAwaitingPaymentAlert(Subscription $subscription): array

    {

        return [

            'id' => $subscription->id,

            'salon_name' => $this->resolveSalonName($subscription),

            'requested_package' => $subscription->requestedPackage?->name,

            'requested_amount' => $subscription->requested_amount,

            'requested_at' => $this->formatDateTime($subscription->requested_at),

        ];

    }



    protected function resolveSalonName(Subscription $subscription): string

    {

        $salonName = $subscription->owner?->ownedSalons

            ?->pluck('name')

            ->filter()

            ->first();



        if ($salonName) {

            return $salonName;

        }



        return $subscription->owner?->name ?? '—';

    }



    protected function buildChartData(Carbon $start, Carbon $end): array

    {

        $labels = [];

        $bookings = [];

        $revenue = [];

        $cursor = $start->copy()->startOfDay();

        $endDay = $end->copy()->startOfDay();



        while ($cursor->lte($endDay)) {

            $labels[] = $cursor->format('d/m');

            $bookings[] = $this->bookingRepository->countByDateExcludingCancelled($cursor);

            $revenue[] = $this->bookingRepository->sumRevenueByDate($cursor);

            $cursor->addDay();

        }



        return [

            'labels' => $labels,

            'bookings' => $bookings,

            'revenue' => $revenue,

        ];

    }



    protected function buildAlerts(): array

    {

        $pendingSalons = $this->salonRepository->getPendingAlerts(5)

            ->map(fn (Salon $salon) => [

                'id' => $salon->id,

                'name' => $salon->name,

                'created_at' => $salon->created_at?->toDateString(),

            ])

            ->values()

            ->all();



        $lockedSalons = $this->salonRepository->getLockedAlerts(5)

            ->map(fn (Salon $salon) => [

                'id' => $salon->id,

                'name' => $salon->name,

                'created_at' => $salon->created_at?->toDateString(),

            ])

            ->values()

            ->all();



        $suspendedUsers = $this->userRepository->getSuspendedAlerts(5)

            ->map(fn (User $user) => [

                'id' => $user->id,

                'name' => $user->name,

                'email' => $user->email,

                'created_at' => $user->created_at?->toDateString(),

            ])

            ->values()

            ->all();



        return [

            'pending_salons' => $pendingSalons,

            'locked_salons' => $lockedSalons,

            'suspended_users' => $suspendedUsers,

            'failed_payments' => [],

            'owner_violations' => [],

        ];

    }



    protected function mapActivityFromAudit(AuditLog $log): array

    {

        return [

            'id' => $log->id,

            'type' => $log->target_type,

            'action' => $log->action,

            'action_label' => AuditLogPresenter::actionLabel($log->action),

            'message' => AuditLogPresenter::message($log),

            'user' => $log->user?->name ?? AuditLogPresenter::resolveObjectLabel($log) ?? 'Hệ thống',

            'user_role' => AuditLogPresenter::resolveRole($log),

            'target_type' => $log->target_type,

            'target_id' => $log->target_id,

            'status' => $log->status,

            'time' => $this->formatDateTime($log->created_at),

        ];

    }



    protected function formatDateTime(mixed $value): ?string

    {

        if ($value === null) {

            return null;

        }



        if ($value instanceof \DateTimeInterface) {

            return $value->format(\DateTimeInterface::ATOM);

        }



        return Carbon::parse((string) $value)->toIso8601String();

    }

}


