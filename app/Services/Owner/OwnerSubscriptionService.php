<?php

namespace App\Services\Owner;

use App\Contracts\Services\Owner\OwnerSubscriptionServiceInterface;
use App\Contracts\Services\Owner\SalonServiceInterface;
use App\Exceptions\BusinessException;
use App\Models\Package;
use App\Models\Salon;
use App\Models\Subscription;
use App\Models\User;
use App\Repositories\Interfaces\Owner\BookingRepositoryInterface as OwnerBookingRepositoryInterface;
use App\Repositories\Interfaces\Owner\PackageRepositoryInterface;
use App\Repositories\Interfaces\Owner\ServiceRepositoryInterface;
use App\Repositories\Interfaces\Owner\StaffRepositoryInterface;
use App\Repositories\Interfaces\Owner\SubscriptionRepositoryInterface;
use App\Support\AuditLogger;
use App\Support\SubscriptionExpiry;
use App\Support\TrialPackage;
use Carbon\Carbon;

class OwnerSubscriptionService implements OwnerSubscriptionServiceInterface
{
    public function __construct(
        protected SalonServiceInterface $salonService,
        protected SubscriptionRepositoryInterface $subscriptionRepository,
        protected PackageRepositoryInterface $packageRepository,
        protected StaffRepositoryInterface $staffRepository,
        protected ServiceRepositoryInterface $serviceRepository,
        protected OwnerBookingRepositoryInterface $bookingRepository,
    ) {}

    public function getSubscription(User $owner): array
    {
        SubscriptionExpiry::syncExpiredSubscriptions();

        try {
            $salon = $this->salonService->getOwnerSalon($owner);
        } catch (BusinessException $e) {
            if ($e->getErrorCode() !== 'OWNER_SALON_NOT_FOUND') {
                throw $e;
            }

            return $this->buildSalonMissingState();
        }

        $salon->loadMissing('requestedPackage');

        $subscription = $this->subscriptionRepository->findResolvableForOwner($owner->id);

        if (! $subscription) {
            return $this->buildPreSubscriptionState($salon);
        }

        $subscription->loadMissing(['package', 'requestedPackage']);
        $package = $subscription->package;

        if (! $package) {
            throw new BusinessException('Gói dịch vụ không tồn tại.', 'PACKAGE_NOT_FOUND', 404);
        }

        $usage = $this->buildUsage($salon->id, $package, $subscription);
        $pendingUpgrade = $this->buildPendingUpgrade($subscription);
        $effectiveStatus = SubscriptionExpiry::resolveEffectiveStatus($subscription);

        return [
            'subscription_id' => $subscription->id,
            'salon_approval_status' => $salon->approval_status,
            'is_salon_public' => $salon->isPubliclyVisible(),
            'plan' => $this->mapPlan($package),
            'expire_date' => $this->formatDate($subscription->end_date),
            'days_left' => $this->resolveDaysLeft($subscription),
            'auto_renew' => (bool) $subscription->auto_renew,
            'status' => $effectiveStatus,
            'requires_initial_payment' => $this->requiresInitialPayment($subscription, $salon),
            'initial_payment_submitted' => $this->initialPaymentSubmitted($subscription),
            'pending_upgrade' => $pendingUpgrade,
            'usage' => $usage,
        ];
    }

    public function getAvailablePackages(User $owner): array
    {
        return TrialPackage::annotatePackagesForOwner(
            $this->packageRepository->getActivePlans(),
            $owner->id
        );
    }

    public function getPlans(User $owner): array
    {
        $this->resolveSubscription($owner->id);

        return TrialPackage::annotatePackagesForOwner(
            $this->packageRepository->getActivePlans(),
            $owner->id
        );
    }

    public function submitPayment(User $owner, array $data): array
    {
        SubscriptionExpiry::syncExpiredSubscriptions();
        $subscription = $this->resolveSubscription($owner->id)->fresh(['package']);

        if (! in_array($subscription->status, [
            Subscription::STATUS_AWAITING_PAYMENT,
            Subscription::STATUS_REJECTED,
        ], true)) {
            throw new BusinessException('Gói hiện tại không yêu cầu thanh toán.', 'INVALID_SUBSCRIPTION_STATUS', 422);
        }

        if ($subscription->requested_package_id) {
            throw new BusinessException(
                'Vui lòng dùng luồng nâng cấp gói cho yêu cầu này.',
                'USE_UPGRADE_FLOW',
                422
            );
        }

        if ($subscription->status === Subscription::STATUS_AWAITING_PAYMENT
            && $subscription->payment_proof
            && $subscription->requested_at) {
            throw new BusinessException(
                'Yêu cầu thanh toán đã được gửi và đang chờ xác minh.',
                'PAYMENT_REQUEST_PENDING',
                422
            );
        }

        $subscription->update([
            'status' => Subscription::STATUS_AWAITING_PAYMENT,
            'payment_proof' => $data['payment_proof'] ?? null,
            'payment_note' => $data['payment_note'] ?? null,
            'requested_at' => now(),
            'requested_amount' => $subscription->package?->price,
            'reviewed_at' => null,
            'reviewed_by' => null,
        ]);

        AuditLogger::log('Submitted initial subscription payment', 'subscription', $subscription->id, 'success', [
            'owner_id' => $owner->id,
            'package_id' => $subscription->package_id,
        ]);

        return [
            'subscription_id' => $subscription->id,
            'status' => Subscription::STATUS_AWAITING_PAYMENT,
            'requested_amount' => $subscription->package?->price,
        ];
    }

