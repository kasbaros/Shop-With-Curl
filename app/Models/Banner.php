<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Builder;

    class Banner extends Model
    {
        use HasFactory;

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

        // Get image URL with fallback
        public function getImageUrlAttribute(): string
        {
            if ($this->image && file_exists(public_path('storage/' . $this->image))) {
                return asset('storage/' . $this->image);
            }

            // Fallback to a default banner image
            return asset('images/banners/default-banner.jpg');
        }
    }
