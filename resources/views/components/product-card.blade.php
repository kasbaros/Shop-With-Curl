@props(['product'])

<div class="bg-white rounded-lg shadow-sm border overflow-hidden hover:shadow-md transition-shadow">
    <div class="aspect-square bg-gray-100 relative">
        <a href="{{ route('product.show', $product->slug) }}">
            <img
                src="{{ $product->featured_image ?: '/images/placeholder.png' }}"
                alt="{{ $product->name }}"
                class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
            >
        </a>

        <!-- Sale Badge -->
        @if($product->sale_price)
            <span class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                {{ $product->discount_percentage }}% OFF
            </span>
        @endif

        <!-- Wishlist Button -->
        @auth
            <button
                wire:click="$dispatch('toggle-wishlist', { productId: {{ $product->id }} })"
                class="absolute top-2 right-2 bg-white/80 hover:bg-white p-2 rounded-full transition-colors"
            >
                <x-heroicon-m-heart class="w-5 h-5 text-gray-400 hover:text-red-500" />
            </button>
        @endauth
    </div>

    <div class="p-4">
        <!-- Product Name -->
        <h3 class="font-medium text-gray-900 mb-2">
            <a href="{{ route('product.show', $product->slug) }}" class="hover:text-blue-600">
                {{ $product->name }}
            </a>
        </h3>

        <!-- Category -->
        @if($product->categories->isNotEmpty())
            <p class="text-sm text-gray-600 mb-2">
                {{ $product->categories->first()->name }}
            </p>
        @endif

        <!-- Rating -->
        @if($product->reviews_count > 0)
            <div class="flex items-center space-x-1 mb-2">
                <div class="flex items-center">
                    @for($i = 1; $i <= 5; $i++)
                        <x-heroicon-s-star class="w-4 h-4 {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" />
                    @endfor
                </div>
                <span class="text-sm text-gray-600">
                    ({{ $product->reviews_count }})
                </span>
            </div>
        @endif

        <!-- Price -->
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center space-x-2">
                <span class="text-lg font-bold text-gray-900">
                    ${{ number_format($product->effective_price, 2) }}
                </span>
                @if($product->sale_price)
                    <span class="text-sm text-gray-500 line-through">
                        ${{ number_format($product->price, 2) }}
                    </span>
                @endif
            </div>

            @if($product->is_in_stock)
                <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">
                    In Stock
                </span>
            @else
                <span class="text-xs text-red-600 bg-red-100 px-2 py-1 rounded-full">
                    Out of Stock
                </span>
            @endif
        </div>

        <!-- Quick Add to Cart -->
        <button
            wire:click="$dispatch('quick-add-to-cart', { productId: {{ $product->id }} })"
            class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors
                   {{ !$product->is_in_stock ? 'opacity-50 cursor-not-allowed' : '' }}"
            {{ !$product->is_in_stock ? 'disabled' : '' }}
        >
            @if($product->is_in_stock)
                Quick Add to Cart
            @else
                Out of Stock
            @endif
        </button>
    </div>
</div>
