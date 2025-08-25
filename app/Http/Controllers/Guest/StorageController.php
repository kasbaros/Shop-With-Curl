<?php

    namespace App\Http\Controllers\Guest;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use Illuminate\Support\Facades\Storage;
    use Symfony\Component\HttpFoundation\StreamedResponse;

    class StorageController extends Controller
    {
        /**
         * Serve files from storage/app/public dynamically
         */
        public function serve(Request $request, $path)
        {
            try {
                // Decode the path to handle special characters
                $path = urldecode($path);

                // Build the full path
                $fullPath = storage_path('app/public/' . $path);

                // Security validation
                if (!$this->isPathAllowed($fullPath)) {
                    return $this->serveNotFound();
                }

                // Check if file exists
                if (!file_exists($fullPath)) {
                    return $this->serveNotFound();
                }

                // Determine MIME type
                $mimeType = $this->getMimeType($fullPath);

                // Handle image optimization for different sizes
                if ($this->isImage($mimeType) && $request->has('w') || $request->has('h')) {
                    return $this->serveOptimizedImage($fullPath, $request, $mimeType);
                }

                // Serve the file with appropriate headers
                return $this->serveFile($fullPath, $mimeType);

            } catch (\Exception $e) {
                \Log::error('File serving error: ' . $e->getMessage(), [
                    'path' => $path,
                    'full_path' => $fullPath ?? null
                ]);

                return $this->serveNotFound();
            }
        }

        /**
         * Security check: ensure path is within allowed directory
         */
        private function isPathAllowed(string $fullPath): bool
        {
            $realPath = realpath($fullPath);
            $allowedPath = realpath(storage_path('app/public'));

            if (!$realPath || !$allowedPath) {
                return false;
            }

            return str_starts_with($realPath, $allowedPath);
        }

        /**
         * Get MIME type of file
         */
        private function getMimeType(string $fullPath): string
        {
            $mimeType = mime_content_type($fullPath);

            // Fallback for common image types
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

        /**
         * Check if file is an image
         */
        private function isImage(string $mimeType): bool
        {
            return str_starts_with($mimeType, 'image/');
        }

        /**
         * Serve optimized image with width/height parameters
         */
        private function serveOptimizedImage(string $fullPath, Request $request, string $mimeType)
        {
            // This requires Intervention/Image package
            if (!class_exists(\Intervention\Image\Facades\Image::class)) {
                return $this->serveFile($fullPath, $mimeType);
            }

            try {
                $image = \Intervention\Image\Facades\Image::make($fullPath);

                $width = $request->integer('w');
                $height = $request->integer('h');
                $quality = min(100, max(10, $request->integer('q', 85)));

                if ($width || $height) {
                    if ($width && $height) {
                        $image->fit($width, $height);
                    } elseif ($width) {
                        $image->widen($width);
                    } else {
                        $image->heighten($height);
                    }
                }

                $format = pathinfo($fullPath, PATHINFO_EXTENSION);
                $encodedImage = $image->encode($format, $quality);

                return response($encodedImage, 200, [
                    'Content-Type' => $mimeType,
                    'Cache-Control' => 'public, max-age=2592000', // 30 days for optimized images
                    'Expires' => gmdate('D, d M Y H:i:s \G\M\T', time() + 2592000),
                    'Content-Length' => strlen($encodedImage),
                ]);

            } catch (\Exception $e) {
                \Log::warning('Image optimization failed: ' . $e->getMessage());
                return $this->serveFile($fullPath, $mimeType);
            }
        }

        /**
         * Serve file with appropriate headers
         */
        private function serveFile(string $fullPath, string $mimeType)
        {
            $fileSize = filesize($fullPath);
            $lastModified = filemtime($fullPath);

            $headers = [
                'Content-Type' => $mimeType,
                'Content-Length' => $fileSize,
                'Cache-Control' => 'public, max-age=31536000', // 1 year
                'Expires' => gmdate('D, d M Y H:i:s \G\M\T', time() + 31536000),
                'Last-Modified' => gmdate('D, d M Y H:i:s \G\M\T', $lastModified),
                'ETag' => '"' . md5($lastModified . $fileSize) . '"',
            ];

            return response()->file($fullPath, $headers);
        }

        /**
         * Serve 404 or placeholder image
         */
        private function serveNotFound()
        {
            // Try to serve a placeholder image
            $placeholderPaths = [
                public_path('images/placeholder-product.jpg'),
                public_path('images/placeholder.jpg'),
                public_path('images/no-image.jpg'),
            ];

            foreach ($placeholderPaths as $placeholderPath) {
                if (file_exists($placeholderPath)) {
                    return response()->file($placeholderPath, [
                        'Content-Type' => 'image/jpeg',
                        'Cache-Control' => 'public, max-age=86400', // 1 day
                    ]);
                }
            }

            // Return a simple 1x1 transparent pixel as absolute fallback
            $pixel = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');

            return response($pixel, 200, [
                'Content-Type' => 'image/png',
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }
    }
