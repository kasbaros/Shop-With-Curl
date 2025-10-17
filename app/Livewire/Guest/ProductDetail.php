<?php

namespace App\Livewire\Guest;

use App\Models\Product;
use App\Models\ProductVariant;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Shop - ShopWithCarl')]
#[Layout('components.app-layout')]
class ProductDetail extends Component
{
    public Product $product;

    // UI state
    public int $selectedImageIndex = 0;
    public bool $showSizeChart = false;

    // Variant selection state
    public ?string $selectedSize = null;
    public ?string $selectedColor = null;
    public ?string $selectedMaterial = null;
    public ?int $selectedVariantId = null;
    public int $quantity = 1;
    public bool $showVariantSelection = false;

    public function mount(Product $product): void
    {
        $this->product = $product->load(['categories', 'variants', 'reviews.user', 'media']);
        $this->showVariantSelection = $this->product->variants()->exists();

        // Optionally preselect first available variant
        if ($this->showVariantSelection) {
            $first = $this->product->variants()->where('is_active', true)->first();
            if ($first) {
                $this->selectedSize = $first->size ?: null;
                $this->selectedColor = $first->color ?: null;
                $this->selectedMaterial = $first->material ?: null;
                $this->selectedVariantId = $first->id;
            }
        }
    }

    public function selectImage($index): void
    {
        $this->selectedImageIndex = (int) $index;
    }

    public function toggleSizeChart(): void
    {
        $this->showSizeChart = !$this->showSizeChart;
    }

    // Keep legacy images accessor (fallbacks only used when no variant image selected)
    public function getImagesProperty(): array
    {
        $images = $this->product->gallery_images;

        if (empty($images) && $this->product->featured_image) {
            $images = [[
                'url' => $this->product->featured_image,
                'thumb' => $this->product->featured_image,
                'large' => $this->product->featured_image,
            ]];
        }

        return $images ?: [[
            'url' => '/images/placeholder.png',
            'thumb' => '/images/placeholder.png',
            'large' => '/images/placeholder.png',
        ]];
    }

    public function getSelectedImageProperty(): ?array
    {
        $images = $this->images;
        return $images[$this->selectedImageIndex] ?? $images[0] ?? null;
    }

    // Variant-driven computed values
    public function getCurrentVariantProperty(): ?ProductVariant
    {
        if ($this->selectedVariantId) {
            return ProductVariant::find($this->selectedVariantId);
        }

        if (!$this->showVariantSelection) return null;

        $query = $this->product->variants()->where('is_active', true);
        if ($this->selectedSize) $query->where('size', $this->selectedSize);
        if ($this->selectedColor) $query->where('color', $this->selectedColor);
        if ($this->selectedMaterial) $query->where('material', $this->selectedMaterial);

        return $query->first();
    }

    // Provide selectedVariant magic property for Blade compatibility
    public function getSelectedVariantProperty(): ?ProductVariant
    {
        return $this->currentVariant;
    }

    public function getImageUrlProperty(): string
    {
        // Prefer current variant image
        if ($this->currentVariant && !empty($this->currentVariant->image_url)) {
            return $this->currentVariant->image_url;
        }
        // Fallback to product primary image
        return $this->product->primary_image_url ?? asset('images/placeholder-product.jpg');
    }

    public function getAvailableSizesProperty()
    {
        if (!$this->showVariantSelection) return collect([]);
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
        if (!$this->showVariantSelection) return collect([]);
        $colors = $this->product->variants()
            ->where('is_active', true)
            ->when($this->selectedSize, fn($q) => $q->where('size', $this->selectedSize))
            ->when($this->selectedMaterial, fn($q) => $q->where('material', $this->selectedMaterial))
            ->whereNotNull('color')
            ->pluck('color')
            ->unique()
            ->values();

        // Map to objects with name + hex_code for Blade that expects this structure
        return $colors->map(function ($name) {
            return [
                'name' => $name,
                'hex_code' => $this->resolveColorHex($name),
            ];
        });
    }

