<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('email_templates')->where('template_key', 'booking_request')->exists()) {
            return;
        }

        $now = now();

        DB::table('email_templates')->insert([
            'id' => (string) Str::uuid(),
            'template_key' => 'booking_request',
            'template_name' => 'Booking Request Notification',
            'subject' => 'Yêu cầu đặt lịch mới',
            'content' => '<p>Vui lòng đăng nhập hệ thống để xem chi tiết và xác nhận lịch hẹn cho khách.</p><p>Cảm ơn bạn đã sử dụng hệ thống quản lý salon.</p>',
            'status' => 'active',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    public function down(): void
    {
        DB::table('email_templates')->where('template_key', 'booking_request')->delete();
    }
};