    public function upgrade(User $owner, array $data): array
    {
        SubscriptionExpiry::syncExpiredSubscriptions();
        $subscription = $this->resolveSubscription($owner->id)->fresh(['package', 'requestedPackage']);
        $package = $this->packageRepository->findActiveById($data['package_id']);

        if (! $package) {
            throw new BusinessException('Gói dịch vụ không khả dụng.', 'PACKAGE_NOT_FOUND', 422);
        }

        TrialPackage::assertOwnerCanSelectTrial($owner->id, $package);

        $effectiveStatus = SubscriptionExpiry::resolveEffectiveStatus($subscription);

        if ($subscription->package_id === $package->id && $effectiveStatus !== Subscription::STATUS_EXPIRED) {
            throw new BusinessException('Bạn đang sử dụng gói này.', 'SAME_PACKAGE', 422);
        }

        if ($subscription->isAwaitingPayment()) {
            throw new BusinessException(
                'Bạn đã có yêu cầu nâng cấp đang chờ xác minh thanh toán.',
                'UPGRADE_REQUEST_PENDING',
                422
            );
        }

        if (! in_array($effectiveStatus, [
            Subscription::STATUS_ACTIVE,
            Subscription::STATUS_REJECTED,
            Subscription::STATUS_EXPIRED,
        ], true)) {
            throw new BusinessException('Không thể gửi yêu cầu nâng cấp với trạng thái gói hiện tại.', 'INVALID_SUBSCRIPTION_STATUS', 422);
        }

        $subscription->update([
            'status' => Subscription::STATUS_AWAITING_PAYMENT,
            'requested_package_id' => $package->id,
            'requested_amount' => $package->price,
            'requested_at' => now(),
            'payment_proof' => $data['payment_proof'] ?? null,
            'payment_note' => $data['payment_note'] ?? null,
            'reviewed_at' => null,
            'reviewed_by' => null,
            'approved_at' => null,
            'approved_by' => null,
        ]);

        AuditLogger::log('Requested subscription upgrade', 'subscription', $subscription->id, 'success', [
            'owner_id' => $owner->id,
            'current_package_id' => $subscription->package_id,
            'requested_package_id' => $package->id,
            'requested_package_name' => $package->name,
            'requested_amount' => $package->price,
            'payment_proof' => $data['payment_proof'] ?? null,
        ]);

        return [
            'subscription_id' => $subscription->id,
            'status' => Subscription::STATUS_AWAITING_PAYMENT,
            'requested_plan' => $package->name,
            'requested_amount' => $package->price,
        ];
    }

    protected function buildPreSubscriptionState(Salon $salon): array
    {
        $requestedPackage = $salon->requestedPackage;

        return [
            'subscription_id' => null,
            'salon_approval_status' => $salon->approval_status,
            'is_salon_public' => false,
            'plan' => $requestedPackage ? $this->mapPlan($requestedPackage) : null,
            'expire_date' => null,
            'days_left' => 0,
            'auto_renew' => false,
            'status' => 'none',
            'requires_initial_payment' => false,
            'initial_payment_submitted' => false,
            'pending_upgrade' => null,
            'usage' => null,
        ];
    }

    protected function buildSalonMissingState(): array
    {
        return [
            'subscription_id' => null,
            'salon_approval_status' => null,
            'is_salon_public' => false,
            'plan' => null,
            'expire_date' => null,
            'days_left' => 0,
            'auto_renew' => false,
            'status' => 'none',
            'requires_initial_payment' => false,
            'initial_payment_submitted' => false,
            'usage' => null,
        ];
    }

    protected function requiresInitialPayment(Subscription $subscription, Salon $salon): bool
    {
        if ($salon->approval_status !== Salon::APPROVAL_APPROVED) {
            return false;
        }

        return $subscription->status === Subscription::STATUS_AWAITING_PAYMENT
            && ! $subscription->requested_package_id;
    }

    protected function initialPaymentSubmitted(Subscription $subscription): bool
    {
        return $subscription->status === Subscription::STATUS_AWAITING_PAYMENT
            && ! $subscription->requested_package_id
            && ($subscription->payment_proof || $subscription->requested_at);
    }

