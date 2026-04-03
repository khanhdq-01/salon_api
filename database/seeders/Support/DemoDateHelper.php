<?php

namespace Database\Seeders\Support;

use Carbon\Carbon;

final class DemoDateHelper
{
    public static function today(): Carbon
    {
        return Carbon::today();
    }

    /**
     * @return list<string>
     */
    public static function range(int $startOffsetDays, int $endOffsetDays): array
    {
        $dates = [];
        $cursor = self::today()->copy()->addDays($startOffsetDays);
        $end = self::today()->copy()->addDays($endOffsetDays);

        while ($cursor->lte($end)) {
            $dates[] = $cursor->toDateString();
            $cursor->addDay();
        }

        return $dates;
    }

    /**
     * Booking + schedule window: yesterday through next 14 days.
     *
     * @return list<string>
     */
    public static function demoHorizon(): array
    {
        return self::range(-1, 14);
    }

    /**
     * Extended history for customer repeat visits.
     *
     * @return list<string>
     */
    public static function historyWindow(int $daysBack = 45): array
    {
        return self::range(-$daysBack, -2);
    }
}
