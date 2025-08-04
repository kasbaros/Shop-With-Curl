<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\Cache;

    class Setting extends Model
    {
        use HasFactory;

        protected $fillable = [
            'key',
            'value',
            'group',
            'type',
            'description',
            'is_public',
            'is_system',
            'sort_order',
            'options',
        ];

        protected $casts = [
            'is_public' => 'boolean',
            'is_system' => 'boolean',
            'sort_order' => 'integer',
            'options' => 'array',
        ];

        /**
         * Get the display value of the setting
         */
        public function getDisplayValueAttribute()
        {
            return match ($this->type) {
                'boolean' => (bool)$this->value,
                'integer' => (int)$this->value,
                'decimal', 'float' => (float)$this->value,
                'json' => json_decode($this->value, true),
                'array' => is_array($this->value) ? $this->value : json_decode($this->value, true),
                default => $this->value
            };
        }

        /**
         * Clear settings cache when model is saved or deleted
         */
        protected static function boot()
        {
            parent::boot();

            static::saved(function ($setting) {
                $setting->clearRelatedCache();
            });

            static::deleted(function ($setting) {
                $setting->clearRelatedCache();
            });
        }

        /**
         * Clear related cache entries efficiently
         */
        public function clearRelatedCache()
        {
            // Clear specific caches
            Cache::forget("setting_{$this->key}");
            Cache::forget("settings_group_{$this->group}");
            Cache::forget('public_settings');
            Cache::forget('all_settings');
            Cache::forget('site_config');

            // Log cache clearing for debugging
            \Log::info("Cleared cache for setting: {$this->key}");
        }

        /**
         * Scope for public settings
         */
        public function scopePublic($query)
        {
            return $query->where('is_public', true);
        }

        /**
         * Scope for system settings
         */
        public function scopeSystem($query)
        {
            return $query->where('is_system', true);
        }

        /**
         * Scope by group
         */
        public function scopeGroup($query, $group)
        {
            return $query->where('group', $group);
        }

        /**
         * Get settings optimized for database cache
         */
        public static function getCachedByGroup($group)
        {
            return Cache::remember("settings_group_{$group}", 7200, function () use ($group) {
                return static::where('group', $group)
                    ->orderBy('sort_order')
                    ->get()
                    ->pluck('display_value', 'key')
                    ->toArray();
            });
        }

        /**
         * Bulk update settings (efficient for forms)
         */
        public static function bulkUpdate(array $settings, $group = null)
        {
            $updatedGroups = [];

            foreach ($settings as $key => $value) {
                $setting = static::updateOrCreate(
                    ['key' => $key],
                    [
                        'value' => $value,
                        'group' => $group ?? 'general',
                    ]
                );
                $updatedGroups[] = $setting->group;
            }

            // Clear cache for all affected groups
            foreach (array_unique($updatedGroups) as $affectedGroup) {
                Cache::forget("settings_group_{$affectedGroup}");
            }

            // Clear common caches
            Cache::forget('public_settings');
            Cache::forget('all_settings');
            Cache::forget('site_config');

            return true;
        }
    }
