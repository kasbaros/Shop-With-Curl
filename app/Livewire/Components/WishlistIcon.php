<?php

namespace App\Livewire\Components;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class WishlistIcon extends Component
{
    public int $wishlistCount = 0;

    public function mount()
    {
        $this->updateWishlistCount();
    }

    #[On('wishlist:updated')]
    public function updateWishlistCountFromEvent($data = null)
    {
        if (is_array($data) && array_key_exists('count', $data)) {
            $this->wishlistCount = (int) $data['count'];
        } elseif (is_numeric($data)) {
            $this->wishlistCount = (int) $data;
        } else {
            $this->wishlistCount = auth()->check() ? auth()->user()->wishlist()->count() : 0;
        }
    }

    private function updateWishlistCount()
    {
        if (Auth::check()) {
            $this->wishlistCount = Auth::user()->wishlist()->count();
        } else {
            $this->wishlistCount = 0;
        }
    }

    public function render()
    {
        return view('livewire.components.wishlist-icon');
    }
}
