<?php

namespace App\Models;

use App\Helpers\ImageStorageHelper;
use App\Traits\HasStorageImages;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
//use Laravel\Scout\Searchable;

class Product extends Model implements HasMedia
{
    use HasFactory, HasSlug, InteractsWithMedia, HasStorageImages {
        // Resolve the getFirstMediaUrl collision by using our trait's version
        HasStorageImages::getFirstMediaUrl insteadof InteractsWithMedia;
        // But keep Spatie's original available as an alias
        InteractsWithMedia::getFirstMediaUrl as getSpatieMediaUrl;
    }

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'sale_price',
        'sku',
        'stock_quantity',
        'min_stock_level',
        'manage_stock',
        'is_active',
        'is_featured',
        'status',
        'weight',
        'dimensions',
        'meta_title',
        'meta_description',
        'gallery',
        'published_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'manage_stock' => 'boolean',
        'gallery' => 'array',
        'published_at' => 'datetime',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the brand that the product belongs to.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the colors available for this product.
     */
    public function colors(): BelongsToMany
    {
        return $this->belongsToMany(Color::class, 'product_colors')
                    ->withPivot(['stock_quantity', 'additional_price', 'sku_suffix'])
                    ->withTimestamps();
    }

    /**
     * Get the sizes available for this product.
     */
    public function sizes(): BelongsToMany
    {
        return $this->belongsToMany(Size::class, 'product_sizes')
                    ->withPivot(['stock_quantity', 'additional_price', 'sku_suffix'])
                    ->withTimestamps();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->useDisk('media')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);

        $this->addMediaCollection('gallery')
            ->useDisk('media')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
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

        $this->addMediaConversion('large')
            ->width(1200)
            ->height(1200)
            ->sharpen(10);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', 'inactive');
    }

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function inventoryAlerts(): HasMany
    {
        return $this->hasMany(InventoryAlert::class);
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


    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function scopeOnSale(Builder $query): Builder
    {
        return $query->whereNotNull('sale_price')
            ->whereColumn('sale_price', '<', 'price');
    }

    // Accessors
    public function getEffectivePriceAttribute(): float
    {
        return $this->sale_price ?? $this->price;
    }

    public function getDiscountPercentageAttribute(): ?float
    {
        if (!$this->sale_price) {
            return null;
        }

        return round((($this->price - $this->sale_price) / $this->price) * 100, 2);
    }

    public function getIsInStockAttribute(): bool
    {
        if (!$this->manage_stock) {
            return true;
        }

        return $this->stock_quantity > 0;
    }

    public function getIsLowStockAttribute(): bool
    {
        if (!$this->manage_stock) {
            return false;
        }

        return $this->stock_quantity <= $this->min_stock_level;
    }

    public function getAverageRatingAttribute(): float
    {
        return $this->reviews()->approved()->avg('rating') ?? 0;
    }

    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->approved()->count();
    }

    public function getFeaturedImageAttribute(): ?string
    {
        return $this->getFirstMediaUrl('images');
    }