    protected function mapPlan(Package $package): array
    {
        return [
            'id' => $package->id,
            'name' => $package->name,
            'description' => $package->description,
            'price' => $package->price,
            'billing_period' => $package->billing_period ?? Package::BILLING_1_MONTH,
            'billing_period_label' => $package->billingPeriodLabel(),
            'bookings_limit_label' => $package->bookingsLimitLabel(),
            'max_staff' => $package->max_staff,
            'max_services' => $package->max_services,
            'max_bookings_per_month' => $package->max_bookings_per_month,
        ];
    }

    protected function resolveSubscription(string $ownerId): Subscription
    {
        $subscription = $this->subscriptionRepository->findResolvableForOwner($ownerId);

        if (! $subscription) {
            throw new BusinessException('Chưa có gói đăng ký.', 'SUBSCRIPTION_NOT_FOUND', 404);
        }

        return $subscription;
    }

    protected function buildPendingUpgrade(Subscription $subscription): ?array
    {
        if (! $subscription->isAwaitingPayment() || ! $subscription->requested_package_id) {
            return null;
        }

        $requestedPackage = $subscription->requestedPackage;
        $currentPackage = $subscription->package;

        return [
            'package_id' => $subscription->requested_package_id,
            'package_name' => $requestedPackage?->name,
            'current_package_id' => $subscription->package_id,
            'current_package_name' => $currentPackage?->name,
            'requested_amount' => $subscription->requested_amount ?? $requestedPackage?->price,
            'billing_period' => $requestedPackage?->billing_period ?? Package::BILLING_1_MONTH,
            'billing_period_label' => $requestedPackage?->billingPeriodLabel(),
            'requested_at' => $this->formatDateTime($subscription->requested_at),
            'expire_date' => $this->formatDate($subscription->end_date),
            'payment_proof' => $subscription->payment_proof,
            'payment_proof_url' => $this->resolvePaymentProofUrl($subscription->payment_proof),
            'payment_note' => $subscription->payment_note,
            'status' => SubscriptionExpiry::resolveEffectiveStatus($subscription),
        ];
    }

    protected function resolvePaymentProofUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '/')) {
            return $path;
        }

        return '/storage/'.$path;
    }

    protected function formatDateTime(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format(DATE_ATOM);
        }

        return (string) $value;
    }

    protected function buildUsage(string $salonId, Package $package, Subscription $subscription): array
    {
        if (! $subscription->start_date || ! $subscription->end_date) {
            return [
                'staff' => [
                    'current' => $this->staffRepository->countBySalon($salonId),
                    'max' => $package->max_staff,
                ],
                'services' => [
                    'current' => $this->serviceRepository->countBySalon($salonId),
                    'max' => $package->max_services,
                ],
                'bookings_in_period' => [
                    'current' => 0,
                    'max' => $package->max_bookings_per_month,
                    'period_label' => $package->billingPeriodLabel(),
                    'limit_label' => $package->bookingsLimitLabel(),
                ],
                'bookings_this_month' => [
                    'current' => 0,
                    'max' => $package->max_bookings_per_month,
                    'period_label' => $package->billingPeriodLabel(),
                    'limit_label' => $package->bookingsLimitLabel(),
                ],
            ];
        }

        $periodStart = Carbon::parse($subscription->start_date)->startOfDay();
        $periodEnd = Carbon::parse($subscription->end_date)->endOfDay();
        $today = Carbon::today()->endOfDay();

        if ($today->lt($periodEnd)) {
            $periodEnd = $today;
        }

        $staffCount = $this->staffRepository->countBySalon($salonId);

        $servicesCount = $this->serviceRepository->countBySalon($salonId);

        $bookingsInPeriod = $this->bookingRepository->countNonCancelledInPeriod(
            $salonId,
            $periodStart->toDateString(),
            $periodEnd->toDateString()
        );

        $bookingsUsage = [
            'current' => $bookingsInPeriod,
            'max' => $package->max_bookings_per_month,
            'period_label' => $package->billingPeriodLabel(),
            'limit_label' => $package->bookingsLimitLabel(),
        ];

        return [
            'staff' => [
                'current' => $staffCount,
                'max' => $package->max_staff,
            ],
            'services' => [
                'current' => $servicesCount,
                'max' => $package->max_services,
            ],
            'bookings_in_period' => $bookingsUsage,
            'bookings_this_month' => $bookingsUsage,
        ];
    }

    protected function resolveDaysLeft(Subscription $subscription): int
    {
        if (! $subscription->end_date) {
            return 0;
        }

        return max(0, Carbon::today()->diffInDays(Carbon::parse($subscription->end_date), false));
    }

    protected function formatDate(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        return substr((string) $value, 0, 10);
    }
}
