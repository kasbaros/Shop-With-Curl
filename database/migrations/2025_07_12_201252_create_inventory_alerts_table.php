<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_variant_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('type', ['low_stock', 'out_of_stock', 'restock']);
            $table->integer('threshold_quantity');
            $table->integer('current_quantity');
            $table->boolean('is_resolved')->default(false);
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'type', 'is_resolved']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_alerts');
    }
};
