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
            if (!method_exists($this, 'getMedia')) {
                return $this->getPlaceholderImage();
            }

            $media = $this->getMedia($collection)->get($index);

            if (!$media) {
                return $this->getPlaceholderImage();
            }

            // For Spatie media, we'll store in media subfolder
            $path = 'media/' . $media->id . '/' . $media->file_name;

            if ($conversion === 'thumb') {
                return ImageStorageHelper::url($path);
            } elseif ($conversion === 'large') {
                return ImageStorageHelper::url($path);
            }

            return ImageStorageHelper::url($path);
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
