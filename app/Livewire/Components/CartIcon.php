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
    public function updateCartCount($count)
    {
        $this->cartCount = $count;
    }

    public function toggleCart()
    {
        $this->dispatch('cart:toggle');
    }

    public function render()
    {
        return view('livewire.components.cart-icon');
    }
}
