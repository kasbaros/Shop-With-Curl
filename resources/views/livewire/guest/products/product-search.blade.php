<div>
    <!-- Breadcrumbs -->
    <livewire:components.breadcrumbs />

    <div class="tf-page-title">
        <div class="container-full">
            <div class="heading text-center">Search Results</div>
            <p class="text-center text-muted mt-2">
                @if($query)
                    Showing results for "<strong>{{ $query }}</strong>"
                @else
                    Enter a search term to find products
                @endif
            </p>
        </div>
    </div>

    <section class="flat-spacing-1">
        <div class="container">
            {{-- Search input --}}
            <div class="row justify-content-center mb-4">
                <div class="col-lg-6">
                    <div class="position-relative">
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="query"
                            placeholder="Search products..."
                            class="form-control form-control-lg ps-5"
                            autofocus
                        >
                        <i class="icon icon-search position-absolute" style="left: 16px; top: 50%; transform: translateY(-50%); font-size: 18px; color: #999;"></i>
                        @if($query)
                            <button
                                wire:click="$set('query', '')"
                                class="btn position-absolute border-0 bg-transparent"
                                style="right: 8px; top: 50%; transform: translateY(-50%);"
                            >
                                <i class="icon icon-close" style="font-size: 12px; color: #999;"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            @if(strlen($query) >= 2)
                @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator && $products->total() > 0)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-muted">
                            Showing {{ $products->firstItem() }}&ndash;{{ $products->lastItem() }} of {{ $products->total() }} results
                        </div>
                    </div>

                    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
                        @foreach($products as $product)
                            @php
                                $galleryPaths = is_array($product->gallery) ? $product->gallery : [];
                                $primaryPath = $galleryPaths[0] ?? $product->featured_image;
                                $imageUrl = $product->getStorageImageUrl($primaryPath);

                                $hoverPath = $product->featured_image ?? $primaryPath;
                                $hoverUrl = $product->getStorageImageUrl($hoverPath);

                                $hasSale = !is_null($product->sale_price) && $product->sale_price < $product->price;
                                $displayPrice = $hasSale ? $product->sale_price : $product->price;
                            @endphp

                            <div class="col">
                                <div class="card-product">
                                    <div class="card-product-wrapper">
                                        <a href="{{ route('products.show', $product->slug) }}" class="product-img">
                                            <img class="img-product" src="{{ $imageUrl }}" alt="{{ $product->name }}">
                                            <img class="img-hover" src="{{ $hoverUrl }}" alt="{{ $product->name }}">
                                        </a>
                                        <div class="list-product-btn absolute-2">
                                            <a href="javascript:void(0);"
                                               class="box-icon bg_white quick-add"
                                               wire:click.prevent="$dispatch('product:quickAdd', {productId: {{ $product->id }}})">
                                                <span class="icon icon-bag"></span>
                                                <span class="tooltip">Quick Add</span>
                                            </a>
                                            <a href="javascript:void(0);"
                                               class="box-icon bg_white wishlist btn-icon-action"
                                               wire:click.prevent="$dispatch('wishlist:toggle', {id: {{ $product->id }}})">
                                                <span class="icon icon-heart"></span>
                                                <span class="tooltip">Add to Wishlist</span>
                                                <span class="icon icon-delete"></span>
                                            </a>
                                            <a href="javascript:void(0);"
                                               class="box-icon bg_white quickview"
                                               wire:click.prevent="$dispatch('product:quickView', {productId: {{ $product->id }}})">
                                                <span class="icon icon-view"></span>
                                                <span class="tooltip">Quick View</span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-product-info">
                                        <a href="{{ route('products.show', $product->slug) }}" class="title link">
                                            {{ $product->name }}
                                        </a>
                                        <div class="tf-product-info-price">
                                            @if($hasSale)
                                                <div class="compare-at-price">{{ money_format_ugx($product->price) }}</div>
                                                <div class="price-on-sale fw-6">{{ money_format_ugx($displayPrice) }}</div>
                                            @else
                                                <div class="price fw-6">{{ money_format_ugx($displayPrice) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="mt-4">
                            {{ $products->onEachSide(1)->links('components.pagination.tf') }}
                        </div>
                    @endif

                @else
                    <div class="text-center py-5">
                        <i class="icon icon-search d-block mx-auto mb-3" style="font-size: 48px; color: #ccc;"></i>
                        <h5 class="text-muted">No products found for "{{ $query }}"</h5>
                        <p class="text-muted">Try a different search term or browse our <a href="{{ route('shop.index') }}">shop</a>.</p>
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="icon icon-search d-block mx-auto mb-3" style="font-size: 48px; color: #ccc;"></i>
                    <p class="text-muted">Type at least 2 characters to search</p>
                </div>
            @endif
        </div>
    </section>
</div>
