<div class="space-y-6">
    <!-- Breadcrumbs -->
    <livewire:components.breadcrumbs />

    <div class="tf-page-title bg-body-tertiary">
        <div class="container-full">
            <div class="heading text-center">Shop</div>
        </div>
    </div>

    <section class="flat-spacing-1">
        <div class="container">
            <!-- Top controls: Filter button + count + sort -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center gap-3">
                    <a href="#filterShop" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft"
                       class="tf-btn-filter">
                        <span class="icon icon-filter"></span>
                        <span class="text">Filter</span>
                    </a>
                    <span class="text-muted">
                        {{ $products->total() }} product{{ $products->total() !== 1 ? 's' : '' }}
                    </span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="me-1">Sort:</span>
                    <select class="form-select" style="min-width: 220px" wire:model.live="sortBy">
                        <option value="popular">Popular</option>
                        <option value="newest">Date, new to old</option>
                        <option value="name">Alphabetically, A-Z</option>
                        <option value="price_low_high">Price, low to high</option>
                        <option value="price_high_low">Price, high to low</option>
                    </select>
                </div>
            </div>

            <!-- Active filter tags -->
            @if($search || $selectedCategory || $minPrice || $maxPrice || $inStockOnly)
                <div class="d-flex align-items-center gap-2 flex-wrap mb-3">
                    @if($search)
                        <a href="javascript:void(0);" class="tf-btn btn-line" wire:click="$set('search', '')">
                            Search: "{{ $search }}" <i class="icon icon-close"></i>
                        </a>
                    @endif
                    @if($selectedCategory)
                        <a href="javascript:void(0);" class="tf-btn btn-line" wire:click="$set('selectedCategory', '')">
                            {{ $categories->firstWhere('slug', $selectedCategory)?->name ?? $selectedCategory }} <i class="icon icon-close"></i>
                        </a>
                    @endif
                    @if($minPrice || $maxPrice)
                        <a href="javascript:void(0);" class="tf-btn btn-line" wire:click="$set('minPrice', ''); $set('maxPrice', '')">
                            UGX {{ $minPrice ? number_format($minPrice) : '0' }} - {{ $maxPrice ? number_format($maxPrice) : '∞' }} <i class="icon icon-close"></i>
                        </a>
                    @endif
                    @if($inStockOnly)
                        <a href="javascript:void(0);" class="tf-btn btn-line" wire:click="$set('inStockOnly', false)">
                            In stock <i class="icon icon-close"></i>
                        </a>
                    @endif
                    <a href="javascript:void(0);" class="text-danger fw-5" wire:click="clearFilters">
                        Clear all
                    </a>
                </div>
            @endif

            @php
                $productFallback = asset('images/placeholder-product.jpg');
            @endphp

            <!-- Product grid (original layout) -->
            <div class="row row-cols-2 row-cols-md-3 g-4" wire:loading.class="opacity-50">
                @forelse($products as $product)
                    @php
                        $galleryPaths = is_array($product->gallery) ? $product->gallery : [];
                        $primaryPath = $galleryPaths[0] ?? $product->featured_image;
                        $imageUrl = $product->getStorageImageUrl($primaryPath);

                        $hoverPath = $product->featured_image ?? $primaryPath;
                        $hoverUrl = $product->getStorageImageUrl($hoverPath);

                        $hasSale = !is_null($product->sale_price) && $product->sale_price < $product->price;
                        $displayPrice = $hasSale ? $product->sale_price : $product->price;
                    @endphp

                    <div class="col" wire:key="product-{{ $product->id }}">
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
                                        <div class="compare-at-price">{{ money_format_ugx($product->price) }}</div>
                                        <div class="price-on-sale fw-6">{{ money_format_ugx($displayPrice) }}</div>
                                    @else
                                        <div class="price fw-6">{{ money_format_ugx($displayPrice) }}</div>
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
    </section>

    <!-- Filter Offcanvas -->
    <div class="offcanvas offcanvas-start canvas-filter" id="filterShop" wire:ignore.self>
        <div class="canvas-wrapper">
            <header class="canvas-header">
                <div class="filter-icon">
                    <span class="icon icon-filter"></span>
                    <span>Filter</span>
                </div>
                <span class="icon-close icon-close-popup" data-bs-dismiss="offcanvas" aria-label="Close"></span>
            </header>
            <div class="canvas-body">
                <!-- Search -->
                <div class="widget-facet">
                    <div class="facet-title" data-bs-target="#filterSearch" data-bs-toggle="collapse" aria-expanded="true" aria-controls="filterSearch">
                        <span>Search</span>
                        <span class="icon icon-arrow-up"></span>
                    </div>
                    <div id="filterSearch" class="collapse show">
                        <div class="mb_36">
                            <input type="text" class="tf-input" placeholder="Search products..."
                                   wire:model.live.debounce.400ms="search">
                        </div>
                    </div>
                </div>

                <!-- Categories -->
                <div class="widget-facet wd-categories">
                    <div class="facet-title" data-bs-target="#filterCategories" data-bs-toggle="collapse" aria-expanded="true" aria-controls="filterCategories">
                        <span>Product categories</span>
                        <span class="icon icon-arrow-up"></span>
                    </div>
                    <div id="filterCategories" class="collapse show">
                        <ul class="list-categoris current-scrollbar mb_36">
                            <li class="cate-item {{ $selectedCategory === '' ? 'current' : '' }}">
                                <a href="javascript:void(0);" wire:click="$set('selectedCategory', '')"><span>All</span></a>
                            </li>
                            @foreach($categories as $cat)
                                <li class="cate-item {{ $selectedCategory === $cat->slug ? 'current' : '' }}">
                                    <a href="javascript:void(0);" wire:click="$set('selectedCategory', '{{ $cat->slug }}')">
                                        <span>{{ $cat->name }}</span>
                                        <span class="text-secondary">({{ $cat->products_count ?? 0 }})</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Availability -->
                <div class="widget-facet">
                    <div class="facet-title" data-bs-target="#filterAvailability" data-bs-toggle="collapse" aria-expanded="true" aria-controls="filterAvailability">
                        <span>Availability</span>
                        <span class="icon icon-arrow-up"></span>
                    </div>
                    <div id="filterAvailability" class="collapse show">
                        <ul class="tf-filter-group current-scrollbar mb_36">
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="checkbox" class="tf-check" id="filterInStock"
                                       wire:model.live="inStockOnly">
                                <label for="filterInStock" class="label"><span>In stock only</span></label>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Price -->
                <div class="widget-facet">
                    <div class="facet-title" data-bs-target="#filterPrice" data-bs-toggle="collapse" aria-expanded="true" aria-controls="filterPrice">
                        <span>Price (UGX)</span>
                        <span class="icon icon-arrow-up"></span>
                    </div>
                    <div id="filterPrice" class="collapse show">
                        <div class="mb_36">
                            <div class="d-flex gap-2 align-items-center">
                                <input type="number" class="tf-input" placeholder="Min" min="0"
                                       wire:model.live.debounce.500ms="minPrice" style="width: 50%;">
                                <span>-</span>
                                <input type="number" class="tf-input" placeholder="Max" min="0"
                                       wire:model.live.debounce.500ms="maxPrice" style="width: 50%;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Clear All -->
                @if($search || $selectedCategory || $minPrice || $maxPrice || $inStockOnly)
                    <div class="mt-3">
                        <button class="tf-btn btn-fill radius-60 animate-hover-btn w-100 justify-content-center" wire:click="clearFilters" data-bs-dismiss="offcanvas">
                            <span>Clear All Filters</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

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
