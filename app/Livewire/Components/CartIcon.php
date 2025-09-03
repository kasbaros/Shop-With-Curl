<?php

    namespace App\Livewire\Components;

    use App\Services\CartService;
    use Livewire\Attributes\On;
    use Livewire\Component;

    class CartIcon extends Component
    {
        public int $cartCount = 0;
        protected CartService $cartService;

        public function boot(CartService $cartService)
        {
            $this->cartService = $cartService;
        }

        public function mount()
        {
            $this->cartCount = $this->cartService->getCount();
        }

        #[On('cart:updated')]
        public function updateCartCount($data)
        {
            // Handle both numeric counts and array payloads
            if (is_array($data) && isset($data['count'])) {
                $this->cartCount = (int) $data['count'];
            } elseif (is_numeric($data)) {
                $this->cartCount = (int) $data;
            } else {
                $this->cartCount = $this->cartService->getCount();
            }
        }

        // Called by wire:click on the navbar cart icon
        public function toggleCart(): void
        {
            // Bubble an event that the ShoppingCart component listens to
            $this->dispatch('cart:toggle');
        }

        public function render()
        {
            return view('livewire.components.cart-icon');
        }
    }
