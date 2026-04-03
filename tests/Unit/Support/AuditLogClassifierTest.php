<?php

namespace Tests\Unit\Support;

use App\Support\AuditLogClassifier;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

class AuditLogClassifierTest extends TestCase
{
    public function test_admin_email_template_show(): void
    {
        $result = AuditLogClassifier::classifyRequest(
            Request::create('/api/v1/admin/email-templates/550e8400-e29b-41d4-a716-446655440000', 'GET')
        );

        $this->assertSame('view', $result['action']);
        $this->assertSame('view', $result['action_category']);
        $this->assertSame('email_template', $result['target_type']);
        $this->assertSame('settings', $result['module']);
        $this->assertSame('admin', $result['portal']);
    }

    public function test_admin_email_template_list(): void
    {
        $result = AuditLogClassifier::classifyRequest(
            Request::create('/api/v1/admin/email-templates', 'GET')
        );

        $this->assertSame('list', $result['action']);
        $this->assertSame('settings', $result['module']);
    }

    public function test_admin_salon_lock_action(): void
    {
        $result = AuditLogClassifier::classifyRequest(
            Request::create('/api/v1/admin/salons/550e8400-e29b-41d4-a716-446655440000/lock', 'PATCH')
        );

        $this->assertSame('lock', $result['action']);
        $this->assertSame('salon', $result['module']);
        $this->assertSame('salon', $result['target_type']);
    }

    public function test_staff_dashboard_view(): void
    {
        $result = AuditLogClassifier::classifyRequest(
            Request::create('/api/v1/staff/dashboard', 'GET')
        );

        $this->assertSame('view', $result['action']);
        $this->assertSame('staff', $result['portal']);
        $this->assertSame('settings', $result['module']);
        $this->assertSame('dashboard', $result['target_type']);
    }

    public function test_customer_salon_reviews_list(): void
    {
        $result = AuditLogClassifier::classifyRequest(
            Request::create('/api/v1/salons/550e8400-e29b-41d4-a716-446655440000/reviews', 'GET')
        );

        $this->assertSame('list', $result['action']);
        $this->assertSame('review', $result['target_type']);
        $this->assertSame('notification', $result['module']);
    }

    public function test_auth_login_includes_portal_from_body(): void
    {
        $request = Request::create('/api/auth/login', 'POST', [
            'email' => 'admin@test.com',
            'password' => 'secret',
            'portal' => 'admin',
        ]);

        $result = AuditLogClassifier::classifyRequest($request);

        $this->assertSame('login', $result['action']);
        $this->assertSame('admin', $result['portal']);
        $this->assertSame('user', $result['module']);
    }

    public function test_owner_settings_update(): void
    {
        $result = AuditLogClassifier::classifyRequest(
            Request::create('/api/v1/owner/settings', 'PUT')
        );

        $this->assertSame('update', $result['action']);
        $this->assertSame('owner', $result['portal']);
        $this->assertSame('settings', $result['module']);
    }
}
