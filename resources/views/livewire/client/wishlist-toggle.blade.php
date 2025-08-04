<button
    wire:click="toggle"
    class="flex items-center space-x-2 px-4 py-2 border border-gray-300 rounded-lg hover:border-gray-400 transition-colors"
>
    <x-heroicon-m-heart class="w-5 h-5 {{ $isInWishlist ? 'text-red-500 fill-current' : 'text-gray-400' }}" />
    <span class="text-sm font-medium">
        {{ $isInWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}
    </span>
</button>
