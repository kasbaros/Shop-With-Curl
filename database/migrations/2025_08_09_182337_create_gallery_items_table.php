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
            // Check if the table exists first
            if (Schema::hasTable('gallery_items')) {
                // Table exists, check if the 'image' column exists
                if (!Schema::hasColumn('gallery_items', 'image')) {
                    Schema::table('gallery_items', function (Blueprint $table) {
                        $table->string('image')->after('id');
                    });
                }

                // Check and add other columns if they don't exist
                if (!Schema::hasColumn('gallery_items', 'caption')) {
                    Schema::table('gallery_items', function (Blueprint $table) {
                        $table->string('caption')->nullable()->after('image');
                    });
                }

                if (!Schema::hasColumn('gallery_items', 'hashtags')) {
                    Schema::table('gallery_items', function (Blueprint $table) {
                        $table->json('hashtags')->nullable()->after('caption');
                    });
                }

                if (!Schema::hasColumn('gallery_items', 'link')) {
                    Schema::table('gallery_items', function (Blueprint $table) {
                        $table->string('link')->nullable()->after('hashtags');
                    });
                }

                if (!Schema::hasColumn('gallery_items', 'product_id')) {
                    Schema::table('gallery_items', function (Blueprint $table) {
                        $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete()->after('link');
                    });
                }

                if (!Schema::hasColumn('gallery_items', 'source_type')) {
                    Schema::table('gallery_items', function (Blueprint $table) {
                        $table->enum('source_type', ['upload', 'instagram', 'customer'])->default('upload')->after('product_id');
                    });
                }

                if (!Schema::hasColumn('gallery_items', 'is_featured')) {
                    Schema::table('gallery_items', function (Blueprint $table) {
                        $table->boolean('is_featured')->default(false)->after('source_type');
                    });
                }

                if (!Schema::hasColumn('gallery_items', 'is_active')) {
                    Schema::table('gallery_items', function (Blueprint $table) {
                        $table->boolean('is_active')->default(true)->after('is_featured');
                    });
                }

                if (!Schema::hasColumn('gallery_items', 'sort_order')) {
                    Schema::table('gallery_items', function (Blueprint $table) {
                        $table->integer('sort_order')->default(0)->after('is_active');
                    });
                }

                // Add indexes - Laravel will handle if they already exist
                try {
                    Schema::table('gallery_items', function (Blueprint $table) {
                        $table->index(['is_active', 'is_featured']);
                        $table->index('sort_order');
                    });
                } catch (\Exception $e) {
                    // Indexes might already exist, which is fine
                }
            } else {
                // Create the table if it doesn't exist
                Schema::create('gallery_items', function (Blueprint $table) {
                    $table->id();
                    $table->string('image');
                    $table->string('caption')->nullable();
                    $table->json('hashtags')->nullable();
                    $table->string('link')->nullable();
                    $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
                    $table->enum('source_type', ['upload', 'instagram', 'customer'])->default('upload');
                    $table->boolean('is_featured')->default(false);
                    $table->boolean('is_active')->default(true);
                    $table->integer('sort_order')->default(0);
                    $table->timestamps();

                    $table->index(['is_active', 'is_featured']);
                    $table->index('sort_order');
                });
            }
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('gallery_items');
        }
    };
