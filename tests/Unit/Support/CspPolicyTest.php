<?php

namespace Tests\Unit\Support;

use App\Support\CspPolicy;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class CspPolicyTest extends TestCase
{
    public function test_api_csp_contains_restrictive_directives(): void
    {
        Config::set('security.csp.enabled', true);
        Config::set('security.csp.api_directives', [
            "default-src 'none'",
            "frame-ancestors 'none'",
        ]);

        $value = CspPolicy::apiHeaderValue();

        $this->assertStringContainsString("default-src 'none'", $value);
        $this->assertStringContainsString('frame-ancestors', $value);
    }

    public function test_csp_disabled_returns_null(): void
    {
        Config::set('security.csp.enabled', false);

        $this->assertNull(CspPolicy::apiHeaderValue());
    }

    public function test_local_uses_report_only_header_name(): void
    {
        app()['env'] = 'local';

        $this->assertSame('Content-Security-Policy-Report-Only', CspPolicy::headerName());
    }
}
