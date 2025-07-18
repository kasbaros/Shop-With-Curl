<?php

namespace App\Livewire\Cart;

use App\Models\Product;
use App\Models\ProductVariant;
use Livewire\Component;
use Livewire\Attributes\On;

class CartDrawer extends Component
{
    public $isOpen = false;
    public $cart = [];

    public function mount()
    {
        $this->loadCart();
    }

    #[On('cart-updated')]
    public function loadCart()
    {
        $this->cart = $this->getCartWithDetails();
    }

    private function getCartWithDetails()
    {
        $cart = session()->get('cart', []);
        $cartWithDetails = [];

        foreach ($cart as $key => $item) {
            $product = Product::find($item['product_id']);
            $variant = $item['product_variant_id'] ? ProductVariant::find($item['product_variant_id']) : null;

            $cartWithDetails[$key] = [
                'key' => $key,
                'product' => $product,
                'variant' => $variant,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['price'] * $item['quantity'],
                'image' => $product->featured_image ?? '/images/placeholder.png',
                'name' => $product->name . ($variant ? ' - ' . $variant->display_name : ''),
            ];
        }

        return $cartWithDetails;
    }

    public function updateQuantity($key, $quantity)
    {
        if ($quantity < 1) {
            $this->removeItem($key);
            return;
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = $quantity;
            session()->put('cart', $cart);
            $this->loadCart();
            $this->dispatch('cart-updated');
        }
    }

    public function removeItem($key)
    {
        $cart = session()->get('cart', []);
        unset($cart[$key]);
        session()->put('cart', $cart);
        $this->loadCart();
        $this->dispatch('cart-updated');

        $this->dispatch('show-notification',
            type: 'info',
            message: 'Item removed from cart'
        );
    }

    public function clearCart()
    {
        session()->forget('cart');
        $this->cart = [];
        $this->dispatch('cart-updated');

        $this->dispatch('show-notification',
            type: 'info',
            message: 'cart cleared'
        );
    }

    public function toggleDrawer()
    {
        $this->isOpen = !$this->isOpen;
    }

    #[On('toggle-cart-drawer')]
    public function handleToggleDrawer()
    {
        $this->toggleDrawer();
    }

    public function getSubtotalProperty()
    {
        return collect($this->cart)->sum('total');
    }

    public function getTotalItemsProperty()
    {
        return collect($this->cart)->sum('quantity');
    }

    public function render()
    {
        return view('livewire.cart.cart-drawer');
    }
}
