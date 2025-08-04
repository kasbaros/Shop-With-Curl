<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::create('review_replies', function (Blueprint $table) {
                $table->id();
                $table->foreignId('review_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->text('content');
                $table->boolean('is_admin_reply')->default(false);
                $table->timestamps();

                $table->index(['review_id']);
                $table->index(['user_id']);
                $table->index(['is_admin_reply']);
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('review_replies');
        }
    };
