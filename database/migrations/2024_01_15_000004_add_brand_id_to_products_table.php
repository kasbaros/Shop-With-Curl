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
        // Ensure the table exists and the column doesn't already exist
        if (!Schema::hasTable('products')) {
            return; // products will be created later with the correct schema
        }

        if (!Schema::hasColumn('products', 'brand_id')) {
            Schema::table('products', function (Blueprint $table) {
                // Add brand_id without relying on a non-existent column order reference
                $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
                $table->index('brand_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'brand_id')) {
            Schema::table('products', function (Blueprint $table) {
                // Drop FK safely if exists, then the column
                try {
                    $table->dropForeign(['brand_id']);
                } catch (\Throwable $e) {
                    // ignore if FK name differs or doesn't exist
                }
                $table->dropColumn('brand_id');
            });
        }
    }
};
