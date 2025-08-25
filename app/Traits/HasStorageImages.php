<?php

    namespace App\Traits;

    use App\Helpers\StorageHelper;

    trait HasStorageImages
    {
        /**
         * Get image URL using the storage serving route
         */
        public function getStorageImageUrl(?string $path, ?int $width = null, ?int $height = null): string
        {
            if (!$path) {
                return $this->getPlaceholderImage();
            }

            // If already an absolute URL or data URI, return as-is
            if (preg_match('#^https?://#i', $path) || str_starts_with($path, 'data:image/')) {
                return $path;
            }

            // Normalize common prefixes so the storage route can resolve correctly
            $cleanPath = ltrim($path, '/');

            // Remove leading "storage/" if present (e.g. "storage/media/xyz.jpg" -> "media/xyz.jpg")
            if (str_starts_with($cleanPath, 'storage/')) {
                $cleanPath = substr($cleanPath, strlen('storage/'));
            }

            // Remove leading "public/" if present (when public disk paths were saved)
            if (str_starts_with($cleanPath, 'public/')) {
                $cleanPath = substr($cleanPath, strlen('public/'));
            }

            // Build served URL (optionally with resizing params)
            if ($width || $height) {
                return StorageHelper::imageUrl($cleanPath, $width, $height);
            }

            return StorageHelper::url($cleanPath);
        }

        /**
         * Get Spatie Media URL using the storage serving route
         */
//        public function getMediaStorageUrl(string $collection = 'images', string $conversion = '', int $index = 0): string
//        {
//            if (!method_exists($this, 'getMedia')) {
//                return $this->getPlaceholderImage();
//            }
//
//            $media = $this->getMedia($collection)->get($index);
//
//            if (!$media) {
//                return $this->getPlaceholderImage();
//            }
//
//            $path = $media->id . '/' . $media->file_name;
//
//            if ($conversion === 'thumb') {
//                return StorageHelper::imageUrl($path, 400, 400);
//            } elseif ($conversion === 'large') {
//                return StorageHelper::imageUrl($path, 1200, 1200);
//            } elseif ($conversion && $media->hasGeneratedConversion($conversion)) {
//                return StorageHelper::url($media->id . '/conversions/' . $media->file_name . '-' . $conversion . '.' . $media->extension);
//            }
//
//            return StorageHelper::url($path);
//        }


        public function getMediaStorageUrl(string $collection = 'images', string $conversion = '', int $index = 0): string
        {
            if (!method_exists($this, 'getMedia')) {
                return $this->getPlaceholderImage();
            }

            $media = $this->getMedia($collection)->get($index);

            if (!$media) {
                return $this->getPlaceholderImage();
            }

            // Ensure we include the media/ prefix so the StorageController can resolve it
            $basePath = 'media/' . $media->id . '/' . $media->file_name;

            if ($conversion === 'thumb') {
                return StorageHelper::imageUrl($basePath, 400, 400);
            } elseif ($conversion === 'large') {
                return StorageHelper::imageUrl($basePath, 1200, 1200);
            } elseif ($conversion && $media->hasGeneratedConversion($conversion)) {
                $convPath = 'media/' . $media->id . '/conversions/' . $media->file_name . '-' . $conversion . '.' . $media->extension;
                return StorageHelper::url($convPath);
            }

            return StorageHelper::url($basePath);
        }


        /**
         * Get all media URLs from a collection
         */
        public function getAllMediaStorageUrls(string $collection = 'images', ?string $conversion = null): array
        {
            if (!method_exists($this, 'getMedia')) {
                return [];
            }

            return $this->getMedia($collection)->map(function ($media) use ($conversion) {
                $path = $media->id . '/' . $media->file_name;

                if ($conversion === 'thumb') {
                    return StorageHelper::imageUrl($path, 400, 400);
                } elseif ($conversion === 'large') {
                    return StorageHelper::imageUrl($path, 1200, 1200);
                } elseif ($conversion && $media->hasGeneratedConversion($conversion)) {
                    return StorageHelper::url($media->id . '/conversions/' . $media->file_name . '-' . $conversion . '.' . $media->extension);
                }

                return StorageHelper::url($path);
            })->toArray();
        }

        /**
         * Get placeholder image for this model type
         */
        protected function getPlaceholderImage(): string
        {
            $modelName = strtolower(class_basename($this));

            $placeholders = [
                'product' => 'images/placeholder-product.jpg',
                'category' => 'images/placeholder-category.jpg',
                'banner' => 'images/placeholder-banner.jpg',
                'user' => 'images/placeholder-avatar.jpg',
                'brand' => 'images/placeholder-brand.jpg',
                'post' => 'images/placeholder-blog.jpg',
            ];

            $placeholder = $placeholders[$modelName] ?? 'images/placeholder.jpg';

            return asset($placeholder);
        }

        /**
         * Override Spatie's getFirstMediaUrl to use our storage route
         */
        public function getFirstMediaUrl(string $collectionName = 'default', string $conversionName = ''): string
        {
            return $this->getMediaStorageUrl($collectionName, $conversionName);
        }
    }
