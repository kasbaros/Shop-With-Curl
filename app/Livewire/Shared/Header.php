<?php

namespace App\Livewire\Shared;

use AllowDynamicProperties;
use App\Traits\SharedLayoutData;
use Livewire\Component;

#[AllowDynamicProperties]
class Header extends Component
{
    use SharedLayoutData;

    public $itemCount = 0;
    public $total = 0;

    protected $listeners = ['cart:updated' => 'refreshCart'];

    public function mount(): void
    {
        $this->refreshCart();
    }


    public $cartCount = 0;

    public function refreshCart(): void
    {
        $cart = session()->get('cart', []);
        $this->itemCount = collect($cart)->sum('quantity');
        $this->cartCount = $this->itemCount; // Sync with itemCount
        $this->total = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
    }

    public function refreshWishlist(): void
    {
        $wishlist = session()->get('wishlist', []);
        $this->wishlistCount = collect($wishlist)->count();
    }

    #[On('cart:updated')]
    public function updateCartCount(): void
    {
        $this->cartCount = collect(session()->get('cart', []))->sum('quantity');
    }

    public function toggleCartDrawer(): void
    {
        $this->dispatch('toggle-cart-drawer');
    }

    public function getCartCountProperty(): int
    {
        return $this->getLayoutData()['cartCount'];
    }

    public function getWishlistCountProperty(): int
    {
        return $this->getLayoutData()['wishlistCount'];
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('livewire.shared.header');
    }
}
