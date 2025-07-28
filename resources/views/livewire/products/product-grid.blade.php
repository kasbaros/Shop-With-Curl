{{--<div class="space-y-6">--}}
{{--    <!-- Filters -->--}}
{{--    <div class="bg-white p-6 rounded-lg shadow-sm border">--}}
{{--        <div class="flex flex-wrap items-center gap-4">--}}
{{--            <!-- Category Filter -->--}}
{{--            <div>--}}
{{--                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>--}}
{{--                <select wire:model.live="selectedCategory" class="border border-gray-300 rounded-md px-3 py-2">--}}
{{--                    <option value="">All Categories</option>--}}
{{--                    @foreach($this->categories as $category)--}}
{{--                        <option value="{{ $category->id }}">--}}
{{--                            {{ $category->name }} ({{ $category->products_count }})--}}
{{--                        </option>--}}
{{--                    @endforeach--}}
{{--                </select>--}}
{{--            </div>--}}

{{--            <!-- Price Range -->--}}
{{--            <div>--}}
{{--                <label class="block text-sm font-medium text-gray-700 mb-1">Price Range</label>--}}
{{--                <div class="flex items-center space-x-2">--}}
{{--                    <input--}}
{{--                        type="number"--}}
{{--                        wire:model.live="minPrice"--}}
{{--                        placeholder="Min"--}}
{{--                        class="w-20 border border-gray-300 rounded-md px-2 py-2"--}}
{{--                    >--}}
{{--                    <span class="text-gray-500">-</span>--}}
{{--                    <input--}}
{{--                        type="number"--}}
{{--                        wire:model.live="maxPrice"--}}
{{--                        placeholder="Max"--}}
{{--                        class="w-20 border border-gray-300 rounded-md px-2 py-2"--}}
{{--                    >--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <!-- Filter Checkboxes -->--}}
{{--            <div class="flex flex-wrap gap-4">--}}
{{--                <label class="flex items-center">--}}
{{--                    <input type="checkbox" wire:model.live="inStockOnly" class="mr-2">--}}
{{--                    <span class="text-sm">In Stock Only</span>--}}
{{--                </label>--}}

{{--                <label class="flex items-center">--}}
{{--                    <input type="checkbox" wire:model.live="onSaleOnly" class="mr-2">--}}
{{--                    <span class="text-sm">On Sale</span>--}}
{{--                </label>--}}

{{--                <label class="flex items-center">--}}
{{--                    <input type="checkbox" wire:model.live="featuredOnly" class="mr-2">--}}
{{--                    <span class="text-sm">Featured</span>--}}
{{--                </label>--}}
{{--            </div>--}}

{{--            <!-- Clear Filters -->--}}
{{--            <button--}}
{{--                wire:click="clearFilters"--}}
{{--                class="text-sm text-blue-600 hover:text-blue-700 underline"--}}
{{--            >--}}
{{--                Clear Filters--}}
{{--            </button>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <!-- Sort Options -->--}}
{{--    <div class="flex justify-between items-center">--}}
{{--        <p class="text-sm text-gray-600">--}}
{{--            Showing {{ $this->products->firstItem() ?? 0 }} to {{ $this->products->lastItem() ?? 0 }}--}}
{{--            of {{ $this->products->total() }} products--}}
{{--        </p>--}}

