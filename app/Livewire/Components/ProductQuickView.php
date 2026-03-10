<?php

namespace App\Livewire\Components;

use App\Models\Product;
use App\Models\ProductVariant;
use Livewire\Attributes\On;
use Livewire\Component;

class ProductQuickView extends Component
{
    public ?Product $product = null;
    public bool $showModal = false;
    public int $quantity = 1;
    public ?string $selectedColor = null;
    public ?string $selectedSize = null;
    public ?string $selectedMaterial = null;
    public array $selectedVariants = [];
    public ?int $selectedVariantId = null;

    #[On('product:quickView')]
    public function showQuickView($productId = null): void
    {
        // Support both primitive ID and object payloads { productId: X } or { id: X }
        if (is_array($productId)) {
            $productId = $productId['productId'] ?? $productId['id'] ?? null;
        }
        if (!$productId) return;

        $this->product = Product::with(['categories', 'media', 'variants'])->find($productId);
        if (!$this->product) return;

        $this->showModal = true;
        $this->quantity = 1;
        $this->resetVariantSelections();
        $this->initializeVariantSelections();

        // Dispatch event to initialize Swiper after modal is shown
        $this->dispatch('product:quickViewReady');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->product = null;
        $this->reset(['quantity', 'selectedColor', 'selectedSize', 'selectedMaterial', 'selectedVariants', 'selectedVariantId']);
    }

    private function resetVariantSelections()
    {
        $this->selectedColor = null;
        $this->selectedSize = null;
        $this->selectedMaterial = null;
        $this->selectedVariantId = null;
        $this->selectedVariants = [];
    }

