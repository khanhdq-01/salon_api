<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('staff_schedules', function (Blueprint $table) {
            $table->string('status', 20)->default('approved')->after('end_time');
            $table->string('submitted_by', 20)->default('owner')->after('status');
            $table->index(['staff_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('staff_schedules', function (Blueprint $table) {
            $table->dropIndex(['staff_id', 'status']);
            $table->dropColumn(['status', 'submitted_by']);
        });
    }
};
