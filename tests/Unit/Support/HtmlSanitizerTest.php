<?php

namespace Tests\Unit\Support;

use App\Support\HtmlSanitizer;
use PHPUnit\Framework\TestCase;

class HtmlSanitizerTest extends TestCase
{
    public function test_rich_html_removes_script_and_event_handlers(): void
    {
        $input = '<p>Hello</p><script>alert(1)</script><img src=x onerror=alert(1)>';
        $result = HtmlSanitizer::richHtml($input);

        $this->assertStringContainsString('<p>Hello</p>', $result);
        $this->assertStringNotContainsString('script', strtolower($result));
        $this->assertStringNotContainsString('onerror', strtolower($result));
    }

    public function test_rich_html_blocks_javascript_urls(): void
    {
        $input = '<a href="javascript:alert(1)">click</a>';
        $result = HtmlSanitizer::richHtml($input);

        $this->assertStringNotContainsString('javascript:', strtolower($result));
    }

    public function test_plain_text_strips_all_html(): void
    {
        $input = '<b>Safe</b> <script>alert(1)</script>';
        $result = HtmlSanitizer::plainText($input);

        $this->assertSame('Safe', $result);
    }

    public function test_empty_values_return_null(): void
    {
        $this->assertNull(HtmlSanitizer::richHtml('   '));
        $this->assertNull(HtmlSanitizer::plainText(''));
        $this->assertNull(HtmlSanitizer::richHtml(null));
    }
}
