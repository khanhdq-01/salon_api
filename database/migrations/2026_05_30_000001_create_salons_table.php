<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('owner_id')->constrained('users')->restrictOnDelete();
            $table->string('name', 200);
            $table->string('slug', 220)->nullable();
            $table->text('description')->nullable();
            $table->string('address', 500);
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('image_url')->nullable();
            $table->time('open_time')->default('09:00:00');
            $table->time('close_time')->default('20:00:00');
            $table->string('status', 20)->default('open');
            $table->string('approval_status', 20)->default('pending');
            $table->boolean('is_locked')->default(false);
            $table->decimal('rating_avg', 3, 2)->default(0);
            $table->unsignedInteger('rating_count')->default(0);
            $table->unsignedInteger('bookings_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('owner_id');
            $table->index('status');
            $table->index('approval_status');
            $table->index('is_locked');
            $table->index(['lat', 'lng']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salons');
    }
};
