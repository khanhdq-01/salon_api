<?php

namespace App\Support;

use App\Exceptions\BusinessException;
use App\Models\Package;
use App\Models\Subscription;

class TrialPackage
{
    /** @var list<string> */
    public const USED_TRIAL_STATUSES = [
        Subscription::STATUS_ACTIVE,
        Subscription::STATUS_EXPIRED,
        Subscription::STATUS_CANCELLED,
        'completed',
    ];

    public static function isTrial(Package $package): bool
    {
        return (int) ($package->price ?? 0) <= 0;
    }

    public static function ownerHasUsedTrial(string $ownerId): bool
    {
        return Subscription::query()
            ->where('owner_id', $ownerId)
            ->whereIn('status', self::USED_TRIAL_STATUSES)
            ->whereHas('package', fn ($query) => $query->where('price', '<=', 0))
            ->exists();
    }

    public static function assertOwnerCanSelectTrial(string $ownerId, Package $package): void
    {
        if (! self::isTrial($package)) {
            return;
        }

        if (self::ownerHasUsedTrial($ownerId)) {
            throw new BusinessException(
                'Bạn đã sử dụng gói dùng thử trước đó. Vui lòng chọn gói trả phí.',
                'TRIAL_ALREADY_USED',
                422
            );
        }
    }

    /**
     * @param  array<int, Package>  $packages
     * @return array<int, Package>
     */
    public static function annotatePackagesForOwner(array $packages, string $ownerId): array
    {
        $hasUsedTrial = self::ownerHasUsedTrial($ownerId);

        foreach ($packages as $package) {
            $isTrial = self::isTrial($package);
            $trialUsed = $isTrial && $hasUsedTrial;

            $package->setAttribute('is_trial', $isTrial);
            $package->setAttribute('trial_used', $trialUsed);
            $package->setAttribute('selectable', ! $trialUsed);
        }

        return $packages;
    }
}
