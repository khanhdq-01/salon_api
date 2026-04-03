<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\SystemSetting;
use App\Models\User;
use Database\Seeders\Support\DemoSeederConstants;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AuditLogSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->where('email', DemoSeederConstants::ADMIN_EMAIL)->firstOrFail();

        SystemSetting::query()->create([
            'key' => 'app',
            'value' => [
                'system_name' => 'Salonify SaaS',
                'logo_url' => '',
                'support_email' => 'support@salonify.vn',
                'timezone' => 'Asia/Ho_Chi_Minh',
                'currency' => 'VND',
                'auto_renew' => true,
                'email_sender_name' => 'Salonify Admin',
                'email_sender_address' => 'admin@salonify.vn',
                'enable_notifications' => true,
            ],
            'updated_by' => $admin->id,
            'updated_at' => now(),
        ]);

        AuditLog::query()->insert([
            [
                'id' => (string) Str::uuid(),
                'user_id' => $admin->id,
                'action' => 'Seeded demo data',
                'target_type' => 'system',
                'target_id' => null,
                'status' => 'success',
                'details' => json_encode(['target_label' => 'Demo seeders']),
                'ip_address' => '127.0.0.1',
                'created_at' => now()->subDay(),
            ],
        ]);
    }
}
