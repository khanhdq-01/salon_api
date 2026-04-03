<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_notification_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('subscription_id');
            $table->string('template_key', 80);
            $table->string('recipient_email', 255);
            $table->timestamp('sent_at');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('subscription_id')->references('id')->on('subscriptions')->cascadeOnDelete();
            $table->unique(['subscription_id', 'template_key'], 'email_notification_logs_subscription_template_unique');
            $table->index('template_key');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_notification_logs');
    }
};