    public function getAvailableMaterialsProperty()
    {
        if (!$this->showVariantSelection) return collect([]);
        return $this->product->variants()
            ->where('is_active', true)
            ->when($this->selectedSize, fn($q) => $q->where('size', $this->selectedSize))
            ->when($this->selectedColor, fn($q) => $q->where('color', $this->selectedColor))
            ->whereNotNull('material')
            ->pluck('material')
            ->unique()
            ->values();
    }

    // Keep price helpers similar to quick components
    public function getFormattedPriceProperty(): string
    {
        $price = $this->currentVariant?->price ?? ($this->product->sale_price ?: $this->product->price);
        $amount = (is_int($price) || ctype_digit((string)$price)) ? (float)$price / 100 : (float)$price;
        return money_format_ugx($amount);
    }

    public function getOriginalPriceProperty(): string
    {
        if (!$this->product || (!$this->product->sale_price && !$this->currentVariant)) return '';
        $price = $this->currentVariant?->price ?: $this->product->price;
        $amount = (is_int($price) || ctype_digit((string)$price)) ? (float)$price / 100 : (float)$price;
        return money_format_ugx($amount);
    }

    // React to selection changes to lock onto an exact variant id when possible
    public function updatedSelectedSize(): void { $this->resolveVariantSelection(); }
    public function updatedSelectedColor(): void { $this->resolveVariantSelection(); }
    public function updatedSelectedMaterial(): void { $this->resolveVariantSelection(); }

    protected function resolveVariantSelection(): void
    {
        if (!$this->showVariantSelection) return;
        $query = $this->product->variants()->where('is_active', true);
        if ($this->selectedSize) $query->where('size', $this->selectedSize);
        if ($this->selectedColor) $query->where('color', $this->selectedColor);
        if ($this->selectedMaterial) $query->where('material', $this->selectedMaterial);

        $match = $query->first();
        $this->selectedVariantId = $match?->id;
    }

    // Add to Cart (align with QuickAdd/QuickView logic)
    public function addToCart(): void
    {
        if (!$this->product) return;

        // Validate selection when variants exist
        if ($this->showVariantSelection && !$this->selectedVariantId) {
            $this->dispatch('notify', [
                'message' => 'Please select product options.',
                'type' => 'error'
            ]);
            return;
        }

        // Stock checks
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

        $selected = [
            'size' => $this->selectedSize,
            'color' => $this->selectedColor,
            'material' => $this->selectedMaterial,
            'variant_id' => $this->selectedVariantId,
        ];

        $cartService = app(\App\Services\CartService::class);
        $success = $cartService->add($this->product->id, $this->quantity, $selected);

        if ($success) {
            $this->dispatch('cart:add', $this->product->id, $this->quantity, $selected);
            $this->dispatch('notify', [
                'message' => $this->product->name . ' added to cart!',
                'type' => 'success'
            ]);
        } else {
            $this->dispatch('notify', [
                'message' => 'Failed to add item to cart.',
                'type' => 'error'
            ]);
        }
    }

    protected function resolveColorHex(?string $name): string
    {
        if (!$name) return '#999999';
        $map = [
            'black' => '#000000', 'white' => '#FFFFFF', 'blue' => '#1D4ED8', 'red' => '#DC2626',
            'green' => '#16A34A', 'yellow' => '#F59E0B', 'orange' => '#F97316', 'purple' => '#7C3AED',
            'pink' => '#DB2777', 'gray' => '#6B7280', 'grey' => '#6B7280', 'brown' => '#92400E',
        ];
        $key = strtolower(trim($name));
        return $map[$key] ?? '#999999';
    }

    public function getRelatedProductsProperty()
    {
        return Product::active()
            ->whereHas('categories', function ($query) {
                $query->whereIn('categories.id', $this->product->categories->pluck('id'));
            })
            ->where('id', '!=', $this->product->id)
            ->limit(4)
            ->get();
    }

    public function render()
    {
        return view('livewire.guest.products.product-detail');
    }
}
