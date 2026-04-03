<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        if (! Schema::hasTable('salons')) {
            return;
        }

        DB::statement('ALTER TABLE salons ADD FULLTEXT INDEX salons_fulltext_search (name, address, description)');
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        if (! Schema::hasTable('salons')) {
            return;
        }

        DB::statement('ALTER TABLE salons DROP INDEX salons_fulltext_search');
    }
};
