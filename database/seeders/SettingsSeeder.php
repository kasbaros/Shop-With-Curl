<?php

    namespace Database\Seeders;

    use App\Models\Setting;
    use Illuminate\Database\Seeder;

    class SettingsSeeder extends Seeder
    {
        public function run(): void
        {
            $settings = [
                // General Settings
                [
                    'key' => 'site_name',
                    'value' => config('app.name'),
                    'group' => 'general',
                    'type' => 'text',
                    'description' => 'The name of your website',
                    'is_public' => true,
                    'sort_order' => 1,
                ],
                [
                    'key' => 'site_tagline',
                    'value' => 'Your Shopping Destination',
                    'group' => 'general',
                    'type' => 'text',
                    'description' => 'A short description of your site',
                    'is_public' => true,
                    'sort_order' => 2,
                ],
                [
                    'key' => 'site_description',
                    'value' => 'Welcome to our online store where you can find quality products at great prices.',
                    'group' => 'general',
                    'type' => 'textarea',
                    'description' => 'Site description used for SEO',
                    'is_public' => true,
                    'sort_order' => 3,
                ],
                [
                    'key' => 'site_email',
                    'value' => 'info@shopwithcarl.com',
                    'group' => 'general',
                    'type' => 'email',
                    'description' => 'Main contact email address',
                    'is_public' => true,
                    'sort_order' => 4,
                ],
                [
                    'key' => 'timezone',
                    'value' => 'UTC',
                    'group' => 'general',
                    'type' => 'select',
                    'description' => 'Default timezone for the application',
                    'sort_order' => 5,
                ],
                [
                    'key' => 'currency',
                    'value' => 'USD',
                    'group' => 'general',
                    'type' => 'select',
                    'description' => 'Default currency for products and orders',
                    'is_public' => true,
                    'sort_order' => 6,
                ],
                [
                    'key' => 'date_format',
                    'value' => 'M j, Y',
                    'group' => 'general',
                    'type' => 'select',
                    'description' => 'Default date format',
                    'sort_order' => 7,
                ],
                [
                    'key' => 'time_format',
                    'value' => 'g:i A',
                    'group' => 'general',
                    'type' => 'select',
                    'description' => 'Default time format',
                    'sort_order' => 8,
                ],
                [
                    'key' => 'language',
                    'value' => 'en',
                    'group' => 'general',
                    'type' => 'select',
                    'description' => 'Default site language',
                    'is_public' => true,
                    'sort_order' => 9,
                ],
                [
                    'key' => 'primary_color',
                    'value' => '#007bff',
                    'group' => 'general',
                    'type' => 'color',
                    'description' => 'Primary brand color',
                    'is_public' => true,
                    'sort_order' => 10,
                ],

                // Store Settings
                [
                    'key' => 'store_address',
                    'value' => '123 Commerce St, Business City, BC 12345',
                    'group' => 'store',
                    'type' => 'textarea',
                    'description' => 'Physical store address',
                    'is_public' => true,
                    'sort_order' => 1,
                ],
                [
                    'key' => 'tax_rate',
                    'value' => '8.5',
                    'group' => 'store',
                    'type' => 'decimal',
                    'description' => 'Default tax rate percentage',
                    'sort_order' => 2,
                ],
                [
                    'key' => 'enable_reviews',
                    'value' => '1',
                    'group' => 'store',
                    'type' => 'boolean',
                    'description' => 'Allow customers to leave product reviews',
                    'sort_order' => 3,
                ],

                // Email Settings
                [
                    'key' => 'mail_driver',
                    'value' => 'smtp',
                    'group' => 'email',
                    'type' => 'select',
                    'description' => 'Email driver (smtp, sendmail, etc.)',
                    'sort_order' => 1,
                ],
                [
                    'key' => 'mail_from_address',
                    'value' => 'noreply@shopwithcarl.com',
                    'group' => 'email',
                    'type' => 'email',
                    'description' => 'Default from email address',
                    'sort_order' => 2,
                ],
                [
                    'key' => 'mail_from_name',
                    'value' => 'ShopWithCarl',
                    'group' => 'email',
                    'type' => 'text',
                    'description' => 'Default from name for emails',
                    'sort_order' => 3,
                ],

                // API Settings
                [
                    'key' => 'api_keys',
                    'value' => '[]',
                    'group' => 'api',
                    'type' => 'json',
                    'description' => 'Generated API keys',
                    'sort_order' => 1,
                ],

                // Maintenance
                [
                    'key' => 'maintenance_enabled',
                    'value' => '0',
                    'group' => 'maintenance',
                    'type' => 'boolean',
                    'description' => 'Enable maintenance mode',
                    'is_system' => true,
                    'sort_order' => 1,
                ],
                [
                    'key' => 'recent_backups',
                    'value' => '[]',
                    'group' => 'maintenance',
                    'type' => 'json',
                    'description' => 'Recent backup information',
                    'is_system' => true,
                    'sort_order' => 2,
                ],
            ];

            foreach ($settings as $setting) {
                Setting::updateOrCreate(
                    ['key' => $setting['key']],
                    $setting
                );
            }
        }
    }
