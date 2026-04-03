<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('search_histories', function (Blueprint $table) {
            $table->string('type', 20)->default('query')->after('user_id');
            $table->foreignUuid('salon_id')->nullable()->after('query')->constrained('salons')->nullOnDelete();
            $table->index(['user_id', 'type', 'searched_at']);
        });
    }

    public function down(): void
    {
        Schema::table('search_histories', function (Blueprint $table) {
            $table->dropForeign(['salon_id']);
            $table->dropIndex(['user_id', 'type', 'searched_at']);
            $table->dropColumn(['type', 'salon_id']);
        });
    }
};
