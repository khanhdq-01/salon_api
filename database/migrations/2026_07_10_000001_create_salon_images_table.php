<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salon_images', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('salon_id')->constrained('salons')->cascadeOnDelete();
            $table->text('image_url');
            $table->timestamps();

            $table->index(['salon_id', 'created_at']);
        });

        $salons = DB::table('salons')
            ->whereNotNull('image_url')
            ->where('image_url', '!=', '')
            ->get(['id', 'image_url', 'created_at']);

        foreach ($salons as $salon) {
            DB::table('salon_images')->insert([
                'id' => (string) Str::uuid(),
                'salon_id' => $salon->id,
                'image_url' => $salon->image_url,
                'created_at' => $salon->created_at ?? now(),
                'updated_at' => $salon->created_at ?? now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('salon_images');
    }
};
