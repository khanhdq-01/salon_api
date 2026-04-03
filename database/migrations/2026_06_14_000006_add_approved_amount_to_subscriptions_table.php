<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('approved_amount')->nullable()->after('approved_by');
        });

        DB::statement('
            UPDATE subscriptions
            INNER JOIN packages ON subscriptions.package_id = packages.id
            SET subscriptions.approved_amount = packages.price
            WHERE subscriptions.approved_at IS NOT NULL
              AND subscriptions.approved_amount IS NULL
        ');
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('approved_amount');
        });
    }
};
