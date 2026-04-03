<?php

namespace App\Services\Owner;

use App\Contracts\Services\Owner\OwnerPackageLimitServiceInterface;
use App\Exceptions\BusinessException;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\User;
use App\Repositories\Interfaces\Customer\UserRepositoryInterface;
use App\Repositories\Interfaces\Owner\BookingRepositoryInterface as OwnerBookingRepositoryInterface;
use App\Repositories\Interfaces\Owner\SalonRepositoryInterface;
use App\Repositories\Interfaces\Owner\ServiceRepositoryInterface;
use App\Repositories\Interfaces\Owner\StaffRepositoryInterface;
use App\Repositories\Interfaces\Owner\SubscriptionRepositoryInterface;
use Carbon\Carbon;

class OwnerPackageLimitService implements OwnerPackageLimitServiceInterface
{
    public const UPGRADE_MESSAGE = 'Gói của bạn cần phải nâng cấp để sử dụng, vui lòng liên hệ với admin.';

    public function __construct(
        protected StaffRepositoryInterface $staffRepository,
        protected ServiceRepositoryInterface $serviceRepository,
        protected SalonRepositoryInterface $salonRepository,
        protected UserRepositoryInterface $userRepository,
        protected OwnerBookingRepositoryInterface $bookingRepository,
        protected SubscriptionRepositoryInterface $subscriptionRepository,
    ) {}

    public function assertCanAddStaff(User $owner, string $salonId): void
    {
        $package = $this->resolveActivePackage($owner);

        $count = $this->staffRepository->countBySalon($salonId);

        if ($count >= $package->max_staff) {
            throw new BusinessException(self::UPGRADE_MESSAGE, 'PACKAGE_LIMIT_EXCEEDED', 422);
        }
    }

    public function assertCanAddService(User $owner, string $salonId): void
    {
        $package = $this->resolveActivePackage($owner);

        $count = $this->serviceRepository->countBySalon($salonId);

        if ($count >= $package->max_services) {
            throw new BusinessException(self::UPGRADE_MESSAGE, 'PACKAGE_LIMIT_EXCEEDED', 422);
        }
    }

    public function assertCanAddBookingForSalon(string $salonId): void
    {
        $salon = $this->salonRepository->findById($salonId);

        if (! $salon?->owner_id) {
            return;
        }

        $owner = $this->userRepository->findById($salon->owner_id);

        if (! $owner?->isOwner()) {
            return;
        }

        [$package, $subscription] = $this->resolveActiveSubscription($owner);

        $periodStart = Carbon::parse($subscription->start_date)->startOfDay();
        $periodEnd = Carbon::parse($subscription->end_date)->endOfDay();
        $today = Carbon::today()->endOfDay();

        if ($today->lt($periodEnd)) {
            $periodEnd = $today;
        }

        $count = $this->bookingRepository->countNonCancelledInPeriod(
            $salonId,
            $periodStart->toDateString(),
            $periodEnd->toDateString()
        );

        if ($count >= $package->max_bookings_per_month) {
            throw new BusinessException(self::UPGRADE_MESSAGE, 'PACKAGE_LIMIT_EXCEEDED', 422);
        }
    }

    protected function resolveActivePackage(User $owner): Package
    {
        return $this->resolveActiveSubscription($owner)[0];
    }

    /**
     * @return array{0: Package, 1: Subscription}
     */
    protected function resolveActiveSubscription(User $owner): array
    {
        $subscription = $this->subscriptionRepository->findEffectiveForOwner($owner->id);

        if (! $subscription?->package) {
            throw new BusinessException(self::UPGRADE_MESSAGE, 'SUBSCRIPTION_REQUIRED', 422);
        }

        if ($subscription->end_date && Carbon::today()->gt(Carbon::parse($subscription->end_date))) {
            throw new BusinessException(self::UPGRADE_MESSAGE, 'SUBSCRIPTION_EXPIRED', 422);
        }

        return [$subscription->package, $subscription];
    }
}
