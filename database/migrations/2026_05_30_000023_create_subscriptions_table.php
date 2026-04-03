<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('owner_id')->constrained('users')->restrictOnDelete();
            $table->foreignUuid('package_id')->constrained('packages')->restrictOnDelete();
            $table->string('status', 20)->default('active');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('auto_renew')->default(true);
            $table->timestamps();

            $table->index('owner_id');
            $table->index('status');
            $table->index(['owner_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
