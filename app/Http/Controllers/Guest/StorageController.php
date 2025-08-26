<?php
//
namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;

//class StorageController extends Controller
//{
//    public function serve(Request $request, $path)
//    {
//        // For shared hosting where Laravel is in a subfolder
//        $path = urldecode($path);
//
//        // Build the correct path - adjust this to match your setup
//        $fullPath = base_path('storage/app/public/' . $path);
//
//        // Alternative: if base_path doesn't work, use absolute path
//        // $fullPath = '/home/shopwithcaug/Laravel/storage/app/public/' . $path;
//
//        Log::info('Storage serve called', [
//            'path' => $path,
//            'full_path' => $fullPath,
//            'exists' => file_exists($fullPath),
//            'base_path' => base_path(),
//            'storage_path' => storage_path('app/public'),
//            'request_url' => $request->fullUrl()
//        ]);
//
//        try {
//            // Security validation
//            if (!$this->isPathAllowed($fullPath)) {
//                \Log::warning('Path not allowed', ['path' => $fullPath]);
//                return $this->serveNotFound();
//            }
//
//            // Check if file exists
//            if (!file_exists($fullPath)) {
//                \Log::warning('File not found', ['path' => $fullPath]);
//                return $this->serveNotFound();
//            }
//
//            // Determine MIME type
//            $mimeType = $this->getMimeType($fullPath);
//
//            // Serve the file
//            return $this->serveFile($fullPath, $mimeType);
//
//        } catch (\Exception $e) {
//            \Log::error('File serving error: ' . $e->getMessage(), [
//                'path' => $path,
//                'full_path' => $fullPath ?? null,
//                'trace' => $e->getTraceAsString()
//            ]);
//
//            return $this->serveNotFound();
//        }
//    }
//
//    private function isPathAllowed(string $fullPath): bool
//    {
//        $realPath = realpath($fullPath);
//        // Update the allowed path to match your structure
//        $allowedPath = realpath(base_path('storage/app/public'));
//
//        // Alternative absolute path if base_path doesn't work
//        // $allowedPath = realpath('/home/shopwithcaug/Laravel/storage/app/public');
//
//        if (!$realPath || !$allowedPath) {
//            return false;
//        }
//
//        if (str_contains($fullPath, '..') || str_contains($fullPath, '//')) {
//            \Log::warning('Directory traversal attempt detected', ['path' => $fullPath]);
//            return false;
//        }
//
//        return str_starts_with($realPath, $allowedPath);
//    }
//
//    private function getMimeType(string $fullPath): string
//    {
//        $mimeType = mime_content_type($fullPath);
//
//        if (!$mimeType) {
//            $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
//            $mimeTypes = [
//                'jpg' => 'image/jpeg',
//                'jpeg' => 'image/jpeg',
//                'png' => 'image/png',
//                'gif' => 'image/gif',
//                'webp' => 'image/webp',
//                'svg' => 'image/svg+xml',
//            ];
//
//            $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';
//        }
//
//        return $mimeType;
//    }
//
//    private function serveFile(string $fullPath, string $mimeType)
//    {
//        $fileSize = filesize($fullPath);
//        $lastModified = filemtime($fullPath);
//
//        $headers = [
//            'Content-Type' => $mimeType,
//            'Content-Length' => $fileSize,
//            'Cache-Control' => 'public, max-age=31536000',
//            'Last-Modified' => gmdate('D, d M Y H:i:s \G\M\T', $lastModified),
//        ];
//
//        return response()->file($fullPath, $headers);
//    }
//
//    private function serveNotFound()
//    {
//        // Try to serve a placeholder from public_html/images
//        $placeholderPaths = [
//            $_SERVER['DOCUMENT_ROOT'] . '/images/placeholder-product.jpg',
//            $_SERVER['DOCUMENT_ROOT'] . '/images/placeholder.jpg',
//        ];
//
//        foreach ($placeholderPaths as $placeholderPath) {
//            if (file_exists($placeholderPath)) {
//                return response()->file($placeholderPath, [
//                    'Content-Type' => 'image/jpeg',
//                    'Cache-Control' => 'public, max-age=86400',
//                ]);
//            }
//        }
//
//        // Return 1x1 transparent pixel
//        $pixel = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
//
//        return response($pixel, 200, [
//            'Content-Type' => 'image/png',
//            'Cache-Control' => 'public, max-age=86400',
//        ]);
//    }
//}

