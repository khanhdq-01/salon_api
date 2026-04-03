<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('salons', function (Blueprint $table) {
            $table->foreignUuid('requested_package_id')
                ->nullable()
                ->after('owner_id')
                ->constrained('packages')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('salons', function (Blueprint $table) {
            $table->dropConstrainedForeignId('requested_package_id');
        });
    }
};
