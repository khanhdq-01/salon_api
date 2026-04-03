<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class BookingRateLimitServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        RateLimiter::for('booking-slots', function (Request $request) {
            $limit = max(1, (int) config('booking.rate_limits.slots_per_minute', 60));

            return Limit::perMinute($limit)->by(
                $request->user()?->id ?: $request->ip()
            );
        });

        RateLimiter::for('booking-create', function (Request $request) {
            $limit = max(1, (int) config('booking.rate_limits.create_per_minute', 10));

            return Limit::perMinute($limit)->by(
                $request->user()?->id ?: $request->ip()
            );
        });

        RateLimiter::for('booking-mutate', function (Request $request) {
            $limit = max(1, (int) config('booking.rate_limits.mutate_per_minute', 20));

            return Limit::perMinute($limit)->by(
                $request->user()?->id ?: $request->ip()
            );
        });
    }
}
