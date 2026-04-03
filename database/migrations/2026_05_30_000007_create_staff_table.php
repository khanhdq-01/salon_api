<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('salon_id')->constrained('salons')->cascadeOnDelete();
            $table->string('name', 100);
            $table->text('avatar_url')->nullable();
            $table->text('bio')->nullable();
            $table->unsignedSmallInteger('experience_years')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('salon_id');
            $table->index(['salon_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
