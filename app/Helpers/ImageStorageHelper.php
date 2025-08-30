<?php

    namespace App\Helpers;

    use Illuminate\Http\UploadedFile;
    use Illuminate\Support\Facades\File;
    use Illuminate\Support\Str;

    class ImageStorageHelper
    {
        /**
         * Get the base path where images should be stored
         * This points directly to public_html/storage for production
         */
        private static function getStorageBasePath(): string
        {
            // In your setup, this should point to public_html/storage
            if (app()->isLocal()) {
                return public_path('storage');
            }

            return $_SERVER['DOCUMENT_ROOT'] . '/storage';
        }


        /**
         * Store image in public_html/storage directory
         */
        public static function store(UploadedFile $file, string $directory, ?string $filename = null): string
        {
            // Generate filename if not provided
            if (!$filename) {
                $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            }

            // Get the full directory path
            $fullDirectoryPath = self::getStorageBasePath() . '/' . $directory;

            // Create directory if it doesn't exist. This is non-destructive.
            // The `true` for recursive ensures parent directories are also made if needed.
            if (!File::isDirectory($fullDirectoryPath)) {
                File::makeDirectory($fullDirectoryPath, 0755, true, true);
            }

            // Move the file to the directory
            $file->move($fullDirectoryPath, $filename);

            // Return the relative path for database storage (without leading slash)
            return $directory . '/' . $filename;
        }


        /**
         * Get the full URL for an image
         */
        public static function url(?string $path): string
        {
            // If path empty, return placeholder
            if (!$path) {
                return asset('images/placeholder-product.jpg');
            }

            // Clean the path
            $clean = ltrim($path, '/');

            // If it's already a full URL (http/https), return as-is
            if (preg_match('/^https?:\/\//i', $clean)) {
                return $clean;
            }

            // If file exists in storage, return storage URL; otherwise placeholder
            if (self::exists($clean)) {
                return asset('storage/' . $clean);
            }

            // Also attempt to detect if given value is under public/ directly
            $publicPath = public_path($clean);
            if (file_exists($publicPath)) {
                return asset($clean);
            }

            return asset('images/placeholder-product.jpg');
        }

        /**
         * Delete an image
         */
        public static function delete(?string $path): bool
        {
            if (!$path) {
                return false;
            }

            $fullPath = self::getStorageBasePath() . '/' . ltrim($path, '/');

            if (File::exists($fullPath)) {
                return File::delete($fullPath);
            }

            return false;
        }

        /**
         * Check if image exists
         */
        public static function exists(?string $path): bool
        {
            if (!$path) {
                return false;
            }

            $fullPath = self::getStorageBasePath() . '/' . ltrim($path, '/');
            return File::exists($fullPath);
        }
    }