{{--        <div class="flex items-center space-x-2">--}}
{{--            <label class="text-sm font-medium text-gray-700">Sort by:</label>--}}
{{--            <select--}}
{{--                wire:change="setSortBy($event.target.value.split('|')[0], $event.target.value.split('|')[1])"--}}
{{--                class="border border-gray-300 rounded-md px-3 py-2"--}}
{{--            >--}}
{{--                <option value="name|asc" {{ $sortBy === 'name' && $sortOrder === 'asc' ? 'selected' : '' }}>--}}
{{--                    Name (A-Z)--}}
{{--                </option>--}}
{{--                <option value="name|desc" {{ $sortBy === 'name' && $sortOrder === 'desc' ? 'selected' : '' }}>--}}
{{--                    Name (Z-A)--}}
{{--                </option>--}}
{{--                <option value="price|asc" {{ $sortBy === 'price' && $sortOrder === 'asc' ? 'selected' : '' }}>--}}
{{--                    Price (Low to High)--}}
{{--                </option>--}}
{{--                <option value="price|desc" {{ $sortBy === 'price' && $sortOrder === 'desc' ? 'selected' : '' }}>--}}
{{--                    Price (High to Low)--}}
{{--                </option>--}}
{{--                <option value="created_at|desc" {{ $sortBy === 'created_at' && $sortOrder === 'desc' ? 'selected' : '' }}>--}}
{{--                    Newest First--}}
{{--                </option>--}}
{{--            </select>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <!-- Products Grid -->--}}
{{--    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">--}}
{{--        @foreach($this->products as $product)--}}
{{--            <x-product-card :product="$product" />--}}
{{--        @endforeach--}}
{{--    </div>--}}

{{--    <!-- Pagination -->--}}
{{--    <div class="mt-8">--}}
{{--        {{ $this->products->links() }}--}}
{{--    </div>--}}

{{--    <!-- No Products Message -->--}}
{{--    @if($this->products->isEmpty())--}}
{{--        <div class="text-center py-12">--}}
{{--            <x-heroicon-o-cube class="w-16 h-16 mx-auto text-gray-400 mb-4" />--}}
{{--            <h3 class="text-lg font-medium text-gray-900 mb-2">No products found</h3>--}}
{{--            <p class="text-gray-500">Try adjusting your filters or search terms.</p>--}}
{{--        </div>--}}
{{--    @endif--}}
{{--</div>--}}
<div>

    <div class="tf-page-title">
        <div class="container-full">
            <div class="heading text-center">All Products</div>
            <p class="text-center text-2 text_black-2 mt_5">Shop through our latest selection of Fashion</p>
        </div>
    </div>

    <section class="flat-spacing-2">
        <div class="container">
            <div class="tf-shop-control grid-3 align-items-center">
                {{--                <div class="tf-control-filter">--}}
                {{--                    <a href="#filterShop" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft"--}}
                {{--                       class="tf-btn-filter"><span class="icon icon-filter"></span><span class="text">Filter</span></a>--}}
                {{--                </div>--}}
                {{--                <ul class="tf-control-layout d-flex justify-content-center">--}}
                {{--                    <li class="tf-view-layout-switch sw-layout-list list-layout" data-value-layout="list">--}}
                {{--                        <div class="item"><span class="icon icon-list"></span></div>--}}
                {{--                    </li>--}}
                {{--                    <li class="tf-view-layout-switch sw-layout-2" data-value-layout="tf-col-2">--}}
                {{--                        <div class="item"><span class="icon icon-grid-2"></span></div>--}}
                {{--                    </li>--}}
                {{--                    <li class="tf-view-layout-switch sw-layout-3" data-value-layout="tf-col-3">--}}
                {{--                        <div class="item"><span class="icon icon-grid-3"></span></div>--}}
                {{--                    </li>--}}
                {{--                    <li class="tf-view-layout-switch sw-layout-4 active" data-value-layout="tf-col-4">--}}
                {{--                        <div class="item"><span class="icon icon-grid-4"></span></div>--}}
                {{--                    </li>--}}
                {{--                    <li class="tf-view-layout-switch sw-layout-5" data-value-layout="tf-col-5">--}}
                {{--                        <div class="item"><span class="icon icon-grid-5"></span></div>--}}
                {{--                    </li>--}}
                {{--                    <li class="tf-view-layout-switch sw-layout-6" data-value-layout="tf-col-6">--}}
                {{--                        <div class="item"><span class="icon icon-grid-6"></span></div>--}}
                {{--                    </li>--}}
                {{--                </ul>--}}
                {{--                <div class="tf-control-sorting d-flex justify-content-end">--}}
                {{--                    <div class="tf-dropdown-sort" data-bs-toggle="dropdown">--}}
                {{--                        <div class="btn-select">--}}
                {{--                            <span class="text-sort-value">Featured</span>--}}
                {{--                            <span class="icon icon-arrow-down"></span>--}}
                {{--                        </div>--}}
                {{--                        <div class="dropdown-menu">--}}
                {{--                            <div class="select-item active">--}}
                {{--                                <span class="text-value-item">Featured</span>--}}
                {{--                            </div>--}}
                {{--                            <div class="select-item">--}}
                {{--                                <span class="text-value-item">Best selling</span>--}}
                {{--                            </div>--}}
                {{--                            <div class="select-item" data-sort-value="a-z">--}}
                {{--                                <span class="text-value-item">Alphabetically, A-Z</span>--}}
                {{--                            </div>--}}
                {{--                            <div class="select-item" data-sort-value="z-a">--}}
                {{--                                <span class="text-value-item">Alphabetically, Z-A</span>--}}
                {{--                            </div>--}}
                {{--                            <div class="select-item" data-sort-value="price-low-high">--}}
                {{--                                <span class="text-value-item">Price, low to high</span>--}}
                {{--                            </div>--}}
                {{--                            <div class="select-item" data-sort-value="price-high-low">--}}
                {{--                                <span class="text-value-item">Price, high to low</span>--}}
                {{--                            </div>--}}
                {{--                            <div class="select-item">--}}
                {{--                                <span class="text-value-item">Date, old to new</span>--}}
                {{--                            </div>--}}
                {{--                            <div class="select-item">--}}
                {{--                                <span class="text-value-item">Date, new to old</span>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                <!-- Filters -->
                <div class="bg-white p-6 rounded-lg shadow-sm border">
                    <div class="flex flex-wrap items-center gap-4">
                        <!-- Category Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select wire:model.live="selectedCategory"
                                    class="border border-gray-300 rounded-md px-3 py-2">
                                <option value="">All Categories</option>
                                @foreach($this->categories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }} ({{ $category->products_count }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price Range</label>
                            <div class="flex items-center space-x-2">
                                <input
                                    type="number"
                                    wire:model.live="minPrice"
                                    placeholder="Min"
                                    class="w-20 border border-gray-300 rounded-md px-2 py-2"
                                >
                                <span class="text-gray-500">-</span>
                                <input
                                    type="number"
                                    wire:model.live="maxPrice"
                                    placeholder="Max"
                                    class="w-20 border border-gray-300 rounded-md px-2 py-2"
                                >
                            </div>
                        </div>

                        <!-- Filter Checkboxes -->
                        <div class="flex flex-wrap gap-4">
                            <label class="flex items-center">
                                <input type="checkbox" wire:model.live="inStockOnly" class="mr-2">
                                <span class="text-sm">In Stock Only</span>
                            </label>

                            <label class="flex items-center">
                                <input type="checkbox" wire:model.live="onSaleOnly" class="mr-2">
                                <span class="text-sm">On Sale</span>
                            </label>

                            <label class="flex items-center">
                                <input type="checkbox" wire:model.live="featuredOnly" class="mr-2">
                                <span class="text-sm">Featured</span>
                            </label>
                        </div>

                        <!-- Clear Filters -->
                        <button
                            wire:click="clearFilters"
                            class="text-sm text-blue-600 hover:text-blue-700 underline"
                        >
                            Clear Filters
                        </button>
                    </div>
                </div>

            </div>
            <div class="wrapper-control-shop gridLayout-wrapper">
                <div class="meta-filter-shop" style="display: none;">
                    <div id="product-count-grid" class="count-text"><span class="count">12</span> Products Found</div>
                    <div id="product-count-list" class="count-text"><span class="count">8</span> Products Found</div>
                    <div id="applied-filters"></div>
                    <button id="remove-all" class="remove-all-filters" style="display: none;">Remove All <i
                            class="icon icon-close"></i></button>
                </div>
                <div class="tf-list-layout wrapper-shop" id="listLayout" style="display: none;">
                    <!-- card product 1 -->
                    <div class="card-product list-layout" data-availability="In stock" data-brand="Ecomus">
                        <div class="card-product-wrapper">
                            <a href="#" class="product-img">
                                <img class="img-product ls-is-cached lazyloaded"
                                     data-src="images/products/shop_with_carl-1.jpg"
                                     src="images/products/shop_with_carl-1.jpg" alt="image-product">
                                <img class="img-hover ls-is-cached lazyloaded"
                                     data-src="images/products/shop_with_carl-1.jpg"
                                     src="images/products/shop_with_carl-1.jpg" alt="image-product">
                            </a>
                        </div>
                        <div class="card-product-info">
                            <a href="#" class="title link">Ribbed Tank Top</a>
                            <span class="price current-price">$16.95</span>
                            <p class="description">Button-up shirt sleeves and a relaxed silhouette. It’s tailored
                                with drapey, crinkle-texture fabric that’s made from LENZING™ ECOVERO™ Viscose —
                                responsibly sourced wood-based fibres produced through a process that reduces...</p>
                            <ul class="list-color-product">
                                <li class="list-color-item hover-tooltip color-swatch active">
                                    <span class="tooltip tooltip-bottom">Orange</span>
                                    <span class="swatch-value bg_orange-3"></span>
                                    <img src="images/products/shop_with_carl-1.jpg" ) }}" alt="image-product">
                                </li>
                                <li class="list-color-item color-swatch">
                                    <span class="tooltip">Black</span>
                                    <span class="swatch-value bg_dark"></span>
                                    <img class=" ls-is-cached lazyloaded"
                                         data-src="images/products/shop_with_carl-2.jpg"
                                         src="images/products/shop_with_carl-2.jpg" alt="image-product">
                                </li>
                                <li class="list-color-item color-swatch">
                                    <span class="tooltip">White</span>
                                    <span class="swatch-value bg_white"></span>
                                    <img class=" ls-is-cached lazyloaded"
                                         data-src="images/products/shop_with_carl-3.jpg"
                                         src="images/products/shop_with_carl-3.jpg" alt="image-product">
                                </li>
                            </ul>
                            <div class="size-list">
                                <span class="size-item">S</span>
                                <span class="size-item">M</span>
                                <span class="size-item">L</span>
                                <span class="size-item">XL</span>
                            </div>
                            <div class="list-product-btn">
                                <a href="#quick_add" data-bs-toggle="modal"
                                   class="box-icon quick-add style-3 hover-tooltip"><span
                                        class="icon icon-bag"></span><span class="tooltip">Quick add</span></a>
                                <a href="#" class="box-icon wishlist style-3 hover-tooltip"><span
                                        class="icon icon-heart"></span> <span class="tooltip">Add to
                                            Wishlist</span></a>
                                <a href="#compare" data-bs-toggle="offcanvas"
                                   class="box-icon compare style-3 hover-tooltip"><span
                                        class="icon icon-compare"></span> <span class="tooltip">Add to
                                            Compare</span></a>
                                <a href="#quick_view" data-bs-toggle="modal"
                                   class="box-icon quickview style-3 hover-tooltip"><span class="icon icon-view"></span><span
                                        class="tooltip">Quick view</span></a>
                            </div>
                        </div>
                    </div>
                    <!-- card product 2 -->
                    <div class="card-product list-layout" data-availability="In stock" data-brand="Ecomus">
                        <div class="card-product-wrapper">
                            <div class="product-img">
                                <img class="img-product ls-is-cached lazyloaded" data-src="images/products/brown.jpg" )
                                     }}"
                                src="images/products/brown.jpg") }}" alt="image-product">
                                <img class="img-hover ls-is-cached lazyloaded" data-src="images/products/purple.jpg"
                                     src="images/products/purple.jpg" alt="image-product">
                            </div>
                            <div class="countdown-box">
                                <div class="js-countdown" data-timer="1007500" data-labels="d :,h :,m :,s">
                                    <div aria-hidden="true" class="countdown__timer"><span class="countdown__item"><span
                                                class="countdown__value countdown__value--0 js-countdown__value--0">11</span><span
                                                class="countdown__label">d :</span></span><span class="countdown__item"><span
                                                class="countdown__value countdown__value--1 js-countdown__value--1">15</span><span
                                                class="countdown__label">h :</span></span><span class="countdown__item"><span
                                                class="countdown__value countdown__value--2 js-countdown__value--2">49</span><span
                                                class="countdown__label">m :</span></span><span class="countdown__item"><span
                                                class="countdown__value countdown__value--3 js-countdown__value--3">08</span><span
                                                class="countdown__label">s</span></span></div>
                                </div>
                            </div>
                        </div>
                        <div class="card-product-info">
                            <a href="#" class="title link">Ribbed modal T-shirt</a>
                            <span class="price current-price">$18.95</span>
                            <p class="description">Button-up shirt sleeves and a relaxed silhouette. It’s tailored
                                with drapey, crinkle-texture fabric that’s made from LENZING™ ECOVERO™ Viscose —
                                responsibly sourced wood-based fibres produced through a process that reduces...</p>
                            <ul class="list-color-product">
                                <li class="list-color-item color-swatch active">
                                    <span class="tooltip">Brown</span>
                                    <span class="swatch-value bg_brown"></span>
                                    <img class="lazyload" data-src="images/products/brown.jpg" ) }}"
                                    src="images/products/brown.jpg") }}" alt="image-product">
                                </li>
                                <li class="list-color-item color-swatch">
                                    <span class="tooltip">Light Purple</span>
                                    <span class="swatch-value bg_purple"></span>
                                    <img class="lazyload" data-src="images/products/purple.jpg" ) }}"
                                    src="images/products/purple.jpg") }}" alt="image-product">
                                </li>
                                <li class="list-color-item color-swatch">
                                    <span class="tooltip">Light Green</span>
                                    <span class="swatch-value bg_light-green"></span>
                                    <img class="lazyload" data-src="images/products/green.jpg" ) }}"
                                    src="images/products/green.jpg") }}" alt="image-product">
                                </li>
                            </ul>
                            <div class="size-list">
                                <span class="size-item">S</span>
                                <span class="size-item">M</span>
                                <span class="size-item">L</span>
                                <span class="size-item">XL</span>
                            </div>
                            <div class="list-product-btn">
                                <a href="#quick_add" data-bs-toggle="modal" class="box-icon quick-add style-3"><span
                                        class="icon icon-bag"></span><span class="tooltip">Quick add</span></a>
                                <a href="#" class="box-icon wishlist style-3"><span class="icon icon-heart"></span>
                                    <span class="tooltip">Add to Wishlist</span></a>
                                <a href="#compare" data-bs-toggle="offcanvas" class="box-icon compare style-3"><span
                                        class="icon icon-compare"></span> <span class="tooltip">Add to
                                            Compare</span></a>
                                <a href="#quick_view" data-bs-toggle="modal" class="box-icon quickview style-3"><span
                                        class="icon icon-view"></span><span class="tooltip">Quick view</span></a>
                            </div>
                        </div>
                    </div>
                    <!-- card product 3 -->
                    <div class="card-product list-layout" data-availability="In stock" data-brand="Ecomus">
                        <div class="card-product-wrapper">
                            <div class="product-img">
                                <img class="lazyload img-product" data-src="images/products/white-3.jpg"
                                     src="images/products/white-3.jpg" alt="image-product">
                                <img class="lazyload img-hover" data-src="images/products/white-4.jpg"
                                     src="images/products/white-4.jpg" alt="image-product">
                            </div>
                        </div>
                        <div class="card-product-info">
                            <a href="#" class="title link">Oversized Printed T-shirt</a>
                            <span class="price current-price">$10.00</span>
                            <p class="description">Button-up shirt sleeves and a relaxed silhouette. It’s tailored
                                with drapey, crinkle-texture fabric that’s made from LENZING™ ECOVERO™ Viscose —
                                responsibly sourced wood-based fibres produced through a process that reduces...</p>
                            <div class="list-product-btn">
                                <a href="#quick_add" data-bs-toggle="modal" class="box-icon quick-add style-3"><span
                                        class="icon icon-bag"></span><span class="tooltip">Quick add</span></a>
                                <a href="#" class="box-icon wishlist style-3"><span class="icon icon-heart"></span>
                                    <span class="tooltip">Add to Wishlist</span></a>
                                <a href="#compare" data-bs-toggle="offcanvas" class="box-icon compare style-3"><span
                                        class="icon icon-compare"></span> <span class="tooltip">Add to
                                            Compare</span></a>
                                <a href="#quick_view" data-bs-toggle="modal" class="box-icon quickview style-3"><span
                                        class="icon icon-view"></span><span class="tooltip">Quick view</span></a>
                            </div>
                        </div>
                    </div>
                    <!-- card product 4 -->
                    <div class="card-product list-layout" data-availability="In stock" data-brand="Ecomus">
                        <div class="card-product-wrapper">
                            <div class="product-img">
                                <img class="lazyload img-product" data-src="images/products/white-2.jpg"
                                     src="images/products/white-2.jpg" alt="image-product">
                                <img class="lazyload img-hover" data-src="images/products/pink-1.jpg"
                                     src="images/products/pink-1.jpg" alt="image-product">
                            </div>
                        </div>
                        <div class="card-product-info">
                            <a href="#" class="title link">Oversized Printed T-shirt</a>
                            <span class="price current-price">$16.95</span>
                            <p class="description">Button-up shirt sleeves and a relaxed silhouette. It’s tailored
                                with drapey, crinkle-texture fabric that’s made from LENZING™ ECOVERO™ Viscose —
                                responsibly sourced wood-based fibres produced through a process that reduces...</p>
                            <ul class="list-color-product">
                                <li class="list-color-item color-swatch active">
                                    <span class="tooltip">White</span>
                                    <span class="swatch-value bg_white"></span>
                                    <img class="lazyload" data-src="images/products/white-2.jpg"
                                         src="images/products/white-2.jpg" alt="image-product">
                                </li>
                                <li class="list-color-item color-swatch">
                                    <span class="tooltip">Pink</span>
                                    <span class="swatch-value bg_purple"></span>
                                    <img class="lazyload" data-src="images/products/pink-1.jpg"
                                         src="images/products/pink-1.jpg" alt="image-product">
                                </li>
                                <li class="list-color-item color-swatch">
                                    <span class="tooltip">Black</span>
                                    <span class="swatch-value bg_dark"></span>
                                    <img class="lazyload" data-src="images/products/black-2.jpg"
                                         src="images/products/black-2.jpg" alt="image-product">
                                </li>
                            </ul>
                            <div class="size-list">
                                <span class="size-item">S</span>
                                <span class="size-item">M</span>
                                <span class="size-item">L</span>
                                <span class="size-item">XL</span>
                            </div>
                            <div class="list-product-btn">
                                <a href="#quick_add" data-bs-toggle="modal" class="box-icon quick-add style-3"><span
                                        class="icon icon-bag"></span><span class="tooltip">Quick add</span></a>
                                <a href="#" class="box-icon wishlist style-3"><span class="icon icon-heart"></span>
                                    <span class="tooltip">Add to Wishlist</span></a>
                                <a href="#compare" data-bs-toggle="offcanvas" class="box-icon compare style-3"><span
                                        class="icon icon-compare"></span> <span class="tooltip">Add to
                                            Compare</span></a>
                                <a href="#quick_view" data-bs-toggle="modal" class="box-icon quickview style-3"><span
                                        class="icon icon-view"></span><span class="tooltip">Quick view</span></a>
                            </div>
                        </div>
                    </div>
                    <!-- card product 5 -->
                    <div class="card-product list-layout" data-availability="In stock" data-brand="Ecomus">
                        <div class="card-product-wrapper">
                            <div class="product-img">
                                <img class="lazyload img-product" data-src="images/products/brown-2.jpg"
                                     src="images/products/brown-2.jpg" alt="image-product">
                                <img class="lazyload img-hover" data-src="images/products/brown-3.jpg"
                                     src="images/products/brown-3.jpg" alt="image-product">
                            </div>
                        </div>
                        <div class="card-product-info">
                            <a href="#" class="title link">V-neck linen T-shirt</a>
                            <span class="price current-price">$114.95</span>
                            <p class="description">Button-up shirt sleeves and a relaxed silhouette. It’s tailored
                                with drapey, crinkle-texture fabric that’s made from LENZING™ ECOVERO™ Viscose —
                                responsibly sourced wood-based fibres produced through a process that reduces...</p>
                            <ul class="list-color-product">
                                <li class="list-color-item color-swatch active">
                                    <span class="tooltip">Brown</span>
                                    <span class="swatch-value bg_brown"></span>
                                    <img class="lazyload" data-src="images/products/brown-2.jpg"
                                         src="images/products/brown-2.jpg" alt="image-product">
                                </li>
                                <li class="list-color-item color-swatch">
                                    <span class="tooltip">White</span>
                                    <span class="swatch-value bg_white"></span>
                                    <img class="lazyload" data-src="images/products/white-5.jpg"
                                         src="images/products/white-5.jpg" alt="image-product">
                                </li>
                            </ul>
                            <div class="size-list">
                                <span class="size-item">S</span>
                                <span class="size-item">M</span>
                                <span class="size-item">L</span>
                                <span class="size-item">XL</span>
                            </div>
                            <div class="list-product-btn">
                                <a href="#quick_add" data-bs-toggle="modal" class="box-icon quick-add style-3"><span
                                        class="icon icon-bag"></span><span class="tooltip">Quick add</span></a>
                                <a href="#" class="box-icon wishlist style-3"><span class="icon icon-heart"></span>
                                    <span class="tooltip">Add to Wishlist</span></a>
                                <a href="#compare" data-bs-toggle="offcanvas" class="box-icon compare style-3"><span
                                        class="icon icon-compare"></span> <span class="tooltip">Add to
                                            Compare</span></a>
                                <a href="#quick_view" data-bs-toggle="modal" class="box-icon quickview style-3"><span
                                        class="icon icon-view"></span><span class="tooltip">Quick view</span></a>
                            </div>
                        </div>
                    </div>
                    <!-- card product 6 -->
                    <div class="card-product list-layout" data-availability="In stock" data-brand="M&amp;H">
                        <div class="card-product-wrapper">
                            <div class="product-img">
                                <img class="lazyload img-product" data-src="images/products/light-green-1.jpg"
                                     src="images/products/light-green-1.jpg" alt="image-product">
                                <img class="lazyload img-hover" data-src="images/products/light-green-2.jpg"
                                     src="images/products/light-green-2.jpg" alt="image-product">
                            </div>
                        </div>
                        <div class="card-product-info">
                            <a href="#" class="title link">Loose Fit Sweatshirt</a>
                            <span class="price current-price">$10.00</span>
                            <p class="description">Button-up shirt sleeves and a relaxed silhouette. It’s tailored
                                with drapey, crinkle-texture fabric that’s made from LENZING™ ECOVERO™ Viscose —
                                responsibly sourced wood-based fibres produced through a process that reduces...</p>
                            <ul class="list-color-product">
                                <li class="list-color-item color-swatch active">
                                    <span class="tooltip">Light Green</span>
                                    <span class="swatch-value bg_light-green"></span>
                                    <img class="lazyload" data-src="images/products/light-green-1.jpg"
                                         src="images/products/light-green-1.jpg" alt="image-product">
                                </li>
                                <li class="list-color-item color-swatch">
                                    <span class="tooltip">Black</span>
                                    <span class="swatch-value bg_dark"></span>
                                    <img class="lazyload" data-src="images/products/black-3.jpg"
                                         src="images/products/black-3.jpg" alt="image-product">
                                </li>
                                <li class="list-color-item color-swatch">
                                    <span class="tooltip">Blue</span>
                                    <span class="swatch-value bg_blue-2"></span>
                                    <img class="lazyload" data-src="images/products/blue.jpg"
                                         src="images/products/blue.jpg" alt="image-product">
                                </li>
                                <li class="list-color-item color-swatch">
                                    <span class="tooltip">Dark Blue</span>
                                    <span class="swatch-value bg_dark-blue"></span>
                                    <img class="lazyload" data-src="images/products/dark-blue.jpg"
                                         src="images/products/dark-blue.jpg" alt="image-product">
                                </li>
                                <li class="list-color-item color-swatch">
                                    <span class="tooltip">White</span>
                                    <span class="swatch-value bg_white"></span>
                                    <img class="lazyload" data-src="images/products/white-6.jpg"
                                         src="images/products/white-6.jpg" alt="image-product">
                                </li>
                                <li class="list-color-item color-swatch">
                                    <span class="tooltip">Light Grey</span>
                                    <span class="swatch-value bg_light-grey"></span>
                                    <img class="lazyload" data-src="images/products/light-grey.jpg"
                                         src="images/products/light-grey.jpg" alt="image-product">
                                </li>
                            </ul>
                            <div class="list-product-btn">
                                <a href="#quick_add" data-bs-toggle="modal" class="box-icon quick-add style-3"><span
                                        class="icon icon-bag"></span><span class="tooltip">Quick add</span></a>
                                <a href="#" class="box-icon wishlist style-3"><span class="icon icon-heart"></span>
                                    <span class="tooltip">Add to Wishlist</span></a>
                                <a href="#compare" data-bs-toggle="offcanvas" class="box-icon compare style-3"><span
                                        class="icon icon-compare"></span> <span class="tooltip">Add to
                                            Compare</span></a>
                                <a href="#quick_view" data-bs-toggle="modal" class="box-icon quickview style-3"><span
                                        class="icon icon-view"></span><span class="tooltip">Quick view</span></a>
                            </div>
                        </div>
                    </div>
                    <!-- card product 7 -->
                    <div class="card-product list-layout" data-availability="Out of stock" data-brand="M&amp;H">
                        <div class="card-product-wrapper">
                            <div class="product-img">
                                <img class="lazyload img-product" data-src="images/products/black-4.jpg"
                                     src="images/products/black-4.jpg" alt="image-product">
                                <img class="lazyload img-hover" data-src="images/products/black-5.jpg"
                                     src="images/products/black-5.jpg" alt="image-product">
                            </div>
                        </div>
                        <div class="card-product-info">
                            <a href="#" class="title link">Regular Fit Oxford Shirt</a>
                            <span class="price current-price">$10.00</span>
                            <p class="description">Button-up shirt sleeves and a relaxed silhouette. It’s tailored
                                with drapey, crinkle-texture fabric that’s made from LENZING™ ECOVERO™ Viscose —
                                responsibly sourced wood-based fibres produced through a process that reduces...</p>
                            <ul class="list-color-product">
                                <li class="list-color-item color-swatch active">
                                    <span class="tooltip">Black</span>
                                    <span class="swatch-value bg_dark"></span>
                                    <img class="lazyload" data-src="images/products/black-4.jpg"
                                         src="images/products/black-4.jpg" alt="image-product">
                                </li>
                                <li class="list-color-item color-swatch">
                                    <span class="tooltip">Dark Blue</span>
                                    <span class="swatch-value bg_dark-blue"></span>
                                    <img class="lazyload" data-src="images/products/dark-blue-2.jpg"
                                         src="images/products/dark-blue-2.jpg" alt="image-product">
                                </li>
                                <li class="list-color-item color-swatch">
                                    <span class="tooltip">Beige</span>
                                    <span class="swatch-value bg_beige"></span>
                                    <img class="lazyload" data-src="images/products/beige.jpg"
                                         src="images/products/beige.jpg" alt="image-product">
                                </li>
                                <li class="list-color-item color-swatch">
                                    <span class="tooltip">Light Blue</span>
                                    <span class="swatch-value bg_light-blue"></span>
                                    <img class="lazyload" data-src="images/products/light-blue.jpg"
                                         src="images/products/light-blue.jpg" alt="image-product">
                                </li>
                                <li class="list-color-item color-swatch">
                                    <span class="tooltip">White</span>
                                    <span class="swatch-value bg_white"></span>
                                    <img class="lazyload" data-src="images/products/white-7.jpg"
                                         src="images/products/white-7.jpg" alt="image-product">
                                </li>
                            </ul>
                            <div class="size-list">
                                <span class="size-item">S</span>
                                <span class="size-item">M</span>
                                <span class="size-item">L</span>
                            </div>
                            <div class="list-product-btn">
                                <a href="#quick_add" data-bs-toggle="modal" class="box-icon quick-add style-3"><span
                                        class="icon icon-bag"></span><span class="tooltip">Quick add</span></a>
                                <a href="#" class="box-icon wishlist style-3"><span class="icon icon-heart"></span>
                                    <span class="tooltip">Add to Wishlist</span></a>
                                <a href="#compare" data-bs-toggle="offcanvas" class="box-icon compare style-3"><span
                                        class="icon icon-compare"></span> <span class="tooltip">Add to
                                            Compare</span></a>
                                <a href="#quick_view" data-bs-toggle="modal" class="box-icon quickview style-3"><span
                                        class="icon icon-view"></span><span class="tooltip">Quick view</span></a>
                            </div>
                        </div>
                    </div>
                    <!-- card product 8 -->
                    <div class="card-product list-layout" data-availability="Out of stock" data-brand="M&amp;H">
                        <div class="card-product-wrapper">
                            <div class="product-img">
                                <img class="lazyload img-product" data-src="images/products/white-8.jpg"
                                     src="images/products/white-8.jpg" alt="image-product">
                                <img class="lazyload img-hover" data-src="images/products/black-6.jpg"
                                     src="images/products/black-6.jpg" alt="image-product">
                            </div>
                        </div>
                        <div class="card-product-info">
                            <a href="#" class="title link">Loose Fit Hoodie</a>
                            <span class="price current-price">$9.95</span>
                            <p class="description">Button-up shirt sleeves and a relaxed silhouette. It’s tailored
                                with drapey, crinkle-texture fabric that’s made from LENZING™ ECOVERO™ Viscose —
                                responsibly sourced wood-based fibres produced through a process that reduces...</p>
                            <ul class="list-color-product">
                                <li class="list-color-item color-swatch active">
                                    <span class="tooltip">White</span>
                                    <span class="swatch-value bg_white"></span>
                                    <img class="lazyload" data-src="images/products/white-8.jpg"
                                         src="images/products/white-8.jpg" alt="image-product">
                                </li>
                                <li class="list-color-item color-swatch">
                                    <span class="tooltip">Black</span>
                                    <span class="swatch-value bg_dark"></span>
                                    <img class="lazyload" data-src="images/products/black-7.jpg"
                                         src="images/products/black-7.jpg" alt="image-product">
                                </li>
                                <li class="list-color-item color-swatch">
                                    <span class="tooltip">Blue</span>
                                    <span class="swatch-value bg_blue-2"></span>
                                    <img class="lazyload" data-src="images/products/blue-2.jpg"
                                         src="images/products/blue-2.jpg" alt="image-product">
                                </li>
                            </ul>
                            <div class="size-list">
                                <span class="size-item">S</span>
                                <span class="size-item">M</span>
                                <span class="size-item">L</span>
                                <span class="size-item">XL</span>
                            </div>
                            <div class="list-product-btn">
                                <a href="#quick_add" data-bs-toggle="modal" class="box-icon quick-add style-3"><span
                                        class="icon icon-bag"></span><span class="tooltip">Quick add</span></a>
                                <a href="#" class="box-icon wishlist style-3"><span class="icon icon-heart"></span>
                                    <span class="tooltip">Add to Wishlist</span></a>
                                <a href="#compare" data-bs-toggle="offcanvas" class="box-icon compare style-3"><span
                                        class="icon icon-compare"></span> <span class="tooltip">Add to
                                            Compare</span></a>
                                <a href="#quick_view" data-bs-toggle="modal" class="box-icon quickview style-3"><span
                                        class="icon icon-view"></span><span class="tooltip">Quick view</span></a>
                            </div>
                        </div>
                    </div>
                    <!-- pagination -->
                    <ul class="wg-pagination tf-pagination-list justify-content-start">
                        <li class="active">
                            <a href="#" class="pagination-link">1</a>
                        </li>
                        <li>
                            <a href="#" class="pagination-link animate-hover-btn">2</a>
                        </li>
                        <li>
                            <a href="#" class="pagination-link animate-hover-btn">3</a>
                        </li>
                        <li>
                            <a href="#" class="pagination-link animate-hover-btn">
                                <span class="icon icon-arrow-right"></span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="tf-grid-layout wrapper-shop tf-col-4" id="gridLayout">
                    <!-- card product 1 -->
                    <div class="card-product grid" data-availability="In stock" data-brand="Ecomus">
                        <div class="card-product-wrapper">
                            <a href="product-detail.html" class="product-img">
                                <img class="img-product ls-is-cached lazyloaded" data-src="images/products/orange-1.jpg"
                                     src="images/products/orange-1.jpg" alt="image-product">
                                <img class="img-hover ls-is-cached lazyloaded" data-src="images/products/white-1.jpg"
                                     src="images/products/white-1.jpg" alt="image-product">
                            </a>
                            <div class="list-product-btn absolute-2">
                                <a href="#quick_add" data-bs-toggle="modal"
                                   class="box-icon bg_white quick-add tf-btn-loading">
                                    <span class="icon icon-bag"></span>
                                    <span class="tooltip">Quick Add</span>
                                </a>
                                <a href="#" class="box-icon bg_white wishlist btn-icon-action">
                                    <span class="icon icon-heart"></span>
                                    <span class="tooltip">Add to Wishlist</span>
                                    <span class="icon icon-delete"></span>
                                </a>
                                <a href="#compare" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft"
                                   class="box-icon bg_white compare btn-icon-action">
                                    <span class="icon icon-compare"></span>
                                    <span class="tooltip">Add to Compare</span>
                                    <span class="icon icon-check"></span>
                                </a>
                                <a href="#quick_view" data-bs-toggle="modal"
                                   class="box-icon bg_white quickview tf-btn-loading">
                                    <span class="icon icon-view"></span>
                                    <span class="tooltip">Quick View</span>
                                </a>
                            </div>
                        </div>
                        <div class="card-product-info">
                            <a href="product-detail.html" class="title link">Ribbed Tank Top</a>
                            <span class="price current-price">$16.95</span>
                            <ul class="list-color-product">
                                <li class="list-color-item color-swatch active">
                                    <span class="tooltip">Orange</span>
                                    <span class="swatch-value bg_orange-3"></span>
                                    <img class=" ls-is-cached lazyloaded" data-src="images/products/orange-1.jpg"
                                         src="images/products/orange-1.jpg" alt="image-product">
                                </li>
                                <li class="list-color-item color-swatch">
                                    <span class="tooltip">Black</span>
                                    <span class="swatch-value bg_dark"></span>
                                    <img class=" ls-is-cached lazyloaded" data-src="images/products/black-1.jpg"
                                         src="images/products/black-1.jpg" alt="image-product">
                                </li>
                                <li class="list-color-item color-swatch">
                                    <span class="tooltip">White</span>
                                    <span class="swatch-value bg_white"></span>
                                    <img class=" ls-is-cached lazyloaded" data-src="images/products/white-1.jpg"
                                         src="images/products/white-1.jpg" alt="image-product">
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- card product 2 -->
                    <div class="card-product grid" data-availability="In stock" data-brand="Ecomus">
                        <div class="card-product-wrapper">
                            <a href="product-detail.html" class="product-img">
                                <img class="img-product ls-is-cached lazyloaded" data-src="images/products/brown.jpg"
                                     src="images/products/brown.jpg" alt="image-product">
                                <img class="img-hover ls-is-cached lazyloaded" data-src="images/products/purple.jpg"
                                     src="images/products/purple.jpg" alt="image-product">
                            </a>
                            <div class="list-product-btn">
                                <a href="#quick_add" data-bs-toggle="modal"
                                   class="box-icon bg_white quick-add tf-btn-loading">
                                    <span class="icon icon-bag"></span>
                                    <span class="tooltip">Quick Add</span>
                                </a>
                                <a href="#" class="box-icon bg_white wishlist btn-icon-action">
                                    <span class="icon icon-heart"></span>
                                    <span class="tooltip">Add to Wishlist</span>
                                    <span class="icon icon-delete"></span>
                                </a>
                                <a href="#compare" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft"
                                   class="box-icon bg_white compare btn-icon-action">
                                    <span class="icon icon-compare"></span>
                                    <span class="tooltip">Add to Compare</span>
                                    <span class="icon icon-check"></span>
                                </a>
                                <a href="#quick_view" data-bs-toggle="modal"
                                   class="box-icon bg_white quickview tf-btn-loading">
                                    <span class="icon icon-view"></span>
                                    <span class="tooltip">Quick View</span>
                                </a>
                            </div>
                            <div class="size-list">
                                <span class="size-item">M</span>
                                <span class="size-item">L</span>
                                <span class="size-item">XL</span>
                            </div>
                            <div class="countdown-box">
                                <div class="js-countdown" data-timer="1007500" data-labels="d :,h :,m :,s">
                                    <div aria-hidden="true" class="countdown__timer"><span class="countdown__item"><span
                                                class="countdown__value countdown__value--0 js-countdown__value--0">11</span><span
                                                class="countdown__label">d :</span></span><span class="countdown__item"><span
                                                class="countdown__value countdown__value--1 js-countdown__value--1">15</span><span
                                                class="countdown__label">h :</span></span><span class="countdown__item"><span
                                                class="countdown__value countdown__value--2 js-countdown__value--2">49</span><span
                                                class="countdown__label">m :</span></span><span class="countdown__item"><span
                                                class="countdown__value countdown__value--3 js-countdown__value--3">07</span><span
                                                class="countdown__label">s</span></span></div>
                                </div>
                            </div>
                            <div class="on-sale-wrap text-end">
                                <div class="on-sale-item">-33%</div>
                            </div>
                        </div>
                        <div class="card-product-info">
                            <a href="product-detail.html" class="title link">Ribbed modal T-shirt</a>
                            <span class="price current-price">$18.95</span>
                            <ul class="list-color-product">
                                <li class="list-color-item color-swatch active">
                                    <span class="tooltip">Brown</span>
                                    <span class="swatch-value bg_brown"></span>
                                    <img class=" ls-is-cached lazyloaded" data-src="images/products/brown.jpg"
                                         src="images/products/brown.jpg" alt="image-product">
                                </li>
                                <li class="list-color-item color-swatch">
                                    <span class="tooltip">Light Purple</span>
                                    <span class="swatch-value bg_purple"></span>
                                    <img class=" ls-is-cached lazyloaded" data-src="images/products/purple.jpg"
                                         src="images/products/purple.jpg" alt="image-product">
                                </li>
                                <li class="list-color-item color-swatch">
                                    <span class="tooltip">Light Green</span>
                                    <span class="swatch-value bg_light-green"></span>
                                    <img class=" ls-is-cached lazyloaded" data-src="images/products/green.jpg"
                                         src="images/products/green.jpg" alt="image-product">
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- card product 3 -->
                    <div class="card-product grid" data-availability="In stock" data-brand="Ecomus">
                        <div class="card-product-wrapper">
                            <a href="product-detail.html" class="product-img">
                                <img class="img-product ls-is-cached lazyloaded" data-src="images/products/white-3.jpg"
                                     src="images/products/white-3.jpg" alt="image-product">
                                <img class="img-hover ls-is-cached lazyloaded" data-src="images/products/white-4.jpg"
                                     src="images/products/white-4.jpg" alt="image-product">
                            </a>
                            <div class="list-product-btn absolute-2">
                                <a href="#shoppingCart" data-bs-toggle="modal"
                                   class="box-icon bg_white quick-add tf-btn-loading">
                                    <span class="icon icon-bag"></span>
                                    <span class="tooltip">Add to cart</span>
                                </a>
                                <a href="#" class="box-icon bg_white wishlist btn-icon-action">
                                    <span class="icon icon-heart"></span>
                                    <span class="tooltip">Add to Wishlist</span>
                                    <span class="icon icon-delete"></span>
                                </a>
                                <a href="#compare" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft"
                                   class="box-icon bg_white compare btn-icon-action">
                                    <span class="icon icon-compare"></span>
                                    <span class="tooltip">Add to Compare</span>
                                    <span class="icon icon-check"></span>
                                </a>
                                <a href="#quick_view" data-bs-toggle="modal"
                                   class="box-icon bg_white quickview tf-btn-loading">
                                    <span class="icon icon-view"></span>
                                    <span class="tooltip">Quick View</span>
                                </a>
                            </div>
                        </div>
                        <div class="card-product-info">
                            <a href="product-detail.html" class="title link">Oversized Printed T-shirt</a>
                            <span class="price current-price">$10.00</span>
                        </div>
                    </div>
                    <!-- card product 4 -->
                    <div class="card-product grid" data-availability="In stock" data-brand="Ecomus">
                        <div class="card-product-wrapper">
                            <a href="product-detail.html" class="product-img">
                                <img class="img-product ls-is-cached lazyloaded" data-src="images/products/white-2.jpg"
                                     src="images/products/white-2.jpg" alt="image-product">
                                <img class="img-hover ls-is-cached lazyloaded" data-src="images/products/pink-1.jpg"
                                     src="images/products/pink-1.jpg" alt="image-product">
                            </a>
                            <div class="list-product-btn">
                                <a href="#quick_add" data-bs-toggle="modal"
                                   class="box-icon bg_white quick-add tf-btn-loading">
                                    <span class="icon icon-bag"></span>
                                    <span class="tooltip">Quick Add</span>
                                </a>
                                <a href="#" class="box-icon bg_white wishlist btn-icon-action">
                                    <span class="icon icon-heart"></span>
                                    <span class="tooltip">Add to Wishlist</span>
                                    <span class="icon icon-delete"></span>
                                </a>
                                <a href="#compare" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft"
                                   class="box-icon bg_white compare btn-icon-action">
                                    <span class="icon icon-compare"></span>
                                    <span class="tooltip">Add to Compare</span>
                                    <span class="icon icon-check"></span>
                                </a>
                                <a href="#quick_view" data-bs-toggle="modal"
                                   class="box-icon bg_white quickview tf-btn-loading">
                                    <span class="icon icon-view"></span>
                                    <span class="tooltip">Quick View</span>
                                </a>
                            </div>
                            <div class="size-list">
                                <span class="size-item">S</span>
                                <span class="size-item">M</span>
                                <span class="size-item">L</span>
                                <span class="size-item">XL</span>
                            </div>
                        </div>
                        <div class="card-product-info">
                            <a href="product-detail.html" class="title">Oversized Printed T-shirt</a>
                            <span class="price current-price">$16.95</span>
                            <ul class="list-color-product">
                                <li class="list-color-item color-swatch active">
                                    <span class="tooltip">White</span>
                                    <span class="swatch-value bg_white"></span>
                                    <img class=" ls-is-cached lazyloaded" data-src="images/products/white-2.jpg"
                                         src="images/products/white-2.jpg" alt="image-product">
                                </li>
                                <li class="list-color-item color-swatch">
                                    <span class="tooltip">Pink</span>
                                    <span class="swatch-value bg_purple"></span>
                                    <img class=" ls-is-cached lazyloaded" data-src="images/products/pink-1.jpg"
                                         src="images/products/pink-1.jpg" alt="image-product">
                                </li>
                                <li class="list-color-item color-swatch">
                                    <span class="tooltip">Black</span>
                                    <span class="swatch-value bg_dark"></span>
                                    <img class=" ls-is-cached lazyloaded" data-src="images/products/black-2.jpg"
                                         src="images/products/black-2.jpg" alt="image-product">
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- pagination -->
                    <ul class="wg-pagination tf-pagination-list">
                        <li class="active">
                            <a href="#" class="pagination-link">1</a>
                        </li>
                        <li>
                            <a href="#" class="pagination-link animate-hover-btn">2</a>
                        </li>
                        <li>
                            <a href="#" class="pagination-link animate-hover-btn">3</a>
                        </li>
                        <li>
                            <a href="#" class="pagination-link animate-hover-btn">4</a>
                        </li>
                        <li>
                            <a href="#" class="pagination-link animate-hover-btn">
                                <span class="icon icon-arrow-right"></span>
                            </a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </section>

</div>
