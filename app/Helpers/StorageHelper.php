<?php

    namespace App\Helpers;

    use Illuminate\Support\Facades\URL;

    class StorageHelper
    {
        /**
         * Generate storage URL that works on any hosting
         */
        public static function url(string $path): string
        {
            // Remove leading slash if present
            $path = ltrim($path, '/');

            // Prefer named route when available, but fall back safely to /storage/{path}
            try {
                if (app()->routesAreCached() || app('router')->getRoutes()->hasNamedRoute('storage.serve')) {
                    return route('storage.serve', ['path' => $path]);
                }
            } catch (\Throwable $e) {
                // Swallow and use fallback below
            }

            // Fallback: build a URL to /storage/{path}
            return URL::to('storage/' . $path);
        }

        /**
         * Generate optimized image URL
         */
        public static function imageUrl(string $path, ?int $width = null, ?int $height = null, int $quality = 85): string
        {
            $url = self::url($path);

            $params = array_filter([
                'w' => $width,
                'h' => $height,
                'q' => $quality !== 85 ? $quality : null,
            ]);

            return $params ? $url . '?' . http_build_query($params) : $url;
        }
    }
