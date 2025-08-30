<?php

    namespace Database\Seeders;

    use App\Models\User;
    use Illuminate\Database\Seeder;
    use Illuminate\Support\Facades\Hash;

    class AdminUserSeeder extends Seeder
    {
        /**
         * Seed admin users for the application.
         */
        public function run(): void
        {
            // Create main admin user
            $admin = User::firstOrCreate(
                ['email' => config('app.admin_email', 'admin@shopwithcarl.com')],
                [
                    'name' => 'System Administrator',
                    'email' => config('app.admin_email', 'admin@shopwithcarl.com'),
                    'password' => Hash::make(config('app.admin_password', 'SecureAdmin123!')),
                    'role' => 'admin',
                    'is_admin' => true,
                    'email_verified_at' => now(),
                ]
            );

            if ($admin->wasRecentlyCreated) {
                $this->command->info('✅ Admin user created: ' . $admin->email);
            } else {
                $this->command->info('ℹ️ Admin user already exists: ' . $admin->email);
            }

            // Create developer user if configured
            if (config('app.developer_email')) {
                $developer = User::firstOrCreate(
                    ['email' => config('app.developer_email')],
                    [
                        'name' => 'Developer',
                        'email' => config('app.developer_email'),
                        'password' => Hash::make(config('app.developer_password', 'SecureDev123!')),
                        'role' => 'developer',
                        'is_admin' => true,
                        'email_verified_at' => now(),
                    ]
                );

                if ($developer->wasRecentlyCreated) {
                    $this->command->info('✅ Developer user created: ' . $developer->email);
                } else {
                    $this->command->info('ℹ️ Developer user already exists: ' . $developer->email);
                }
            }

            // Create test user if configured
            if (config('app.test_email')) {
                $developer = User::firstOrCreate(
                    ['email' => config('app.test_email')],
                    [
                        'name' => 'Teste User',
                        'email' => config('app.test_email'),
                        'password' => Hash::make(config('app.test_password', 'SecureTest123!')),
                        'role' => 'customer',
                        'is_admin' => false,
                        'email_verified_at' => now(),
                    ]
                );

                if ($developer->wasRecentlyCreated) {
                    $this->command->info('✅ Test user created: ' . $developer->email);
                } else {
                    $this->command->info('ℹ️ Test user already exists: ' . $developer->email);
                }
            }
        }
    }
