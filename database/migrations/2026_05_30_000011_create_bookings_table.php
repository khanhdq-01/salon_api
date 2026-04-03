<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('salon_id')->constrained('salons')->restrictOnDelete();
            $table->foreignUuid('customer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('walk_in_customer_name', 100)->nullable();
            $table->foreignUuid('staff_id')->constrained('staff')->restrictOnDelete();
            $table->foreignUuid('seat_id')->constrained('seats')->restrictOnDelete();
            $table->date('booking_date');
            $table->time('booking_time');
            $table->string('status', 20)->default('pending');
            $table->unsignedBigInteger('total_price')->default(0);
            $table->unsignedSmallInteger('total_duration_minutes')->default(0);
            $table->text('customer_notes')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->foreignUuid('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('has_reviewed')->default(false);
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('customer_id');
            $table->index('salon_id');
            $table->index('status');
            $table->index('staff_id');
            $table->index(['salon_id', 'booking_date', 'status']);
            $table->index(['salon_id', 'booking_date', 'booking_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
