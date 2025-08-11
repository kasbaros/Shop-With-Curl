<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_colors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('color_id')->constrained()->cascadeOnDelete();
            $table->integer('stock_quantity')->default(0);
            $table->decimal('additional_price', 10, 2)->default(0.00);
            $table->string('sku_suffix')->nullable(); // Like "-RED" for product variants
            $table->timestamps();

            $table->unique(['product_id', 'color_id']);
            $table->index('product_id');
            $table->index('color_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_colors');
    }
};
