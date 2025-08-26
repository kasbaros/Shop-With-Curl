<?php

    namespace Database\Seeders;

    use App\Models\Category;
    use Illuminate\Database\Seeder;

    class DatabaseSeeder extends Seeder
    {
        /**
         * Seed the application's database.
         */
        public function run(): void
        {
            // Always seed admin users first
            $this->call([
                AdminUserSeeder::class,
                SettingsSeeder::class,
                CategoryTypeSeeder::class,
                GallerySeeder::class,
                BannerSeeder::class,
            ]);

            // Only create test users in non-production environments
            if (!app()->environment('production')) {
                // Your existing test user
                \App\Models\User::factory()->create([
                    'name' => 'Test User',
                    'email' => 'test@example.com',
                ]);

                // Additional test users if needed
                // \App\Models\User::factory(10)->create();
            }
        }
    }
