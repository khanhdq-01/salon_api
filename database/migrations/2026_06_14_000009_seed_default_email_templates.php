<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $templates = [
            [
                'template_key' => 'subscription_expiry_7_days',
                'template_name' => 'Subscription Expiry Reminder - 7 Days',
                'subject' => 'Nhắc nhở gia hạn gói dịch vụ',
                'content' => '<p>Vui lòng gia hạn gói dịch vụ trước ngày hết hạn để tránh gián đoạn sử dụng hệ thống.</p><p>Nếu cần hỗ trợ vui lòng liên hệ đội ngũ chăm sóc khách hàng.</p>',
            ],
            [
                'template_key' => 'subscription_expiry_3_days',
                'template_name' => 'Subscription Expiry Reminder - 3 Days',
                'subject' => 'Gói dịch vụ sắp hết hạn - cần gia hạn sớm',
                'content' => '<p>Gói dịch vụ của bạn sắp hết hạn. Vui lòng thực hiện gia hạn trong thời gian sớm nhất để tiếp tục sử dụng đầy đủ tính năng.</p><p>Liên hệ bộ phận hỗ trợ nếu bạn cần trợ giúp về thanh toán hoặc nâng cấp gói.</p>',
            ],
            [
                'template_key' => 'subscription_expired',
                'template_name' => 'Subscription Expired',
                'subject' => 'Gói dịch vụ đã hết hạn',
                'content' => '<p>Gói dịch vụ của bạn đã hết hạn. Một số tính năng có thể bị giới hạn cho đến khi bạn gia hạn.</p><p>Vui lòng đăng nhập và thực hiện gia hạn để tiếp tục sử dụng hệ thống.</p>',
            ],
        ];

        foreach ($templates as $template) {
            DB::table('email_templates')->insert([
                'id' => (string) Str::uuid(),
                'template_key' => $template['template_key'],
                'template_name' => $template['template_name'],
                'subject' => $template['subject'],
                'content' => $template['content'],
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('email_templates')->whereIn('template_key', [
            'subscription_expiry_7_days',
            'subscription_expiry_3_days',
            'subscription_expired',
        ])->delete();
    }
};
