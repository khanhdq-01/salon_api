<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_instructions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title', 200);
            $table->string('bank_name', 150)->nullable();
            $table->string('account_number', 100)->nullable();
            $table->string('account_holder', 150)->nullable();
            $table->string('transfer_content', 255)->nullable();
            $table->longText('content')->nullable();
            $table->string('status', 20)->default('inactive');
            $table->timestamps();

            $table->index('status');
            $table->index('updated_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_instructions');
    }
};
