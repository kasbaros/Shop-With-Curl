<?php

    namespace App\Livewire\Components;

    use App\Models\Product;
    use App\Models\ProductVariant;
    use App\Services\CartService;
    use Livewire\Attributes\On;
    use Livewire\Component;

    class ShoppingCart extends Component
    {
        public bool $showCart = false;
        public bool $agreeToTerms = false;

        // Modal states for the new design
        public bool $showAddNote = false;
        public bool $showAddGift = false;
        public bool $showEstimateShipping = false;

        // Additional properties for the new design
        public array $recommendations = [];
        public float $freeShippingThreshold = 75.0;

        protected CartService $cartService;

        public function boot(CartService $cartService)
        {
            $this->cartService = $cartService;
            $this->loadRecommendations();
        }

        #[On('cart:add-product')]
        public function addProduct(int $productId, ?int $variantId = null, int $quantity = 1): void
        {
            $product = Product::find($productId);
            if (!$product || !$product->is_active) {
                $this->dispatch('notify', ['message' => 'Product not found.', 'type' => 'error']);
                return;
            }

            $variants = [];
            if ($variantId) {
                $variant = ProductVariant::find($variantId);
                if ($variant) {
                    $variants['variant_id'] = $variant->id;
                    if ($variant->size) $variants['size'] = $variant->size;
                    if ($variant->color) $variants['color'] = $variant->color;
                }
            }

            $success = $this->cartService->add($product->id, $quantity, $variants);

            if ($success) {
                $this->showCart = true;
                $this->dispatch('notify', ['message' => $product->name . ' added to cart!', 'type' => 'success']);
                $this->dispatch('cart:updated', ['count' => $this->cartService->getCount()]);
            } else {
                $this->dispatch('notify', ['message' => 'Could not add ' . $product->name . ' to cart.', 'type' => 'error']);
            }
        }

        #[On('cart:add')]
        public function handleCartAdd($payloadOrProductId, $quantity = null, $variants = null)
        {
            // This method RECEIVES events from other components (like Cart.php, ProductQuickView.php)
            // It should NOT add items to cart itself, but rather:
            // 1. Auto-open the cart modal
            // 2. Show notifications
            // 3. Update cart count

            $this->showCart = true; // Auto-open the mini cart

            $this->dispatch('notify', [
                'message' => 'Product added to cart successfully!',
                'type' => 'success'
            ]);

            // Update cart count for the icon
            $cartCount = collect(session()->get('cart', []))->sum('quantity');
            $this->dispatch('cart:updated', ['count' => $cartCount]);
        }

        #[On('cart:update')]
        public function updateQuantity($itemKey, $quantity)
        {
            $success = $this->cartService->update($itemKey, $quantity);

            if ($success) {
                $this->dispatch('notify', [
                    'message' => 'Cart updated successfully!',
                    'type' => 'success'
                ]);

                $this->dispatch('cart:updated', ['count' => $this->cartService->getCount()]);
            } else {
                $this->dispatch('notify', [
                    'message' => 'Failed to update cart item.',
                    'type' => 'error'
                ]);
            }
        }

        #[On('cart:remove')]
        public function removeItem($itemKey)
        {
            $success = $this->cartService->remove($itemKey);

            if ($success) {
                $this->dispatch('notify', [
                    'message' => 'Item removed from cart!',
                    'type' => 'success'
                ]);

                $this->dispatch('cart:updated', ['count' => $this->cartService->getCount()]);
            }
        }

        #[On('cart:clear')]
        public function clearCart()
        {
            $this->cartService->clear();

            $this->dispatch('notify', [
                'message' => 'Cart cleared successfully!',
                'type' => 'success'
            ]);

            $this->dispatch('cart:updated', ['count' => 0]);
        }

        #[On('cart:toggle')]
        public function toggleCart()
        {
            $this->showCart = !$this->showCart;
        }

        public function closeCart()
        {
            $this->showCart = false;
        }

        // New methods for the redesigned drawer
        public function toggleDrawer()
        {
            $this->showCart = !$this->showCart;
        }

        public function toggleAddNote()
        {
            $this->showAddNote = !$this->showAddNote;
        }

        public function toggleAddGift()
        {
            $this->showAddGift = !$this->showAddGift;
        }

        public function toggleEstimateShipping()
        {
            $this->showEstimateShipping = !$this->showEstimateShipping;
        }

        /**
         * Load recommended products for the cart drawer
         */
        private function loadRecommendations()
        {
            try {
                // Get 3-4 random products as recommendations
                $products = Product::where('is_active', true)
                    ->where('stock_quantity', '>', 0)
                    ->inRandomOrder()
                    ->take(4)
                    ->get();

                $this->recommendations = $products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'price' => $product->effective_price,
                        'image' => $product->getStorageImageUrl($product->featured_image),
                    ];
                })->toArray();
            } catch (\Exception $e) {
                // If there's an error loading recommendations, set empty array
                $this->recommendations = [];
            }
        }

        // Computed properties using the CartService
