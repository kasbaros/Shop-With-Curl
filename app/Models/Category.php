<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Relations\BelongsToMany;
    use Spatie\Sluggable\HasSlug;
    use Spatie\Sluggable\SlugOptions;
    use Spatie\MediaLibrary\HasMedia;
    use Spatie\MediaLibrary\InteractsWithMedia;
    use Spatie\MediaLibrary\MediaCollections\Models\Media;

    class Category extends Model implements HasMedia
    {
        use HasFactory, HasSlug, InteractsWithMedia;

        protected $fillable = [
            'name',
            'slug',
            'description',
            'image',
            'parent_id',
            'sort_order',
            'is_active',
            'meta_title',
            'meta_description',
        ];

        protected $casts = [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
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

        public function registerMediaCollections(): void
        {
            $this->addMediaCollection('images')
                ->useDisk('media')
                ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                ->singleFile();
        }

        public function registerMediaConversions(?Media $media = null): void
        {
            $this->addMediaConversion('thumb')
                ->width(300)
                ->height(300)
                ->sharpen(10);

            $this->addMediaConversion('banner')
                ->width(1200)
                ->height(400)
                ->sharpen(10);
        }

        public function parent(): BelongsTo
        {
            return $this->belongsTo(Category::class, 'parent_id');
        }

        public function children(): HasMany
        {
            return $this->hasMany(Category::class, 'parent_id');
        }

        public function products(): BelongsToMany
        {
            return $this->belongsToMany(Product::class, 'product_categories')
                ->where('products.is_active', true)
                ->where('products.status', 'published');
        }

        public function sizeCharts(): HasMany
        {
            return $this->hasMany(SizeChart::class);
        }

        public function scopeActive($query)
        {
            return $query->where('is_active', true);
        }

        public function scopeParent($query)
        {
            return $query->whereNull('parent_id');
        }

        public function scopeChild($query)
        {
            return $query->whereNotNull('parent_id');
        }

        public function getImageUrlAttribute(): ?string
        {
            // Prefer a generated thumb if available; otherwise, fall back to the original
            $media = $this->getFirstMedia('images');
            if ($media) {
                return $media->hasGeneratedConversion('thumb') ? $media->getUrl('thumb') : $media->getUrl();
            }
            return null;
        }

        public function getBreadcrumbsAttribute(): array
        {
            $breadcrumbs = [];
            $category = $this;

            while ($category) {
                array_unshift($breadcrumbs, [
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'url' => route('categories.show', $category->slug)
                ]);
                $category = $category->parent;
            }

            return $breadcrumbs;
        }

        // Add a custom method to get products count
        public function getProductsCountAttribute(): int
        {
            return $this->products()->count();
        }


        /**
         * Get all brands that have products in this category.
         */
        public function getBrandsAttribute()
        {
            return Brand::whereHas('products', function ($query) {
                $query->whereHas('categories', function ($subQuery) {
                    $subQuery->where('categories.id', $this->id);
                });
            })->get();
        }

        /**
         * Get all colors available in products of this category.
         */
        public function getColorsAttribute()
        {
            return Color::whereHas('products', function ($query) {
                $query->whereHas('categories', function ($subQuery) {
                    $subQuery->where('categories.id', $this->id);
                });
            })->get();
        }

        /**
         * Get all sizes available in products of this category.
         */
        public function getSizesAttribute()
        {
            return Size::whereHas('products', function ($query) {
                $query->whereHas('categories', function ($subQuery) {
                    $subQuery->where('categories.id', $this->id);
                });
            })->get();
        }
    }
