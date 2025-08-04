<?php

    namespace App\Http\Controllers\Admin;

    use App\Models\Setting;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\Mail;
    use Illuminate\Support\Facades\Artisan;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    use Carbon\Carbon;

    class SettingsController extends AdminController
    {
        public function index()
        {
            $stats = [
                'total_settings' => Setting::count(),
                'public_settings' => Setting::where('is_public', true)->count(),
                'system_settings' => Setting::where('is_system', true)->count(),
                'groups' => Setting::distinct('group')->count(),
            ];

            $recentUpdates = Setting::latest('updated_at')->limit(5)->get();

            return view('admin.settings.index', array_merge(
                $this->getAdminViewData(),
                compact('stats', 'recentUpdates')
            ));
        }

        public function general()
        {
            $timezones = $this->getTimezones();
            $currencies = $this->getCurrencies();
            $languages = $this->getLanguages();

            return view('admin.settings.general_settings', array_merge(
                $this->getAdminViewData(),
                compact('timezones', 'currencies', 'languages')
            ));
        }

        public function store()
        {
            $countries = $this->getCountries();
            $taxTypes = ['inclusive' => 'Tax Inclusive', 'exclusive' => 'Tax Exclusive'];

            return view('admin.settings.store_settings', array_merge(
                $this->getAdminViewData(),
                compact('countries', 'taxTypes')
            ));
        }

        public function payment()
        {
            return view('admin.settings.payment_settings', $this->getAdminViewData());
        }

        public function shipping()
        {
            $countries = $this->getCountries();

            return view('admin.settings.shipping', array_merge(
                $this->getAdminViewData(),
                compact('countries')
            ));
        }

        public function email()
        {
            return view('admin.settings.email', $this->getAdminViewData());
        }

        public function seo()
        {
            return view('admin.settings.seo_settings', $this->getAdminViewData());
        }

        public function social()
        {
            return view('admin.settings.social', $this->getAdminViewData());
        }

        public function security()
        {
            return view('admin.settings.security', $this->getAdminViewData());
        }

        public function advanced()
        {
            $cacheStats = $this->getCacheStats();

            return view('admin.settings.advanced', array_merge(
                $this->getAdminViewData(),
                compact('cacheStats')
            ));
        }

        public function integrations()
        {
            return view('admin.settings.integrations', $this->getAdminViewData());
        }

        public function api()
        {
            $apiKeys = json_decode(setting('api_keys', '[]'), true);

            return view('admin.settings.api', array_merge(
                $this->getAdminViewData(),
                compact('apiKeys')
            ));
        }

        public function maintenance()
        {
            $backups = json_decode(setting('recent_backups', '[]'), true);
            $maintenanceEnabled = setting('maintenance_enabled', false);

            return view('admin.settings.maintenance', array_merge(
                $this->getAdminViewData(),
                compact('backups', 'maintenanceEnabled')
            ));
        }

        public function update(Request $request)
        {
            $group = $request->input('group', 'general');

            // Validate based on group
            $rules = $this->getValidationRules($group);
            $validated = $request->validate($rules);

            // Remove group from validated data
            unset($validated['group']);

            // Handle file uploads
            $validated = $this->handleFileUploads($validated, $request);

            // Bulk update settings
            Setting::bulkUpdate($validated, $group);

            return redirect()->back()->with('success', ucfirst($group) . ' settings updated successfully!');
        }

        public function testEmail(Request $request)
        {
            $request->validate([
                'test_email' => 'required|email',
            ]);

            try {
                Mail::raw('This is a test email from ' . setting('site_name'), function ($message) use ($request) {
                    $message->to($request->test_email)
                        ->subject('Test Email from ' . setting('site_name'));
                });

                return response()->json(['success' => true, 'message' => 'Test email sent successfully!']);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Failed to send email: ' . $e->getMessage()]);
            }
        }

        public function generateApiKey(Request $request)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'permissions' => 'required|array',
            ]);

            $apiKey = [
                'id' => Str::uuid(),
                'name' => $request->name,
                'key' => 'swc_' . Str::random(40),
                'permissions' => $request->permissions,
                'created_at' => now()->toISOString(),
                'last_used' => null,
            ];

            $apiKeys = json_decode(setting('api_keys', '[]'), true);
            $apiKeys[] = $apiKey;

            set_setting('api_keys', json_encode($apiKeys), 'api');

            return response()->json(['success' => true, 'api_key' => $apiKey]);
        }

        public function revokeApiKey($keyId)
        {
            $apiKeys = json_decode(setting('api_keys', '[]'), true);
            $apiKeys = array_filter($apiKeys, fn($key) => $key['id'] !== $keyId);

            set_setting('api_keys', json_encode(array_values($apiKeys)), 'api');

            return response()->json(['success' => true, 'message' => 'API key revoked successfully']);
        }

        public function clearCache()
        {
            try {
                clear_settings_cache();
                Cache::flush();

                return response()->json(['success' => true, 'message' => 'Cache cleared successfully!']);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Failed to clear cache: ' . $e->getMessage()]);
            }
        }

        public function backup()
        {
            try {
                $backupName = 'backup_' . now()->format('Y_m_d_H_i_s') . '.sql';

                // Simple backup command (adjust for your hosting)
                $dbName = config('database.connections.pgsql.database');
                $dbUser = config('database.connections.pgsql.username');
                $dbHost = config('database.connections.pgsql.host');

                $backupPath = storage_path('app/backups/' . $backupName);

                // Create backups directory if it doesn't exist
                if (!Storage::exists('backups')) {
                    Storage::makeDirectory('backups');
                }

                // For PostgreSQL
                $command = "pg_dump -h {$dbHost} -U {$dbUser} -d {$dbName} > {$backupPath}";

                // Execute backup (this is basic - you might want to use a package)
                exec($command, $output, $returnCode);

                if ($returnCode === 0) {
                    // Update recent backups
                    $backups = json_decode(setting('recent_backups', '[]'), true);
                    $backups[] = [
                        'name' => $backupName,
                        'size' => filesize($backupPath),
                        'created_at' => now()->toISOString(),
                    ];

                    // Keep only last 10 backups
                    $backups = array_slice($backups, -10);
                    set_setting('recent_backups', json_encode($backups), 'maintenance');

                    return response()->json(['success' => true, 'message' => 'Backup created successfully!']);
                } else {
                    return response()->json(['success' => false, 'message' => 'Backup failed']);
                }
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Backup failed: ' . $e->getMessage()]);
            }
        }

        public function maintenanceMode(Request $request)
        {
            $enabled = $request->boolean('enabled');

            if ($enabled) {
                Artisan::call('down', [
                    '--message' => $request->input('message', 'We are currently performing scheduled maintenance.'),
                    '--retry' => 60,
                ]);
            } else {
                Artisan::call('up');
            }

            set_setting('maintenance_enabled', $enabled ? '1' : '0', 'maintenance');

            $status = $enabled ? 'enabled' : 'disabled';
            return response()->json(['success' => true, 'message' => "Maintenance mode {$status} successfully"]);
        }

        public function export()
        {
            $settings = Setting::all();
            $data = $settings->map(function ($setting) {
                return [
                    'key' => $setting->key,
                    'value' => $setting->value,
                    'group' => $setting->group,
                    'type' => $setting->type,
                    'description' => $setting->description,
                    'is_public' => $setting->is_public,
                    'is_system' => $setting->is_system,
                    'sort_order' => $setting->sort_order,
                    'options' => $setting->options,
                ];
            });

            $filename = 'settings_export_' . now()->format('Y_m_d_H_i_s') . '.json';

            return response()->json($data)
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Content-Type', 'application/json');
        }

        public function import(Request $request)
        {
            $request->validate([
                'settings_file' => 'required|file|mimes:json',
            ]);

            try {
                $content = file_get_contents($request->file('settings_file')->getRealPath());
                $settings = json_decode($content, true);

                if (!is_array($settings)) {
                    throw new \Exception('Invalid settings file format');
                }

                $imported = 0;
                foreach ($settings as $settingData) {
                    if (isset($settingData['key'])) {
                        Setting::updateOrCreate(
                            ['key' => $settingData['key']],
                            $settingData
                        );
                        $imported++;
                    }
                }

                clear_settings_cache();

                return redirect()->back()->with('success', "Imported {$imported} settings successfully!");
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
            }
        }

        // Helper methods
        private function getValidationRules($group)
        {
            $rules = ['group' => 'required|string'];

            switch ($group) {
                case 'general':
                    $rules += [
                        'site_name' => 'required|string|max:255',
                        'site_email' => 'required|email',
                        'site_tagline' => 'nullable|string|max:255',
                        'site_description' => 'nullable|string|max:1000',
                        'site_phone' => 'nullable|string|max:20',
                        'timezone' => 'required|string',
                        'currency' => 'required|string|size:3',
                        'language' => 'required|string|size:2',
                        'date_format' => 'required|string',
                        'time_format' => 'required|string',
                        'primary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
                        'secondary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
                        'site_logo' => 'nullable|image|max:2048',
                        'site_favicon' => 'nullable|image|max:1024',
                    ];
                    break;

                case 'store':
                    $rules += [
                        'store_name' => 'required|string|max:255',
                        'store_address' => 'required|string|max:500',
                        'store_city' => 'required|string|max:100',
                        'store_country' => 'required|string|size:2',
                        'store_postal_code' => 'required|string|max:20',
                        'tax_rate' => 'required|numeric|min:0|max:100',
                        'tax_type' => 'required|in:inclusive,exclusive',
                        'enable_reviews' => 'boolean',
                        'enable_wishlist' => 'boolean',
                        'enable_compare' => 'boolean',
                    ];
                    break;

                case 'email':
                    $rules += [
                        'mail_driver' => 'required|in:smtp,sendmail,mailgun,ses',
                        'mail_host' => 'required_if:mail_driver,smtp|string',
                        'mail_port' => 'required_if:mail_driver,smtp|integer',
                        'mail_username' => 'required_if:mail_driver,smtp|string',
                        'mail_password' => 'required_if:mail_driver,smtp|string',
                        'mail_encryption' => 'nullable|in:tls,ssl',
                        'mail_from_address' => 'required|email',
                        'mail_from_name' => 'required|string|max:255',
                    ];
                    break;
            }

            return $rules;
        }

        private function handleFileUploads($validated, $request)
        {
            if ($request->hasFile('site_logo')) {
                $path = $request->file('site_logo')->store('logos', 'public');
                $validated['site_logo'] = $path;
            }

            if ($request->hasFile('site_favicon')) {
                $path = $request->file('site_favicon')->store('favicons', 'public');
                $validated['site_favicon'] = $path;
            }

            return $validated;
        }

        private function getTimezones()
        {
            return collect(timezone_identifiers_list())->mapWithKeys(function ($timezone) {
                return [$timezone => $timezone];
            })->toArray();
        }

        private function getCurrencies()
        {
            return [
                'USD' => 'US Dollar (USD)',
                'EUR' => 'Euro (EUR)',
                'GBP' => 'British Pound (GBP)',
                'CAD' => 'Canadian Dollar (CAD)',
                'AUD' => 'Australian Dollar (AUD)',
                'JPY' => 'Japanese Yen (JPY)',
                'UGX' => 'Ugandan Shilling (UGX)',
                'NGN' => 'Nigerian Naira (NGN)',
                'GHS' => 'Ghanaian Cedi (GHS)',
                'ZAR' => 'South African Rand (ZAR)',
            ];
        }

        private function getLanguages()
        {
            return [
                'en' => 'English',
                'es' => 'Spanish',
                'fr' => 'French',
                'de' => 'German',
                'it' => 'Italian',
                'pt' => 'Portuguese',
                'ar' => 'Arabic',
                'zh' => 'Chinese',
                'ja' => 'Japanese',
                'ko' => 'Korean',
            ];
        }

        private function getCountries()
        {
            return [
                'US' => 'United States',
                'CA' => 'Canada',
                'GB' => 'United Kingdom',
                'AU' => 'Australia',
                'DE' => 'Germany',
                'FR' => 'France',
                'IT' => 'Italy',
                'ES' => 'Spain',
                'UG' => 'Uganda',
                'NG' => 'Nigeria',
                'GH' => 'Ghana',
                'ZA' => 'South Africa',
                // Add more countries as needed
            ];
        }

        private function getCacheStats()
        {
            try {
                $cacheSize = Cache::remember('cache_size', 300, function () {
                    return \DB::table('cache')->count();
                });

                return [
                    'entries' => $cacheSize,
                    'driver' => config('cache.default'),
                    'last_cleared' => setting('cache_last_cleared', 'Never'),
                ];
            } catch (\Exception $e) {
                return [
                    'entries' => 'Unknown',
                    'driver' => config('cache.default'),
                    'last_cleared' => 'Never',
                ];
            }
        }
    }
