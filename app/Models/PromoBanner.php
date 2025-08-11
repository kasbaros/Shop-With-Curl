<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Builder;

    class PromoBanner extends Model
    {
        protected $fillable = [
            'heading','subtitle','features','cta_text','cta_link',
            'price_badge','image_desktop','image_mobile','active','priority',
            'starts_at','ends_at'
        ];

        protected $casts = [
            'features' => 'array',
            'active' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];

        public function scopeActive(Builder $q): Builder { return $q->where('active', true); }
        public function scopeCurrent(Builder $q): Builder {
            return $q->where(function($w){
                $w->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })->where(function($w){
                $w->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            });
        }
    }
