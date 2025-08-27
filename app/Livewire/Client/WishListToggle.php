<?php
//
//namespace App\Livewire\Client;
//
//use App\Models\Product;
//use Livewire\Component;
//use Illuminate\Support\Facades\Auth;
//
//class WishListToggle extends Component
//{
//    public Product $product;
//    public $isInWishlist = false;
//
//    public function mount(Product $product)
//    {
//        $this->product = $product;
//        $this->checkWishlistStatus();
//    }
//
//    public function checkWishlistStatus()
//    {
//        if (Auth::check()) {
//            $this->isInWishlist = Auth::user()->wishlist()
//                ->where('product_id', $this->product->id)
//                ->exists();
//        } else {
//            $this->isInWishlist = false;
//        }
//    }
//
//
//
//    public function toggle()
//    {
//        if (!Auth::check()) {
//            return redirect()->route('login');
//        }
//
//        $user = Auth::user();
//        $existingWishlistItem = $user->wishlist()
//            ->where('product_id', $this->product->id)
//            ->first();
//
//        if ($existingWishlistItem) {
//            // Remove from wishlist
//            $existingWishlistItem->delete();
//            $this->isInWishlist = false;
//            session()->flash('message', 'Removed from wishlist');
//        } else {
//            // Add to wishlist
//            $user->wishlist()->create([
//                'product_id' => $this->product->id
//            ]);
//            $this->isInWishlist = true;
//            session()->flash('message', 'Added to wishlist');
//        }
//
//        // Dispatch event to update wishlist count in header
//        $this->dispatch('wishlist-updated');
//    }
//
//    public function render()
//    {
//        return view('livewire.client.wishlist-toggle');
//    }
//}


namespace App\Livewire\Client;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WishListToggle extends Component
{
    public Product $product;
    public $isInWishlist = false;
    public bool $isAdmin = false;

    /**
     * Mount the component, check admin status, and initialize wishlist status.
     */
    public function mount()
    {
        if (Auth::check()) {
            // We'll assume an `isAdmin()` method exists on your User model.
            // If you use a different method (e.g., hasRole('admin')), please adjust this line.
            $user = Auth::user();
            if (method_exists($user, 'isAdmin')) {
                $this->isAdmin = $user->isAdmin();
            }
        }

        // Now, check the wishlist status safely.
        $this->checkWishlistStatus();
    }

    /**
     * Check if the product is in the user's wishlist, but only for non-admins.
     */
    public function checkWishlistStatus()
    {
        // Only check wishlist for authenticated, non-admin users.
        if (Auth::check() && !$this->isAdmin) {
            $this->isInWishlist = Auth::user()->wishlist()
                ->where('product_id', $this->product->id)
                ->exists();
        } else {
            // Default to false for guests and admins.
            $this->isInWishlist = false;
        }
    }

    /**
     * Toggle the product's state in the wishlist.
     */
    public function toggle()
    {
        if (!Auth::check()) {
            // For guests, you might want to dispatch an event to show a login modal.
            return redirect()->route('login');
        }

        // Prevent admins from using the wishlist feature.
        if ($this->isAdmin) {
            $this->dispatch('notify', ['message' => 'Admin accounts cannot manage wishlists.', 'type' => 'info']);
            return;
        }

        $user = Auth::user();
        $existingWishlistItem = $user->wishlist()
            ->where('product_id', $this->product->id)
            ->first();

        if ($existingWishlistItem) {
            // Remove from wishlist
            $existingWishlistItem->delete();
            $this->isInWishlist = false;
            $this->dispatch('notify', ['message' => 'Removed from wishlist.', 'type' => 'success']);
        } else {
            // Add to wishlist
            $user->wishlist()->create([
                'product_id' => $this->product->id
            ]);
            $this->isInWishlist = true;
            $this->dispatch('notify', ['message' => 'Added to wishlist.', 'type' => 'success']);
        }

        // Dispatch event to update wishlist count in header
        $this->dispatch('wishlist-updated');
    }

    public function render()
    {
        return view('livewire.client.wishlist-toggle');
    }
}
