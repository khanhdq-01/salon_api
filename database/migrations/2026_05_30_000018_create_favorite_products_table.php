<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorite_products', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('product_type', 30);
            $table->uuid('product_ref');
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['user_id', 'product_type', 'product_ref']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorite_products');
    }
};