class StorageController extends Controller
{
    public function serve(Request $request, $path)
    {
        // For shared hosting where Laravel is in a subfolder
        $path = urldecode($path);

        // Debug information
        $debugInfo = [
            'requested_path' => $path,
            'request_uri' => $request->getRequestUri(),
            'full_url' => $request->fullUrl(),
            'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'not_set',
            'script_name' => $_SERVER['SCRIPT_NAME'] ?? 'not_set',
            'base_path' => base_path(),
            'storage_path' => storage_path(),
        ];

        // Try multiple path configurations for cPanel hosting
        $possiblePaths = [
            // Standard Laravel structure (should work based on your test results)
            storage_path('app/public/' . $path), // /home/shopwithcaug/Laravel/storage/app/public/
            base_path('storage/app/public/' . $path), // /home/shopwithcaug/Laravel/storage/app/public/

            // Direct absolute paths for your server structure
            '/home/shopwithcaug/Laravel/storage/app/public/' . $path,

            // Relative to document root (document_root is /home/shopwithcaug/public_html)
            $_SERVER['DOCUMENT_ROOT'] . '/../Laravel/storage/app/public/' . $path,
        ];

        $foundPath = null;
        $pathTests = [];

        foreach ($possiblePaths as $testPath) {
            $exists = file_exists($testPath);
            $pathTests[] = [
                'path' => $testPath,
                'exists' => $exists,
                'readable' => $exists ? is_readable($testPath) : false,
                'realpath' => $exists ? realpath($testPath) : null,
            ];

            if ($exists && !$foundPath) {
                $foundPath = $testPath;
            }
        }

        $debugInfo['path_tests'] = $pathTests;
        $debugInfo['found_path'] = $foundPath;

        // Log comprehensive debug info
        Log::info('Storage serve debug', $debugInfo);

        // If we're in debug mode, return the debug info as JSON
        if (config('app.debug') && $request->has('debug')) {
            return response()->json($debugInfo, 200, [], JSON_PRETTY_PRINT);
        }

        try {
            // Check if we found a valid file
            if (!$foundPath || !file_exists($foundPath)) {
                Log::warning('File not found in any location', [
                    'path' => $path,
                    'tested_paths' => array_column($pathTests, 'path'),
                ]);
                return $this->serveNotFound();
            }

            // Security validation
            if (!$this->isPathAllowed($foundPath)) {
                Log::warning('Path not allowed', ['path' => $foundPath]);
                return $this->serveNotFound();
            }

            // Determine MIME type
            $mimeType = $this->getMimeType($foundPath);

            Log::info('Serving file', [
                'path' => $path,
                'full_path' => $foundPath,
                'mime_type' => $mimeType,
                'file_size' => filesize($foundPath),
            ]);

            // Serve the file
            return $this->serveFile($foundPath, $mimeType);

        } catch (\Exception $e) {
            Log::error('File serving error: ' . $e->getMessage(), [
                'path' => $path,
                'found_path' => $foundPath ?? null,
                'debug_info' => $debugInfo,
                'trace' => $e->getTraceAsString()
            ]);

            return $this->serveNotFound();
        }
    }

    private function isPathAllowed(string $fullPath): bool
    {
        $realPath = realpath($fullPath);

        // Try multiple allowed paths for cPanel hosting
        $allowedPaths = [
            realpath(storage_path('app/public')),
            realpath(base_path('storage/app/public')),
            realpath('/home/shopwithcaug/Laravel/storage/app/public'),
            realpath($_SERVER['DOCUMENT_ROOT'] . '/../storage/app/public'),
        ];

        if (!$realPath) {
            Log::warning('Could not resolve real path', ['path' => $fullPath]);
            return false;
        }

        // Check for directory traversal attempts
        if (str_contains($fullPath, '..') || str_contains($fullPath, '//')) {
            Log::warning('Directory traversal attempt detected', ['path' => $fullPath]);
            return false;
        }

        // Check if the real path starts with any of our allowed paths
        foreach ($allowedPaths as $allowedPath) {
            if ($allowedPath && str_starts_with($realPath, $allowedPath)) {
                Log::info('Path allowed', [
                    'real_path' => $realPath,
                    'allowed_path' => $allowedPath
                ]);
                return true;
            }
        }

        Log::warning('Path not in allowed directories', [
            'real_path' => $realPath,
            'allowed_paths' => $allowedPaths
        ]);

        return false;
    }

    private function getMimeType(string $fullPath): string
    {
        $mimeType = mime_content_type($fullPath);

        if (!$mimeType) {
            $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
            $mimeTypes = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
                'svg' => 'image/svg+xml',
            ];

            $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';
        }

        return $mimeType;
    }

    private function serveFile(string $fullPath, string $mimeType)
    {
        $fileSize = filesize($fullPath);
        $lastModified = filemtime($fullPath);

        $headers = [
            'Content-Type' => $mimeType,
            'Content-Length' => $fileSize,
            'Cache-Control' => 'public, max-age=31536000',
            'Last-Modified' => gmdate('D, d M Y H:i:s \G\M\T', $lastModified),
        ];

        return response()->file($fullPath, $headers);
    }

    private function serveNotFound()
    {
        Log::info('Serving 404 fallback');

        // Try to serve a placeholder from public_html/images
        $placeholderPaths = [
            $_SERVER['DOCUMENT_ROOT'] . '/images/placeholder-product.jpg',
            $_SERVER['DOCUMENT_ROOT'] . '/images/placeholder.jpg',
            $_SERVER['DOCUMENT_ROOT'] . '/images/products/shop_with_carl-1.jpg',
        ];

        foreach ($placeholderPaths as $placeholderPath) {
            if (file_exists($placeholderPath)) {
                Log::info('Serving placeholder', ['path' => $placeholderPath]);
                return response()->file($placeholderPath, [
                    'Content-Type' => 'image/jpeg',
                    'Cache-Control' => 'public, max-age=86400',
                ]);
            }
        }

        // Return 1x1 transparent pixel
        Log::info('Serving transparent pixel fallback');
        $pixel = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');

        return response($pixel, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
