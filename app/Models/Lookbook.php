<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Builder;

    class Lookbook extends Model
    {
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
    }
