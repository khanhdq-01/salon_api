<?php

namespace App\Support;

use App\Exceptions\BusinessException;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Support\Facades\Cache;

class BookingSlotLock
{
    public const WAIT_SECONDS = 5;

    public const TTL_SECONDS = 15;

    /**
     * Serialize slot mutations for the same staff/seat on a given day.
     *
     * @template TReturn
     *
     * @param  callable(): TReturn  $callback
     * @return TReturn
     */
    public static function run(
        string $salonId,
        string $date,
        string $staffId,
        ?string $seatId,
        callable $callback
    ): mixed {
        $keys = self::lockKeys($salonId, $date, $staffId, $seatId);

        try {
            return self::runWithKeys($keys, $callback);
        } catch (LockTimeoutException) {
            throw new BusinessException(
                'Hệ thống đang xử lý lịch trùng khung giờ, vui lòng thử lại.',
                'SLOT_LOCK_BUSY',
                409
            );
        }
    }

    /**
     * @return list<string>
     */
    public static function lockKeys(string $salonId, string $date, string $staffId, ?string $seatId): array
    {
        $keys = [
            self::staffKey($salonId, $staffId, $date),
        ];

        if ($seatId) {
            $keys[] = self::seatKey($salonId, $seatId, $date);
        }

        sort($keys);

        return $keys;
    }

    /**
     * @param  list<string>  $keys
     */
    protected static function runWithKeys(array $keys, callable $callback): mixed
    {
        if ($keys === []) {
            return $callback();
        }

        $lock = Cache::lock($keys[0], self::TTL_SECONDS);

        return $lock->block(self::WAIT_SECONDS, function () use ($keys, $callback) {
            if (count($keys) === 1) {
                return $callback();
            }

            return self::runWithKeys(array_slice($keys, 1), $callback);
        });
    }

    public static function staffKey(string $salonId, string $staffId, string $date): string
    {
        return "booking-slot-lock:staff:{$salonId}:{$staffId}:{$date}";
    }

    public static function seatKey(string $salonId, string $seatId, string $date): string
    {
        return "booking-slot-lock:seat:{$salonId}:{$seatId}:{$date}";
    }
}
