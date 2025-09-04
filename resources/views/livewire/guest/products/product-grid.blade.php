<div>
    <!-- page-title -->
    <div class="tf-page-title">
        <div class="container-full">
            <div class="heading text-center">All Products</div>
            <p class="text-center text-2 text_black-2 mt_5">Shop through our latest selection of Fashion</p>
        </div>
    </div>
    <!-- /page-title -->

    <!-- Section Product -->
    <section class="flat-spacing-2">
        <div class="container">
            <div class="tf-shop-control grid-3 align-items-center">
                <div class="tf-control-filter ">
                    <a href="#filterShop" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft"
                       class="tf-btn-filter border border-1 border-black">
                        <span class="icon icon-filter"></span>
                        <span class="text">Filter</span>
                    </a>
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
                    <li class="tf-view-layout-switch sw-layout-5" data-value-layout="tf-col-5">
                        <div class="item"><span class="icon icon-grid-5"></span></div>
                    </li>
                    <li class="tf-view-layout-switch sw-layout-6" data-value-layout="tf-col-6">
                        <div class="item"><span class="icon icon-grid-6"></span></div>
                    </li>
                </ul>
                <div class="tf-control-sorting d-flex justify-content-end">
                    <div class="tf-dropdown-sort border border-1 border-black" data-bs-toggle="dropdown">
                        <div class="btn-select">
                            <span class="text-sort-value">{{ $sortOptions[$sortBy] ?? 'Featured' }}</span>
                            <span class="icon icon-arrow-down"></span>
                        </div>
                        <div class="dropdown-menu">
                            <div class="select-item {{ $sortBy == 'featured' ? 'active' : '' }}"
                                 wire:click="$set('sortBy', 'featured')">
                                <span class="text-value-item">Featured</span>
                            </div>
                            <div class="select-item {{ $sortBy == 'name' ? 'active' : '' }}"
                                 wire:click="$set('sortBy', 'name')">
                                <span class="text-value-item">Best selling</span>
                            </div>
                            <div class="select-item {{ $sortBy == 'name_asc' ? 'active' : '' }}"
                                 wire:click="$set('sortBy', 'name_asc')">
                                <span class="text-value-item">Alphabetically, A-Z</span>
                            </div>
                            <div class="select-item {{ $sortBy == 'name_desc' ? 'active' : '' }}"
                                 wire:click="$set('sortBy', 'name_desc')">
                                <span class="text-value-item">Alphabetically, Z-A</span>
                            </div>
                            <div class="select-item {{ $sortBy == 'price_asc' ? 'active' : '' }}"
                                 wire:click="$set('sortBy', 'price_asc')">
                                <span class="text-value-item">Price, low to high</span>
                            </div>
                            <div class="select-item {{ $sortBy == 'price_desc' ? 'active' : '' }}"
                                 wire:click="$set('sortBy', 'price_desc')">
                                <span class="text-value-item">Price, high to low</span>
                            </div>
                            <div class="select-item {{ $sortBy == 'created_asc' ? 'active' : '' }}"
                                 wire:click="$set('sortBy', 'created_asc')">
                                <span class="text-value-item">Date, old to new</span>
                            </div>
                            <div class="select-item {{ $sortBy == 'created_desc' ? 'active' : '' }}"
                                 wire:click="$set('sortBy', 'created_desc')">
                                <span class="text-value-item">Date, new to old</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="wrapper-control-shop">
                <div class="meta-filter-shop">
                    <div id="product-count-grid" class="count-text">
                        <span class="count">{{ $products->total() }}</span> Products Found
                    </div>
                    <div id="product-count-list" class="count-text">
                        <span class="count">{{ $products->total() }}</span> Products Found
                    </div>
                    <div id="applied-filters">
                        @if(isset($selectedCategory) && $selectedCategory)
                            <span class="filter-item">
                                Category: {{ $categories->find($selectedCategory)?->name }}
                                <i class="icon-close" wire:click="$set('selectedCategory', '')"></i>
                            </span>
                        @endif
                        @if((isset($minPrice) && $minPrice) || (isset($maxPrice) && $maxPrice))
                            <span class="filter-item">
                                Price: ${{ $minPrice ?? 0 }} - ${{ $maxPrice ?? 1000 }}
                                <i class="icon-close" wire:click="clearPriceFilter"></i>
                            </span>
                        @endif
                        @if(isset($inStockOnly) && $inStockOnly)
                            <span class="filter-item">
                                In Stock Only
                                <i class="icon-close" wire:click="$set('inStockOnly', false)"></i>
                            </span>
                        @endif
                        @if(isset($onSaleOnly) && $onSaleOnly)
                            <span class="filter-item">
                                On Sale
                                <i class="icon-close" wire:click="$set('onSaleOnly', false)"></i>
                            </span>
                        @endif
                        @if(isset($featuredOnly) && $featuredOnly)
                            <span class="filter-item">
                                Featured
                                <i class="icon-close" wire:click="$set('featuredOnly', false)"></i>
                            </span>
                        @endif
                    </div>
                    @if((isset($selectedCategory) && $selectedCategory) ||
                        (isset($minPrice) && $minPrice) ||
                        (isset($maxPrice) && $maxPrice) ||
                        (isset($inStockOnly) && $inStockOnly) ||
                        (isset($onSaleOnly) && $onSaleOnly) ||
                        (isset($featuredOnly) && $featuredOnly))
                        <button id="remove-all" class="remove-all-filters" wire:click="clearFilters">
                            Remove All <i class="icon icon-close"></i>
                        </button>
                    @endif
                </div>

                <!-- List Layout -->
                <div class="tf-list-layout wrapper-shop" id="listLayout" style="display: none;">
                    @forelse($products as $product)
                        <div class="card-product list-layout"
                             data-availability="{{ $product->stock_quantity > 0 ? 'In stock' : 'Out of stock' }}"
                             data-brand="{{ $product->brand ?? 'ShopWithCarl' }}">
                            <div class="card-product-wrapper">
                                <a href="{{ route('products.show', $product->slug) }}" class="product-img">
                                    @php
                                        $primary = $product->getFirstMediaUrl('images', 'large') ?: $product->getFirstMediaUrl('images');
                                        $hover = $product->getMedia('images')->get(1)?->getUrl('large') ?? $primary;
                                    @endphp
                                    @if($primary)
                                        <img class="img-product lazyload"
                                             data-src="{{ $primary }}"
                                             src="{{ $primary }}"
                                             alt="{{ $product->name }}">
                                        @if($hover && $hover !== $primary)
                                            <img class="img-hover lazyload"
                                                 data-src="{{ $hover }}"
                                                 src="{{ $hover }}"
                                                 alt="{{ $product->name }}">
                                        @endif
                                    @else
                                        <img class="img-product" src="{{ asset('images/placeholder-product.jpg') }}"
                                             alt="{{ $product->name }}">
                                    @endif
                                </a>
                                @if($product->sale_price && $product->sale_price < $product->price)
                                    <div class="countdown-box">
                                        <!-- Add countdown if needed -->
                                    </div>
                                @endif
                            </div>
                            <div class="card-product-info">
                                <a href="{{ route('products.show', $product->slug) }}"
                                   class="title link">{{ $product->name }}</a>
                                <div class="price-wrapper">
                                    @if($product->sale_price && $product->sale_price < $product->price)
                                        <span
                                            class="price current-price">${{ number_format($product->sale_price, 2) }}</span>
                                        <span
                                            class="price compare-at-price">${{ number_format($product->price, 2) }}</span>
                                    @else
                                        <span
                                            class="price current-price">${{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                <p class="description">{{ Str::limit($product->description, 150) }}</p>

                                @if($product->variants->where('type', 'color')->count() > 0)
                                    <ul class="list-color-product">
                                        @foreach($product->variants->where('type', 'color')->take(3) as $colorVariant)
                                            <li class="list-color-item color-swatch {{ $loop->first ? 'active' : '' }}">
                                                <span class="tooltip">{{ $colorVariant->value }}</span>
                                                <span class="swatch-value"
                                                      style="background-color: {{ $this->getColorCode($colorVariant->value) }}"></span>
                                                @if($colorVariant->image)
                                                    <img class="lazyload" data-src="{{ $colorVariant->image }}"
                                                         src="{{ $colorVariant->image }}" alt="{{ $product->name }}">
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif

                                @if($product->variants->where('type', 'size')->count() > 0)
                                    <div class="size-list">
                                        @foreach($product->variants->where('type', 'size')->take(4) as $sizeVariant)
                                            <span class="size-item">{{ $sizeVariant->value }}</span>
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
                        <div class="card-product grid"
                             data-availability="{{ $product->stock_quantity > 0 ? 'In stock' : 'Out of stock' }}"
                             data-brand="{{ $product->brand ?? 'ShopWithCarl' }}">
                            <div class="card-product-wrapper">
                                <a href="{{ route('products.show', $product->slug) }}" class="product-img">
                                    @php
                                        $primary = $product->getFirstMediaUrl('images', 'large') ?: $product->getFirstMediaUrl('images');
                                        $hover = $product->getMedia('images')->get(1)?->getUrl('large') ?? $primary;
                                    @endphp
                                    @if($primary)
                                        <img class="img-product lazyload"
                                             data-src="{{ $primary }}"
                                             src="{{ $primary }}"
                                             alt="{{ $product->name }}">
                                        @if($hover && $hover !== $primary)
                                            <img class="img-hover lazyload"
                                                 data-src="{{ $hover }}"
                                                 src="{{ $hover }}"
                                                 alt="{{ $product->name }}">
                                        @endif
                                    @else
                                        <img class="img-product" src="{{ asset('images/placeholder-product.jpg') }}"
                                             alt="{{ $product->name }}">
                                    @endif
                                </a>
                                <div
                                    class="list-product-btn {{ $product->variants->where('type', 'size')->count() > 0 ? '' : 'absolute-2' }}">
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

                                @if($product->variants->where('type', 'size')->count() > 0)
                                    <div class="size-list">
                                        @foreach($product->variants->where('type', 'size')->take(4) as $sizeVariant)
                                            <span class="size-item">{{ $sizeVariant->value }}</span>
                                        @endforeach
                                    </div>
                                @endif

                                @if($product->sale_price && $product->sale_price < $product->price)
                                    <div class="countdown-box">
                                        <!-- Add countdown if needed -->
                                    </div>
                                    <div class="on-sale-wrap text-end">
                                        <div class="on-sale-item">
                                            -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}
                                            %
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="card-product-info">
                                <a href="{{ route('products.show', $product->slug) }}"
                                   class="title link">{{ $product->name }}</a>
                                <div class="price-wrapper">
                                    @if($product->sale_price && $product->sale_price < $product->price)
                                        <span
                                            class="price current-price">${{ number_format($product->sale_price, 2) }}</span>
                                        <span
                                            class="price compare-at-price">${{ number_format($product->price, 2) }}</span>
                                    @else
                                        <span
                                            class="price current-price">${{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>

                                @if($product->variants->where('type', 'color')->count() > 0)
                                    <ul class="list-color-product">
                                        @foreach($product->variants->where('type', 'color')->take(3) as $colorVariant)
                                            <li class="list-color-item color-swatch {{ $loop->first ? 'active' : '' }}">
                                                <span class="tooltip">{{ $colorVariant->value }}</span>
                                                <span class="swatch-value"
                                                      style="background-color: {{ $this->getColorCode($colorVariant->value) }}"></span>
                                                @if($colorVariant->image)
                                                    <img class="lazyload" data-src="{{ $colorVariant->image }}"
                                                         src="{{ $colorVariant->image }}" alt="{{ $product->name }}">
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="no-products text-center py-5 col-12">
                            <h3>No products found</h3>
                            <p>Try adjusting your filters or search criteria.</p>
                        </div>
                    @endforelse

                    <!-- Pagination -->
                    @if($products->hasPages())
                        <div class="col-12">
                            {{ $products->links('pagination::bootstrap-4', ['paginator' => $products]) }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Filter Offcanvas from template, wired to Livewire -->
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
                <!-- Categories -->
                <div class="widget-facet wd-categories">
                    <div class="facet-title" data-bs-target="#categories" data-bs-toggle="collapse" aria-expanded="true"
                         aria-controls="categories">
                        <span>Product categories</span>
                        <span class="icon icon-arrow-up"></span>
                    </div>
                    <div id="categories" class="collapse show">
                        <ul class="list-categoris current-scrollbar mb_36">
                            <li class="cate-item {{ empty($selectedCategory) ? 'current' : '' }}">
                                <a href="javascript:void(0)"
                                   wire:click="$set('selectedCategory','')"><span>All</span></a>
                            </li>
                            @foreach($categories as $category)
                                <!-- Parent Category -->
                                <li class="cate-item {{ (string)$selectedCategory === (string)$category->id ? 'current' : '' }}">
                                    <a href="javascript:void(0)"
                                       wire:click="$set('selectedCategory','{{ $category->id }}')">
                                        <span>{{ $category->name }}</span>&nbsp;<span>({{ $category->products_count ?? 0 }})</span>
                                    </a>
                                </li>
                                <!-- Subcategories -->
                                @if($category->children && $category->children->count() > 0)
                                    @foreach($category->children as $subcategory)
                                        <li class="cate-item subcategory {{ (string)$selectedCategory === (string)$subcategory->id ? 'current' : '' }}" style="padding-left: 20px;">
                                            <a href="javascript:void(0)"
                                               wire:click="$set('selectedCategory','{{ $subcategory->id }}')">
                                                <span>{{ $subcategory->name }}</span>&nbsp;<span>({{ $subcategory->products_count ?? 0 }})</span>
                                            </a>
                                        </li>
                                    @endforeach
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Availability & Flags -->
                <div class="widget-facet">
                    <div class="facet-title" data-bs-target="#availability" data-bs-toggle="collapse"
                         aria-expanded="true" aria-controls="availability">
                        <span>Options</span>
                        <span class="icon icon-arrow-up"></span>
                    </div>
                    <div id="availability" class="collapse show">
                        <ul class="tf-filter-group current-scrollbar mb_36">
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="checkbox" class="tf-check" id="inStockOnly" wire:model.live="inStockOnly">
                                <label for="inStockOnly" class="label"><span>In stock only</span></label>
                            </li>
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="checkbox" class="tf-check" id="onSaleOnly" wire:model.live="onSaleOnly">
                                <label for="onSaleOnly" class="label"><span>On sale</span></label>
                            </li>
                            <li class="list-item d-flex gap-12 align-items-center">
                                <input type="checkbox" class="tf-check" id="featuredOnly"
                                       wire:model.live="featuredOnly">
                                <label for="featuredOnly" class="label"><span>Featured</span></label>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Price -->
                <div class="widget-facet">
                    <div class="facet-title" data-bs-target="#price" data-bs-toggle="collapse" aria-expanded="true"
                         aria-controls="price">
                        <span>Price</span>
                        <span class="icon icon-arrow-up"></span>
                    </div>
                    <div id="price" class="collapse show">
                        <div class="widget-price filter-price">
                            <div class="box-title-price">
                                <span class="title-price">Price :</span>
                                <div class="caption-price d-flex align-items-center gap-2">
                                    <input type="number" class="form-control" placeholder="Min"
                                           wire:model.live="minPrice" style="max-width: 120px;"/>
                                    <span>-</span>
                                    <input type="number" class="form-control" placeholder="Max"
                                           wire:model.live="maxPrice" style="max-width: 120px;"/>
                                </div>
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-outline-secondary" wire:click="clearPriceFilter">Clear
                                        price
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-10">
                    <button class="tf-btn btn-fill" wire:click="clearFilters">Remove All</button>
                    <button class="tf-btn" data-bs-dismiss="offcanvas">Done</button>
                </div>
            </div>
        </div>
    </div>

    <!-- /Section Product -->
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
</div>
