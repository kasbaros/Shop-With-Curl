<?php

    namespace App\Livewire\Components;

    use App\Models\Product;
    use Livewire\Attributes\On;
    use Livewire\Component;

    class ProductQuickAdd extends Component
    {
        public ?Product $product = null;
        public bool $showModal = false;
        public int $quantity = 1;
        public string $selectedColor = 'Orange'; // Initialize with default value
        public string $selectedSize = 'S'; // Initialize with default value
        public array $selectedVariants = [];

        #[On('product:quickAdd')]
        public function showQuickAdd($productId)
        {
            $this->product = Product::with(['categories', 'media'])->find($productId);
            $this->showModal = true;
            $this->quantity = 1;
            $this->selectedColor = 'Orange';
            $this->selectedSize = 'S';
            $this->selectedVariants = [
                'color' => $this->selectedColor,
                'size' => $this->selectedSize,
            ];
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

        public function updatedSelectedColor($value)
        {
            $this->selectedVariants['color'] = $value;
        }

        public function updatedSelectedSize($value)
        {
            $this->selectedVariants['size'] = $value;
        }

        public function addToCart()
        {
            if (!$this->product) return;

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

        public function getFormattedPriceProperty()
        {
            if (!$this->product) return '';

            $price = $this->product->sale_price ?: $this->product->price;
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
