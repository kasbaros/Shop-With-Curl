<?php

namespace App\Livewire\User;

use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class WishlistToggle extends Component
{
    public Product $product;
    public $isInWishlist = false;

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->checkWishlistStatus();
    }

    public function checkWishlistStatus()
    {
        if (Auth::check()) {
            $this->isInWishlist = Auth::user()->wishlist()
                ->where('product_id', $this->product->id)
                ->exists();
        }
    }

    public function toggle()
    {
        if (!Auth::check()) {
            $this->dispatch('show-notification',
                type: 'error',
                message: 'Please login to manage your wishlist'
            );
            return;
        }

        $user = Auth::user();

        if ($this->isInWishlist) {
            $user->wishlist()->where('product_id', $this->product->id)->delete();
            $this->isInWishlist = false;
            $message = 'Product removed from wishlist';
        } else {
            $user->wishlist()->create(['product_id' => $this->product->id]);
            $this->isInWishlist = true;
            $message = 'Product added to wishlist';
        }

        $this->dispatch('show-notification',
            type: 'success',
            message: $message
        );

        $this->dispatch('wishlist-updated');
    }

    public function render()
    {
        return view('livewire.user.wishlist-toggle');
    }
}
