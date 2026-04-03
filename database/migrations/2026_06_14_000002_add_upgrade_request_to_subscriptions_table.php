<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->uuid('requested_package_id')->nullable()->after('package_id');
            $table->timestamp('reviewed_at')->nullable()->after('auto_renew');
            $table->uuid('reviewed_by')->nullable()->after('reviewed_at');

            $table->foreign('requested_package_id')->references('id')->on('packages')->nullOnDelete();
            $table->foreign('reviewed_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign(['requested_package_id']);
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn(['requested_package_id', 'reviewed_at', 'reviewed_by']);
        });
    }
};
