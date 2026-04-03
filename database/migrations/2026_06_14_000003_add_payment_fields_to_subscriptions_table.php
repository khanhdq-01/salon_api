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
            $table->unsignedBigInteger('requested_amount')->nullable()->after('requested_package_id');
            $table->timestamp('requested_at')->nullable()->after('requested_amount');
            $table->string('payment_proof', 500)->nullable()->after('requested_at');
            $table->text('payment_note')->nullable()->after('payment_proof');
            $table->timestamp('approved_at')->nullable()->after('reviewed_by');
            $table->uuid('approved_by')->nullable()->after('approved_at');

            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
        });

        DB::table('subscriptions')
            ->where('status', 'pending_approval')
            ->update(['status' => 'awaiting_payment']);
    }

    public function down(): void
    {
        DB::table('subscriptions')
            ->where('status', 'awaiting_payment')
            ->update(['status' => 'pending_approval']);

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'requested_amount',
                'requested_at',
                'payment_proof',
                'payment_note',
                'approved_at',
                'approved_by',
            ]);
        });
    }
};
