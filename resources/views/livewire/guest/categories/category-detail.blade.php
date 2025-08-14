<!-- Category Details Page with Blog-Style Layout -->
<div>
    <!-- Page Title Section -->
    <div class="tf-page-title">
        <div class="container-full">
            <div class="row">
                <div class="col-12">
                    <div class="heading text-center">{{ $category->name }}</div>
                    <ul class="breadcrumbs d-flex align-items-center justify-content-center">
                        <li>
                            <a href="{{ route('home') }}" wire:navigate>Home</a>
                        </li>
                        <li>
                            <i class="icon-arrow-right"></i>
                        </li>
                        <li>
                            <a href="{{ route('categories.index') }}" wire:navigate>Categories</a>
                        </li>
                        @foreach($category->breadcrumbs ?? [] as $breadcrumb)
                            @if(!$loop->last)
                                <li>
                                    <i class="icon-arrow-right"></i>
                                </li>
                                <li>
                                    <a href="{{ $breadcrumb['url'] }}" wire:navigate>{{ $breadcrumb['name'] }}</a>
                                </li>
                            @else
                                <li>
                                    <i class="icon-arrow-right"></i>
                                </li>
                                <li>
                                    {{ $breadcrumb['name'] }}
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Title Section -->

    <!-- Category Banner -->
    @if($category->image_url)
        <div class="container mt-4 mb-5">
            <div class="row">
                <div class="col-12">
                    <div class="tf-banner-category position-relative overflow-hidden rounded-3 shadow-sm">
                        <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="img-fluid w-100" style="max-height: 400px; object-fit: cover;">
                        <div class="banner-content position-absolute top-50 start-0 translate-middle-y p-5 bg-white bg-opacity-75 rounded-end-3">
                            <h2 class="fw-bold text-dark">{{ $category->name }}</h2>
                            <h4 class="text-muted mb-3">Explore Our Curated Collection</h4>
                            <p class="text-secondary">{{ $category->description ?? 'Indulge in style and comfort with our exclusive range.' }}</p>
                            <a href="{{ route('shop.index', ['category' => $category->slug]) }}" class="tf-btn btn-fill radius-3 animate-hover-btn">Shop Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Subcategories Section -->
    @if($category->children->count() > 0)
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="fw-bold">Subcategories</h2>
                        <button wire:click="toggleSubcategories" class="tf-btn btn-line radius-3">
                            {{ $showSubcategories ? 'Hide' : 'Show' }} Subcategories
                        </button>
                    </div>
                    @if($showSubcategories)
                        <div class="swiper category-slider" wire:transition>
                            <div class="swiper-wrapper">
                                @foreach($category->children as $child)
                                    <div class="swiper-slide">
                                        <div class="blog-article-item style-grid h-100">
                                            <div class="article-thumb">
                                                <a href="{{ route('categories.show', $child->slug) }}" wire:navigate>
                                                    <img src="{{ $child->image_url ?? asset('images/category-placeholder.jpg') }}"
                                                         alt="{{ $child->name }}" class="img-fluid">
                                                </a>
                                            </div>
                                            <div class="article-content text-center">
                                                <div class="article-title">
                                                    <a href="{{ route('categories.show', $child->slug) }}" wire:navigate class="">
                                                        {{ $child->name }}
                                                    </a>
                                                </div>
                                                <div class="desc">
                                                    {{ $child->products_count ?? 0 }} {{ Str::plural('product', $child->products_count ?? 0) }}
                                                </div>
                                                <div class="article-btn">
                                                    <a href="{{ route('categories.show', $child->slug) }}" wire:navigate class="tf-btn btn-line fw-6">
                                                        View Category<i class="icon icon-arrow1-top-left"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Products List Section -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <!-- Filter Controls -->
                <div class="tf-shop-control grid-3 align-items-center mb-4">
                    <div class="tf-control-filter">
                        <a href="#filterShop" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft"
                           class="tf-btn btn-line radius-3 tf-btn-filter">
                            <i class="icon icon-filter"></i><span>Filter</span>
                        </a>
                    </div>
                    <ul class="tf-control-layout d-flex justify-content-center gap-2">
                        <li class="tf-view-layout-switch sw-layout-2 {{ ($layout ?? 'grid-3') === 'grid-2' ? 'active' : '' }}"
                            data-value-layout="row-cols-md-2" wire:click="setLayout('grid-2')">
                            <div class="item"><i class="bi bi-grid"></i></div>
                        </li>
                        <li class="tf-view-layout-switch sw-layout-3 {{ ($layout ?? 'grid-3') === 'grid-3' ? 'active' : '' }}"
                            data-value-layout="row-cols-md-3" wire:click="setLayout('grid-3')">
                            <div class="item"><i class="bi bi-grid-3x3"></i></div>
                        </li>
                        <li class="tf-view-layout-switch sw-layout-4 {{ ($layout ?? 'grid-3') === 'grid-4' ? 'active' : '' }}"
                            data-value-layout="row-cols-md-4" wire:click="setLayout('grid-4')">
                            <div class="item"><i class="bi bi-grid-fill"></i></div>
                        </li>
                    </ul>
                    <div class="tf-control-sorting d-flex justify-content-end">
                        <div class="tf-dropdown-sort dropdown">
                            <button class="tf-btn btn-line radius-3 dropdown-toggle" data-bs-toggle="dropdown">
                                <span class="text-sort-value">{{ ucfirst(str_replace('_', ' ', $sortBy ?? 'featured')) }}</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <div class="dropdown-item {{ ($sortBy ?? 'featured') === 'featured' ? 'active' : '' }}"
                                     wire:click="setSortBy('featured')">Featured
                                </div>
                                <div class="dropdown-item {{ ($sortBy ?? 'featured') === 'name_asc' ? 'active' : '' }}"
                                     wire:click="setSortBy('name_asc')">Alphabetically, A-Z
                                </div>
                                <div class="dropdown-item {{ ($sortBy ?? 'featured') === 'name_desc' ? 'active' : '' }}"
                                     wire:click="setSortBy('name_desc')">Alphabetically, Z-A
                                </div>
                                <div class="dropdown-item {{ ($sortBy ?? 'featured') === 'created_at_desc' ? 'active' : '' }}"
                                     wire:click="setSortBy('created_at_desc')">Date, new to old
                                </div>
                                <div class="dropdown-item {{ ($sortBy ?? 'featured') === 'created_at_asc' ? 'active' : '' }}"
                                     wire:click="setSortBy('created_at_asc')">Date, old to new
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Count Info -->
                <div class="meta-filter-shop mb-4">
                    <div class="count-text">
                        @if(isset($products) && method_exists($products, 'total') && $products->total() > 0)
                            Showing {{ $products->firstItem() }}-{{ $products->lastItem() }} of {{ $products->total() }} products
                        @elseif(isset($products) && $products->count() > 0)
                            Showing {{ $products->count() }} products
                        @else
                            No products found
                        @endif
                    </div>
                    @if(isset($hasFilters) && $hasFilters)
                        <button wire:click="clearFilters" class="tf-btn btn-sm radius-3 btn-outline-danger">
                            Remove All Filters <i class="bi bi-x"></i>
                        </button>
                    @endif
                </div>

                <!-- Products List -->
                @if($products && $products->count() > 0)
                    <div class="list-blog">
                        @foreach($products as $product)
                            <div class="blog-article-item style-row">
                                @php
                                    $primary = method_exists($product, 'getFirstMediaUrl') ?
                                        ($product->getFirstMediaUrl('images', 'large') ?: $product->getFirstMediaUrl('images')) :
                                        ($product->image_url ?? asset('images/placeholder-product.jpg'));
                                    $hover = method_exists($product, 'getMedia') ?
                                        ($product->getMedia('images')->get(1)?->getUrl('large') ?? $primary) :
                                        $primary;
                                @endphp
                                <div class="article-thumb position-relative">
                                    <a href="{{ route('products.show', $product->slug) }}">
                                        <img class="img-fluid" src="{{ $primary }}" alt="{{ $product->name }}">
                                    </a>
                                    @if(isset($product->sale_price) && $product->sale_price && $product->sale_price < $product->price)
                                        <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                                            -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                                        </span>
                                    @endif
                                </div>
                                <div class="article-content">
                                    @if(isset($product->brand) && $product->brand)
                                        <div class="article-label">
                                            <a href="#" class="tf-btn btn-sm radius-3 btn-fill animate-hover-btn">
                                                {{ $product->brand }}
                                            </a>
                                        </div>
                                    @endif
                                    <div class="article-title">
                                        <a href="{{ route('products.show', $product->slug) }}" class="">
                                            {{ $product->name }}
                                        </a>
                                    </div>
                                    <div class="price-block mb-3">
                                        @if(isset($product->sale_price) && $product->sale_price && $product->sale_price < $product->price)
                                            <span class="text-primary fw-bold">${{ number_format($product->sale_price, 2) }}</span>
                                            <span class="text-muted text-decoration-line-through">${{ number_format($product->price, 2) }}</span>
                                        @else
                                            <span class="text-primary fw-bold">${{ number_format($product->price, 2) }}</span>
                                        @endif
                                    </div>
                                    @if(isset($product->variants) && $product->variants->where('type', 'color')->count() > 0)
                                        <ul class="list-color-product d-flex gap-2 mb-3">
                                            @foreach($product->variants->where('type', 'color')->take(3) as $colorVariant)
                                                <li class="list-color-item color-swatch {{ $loop->first ? 'active' : '' }}">
                                                    <span class="tooltip">{{ $colorVariant->value }}</span>
                                                    <span class="swatch-value rounded-circle border"
                                                          style="background-color: {{ isset($this) && method_exists($this, 'getColorCode') ? $this->getColorCode($colorVariant->value) : '#cccccc' }}; width: 20px; height: 20px;"></span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                    <div class="desc">
                                        {{ Str::limit($product->description ?? '', 100) }}
                                    </div>
                                    <div class="article-btn d-flex gap-2">
                                        <a href="{{ route('products.show', $product->slug) }}" class="tf-btn btn-line fw-6">
                                            View Details<i class="icon icon-arrow1-top-left"></i>
                                        </a>
                                        <button class="tf-btn btn-sm btn-fill radius-3 animate-hover-btn" data-bs-toggle="modal"
                                                data-bs-target="#quick_add" data-product-id="{{ $product->id }}">
                                            <i class="bi bi-cart-plus me-1"></i>Quick Add
                                        </button>
                                        <button class="tf-btn btn-sm btn-line radius-3"
                                                wire:click="toggleWishlist({{ $product->id }})">
                                            <i class="bi bi-heart"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if(isset($hasMoreProducts) && $hasMoreProducts)
                        <div class="text-center mt-5">
                            <button wire:click="loadMoreProducts" class="tf-btn btn-fill radius-3 animate-hover-btn btn-lg">
                                <span wire:loading.remove wire:target="loadMoreProducts">Load More Products</span>
                                <span wire:loading wire:target="loadMoreProducts">Loading...</span>
                            </button>
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-tags display-4 text-muted"></i>
                        <h4 class="mt-3">No products in this category</h4>
                        <p class="text-secondary">Products will appear here once they are added to this category.</p>
                        <a href="{{ route('shop.index') }}" class="tf-btn btn-fill radius-3 animate-hover-btn">Continue Shopping</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Filter Offcanvas -->
    <div class="offcanvas offcanvas-start canvas-filter" id="filterShop">
        <div class="canvas-wrapper">
            <header class="canvas-header d-flex justify-content-between align-items-center p-3">
                <div class="filter-icon">
                    <i class="bi bi-filter me-1"></i><span>Filter</span>
                </div>
                <button class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </header>
            <div class="canvas-body p-3">
                <form wire:submit.prevent class="facet-filter-form">
                    @if(isset($brands) && $brands->isNotEmpty())
                        <div class="widget-facet mb-4">
                            <div class="facet-title d-flex justify-content-between align-items-center"
                                 data-bs-target="#brand" data-bs-toggle="collapse" aria-expanded="true"
                                 aria-controls="brand">
                                <span>Brand</span>
                                <i class="bi bi-chevron-up"></i>
                            </div>
                            <div id="brand" class="collapse show">
                                <ul class="tf-filter-group list-unstyled mt-3">
                                    @foreach($brands as $brand)
                                        <li class="list-item d-flex gap-2 align-items-center">
                                            <input type="checkbox" wire:model.live="selectedBrands"
                                                   value="{{ $brand->id }}" class="form-check-input"
                                                   id="brand{{ $brand->id }}">
                                            <label for="brand{{ $brand->id }}" class="form-check-label">
                                                {{ $brand->name }} ({{ $brand->products_count ?? 0 }})
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                    @if(isset($colors) && $colors->isNotEmpty())
                        <div class="widget-facet mb-4">
                            <div class="facet-title d-flex justify-content-between align-items-center"
                                 data-bs-target="#color" data-bs-toggle="collapse" aria-expanded="true"
                                 aria-controls="color">
                                <span>Color</span>
                                <i class="bi bi-chevron-up"></i>
                            </div>
                            <div id="color" class="collapse show">
                                <ul class="tf-filter-group list-unstyled mt-3">
                                    @foreach($colors as $color)
                                        <li class="list-item d-flex gap-2 align-items-center">
                                            <input type="checkbox" wire:model.live="selectedColors"
                                                   value="{{ $color->value }}"
                                                   class="form-check-input"
                                                   style="background-color: {{ $color->value }}"
                                                   id="color{{ $loop->index }}">
                                            <label for="color{{ $loop->index }}" class="form-check-label">
                                                {{ $color->name }} ({{ $color->products_count ?? 0 }})
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                    @if(isset($sizes) && $sizes->isNotEmpty())
                        <div class="widget-facet mb-4">
                            <div class="facet-title d-flex justify-content-between align-items-center"
                                 data-bs-target="#size" data-bs-toggle="collapse" aria-expanded="true"
                                 aria-controls="size">
                                <span>Size</span>
                                <i class="bi bi-chevron-up"></i>
                            </div>
                            <div id="size" class="collapse show">
                                <ul class="tf-filter-group list-unstyled mt-3">
                                    @foreach($sizes as $size)
                                        <li class="list-item d-flex gap-2 align-items-center">
                                            <input type="checkbox" wire:model.live="selectedSizes"
                                                   value="{{ $size->value }}"
                                                   class="form-check-input" id="size{{ $loop->index }}">
                                            <label for="size{{ $loop->index }}" class="form-check-label">
                                                {{ $size->value }} ({{ $size->products_count ?? 0 }})
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                    <div class="widget-facet mb-4">
                        <div class="facet-title d-flex justify-content-between align-items-center"
                             data-bs-target="#search" data-bs-toggle="collapse" aria-expanded="true"
                             aria-controls="search">
                            <span>Search Products</span>
                            <i class="bi bi-chevron-up"></i>
                        </div>
                        <div id="search" class="collapse show">
                            <div class="input-group mt-3">
                                <input type="text" wire:model.debounce.400ms="search" class="form-control"
                                       placeholder="Search products..." aria-label="Search products">
                                <button type="button" class="btn btn-outline-secondary"><i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @if(isset($hasFilters) && $hasFilters)
                        <div class="text-center">
                            <button wire:click="clearFilters" class="tf-btn btn-outline-danger w-100 radius-3"
                                    data-bs-dismiss="offcanvas">Clear All Filters
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Initialize Swiper
                new Swiper('.category-slider', {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    breakpoints: {
                        640: {slidesPerView: 2},
                        768: {slidesPerView: 3},
                        1024: {slidesPerView: 4}
                    }
                });

                // Hover image effect
                document.querySelectorAll('.article-thumb').forEach(thumb => {
                    const img = thumb.querySelector('img');
                    const hoverImg = thumb.querySelector('.hover-img');

                    if (img && hoverImg) {
                        thumb.addEventListener('mouseenter', () => {
                            img.style.opacity = '0';
                            hoverImg.style.opacity = '1';
                        });

                        thumb.addEventListener('mouseleave', () => {
                            img.style.opacity = '1';
                            hoverImg.style.opacity = '0';
                        });
                    }
                });

                // Layout switching
                document.querySelectorAll('.tf-view-layout-switch').forEach(el => {
                    el.addEventListener('click', () => {
                        document.querySelectorAll('.tf-view-layout-switch').forEach(item => {
                            item.classList.remove('active');
                        });
                        el.classList.add('active');
                    });
                });

                // Dropdown sorting
                document.querySelectorAll('.tf-dropdown-sort .dropdown-item').forEach(el => {
                    el.addEventListener('click', () => {
                        document.querySelectorAll('.tf-dropdown-sort .dropdown-item').forEach(item => {
                            item.classList.remove('active');
                        });
                        el.classList.add('active');
                        const selectedText = el.textContent;
                        document.querySelector('.text-sort-value').textContent = selectedText;
                    });
                });

                // Collapsible filter sections
                document.querySelectorAll('.facet-title').forEach(title => {
                    title.addEventListener('click', () => {
                        const target = document.querySelector(title.getAttribute('data-bs-target'));
                        const icon = title.querySelector('i');
                        if (target.classList.contains('show')) {
                            icon.classList.remove('bi-chevron-up');
                            icon.classList.add('bi-chevron-down');
                        } else {
                            icon.classList.remove('bi-chevron-down');
                            icon.classList.add('bi-chevron-up');
                        }
                    });
                });
            });
        </script>

        <style>
            /* Blog-style layout styles */
            .blog-article-item.style-row {
                display: flex;
                margin-bottom: 30px;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 5px 15px rgba(0,0,0,0.05);
                background-color: #fff;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .blog-article-item.style-row:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            }

            .blog-article-item.style-row .article-thumb {
                flex: 0 0 40%;
                overflow: hidden;
            }

            .blog-article-item.style-row .article-thumb img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.5s ease;
            }

            .blog-article-item.style-row:hover .article-thumb img {
                transform: scale(1.05);
            }

            .blog-article-item.style-row .article-content {
                flex: 0 0 60%;
                padding: 25px;
            }

            .blog-article-item .article-label {
                margin-bottom: 15px;
            }

            .blog-article-item .article-title {
                margin-bottom: 15px;
            }

            .blog-article-item .article-title a {
                font-size: 24px;
                font-weight: 700;
                color: #333;
                text-decoration: none;
                transition: color 0.3s ease;
            }

            .blog-article-item .article-title a:hover {
                color: #007bff;
            }

            .blog-article-item .desc {
                margin-bottom: 20px;
                color: #666;
                line-height: 1.6;
            }

            .blog-article-item .article-btn {
                margin-top: auto;
            }

            /* Category Slider Styles */
            .blog-article-item.style-grid {
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 5px 15px rgba(0,0,0,0.05);
                background-color: #fff;
                transition: transform 0.3s ease;
            }

            .blog-article-item.style-grid:hover {
                transform: translateY(-5px);
            }

            .blog-article-item.style-grid .article-thumb {
                overflow: hidden;
            }

            .blog-article-item.style-grid .article-thumb img {
                width: 100%;
                height: 200px;
                object-fit: cover;
                transition: transform 0.5s ease;
            }

            .blog-article-item.style-grid:hover .article-thumb img {
                transform: scale(1.05);
            }

            .blog-article-item.style-grid .article-content {
                padding: 20px;
            }

            /* Filter and Sort Controls */
            .tf-shop-control {
                display: grid;
                grid-template-columns: 1fr 1fr 1fr;
                align-items: center;
                margin-bottom: 30px;
            }

            .tf-view-layout-switch {
                cursor: pointer;
                padding: 5px 10px;
                border-radius: 5px;
                transition: background-color 0.3s ease;
            }

            .tf-view-layout-switch.active {
                background-color: #f0f0f0;
                color: #007bff;
            }

            /* Banner Category */
            .tf-banner-category {
                position: relative;
                margin-bottom: 40px;
            }

            /* Responsive fixes */
            @media (max-width: 768px) {
                .blog-article-item.style-row {
                    flex-direction: column;
                }

                .blog-article-item.style-row .article-thumb,
                .blog-article-item.style-row .article-content {
                    flex: 0 0 100%;
                }

                .tf-shop-control {
                    grid-template-columns: 1fr;
                    gap: 15px;
                }
            }
        </style>
    @endpush
</div>
