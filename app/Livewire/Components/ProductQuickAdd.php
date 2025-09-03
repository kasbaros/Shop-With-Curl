<?php

    namespace App\Livewire\Components;

    use App\Models\Product;
    use App\Models\ProductVariant;
    use Livewire\Attributes\On;
    use Livewire\Component;

    class ProductQuickAdd extends Component
    {
        public ?Product $product = null;
        public bool $showModal = false;
        public int $quantity = 1;
        public ?string $selectedColor = null;
        public ?string $selectedSize = null;
        public ?string $selectedMaterial = null;
        public array $selectedVariants = [];
        public ?int $selectedVariantId = null;

        #[On('product:quickAdd')]
        public function showQuickAdd($productId)
        {
            $this->product = Product::with(['categories', 'media', 'variants'])->find($productId);
            if (!$this->product) return;

            $this->showModal = true;
            $this->quantity = 1;
            $this->resetVariantSelections();
            $this->initializeVariantSelections();
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

        public function closeModal()
        {
            $this->showModal = false;
            $this->product = null;
            $this->reset(['quantity', 'selectedColor', 'selectedSize', 'selectedVariants']);
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

            $this->dispatch('cart:add', $this->product->id, $this->quantity, $this->selectedVariants);

            $this->dispatch('notify', [
                'message' => $this->product->name . ' added to cart!',
                'type' => 'success'
            ]);

            $this->closeModal();
        }

        public function getImageUrlProperty()
        {
            if (!$this->product) return asset('images/placeholder-product.jpg');

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

        public function render()
        {
            return view('livewire.components.product-quick-add');
        }
    }
