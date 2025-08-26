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
                'user' => 'images/placeholder-user.jpg',
                'default' => 'images/placeholder.jpg',
            ];

            $placeholder = $placeholders[$modelName] ?? $placeholders['default'];
            return asset($placeholder);
        }
    }
