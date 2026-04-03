<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class UploadRateLimitServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        RateLimiter::for('upload', function (Request $request) {
            $limit = max(1, (int) config('uploads.rate_limit_per_minute', 10));

            return Limit::perMinute($limit)->by(
                $request->user()?->id ?: $request->ip()
            );
        });
    }
}
