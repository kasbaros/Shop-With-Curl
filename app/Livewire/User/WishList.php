<?php

namespace App\Livewire\User;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Wishlist extends Component
{
    use WithPagination;

    public function toggleWishlist($productId)
    {
        if (!Auth::check()) {
            $this->dispatch('show-notification',
                type: 'error',
                message: 'Please login to manage your wishlist'
            );
            return;
        }

        $user = Auth::user();
        $wishlistItem = $user->wishlist()->where('product_id', $productId)->first();

        if ($wishlistItem) {
            $wishlistItem->delete();
            $this->dispatch('show-notification',
                type: 'info',
                message: 'Product removed from wishlist'
            );
        } else {
            $user->wishlist()->create(['product_id' => $productId]);
            $this->dispatch('show-notification',
                type: 'success',
                message: 'Product added to wishlist'
            );
        }

        $this->dispatch('wishlist-updated');
    }

    public function removeFromWishlist($productId)
    {
        $user = Auth::user();
        $user->wishlist()->where('product_id', $productId)->delete();

        $this->dispatch('show-notification',
            type: 'info',
            message: 'Product removed from wishlist'
        );
    }

    public function getWishlistItemsProperty()
    {
        if (!Auth::check()) {
            return collect();
        }

        return Auth::user()->wishlist()
            ->with(['product.media', 'product.categories'])
            ->paginate(12);
    }

    public function render()
    {
        return view('livewire.user.wishlist');
    }
}
