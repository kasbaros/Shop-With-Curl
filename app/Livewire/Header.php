<?php
//
//use App\Traits\SharedLayoutData;
//use Livewire\Volt\Component;
//
//new class extends Component {
//    use SharedLayoutData;
//
//    public function mount(): void
//    {
//        // Remove the language and currency logic since we're not using them
//    }
//
//    public function getCartCountProperty(): int
//    {
//        return $this->getLayoutData()['cartCount'];
//    }
//
//    public function getWishlistCountProperty(): int
//    {
//        return $this->getLayoutData()['wishlistCount'];
//    }
//};
//

namespace App\Livewire;

use App\Traits\SharedLayoutData;
use Livewire\Component;

class Header extends Component
{
    use SharedLayoutData;

    public $itemCount = 0;
    public $total = 0;

    protected $listeners = ['cart-updated' => 'refreshCart'];

    public function mount(): void
    {
        $this->refreshCart();
    }

//    public function refreshCart(): void
//    {
//        $cart = session()->get('cart', []);
//        $this->itemCount = collect($cart)->sum('quantity');
//        $this->total = collect($cart)->sum(function ($item) {
//            return $item['price'] * $item['quantity'];
//        });
//    }

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
        return view('livewire.header');
    }
}
