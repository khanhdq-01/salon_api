<?php

namespace Database\Seeders\Concerns;

use Database\Seeders\Support\DemoSeederConstants;

trait SeedsBookingSlots
{
    /**
     * @param  array<string, true>  $usedSlots
     */
    protected function pickAvailableSlot(string $staffId, string $date, array &$usedSlots, int $seed): ?string
    {
        $candidates = DemoSeederConstants::SLOT_TIMES;

        usort($candidates, fn ($a, $b) => crc32($staffId.$date.$a.$seed) <=> crc32($staffId.$date.$b.$seed));

        foreach ($candidates as $slot) {
            $key = "{$staffId}|{$date}|{$slot}";
            if (! isset($usedSlots[$key])) {
                $usedSlots[$key] = true;

                return $slot;
            }
        }

        return null;
    }
}
