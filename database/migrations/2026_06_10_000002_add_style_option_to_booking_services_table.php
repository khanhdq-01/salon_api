<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_services', function (Blueprint $table) {
            $table->foreignUuid('service_style_option_id')
                ->nullable()
                ->after('service_id')
                ->constrained('service_style_options')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('booking_services', function (Blueprint $table) {
            $table->dropConstrainedForeignId('service_style_option_id');
        });
    }
};
