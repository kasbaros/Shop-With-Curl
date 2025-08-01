<?php
//
//namespace App\Livewire\Cart;
//
//use Livewire\Component;
//use Livewire\Attributes\On;
//
//class MiniCart extends Component
//{
//    public $itemCount = 0;
//    public $total = 0;
//
//    public function mount(): void
//    {
//        $this->updateCartSummary();
//    }
//
//    #[On('cart-updated')]
//    public function updateCartSummary(): void
//    {
//        $cart = session()->get('cart', []);
//        $this->itemCount = collect($cart)->sum('quantity');
//        $this->total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
//    }
//
//    public function toggleCartDrawer(): void
//    {
//        $this->dispatch('toggle-cart-drawer');
//    }
//
////    public function render()
////    {
////        return view('livewire.cart.mini-cart');
////    }
//}
