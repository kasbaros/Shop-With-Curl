<?php

namespace App\Livewire\Client;

use App\Models\Product;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class WishListToggle extends Component
{
    public Product $product;
    public bool $isInWishlist = false;

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

    #[On('wishlist:toggle')]
    public function handleToggle($data)
    {
        if (isset($data['id']) && $data['id'] == $this->product->id) {
            $this->toggle();
        }
    }

    public function toggle()
    {
        if (!Auth::check()) {
            $this->dispatch('notify', [
                'message' => 'Please login to manage your wishlist',
                'type' => 'error'
            ]);
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

        $this->dispatch('notify', [
            'message' => $message,
            'type' => 'success'
        ]);

        $this->dispatch('wishlist:updated', ['count' => $user->wishlist()->count()]);
    }

    public function render()
    {
        return view('livewire.client.wishlist-toggle');
    }
}
