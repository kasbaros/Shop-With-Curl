<?php
//
//namespace App\Models;
//
//use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\Relations\BelongsTo;
//use Illuminate\Database\Eloquent\Relations\HasMany;
//
//class ProductVariant extends Model
//{
//    use HasFactory;
//
//    protected $fillable = [
//        'product_id',
//        'size',
//        'color',
//        'material',
//        'sku',
//        'price',
//        'stock_quantity',
//        'is_active',
//        'attributes',
//    ];
//
//    protected $casts = [
//        'price' => 'decimal:2',
//        'images' => 'array',
//        'is_active' => 'boolean',
//        'attributes' => 'array',
//    ];
//
//    public function product(): BelongsTo
//    {
//        return $this->belongsTo(Product::class);
//    }
//
//    public function orderItems(): HasMany
//    {
//        return $this->hasMany(OrderItem::class);
//    }
//
//    public function inventoryAlerts(): HasMany
//    {
//        return $this->hasMany(InventoryAlert::class);
//    }
//
//    public function getEffectivePriceAttribute(): float
//    {
//        return $this->price ?? $this->product->effective_price;
//    }
//
//    public function getIsInStockAttribute(): bool
//    {
//        return $this->stock_quantity > 0;
//    }
//
//    public function getDisplayNameAttribute(): string
//    {
//        $parts = array_filter([$this->size, $this->color, $this->material]);
//        return implode(' / ', $parts);
//    }
//}


namespace App\Models;

use App\Traits\HasStorageImages;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    use HasFactory, HasStorageImages;

    protected $fillable = [
        'product_id',
        'size',
        'color',
        'material',
        'sku',
        'price',
        'stock_quantity',
        'is_active',
        'attributes',
        'images',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'images' => 'array',
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

    public function getPrimaryImageUrlAttribute(): string
    {
        if (is_array($this->images) && !empty($this->images)) {
            return $this->getStorageImageUrl($this->images[0]);
        }
        // Fallback to product’s featured image or first gallery image
        return $this->product->primary_image_url;
    }

    public function getThumbnailUrlAttribute(): string
    {
        if (is_array($this->images) && !empty($this->images)) {
            return $this->getStorageImageUrl($this->images[0], 400, 400);
        }
        return $this->product->thumbnail_url;
    }
}
