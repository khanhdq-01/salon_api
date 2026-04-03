<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /** @var list<string> */
    private const SUPPORTED = ['vi', 'en'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->resolveLocale($request);
        app()->setLocale($locale);

        return $next($request);
    }

    private function resolveLocale(Request $request): string
    {
        /** @var User|null $user */
        $user = $request->user();
        if ($user?->language && in_array($user->language, self::SUPPORTED, true)) {
            return $user->language;
        }

        $header = $request->header('Accept-Language');
        if (is_string($header) && $header !== '') {
            $primary = strtolower(substr(trim(explode(',', $header)[0]), 0, 2));
            if (in_array($primary, self::SUPPORTED, true)) {
                return $primary;
            }
        }

        $fallback = config('app.locale', 'vi');

        return in_array($fallback, self::SUPPORTED, true) ? $fallback : 'vi';
    }
}
