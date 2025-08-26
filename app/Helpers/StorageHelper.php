<?php

    namespace App\Helpers;

    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\File;

    class StorageHelper
    {
        /**
         * Store file in both storage and public directories for shared hosting
         */
        public static function storePublic($file, $directory = 'gallery', $filename = null)
        {
            // Generate filename if not provided
            if (!$filename) {
                $filename = time() . '_' . $directory . '.' . $file->getClientOriginalExtension();
            }

            // Store in Laravel's storage (for backup/admin access)
            $storagePath = $file->storeAs($directory, $filename, 'public');

            // ALSO copy to public_html for direct web access
            $publicPath = $_SERVER['DOCUMENT_ROOT'] . '/storage/' . $directory;
            $publicFilePath = $publicPath . '/' . $filename;

            // Create directory if it doesn't exist
            if (!is_dir($publicPath)) {
                mkdir($publicPath, 0755, true);
            }

            // Copy file to public directory
            copy($file->getRealPath(), $publicFilePath);

            return $storagePath; // Return the storage path for database saving
        }

        /**
         * Generate storage URL that works on shared hosting
         */
        public static function url(string $path): string
        {
            // Remove leading slash if present
            $path = ltrim($path, '/');

            // For shared hosting, serve directly from public_html/storage
            return asset('storage/' . $path);
        }

        /**
         * Generate optimized image URL (simplified for shared hosting)
         */
        public static function imageUrl(string $path, ?int $width = null, ?int $height = null, int $quality = 85): string
        {
            // For shared hosting, just return the direct URL
            // Image optimization can be handled client-side or via external services
            return self::url($path);
        }

        /**
         * Delete file from both storage and public directories
         */
        public static function delete(string $path)
        {
            // Delete from Laravel storage
            Storage::disk('public')->delete($path);

            // Also delete from public directory
            $publicFilePath = $_SERVER['DOCUMENT_ROOT'] . '/storage/' . $path;
            if (file_exists($publicFilePath)) {
                unlink($publicFilePath);
            }
        }
    }
