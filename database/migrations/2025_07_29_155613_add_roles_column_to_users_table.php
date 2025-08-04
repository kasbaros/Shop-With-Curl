<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['customer', 'admin', 'developer'])
                    ->default('customer')
                    ->after('email');
                $table->ipAddress('last_login_ip')->nullable()->after('last_login_at');
            });
        }

        public function down(): void
        {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['role', 'last_login_at', 'last_login_ip']);
            });
        }
    };
