<?php

namespace App\Http\Livewire;
use Livewire\Component;

class CartCount extends Component
{
    public $cartCount;

    public function mount()
    {
        $this->cartCount = session('cart', [])->count();
    }

    public function render()
    {
        return view('livewire.cart-count');
    }
}
