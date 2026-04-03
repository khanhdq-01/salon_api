<?php

namespace Tests\Unit\Support;

use App\Models\AuditLog;
use App\Support\AuditLogPresenter;
use PHPUnit\Framework\TestCase;

class AuditLogPresenterTest extends TestCase
{
    public function test_module_key_for_booking_target(): void
    {
        $log = new AuditLog([
            'target_type' => 'booking',
            'details' => ['portal' => 'owner'],
        ]);

        $this->assertSame('booking', AuditLogPresenter::moduleKey($log));
    }

    public function test_module_key_for_customer_portal(): void
    {
        $log = new AuditLog([
            'target_type' => 'auth',
            'details' => ['portal' => 'customer'],
        ]);

        $this->assertSame('customer', AuditLogPresenter::moduleKey($log));
    }

    public function test_normalize_action_category_for_manual_create_log(): void
    {
        $this->assertSame('create', AuditLogPresenter::normalizeActionCategory('Created package'));
        $this->assertSame('approve', AuditLogPresenter::normalizeActionCategory('Approved subscription upgrade'));
    }

    public function test_module_key_for_email_template_is_settings(): void
    {
        $log = new AuditLog([
            'target_type' => 'email_template',
            'details' => ['portal' => 'admin'],
        ]);

        $this->assertSame('settings', AuditLogPresenter::moduleKey($log));
        $this->assertFalse(str_contains('email_template', 'salon'));
    }
}
