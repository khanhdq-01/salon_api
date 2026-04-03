<?php

namespace App\Support;

use App\Models\Booking;
use App\Models\StaffSchedule;
use Closure;
use Illuminate\Support\Facades\Cache;

class AvailableSlotsCache
{
    public const TTL_SECONDS = 45;

    public static function remember(string $salonId, array $filters, Closure $callback): array
    {
        $date = $filters['date'];
        $cacheKey = sprintf(
            'available-slots:%s:%s:v%d:sv%d:%s',
            $salonId,
            $date,
            self::version($salonId, $date),
            self::salonVersion($salonId),
            self::itemKey($filters)
        );

        /** @var array $result */
        $result = Cache::remember($cacheKey, self::TTL_SECONDS, $callback);

        return $result;
    }

    public static function forgetSalonDate(string $salonId, string $date): void
    {
        Cache::increment(self::versionKey($salonId, $date));
        self::bumpTodayAvailabilityVersion($date);
    }

    /**
     * @param  list<string|null|mixed>  $dates
     */
    public static function forgetSalonDates(string $salonId, array $dates): void
    {
        foreach (self::uniqueDates($dates) as $date) {
            self::forgetSalonDate($salonId, $date);
        }
    }

    public static function forgetForStaffSchedule(StaffSchedule $schedule, string $salonId): void
    {
        $date = self::normalizeDate($schedule->work_date);

        if ($date !== null) {
            self::forgetSalonDate($salonId, $date);
        }
    }

    /**
     * Invalidate all cached slot variants for a salon (hours, interval, staff roster, …).
     */
    public static function forgetSalonWide(string $salonId): void
    {
        Cache::increment(self::salonVersionKey($salonId));
        self::bumpTodayAvailabilityVersion(now()->toDateString());
    }

    public static function bumpTodayAvailabilityVersion(string $date): void
    {
        Cache::increment("salons:available_today:version:{$date}");
    }

    public static function normalizeDate(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        return substr((string) $value, 0, 10);
    }

    public static function statusAffectsSlots(string $status): bool
    {
        return in_array($status, [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED], true);
    }

    public static function shouldInvalidateOnStatusChange(string $fromStatus, string $toStatus): bool
    {
        return self::statusAffectsSlots($fromStatus) !== self::statusAffectsSlots($toStatus);
    }

    /**
     * @param  list<string|null|mixed>  $dates
     * @return list<string>
     */
    private static function uniqueDates(array $dates): array
    {
        $normalized = [];

        foreach ($dates as $date) {
            $value = self::normalizeDate($date);

            if ($value !== null) {
                $normalized[] = $value;
            }
        }

        return array_values(array_unique($normalized));
    }

    private static function version(string $salonId, string $date): int
    {
        return (int) Cache::get(self::versionKey($salonId, $date), 0);
    }

    private static function salonVersion(string $salonId): int
    {
        return (int) Cache::get(self::salonVersionKey($salonId), 0);
    }

    private static function versionKey(string $salonId, string $date): string
    {
        return "available-slots:version:{$salonId}:{$date}";
    }

    private static function salonVersionKey(string $salonId): string
    {
        return "available-slots:salon-version:{$salonId}";
    }

    private static function itemKey(array $filters): string
    {
        $serviceIds = array_values(array_unique($filters['service_ids'] ?? []));
        sort($serviceIds);

        $styleOptions = $filters['style_options'] ?? [];
        ksort($styleOptions);

        return hash('sha256', json_encode([
            'service_ids' => $serviceIds,
            'style_options' => $styleOptions,
        ], JSON_THROW_ON_ERROR));
    }
}
