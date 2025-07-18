<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });

            // Add index separately with condition
            Schema::table('notifications', function (Blueprint $table) {
                $indexName = 'notifications_notifiable_type_notifiable_id_index';
                if (!Schema::hasIndex('notifications', $indexName)) {
                    $table->index(['notifiable_type', 'notifiable_id'], $indexName);
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
