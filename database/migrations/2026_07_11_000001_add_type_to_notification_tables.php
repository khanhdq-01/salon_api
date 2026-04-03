<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('owner_notification_broadcasts', function (Blueprint $table) {
            $table->string('type', 30)->default('general')->after('owner_id');
            $table->index('type');
        });

        Schema::table('customer_notifications', function (Blueprint $table) {
            $table->string('type', 30)->default('general')->after('salon_id');
            $table->foreignUuid('broadcast_id')
                ->nullable()
                ->after('type')
                ->constrained('owner_notification_broadcasts')
                ->nullOnDelete();

            $table->index('broadcast_id');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::table('customer_notifications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('broadcast_id');
            $table->dropIndex(['type']);
            $table->dropColumn('type');
        });

        Schema::table('owner_notification_broadcasts', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropColumn('type');
        });
    }
};
