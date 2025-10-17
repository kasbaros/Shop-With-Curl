<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'size',
        'color',
        'material',
        'image',
        'sku',
        'price',
        'stock_quantity',
        'is_active',
        'attributes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'attributes' => 'array',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function inventoryAlerts(): HasMany
    {
        return $this->hasMany(InventoryAlert::class);
    }

    public function getEffectivePriceAttribute(): float
    {
        return $this->price ?? $this->product->effective_price;
    }

    public function getIsInStockAttribute(): bool
    {
        return $this->stock_quantity > 0;
    }

    public function getDisplayNameAttribute(): string
    {
        $parts = array_filter([$this->size, $this->color, $this->material]);
        return implode(' / ', $parts);
    }

    public function getImageUrlAttribute(): string
    {
        // If variant has its own image stored via ImageStorageHelper, resolve to public URL
        if (!empty($this->image)) {
            // Product model has a helper for storage-backed images; replicate simple logic here
            $path = $this->image;
            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                return $path;
            }
            // Stored under public disk by ImageStorageHelper (public_html/storage/...)
            return asset('storage/' . ltrim($path, '/'));
        }

        // Fallback to product's primary image
        return $this->product?->primary_image_url ?? asset('images/placeholder-product.jpg');
    }
}
