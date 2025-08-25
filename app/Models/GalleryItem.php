<?php

    namespace App\Models;

    use App\Traits\HasStorageImages;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Builder;
    use Spatie\MediaLibrary\HasMedia;
    use Spatie\MediaLibrary\InteractsWithMedia;
    use Spatie\MediaLibrary\MediaCollections\Models\Media;

    class GalleryItem extends Model implements HasMedia
    {
        use HasFactory, InteractsWithMedia, HasStorageImages {
            // Resolve the getFirstMediaUrl collision by using our trait's version
            HasStorageImages::getFirstMediaUrl insteadof InteractsWithMedia;
            // But keep Spatie's original available as an alias
            InteractsWithMedia::getFirstMediaUrl as getSpatieMediaUrl;
        }

        protected $fillable = [
            'image',
            'caption',
            'hashtags',
            'link',
            'product_id',
            'is_featured',
            'is_active',
            'sort_order',
            'source_type', // 'upload', 'instagram', 'customer'
        ];

        protected $casts = [
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'hashtags' => 'array',
        ];

        // Relationships
        public function product()
        {
            return $this->belongsTo(Product::class);
        }

        // Scopes
        public function scopeActive(Builder $query): Builder
        {
            return $query->where('is_active', true);
        }

        public function scopeFeatured(Builder $query): Builder
        {
            return $query->where('is_featured', true);
        }

        public function scopeOrdered(Builder $query): Builder
        {
            return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
        }

        // Accessors
//        public function getImageUrlAttribute(): string
//        {
//            // If it's already a full URL (external image/Instagram)
//            if (filter_var($this->image, FILTER_VALIDATE_URL)) {
//                return $this->image;
//            }
//
//            // If it's a local storage path
//            if ($this->image && file_exists(public_path('storage/' . $this->image))) {
//                return asset('storage/' . $this->image);
//            }
//
//            // Fallback to a default image
//            return asset('images/gallery/default-gallery.jpg');
//        }

        public function getHashtagsStringAttribute(): string
        {
            if (is_array($this->hashtags)) {
                return implode(' ', array_map(fn($tag) => '#' . $tag, $this->hashtags));
            }
            return $this->hashtags ?? '';
        }

        /**
         * Get gallery image URL
         */
        public function getImageUrlAttribute(): string
        {
            if ($this->image) {
                return $this->getStorageImageUrl($this->image);
            }

            return $this->getFirstMediaUrl('gallery');
        }

        /**
         * Get gallery thumbnail URL
         */
        public function getThumbnailUrlAttribute(): string
        {
            if ($this->image) {
                return $this->getStorageImageUrl($this->image, 400, 400);
            }

            return $this->getMediaStorageUrl('gallery', 'thumb');
        }

        public function registerMediaCollections(): void
        {
            $this->addMediaCollection('gallery')
                ->useDisk('media')
                ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif']);
        }

        public function registerMediaConversions(?Media $media = null): void
        {
            $this->addMediaConversion('thumb')
                ->width(300)
                ->height(300)
                ->sharpen(10);

            $this->addMediaConversion('medium')
                ->width(600)
                ->height(600)
                ->sharpen(10);
        }
    }
