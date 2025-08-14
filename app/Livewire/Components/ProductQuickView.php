<?php

namespace App\Livewire\Components;

use App\Models\Product;
use Livewire\Attributes\On;
use Livewire\Component;

class ProductQuickView extends Component
{
    public ?Product $product = null;
    public bool $showModal = false;
    public int $quantity = 1;
    public array $selectedVariants = [];

    #[On('product:quickView')]
    public function showQuickView($productId = null): void
    {
        // Support both primitive ID and object payloads { productId: X } or { id: X }
        if (is_array($productId)) {
            $productId = $productId['productId'] ?? $productId['id'] ?? null;
        }
        if (!$productId) return;

        $this->product = Product::with(['categories', 'media'])->find($productId);
        if (!$this->product) return;

        $this->showModal = true;
        $this->quantity = 1;
        $this->selectedVariants = [];
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->product = null;
        $this->reset(['quantity', 'selectedVariants']);
    }

    public function incrementQuantity()
    {
        $this->quantity++;
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart()
    {
        if (!$this->product) return;

        // Emit event for cart addition (use positional args for compatibility)
        $this->dispatch('cart:add', $this->product->id, $this->quantity, $this->selectedVariants);

        // Show success message
        $this->dispatch('notify', [
            'message' => $this->product->name . ' added to cart!',
            'type' => 'success'
        ]);

        $this->closeModal();
    }

    public function toggleWishlist()
    {
        if (!$this->product) return;

        $this->dispatch('wishlist:toggle', ['id' => $this->product->id]);
    }

    public function toggleCompare()
    {
        if (!$this->product) return;

        $this->dispatch('compare:toggle', ['id' => $this->product->id]);
    }

    public function getImageUrlProperty()
    {
        if (!$this->product) return asset('images/placeholder-product.jpg');

        $media = $this->product->media->first();
        $img = method_exists($media, 'getUrl') ? $media->getUrl() : ($media->url ?? null);
        return $img ?: asset('images/placeholder-product.jpg');
    }

    public function getFormattedPriceProperty()
    {
        if (!$this->product) return '';

        $price = $this->product->sale_price ?: $this->product->price;
        // Handle both cents and decimal storage
        $formatted = (is_int($price) || ctype_digit((string)$price))
            ? number_format($price / 100, 2)
            : number_format((float)$price, 2);

        return config('app.currency_symbol', '$') . $formatted;
    }

    public function getOriginalPriceProperty()
    {
        if (!$this->product || !$this->product->sale_price) return '';

        $price = $this->product->price;
        $formatted = (is_int($price) || ctype_digit((string)$price))
            ? number_format($price / 100, 2)
            : number_format((float)$price, 2);

        return config('app.currency_symbol', '$') . $formatted;
    }

    public function render()
    {
        return view('livewire.components.product-quick-view');
    }
}
