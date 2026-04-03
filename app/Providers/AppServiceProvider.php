<?php

namespace App\Providers;

use App\Support\PasswordRules;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Password::defaults(fn () => PasswordRules::defaults());
    }
}
