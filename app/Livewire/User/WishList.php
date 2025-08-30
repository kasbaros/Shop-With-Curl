<?php

namespace App\Livewire\User;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

#[Layout('components.app-layout')]
class WishList extends Component
{
    use WithPagination;

    public function toggleWishlist($productId): void
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

    public function removeFromWishlist($productId): void
    {
        if (Auth::check()) {
            Auth::user()->wishlist()->where('product_id', $productId)->delete();
            $this->dispatch('show-notification',
                type: 'info',
                message: 'Product removed from wishlist'
            );
            $this->dispatch('wishlist-updated');
        }
    }

    public function render()
    {
        $wishlistItems = Auth::check()
            ? Auth::user()->wishlist()->with(['product.media', 'product.categories'])->paginate(12)
            : new LengthAwarePaginator([], 0, 12);

        return view('livewire.user.wish-list', compact('wishlistItems'));
    }
}
