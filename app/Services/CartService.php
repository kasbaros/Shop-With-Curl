<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartService
{
    private const CART_SESSION_KEY = 'shopping_cart';

    public function add(int $productId, int $quantity = 1, array $variants = []): bool
    {
        $product = Product::find($productId);

        if (!$product || !$product->is_active || $product->status !== 'published') {
            return false;
        }

        // Check stock
        if ($product->stock_quantity !== null && $product->stock_quantity < $quantity) {
            return false;
        }

        $cart = $this->getCart();
        $itemKey = $this->generateItemKey($productId, $variants);

        if (isset($cart[$itemKey])) {
            // Update existing item
            $cart[$itemKey]['quantity'] += $quantity;
        } else {
            // Add new item
            $cart[$itemKey] = [
                'product_id' => $productId,
                'product_name' => $product->name,
                'product_slug' => $product->slug,
                'product_image' => $this->getProductImage($product),
                'price' => $product->sale_price ?: $product->price,
                'original_price' => $product->price,
                'quantity' => $quantity,
                'variants' => $variants,
                'added_at' => now()->toISOString(),
            ];
        }

        Session::put(self::CART_SESSION_KEY, $cart);
        return true;
    }

    public function update(string $itemKey, int $quantity): bool
    {
        $cart = $this->getCart();

        if (!isset($cart[$itemKey])) {
            return false;
        }

        if ($quantity <= 0) {
            return $this->remove($itemKey);
        }

        // Verify stock
        $product = Product::find($cart[$itemKey]['product_id']);
        if ($product && $product->stock_quantity !== null && $product->stock_quantity < $quantity) {
            return false;
        }

        $cart[$itemKey]['quantity'] = $quantity;
        Session::put(self::CART_SESSION_KEY, $cart);
        return true;
    }

    public function remove(string $itemKey): bool
    {
        $cart = $this->getCart();

        if (!isset($cart[$itemKey])) {
            return false;
        }

        unset($cart[$itemKey]);
        Session::put(self::CART_SESSION_KEY, $cart);
        return true;
    }

    public function clear(): void
    {
        Session::forget(self::CART_SESSION_KEY);
    }

    public function getCart(): array
    {
        return Session::get(self::CART_SESSION_KEY, []);
    }

    public function getItems(): Collection
    {
        return collect($this->getCart());
    }

    public function getCount(): int
    {
        return $this->getItems()->sum('quantity');
    }

    public function getTotal(): float
    {
        return $this->getItems()->sum(function ($item) {
            $price = is_int($item['price']) || ctype_digit((string)$item['price'])
                ? $item['price'] / 100
                : (float)$item['price'];

            return $price * $item['quantity'];
        });
    }

    public function getSubtotal(): float
    {
        return $this->getTotal(); // Same as total for now, can add tax logic later
    }

    public function isEmpty(): bool
    {
        return $this->getItems()->isEmpty();
    }

    private function generateItemKey(int $productId, array $variants = []): string
    {
        $variantString = empty($variants) ? '' : '_' . md5(serialize($variants));
        return $productId . $variantString;
    }

    private function getProductImage(Product $product): ?string
    {
        $media = $product->media->first();
        if (!$media) return null;

        return method_exists($media, 'getUrl') ? $media->getUrl() : ($media->url ?? null);
    }

    public function formatPrice(float $price): string
    {
        return config('app.currency_symbol', '$') . number_format($price, 2);
    }
}
