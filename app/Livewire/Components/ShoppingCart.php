<?php

namespace App\Livewire\Components;

use App\Services\CartService;
use Livewire\Attributes\On;
use Livewire\Component;

class ShoppingCart extends Component
{
    public bool $showCart = false;
    protected CartService $cartService;

    public function boot(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    #[On('cart:add')]
    public function addToCart($productId, $quantity = 1, $variants = [])
    {
        $success = $this->cartService->add($productId, $quantity, $variants);

        if ($success) {
            $this->dispatch('notify', [
                'message' => 'Product added to cart successfully!',
                'type' => 'success'
            ]);

            // Update cart count in header
            $this->dispatch('cart:updated', ['count' => $this->cartService->getCount()]);
        } else {
            $this->dispatch('notify', [
                'message' => 'Failed to add product to cart. Please check stock availability.',
                'type' => 'error'
            ]);
        }
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

    public function getCartItemsProperty()
    {
        return $this->cartService->getItems();
    }

    public function getCartCountProperty()
    {
        return $this->cartService->getCount();
    }

    public function getCartTotalProperty()
    {
        return $this->cartService->formatPrice($this->cartService->getTotal());
    }

    public function getCartSubtotalProperty()
    {
        return $this->cartService->formatPrice($this->cartService->getSubtotal());
    }

    public function render()
    {
        return view('livewire.components.shopping-cart');
    }
}
