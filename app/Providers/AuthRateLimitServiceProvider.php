<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AuthRateLimitServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        RateLimiter::for('auth-register', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('auth-forgot-password', function (Request $request) {
            $email = strtolower(trim((string) $request->input('email', '')));

            return Limit::perMinutes(10, 3)->by($request->ip().'|'.$email);
        });

        RateLimiter::for('auth-resend-verification', function (Request $request) {
            $email = strtolower(trim((string) $request->input('email', '')));

            return Limit::perMinutes(10, 3)->by($request->ip().'|'.$email);
        });
    }
}