//        public function getCartItemsProperty()
//        {
//            return $this->cartService->getItems();
//        }

        public function getCartItemsProperty()
        {
            // Get cart from session (same as Cart component)
            $sessionCart = session()->get('cart', []);

            if (empty($sessionCart)) {
                return [];
            }

            $cartItems = [];

            foreach ($sessionCart as $key => $item) {
                // Load product data
                $product = \App\Models\Product::find($item['product_id']);
                if (!$product) continue;

                // Load variant if exists
                $variant = null;
                if (!empty($item['variant_id'])) {
                    $variant = \App\Models\ProductVariant::find($item['variant_id']);
                }

                // Format for template
                $cartItems[] = [
                    'key' => $key,
                    'product_id' => $item['product_id'],
                    'product_name' => $product->name,
                    'product_slug' => $product->slug,
                    'product_image' => $product->primary_image_url,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'variants' => $variant ? [$variant->display_name] : [],
                    'total' => $item['price'] * $item['quantity'],
                ];
            }

            return $cartItems;
        }

//        public function getCartCountProperty()
//        {
//            return $this->cartService->getCount();
//        }

//        public function getCartTotalProperty()
//        {
//            return $this->cartService->getTotal();
//        }
//
//        public function getCartSubtotalProperty()
//        {
//            return $this->cartService->getSubtotal();
//        }
//
//        public function getFormattedTotalProperty()
//        {
//            return $this->cartService->formatPrice($this->cartService->getTotal());
//        }
//
//        public function getFormattedSubtotalProperty()
//        {
//            return $this->cartService->formatPrice($this->cartService->getSubtotal());
//        }


        public function getCartTotalProperty()
        {
            $cart = session()->get('cart', []);
            return collect($cart)->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            });
        }

        public function getCartSubtotalProperty()
        {
            return $this->getCartTotalProperty(); // Same as total for now
        }

        public function getFormattedTotalProperty()
        {
            return money_format_ugx($this->getCartTotalProperty());
        }

        public function getFormattedSubtotalProperty()
        {
            return money_format_ugx($this->getCartSubtotalProperty());
        }

        /**
         * Check if cart qualifies for free shipping
         */
        public function getQualifiesForFreeShippingProperty()
        {
            return $this->cartTotal >= $this->freeShippingThreshold;
        }

        /**
         * Get remaining amount needed for free shipping
         */
        public function getFreeShippingRemainingProperty()
        {
            $remaining = $this->freeShippingThreshold - $this->cartTotal;
            return max(0, $remaining);
        }

        /**
         * Get free shipping progress percentage
         */
        public function getFreeShippingProgressProperty()
        {
            return min(100, ($this->cartTotal / $this->freeShippingThreshold) * 100);
        }

        /**
         * Check if cart drawer is open (alias for backward compatibility)
         */
        public function getIsOpenProperty()
        {
            return $this->showCart;
        }

        /**
         * Get cart items formatted for the drawer
         */
        public function getCartProperty()
        {
            $items = $this->cartService->getItems();

            // Format items for the drawer template
            $formattedItems = [];
            foreach ($items as $key => $item) {
                $formattedItems[] = [
                    'key' => $key,
                    'id' => $item['product_id'] ?? null,
                    'name' => $item['name'] ?? 'Product',
                    'price' => $item['price'] ?? 0,
                    'quantity' => $item['quantity'] ?? 1,
                    'image' => $item['image'] ?? null,
                    'variant' => $item['variant_name'] ?? null,
                    'product' => [
                        'slug' => $item['product_slug'] ?? '#',
                        'name' => $item['name'] ?? 'Product',
                        'featured_image' => $item['featured_image'] ?? null,
                    ]
                ];
            }

            return $formattedItems;
        }

        public function render()
        {
            return view('livewire.components.shopping-cart');
        }
    }
