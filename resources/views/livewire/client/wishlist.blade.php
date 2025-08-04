<div class="space-y-6">
    <!-- Header -->
    <div class="tf-page-title">
        <div class="container-full">
            <div class="heading text-center">My wishlist</div>
        </div>
        <span class="text-sm text-gray-600">
            {{ $wishlistItems->total() }} items
        </span>
    </div>

    @if($wishlistItems->isNotEmpty())
        <!-- Wishlist Items -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($wishlistItems as $item)
                <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
                    <!-- Product Image -->
                    <div class="aspect-square bg-gray-100 relative">
                        <img
                            src="{{ $item->product->featured_image ?: '/images/placeholder.png' }}"
                            alt="{{ $item->product->name }}"
                            class="w-full h-full object-cover"
                        >

                        <!-- Remove Button -->
                        <button
                            wire:click="removeFromWishlist({{ $item->product_id }})"
                            class="absolute top-2 right-2 bg-white/80 hover:bg-white p-2 rounded-full transition-colors"
                        >
                            <x-heroicon-m-heart class="w-5 h-5 text-red-500 fill-current"/>
                        </button>
                    </div>

                    <!-- Product Info -->
                    <div class="p-4">
                        <h3 class="font-medium text-gray-900 mb-2">
                            <a href="{{ route('product.show', $item->product->slug) }}" class="hover:text-blue-600">
                                {{ $item->product->name }}
                            </a>
                        </h3>

                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-2">
                                <span class="text-lg font-bold text-gray-900">
                                    ${{ number_format($item->product->effective_price, 2) }}
                                </span>
                                @if($item->product->sale_price)
                                    <span class="text-sm text-gray-500 line-through">
                                        ${{ number_format($item->product->price, 2) }}
                                    </span>
                                @endif
                            </div>

                            @if($item->product->is_in_stock)
                                <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">
                                    In Stock
                                </span>
                            @else
                                <span class="text-xs text-red-600 bg-red-100 px-2 py-1 rounded-full">
                                    Out of Stock
                                </span>
                            @endif
                        </div>

                        <!-- Category -->
                        @if($item->product->categories->isNotEmpty())
                            <p class="text-sm text-gray-600 mb-3">
                                {{ $item->product->categories->first()->name }}
                            </p>
                        @endif

                        <!-- Actions -->
                        <div class="flex space-x-2">
                            <a
                                href="{{ route('product.show', $item->product->slug) }}"
                                class="flex-1 bg-blue-600 text-white text-center py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors text-sm"
                            >
                                View Product
                            </a>

                            @if($item->product->is_in_stock)
                                <button
                                    wire:click="toggleWishlist({{ $item->product_id }})"
                                    class="bg-gray-200 text-gray-800 px-3 py-2 rounded-lg hover:bg-gray-300 transition-colors"
                                >
                                    <x-heroicon-m-shopping-cart class="w-4 h-4"/>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $wishlistItems->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <x-heroicon-o-heart class="w-16 h-16 mx-auto text-gray-400 mb-4"/>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Your wishlist is empty</h3>
            <p class="text-gray-500 mb-6">Start adding products you love to your wishlist!</p>
            <a
                href="{{ route('products.index') }}"
                class="inline-flex items-center bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors"
            >
                <x-heroicon-m-shopping-bag class="w-5 h-5 mr-2"/>
                Start Shopping
            </a>
        </div>
    @endif
</div>
