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
                ['email' => env('ADMIN_DEFAULT_EMAIL', 'admin@shopwithcarl.com')],
                [
                    'name' => 'System Administrator',
                    'email' => env('ADMIN_DEFAULT_EMAIL', 'admin@shopwithcarl.com'),
                    'password' => Hash::make(env('ADMIN_DEFAULT_PASSWORD', 'SecureAdmin123!')),
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
            if (env('DEVELOPER_DEFAULT_EMAIL')) {
                $developer = User::firstOrCreate(
                    ['email' => env('DEVELOPER_DEFAULT_EMAIL')],
                    [
                        'name' => 'Developer',
                        'email' => env('DEVELOPER_DEFAULT_EMAIL'),
                        'password' => Hash::make(env('DEVELOPER_DEFAULT_PASSWORD', 'SecureDev123!')),
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
        }
    }
