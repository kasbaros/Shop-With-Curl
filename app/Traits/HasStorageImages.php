<?php

    namespace App\Traits;

    use App\Helpers\ImageStorageHelper;
    use Illuminate\Http\UploadedFile;

    trait HasStorageImages
    {
        /**
         * Get image URL using the unified storage system
         */
        public function getStorageImageUrl(?string $path, ?int $width = null, ?int $height = null): string
        {
            if (!$path) {
                return $this->getPlaceholderImage();
            }

            return ImageStorageHelper::url($path);
        }

        /**
         * Override Spatie's getFirstMediaUrl to use our storage system
         */
        public function getFirstMediaUrl(string $collectionName = 'default', string $conversionName = ''): string
        {
            return $this->getMediaStorageUrl($collectionName, $conversionName);
        }

        /**
         * Get Media URL for Spatie Media Library (if used)
         */
        public function getMediaStorageUrl(string $collection = 'images', string $conversion = '', int $index = 0): string
        {
            // For products, use the new gallery system first
            if (method_exists($this, 'getImagesAttribute') && $collection === 'images') {
                $images = $this->getImagesAttribute();
                if (isset($images[$index])) {
                    switch ($conversion) {
                        case 'thumb':
                            return $images[$index]['thumb'] ?? $this->getPlaceholderImage();
                        case 'large':
                            return $images[$index]['large'] ?? $this->getPlaceholderImage();
                        default:
                            return $images[$index]['original'] ?? $this->getPlaceholderImage();
                    }
                }
            }

            // Fallback to Spatie Media Library
            if (!method_exists($this, 'getMedia')) {
                return $this->getPlaceholderImage();
            }

            $media = $this->getMedia($collection)->get($index);

            if (!$media) {
                return $this->getPlaceholderImage();
            }

            // Try Spatie's URL first
            try {
                $spatieUrl = $media->getUrl($conversion);
                if ($spatieUrl && $this->urlIsAccessible($spatieUrl)) {
                    return $spatieUrl;
                }
            } catch (\Exception $e) {
                // Spatie URL failed, continue to fallbacks
            }

            // Fallback: try to find file in your storage system
            $modelFolder = strtolower(class_basename($this)) . 's';
            $relativePath = $modelFolder . '/' . $media->file_name;

            if (ImageStorageHelper::exists($relativePath)) {
                return ImageStorageHelper::url($relativePath);
            }

            // Final fallback: try media directory structure
            $mediaPath = 'media/' . $media->id . '/' . $media->file_name;
            if (ImageStorageHelper::exists($mediaPath)) {
                return ImageStorageHelper::url($mediaPath);
            }

            return $this->getPlaceholderImage();
        }

        /**
         * Check if URL is accessible
         */
        private function urlIsAccessible(string $url): bool
        {
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                return false;
            }

            // For local URLs, check file existence
            if (str_starts_with($url, asset('storage/'))) {
                $relativePath = str_replace(asset('storage/'), '', $url);
                return ImageStorageHelper::exists($relativePath);
            }

            return true; // For external URLs, assume they exist
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
                $path = 'media/' . $media->id . '/' . $media->file_name;
                return ImageStorageHelper::url($path);
            })->toArray();
        }

        /**
         * Store an image for this model
         */
        public function storeImage(UploadedFile $file, string $attribute = 'image'): string
        {
            $directory = strtolower(class_basename($this)) . 's'; // products, categories, etc.

            // Delete old image if exists
            if ($this->{$attribute}) {
                ImageStorageHelper::delete($this->{$attribute});
            }

            // Store new image
            $path = ImageStorageHelper::store($file, $directory);

            // Update the model
            $this->update([$attribute => $path]);

            return $path;
        }

        /**
         * Delete the image for this model
         */
        public function deleteImage(string $attribute = 'image'): bool
        {
            if ($this->{$attribute}) {
                $deleted = ImageStorageHelper::delete($this->{$attribute});
                if ($deleted) {
                    $this->update([$attribute => null]);
                }
                return $deleted;
            }

            return false;
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
                'gallery' => 'images/placeholder-gallery.jpg',
                'galleryitem' => 'images/placeholder-gallery.jpg',
                'user' => 'images/placeholder-user.jpg',
                'banner' => 'images/placeholder-banner.jpg',
                'lookbook' => 'images/placeholder-lookbook.jpg',
                'promobanner' => 'images/placeholder-banner.jpg',
                'default' => 'images/placeholder.jpg',
            ];

            $placeholder = $placeholders[$modelName] ?? $placeholders['default'];
            return asset($placeholder);
        }
    }
