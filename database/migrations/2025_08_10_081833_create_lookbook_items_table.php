<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            Schema::create('lookbook_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('lookbook_id')->constrained()->cascadeOnDelete();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->unsignedInteger('sort_order')->default(0);
                $table->json('preset_variant')->nullable(); // optional: preselect size/color, etc.
                $table->timestamps();
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('lookbook_items');
        }
    };
