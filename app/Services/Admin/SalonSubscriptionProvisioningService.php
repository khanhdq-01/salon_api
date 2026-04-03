<?php

namespace App\Services\Admin;

use App\Exceptions\BusinessException;
use App\Models\Package;
use App\Models\Salon;
use App\Models\Subscription;
use App\Models\User;
use App\Repositories\Interfaces\Admin\PackageRepositoryInterface;
use App\Repositories\Interfaces\Admin\SubscriptionRepositoryInterface;
use App\Support\AuditLogger;
use App\Support\TrialPackage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class SalonSubscriptionProvisioningService
{
    public function __construct(
        protected SubscriptionRepositoryInterface $subscriptionRepository,
        protected PackageRepositoryInterface $packageRepository,
    ) {}

    public function provisionOnSalonApproval(Salon $salon, ?User $admin = null): Subscription
    {
        if (! $salon->requested_package_id) {
            throw new BusinessException(
                'Salon chưa chọn gói dịch vụ.',
                'SALON_PACKAGE_REQUIRED',
                422
            );
        }

        $existing = Subscription::query()
            ->forOwner($salon->owner_id)
            ->where('status', '!=', Subscription::STATUS_CANCELLED)
            ->first();

        if ($existing) {
            throw new BusinessException(
                'Owner đã có gói đăng ký. Vui lòng gia hạn hoặc nâng cấp gói hiện tại.',
                'SUBSCRIPTION_ALREADY_EXISTS',
                422
            );
        }

        $package = $this->packageRepository->findActiveById($salon->requested_package_id);

        if (! $package) {
            throw new BusinessException('Gói dịch vụ không khả dụng.', 'PACKAGE_NOT_FOUND', 422);
        }

        TrialPackage::assertOwnerCanSelectTrial($salon->owner_id, $package);

        $isFree = TrialPackage::isTrial($package);

        if ($isFree) {
            $startDate = Carbon::today();

            $subscription = $this->subscriptionRepository->create([
                'owner_id' => $salon->owner_id,
                'package_id' => $package->id,
                'status' => Subscription::STATUS_ACTIVE,
                'start_date' => $startDate->toDateString(),
                'end_date' => $package->calculateEndDate($startDate)->toDateString(),
                'auto_renew' => false,
                'approved_at' => now(),
                'approved_by' => $admin?->id,
                'approved_amount' => 0,
            ]);
        } else {
            $subscription = $this->subscriptionRepository->create([
                'owner_id' => $salon->owner_id,
                'package_id' => $package->id,
                'status' => Subscription::STATUS_AWAITING_PAYMENT,
                'start_date' => null,
                'end_date' => null,
                'auto_renew' => false,
            ]);
        }

        Cache::increment('salons:list:version');

        AuditLogger::log('Provisioned subscription on salon approval', 'subscription', $subscription->id, 'success', [
            'salon_id' => $salon->id,
            'owner_id' => $salon->owner_id,
            'package_id' => $package->id,
            'status' => $subscription->status,
        ]);

        return $subscription->load(['package', 'owner.ownedSalons']);
    }
}
