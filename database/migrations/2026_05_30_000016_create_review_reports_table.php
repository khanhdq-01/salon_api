<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('review_reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('review_id')->constrained('reviews')->cascadeOnDelete();
            $table->foreignUuid('reporter_id')->constrained('users')->cascadeOnDelete();
            $table->text('reason')->nullable();
            $table->string('status', 20)->default('pending');
            $table->timestamp('created_at')->useCurrent();

            $table->index('review_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_reports');
    }
};
