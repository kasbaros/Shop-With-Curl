<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::create('reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->integer('rating')->unsigned()->default(5);
                $table->string('title')->nullable();
                $table->text('comment');
                $table->boolean('is_approved')->default(false);
                $table->boolean('is_verified_purchase')->default(false);
                $table->integer('helpful_count')->default(0);
                $table->integer('unhelpful_count')->default(0);
                $table->timestamps();

                $table->index(['product_id', 'is_approved']);
                $table->index(['user_id']);
                $table->index(['rating']);
                $table->index(['created_at']);
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('reviews');
        }
    };
