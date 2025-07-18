<?php

namespace App\Livewire\Cart;

use App\Models\Product;
use App\Models\ProductVariant;
use Livewire\Component;
use Livewire\Attributes\On;

class AddToCart extends Component
{
    public Product $product;
    public $selectedVariant = null;
    public $quantity = 1;
    public $selectedSize = null;
    public $selectedColor = null;
    public $selectedMaterial = null;
    public $showVariantSelection = false;

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->showVariantSelection = $this->product->variants()->exists();

        if ($this->showVariantSelection) {
            $this->initializeVariantSelections();
        }
    }

    private function initializeVariantSelections()
    {
        $variants = $this->product->variants()->where('is_active', true)->get();

        // Get unique values for each variant attribute
        $sizes = $variants->whereNotNull('size')->pluck('size')->unique()->values();
        $colors = $variants->whereNotNull('color')->pluck('color')->unique()->values();
        $materials = $variants->whereNotNull('material')->pluck('material')->unique()->values();

        // Auto-select if only one option available
        if ($sizes->count() === 1) {
            $this->selectedSize = $sizes->first();
        }
        if ($colors->count() === 1) {
            $this->selectedColor = $colors->first();
        }
        if ($materials->count() === 1) {
            $this->selectedMaterial = $materials->first();
        }
    }

    public function updatedSelectedSize()
    {
        $this->updateSelectedVariant();
    }

    public function updatedSelectedColor()
    {
        $this->updateSelectedVariant();
    }

    public function updatedSelectedMaterial()
    {
        $this->updateSelectedVariant();
    }

    private function updateSelectedVariant()
    {
        if (!$this->showVariantSelection) {
            return;
        }

        $variant = $this->product->variants()
            ->where('is_active', true)
            ->when($this->selectedSize, fn($q) => $q->where('size', $this->selectedSize))
            ->when($this->selectedColor, fn($q) => $q->where('color', $this->selectedColor))
            ->when($this->selectedMaterial, fn($q) => $q->where('material', $this->selectedMaterial))
            ->first();

        $this->selectedVariant = $variant?->id;
    }

    public function addToCart()
    {
        if ($this->showVariantSelection && !$this->selectedVariant) {
            $this->addError('variant', 'Please select product options.');
            return;
        }

        if ($this->quantity < 1) {
            $this->addError('quantity', 'Quantity must be at least 1.');
            return;
        }

        // Check stock availability
        $stockQuantity = $this->selectedVariant
            ? ProductVariant::find($this->selectedVariant)->stock_quantity
            : $this->product->stock_quantity;

        if ($stockQuantity < $this->quantity) {
            $this->addError('quantity', 'Not enough stock available.');
            return;
        }

        // Add to cart (using session-based cart)
        $cart = session()->get('cart', []);
        $cartKey = $this->selectedVariant ? 'variant_' . $this->selectedVariant : 'product_' . $this->product->id;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $this->quantity;
        } else {
            $cart[$cartKey] = [
                'product_id' => $this->product->id,
                'product_variant_id' => $this->selectedVariant,
                'quantity' => $this->quantity,
                'price' => $this->selectedVariant
                    ? ProductVariant::find($this->selectedVariant)->effective_price
                    : $this->product->effective_price,
            ];
        }

        session()->put('cart', $cart);

        $this->dispatch('cart-updated');
        $this->dispatch('show-notification',
            type: 'success',
            message: 'Product added to cart!'
        );

        // Reset form
        $this->quantity = 1;
    }

    public function getAvailableSizesProperty()
    {
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
        return $this->product->variants()
            ->where('is_active', true)
            ->when($this->selectedSize, fn($q) => $q->where('size', $this->selectedSize))
            ->when($this->selectedMaterial, fn($q) => $q->where('material', $this->selectedMaterial))
            ->whereNotNull('color')
            ->pluck('color')
            ->unique()
            ->values();
    }

    public function getAvailableMaterialsProperty()
    {
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
        if (!$this->selectedVariant) {
            return null;
        }

        return ProductVariant::find($this->selectedVariant);
    }

    public function render()
    {
        return view('livewire.cart.add-to-cart');
    }
}
