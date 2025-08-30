<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'subtotal',
        'tax_amount',
        'shipping_amount',
        'discount_amount',
        'total_amount',
        'currency',
        'shipping_address',
        'billing_address',
        'notes',
        'payment_method',
        'payment_status',
        'tracking_number',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'shipping_address' => 'array',
        'billing_address' => 'array',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (!$order->order_number) {
                $order->order_number = 'ORD-' . strtoupper(uniqid());
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeRecentFirst(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'processing' => 'blue',
            'shipped' => 'purple',
            'delivered' => 'green',
            'cancelled' => 'red',
            'refunded' => 'gray',
            default => 'gray',
        };
    }

    public function getFormattedTotalAttribute(): string
    {
        if (function_exists('money_format_ugx')) {
            return money_format_ugx((float) $this->total_amount);
        }
        return 'UGX ' . number_format((float) $this->total_amount, 0);
    }

    public function getTotalItemsAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    protected function shippingAddress(): Attribute
    {
        return Attribute::get(function ($value) {
            $data = $value;
            if (is_string($data)) {
                $decoded = json_decode($data, true);
                $data = is_array($decoded) ? $decoded : [];
            }
            if (!is_array($data)) {
                $data = [];
            }
            if (empty(array_filter($data, fn($v) => !is_null($v) && $v !== ''))) {
                return null;
            }
            return (object) $this->normalizeAddressArray($data);
        });
    }

    protected function billingAddress(): Attribute
    {
        return Attribute::get(function ($value) {
            $data = $value;
            if (is_string($data)) {
                $decoded = json_decode($data, true);
                $data = is_array($decoded) ? $decoded : [];
            }
            if (!is_array($data)) {
                $data = [];
            }
            if (empty(array_filter($data, fn($v) => !is_null($v) && $v !== ''))) {
                return null;
            }
            return (object) $this->normalizeAddressArray($data);
        });
    }

    private function normalizeAddressArray(array $data): array
    {
        // Map stored keys to the ones used in views
        $name = $data['name'] ?? null;
        $first = $data['first_name'] ?? null;
        $last = $data['last_name'] ?? null;

        if (!$first && $name) {
            $parts = preg_split('/\s+/', trim($name));
            $first = $parts[0] ?? null;
            $last = isset($parts[1]) ? implode(' ', array_slice($parts, 1)) : null;
        }

        return [
            'first_name' => $first,
            'last_name' => $last,
            'name' => $name ?? trim(((string)$first . ' ' . (string)$last)) ?: null,
            'company' => $data['company'] ?? null,
            'address_line_1' => $data['address_line_1'] ?? null,
            'address_line_2' => $data['address_line_2'] ?? null,
            'city' => $data['city'] ?? null,
            'state' => $data['state'] ?? null,
            'postal_code' => $data['postal_code'] ?? null,
            'country' => $data['country'] ?? null,
            'phone' => $data['phone'] ?? null,
        ];
    }
}
