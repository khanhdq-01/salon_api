<?php

namespace App\Support;

final class CspPolicy
{
    /**
     * Build CSP header value for API responses, or null when disabled.
     */
    public static function apiHeaderValue(): ?string
    {
        if (! config('security.csp.enabled', true)) {
            return null;
        }

        $directives = config('security.csp.api_directives', []);

        if ($directives === []) {
            return null;
        }

        return implode('; ', $directives);
    }

    /**
     * local/testing → Report-Only; production/staging → enforce.
     */
    public static function useReportOnly(): bool
    {
        return app()->environment('local', 'testing');
    }

    public static function headerName(): string
    {
        return self::useReportOnly()
            ? 'Content-Security-Policy-Report-Only'
            : 'Content-Security-Policy';
    }
}
