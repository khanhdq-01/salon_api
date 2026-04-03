<?php

namespace App\Support;

use Carbon\Carbon;

final class TimeFormat
{
    public static function normalize(string $time): string
    {
        return strlen($time) === 5 ? $time . ':00' : $time;
    }

    public static function toIso8601(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format(DATE_ATOM);
        }

        return Carbon::parse((string) $value)->toIso8601String();
    }
}
