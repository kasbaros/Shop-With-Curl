<div class="space-y-6">
    <!-- Header -->
    <div class="tf-page-title">
        <div class="container-full">
            <div class="heading text-center">My Wishlist</div>
        </div>
    </div>


    @if($wishlistItems->isNotEmpty())
        <!-- Wishlist Items -->
        <section class="flat-spacing-2">
            <div class="container">
                <div class="grid-layout wrapper-shop" data-grid="grid-4">
                    @foreach($wishlistItems as $item)
                        <div class="card-product">
                            <div class="card-product-wrapper">
                                <!-- Product Image -->
                                <a href="{{ route('products.show', $item->product->slug) }}" class="product-img">
                                    <img class="img-product ls-is-cached lazyloaded"
                                         data-src="{{ $item->product->featured_image ?: asset('images/placeholder.png') }}"
                                         src="{{ $item->product->featured_image ?: asset('images/placeholder.png') }}"
                                         alt="{{ $item->product->name }}">
                                    <!-- Fallback hover image; adjust if product has variant images -->
                                    <img class="img-hover ls-is-cached lazyloaded"
                                         data-src="{{ $item->product->featured_image ?: asset('images/placeholder.png') }}"
                                         src="{{ $item->product->featured_image ?: asset('images/placeholder.png') }}"
                                         alt="{{ $item->product->name }}">
                                </a>

                                <!-- Wishlist Button -->
                                <div class="list-product-btn type-wishlist">
                                    <a href="javascript:void(0);"
                                       class="box-icon bg_white wishlist"
                                       wire:click="removeFromWishlist({{ $item->product_id }})">
                                        <span class="tooltip">Remove Wishlist</span>
                                        <span class="icon icon-delete"></span>
                                    </a>
                                </div>

                                <!-- Action Buttons -->
                                <div class="list-product-btn">
                                    <a href="#quick_add"
                                       data-bs-toggle="modal"
                                       class="box-icon bg_white quick-add tf-btn-loading"
                                       wire:click="$dispatch('product:quickAdd', {{ $item->product_id }})">
                                        <span class="icon icon-bag"></span>
                                        <span class="tooltip">Quick Add</span>
                                    </a>
                                    <a href="#quick_view"
                                       data-bs-toggle="modal"
                                       class="box-icon bg_white quickview tf-btn-loading"
                                       wire:click="$dispatch('product:quickView', {{ $item->product_id }})">
                                        <span class="icon icon-view"></span>
                                        <span class="tooltip">Quick View</span>
                                    </a>
                                </div>

                                <!-- Size List (if available) -->
                                @if($item->product->variants && $item->product->variants->pluck('size')->filter()->isNotEmpty())
                                    <div class="size-list">
                                        @foreach($item->product->variants->pluck('size')->unique() as $size)
                                            <span>{{ $size }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="size-list">
                                        <span>S</span>
                                        <span>M</span>
                                        <span>L</span>
                                        <span>XL</span>
                                    </div>
                                @endif

                                <!-- Sale Badge -->
                                @if($item->product->sale_price && $item->product->sale_price < $item->product->price)
                                    <div class="on-sale-wrap">
                                        <div class="on-sale-item">
                                            -{{ round(($item->product->price - $item->product->sale_price) / $item->product->price * 100) }}
                                            %
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div class="card-product-info">
                                <a href="{{ route('products.show', $item->product->slug) }}" class="title link">
                                    {{ $item->product->name }}
                                </a>
                                <span class="price">
                                    ${{ number_format($item->product->effective_price, 2) }}
                                    @if($item->product->sale_price && $item->product->sale_price < $item->product->price)
                                        <span class="compare-at-price text-muted text-decoration-line-through">
                                            ${{ number_format($item->product->price, 2) }}
                                        </span>
                                    @endif
                                </span>

                                <!-- Color Swatches -->
                                @if($item->product->variants && $item->product->variants->pluck('color')->filter()->isNotEmpty())
                                    <ul class="list-color-product">
                                        @foreach($item->product->variants->pluck('color')->unique() as $color)
                                            <li class="list-color-item color-swatch {{ $color === ($item->product->variants->first()->color ?? '') ? 'active' : '' }}">
                                                <span class="tooltip">{{ $color }}</span>
                                                <span
                                                    class="swatch-value bg_{{ strtolower(str_replace(' ', '-', $color)) }}"></span>
                                                <img class="ls-is-cached lazyloaded"
                                                     data-src="{{ $item->product->featured_image ?: asset('images/placeholder.png') }}"
                                                     src="{{ $item->product->featured_image ?: asset('images/placeholder.png') }}"
                                                     alt="{{ $item->product->name }}">
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <ul class="list-color-product">
                                        <li class="list-color-item color-swatch active">
                                            <span class="tooltip">Default</span>
                                            <span class="swatch-value bg_default"></span>
                                            <img class="ls-is-cached lazyloaded"
                                                 data-src="{{ $item->product->featured_image ?: asset('images/placeholder.png') }}"
                                                 src="{{ $item->product->featured_image ?: asset('images/placeholder.png') }}"
                                                 alt="{{ $item->product->name }}">
                                        </li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $wishlistItems->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <span class="icon icon-heart w-16 h-16 mx-auto text-gray-400 mb-4"></span>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Your wishlist is empty</h3>
            <p class="text-gray-500 mb-6">Start adding products you love to your wishlist!</p>
            <a href="{{ route('products.index') }}"
               class="inline-flex items-center tf-btn btn-fill px-6 py-3 rounded-lg animate-hover-btn">
                <span class="icon icon-bag mr-2"></span>
                Start Shopping
            </a>
        </div>
    @endif
</div>
