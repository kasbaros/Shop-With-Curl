<?php

    use App\Models\Setting;
    use Illuminate\Support\Facades\Cache;

    if (!function_exists('setting')) {
        /**
         * Get setting value by key
         *
         * @param string $key
         * @param mixed $default
         * @return mixed
         */
        function setting($key, $default = null): mixed
        {
            // Use longer cache time for database cache since it's already in DB
            return Cache::remember("setting_{$key}", 7200, function () use ($key, $default) {
                try {
                    $setting = Setting::where('key', $key)->first();
                    return $setting ? $setting->display_value : $default;
                } catch (\Exception $e) {
                    // Fallback to default if database error
                    \Log::warning("Settings helper error for key '{$key}': " . $e->getMessage());
                    return $default;
                }
            });
        }
    }

    if (!function_exists('settings')) {
        /**
         * Get multiple settings by group or array of keys
         *
         * @param string|array $groupOrKeys
         * @return array
         */
        function settings($groupOrKeys): array
        {
            if (is_string($groupOrKeys)) {
                return Cache::remember("settings_group_{$groupOrKeys}", 7200, function () use ($groupOrKeys) {
                    try {
                        return Setting::where('group', $groupOrKeys)
                            ->orderBy('sort_order')
                            ->pluck('value', 'key')
                            ->toArray();
                    } catch (\Exception $e) {
                        \Log::warning("Settings helper error for group '{$groupOrKeys}': " . $e->getMessage());
                        return [];
                    }
                });
            }

            if (is_array($groupOrKeys)) {
                $cacheKey = "settings_keys_" . md5(serialize($groupOrKeys));
                return Cache::remember($cacheKey, 7200, function () use ($groupOrKeys) {
                    try {
                        return Setting::whereIn('key', $groupOrKeys)
                            ->pluck('value', 'key')
                            ->toArray();
                    } catch (\Exception $e) {
                        \Log::warning("Settings helper error for keys: " . $e->getMessage());
                        return [];
                    }
                });
            }

            return [];
        }
    }

    if (!function_exists('set_setting')) {
        /**
         * Set setting value
         *
         * @param string $key
         * @param mixed $value
         * @param string $group
         * @return bool
         */
        function set_setting($key, $value, $group = 'general'): bool
        {
            try {
                Setting::updateOrCreate(
                    ['key' => $key],
                    [
                        'value' => $value,
                        'group' => $group,
                    ]
                );

                // Clear relevant caches
                Cache::forget("setting_{$key}");
                Cache::forget("settings_group_{$group}");
                Cache::forget('public_settings');
                Cache::forget('all_settings');

                return true;
            } catch (\Exception $e) {
                \Log::error("Error setting '{$key}': " . $e->getMessage());
                return false;
            }
        }
    }

    if (!function_exists('public_settings')) {
        /**
         * Get public settings (for frontend use)
         *
         * @return array
         */
        function public_settings(): array
        {
            return Cache::remember('public_settings', 7200, function () {
                try {
                    return Setting::where('is_public', true)
                        ->pluck('value', 'key')
                        ->toArray();
                } catch (\Exception $e) {
                    \Log::warning("Public settings error: " . $e->getMessage());
                    return [];
                }
            });
        }
    }

    if (!function_exists('all_settings')) {
        /**
         * Get all settings (useful for admin)
         *
         * @return \Illuminate\Support\Collection
         */
        function all_settings(): \Illuminate\Support\Collection
        {
            return Cache::remember('all_settings', 3600, function () {
                try {
                    return Setting::orderBy('group')
                        ->orderBy('sort_order')
                        ->get()
                        ->groupBy('group');
                } catch (\Exception $e) {
                    \Log::warning("All settings error: " . $e->getMessage());
                    return collect();
                }
            });
        }
    }

    if (!function_exists('clear_settings_cache')) {
        /**
         * Clear all settings cache
         *
         * @return void
         */
        function clear_settings_cache(): void
        {
            $cacheKeys = [
                'public_settings',
                'all_settings',
            ];

            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }

            // Clear group caches
            $groups = ['general', 'store', 'email', 'payment', 'shipping', 'seo', 'social', 'security', 'advanced', 'api', 'maintenance'];
            foreach ($groups as $group) {
                Cache::forget("settings_group_{$group}");
            }

            \Log::info('Settings cache cleared');
        }
    }

    if (!function_exists('format_currency')) {
        /**
         * Format amount as currency
         *
         * @param float $amount
         * @param string|null $currency
         * @return string
         */
        function format_currency($amount, $currency = null): string
        {
            $currency = $currency ?? setting('currency', 'UGX');
            $currencySymbols = [
                'USD' => '$',
                'EUR' => '€',
                'GBP' => '£',
                'CAD' => 'C$',
                'AUD' => 'A$',
                'JPY' => '¥',
                'UGX' => 'UGX ',
                'NGN' => '₦',
                'GHS' => 'GH₵',
                'ZAR' => 'R',
            ];

            $symbol = $currencySymbols[$currency] ?? $currency . ' ';

            // Handle different currencies formatting
            if (in_array($currency, ['JPY', 'KRW', 'VND'])) {
                // No decimals for these currencies
                return $symbol . number_format($amount, 0);
            }

            return $symbol . number_format($amount, 2);
        }
    }

    if (!function_exists('money_format_ugx')) {
        /**
         * Format amount as Ugandan Shilling with no decimals
         *
         * @param float|int $amount
         * @return string
         */
        function money_format_ugx($amount): string
        {
            return 'UGX ' . number_format((float)$amount, 0);
        }
    }

    if (!function_exists('format_date')) {
        /**
         * Format date using site settings
         *
         * @param mixed $date
         * @param string|null $format
         * @return string
         */
        function format_date($date, $format = null): string
        {
            if (!$date) return '';

            try {
                $format = $format ?? setting('date_format', 'M j, Y');
                $timezone = setting('timezone', 'UTC');

                return \Carbon\Carbon::parse($date)->setTimezone($timezone)->format($format);
            } catch (\Exception $e) {
                return \Carbon\Carbon::parse($date)->format('M j, Y');
            }
        }
    }

    if (!function_exists('format_datetime')) {
        /**
         * Format datetime using site settings
         *
         * @param mixed $datetime
         * @param string|null $format
         * @return string
         */
        function format_datetime($datetime, $format = null): string
        {
            if (!$datetime) return '';

            try {
                $dateFormat = setting('date_format', 'M j, Y');
                $timeFormat = setting('time_format', 'g:i A');
                $format = $format ?? $dateFormat . ' ' . $timeFormat;
                $timezone = setting('timezone', 'UTC');

                return \Carbon\Carbon::parse($datetime)->setTimezone($timezone)->format($format);
            } catch (\Exception $e) {
                return \Carbon\Carbon::parse($datetime)->format('M j, Y g:i A');
            }
        }
    }

    if (!function_exists('site_config')) {
        /**
         * Get common site configuration in one call
         *
         * @return array
         */
        function site_config(): array
        {
            return Cache::remember('site_config', 7200, function () {
                return [
                    'name' => setting('site_name', config('app.name')),
                    'tagline' => setting('site_tagline'),
                    'description' => setting('site_description'),
                    'email' => setting('site_email'),
                    'phone' => setting('site_phone'),
                    'logo' => setting('site_logo'),
                    'favicon' => setting('site_favicon'),
                    'currency' => setting('currency', 'UGX'),
                    'timezone' => setting('timezone', 'UTC'),
                    'language' => setting('language', 'en'),
                    'primary_color' => setting('primary_color', '#007bff'),
                    'secondary_color' => setting('secondary_color', '#6c757d'),
                ];
            });
        }
    }
