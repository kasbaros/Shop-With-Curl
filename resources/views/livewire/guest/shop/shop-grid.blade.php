<div>
    <!-- Page Title -->
    <div class="tf-page-title">
        <div class="container-full">
            <div class="heading text-center">Shop</div>
        </div>
    </div>

    <!-- Section Product -->
    <section class="flat-spacing-2">
        <div class="container">
            <!-- Shop Controls -->
            <div class="tf-shop-control grid-3 align-items-center">
                <div class="tf-control-filter">
                    <a href="#filterShop" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft"
                       class="tf-btn-filter">
                        <span class="icon icon-filter"></span>
                        <span class="text">Filter</span>
                    </a>
                </div>
                <ul class="tf-control-layout d-flex justify-content-center">
                    <li class="tf-view-layout-switch sw-layout-2" data-value-layout="tf-col-2">
                        <div class="item"><span class="icon icon-grid-2"></span></div>
                    </li>
                    <li class="tf-view-layout-switch sw-layout-3" data-value-layout="tf-col-3">
                        <div class="item"><span class="icon icon-grid-3"></span></div>
                    </li>
                    <li class="tf-view-layout-switch sw-layout-4 active" data-value-layout="tf-col-4">
                        <div class="item"><span class="icon icon-grid-4"></span></div>
                    </li>
                </ul>
                <div class="tf-control-sorting d-flex justify-content-end">
                    <div class="tf-dropdown-sort" data-bs-toggle="dropdown">
                        <div class="btn-select">
                            <span class="text-sort-value">
                                @switch($sortBy)
                                    @case('popular') Featured @break
                                    @case('newest') Date, new to old @break
                                    @case('name') Alphabetically, A-Z @break
                                    @case('price_low_high') Price, low to high @break
                                    @case('price_high_low') Price, high to low @break
                                    @default Featured
                                @endswitch
                            </span>
                            <span class="icon icon-arrow-down"></span>
                        </div>
                        <div class="dropdown-menu">
                            <div class="select-item {{ $sortBy === 'popular' ? 'active' : '' }}" wire:click="$set('sortBy', 'popular')">
                                <span class="text-value-item">Featured</span>
                            </div>
                            <div class="select-item {{ $sortBy === 'name' ? 'active' : '' }}" wire:click="$set('sortBy', 'name')">
                                <span class="text-value-item">Alphabetically, A-Z</span>
                            </div>
                            <div class="select-item {{ $sortBy === 'price_low_high' ? 'active' : '' }}" wire:click="$set('sortBy', 'price_low_high')">
                                <span class="text-value-item">Price, low to high</span>
                            </div>
                            <div class="select-item {{ $sortBy === 'price_high_low' ? 'active' : '' }}" wire:click="$set('sortBy', 'price_high_low')">
                                <span class="text-value-item">Price, high to low</span>
                            </div>
                            <div class="select-item {{ $sortBy === 'newest' ? 'active' : '' }}" wire:click="$set('sortBy', 'newest')">
                                <span class="text-value-item">Date, new to old</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Filters & Count -->
            <div class="wrapper-control-shop">
                <div class="meta-filter-shop d-flex align-items-center gap-10 flex-wrap">
                    <div class="count-text">
                        {{ $products->total() }} product{{ $products->total() !== 1 ? 's' : '' }}
                    </div>
                    @if($search)
                        <a href="javascript:void(0);" class="tf-btn btn-line" wire:click="$set('search', '')">
                            Search: "{{ $search }}" <i class="icon icon-close"></i>
                        </a>
                    @endif
                    @if($selectedCategory)
                        <a href="javascript:void(0);" class="tf-btn btn-line" wire:click="$set('selectedCategory', '')">
                            Category: {{ $categories->firstWhere('slug', $selectedCategory)?->name ?? $selectedCategory }} <i class="icon icon-close"></i>
                        </a>
                    @endif
                    @if($minPrice || $maxPrice)
                        <a href="javascript:void(0);" class="tf-btn btn-line" wire:click="$set('minPrice', ''); $set('maxPrice', '')">
                            Price: {{ $minPrice ? 'UGX ' . number_format($minPrice) : '0' }} - {{ $maxPrice ? 'UGX ' . number_format($maxPrice) : '∞' }} <i class="icon icon-close"></i>
                        </a>
                    @endif
                    @if($inStockOnly)
                        <a href="javascript:void(0);" class="tf-btn btn-line" wire:click="$set('inStockOnly', false)">
                            In stock <i class="icon icon-close"></i>
                        </a>
                    @endif
                    @if($search || $selectedCategory || $minPrice || $maxPrice || $inStockOnly)
                        <a href="javascript:void(0);" class="remove-all-filters" wire:click="clearFilters">
                            Remove All <i class="icon icon-close"></i>
                        </a>
                    @endif
                </div>

                <!-- Product Grid -->
                <div class="grid-layout wrapper-shop tf-col-4" id="gridLayout" wire:loading.class="opacity-50">
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

                        <div class="card-product" wire:key="product-{{ $product->id }}">
                            <div class="card-product-wrapper">
                                <a href="{{ route('products.show', $product->slug) }}" class="product-img">
                                    <img class="img-product" src="{{ $imageUrl }}" alt="{{ $product->name }}">
                                    <img class="img-hover" src="{{ $hoverUrl }}" alt="{{ $product->name }}">
                                </a>
                                @if($hasSale)
                                    @php $discount = round((($product->price - $product->sale_price) / $product->price) * 100); @endphp
                                    <div class="on-sale-wrap"><span class="on-sale-item">-{{ $discount }}%</span></div>
                                @endif
                                <div class="list-product-btn absolute-2">
                                    <a href="javascript:void(0);" class="box-icon bg_white quick-add"
                                       wire:click.prevent="$dispatch('product:quickAdd', {productId: {{ $product->id }}})">
                                        <span class="icon icon-bag"></span>
                                        <span class="tooltip">Quick Add</span>
                                    </a>
                                    <a href="javascript:void(0);" class="box-icon bg_white wishlist btn-icon-action"
                                       wire:click.prevent="$dispatch('wishlist:toggle', {id: {{ $product->id }}})">
                                        <span class="icon icon-heart"></span>
                                        <span class="tooltip">Add to Wishlist</span>
                                    </a>
                                    <a href="javascript:void(0);" class="box-icon bg_white compare btn-icon-action"
                                       wire:click.prevent="$dispatch('compare:toggle', {id: {{ $product->id }}})">
                                        <span class="icon icon-compare"></span>
                                        <span class="tooltip">Add to Compare</span>
                                    </a>
                                    <a href="javascript:void(0);" class="box-icon bg_white quickview"
                                       wire:click.prevent="$dispatch('product:quickView', {productId: {{ $product->id }}})">
                                        <span class="icon icon-view"></span>
                                        <span class="tooltip">Quick View</span>
                                    </a>
                                </div>
                            </div>
                            <div class="card-product-info">
                                <a href="{{ route('products.show', $product->slug) }}" class="title link">{{ $product->name }}</a>
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
                    @empty
                        <div class="text-center py-5 w-100" style="grid-column: 1 / -1;">
                            <p class="text-secondary">No products found matching your filters.</p>
                            <a href="javascript:void(0);" class="tf-btn btn-fill radius-60 animate-hover-btn mt-3" wire:click="clearFilters">
                                <span>Clear Filters</span>
                            </a>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="tf-pagination-wrap">
                        {{ $products->onEachSide(1)->links('components.pagination.tf') }}
                    </div>
                @endif
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
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Layout switcher
        var gridLayout = document.getElementById('gridLayout');
        document.querySelectorAll('.tf-view-layout-switch').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.tf-view-layout-switch').forEach(function(b) { b.classList.remove('active'); });
                this.classList.add('active');
                var layout = this.dataset.valueLayout;
                if (gridLayout) {
                    gridLayout.className = gridLayout.className.replace(/tf-col-\d/g, '').trim();
                    gridLayout.classList.add(layout);
                }
            });
        });

        // Sort dropdown click
        document.querySelectorAll('.tf-dropdown-sort .select-item').forEach(function(item) {
            item.addEventListener('click', function() {
                var text = this.querySelector('.text-value-item').textContent;
                var sortLabel = document.querySelector('.text-sort-value');
                if (sortLabel) sortLabel.textContent = text;
                document.querySelectorAll('.tf-dropdown-sort .select-item').forEach(function(i) { i.classList.remove('active'); });
                this.classList.add('active');
            });
        });
    });
</script>
@endpush
