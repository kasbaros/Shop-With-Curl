<a href="javascript:void(0)"
   wire:click="toggle"
   class="tf-product-btn-wishlist hover-tooltip box-icon bg_white wishlist btn-icon-action">
    <span class="icon icon-heart{{ $isInWishlist ? ' text-danger' : '' }}"></span>
    <span class="tooltip">{{ $isInWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}</span>
    <span class="icon icon-delete"></span>
</a>
