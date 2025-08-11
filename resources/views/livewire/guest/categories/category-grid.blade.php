<div>
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
                    <a href="#filterShop" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft" class="tf-btn-filter"><span class="icon icon-filter"></span><span class="text">Filter</span></a>
                </div>
                <ul class="tf-control-layout d-flex justify-content-center">
                    <li class="tf-view-layout-switch sw-layout-2" data-value-layout="tf-col-2" wire:click="setLayout('grid-2')">
                        <div class="item {{ $layout === 'grid-2' ? 'active' : '' }}"><span class="icon icon-grid-2"></span></div>
                    </li>
                    <li class="tf-view-layout-switch sw-layout-3 {{ $layout === 'grid-3' ? 'active' : '' }}" data-value-layout="tf-col-3" wire:click="setLayout('grid-3')">
                        <div class="item"><span class="icon icon-grid-3"></span></div>
                    </li>
                    <li class="tf-view-layout-switch sw-layout-4" data-value-layout="tf-col-4" wire:click="setLayout('grid-4')">
                        <div class="item {{ $layout === 'grid-4' ? 'active' : '' }}"><span class="icon icon-grid-4"></span></div>
                    </li>
                </ul>
                <div class="tf-control-sorting d-flex justify-content-end">
                    <div class="tf-dropdown-sort" data-bs-toggle="dropdown">
                        <div class="btn-select">
                            <span class="text-sort-value">{{ ucfirst(str_replace('_', ' ', $sortBy)) }}</span>
                            <span class="icon icon-arrow-down"></span>
                        </div>
                        <div class="dropdown-menu">
                            <div class="select-item {{ $sortBy === 'featured' ? 'active' : '' }}" wire:click="setSortBy('featured')">
                                <span class="text-value-item">Featured</span>
                            </div>
                            <div class="select-item {{ $sortBy === 'name_asc' ? 'active' : '' }}" wire:click="setSortBy('name_asc')">
                                <span class="text-value-item">Alphabetically, A-Z</span>
                            </div>
                            <div class="select-item {{ $sortBy === 'name_desc' ? 'active' : '' }}" wire:click="setSortBy('name_desc')">
                                <span class="text-value-item">Alphabetically, Z-A</span>
                            </div>
                            <div class="select-item {{ $sortBy === 'created_at_desc' ? 'active' : '' }}" wire:click="setSortBy('created_at_desc')">
                                <span class="text-value-item">Date, new to old</span>
                            </div>
                            <div class="select-item {{ $sortBy === 'created_at_asc' ? 'active' : '' }}" wire:click="setSortBy('created_at_asc')">
                                <span class="text-value-item">Date, old to new</span>
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
                                            <a href="#" wire:click.prevent="setType('occasion')"><span>By Occasion</span></a>
                                        </li>
                                        <li class="cate-item {{ $selectedType === 'collection' ? 'current' : '' }}">
                                            <a href="#" wire:click.prevent="setType('collection')"><span>Collections</span></a>
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
                                                <input type="radio" wire:model.live="availability" value="all" class="tf-check" id="allAvailable">
                                                <label for="allAvailable" class="label"><span>All categories</span></label>
                                            </li>
                                            <li class="list-item d-flex gap-12 align-items-center">
                                                <input type="radio" wire:model.live="availability" value="with_products" class="tf-check" id="withProducts">
                                                <label for="withProducts" class="label"><span>With products</span></label>
                                            </li>
                                            <li class="list-item d-flex gap-12 align-items-center">
                                                <input type="radio" wire:model.live="availability" value="empty" class="tf-check" id="emptyCategories">
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
                                                <input type="checkbox" wire:model.live="selectedBrands" value="{{ $brand->id }}" class="tf-check" id="brand{{ $brand->id }}">
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
                                                <input type="checkbox" wire:model.live="selectedColors" value="{{ $color->value }}"
                                                       class="tf-check-color" style="background-color: {{ $color->value }}"
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
                                                <input type="checkbox" wire:model.live="selectedSizes" value="{{ $size->value }}"
                                                       class="tf-check tf-check-size" id="size{{ $loop->index }}">
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
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tf-shop-content wrapper-control-shop">
                    <div class="meta-filter-shop">
                        <div class="count-text">
                            @if(isset($categories) && $categories->total() > 0)
                                Showing {{ $categories->firstItem() }}-{{ $categories->lastItem() }} of {{ $categories->total() }} categories
                            @else
                                No categories found
                            @endif
                        </div>
                        @if(isset($hasFilters) && $hasFilters)
                        <button wire:click="clearFilters" class="remove-all-filters">Remove All <i class="icon icon-close"></i></button>
                        @endif
                    </div>
                    <div class="tf-grid-layout wrapper-shop tf-col-{{ str_replace('grid-', '', $layout ?? '3') }}">
                        @if(isset($categories) && $categories->count() > 0)
                            @foreach($categories as $category)
                            <div class="card-product" data-availability="active" data-brand="{{ $category->brands->pluck('name')->implode(',') ?? '' }}">
                                <div class="card-product-wrapper">
                                    <a href="{{ route('products.category', $category->slug) }}" class="product-img">
                                        <img class="lazyload img-product"
                                             data-src="{{ $category->image_url ?? asset('images/placeholder-category.jpg') }}"
                                             src="{{ $category->image_url ?? asset('images/placeholder-category.jpg') }}"
                                             alt="{{ $category->name }}">
                                    </a>
                                    <div class="list-product-btn absolute-2">
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
                                    </div>
                                    @if(isset($category->type) && $category->type)
                                    <div class="on-sale-wrap text-end">
                                        <div class="on-sale-item category-type">{{ ucfirst($category->type) }}</div>
                                    </div>
                                    @endif
                                </div>
                                <div class="card-product-info">
                                    <a href="{{ route('products.category', $category->slug) }}" class="title link">{{ $category->name }}</a>
                                    <span class="price">{{ $category->products_count ?? 0 }} {{ Str::plural('product', $category->products_count ?? 0) }}</span>
                                    @if($category->description)
                                    <p class="category-description">{{ Str::limit($category->description, 100) }}</p>
                                    @endif
                                    @if(isset($category->colors) && $category->colors && $category->colors->isNotEmpty())
                                    <ul class="list-color-product">
                                        @foreach($category->colors->take(5) as $color)
                                        <li class="list-color-item color-swatch">
                                            <span class="tooltip">{{ $color->name }}</span>
                                            <span class="swatch-value" style="background-color: {{ $color->value }}"></span>
                                        </li>
                                        @endforeach
                                        @if($category->colors->count() > 5)
                                        <li class="list-color-item">
                                            <span class="text-muted small">+{{ $category->colors->count() - 5 }}</span>
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
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
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
                        <div class="col-12">
                            <ul class="wg-pagination tf-pagination-list justify-content-center">
                                 Previous Page Link
                                @if ($categories->onFirstPage())
                                    <li class="disabled"><span class="pagination-link">«</span></li>
                                @else
                                    <li><a href="#" wire:click="previousPage" wire:loading.attr="disabled" class="pagination-link animate-hover-btn">«</a></li>
                                @endif

                                 Pagination Elements
                                @foreach ($categories->getUrlRange(1, $categories->lastPage()) as $page => $url)
                                    @if ($page == $categories->currentPage())
                                        <li class="active"><span class="pagination-link">{{ $page }}</span></li>
                                    @else
                                        <li><a href="#" wire:click="gotoPage({{ $page }})" wire:loading.attr="disabled" class="pagination-link animate-hover-btn">{{ $page }}</a></li>
                                    @endif
                                @endforeach

                                 Next Page Link
                                @if ($categories->hasMorePages())
                                    <li><a href="#" wire:click="nextPage" wire:loading.attr="disabled" class="pagination-link animate-hover-btn">»</a></li>
                                @else
                                    <li class="disabled"><span class="pagination-link">»</span></li>
                                @endif
                            </ul>
                        </div>
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
                    <!-- Mobile filter content - same as desktop sidebar -->
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
                                    <a href="#" wire:click.prevent="setType('occasion')" data-bs-dismiss="offcanvas"><span>By Occasion</span></a>
                                </li>
                                <li class="cate-item {{ $selectedType === 'collection' ? 'current' : '' }}">
                                    <a href="#" wire:click.prevent="setType('collection')" data-bs-dismiss="offcanvas"><span>Collections</span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    @if(isset($hasFilters) && $hasFilters)
                    <div class="text-center mb-4">
                        <button wire:click="clearFilters" class="tf-btn btn-outline w-100" data-bs-dismiss="offcanvas">Clear All Filters</button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle layout switching
    document.querySelectorAll('.tf-view-layout-switch').forEach(function(el) {
        el.addEventListener('click', function() {
            document.querySelectorAll('.tf-view-layout-switch .item').forEach(function(item) {
                item.classList.remove('active');
            });
            this.querySelector('.item').classList.add('active');
        });
    });

    // Handle dropdown sorting
    document.querySelectorAll('.tf-dropdown-sort .select-item').forEach(function(el) {
        el.addEventListener('click', function() {
            document.querySelectorAll('.tf-dropdown-sort .select-item').forEach(function(item) {
                item.classList.remove('active');
            });
            this.classList.add('active');
            const selectedText = this.querySelector('.text-value-item').textContent;
            document.querySelector('.text-sort-value').textContent = selectedText;
            // Close dropdown
            document.querySelector('.tf-dropdown-sort').classList.remove('show');
        });
    });

    // Toggle dropdown
    document.querySelector('.tf-dropdown-sort .btn-select')?.addEventListener('click', function() {
        const dropdown = this.closest('.tf-dropdown-sort');
        dropdown.classList.toggle('show');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.tf-dropdown-sort')) {
            document.querySelectorAll('.tf-dropdown-sort').forEach(function(dropdown) {
                dropdown.classList.remove('show');
            });
        }
    });

    // Mobile sidebar functionality
    const filterButton = document.querySelector('.filterShop button');
    const overlay = document.getElementById('overlay-filter');

    if (filterButton && overlay) {
        filterButton.addEventListener('click', function() {
            overlay.classList.add('show');
        });

        overlay.addEventListener('click', function() {
            this.classList.remove('show');
        });
    }

    // Collapsible filter sections
    document.querySelectorAll('.facet-title').forEach(function(title) {
        title.addEventListener('click', function() {
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
    document.addEventListener('livewire:loading', function(e) {
        if (e.detail.component.name === 'guest.categories.category-grid') {
            const loadingEl = document.querySelector('.tf-shop-content');
            if (loadingEl) {
                loadingEl.style.opacity = '0.7';
                loadingEl.style.pointerEvents = 'none';
            }
        }
    });

    document.addEventListener('livewire:loaded', function(e) {
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

<style>
.tf-dropdown-sort {
    position: relative;
}

.tf-dropdown-sort.show .dropdown-menu {
    display: block;
}

.tf-dropdown-sort .dropdown-menu {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    z-index: 1000;
    background: white;
    border: 1px solid #e5e5e5;
    border-radius: 4px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    min-width: 200px;
}

.tf-dropdown-sort .select-item {
    padding: 10px 15px;
    cursor: pointer;
    transition: background-color 0.2s;
}

.tf-dropdown-sort .select-item:hover {
    background-color: #f8f9fa;
}

.tf-dropdown-sort .select-item.active {
    background-color: #e3f2fd;
    color: #1976d2;
}

.category-description {
    color: #666;
    line-height: 1.4;
    margin-top: 8px;
    font-size: 0.875rem;
}

.card-product .color-swatch .swatch-value {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: inline-block;
    border: 1px solid #ddd;
    transition: transform 0.2s;
}

.card-product .color-swatch:hover .swatch-value {
    transform: scale(1.2);
}

.card-product:hover .product-img img {
    transform: scale(1.05);
    transition: transform 0.3s ease;
}

.category-type {
    background-color: #007bff;
    color: white;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
}

.category-brands {
    margin-top: 8px;
}

.card-product .price {
    color: #666;
    font-weight: 500;
}

.card-product .title {
    font-weight: 600;
    color: #333;
}

.card-product .title:hover {
    color: #007bff;
}

.list-product-btn .category-colors-btn {
    display: flex;
    align-items: center;
    justify-content: center;
}

.overlay-filter.show {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1040;
    display: block;
}

@media (max-width: 767px) {
    .tf-shop-control {
        flex-direction: column;
        gap: 1rem;
    }

    .tf-control-layout {
        order: -1;
    }

    .tf-grid-layout.tf-col-2 .collection-item,
    .tf-grid-layout.tf-col-3 .collection-item,
    .tf-grid-layout.tf-col-4 .collection-item {
        width: 100%;
        max-width: none;
    }
}
</style>
@endpush
