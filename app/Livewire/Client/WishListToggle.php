<?php

namespace App\Livewire\Client;

use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class WishListToggle extends Component
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
        } else {
            $this->isInWishlist = false;
        }
    }

    public function toggle()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $existingWishlistItem = $user->wishlist()
            ->where('product_id', $this->product->id)
            ->first();

        if ($existingWishlistItem) {
            // Remove from wishlist
            $existingWishlistItem->delete();
            $this->isInWishlist = false;
            session()->flash('message', 'Removed from wishlist');
        } else {
            // Add to wishlist
            $user->wishlist()->create([
                'product_id' => $this->product->id
            ]);
            $this->isInWishlist = true;
            session()->flash('message', 'Added to wishlist');
        }

        // Dispatch event to update wishlist count in header
        $this->dispatch('wishlist-updated');
    }

    public function render()
    {
        return view('livewire.client.wishlist-toggle');
    }
}
