<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('email_templates')->where('template_key', 'booking_confirmed')->exists()) {
            return;
        }

        $now = now();

        DB::table('email_templates')->insert([
            'id' => (string) Str::uuid(),
            'template_key' => 'booking_confirmed',
            'template_name' => 'Booking Confirmed Notification',
            'subject' => 'Xác nhận đặt lịch thành công',
            'content' => '<p>Vui lòng đến đúng giờ hẹn. Nếu cần thay đổi lịch, hãy liên hệ salon trước thời gian hẹn.</p><p>Cảm ơn bạn đã tin tưởng và sử dụng dịch vụ.</p>',
            'status' => 'active',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    public function down(): void
    {
        DB::table('email_templates')->where('template_key', 'booking_confirmed')->delete();
    }
};
