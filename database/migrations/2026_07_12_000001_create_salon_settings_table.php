<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salon_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('salon_id')->unique()->constrained('salons')->cascadeOnDelete();
            $table->boolean('auto_confirm_booking')->default(false);
            $table->unsignedSmallInteger('customer_cancel_before_minutes')->default(30);
            $table->unsignedSmallInteger('booking_interval_minutes')->default(30);
            $table->boolean('auto_approve_work_schedule')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salon_settings');
    }
};
