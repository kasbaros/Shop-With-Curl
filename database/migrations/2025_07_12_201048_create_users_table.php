<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->boolean('is_admin')->default(false)->after('password');
            $table->date('date_of_birth')->nullable()->after('is_admin');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('date_of_birth');
            $table->timestamp('last_login_at')->nullable()->after('gender');
            $table->json('preferences')->nullable()->after('last_login_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'is_admin', 'date_of_birth', 'gender', 'last_login_at', 'preferences'
            ]);
        });
    }
};
