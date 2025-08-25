<?php

    namespace App\Models;

    use App\Traits\HasStorageImages;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Builder;

    class Banner extends Model
    {
        use HasFactory, HasStorageImages;

        protected $fillable = [
            'title',
            'subtitle',
            'description',
            'image',
            'button_text',
            'button_link',
            'secondary_button_text',
            'secondary_button_link',
            'sort_order',
            'is_active',
        ];

        protected $casts = [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];

        // Scope for active banners
        public function scopeActive(Builder $query): Builder
        {
            return $query->where('is_active', true);
        }

        // Scope for ordered banners
        public function scopeOrdered(Builder $query): Builder
        {
            return $query->orderBy('sort_order')->orderBy('created_at');
        }

//        // Get image URL with fallback (use relative URL to avoid APP_URL host mismatches)
//        public function getImageUrlAttribute(): string
//        {
//            $path = ltrim((string) $this->image, '/');
//            // If stored path points within the public storage disk, return relative URL
//            if ($path !== '' && \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
//                return '/storage/' . $path;
//            }
//
//            // Fallback to a default banner image (also relative)
//            return '/images/banners/default-banner.jpg';
//        }

        /**
         * Get banner image URL
         */
        public function getImageUrlAttribute(): string
        {
            if ($this->image) {
                return $this->getStorageImageUrl($this->image);
            }

            return $this->getFirstMediaUrl('images');
        }

        /**
         * Get mobile banner image URL
         */
        public function getMobileImageUrlAttribute(): string
        {
            if ($this->image) {
                return $this->getStorageImageUrl($this->image, 768, 400);
            }

            return $this->getFirstMediaUrl('images');
        }
    }
