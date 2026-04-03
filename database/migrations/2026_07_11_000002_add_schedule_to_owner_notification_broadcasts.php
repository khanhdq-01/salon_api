<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('owner_notification_broadcasts', function (Blueprint $table) {
            $table->timestamp('scheduled_at')->nullable()->after('recipient_count');
            $table->timestamp('sent_at')->nullable()->after('scheduled_at');

            $table->index(['sent_at', 'scheduled_at']);
        });

        DB::table('owner_notification_broadcasts')
            ->whereNull('scheduled_at')
            ->update([
                'scheduled_at' => DB::raw('created_at'),
                'sent_at' => DB::raw('created_at'),
            ]);
    }

    public function down(): void
    {
        Schema::table('owner_notification_broadcasts', function (Blueprint $table) {
            $table->dropIndex(['sent_at', 'scheduled_at']);
            $table->dropColumn(['scheduled_at', 'sent_at']);
        });
    }
};
