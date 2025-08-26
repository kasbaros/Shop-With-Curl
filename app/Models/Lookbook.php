<?php

    namespace App\Models;

    use App\Traits\HasStorageImages;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Lookbook extends Model
    {
        use HasFactory, HasStorageImages;

        protected $fillable = [
            'title',
            'label',
            'image',
            'active',
            'priority',
            'starts_at',
            'ends_at',
        ];

        protected $casts = [
            'active' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];

        public function items()
        {
            return $this->hasMany(LookbookItem::class)->orderBy('sort_order');
        }

        public function scopeActive(Builder $q): Builder
        {
            return $q->where('active', true);
        }

        public function scopeCurrent(Builder $q): Builder
        {
            return $q->where(function ($w) {
                $w->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })->where(function ($w) {
                $w->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            });
        }

        /**
         * Get lookbook image URL
         */
        public function getImageUrlAttribute(): string
        {
            return $this->image
                ? $this->getStorageImageUrl($this->image)
                : $this->getPlaceholderImage();
        }

        /**
         * Get lookbook thumbnail URL
         */
        public function getThumbnailUrlAttribute(): string
        {
            if ($this->image) {
                return $this->getStorageImageUrl($this->image, 400, 400);
            }

            return $this->getPlaceholderImage();
        }
    }
