<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up()
        {
            Schema::table('categories', function (Blueprint $table) {
                $table->enum('type', ['by_style', 'by_occasion', 'collections'])
                    ->after('parent_id')
                    ->nullable()
                    ->default('collections');

                // Add index for better performance
                $table->index(['type', 'is_active']);
            });
        }

        public function down()
        {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropIndex(['type', 'is_active']);
                $table->dropColumn('type');
            });
        }
    };
