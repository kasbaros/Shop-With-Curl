<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void {
            Schema::create('promo_banners', function (Blueprint $table) {
                $table->id();
                $table->string('heading');
                $table->string('subtitle')->nullable();
                $table->json('features')->nullable(); // ["Premium Materials","Perfect Fit",...]
                $table->string('cta_text')->nullable();
                $table->string('cta_link')->nullable();
                $table->string('price_badge')->nullable(); // "Starting at UGX 35,000"
                $table->string('image_desktop')->nullable();
                $table->string('image_mobile')->nullable();
                $table->boolean('active')->default(true);
                $table->unsignedInteger('priority')->default(10);
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('ends_at')->nullable();
                $table->timestamps();
            });
        }
        public function down(): void {
            Schema::dropIfExists('promo_banners');
        }
    };
