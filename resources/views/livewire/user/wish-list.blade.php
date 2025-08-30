<div>
    <!-- Header with count -->
    <div class="d-flex justify-content-between align-items-center mb_20">
        <h5 class="fw-6 m-0">My Wishlist</h5>
        <span class="text-muted small">{{ method_exists($wishlistItems, 'total') ? $wishlistItems->total() : ($wishlistItems->count() ?? 0) }} items</span>
    </div>

    @if($wishlistItems->isNotEmpty())
        <section class="flat-spacing-2">
            <div class="container p-0">
                <div class="row g-3">
                    @foreach($wishlistItems as $item)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="position-relative">
                                    <a href="{{ route('products.show', $item->product->slug) }}" class="ratio ratio-1x1 d-block bg-light rounded-top">
                                        <img class="w-100 h-100 object-fit-cover rounded-top"
                                             src="{{ \App\Helpers\ImageStorageHelper::url($item->product->featured_image) }}"
                                             alt="{{ $item->product->name }}">
                                    </a>

                                    <!-- Remove from wishlist -->
                                    <button type="button"
                                            class="btn btn-light position-absolute top-0 end-0 m-2 rounded-circle p-2"
                                            aria-label="Remove from wishlist"
                                            title="Remove from wishlist"
                                            wire:click="removeFromWishlist({{ $item->product_id }})">
                                        <span class="icon icon-delete"></span>
                                    </button>

                                    @if($item->product->sale_price && $item->product->sale_price < $item->product->price)
                                        <span class="badge bg-danger position-absolute start-0 top-0 m-2">-{{ round(($item->product->price - $item->product->sale_price) / $item->product->price * 100) }}%</span>
                                    @endif
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <a href="{{ route('products.show', $item->product->slug) }}" class="text-decoration-none text-dark mb-1 fw-6 line-clamp-2">{{ $item->product->name }}</a>

                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="fw-6">${{ number_format($item->product->effective_price, 2) }}</span>
                                            @if($item->product->sale_price && $item->product->sale_price < $item->product->price)
                                                <span class="text-muted text-decoration-line-through small">${{ number_format($item->product->price, 2) }}</span>
                                            @endif
                                        </div>
                                        @if(property_exists($item->product, 'is_in_stock') && $item->product->is_in_stock)
                                            <span class="badge bg-success-subtle text-success">In stock</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">Out of stock</span>
                                        @endif
                                    </div>

                                    @if($item->product->variants && ($item->product->variants->pluck('color')->filter()->isNotEmpty() || $item->product->variants->pluck('size')->filter()->isNotEmpty()))
                                        <div class="mb-2 d-flex flex-wrap gap-1 align-items-center">
                                            @foreach($item->product->variants->pluck('color')->unique()->take(4) as $color)
                                                @if($color)
                                                    <span class="d-inline-block rounded-circle border" style="width:14px;height:14px;background-color: {{ strtolower($color) }}" title="{{ $color }}"></span>
                                                @endif
                                            @endforeach
                                            @foreach($item->product->variants->pluck('size')->unique()->take(3) as $size)
                                                @if($size)
                                                    <span class="badge bg-light text-muted">{{ $size }}</span>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="mt-auto d-flex gap-2">
                                        <a href="{{ route('products.show', $item->product->slug) }}" class="btn btn-sm btn-outline-primary flex-grow-1">View</a>
                                        <a href="#quick_view" data-bs-toggle="modal" class="btn btn-sm btn-light"
                                           wire:click="$dispatch('product:quickView', {{ $item->product_id }})" title="Quick View" aria-label="Quick View">
                                            <span class="icon icon-view"></span>
                                        </a>
                                        <a href="#quick_add" data-bs-toggle="modal" class="btn btn-sm btn-primary"
                                           wire:click="$dispatch('product:quickAdd', {{ $item->product_id }})" title="Quick Add" aria-label="Quick Add">
                                            <span class="icon icon-bag"></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <div class="d-flex justify-content-center mt-4">
            {{ $wishlistItems->links() }}
        </div>
    @else
        <div class="text-center p-5 border rounded bg-light">
            <span class="icon icon-heart d-inline-block mb-3" style="font-size:40px"></span>
            <h6 class="fw-6 mb-2">Your wishlist is empty</h6>
            <p class="text-muted mb-3">Start adding products you love to your wishlist!</p>
            <a href="{{ route('products.index') }}" class="tf-btn btn-fill animate-hover-btn rounded-0 justify-content-center">Start Shopping</a>
        </div>
    @endif
</div>
