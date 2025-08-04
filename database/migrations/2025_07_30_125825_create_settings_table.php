<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->longText('value')->nullable();
                $table->string('group')->default('general');
                $table->string('type')->default('text'); // text, boolean, integer, decimal, json, file, select, textarea
                $table->text('description')->nullable();
                $table->boolean('is_encrypted')->default(false);
                $table->text('validation_rules')->nullable();
                $table->text('default_value')->nullable();
                $table->json('options')->nullable(); // For select/radio options
                $table->integer('sort_order')->default(0);
                $table->boolean('is_public')->default(false); // Can be accessed publicly
                $table->boolean('is_system')->default(false); // System settings (cannot be deleted)
                $table->timestamps();

                $table->index(['group', 'sort_order']);
                $table->index('is_public');
                $table->index('is_system');
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('settings');
        }
    };