    private function initializeVariantSelections()
    {
        if (!$this->product->variants()->exists()) {
            return;
        }

        $variants = $this->product->variants()->where('is_active', true)->get();

        // Auto-select if only one option available for each attribute
        $sizes = $variants->whereNotNull('size')->pluck('size')->unique()->values();
        $colors = $variants->whereNotNull('color')->pluck('color')->unique()->values();
        $materials = $variants->whereNotNull('material')->pluck('material')->unique()->values();

        if ($sizes->count() === 1) {
            $this->selectedSize = $sizes->first();
        }
        if ($colors->count() === 1) {
            $this->selectedColor = $colors->first();
        }
        if ($materials->count() === 1) {
            $this->selectedMaterial = $materials->first();
        }

        $this->updateSelectedVariant();
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

    public function updatedSelectedColor()
    {
        $this->updateSelectedVariant();
    }

    public function updatedSelectedSize()
    {
        $this->updateSelectedVariant();
    }

    public function updatedSelectedMaterial()
    {
        $this->updateSelectedVariant();
    }

    private function updateSelectedVariant()
    {
        if (!$this->product || !$this->product->variants()->exists()) {
            return;
        }

        $variant = $this->product->variants()
            ->where('is_active', true)
            ->when($this->selectedSize, fn($q) => $q->where('size', $this->selectedSize))
            ->when($this->selectedColor, fn($q) => $q->where('color', $this->selectedColor))
            ->when($this->selectedMaterial, fn($q) => $q->where('material', $this->selectedMaterial))
            ->first();

        $this->selectedVariantId = $variant?->id;

        // Update selectedVariants array for compatibility
        $this->selectedVariants = array_filter([
            'size' => $this->selectedSize,
            'color' => $this->selectedColor,
            'material' => $this->selectedMaterial,
            'variant_id' => $this->selectedVariantId,
        ]);
    }

    public function addToCart()
    {
        if (!$this->product) return;

        // Validate variant selection if product has variants
        if ($this->product->variants()->exists() && !$this->selectedVariantId) {
            $this->dispatch('notify', [
                'message' => 'Please select product options.',
                'type' => 'error'
            ]);
            return;
        }

        // Check stock availability
        if ($this->selectedVariantId) {
            $variant = ProductVariant::find($this->selectedVariantId);
            if (!$variant || $variant->stock_quantity < $this->quantity) {
                $this->dispatch('notify', [
                    'message' => 'Not enough stock available.',
                    'type' => 'error'
                ]);
                return;
            }
        } elseif ($this->product->manage_stock && $this->product->stock_quantity < $this->quantity) {
            $this->dispatch('notify', [
                'message' => 'Not enough stock available.',
                'type' => 'error'
            ]);
            return;
        }

        // Actually add item to cart using CartService
        $cartService = app(\App\Services\CartService::class);
        $success = $cartService->add($this->product->id, $this->quantity, $this->selectedVariants);

        if ($success) {
            // Emit event for cart addition (use positional args for compatibility)
            $this->dispatch('cart:add', $this->product->id, $this->quantity, $this->selectedVariants);

            // Show success message
            $this->dispatch('notify', [
                'message' => $this->product->name . ' added to cart!',
                'type' => 'success'
            ]);

            $this->closeModal();
        } else {
            $this->dispatch('notify', [
                'message' => 'Failed to add item to cart.',
                'type' => 'error'
            ]);
        }
    }

    public function toggleWishlist()
    {
        if (!$this->product) return;

        $this->dispatch('wishlist:toggle', id: $this->product->id);
    }

    public function toggleCompare()
    {
        if (!$this->product) return;

        $this->dispatch('compare:toggle', id: $this->product->id);
    }

    public function getImageUrlProperty()
    {
        if (!$this->product) return asset('images/placeholder-product.jpg');

        // Prefer current variant image when selected
        if ($this->currentVariant && !empty($this->currentVariant->image_url)) {
            return $this->currentVariant->image_url;
        }

        // Use the product's built-in primary image accessor
        return $this->product->primary_image_url;
    }

    public function getAvailableSizesProperty()
    {
        if (!$this->product) return collect([]);

        return $this->product->variants()
            ->where('is_active', true)
            ->when($this->selectedColor, fn($q) => $q->where('color', $this->selectedColor))
            ->when($this->selectedMaterial, fn($q) => $q->where('material', $this->selectedMaterial))
            ->whereNotNull('size')
            ->pluck('size')
            ->unique()
            ->values();
    }

    public function getAvailableColorsProperty()
    {
        if (!$this->product) return collect([]);

        $colors = $this->product->variants()
            ->where('is_active', true)
            ->when($this->selectedSize, fn($q) => $q->where('size', $this->selectedSize))
            ->when($this->selectedMaterial, fn($q) => $q->where('material', $this->selectedMaterial))
            ->whereNotNull('color')
            ->pluck('color')
            ->unique()
            ->values();

        return $colors->map(function ($name) {
            return [
                'name' => $name,
                'hex_code' => $this->resolveColorHex($name),
            ];
        });
    }

    protected function resolveColorHex(?string $name): string
    {
        return \App\Support\ColorPalette::resolveHex($name);
    }

    public function getAvailableMaterialsProperty()
    {
        if (!$this->product) return collect([]);

        return $this->product->variants()
            ->where('is_active', true)
            ->when($this->selectedSize, fn($q) => $q->where('size', $this->selectedSize))
            ->when($this->selectedColor, fn($q) => $q->where('color', $this->selectedColor))
            ->whereNotNull('material')
            ->pluck('material')
            ->unique()
            ->values();
    }

    public function getCurrentVariantProperty()
    {
        if (!$this->selectedVariantId) return null;
        return ProductVariant::find($this->selectedVariantId);
    }

    public function getFormattedPriceProperty()
    {
        if (!$this->product) return '';

        // Use variant price if available, otherwise product price
        $price = $this->currentVariant?->price ?? ($this->product->sale_price ?: $this->product->price);
        $amount = (is_int($price) || ctype_digit((string)$price))
            ? (float)$price / 100
            : (float)$price;

        return money_format_ugx($amount);
    }

    public function getOriginalPriceProperty()
    {
        if (!$this->product || (!$this->product->sale_price && !$this->currentVariant)) return '';

        // Use variant price if available for comparison
        if ($this->currentVariant && $this->currentVariant->price) {
            $price = $this->currentVariant->price;
        } else {
            $price = $this->product->price;
        }

        $amount = (is_int($price) || ctype_digit((string)$price))
            ? (float)$price / 100
            : (float)$price;

        return money_format_ugx($amount);
    }

    public function render()
    {
        return view('livewire.components.product-quick-view');
    }
}
