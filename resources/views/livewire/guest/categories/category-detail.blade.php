<div>
    <!-- Page Title -->
    <div class="tf-page-title">
        <div class="container-full">
            <div class="row">
                <div class="col-12">
                    <div class="heading text-center">{{ $category->name }}</div>
                    <ul class="breadcrumbs d-flex align-items-center justify-content-center">
                        <li><a href="{{ route('home') }}" wire:navigate>Home</a></li>
                        <li><i class="icon-arrow-right"></i></li>
                        <li><a href="{{ route('categories.index') }}" wire:navigate>Categories</a></li>
                        @foreach($category->breadcrumbs ?? [] as $breadcrumb)
                            <li><i class="icon-arrow-right"></i></li>
                            @if(!$loop->last)
                                <li><a href="{{ $breadcrumb['url'] }}" wire:navigate>{{ $breadcrumb['name'] }}</a></li>
                            @else
                                <li>{{ $breadcrumb['name'] }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Title -->

    <!-- Subcategories Swiper -->
    @if($category->children->count() > 0 && $showSubcategories)
        <section class="flat-spacing-1 pb_0">
            <div class="container">
                <div class="flat-title">
                    <span class="title">Subcategories</span>
                </div>
                <div class="hover-sw-nav hover-sw-3">
                    <div class="swiper tf-sw-subcategories" data-preview="4" data-tablet="3" data-mobile="2"
                         data-space-lg="30" data-space-md="15">
                        <div class="swiper-wrapper">
                            @foreach($category->children as $child)
                                <div class="swiper-slide">
                                    <a href="{{ route('categories.show', $child->slug) }}" wire:navigate
                                       class="collection-item-circle has-bg hover-img">
                                        <div class="collection-image img-style">
                                            <img class="lazyload"
                                                 data-src="{{ $child->image_url ?? asset('images/category-placeholder.jpg') }}"
                                                 src="{{ $child->image_url ?? asset('images/category-placeholder.jpg') }}"
                                                 alt="{{ $child->name }}">
                                        </div>
                                        <div class="collection-content text-center">
                                            <div class="collection-title">
                                                <a href="{{ route('categories.show', $child->slug) }}"
                                                   wire:navigate class="link">{{ $child->name }}</a>
                                            </div>
                                            <div class="collection-count">
                                                {{ $child->products_count ?? 0 }} {{ Str::plural('item', $child->products_count ?? 0) }}
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="nav-sw nav-next-slider nav-next-subcategories box-icon w_46 round">
                        <span class="icon icon-arrow-left"></span>
                    </div>
                    <div class="nav-sw nav-prev-slider nav-prev-subcategories box-icon w_46 round">
                        <span class="icon icon-arrow-right"></span>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Products Section -->
    <section class="flat-spacing-1">
        <div class="container">
            <!-- Shop Controls -->
            <div class="tf-shop-control grid-3 align-items-center">
                <div class="tf-control-filter">
                    <a href="#filterShop" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft"
                       class="tf-btn-filter"><span class="icon icon-filter"></span><span class="text">Filter</span></a>
                </div>
                <ul class="tf-control-layout d-flex justify-content-center">
                    <li class="tf-view-layout-switch sw-layout-list list-layout" data-value-layout="list">
                        <div class="item"><span class="icon icon-list"></span></div>
                    </li>
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
                            <span class="text-sort-value">{{ ucfirst(str_replace('_', ' ', $sortBy ?? 'featured')) }}</span>
                            <span class="icon icon-arrow-down"></span>
                        </div>
                        <div class="dropdown-menu">
                            <div class="select-item {{ ($sortBy ?? 'featured') === 'featured' ? 'active' : '' }}"
                                 wire:click="setSortBy('featured')">
                                <span class="text-value-item">Featured</span>
                            </div>
                            <div class="select-item {{ ($sortBy ?? 'featured') === 'name_asc' ? 'active' : '' }}"
                                 wire:click="setSortBy('name_asc')">
                                <span class="text-value-item">Alphabetically, A-Z</span>
                            </div>
                            <div class="select-item {{ ($sortBy ?? 'featured') === 'name_desc' ? 'active' : '' }}"
                                 wire:click="setSortBy('name_desc')">
                                <span class="text-value-item">Alphabetically, Z-A</span>
                            </div>
                            <div class="select-item {{ ($sortBy ?? 'featured') === 'created_at_desc' ? 'active' : '' }}"
                                 wire:click="setSortBy('created_at_desc')">
                                <span class="text-value-item">Date, new to old</span>
                            </div>
                            <div class="select-item {{ ($sortBy ?? 'featured') === 'created_at_asc' ? 'active' : '' }}"
                                 wire:click="setSortBy('created_at_asc')">
                                <span class="text-value-item">Date, old to new</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="wrapper-control-shop">
                <!-- Product Count & Applied Filters -->
                <div class="meta-filter-shop">
                    <div id="product-count-grid" class="count-text">
                        @if(isset($products) && method_exists($products, 'total') && $products->total() > 0)
                            <span class="count">{{ $products->total() }}</span> Products Found
                        @elseif(isset($products) && $products->count() > 0)
                            <span class="count">{{ $products->count() }}</span> Products Found
                        @else
                            No products found
                        @endif
                    </div>
                    <div id="applied-filters">
                        @if(!empty($search))
                            <span class="filter-item">
                                Search: {{ $search }}
                                <i class="icon icon-close" wire:click="$set('search', '')" style="cursor:pointer"></i>
                            </span>
                        @endif
                    </div>
                    @if(isset($hasFilters) && $hasFilters)
                        <button id="remove-all" class="remove-all-filters" wire:click="clearFilters">
                            Remove All <i class="icon icon-close"></i>
                        </button>
                    @endif
                </div>

                <!-- List Layout -->
                <div class="tf-list-layout wrapper-shop" id="listLayout" style="display: none;">
                    @forelse($products as $product)
                        @php
                            $primary = $product->primary_image_url ?? asset('images/placeholder-product.jpg');
                            $hover = $product->hover_image_url ?? $primary;
                        @endphp
                        <div class="card-product list-layout"
                             data-availability="{{ $product->stock_quantity > 0 ? 'In stock' : 'Out of stock' }}"
                             data-brand="{{ $product->brand->name ?? 'ShopWithCarl' }}">
                            <div class="card-product-wrapper">
                                <a href="{{ route('products.show', $product->slug) }}" class="product-img">
                                    <img class="lazyload img-product" data-src="{{ $primary }}"
                                         src="{{ $primary }}" alt="{{ $product->name }}">
                                    @if($hover !== $primary)
                                        <img class="lazyload img-hover" data-src="{{ $hover }}"
                                             src="{{ $hover }}" alt="{{ $product->name }}">
                                    @endif
                                </a>
                            </div>
                            <div class="card-product-info">
                                <a href="{{ route('products.show', $product->slug) }}"
                                   class="title link">{{ $product->name }}</a>
                                @if($product->sale_price && $product->sale_price < $product->price)
                                    <span class="price current-price">{{ money_format_ugx($product->sale_price) }}</span>
                                    <span class="price compare-at-price">{{ money_format_ugx($product->price) }}</span>
                                @else
                                    <span class="price current-price">{{ money_format_ugx($product->price) }}</span>
                                @endif
                                <p class="description">{{ Str::limit($product->description, 150) }}</p>
                                @if($product->variants && $product->variants->where('color', '!=', null)->count() > 0)
                                    <ul class="list-color-product">
                                        @foreach($product->variants->whereNotNull('color')->unique('color')->take(4) as $colorVariant)
                                            <li class="list-color-item color-swatch {{ $loop->first ? 'active' : '' }}">
                                                <span class="tooltip">{{ $colorVariant->color }}</span>
                                                <span class="swatch-value"
                                                      style="background-color: {{ $this->getColorCode($colorVariant->color) }}"></span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                                @if($product->variants && $product->variants->whereNotNull('size')->count() > 0)
                                    <div class="size-list">
                                        @foreach($product->variants->whereNotNull('size')->unique('size')->take(4) as $sizeVariant)
                                            <span class="size-item">{{ $sizeVariant->size }}</span>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="list-product-btn">
                                    <a href="#quick_add" data-bs-toggle="modal"
                                       class="box-icon quick-add style-3 hover-tooltip"
                                       data-product-id="{{ $product->id }}">
                                        <span class="icon icon-bag"></span>
                                        <span class="tooltip">Quick add</span>
                                    </a>
                                    <a href="javascript:void(0)"
                                       class="box-icon wishlist style-3 hover-tooltip"
                                       wire:click="toggleWishlist({{ $product->id }})">
                                        <span class="icon icon-heart"></span>
                                        <span class="tooltip">Add to Wishlist</span>
                                    </a>
                                    <a href="#compare" data-bs-toggle="offcanvas"
                                       class="box-icon compare style-3 hover-tooltip"
                                       wire:click="toggleCompare({{ $product->id }})">
                                        <span class="icon icon-compare"></span>
                                        <span class="tooltip">Add to Compare</span>
                                    </a>
                                    <a href="#quick_view" data-bs-toggle="modal"
                                       class="box-icon quickview style-3 hover-tooltip"
                                       data-product-id="{{ $product->id }}">
                                        <span class="icon icon-view"></span>
                                        <span class="tooltip">Quick view</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="no-products text-center py-5">
                            <h3>No products found</h3>
                            <p>Try adjusting your filters or search criteria.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Grid Layout -->
                <div class="tf-grid-layout wrapper-shop tf-col-4" id="gridLayout">
                    @forelse($products as $product)
                        @php
                            $primary = $product->primary_image_url ?? asset('images/placeholder-product.jpg');
                            $hover = $product->hover_image_url ?? $primary;
                            $onSale = $product->sale_price && $product->sale_price < $product->price;
                            $discountPct = $onSale ? round((($product->price - $product->sale_price) / max($product->price, 1)) * 100) : 0;
                        @endphp
                        <div class="card-product"
                             data-availability="{{ $product->stock_quantity > 0 ? 'In stock' : 'Out of stock' }}"
                             data-brand="{{ $product->brand->name ?? 'ShopWithCarl' }}">
                            <div class="card-product-wrapper">
                                <a href="{{ route('products.show', $product->slug) }}" class="product-img">
                                    <img class="lazyload img-product" data-src="{{ $primary }}"
                                         src="{{ $primary }}" alt="{{ $product->name }}">
                                    @if($hover !== $primary)
                                        <img class="lazyload img-hover" data-src="{{ $hover }}"
                                             src="{{ $hover }}" alt="{{ $product->name }}">
                                    @endif
                                </a>
                                <div class="list-product-btn absolute-2">
                                    <a href="#quick_add" data-bs-toggle="modal"
                                       class="box-icon bg_white quick-add tf-btn-loading"
                                       data-product-id="{{ $product->id }}">
                                        <span class="icon icon-bag"></span>
                                        <span class="tooltip">Quick Add</span>
                                    </a>
                                    <a href="javascript:void(0)"
                                       class="box-icon bg_white wishlist btn-icon-action"
                                       wire:click="toggleWishlist({{ $product->id }})">
                                        <span class="icon icon-heart"></span>
                                        <span class="tooltip">Add to Wishlist</span>
                                        <span class="icon icon-delete"></span>
                                    </a>
                                    <a href="#compare" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft"
                                       class="box-icon bg_white compare btn-icon-action"
                                       wire:click="toggleCompare({{ $product->id }})">
                                        <span class="icon icon-compare"></span>
                                        <span class="tooltip">Add to Compare</span>
                                        <span class="icon icon-check"></span>
                                    </a>
                                    <a href="#quick_view" data-bs-toggle="modal"
                                       class="box-icon bg_white quickview tf-btn-loading"
                                       data-product-id="{{ $product->id }}">
                                        <span class="icon icon-view"></span>
                                        <span class="tooltip">Quick View</span>
                                    </a>
                                </div>

                                @if($product->variants && $product->variants->whereNotNull('size')->count() > 0)
                                    <div class="size-list">
                                        @foreach($product->variants->whereNotNull('size')->unique('size')->take(4) as $sizeVariant)
                                            <span class="size-item">{{ $sizeVariant->size }}</span>
                                        @endforeach
                                    </div>
                                @endif

                                @if($onSale && $discountPct > 0)
                                    <div class="on-sale-wrap text-end">
                                        <div class="on-sale-item">-{{ $discountPct }}%</div>
                                    </div>
                                @endif
                            </div>
                            <div class="card-product-info">
                                <a href="{{ route('products.show', $product->slug) }}"
                                   class="title link">{{ $product->name }}</a>
                                <div class="price-wrapper">
                                    @if($onSale)
                                        <span class="price current-price">{{ money_format_ugx($product->sale_price) }}</span>
                                        <span class="price compare-at-price">{{ money_format_ugx($product->price) }}</span>
                                    @else
                                        <span class="price current-price">{{ money_format_ugx($product->price) }}</span>
                                    @endif
                                </div>
                                @if($product->variants && $product->variants->whereNotNull('color')->count() > 0)
                                    <ul class="list-color-product">
                                        @foreach($product->variants->whereNotNull('color')->unique('color')->take(4) as $colorVariant)
                                            <li class="list-color-item color-swatch {{ $loop->first ? 'active' : '' }}">
                                                <span class="tooltip">{{ $colorVariant->color }}</span>
                                                <span class="swatch-value"
                                                      style="background-color: {{ $this->getColorCode($colorVariant->color) }}"></span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="no-products text-center py-5 col-12">
                            <h3>No products found in this category</h3>
                            <p>Try adjusting your filters or search criteria.</p>
                            <a href="{{ route('shop.index') }}" class="tf-btn btn-fill radius-3 animate-hover-btn">Continue Shopping</a>
                        </div>
                    @endforelse

                    <!-- Pagination -->
                    @if($products->hasPages())
                        <div class="col-12">
                            {{ $products->links('pagination::bootstrap-4') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Filter Offcanvas -->
    <div class="offcanvas offcanvas-start canvas-filter" id="filterShop">
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
                    <div class="facet-title" data-bs-target="#searchFilter" data-bs-toggle="collapse"
                         aria-expanded="true" aria-controls="searchFilter">
                        <span>Search</span>
                        <span class="icon icon-arrow-up"></span>
                    </div>
                    <div id="searchFilter" class="collapse show">
                        <div class="input-group mt_10 mb_36">
                            <input type="text" wire:model.live.debounce.400ms="search" class="form-control"
                                   placeholder="Search products...">
                            <button type="button" class="btn btn-outline-secondary">
                                <i class="icon icon-search"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <form wire:submit.prevent class="facet-filter-form">
                    <!-- Brands -->
                    @if(isset($brands) && $brands->isNotEmpty())
                        <div class="widget-facet">
                            <div class="facet-title" data-bs-target="#brand" data-bs-toggle="collapse"
                                 aria-expanded="true" aria-controls="brand">
                                <span>Brand</span>
                                <span class="icon icon-arrow-up"></span>
                            </div>
                            <div id="brand" class="collapse show">
                                <ul class="tf-filter-group current-scrollbar mb_36">
                                    @foreach($brands as $brand)
                                        <li class="list-item d-flex gap-12 align-items-center">
                                            <input type="checkbox" wire:model.live="selectedBrands"
                                                   value="{{ $brand->id }}" class="tf-check"
                                                   id="brand{{ $brand->id }}">
                                            <label for="brand{{ $brand->id }}" class="label">
                                                <span>{{ $brand->name }}</span>&nbsp;<span>({{ $brand->products_count ?? 0 }})</span>
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <!-- Colors -->
                    @if(isset($colors) && $colors->isNotEmpty())
                        <div class="widget-facet">
                            <div class="facet-title" data-bs-target="#color" data-bs-toggle="collapse"
                                 aria-expanded="true" aria-controls="color">
                                <span>Color</span>
                                <span class="icon icon-arrow-up"></span>
                            </div>
                            <div id="color" class="collapse show">
                                <ul class="tf-filter-group filter-color current-scrollbar mb_36">
                                    @foreach($colors as $color)
                                        <li class="list-item d-flex gap-12 align-items-center">
                                            <input type="checkbox" wire:model.live="selectedColors"
                                                   value="{{ $color->color }}"
                                                   class="tf-check-color"
                                                   style="background-color: {{ $this->getColorCode($color->name) }}"
                                                   id="color{{ $loop->index }}">
                                            <label for="color{{ $loop->index }}" class="label">
                                                <span>{{ $color->name }}</span>&nbsp;<span>({{ $color->products_count ?? 0 }})</span>
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <!-- Sizes -->
                    @if(isset($sizes) && $sizes->isNotEmpty())
                        <div class="widget-facet">
                            <div class="facet-title" data-bs-target="#size" data-bs-toggle="collapse"
                                 aria-expanded="true" aria-controls="size">
                                <span>Size</span>
                                <span class="icon icon-arrow-up"></span>
                            </div>
                            <div id="size" class="collapse show">
                                <ul class="tf-filter-group current-scrollbar mb_36">
                                    @foreach($sizes as $size)
                                        <li class="list-item d-flex gap-12 align-items-center">
                                            <input type="checkbox" wire:model.live="selectedSizes"
                                                   value="{{ $size->value }}"
                                                   class="tf-check" id="size{{ $loop->index }}">
                                            <label for="size{{ $loop->index }}" class="label">
                                                <span>{{ $size->value }}</span>&nbsp;<span>({{ $size->products_count ?? 0 }})</span>
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </form>

                @if(isset($hasFilters) && $hasFilters)
                    <div class="mt_20 d-flex gap-10">
                        <button class="tf-btn btn-fill" wire:click="clearFilters"
                                data-bs-dismiss="offcanvas">Remove All</button>
                        <button class="tf-btn" data-bs-dismiss="offcanvas">Done</button>
                    </div>
                @else
                    <div class="mt_20">
                        <button class="tf-btn" data-bs-dismiss="offcanvas">Done</button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Initialize subcategories swiper
                var subcatEl = document.querySelector('.tf-sw-subcategories');
                if (subcatEl) {
                    new Swiper('.tf-sw-subcategories', {
                        slidesPerView: 2,
                        spaceBetween: 15,
                        navigation: {
                            nextEl: '.nav-next-subcategories',
                            prevEl: '.nav-prev-subcategories',
                        },
                        breakpoints: {
                            640: { slidesPerView: 2, spaceBetween: 15 },
                            768: { slidesPerView: 3, spaceBetween: 20 },
                            1024: { slidesPerView: 4, spaceBetween: 30 }
                        }
                    });
                }
            });
        </script>

        <style>
            .filter-item {
                display: inline-block;
                background: #f8f9fa;
                padding: 5px 10px;
                border-radius: 15px;
                margin-right: 10px;
                font-size: 12px;
            }
            .filter-item .icon-close {
                margin-left: 5px;
                cursor: pointer;
            }
            .price-wrapper {
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .compare-at-price {
                text-decoration: line-through;
                opacity: 0.7;
            }
        </style>
    @endpush
</div>
