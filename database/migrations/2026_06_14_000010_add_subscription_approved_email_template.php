<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('email_templates')->where('template_key', 'subscription_approved')->exists()) {
            return;
        }

        $now = now();

        DB::table('email_templates')->insert([
            'id' => (string) Str::uuid(),
            'template_key' => 'subscription_approved',
            'template_name' => 'Subscription Approved',
            'subject' => 'Gói dịch vụ đã được duyệt',
            'content' => '<p>Yêu cầu đăng ký gói dịch vụ của bạn đã được admin xác nhận thành công.</p><p>Vui lòng đăng nhập hệ thống để sử dụng đầy đủ tính năng theo gói đã đăng ký.</p>',
            'status' => 'active',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    public function down(): void
    {
        DB::table('email_templates')->where('template_key', 'subscription_approved')->delete();
    }
};
