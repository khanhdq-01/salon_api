<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignUuid('owner_id')
                ->nullable()
                ->after('role_id')
                ->constrained('users')
                ->nullOnDelete();
            $table->index('owner_id');
        });

        Schema::table('staff', function (Blueprint $table) {
            $table->foreignUuid('user_id')
                ->nullable()
                ->after('salon_id')
                ->constrained('users')
                ->nullOnDelete();
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('owner_id');
        });
    }
};
