<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('staff_schedules', function (Blueprint $table) {
            $table->text('note')->nullable()->after('submitted_by');
            $table->foreignUuid('approved_by')
                ->nullable()
                ->after('note')
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->index(['status', 'work_date']);
        });
    }

    public function down(): void
    {
        Schema::table('staff_schedules', function (Blueprint $table) {
            $table->dropIndex(['status', 'work_date']);
            $table->dropConstrainedForeignId('approved_by');
            $table->dropColumn(['note', 'approved_at']);
        });
    }
};
