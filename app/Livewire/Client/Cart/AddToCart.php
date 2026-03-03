<?php

namespace App\Livewire\Client\Cart;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Support\Collection;
use Livewire\Component;

class AddToCart extends Component
{
    public Product $product;
    public int $quantity = 1;
    public ?string $selectedSize = null;
    public ?string $selectedColor = null;
    public ?string $selectedMaterial = null;
    public ?int $selectedVariant = null;
    public bool $showVariantSelection = false;

    protected CartService $cartService;

    public function boot(CartService $cartService): void
    {
        $this->cartService = $cartService;
    }

    public function mount(Product $product): void
    {
        $this->product = $product;
        $this->product->load('variants', 'colors', 'sizes');

        // Determine if this product has variants
        $this->showVariantSelection = $this->product->variants->where('is_active', true)->isNotEmpty();

        // Pre-select first available options
        if ($this->showVariantSelection) {
            $sizes = $this->getAvailableSizesProperty();
            $colors = $this->getAvailableColorsProperty();

            if ($sizes->isNotEmpty()) {
                $this->selectedSize = $sizes->first();
            }
            if ($colors->isNotEmpty()) {
                $firstColor = $colors->first();
                $this->selectedColor = is_array($firstColor) ? $firstColor['name'] : $firstColor;
            }

            $this->updateSelectedVariant();
        }
    }

    public function getAvailableSizesProperty(): Collection
    {
        if (!$this->showVariantSelection) {
            return collect();
        }

        return $this->product->variants
            ->where('is_active', true)
            ->pluck('size')
            ->unique()
            ->filter()
            ->values();
    }

    public function getAvailableColorsProperty(): Collection
    {
        if (!$this->showVariantSelection) {
            return collect();
        }

        // Get unique colors with their info
        $colors = $this->product->variants
            ->where('is_active', true)
            ->pluck('color')
            ->unique()
            ->filter()
            ->map(function ($colorName) {
                // Try to get hex code from Color model or use a default
                $color = \App\Models\Color::where('name', $colorName)->first();
                return [
                    'name' => $colorName,
                    'hex_code' => $color?->value ?? $this->getDefaultColorHex($colorName),
                ];
            })
            ->values();

        return $colors;
    }

    public function getAvailableMaterialsProperty(): Collection
    {
        if (!$this->showVariantSelection) {
            return collect();
        }

        return $this->product->variants
            ->where('is_active', true)
            ->pluck('material')
            ->unique()
            ->filter()
            ->values();
    }

    public function getCurrentVariantProperty(): ?ProductVariant
    {
        if (!$this->showVariantSelection || !$this->selectedVariant) {
            return null;
        }

        return ProductVariant::find($this->selectedVariant);
    }

    public function updatedSelectedSize(): void
    {
        $this->updateSelectedVariant();
    }

    public function updatedSelectedColor(): void
    {
        $this->updateSelectedVariant();
    }

    public function updatedSelectedMaterial(): void
    {
        $this->updateSelectedVariant();
    }

    protected function updateSelectedVariant(): void
    {
        if (!$this->showVariantSelection) {
            return;
        }

        $query = $this->product->variants()->where('is_active', true);

        if ($this->selectedSize) {
            $query->where('size', $this->selectedSize);
        }
        if ($this->selectedColor) {
            $query->where('color', $this->selectedColor);
        }
        if ($this->selectedMaterial) {
            $query->where('material', $this->selectedMaterial);
        }

        $variant = $query->first();
        $this->selectedVariant = $variant?->id;
    }

    public function addToCart(): void
    {
        // Validate quantity
        if ($this->quantity < 1) {
            $this->quantity = 1;
        }

        // Check if product is in stock
        if (!$this->product->is_in_stock) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'This product is out of stock.'
            ]);
            return;
        }

        // Check variant selection if required
        if ($this->showVariantSelection && !$this->selectedVariant) {
            $this->addError('variant', 'Please select product options.');
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Please select size and color options.'
            ]);
            return;
        }

        // Check variant stock
        if ($this->showVariantSelection && $this->selectedVariant) {
            $variant = ProductVariant::find($this->selectedVariant);
            if (!$variant || $variant->stock_quantity < $this->quantity) {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'Not enough stock available for this variant.'
                ]);
                return;
            }
        }

        // Build variants array for cart
        $variants = [];
        if ($this->selectedVariant) {
            $variants['variant_id'] = $this->selectedVariant;
        }
        if ($this->selectedSize) {
            $variants['size'] = $this->selectedSize;
        }
        if ($this->selectedColor) {
            $variants['color'] = $this->selectedColor;
        }
        if ($this->selectedMaterial) {
            $variants['material'] = $this->selectedMaterial;
        }

        // Add to cart using CartService
        $success = $this->cartService->add(
            $this->product->id,
            $this->quantity,
            $variants
        );

        if ($success) {
            // Dispatch events for cart updates
            $this->dispatch('cart:updated');
            $this->dispatch('cart:item-added', [
                'productId' => $this->product->id,
                'productName' => $this->product->name,
                'quantity' => $this->quantity,
            ]);
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => "{$this->product->name} added to cart!"
            ]);

            // Reset quantity
            $this->quantity = 1;
        } else {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Could not add item to cart. Please try again.'
            ]);
        }
    }

    protected function getDefaultColorHex(string $colorName): string
    {
        $colors = [
            'red' => '#FF0000',
            'blue' => '#0000FF',
            'green' => '#00FF00',
            'yellow' => '#FFFF00',
            'black' => '#000000',
            'white' => '#FFFFFF',
            'pink' => '#FFC0CB',
            'purple' => '#800080',
            'orange' => '#FFA500',
            'gray' => '#808080',
            'grey' => '#808080',
            'brown' => '#A52A2A',
            'navy' => '#000080',
            'beige' => '#F5F5DC',
        ];

        return $colors[strtolower($colorName)] ?? '#CCCCCC';
    }

    public function render()
    {
        return view('livewire.client.cart.add-to-cart');
    }
}
