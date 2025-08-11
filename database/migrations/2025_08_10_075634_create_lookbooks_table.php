<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            Schema::create('lookbooks', function (Blueprint $table) {
                $table->id();
                $table->string('title');              // e.g., "Bundle & Save"
                $table->string('label')->nullable();  // e.g., "SHOP THIS LOOK"
                $table->string('image')->nullable();  // right-side large image
                $table->boolean('active')->default(true);
                $table->unsignedInteger('priority')->default(10);
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('ends_at')->nullable();
                $table->timestamps();
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('lookbooks');
        }
    };
