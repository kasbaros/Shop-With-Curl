<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Builder;

    class GalleryItem extends Model
    {
        use HasFactory;

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
        public function getImageUrlAttribute(): string
        {
            // If it's already a full URL (external image/Instagram)
            if (filter_var($this->image, FILTER_VALIDATE_URL)) {
                return $this->image;
            }

            // If it's a local storage path
            if ($this->image && file_exists(public_path('storage/' . $this->image))) {
                return asset('storage/' . $this->image);
            }

            // Fallback to a default image
            return asset('images/gallery/default-gallery.jpg');
        }

        public function getHashtagsStringAttribute(): string
        {
            if (is_array($this->hashtags)) {
                return implode(' ', array_map(fn($tag) => '#' . $tag, $this->hashtags));
            }
            return $this->hashtags ?? '';
        }
    }
