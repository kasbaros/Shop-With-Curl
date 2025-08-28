<div>
    <!-- Breadcrumbs -->
    <livewire:components.breadcrumbs/>

    <!-- page-title -->
    <div class="tf-page-title">
        <div class="container-full">
            <div class="row">
                <div class="col-12">
                    <div class="heading text-center">Categories</div>
                    <p class="text-center text-2 text_black-2 mt_5">Shop through our latest selection of categories</p>
                </div>
            </div>
        </div>
    </div>
    <!-- /page-title -->

    <section class="flat-spacing-1">
        <div class="container">
            <div class="tf-shop-control grid-3 align-items-center">
                <div class="tf-control-filter">
                    <a href="#filterShop" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft"
                       class="tf-btn-filter"><span class="icon icon-filter"></span><span class="text">Filter</span></a>
                </div>
                <ul class="tf-control-layout d-flex justify-content-center">
                    <li class="tf-view-layout-switch sw-layout-list list-layout {{ $layout === 'list' ? 'active' : '' }}"
                        data-value-layout="list" wire:click="setLayout('list')">
                        <div class="item"><span class="icon icon-list"></span></div>
                    </li>
                    <li class="tf-view-layout-switch sw-layout-2 {{ $layout === 'grid-2' ? 'active' : '' }}"
                        data-value-layout="tf-col-2" wire:click="setLayout('grid-2')">
                        <div class="item"><span class="icon icon-grid-2"></span></div>
                    </li>
                    <li class="tf-view-layout-switch sw-layout-3 {{ $layout === 'grid-3' ? 'active' : '' }}"
                        data-value-layout="tf-col-3" wire:click="setLayout('grid-3')">
                        <div class="item"><span class="icon icon-grid-3"></span></div>
                    </li>
                    <li class="tf-view-layout-switch sw-layout-4 {{ $layout === 'grid-4' ? 'active' : '' }}"
                        data-value-layout="tf-col-4" wire:click="setLayout('grid-4')">
                        <div class="item"><span class="icon icon-grid-4"></span></div>
                    </li>
                </ul>
                <div class="tf-control-sorting d-flex justify-content-end">
                    <div class="tf-dropdown-sort" data-bs-toggle="dropdown">
                        <div class="btn-select">
                            <span class="text-sort-value">{{ ucfirst(str_replace('_', ' ', $sortBy)) }}</span>
                            <span class="icon icon-arrow-down"></span>
                        </div>
                        <div class="dropdown-menu">
                            <div class="select-item {{ $sortBy === 'featured' ? 'active' : '' }}"
                                 wire:click="setSortBy('featured')">
                                <span class="text-value-item">Featured</span>
                            </div>
                            <div class="select-item {{ $sortBy === 'best_selling' ? 'active' : '' }}"
                                 wire:click="setSortBy('best_selling')">
                                <span class="text-value-item">Best selling</span>
                            </div>
                            <div class="select-item {{ $sortBy === 'name_asc' ? 'active' : '' }}"
                                 wire:click="setSortBy('name_asc')">
                                <span class="text-value-item">Alphabetically, A-Z</span>
                            </div>
                            <div class="select-item {{ $sortBy === 'name_desc' ? 'active' : '' }}"
                                 wire:click="setSortBy('name_desc')">
                                <span class="text-value-item">Alphabetically, Z-A</span>
                            </div>
                            <div class="select-item {{ $sortBy === 'created_at_asc' ? 'active' : '' }}"
                                 wire:click="setSortBy('created_at_asc')">
                                <span class="text-value-item">Date, old to new</span>
                            </div>
                            <div class="select-item {{ $sortBy === 'created_at_desc' ? 'active' : '' }}"
                                 wire:click="setSortBy('created_at_desc')">
                                <span class="text-value-item">Date, new to old</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tf-row-flex">
                <div class="tf-shop-sidebar sidebar-filter canvas-filter left">
                    <div class="canvas-wrapper">
                        <div class="canvas-header d-flex d-xl-none">
                            <div class="filter-icon">
                                <span class="icon icon-filter"></span>
                                <span>Filter</span>
                            </div>
                            <span class="icon-close icon-close-popup close-filter"></span>
                        </div>
                        <div class="canvas-body">
                            <div class="widget-facet wd-categories">
                                <div class="facet-title" data-bs-target="#categories" data-bs-toggle="collapse"
                                     aria-expanded="true" aria-controls="categories">
                                    <span>Category types</span>
                                    <span class="icon icon-arrow-up"></span>
                                </div>
                                <div id="categories" class="collapse show">
                                    <ul class="list-categoris current-scrollbar mb_36">
                                        <li class="cate-item {{ empty($selectedType) ? 'current' : '' }}">
                                            <a href="#" wire:click.prevent="setType('')"><span>All Categories</span></a>
                                        </li>
                                        <li class="cate-item {{ $selectedType === 'style' ? 'current' : '' }}">
                                            <a href="#" wire:click.prevent="setType('style')"><span>By Style</span></a>
                                        </li>
                                        <li class="cate-item {{ $selectedType === 'occasion' ? 'current' : '' }}">
                                            <a href="#"
                                               wire:click.prevent="setType('occasion')"><span>By Occasion</span></a>
                                        </li>
                                        <li class="cate-item {{ $selectedType === 'collection' ? 'current' : '' }}">
                                            <a href="#"
                                               wire:click.prevent="setType('collection')"><span>Collections</span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <form wire:submit.prevent class="facet-filter-form">
                                <div class="widget-facet">
                                    <div class="facet-title" data-bs-target="#availability"
                                         data-bs-toggle="collapse" aria-expanded="true" aria-controls="availability">
                                        <span>Availability</span>
                                        <span class="icon icon-arrow-up"></span>
                                    </div>
                                    <div id="availability" class="collapse show">
                                        <ul class="tf-filter-group current-scrollbar mb_36">
                                            <li class="list-item d-flex gap-12 align-items-center">
                                                <input type="radio" wire:model.live="availability" value="all"
                                                       class="tf-check" id="allAvailable">
                                                <label for="allAvailable"
                                                       class="label"><span>All categories</span></label>
                                            </li>
                                            <li class="list-item d-flex gap-12 align-items-center">
                                                <input type="radio" wire:model.live="availability" value="with_products"
                                                       class="tf-check" id="withProducts">
                                                <label for="withProducts"
                                                       class="label"><span>With products</span></label>
                                            </li>
                                            <li class="list-item d-flex gap-12 align-items-center">
                                                <input type="radio" wire:model.live="availability" value="empty"
                                                       class="tf-check" id="emptyCategories">
                                                <label for="emptyCategories" class="label"><span>Empty categories</span></label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
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
                                                               value="{{ $color->value }}"
                                                               class="tf-check-color"
                                                               style="background-color: {{ $color->value }}"
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
                                @if(isset($sizes) && $sizes->isNotEmpty())
                                    <div class="widget-facet">
                                        <div class="facet-title" data-bs-target="#size" data-bs-toggle="collapse"
                                             aria-expanded="true" aria-controls="size">
                                            <span>Size</span>
                                            <span class="icon icon-arrow-up"></span>
                                        </div>
                                        <div id="size" class="collapse show">
                                            <ul class="tf-filter-group current-scrollbar">
                                                @foreach($sizes as $size)
                                                    <li class="list-item d-flex gap-12 align-items-center">
                                                        <input type="checkbox" wire:model.live="selectedSizes"
                                                               value="{{ $size->value }}"
                                                               class="tf-check tf-check-size"
                                                               id="size{{ $loop->index }}">
                                                        <label for="size{{ $loop->index }}" class="label">
                                                            <span>{{ $size->value }}</span>&nbsp;<span>({{ $size->products_count ?? 0 }})</span>
                                                        </label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                                <div class="widget-facet">
                                    <div class="facet-title" data-bs-target="#search" data-bs-toggle="collapse"
                                         aria-expanded="true" aria-controls="search">
                                        <span>Search Categories</span>
                                        <span class="icon icon-arrow-up"></span>
                                    </div>
                                    <div id="search" class="collapse show">
                                        <div class="tf-search-widget mb_36">
                                            <form class="tf-mini-search-frm" onsubmit="return false;">
                                                <fieldset class="text">
                                                    <input type="text"
                                                           placeholder="Search categories..."
                                                           wire:model.debounce.400ms="search"
                                                           class="tf-field-input tf-input"
                                                           aria-label="Search categories">
                                                    <div class="button-submit">
                                                        <button type="button"><i class="icon icon-search"></i></button>
                                                    </div>
                                                </fieldset>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- Add Sale Products Section -->
                                <div class="widget-facet">
                                    <div class="facet-title" data-bs-target="#sale-products" data-bs-toggle="collapse"
                                         aria-expanded="true" aria-controls="sale-products">
                                        <span>Sale products</span>
                                        <span class="icon icon-arrow-up"></span>
                                    </div>
                                    <div id="sale-products" class="collapse show">
                                        <div class="widget-featured-products mb_36">
                                            @foreach($saleProducts ?? [] as $product)
                                                <div class="featured-product-item">
                                                    <a href="{{ route('products.show', $product->slug) }}"
                                                       class="card-product-wrapper">
                                                        <img class="img-product lazyloaded"
                                                             data-src="{{ $product->thumbnail_url }}"
                                                             alt="{{ $product->name }}"
                                                             src="{{ $product->thumbnail_url }}">
                                                    </a>
                                                    <div class="card-product-info">
                                                        <a href="{{ route('products.show', $product->slug) }}"
                                                           class="title link">{{ $product->name }}</a>
                                                        <span class="price">{{ $product->price }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <!-- Add Shipping & Delivery Section -->
                                <div class="widget-facet">
                                    <div class="facet-title" data-bs-target="#shipping" data-bs-toggle="collapse"
                                         aria-expanded="true" aria-controls="shipping">
                                        <span>Shipping & Delivery</span>
                                        <span class="icon icon-arrow-up"></span>
                                    </div>
                                    <div id="shipping" class="collapse show">
                                        <ul class="widget-iconbox-list mb_36">
                                            <li class="iconbox-item">
                                                <div class="box-icon w_50 round">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="16"
                                                         viewBox="0 0 24 16" fill="none">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                              d="M0 1C0 0.447715 0.447715 0 1 0H15.5C16.0523 0 16.5 0.447715 16.5 1V3.5H19.7857C20.099 3.5 20.3943 3.64687 20.5833 3.89679L23.7976 8.14679C23.9289 8.32046 24 8.53225 24 8.75V13C24 13.5523 23.5523 14 23 14H20.3293C19.9174 15.1652 18.8062 16 17.5 16C16.1938 16 15.0826 15.1652 14.6707 14H8.82929C8.41745 15.1652 7.3062 16 6 16C4.69378 16 3.58255 15.1652 3.17071 14H1C0.447715 14 0 13.5523 0 13V1ZM3.17071 12C3.58255 10.8348 4.69378 10 6 10C7.3062 10 8.41745 10.8348 8.82929 12H14.5V2H2V12H3.17071ZM16.5 10.1707V5.5H19.2882L22 9.08557V12H20.3293C19.9174 10.8348 18.8062 10 17.5 10C17.1494 10 16.8128 10.0602 16.5 10.1707ZM6 12C5.44772 12 5 12.4477 5 13C5 13.5523 5.44772 14 6 14C6.55227 14 7 13.5523 7 13C7 12.4477 6.55227 12 6 12ZM17.5 12C16.9477 12 16.5 12.4477 16.5 13C16.5 13.5523 16.9477 14 17.5 14C18.0523 14 18.5 13.5523 18.5 13C18.5 12.4477 18.0523 12 17.5 12Z"
                                                              fill="black"></path>
                                                    </svg>
                                                </div>
                                                <div class="iconbox-content">
                                                    <h4 class="iconbox-title">Free shipping</h4>
                                                    <p class="iconbox-desc">Free iconbox for all US order</p>
                                                </div>
                                            </li>
                                            <li class="iconbox-item">
                                                <div class="box-icon w_50 round">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                         viewBox="0 0 24 24" fill="none">
                                                        <!-- SVG content for Premium Support -->
                                                    </svg>
                                                </div>
                                                <div class="iconbox-content">
                                                    <h4 class="iconbox-title">Premium Support</h4>
                                                    <p class="iconbox-desc">Support 24 hours a day</p>
                                                </div>
                                            </li>
                                            <li class="iconbox-item">
                                                <div class="box-icon w_50 round">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                         viewBox="0 0 24 24" fill="none">
                                                        <!-- SVG content for 30 Days Return -->
                                                    </svg>
                                                </div>
                                                <div class="iconbox-content">
                                                    <h4 class="iconbox-title">30 Days Return</h4>
                                                    <p class="iconbox-desc">You have 30 days to return</p>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- Add Gallery Section -->
                                <div class="widget-facet">
                                    <div class="facet-title" data-bs-target="#gallery" data-bs-toggle="collapse"
                                         aria-expanded="true" aria-controls="gallery">
                                        <span>Gallery</span>
                                        <span class="icon icon-arrow-up"></span>
                                    </div>
                                    <div id="gallery" class="collapse show">
                                        <div class="grid-3 gap-4 mb_36">
                                            @foreach($galleryImages ?? [] as $image)
                                                <a href="{{ $image->url }}" class="item-gallery">
                                                    <img class="lazyloaded" data-src="{{ $image->src }}"
                                                         alt="img-gallery" src="{{ $image->src }}">
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <!-- Add Follow Us Section -->
                                <div class="widget-facet">
                                    <div class="facet-title" data-bs-target="#follow" data-bs-toggle="collapse"
                                         aria-expanded="true" aria-controls="follow">
                                        <span>Follow us</span>
                                        <span class="icon icon-arrow-up"></span>
                                    </div>
                                    <div id="follow" class="collapse show">
                                        <ul class="tf-social-icon d-flex gap-10">
                                            <li><a href="#" class="box-icon w_34 round bg_line social-facebook"><i
                                                        class="icon fs-14 icon-fb"></i></a></li>
                                            <li><a href="#" class="box-icon w_34 round bg_line social-twiter"><i
                                                        class="icon fs-12 icon-Icon-x"></i></a></li>
                                            <li><a href="#" class="box-icon w_34 round bg_line social-instagram"><i
                                                        class="icon fs-14 icon-instagram"></i></a></li>
                                            <li><a href="#" class="box-icon w_34 round bg_line social-tiktok"><i
                                                        class="icon fs-14 icon-tiktok"></i></a></li>
                                            <li><a href="#" class="box-icon w_34 round bg_line social-pinterest"><i
                                                        class="icon fs-14 icon-pinterest-1"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="wrapper-control-shop tf-shop-content gridLayout-wrapper">
                    <div class="meta-filter-shop" style="display: none;">
                        <div class="count-text">
                            @if(isset($categories) && $categories->total() > 0)
                                Showing {{ $categories->firstItem() }}-{{ $categories->lastItem() }}
                                of {{ $categories->total() }} categories
                            @else
                                No categories found
                            @endif
                        </div>
                        @if(isset($hasFilters) && $hasFilters)
                            <button wire:click="clearFilters" class="remove-all-filters">Remove All <i
                                    class="icon icon-close"></i></button>
                        @endif
                    </div>
                    <div
                        class="{{ $layout === 'list' ? 'tf-list-layout wrapper-shop' : 'tf-grid-layout wrapper-shop tf-col-' . str_replace('grid-', '', $layout ?? '3') }}"
                        id="{{ $layout === 'list' ? 'listLayout' : 'gridLayout' }}">
                        @if(isset($categories) && $categories->count() > 0)
                            @foreach($categories as $category)
                                <div class="card-product {{ $layout === 'list' ? 'list-layout' : 'grid' }}"
                                     data-availability="active"
                                     data-brand="{{ $category->brands->pluck('name')->implode(',') ?? '' }}">
                                    <div class="card-product-wrapper">
                                        <a href="{{ route('products.category', $category->slug) }}" class="product-img">
                                            <img class="lazyload img-product"
                                                 data-src="{{ $category->thumbnail_url }}"
                                                 src="{{ $category->thumbnail_url }}"
                                                 alt="{{ $category->name }}"
                                                 onerror="this.onerror=null;this.src='{{ asset('images/placeholder.jpg') }}'">
                                            @if($layout === 'list' && isset($category->hover_image_url))
                                                <img class="lazyload img-hover"
                                                     data-src="{{ $category->hover_image_url }}"
                                                     src="{{ $category->hover_image_url }}"
                                                     alt="{{ $category->name }}"
                                                     onerror="this.onerror=null;this.src='{{ asset('images/placeholder.jpg') }}'">
                                            @endif
                                        </a>
                                        <div class="list-product-btn {{ $layout === 'list' ? '' : 'absolute-2' }}">
                                            <a href="{{ route('products.category', $category->slug) }}"
                                               class="box-icon bg_white quick-add tf-btn-loading">
                                                <span class="icon icon-arrow1-top-left"></span>
                                                <span class="tooltip">View Category</span>
                                            </a>
                                            @if(isset($category->colors) && $category->colors && $category->colors->isNotEmpty())
                                                <a href="javascript:void(0);"
                                                   class="box-icon bg_white wishlist btn-icon-action category-colors-btn">
                                                    <span class="icon icon-view"></span>
                                                    <span class="tooltip">View Colors</span>
                                                </a>
                                            @endif
                                            <a href="#compare" data-bs-toggle="offcanvas"
                                               class="box-icon bg_white compare btn-icon-action">
                                                <span class="icon icon-compare"></span>
                                                <span class="tooltip">Add to Compare</span>
                                                <span class="icon icon-check"></span>
                                            </a>
                                        </div>
                                        @if(isset($category->type) && $category->type)
                                            <div class="on-sale-wrap text-end">
                                                <div
                                                    class="on-sale-item category-type">{{ ucfirst($category->type) }}</div>
                                            </div>
                                        @endif
                                        @if($layout === 'list' && isset($category->sizes) && $category->sizes->isNotEmpty())
                                            <div class="size-list">
                                                @foreach($category->sizes->take(4) as $size)
                                                    <span class="size-item">{{ $size->value }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-product-info">
                                        <a href="{{ route('products.category', $category->slug) }}"
                                           class="title link">{{ $category->name }}</a>
                                        <span
                                            class="price">{{ $category->products_count ?? 0 }} {{ Str::plural('product', $category->products_count ?? 0) }}</span>
                                        @if($layout === 'list' && $category->description)
                                            <p class="description">{{ Str::limit($category->description, 200) }}</p>
                                        @endif
                                        @if(isset($category->colors) && $category->colors && $category->colors->isNotEmpty())
                                            <ul class="list-color-product">
                                                @foreach($category->colors->take(5) as $color)
                                                    <li class="list-color-item color-swatch">
                                                        <span class="tooltip">{{ $color->name }}</span>
                                                        <span class="swatch-value"
                                                              style="background-color: {{ $color->value }}"></span>
                                                    </li>
                                                @endforeach
                                                @if($category->colors->count() > 5)
                                                    <li class="list-color-item">
                                                        <span
                                                            class="text-muted small">+{{ $category->colors->count() - 5 }}</span>
                                                    </li>
                                                @endif
                                            </ul>
                                        @endif
                                        @if(isset($category->brands) && $category->brands && $category->brands->isNotEmpty())
                                            <div class="category-brands mt-2">
                                                <small class="text-muted">Brands:
                                                    @foreach($category->brands->take(3) as $brand)
                                                        {{ $brand->name }}{{ !$loop->last ? ', ' : '' }}
                                                    @endforeach
                                                    @if($category->brands->count() > 3)
                                                        and {{ $category->brands->count() - 3 }} more
                                                    @endif
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <div class="text-center py-12">
                                    <div class="max-w-md mx-auto">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                             stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                        <h3 class="mt-4 text-lg font-medium text-gray-900">
                                            @if(isset($search) && $search)
                                                No categories found matching "{{ $search }}"
                                            @else
                                                No categories available
                                            @endif
                                        </h3>
                                        <p class="mt-2 text-gray-500">
                                            @if(isset($search) && $search)
                                                Try adjusting your search terms or filters.
                                            @else
                                                Categories will appear here once they are added.
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- pagination -->
                        @if(isset($categories) && $categories->hasPages())
                            <ul class="wg-pagination tf-pagination-list">
                                @foreach($categories->links()->elements[0] as $page => $url)
                                    <li class="{{ $categories->currentPage() == $page ? 'active' : '' }}">
                                        <a href="{{ $url }}"
                                           class="pagination-link {{ $categories->currentPage() != $page ? 'animate-hover-btn' : '' }}">{{ $page }}</a>
                                    </li>
                                @endforeach
                                @if($categories->hasMorePages())
                                    <li>
                                        <a href="{{ $categories->nextPageUrl() }}"
                                           class="pagination-link animate-hover-btn">
                                            <span class="icon icon-arrow-right"></span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="btn-sidebar-mobile start-0 filterShop">
        <button class="type-hover" data-bs-toggle="offcanvas" data-bs-target="#filterShop">
            <i class="icon-open"></i>
            <span class="fw-5">Open Filter</span>
        </button>
    </div>
    <div class="overlay-filter" id="overlay-filter"></div>

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
                <div class="sidebar-mobile-append tf-section-sidebar">
                    <!-- Mobile filter content -->
                    <div class="widget-facet wd-categories">
                        <div class="facet-title" data-bs-target="#mobile-categories" data-bs-toggle="collapse"
                             aria-expanded="true" aria-controls="mobile-categories">
                            <span>Category types</span>
                            <span class="icon icon-arrow-up"></span>
                        </div>
                        <div id="mobile-categories" class="collapse show">
                            <ul class="list-categoris current-scrollbar mb_36">
                                <li class="cate-item {{ empty($selectedType) ? 'current' : '' }}">
                                    <a href="#" wire:click.prevent="setType('')" data-bs-dismiss="offcanvas"><span>All Categories</span></a>
                                </li>
                                <li class="cate-item {{ $selectedType === 'style' ? 'current' : '' }}">
                                    <a href="#" wire:click.prevent="setType('style')" data-bs-dismiss="offcanvas"><span>By Style</span></a>
                                </li>
                                <li class="cate-item {{ $selectedType === 'occasion' ? 'current' : '' }}">
                                    <a href="#" wire:click.prevent="setType('occasion')"
                                       data-bs-dismiss="offcanvas"><span>By Occasion</span></a>
                                </li>
                                <li class="cate-item {{ $selectedType === 'collection' ? 'current' : '' }}">
                                    <a href="#" wire:click.prevent="setType('collection')"
                                       data-bs-dismiss="offcanvas"><span>Collections</span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    @if(isset($hasFilters) && $hasFilters)
                        <div class="text-center mb-4">
                            <button wire:click="clearFilters" class="tf-btn btn-outline w-100"
                                    data-bs-dismiss="offcanvas">Clear All Filters
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Handle layout switching
                document.querySelectorAll('.tf-view-layout-switch').forEach(function (el) {
                    el.addEventListener('click', function () {
                        document.querySelectorAll('.tf-view-layout-switch .item').forEach(function (item) {
                            item.classList.remove('active');
                        });
                        this.querySelector('.item').classList.add('active');

                        const layout = this.getAttribute('data-value-layout');
                        const listLayout = document.getElementById('listLayout');
                        const gridLayout = document.getElementById('gridLayout');
                        if (!listLayout || !gridLayout) {
                            // Layout is managed by Livewire re-render; nothing to toggle here safely.
                            return;
                        }
                        if (layout === 'list') {
                            listLayout.style.display = 'block';
                            gridLayout.style.display = 'none';
                        } else {
                            listLayout.style.display = 'none';
                            gridLayout.style.display = 'block';
                        }
                    });
                });

                // Handle dropdown sorting
                document.querySelectorAll('.tf-dropdown-sort .select-item').forEach(function (el) {
                    el.addEventListener('click', function () {
                        document.querySelectorAll('.tf-dropdown-sort .select-item').forEach(function (item) {
                            item.classList.remove('active');
                        });
                        this.classList.add('active');
                        const selectedText = this.querySelector('.text-value-item').textContent;
                        document.querySelector('.text-sort-value').textContent = selectedText;
                        document.querySelector('.tf-dropdown-sort').classList.remove('show');
                    });
                });

                // Toggle dropdown
                document.querySelector('.tf-dropdown-sort .btn-select')?.addEventListener('click', function () {
                    const dropdown = this.closest('.tf-dropdown-sort');
                    dropdown.classList.toggle('show');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function (e) {
                    if (!e.target.closest('.tf-dropdown-sort')) {
                        document.querySelectorAll('.tf-dropdown-sort').forEach(function (dropdown) {
                            dropdown.classList.remove('show');
                        });
                    }
                });

                // Mobile sidebar functionality
                const filterButton = document.querySelector('.filterShop button');
                const overlay = document.getElementById('overlay-filter');

                if (filterButton && overlay) {
                    filterButton.addEventListener('click', function () {
                        overlay.classList.add('show');
                    });

                    overlay.addEventListener('click', function () {
                        this.classList.remove('show');
                    });
                }

                // Collapsible filter sections
                document.querySelectorAll('.facet-title').forEach(function (title) {
                    title.addEventListener('click', function () {
                        const target = document.querySelector(this.getAttribute('data-bs-target'));
                        const icon = this.querySelector('.icon');

                        if (target.classList.contains('show')) {
                            target.classList.remove('show');
                            icon.classList.remove('icon-arrow-up');
                            icon.classList.add('icon-arrow-down');
                        } else {
                            target.classList.add('show');
                            icon.classList.remove('icon-arrow-down');
                            icon.classList.add('icon-arrow-up');
                        }
                    });
                });

                // Loading states for Livewire interactions
                document.addEventListener('livewire:loading', function (e) {
                    if (e.detail.component.name === 'guest.categories.category-grid') {
                        const loadingEl = document.querySelector('.tf-shop-content');
                        if (loadingEl) {
                            loadingEl.style.opacity = '0.7';
                            loadingEl.style.pointerEvents = 'none';
                        }
                    }
                });

                document.addEventListener('livewire:loaded', function (e) {
                    if (e.detail.component.name === 'guest.categories.category-grid') {
                        const loadingEl = document.querySelector('.tf-shop-content');
                        if (loadingEl) {
                            loadingEl.style.opacity = '1';
                            loadingEl.style.pointerEvents = 'auto';
                        }
                    }
                });
            });
        </script>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Handle layout switching (UI highlight only; Livewire re-renders the layout)
                document.querySelectorAll('.tf-view-layout-switch').forEach(function(el) {
                    el.addEventListener('click', function() {
                        document.querySelectorAll('.tf-view-layout-switch .item').forEach(function(item) {
                            item.classList.remove('active');
                        });
                        const item = this.querySelector('.item');
                        if (item) item.classList.add('active');

                        // Do NOT try to manually show/hide #listLayout/#gridLayout here.
                        // Livewire's setLayout() will re-render the correct container.
                    });
                });

                // Handle dropdown sorting
                document.querySelectorAll('.tf-dropdown-sort .select-item').forEach(function(el) {
                    el.addEventListener('click', function() {
                        document.querySelectorAll('.tf-dropdown-sort .select-item').forEach(function(item) {
                            item.classList.remove('active');
                        });
                        this.classList.add('active');
                        const selectedText = this.querySelector('.text-value-item')?.textContent?.trim() || '';
                        const label = document.querySelector('.text-sort-value');
                        if (label && selectedText) label.textContent = selectedText;
                        const dd = this.closest('.tf-dropdown-sort');
                        if (dd) dd.classList.remove('show');
                    });
                });

                // Toggle dropdown
                const sortBtn = document.querySelector('.tf-dropdown-sort .btn-select');
                if (sortBtn) {
                    sortBtn.addEventListener('click', function() {
                        const dropdown = this.closest('.tf-dropdown-sort');
                        if (dropdown) dropdown.classList.toggle('show');
                    });
                }

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!e.target.closest('.tf-dropdown-sort')) {
                        document.querySelectorAll('.tf-dropdown-sort').forEach(function(dropdown) {
                            dropdown.classList.remove('show');
                        });
                    }
                });

                // Mobile sidebar overlay
                const filterButton = document.querySelector('.filterShop button');
                const overlay = document.getElementById('overlay-filter');
                if (filterButton && overlay) {
                    filterButton.addEventListener('click', function() {
                        overlay.classList.add('show');
                    });
                    overlay.addEventListener('click', function() {
                        overlay.classList.remove('show');
                    });
                }

                // Collapsible sections
                document.querySelectorAll('.facet-title').forEach(function(title) {
                    title.addEventListener('click', function() {
                        const targetSel = this.getAttribute('data-bs-target');
                        const target = targetSel ? document.querySelector(targetSel) : null;
                        const icon = this.querySelector('.icon');
                        if (!target) return;

                        const show = !target.classList.contains('show');
                        target.classList.toggle('show', show);
                        if (icon) {
                            icon.classList.toggle('icon-arrow-up', show);
                            icon.classList.toggle('icon-arrow-down', !show);
                        }
                    });
                });

                // Livewire loading states (guard against missing detail)
                document.addEventListener('livewire:loading', function(e) {
                    const loadingEl = document.querySelector('.tf-shop-content');
                    if (loadingEl) {
                        loadingEl.style.opacity = '0.7';
                        loadingEl.style.pointerEvents = 'none';
                    }
                });
                document.addEventListener('livewire:loaded', function() {
                    const loadingEl = document.querySelector('.tf-shop-content');
                    if (loadingEl) {
                        loadingEl.style.opacity = '1';
                        loadingEl.style.pointerEvents = 'auto';
                    }
                });
            });
        </script>
    @endpush

</div>

