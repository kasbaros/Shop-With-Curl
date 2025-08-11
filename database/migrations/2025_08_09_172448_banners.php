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
            Schema::table('banners', function (Blueprint $table) {
                $table->string('title');
                $table->string('subtitle')->nullable();
                $table->text('description')->nullable();
                $table->string('image');
                $table->string('button_text')->nullable();
                $table->string('button_link')->nullable();
                $table->string('secondary_button_text')->nullable();
                $table->string('secondary_button_link')->nullable();
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::table('banners', function (Blueprint $table) {
                $table->dropColumn([
                    'title',
                    'subtitle',
                    'description',
                    'image',
                    'button_text',
                    'button_link',
                    'secondary_button_text',
                    'secondary_button_link',
                    'sort_order',
                    'is_active'
                ]);
            });
        }
    };
