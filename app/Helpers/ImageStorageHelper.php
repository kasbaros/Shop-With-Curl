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
            if (!$path) {
                return asset('images/placeholder.jpg');
            }

            // Clean the path
            $path = ltrim($path, '/');

            // Return the URL that points to public_html/storage/...
            return asset('storage/' . $path);
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
