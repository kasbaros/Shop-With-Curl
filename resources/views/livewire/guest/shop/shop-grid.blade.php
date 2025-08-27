<div class="space-y-6">
    <!-- Breadcrumbs -->
    <livewire:components.breadcrumbs />

    <div class="tf-page-title">
        <div class="container-full">
            <div class="heading text-center">Shop</div>
        </div>
    </div>

    <section class="flat-spacing-1">
        <div class="container">
            <div class="row g-4">
                <!-- Sidebar filters -->
                <div class="col-lg-3">
                    <div class="p-3 border rounded-3">
                        <h6 class="mb-3">Filters</h6>

                        <div class="mb-3">
                            <label class="form-label">Search</label>
                            <input type="text" class="form-control"
                                   placeholder="Search products..."
                                   wire:model.debounce.400ms="search">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" wire:model="selectedCategory">
                                <option value="">All</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->slug }}">{{ $cat->name }} ({{ $cat->products_count ?? 0 }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Price ({{ config('app.currency_symbol', '$') }})</label>
                            <div class="d-flex gap-2">
                                <input type="number" class="form-control" placeholder="Min" min="0" wire:model.lazy="minPrice">
                                <input type="number" class="form-control" placeholder="Max" min="0" wire:model.lazy="maxPrice">
                            </div>
                            <small class="text-muted d-block mt-1">Tip: set both min and max</small>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="inStockOnly" wire:model="inStockOnly">
                            <label class="form-check-label" for="inStockOnly">
                                In stock only
                            </label>
                        </div>

                        <button class="tf-btn btn-outline w-100" wire:click="clearFilters">Clear filters</button>
                    </div>
                </div>

                <!-- Product grid -->
                <div class="col-lg-9">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-muted">
                            Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }}
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="me-1">Sort:</span>
                            <select class="form-select" style="min-width: 220px" wire:model="sortBy">
                                <option value="popular">Popular</option>
                                <option value="newest">Date, new to old</option>
                                <option value="name">Alphabetically, A-Z</option>
                                <option value="price_low_high">Price, low to high</option>
                                <option value="price_high_low">Price, high to low</option>
                            </select>
                        </div>
                    </div>

                    @php
                        $productFallback = asset('images/placeholder-product.jpg');
                    @endphp

                    <div class="row row-cols-2 row-cols-md-3 g-4">
                        @forelse($products as $product)
{{--                            @php--}}
{{--                                $media = $product->media->first();--}}
{{--                                $img = $media && method_exists($media, 'getUrl') ? $media->getUrl() : ($media->url ?? null);--}}
{{--                                $imageUrl = $img ?: $productFallback;--}}

{{--                                $hasSale = !is_null($product->sale_price) && $product->sale_price < $product->price;--}}
{{--                                $displayPrice = $hasSale ? $product->sale_price : $product->price;--}}
{{--                                $format = fn($p) => (is_int($p) || ctype_digit((string)$p)) ? number_format($p / 100, 2) : number_format((float)$p, 2);--}}
{{--                            @endphp--}}

                            @php
                                // Use the same robust image logic as the home page for consistency.
                                $galleryPaths = is_array($product->gallery) ? $product->gallery : [];
                                $primaryPath = $galleryPaths[0] ?? $product->featured_image;
                                $imageUrl = $product->getStorageImageUrl($primaryPath);

                                // The hover image is the featured_image, falling back to the primary image if it doesn't exist.
                                $hoverPath = $product->featured_image ?? $primaryPath;
                                $hoverUrl = $product->getStorageImageUrl($hoverPath);

                                $hasSale = !is_null($product->sale_price) && $product->sale_price < $product->price;
                                $displayPrice = $hasSale ? $product->sale_price : $product->price;
                                $format = fn($p) => number_format((float)$p, 2);
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
                                            <a href="javascript:void(0);" class="box-icon bg_white wishlist btn-icon-action" wire:click.prevent="$dispatch('wishlist:toggle', {id: {{ $product->id }} })">
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
                                                <div class="compare-at-price">{{ config('app.currency_symbol', '$') }}{{ $format($product->price) }}</div>
                                                <div class="price-on-sale fw-6">{{ config('app.currency_symbol', '$') }}{{ $format($displayPrice) }}</div>
                                            @else
                                                <div class="price fw-6">{{ config('app.currency_symbol', '$') }}{{ $format($displayPrice) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <p>No products found.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4">
                        {{ $products->onEachSide(1)->links('components.pagination.tf') }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick View Modal Component -->
    <livewire:components.product-quick-view />

    <!-- Quick Add Modal Component -->
    <livewire:components.product-quick-add />

    <!-- Fixed Compare Bar (when items in comparison) -->
    <div class="position-fixed bottom-0 start-0 end-0 bg-primary text-white p-3"
         style="z-index: 1050; display: none;"
         id="compare-bar">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="icon icon-compare me-2"></i>
                    <span id="compare-count">0</span> products selected for comparison
                </div>
                <div>
                    <button class="btn btn-sm btn-outline-light me-2" onclick="Livewire.dispatch('compare:show')">
                        Compare Now
                    </button>
                    <button class="btn btn-sm btn-light" onclick="Livewire.dispatch('compare:clear')">
                        Clear
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Show/hide compare bar based on count
    document.addEventListener('livewire:init', () => {
        Livewire.on('compare:updated', (event) => {
            const count = event[0].count;
            const bar = document.getElementById('compare-bar');
            const countSpan = document.getElementById('compare-count');

            if (count > 0) {
                countSpan.textContent = count;
                bar.style.display = 'block';
            } else {
                bar.style.display = 'none';
            }
        });
    });
</script>
@endpush
