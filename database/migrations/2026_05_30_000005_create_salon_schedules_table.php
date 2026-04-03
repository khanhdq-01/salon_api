<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salon_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('salon_id')->constrained('salons')->cascadeOnDelete();
            $table->unsignedTinyInteger('day_of_week');
            $table->time('open_time');
            $table->time('close_time');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['salon_id', 'day_of_week']);
            $table->index('salon_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salon_schedules');
    }
};
