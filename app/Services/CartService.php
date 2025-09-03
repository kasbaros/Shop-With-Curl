<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartService
{
    private const CART_SESSION_KEY = 'shopping_cart';

    public function add(int $productId, int $quantity = 1, array $variants = []): bool
    {
        $product = Product::find($productId);

        if (!$product || !$product->is_active) {
            return false;
        }

        // Handle variant-based products
        $variant = null;
        $variantId = $variants['variant_id'] ?? null;

        if ($variantId) {
            $variant = ProductVariant::find($variantId);
            if (!$variant || !$variant->is_active || $variant->product_id !== $productId) {
                return false;
            }

            // Check variant stock
            if ($variant->stock_quantity < $quantity) {
                return false;
            }
        } else {
            // Check product stock for non-variant products
            if ($product->manage_stock && $product->stock_quantity < $quantity) {
                return false;
            }
        }

        $cart = $this->getCart();
        $itemKey = $this->generateItemKey($productId, $variants);

        // Determine price - use variant price if available, otherwise product price
        $price = $variant ? $variant->effective_price : ($product->sale_price ?: $product->price);
        $originalPrice = $variant ? $variant->price : $product->price;

        if (isset($cart[$itemKey])) {
            // Update existing item - check stock for new total quantity
            $newQuantity = $cart[$itemKey]['quantity'] + $quantity;

            if ($variant && $variant->stock_quantity < $newQuantity) {
                return false;
            } elseif (!$variant && $product->manage_stock && $product->stock_quantity < $newQuantity) {
                return false;
            }

            $cart[$itemKey]['quantity'] = $newQuantity;
        } else {
            // Add new item
            $cart[$itemKey] = [
                'product_id' => $productId,
                'variant_id' => $variantId,
                'product_name' => $product->name,
                'product_slug' => $product->slug,
                'product_image' => $this->getProductImage($product),
                'price' => $price,
                'original_price' => $originalPrice,
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

        $cartItem = $cart[$itemKey];
        $product = Product::find($cartItem['product_id']);

        if (!$product) {
            return false;
        }

        // Verify stock based on whether it's a variant or regular product
        if (isset($cartItem['variant_id']) && $cartItem['variant_id']) {
            $variant = ProductVariant::find($cartItem['variant_id']);
            if (!$variant || $variant->stock_quantity < $quantity) {
                return false;
            }
        } else {
            // Check product stock for non-variant products
            if ($product->manage_stock && $product->stock_quantity < $quantity) {
                return false;
            }
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
        return money_format_ugx($price);
    }
}
