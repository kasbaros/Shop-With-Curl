<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Size extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'value',
        'slug',
        'type',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($size) {
            if (empty($size->slug)) {
                $size->slug = Str::slug($size->name . '-' . $size->type);
            }
        });

        static::updating(function ($size) {
            if ($size->isDirty(['name', 'type']) && empty($size->slug)) {
                $size->slug = Str::slug($size->name . '-' . $size->type);
            }
        });
    }

    /**
     * Get the products that have this size.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_sizes')
                    ->withPivot(['stock_quantity', 'additional_price', 'sku_suffix'])
                    ->withTimestamps();
    }

    /**
     * Scope a query to only include active sizes.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Scope a query to filter by type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get all available size types.
     */
    public static function getTypes(): array
    {
        return [
            'general' => 'General',
            'clothing' => 'Clothing',
            'shoes' => 'Shoes',
            'accessories' => 'Accessories',
        ];
    }
}
