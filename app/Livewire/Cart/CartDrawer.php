<?php
//
//namespace App\Livewire\Cart;
//
//use Livewire\Component;
//
//class CartDrawer extends Component
//{
//    public $isOpen = false;
//    public $cart = [];
//    public $itemCount = 0; // Add itemCount property
//    public $total = 0; // Add total property
//
//    protected $listeners = ['cart-updated' => 'refreshCart'];
//
//    public function mount(): void
//    {
//        $this->refreshCart();
//    }
//
//    public function toggleDrawer(): void
//    {
//        $this->isOpen = !$this->isOpen;
//    }
//
//    public function openDrawer(): void
//    {
//        $this->isOpen = true;
//    }
//
//    public function closeDrawer(): void
//    {
//        $this->isOpen = false;
//    }
//
//    public function refreshCart(): void
//    {
//        // Get cart from session
//        $this->cart = session()->get('cart', []);
//
//        // Calculate item count (sum of quantities)
//        $this->itemCount = collect($this->cart)->sum('quantity');
//
//        // Calculate total (use existing subtotal logic)
//        $this->total = $this->getSubtotalProperty();
//    }
//
//    public function updateQuantity($key, $quantity): void
//    {
//        if ($quantity <= 0) {
//            $this->removeItem($key);
//            return;
//        }
//
//        $cart = session()->get('cart', []);
//        if (isset($cart[$key])) {
//            $cart[$key]['quantity'] = $quantity;
//            session()->put('cart', $cart);
//            $this->refreshCart();
//            $this->dispatch('cart-updated');
//        }
//    }
//
//    public function removeItem($key): void
//    {
//        $cart = session()->get('cart', []);
//        unset($cart[$key]);
//        session()->put('cart', $cart);
//        $this->refreshCart();
//        $this->dispatch('cart-updated');
//    }
//
//    public function clearCart(): void
//    {
//        session()->forget('cart');
//        $this->refreshCart();
//        $this->dispatch('cart-updated');
//    }
//
//    public function getSubtotalProperty()
//    {
//        return collect($this->cart)->sum(function ($item) {
//            return $item['price'] * $item['quantity'];
//        });
//    }
//
//    public function render()
//    {
//        return view('livewire.cart.cart-drawer');
//    }
//}


namespace App\Livewire\Cart;

use Livewire\Component;

class CartDrawer extends Component
{
    public $isOpen = false;
    public $cart = [];
    public $itemCount = 0;
    public $total = 0;

    protected $listeners = [
        'cart-updated' => 'refreshCart',
        'toggle-cart-drawer' => 'toggleDrawer',
    ];

    public function mount(): void
    {
        $this->refreshCart();
    }

    public function toggleDrawer(): void
    {
        $this->isOpen = !$this->isOpen;
    }

    public function openDrawer(): void
    {
        $this->isOpen = true;
    }

    public function closeDrawer(): void
    {
        $this->isOpen = false;
    }

    public function refreshCart(): void
    {
        $this->cart = session()->get('cart', []);
        $this->itemCount = collect($this->cart)->sum('quantity');
        $this->total = collect($this->cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
    }

    public function updateQuantity($key, $quantity): void
    {
        if ($quantity <= 0) {
            $this->removeItem($key);
            return;
        }

        $cart = session()->get('cart', []);
        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = $quantity;
            session()->put('cart', $cart);
            $this->refreshCart();
            $this->dispatch('cart-updated');
        }
    }

    public function removeItem($key): void
    {
        $cart = session()->get('cart', []);
        unset($cart[$key]);
        session()->put('cart', $cart);
        $this->refreshCart();
        $this->dispatch('cart-updated');
    }

    public function clearCart(): void
    {
        session()->forget('cart');
        $this->refreshCart();
        $this->dispatch('cart-updated');
    }

    public function getSubtotalProperty()
    {
        return collect($this->cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
    }

    public function render()
    {
        return view('livewire.cart.cart-drawer');
    }
}
