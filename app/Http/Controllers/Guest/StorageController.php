<?php
//
//namespace App\Http\Controllers\Guest;
//
//use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
//use Illuminate\Http\Response;
//use Illuminate\Support\Facades\Storage;
//use Symfony\Component\HttpFoundation\StreamedResponse;
//
//class StorageController extends Controller
//{
//    /**
//     * Serve files from storage/app/public dynamically
//     */
//    public function serve(Request $request, $path)
//    {
//        // Add debugging
//        \Log::info('Storage serve called', [
//            'path' => $path,
//            'decoded_path' => urldecode($path),
//            'full_path' => storage_path('app/public/' . urldecode($path)),
//            'exists' => file_exists(storage_path('app/public/' . urldecode($path))),
//            'request_url' => $request->fullUrl(),
//            'storage_path' => storage_path('app/public')
//        ]);
//
//        try {
//            // Decode the path to handle special characters
//            $path = urldecode($path);
//
//            // Build the full path
//            $fullPath = storage_path('app/public/' . $path);
//
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
//            // Handle image optimization for different sizes (only if Intervention Image is available)
//            if ($this->isImage($mimeType) && ($request->has('w') || $request->has('h')) && $this->interventionImageAvailable()) {
//                return $this->serveOptimizedImage($fullPath, $request, $mimeType);
//            }
//
//            // Serve the file with appropriate headers
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
//    /**
//     * Check if Intervention Image is available
//     */
//    private function interventionImageAvailable(): bool
//    {
//        return class_exists(\Intervention\Image\ImageManager::class) ||
//            class_exists(\Intervention\Image\Facades\Image::class);
//    }
//
//    /**
//     * Security check: ensure path is within allowed directory
//     */
//    private function isPathAllowed(string $fullPath): bool
//    {
//        $realPath = realpath($fullPath);
//        $allowedPath = realpath(storage_path('app/public'));
//
//        if (!$realPath || !$allowedPath) {
//            return false;
//        }
//
//        // Additional security: check for directory traversal attempts
//        if (str_contains($fullPath, '..') || str_contains($fullPath, '//')) {
//            \Log::warning('Directory traversal attempt detected', ['path' => $fullPath]);
//            return false;
//        }
//
//        return str_starts_with($realPath, $allowedPath);
//    }
//
//    /**
//     * Get MIME type of file
//     */
//    private function getMimeType(string $fullPath): string
//    {
//        $mimeType = mime_content_type($fullPath);
//
//        // Fallback for common image types
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
//    /**
//     * Check if file is an image
//     */
//    private function isImage(string $mimeType): bool
//    {
//        return str_starts_with($mimeType, 'image/');
//    }
//
//    /**
//     * Serve optimized image with width/height parameters
//     */
//    private function serveOptimizedImage(string $fullPath, Request $request, string $mimeType)
//    {
//        try {
//            // Try different ways to load Intervention Image
//            $image = null;
//
//            if (class_exists(\Intervention\Image\ImageManager::class)) {
//                // Intervention Image v3
//                try {
//                    $manager = new \Intervention\Image\ImageManager(
//                        new \Intervention\Image\Drivers\Gd\Driver()
//                    );
//                    $image = $manager->read($fullPath);
//                } catch (\Exception $e) {
//                    // Try with Imagick driver
//                    try {
//                        $manager = new \Intervention\Image\ImageManager(
//                            new \Intervention\Image\Drivers\Imagick\Driver()
//                        );
//                        $image = $manager->read($fullPath);
//                    } catch (\Exception $e2) {
//                        \Log::warning('Could not create image with v3: ' . $e2->getMessage());
//                        return $this->serveFile($fullPath, $mimeType);
//                    }
//                }
//            } elseif (class_exists(\Intervention\Image\Facades\Image::class)) {
//                // Intervention Image v2
//                $image = \Intervention\Image\Facades\Image::make($fullPath);
//            }
//
//            if (!$image) {
//                return $this->serveFile($fullPath, $mimeType);
//            }
//
//            $width = $request->integer('w');
//            $height = $request->integer('h');
//            $quality = min(100, max(10, $request->integer('q', 85)));
//
//            if ($width || $height) {
//                if ($width && $height) {
//                    $image = $image->cover($width, $height);
//                } elseif ($width) {
//                    $image = $image->scaleDown(width: $width);
//                } else {
//                    $image = $image->scaleDown(height: $height);
//                }
//            }
//
//            $format = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
//            $encodedImage = $image->encode($format, $quality);
//
//            return response($encodedImage, 200, [
//                'Content-Type' => $mimeType,
//                'Cache-Control' => 'public, max-age=2592000', // 30 days for optimized images
//                'Expires' => gmdate('D, d M Y H:i:s \G\M\T', time() + 2592000),
//                'Content-Length' => strlen($encodedImage),
//            ]);
//
//        } catch (\Exception $e) {
//            \Log::warning('Image optimization failed: ' . $e->getMessage());
//            return $this->serveFile($fullPath, $mimeType);
//        }
//    }
//
//    /**
//     * Serve file with appropriate headers
//     */
//    private function serveFile(string $fullPath, string $mimeType)
//    {
//        $fileSize = filesize($fullPath);
//        $lastModified = filemtime($fullPath);
//
//        $headers = [
//            'Content-Type' => $mimeType,
//            'Content-Length' => $fileSize,
//            'Cache-Control' => 'public, max-age=31536000', // 1 year
//            'Expires' => gmdate('D, d M Y H:i:s \G\M\T', time + 31536000),
//            'Last-Modified' => gmdate('D, d M Y H:i:s \G\M\T', $lastModified),
//            'ETag' => '"' . md5($lastModified . $fileSize) . '"',
//        ];
//
//        return response()->file($fullPath, $headers);
//    }
//
//    /**
//     * Serve 404 or placeholder image
//     */
//    private function serveNotFound()
//    {
//        // Try to serve a placeholder image
//        $placeholderPaths = [
//            public_path('images/placeholder-product.jpg'),
//            public_path('images/placeholder.jpg'),
//            public_path('images/no-image.jpg'),
//        ];
//
//        foreach ($placeholderPaths as $placeholderPath) {
//            if (file_exists($placeholderPath)) {
//                return response()->file($placeholderPath, [
//                    'Content-Type' => 'image/jpeg',
//                    'Cache-Control' => 'public, max-age=86400', // 1 day
//                ]);
//            }
//        }
//
//        // Return a simple 1x1 transparent pixel as absolute fallback
//        $pixel = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
//
//        return response($pixel, 200, [
//            'Content-Type' => 'image/png',
//            'Cache-Control' => 'public, max-age=86400',
//        ]);
//    }
//}


namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;

class StorageController extends Controller
{
    public function serve(Request $request, $path)
    {
        // For shared hosting where Laravel is in a subfolder
        $path = urldecode($path);

        // Build the correct path - adjust this to match your setup
        $fullPath = base_path('storage/app/public/' . $path);

        // Alternative: if base_path doesn't work, use absolute path
        // $fullPath = '/home/shopwithcaug/Laravel/storage/app/public/' . $path;

        Log::info('Storage serve called', [
            'path' => $path,
            'full_path' => $fullPath,
            'exists' => file_exists($fullPath),
            'base_path' => base_path(),
            'storage_path' => storage_path('app/public'),
            'request_url' => $request->fullUrl()
        ]);

        try {
            // Security validation
            if (!$this->isPathAllowed($fullPath)) {
                \Log::warning('Path not allowed', ['path' => $fullPath]);
                return $this->serveNotFound();
            }

            // Check if file exists
            if (!file_exists($fullPath)) {
                \Log::warning('File not found', ['path' => $fullPath]);
                return $this->serveNotFound();
            }

            // Determine MIME type
            $mimeType = $this->getMimeType($fullPath);

            // Serve the file
            return $this->serveFile($fullPath, $mimeType);

        } catch (\Exception $e) {
            \Log::error('File serving error: ' . $e->getMessage(), [
                'path' => $path,
                'full_path' => $fullPath ?? null,
                'trace' => $e->getTraceAsString()
            ]);

            return $this->serveNotFound();
        }
    }

    private function isPathAllowed(string $fullPath): bool
    {
        $realPath = realpath($fullPath);
        // Update the allowed path to match your structure
        $allowedPath = realpath(base_path('storage/app/public'));

        // Alternative absolute path if base_path doesn't work
        // $allowedPath = realpath('/home/shopwithcaug/Laravel/storage/app/public');

        if (!$realPath || !$allowedPath) {
            return false;
        }

        if (str_contains($fullPath, '..') || str_contains($fullPath, '//')) {
            \Log::warning('Directory traversal attempt detected', ['path' => $fullPath]);
            return false;
        }

        return str_starts_with($realPath, $allowedPath);
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
        // Try to serve a placeholder from public_html/images
        $placeholderPaths = [
            $_SERVER['DOCUMENT_ROOT'] . '/images/placeholder-product.jpg',
            $_SERVER['DOCUMENT_ROOT'] . '/images/placeholder.jpg',
        ];

        foreach ($placeholderPaths as $placeholderPath) {
            if (file_exists($placeholderPath)) {
                return response()->file($placeholderPath, [
                    'Content-Type' => 'image/jpeg',
                    'Cache-Control' => 'public, max-age=86400',
                ]);
            }
        }

        // Return 1x1 transparent pixel
        $pixel = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');

        return response($pixel, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