//    public function getGalleryImagesAttribute(): array
//    {
//        return $this->getMedia('gallery')->map(function ($media) {
//            return [
//                'id' => $media->id,
//                'url' => $media->getUrl(),
//                'thumb' => $media->getUrl('thumb'),
//                'medium' => $media->getUrl('medium'),
//                'large' => $media->getUrl('large'),
//            ];
//        })->toArray();
//    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'published' => '<span class="badge bg-success">Published</span>',
            'draft' => '<span class="badge bg-warning">Draft</span>',
            'inactive' => '<span class="badge bg-danger">Inactive</span>',
            default => '<span class="badge bg-secondary">Unknown</span>'
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'published' => 'success',
            'draft' => 'warning',
            'inactive' => 'danger',
            default => 'secondary'
        };
    }

    // Scout search - disabled
    /*
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'sku' => $this->sku,
            'price' => $this->price,
            'sale_price' => $this->sale_price,
            'categories' => $this->categories->pluck('name')->toArray(),
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'stock_quantity' => $this->stock_quantity,
        ];
    }

    public function shouldBeSearchable(): bool
    {
        return $this->is_active && $this->published_at && $this->published_at->isPast();
    }
    */

    /**
     * Get the previous product based on ID
     *
     * @return \App\Models\Product|null
     */
    public function previous()
    {
        return static::where('id', '<', $this->id)
            ->where('status', 'published')
            ->where('is_active', true)
            ->orderBy('id', 'desc')
            ->first();
    }

    /**
     * Get the next product based on ID
     *
     * @return \App\Models\Product|null
     */
    public function next()
    {
        return static::where('id', '>', $this->id)
            ->where('status', 'published')
            ->where('is_active', true)
            ->orderBy('id', 'asc')
            ->first();
    }


    /**
     * Get product images array
     */
    public function getImagesAttribute(): array
    {
        // First check if we have a gallery array (your new storage system)
        if (is_array($this->gallery) && count($this->gallery) > 0) {
            return array_map(function ($path, $index) {
                return [
                    'thumb' => $this->getStorageImageUrl($path, 400, 400),
                    'large' => $this->getStorageImageUrl($path, 1200, 1200),
                    'original' => $this->getStorageImageUrl($path),
                ];
            }, $this->gallery, array_keys($this->gallery));
        }

        // Fallback to Spatie Media Library
        $mediaImages = $this->getMedia('images');
        if ($mediaImages->count() > 0) {
            return $mediaImages->map(function ($media, $index) {
                // Try to get the actual URL from Spatie first
                $originalUrl = $media->getUrl();

                // If Spatie URL doesn't work, try to find the file in your storage
                if (!$this->urlExists($originalUrl)) {
                    $filename = $media->file_name;
                    $storagePath = 'products/' . $filename;
                    if (ImageStorageHelper::exists($storagePath)) {
                        $originalUrl = ImageStorageHelper::url($storagePath);
                    }
                }

                return [
                    'thumb' => $originalUrl, // You can add thumbnail generation later
                    'large' => $originalUrl,
                    'original' => $originalUrl,
                ];
            })->toArray();
        }

        // Fallback to featured_image field
        if ($this->featured_image) {
            return [
                [
                    'thumb' => $this->getStorageImageUrl($this->featured_image, 400, 400),
                    'large' => $this->getStorageImageUrl($this->featured_image, 1200, 1200),
                    'original' => $this->getStorageImageUrl($this->featured_image),
                ]
            ];
        }

        // Default placeholder
        return [
            [
                'thumb' => $this->getPlaceholderImage(),
                'large' => $this->getPlaceholderImage(),
                'original' => $this->getPlaceholderImage(),
            ]
        ];
    }


    /**
     * Check if URL exists (helper method)
     */
    private function urlExists($url): bool
    {
        if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        // For local URLs, check file existence
        if (str_starts_with($url, asset(''))) {
            $relativePath = str_replace(asset(''), '', $url);
            $fullPath = public_path($relativePath);
            return file_exists($fullPath);
        }

        return true; // For external URLs, assume they exist
    }


    /**
     * Get featured image URL
     */
    public function getFeaturedImageUrlAttribute(): string
    {
        // Use featured_image field first (your new storage system)
        if ($this->featured_image) {
            return $this->getStorageImageUrl($this->featured_image);
        }

        // Fallback to first gallery image
        $images = $this->getImagesAttribute();
        if (!empty($images[0]['original'])) {
            return $images[0]['original'];
        }

        return $this->getPlaceholderImage();
    }


    /**
     * Get thumbnail URL
     */
    public function getThumbnailUrlAttribute(): string
    {
        // First try gallery images
        if (is_array($this->gallery) && count($this->gallery) > 0) {
            return $this->getStorageImageUrl($this->gallery[0], 400, 400);
        }

        // Then try featured_image field
        if ($this->featured_image) {
            return $this->getStorageImageUrl($this->featured_image, 400, 400);
        }

        // Finally try Spatie media library
        if (method_exists($this, 'getFirstMediaUrl')) {
            $mediaUrl = $this->getFirstMediaUrl('images', 'thumb');
            if ($mediaUrl) {
                return $mediaUrl;
            }
        }

        return $this->getPlaceholderImage();
    }

    /**
     * Primary image URL (prefers featured_image, else first gallery image, else placeholder)
     */
    public function getPrimaryImageUrlAttribute(): string
    {
        return $this->getFeaturedImageUrlAttribute();
    }

    /**
     * Secondary/hover image URL (second gallery image if present, else primary)
     */
    public function getHoverImageUrlAttribute(): string
    {
        $images = $this->getImagesAttribute();

        // Use second image if available
        if (isset($images[1]['original'])) {
            return $images[1]['original'];
        }

        // Fallback to primary image
        return $this->getPrimaryImageUrlAttribute();
    }

    /**
     * Backward-compat for places that still call getFirstMediaUrl('images', 'large')
     */
    public function getFirstMediaUrl(string $collectionName = 'images', string $conversionName = ''): string
    {
        $images = $this->getImagesAttribute();

        if (empty($images)) {
            return $this->getPlaceholderImage();
        }

        // Return appropriate image based on conversion
        switch ($conversionName) {
            case 'thumb':
                return $images[0]['thumb'] ?? $this->getPlaceholderImage();
            case 'large':
                return $images[0]['large'] ?? $this->getPlaceholderImage();
            default:
                return $images[0]['original'] ?? $this->getPlaceholderImage();
        }
    }

    /**
     * Override the gallery images accessor to use the new system
     */
    public function getGalleryImagesAttribute(): array
    {
        return $this->getImagesAttribute();
    }

}
