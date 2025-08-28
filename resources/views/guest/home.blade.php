<x-app-layout :isAuthPage="false" :cartCount="$cartCount ?? 0" :wishlistCount="$wishlistCount ?? 0"
              :languages="$languages ?? ['English']" :selectedLanguage="$selectedLanguage ?? 'English'">

    <x-slot name="title">Home - ShopWithCarl</x-slot>

    <!-- preload -->
    <div class="preload preload-container">
        <div class="preload-logo">
            <div class="spinner"></div>
        </div>
    </div>
    <!-- /preload -->
    <div id="wrapper">
        <!-- Slider -->
        <div class="slider-container">
            <div class="slides-wrapper">
                @forelse($banners as $index => $banner)
                    <div class="slide {{ $loop->first ? 'active' : '' }}" data-slide="{{ $index }}">
                        <div class="slide-bg" style="background-image: url('{{ $banner->image_url }}')"></div>
                        <div class="slide-particles">
                            @for($i = 1; $i <= 9; $i++)
                                <div class="particle"
                                     style="left: {{ 10 + ($i * 10) }}%; animation-delay: {{ ($i % 6) }}s;"></div>
                            @endfor
                        </div>
                        <div class="floating-elements">
                            <div class="floating-element"></div>
                            <div class="floating-element"></div>
                            <div class="floating-element"></div>
                        </div>
                        <div class="slide-number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</div>
                        <div class="slide-content">
                            @if($banner->subtitle)
                                <div class="slide-subtitle">{{ $banner->subtitle }}</div>
                            @endif
                            <h1 class="slide-title">{!! nl2br(e($banner->title)) !!}</h1>
                            @if($banner->description)
                                <p class="slide-description">{{ $banner->description }}</p>
                            @endif
                            <div class="cta-group">
                                @if($banner->button_text && $banner->button_link)
                                    <a href="{{ $banner->button_link }}" class="cta-button">
                                        {{ $banner->button_text }}
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2">
                                            <path d="M5 12h14M12 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                @endif
                                @if($banner->secondary_button_text && $banner->secondary_button_link)
                                    <a href="{{ $banner->secondary_button_link }}" class="cta-button cta-secondary">
                                        {{ $banner->secondary_button_text }}
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2">
                                            <polygon points="5,3 19,12 5,21"/>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                     Fallback to original hardcoded slide if no banners exist
                    <div class="slide active" data-slide="0">
                        <div class="slide-bg"
                             style="background-image: url('https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=1920&h=1080&fit=crop&crop=center')"></div>
                         Include the original hardcoded slide content as fallback
                        <div class="slide-particles">...</div>
                        <div class="floating-elements">...</div>
                        <div class="slide-number">01</div>
                        <div class="slide-content">
                            <div class="slide-subtitle">Premium Collection</div>
                            <h1 class="slide-title">Living<br>Beyond<br>Limits</h1>
                            <p class="slide-description">Experience unparalleled comfort and revolutionary design that
                                transforms your active lifestyle into an extraordinary journey.</p>
                            <div class="cta-group">
                                <a href="#" class="cta-button">Explore Collection</a>
                                <a href="#" class="cta-button cta-secondary">Watch Story</a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Navigation -->
            <div class="navigation">
                <button class="nav-arrow prev-arrow" aria-label="Previous slide">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                </button>
                <div class="pagination-dots">
                    @forelse($banners as $index => $banner)
                        <div class="dot {{ $loop->first ? 'active' : '' }}" data-slide="{{ $index }}"></div>
                    @empty
                        <div class="dot active" data-slide="0"></div>
                    @endforelse
                </div>
                <button class="nav-arrow next-arrow" aria-label="Next slide">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            <!-- Side Navigation -->
            <div class="side-navigation">
                @forelse($banners as $index => $banner)
                    <div class="side-nav-dot {{ $loop->first ? 'active' : '' }}" data-slide="{{ $index }}"></div>
                @empty
                    <div class="side-nav-dot active" data-slide="0"></div>
                @endforelse
            </div>

            <!-- Slide Counter -->
            <div class="slide-counter">
                <span class="current-slide">01</span> / <span
                    class="total-slides">{{ str_pad($banners->count() ?: 1, 2, '0', STR_PAD_LEFT) }}</span>
            </div>

            <!-- Progress Bar -->
            <div class="progress-bar">
                <div class="progress-fill"></div>
            </div>
        </div>

        <!-- /Slider -->
        <!-- Iconbox -->

        <div class="container-full mt-5">
            <div class="features">
                <div class="feature-list">
                    <div class="single-feature">
                        <div class="feature-icon">
                            <span>🎧</span>
                        </div>
                        <div class="feature-details">
                            <h6>24/7 SUPPORT</h6>
                            <span>Support always</span>
                        </div>
                    </div>

                    <div class="single-feature">
                        <div class="feature-icon">
                            <span>💳</span>
                        </div>
                        <div class="feature-details">
                            <h6>SECURE PAYMENT</h6>
                            <span>Visa, PayPal, MasterCard</span>
                        </div>
                    </div>

                    <div class="single-feature">
                        <div class="feature-icon">
                            <span>🛡️</span>
                        </div>
                        <div class="feature-details">
                            <h6>PRIVACY PROTECTION</h6>
                            <span>100% secure & confidential</span>
                        </div>
                    </div>

                    <div class="single-feature">
                        <div class="feature-icon">
                            <span>🚚</span>
                        </div>
                        <div class="feature-details">
                            <h6>DISCREET DELIVERY</h6>
                            <span>Private packaging, ontime</span>
                        </div>
                    </div>

                    <div class="single-feature">
                        <div class="feature-icon">
                            <span>📅</span>
                        </div>
                        <div class="feature-details">
                            <h6>30 DAYS RETURN</h6>
                            <span>Hassle-free returns</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- /Iconbox -->

        <!-- Season Collection -->

        <section class="flat-spacing-12 bg_grey-3">
            <div class="container">
                <div class="flat-title flex-row justify-content-between align-items-center px-0 wow fadeInUp"
                     data-wow-delay="0s">
                    <h3 class="title">Season Collection</h3>
                    <a href="{{ route('categories.index') }}" class="btn btn-line">View all categories<i
                            class="icon icon-arrow1-top-left"></i></a>
                </div>
                <div class="hover-sw-nav hover-sw-2">
                    <div dir="ltr" class="swiper tf-sw-collection" data-preview="6" data-tablet="3" data-mobile="2"
                         data-space-lg="50" data-space-md="30" data-space="15" data-loop="false" data-auto-play="false">
                        <div class="swiper-wrapper">
                            @forelse($categories as $category)
                                @php
                                    // The complex logic, including varied fallbacks, is now handled by the model's accessor.
                                    // We can just call it directly for clean, consistent code.
                                    $imageUrl = $category->thumbnail_url;
                                    // This onerror fallback is now just a final safety net for the browser.
                                    $browserFallbackImage = asset('images/products/shop_with_carl-1.jpg');
                                @endphp
                                <div class="swiper-slide" lazy="true">
                                    <div class="collection-item-circle hover-img">
                                        <a href="{{ route('categories.show', $category) }}" class="collection-image img-style">
                                            <img
                                                src="{{ $imageUrl }}"
                                                alt="{{ $category->name }}"
                                                style="width: 100%; height: 200px; object-fit: cover;"
                                                onload="this.style.opacity=1"
                                                onerror="this.src='{{ $browserFallbackImage }}'">
                                        </a>
                                        <div class="collection-content text-center">
                                            <a href="{{ route('categories.show', $category) }}" class="link title fw-5">{{ $category->name }}</a>
                                            <div class="count">{{ $category->products_count ?? 0 }} items</div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="swiper-slide">
                                    <div class="collection-item-circle hover-img">
                                        <div class="collection-content text-center">
                                            <div class="link title fw-5">No categories available</div>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    <div class="sw-dots style-2 sw-pagination-collection justify-content-center"></div>
                    <div class="nav-sw nav-next-slider nav-next-collection"><span class="icon icon-arrow-left"></span></div>
                    <div class="nav-sw nav-prev-slider nav-prev-collection"><span class="icon icon-arrow-right"></span></div>
                </div>
            </div>
        </section>

        <!-- /Season Collection -->

        <!-- New releases -->
        <section class="flat-spacing-12">
            <div class="container">
                <div class="flat-title mb_1 flex-row justify-content-between px-0">
                    <span class="title wow fadeInUp font-young-serif" data-wow-delay="0s">New releases</span>
                    <div class="box-sw-navigation">
                        <div class="nav-sw round nav-next-brand nav-next-slider" tabindex="0" role="button" aria-label="Previous slide">
                            <span class="icon icon-arrow1-left"></span>
                        </div>
                        <div class="nav-sw round nav-prev-brand nav-prev-slider" tabindex="0" role="button" aria-label="Next slide">
                            <span class="icon icon-arrow1-right"></span>
                        </div>
                    </div>
                </div>

                <div class="hover-sw-nav hover-sw-3">
                    <div class="swiper tf-sw-brand rounded-0 border-0 wrap-sw-over"
                         data-loop="false" data-play="false" data-preview="4" data-tablet="3" data-mobile="2"
                         data-space-lg="30" data-space-md="15">
                        <div class="swiper-wrapper" aria-live="polite">
                            @forelse($latestProducts as $product)
                                @php
                                    $galleryPaths = is_array($product->gallery) ? $product->gallery : [];

                                    // The main image is the FIRST image from the gallery, falling back to the featured image.
                                    $primaryPath = $galleryPaths[0] ?? $product->featured_image;
                                    $img = $product->getStorageImageUrl($primaryPath);

                                    // The hover image is the featured_image, falling back to the primary image if it doesn't exist.
                                    $hoverPath = $product->featured_image ?? $primaryPath;
                                    $hover = $product->getStorageImageUrl($hoverPath);

                                    $price = (float) ($product->price ?? 0);
                                    $sale = (float) ($product->sale_price ?? 0);
                                    $isOnSale = $sale > 0 && $sale < $price;
                                @endphp
                                <div class="swiper-slide" lazy="true" role="group" aria-label="{{ $loop->iteration }} / {{ $latestProducts->count() }}">
                                    <div class="card-product">
                                        <div class="card-product-wrapper">
                                            <a href="{{ route('products.show', $product->slug) }}" class="product-img">
                                                <img class="img-product" src="{{ $img }}" alt="{{ $product->name }}">
                                                <img class="img-hover" src="{{ $hover }}" alt="{{ $product->name }}">
                                            </a>
                                            <div class="list-product-btn">
                                                <a href="javascript:void(0)" class="box-icon bg_white quick-add" data-product-id="{{ $product->id }}">
                                                    <span class="icon icon-bag"></span>
                                                    <span class="tooltip">Quick Add</span>
                                                </a>
                                                <a href="javascript:void(0)" class="box-icon bg_white wishlist btn-icon-action" data-product-id="{{ $product->id }}">
                                                    <span class="icon icon-heart"></span>
                                                    <span class="tooltip">Add to Wishlist</span>
                                                    <span class="icon icon-delete"></span>
                                                </a>
                                                <a href="javascript:void(0)" class="box-icon bg_white quickview" data-product-id="{{ $product->id }}">
                                                    <span class="icon icon-view"></span>
                                                    <span class="tooltip">Quick View</span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card-product-info">
                                            <a href="{{ route('products.show', $product->slug) }}" class="title link">{{ $product->name }}</a>
                                            @if($isOnSale)
                                                <span class="price">
                                                    <span class="text-danger fw-6">UGX {{ number_format($sale, 0) }}</span>
                                                    <span class="text-muted text-decoration-line-through ms-1">UGX {{ number_format($price, 0) }}</span>
                                                </span>
                                            @else
                                                <span class="price">UGX {{ number_format($price, 0) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="swiper-slide w-100">
                                    <div class="text-center py-4 w-100">No products yet. Check back soon.</div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </section>



        @livewire('components.product-quick-add')
        @livewire('components.product-quick-view')
        @livewire('components.compare-modal')
        @livewire('components.shopping-cart')

        @push('styles')
            <style>
                .list-product-btn .wishlist .icon-delete { display: none; }
                .list-product-btn .wishlist.active .icon-delete { display: inline-block; }
                .list-product-btn .wishlist.active .icon-heart { display: none; }
            </style>
        @endpush

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const root = document;

                    function getProductId(el) {
                        const btn = el.closest('[data-product-id]') || el.closest('a[data-product-id]') || el;
                        return btn?.dataset?.productId || el.getAttribute('data-product-id');
                    }

                    function notify(msg, type = 'info') {
                        if (window.Livewire?.dispatch) {
                            window.Livewire.dispatch('notify', {message: msg, type});
                        } else {
                            console.log(type.toUpperCase() + ': ' + msg);
                        }
                    }

                    // Delegate clicks for New releases actions
                    root.addEventListener('click', function (e) {
                        const quickAdd = e.target.closest('.quick-add');
                        const quickView = e.target.closest('.quickview');
                        const wishlist = e.target.closest('.wishlist');
                        const compare = e.target.closest('.compare');

                        if (quickAdd) {
                            e.preventDefault();
                            const id = getProductId(quickAdd);
                            if (window.Livewire?.dispatch) {
                                window.Livewire.dispatch('product:quickAdd', { productId: id });                            } else {
                                notify('Livewire not loaded. Cannot quick add right now.', 'error');
                            }
                            return;
                        }

                        if (quickView) {
                            e.preventDefault();
                            const id = getProductId(quickView);
                            if (window.Livewire?.dispatch) {
                                window.Livewire.dispatch('product:quickView', {productId: Number(id)});
                            } else {
                                // Fallback: navigate to product page if Livewire missing
                                const link = quickView.closest('.card-product')?.querySelector('a.product-img');
                                if (link) window.location.href = link.getAttribute('href');
                            }
                            return;
                        }

                        if (compare) {
                            e.preventDefault();
                            const id = getProductId(compare);
                            if (window.Livewire?.dispatch) {
                                window.Livewire.dispatch('compare:toggle', {id: Number(id)});
                            }
                            return;
                        }

                        if (wishlist) {
                            e.preventDefault();
                            const id = getProductId(wishlist);

                            // Try Livewire event first if you have per-item listeners, otherwise use fetch to route
                            if (window.Livewire?.dispatch) {
                                // If a WishListToggle component exists on the page it will react to this
                                window.Livewire.dispatch('wishlist:toggle', {id: Number(id)});
                            }

                            // Also call the wishlist add route to ensure persistence
                            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                            if (!token) return;

                            fetch("{{ route('wishlist.add') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ product_id: Number(id) })
                            }).then(async (res) => {
                                if (res.status === 401) {
                                    // Not logged in -> redirect to login
                                    window.location.href = "{{ route('login') }}";
                                    return;
                                }
                                const data = await res.json().catch(() => ({}));
                                // Toggle icon state visually
                                wishlist.classList.toggle('active');
                                const tooltip = wishlist.querySelector('.tooltip');
                                if (tooltip) {
                                    tooltip.textContent = wishlist.classList.contains('active') ? 'Remove from Wishlist' : 'Add to Wishlist';
                                }
                                notify(data?.message || 'Wishlist updated', 'success');
                            }).catch(() => {
                                notify('Failed to update wishlist', 'error');
                            });
                            return;
                        }
                    });

                    function addLookbookToCart(lookbookId) {
                        const selectedItems = document.querySelectorAll('.lookbook-item-checkbox:checked');
                        const productIds = Array.from(selectedItems).map(item => item.value).filter(id => id);

                        if (productIds.length === 0) {
                            if (window.Livewire?.dispatch) {
                                window.Livewire.dispatch('notify', {message: 'Please select at least one item to add.', type: 'info'});
                            } else {
                                alert('Please select at least one item to add.');
                            }
                            return;
                        }

                        if (window.Livewire?.dispatch) {
                            let addedCount = 0;
                            productIds.forEach(productId => {
                                // This will open the quick add modal for each product if it has variants,
                                // or add it directly if it doesn't.
                                window.Livewire.dispatch('product:quickAdd', { productId: productId });
                                addedCount++;
                            });

                            // A generic notification, since QuickAdd might show its own.
                            if (addedCount > 0) {
                                setTimeout(() => {
                                    window.Livewire.dispatch('notify', {
                                        message: `Attempting to add ${addedCount} item(s).`,
                                        type: 'info'
                                    });
                                }, 500); // Delay to avoid conflicting with other notifications
                            }
                        } else {
                            console.error('Livewire not available. Cannot add lookbook to cart.');
                            alert('Could not add items to cart.');
                        }
                    }
                });
            </script>
        @endpush

{{--         Mobile Menu (categories dynamic)--}}
        <div class="offcanvas offcanvas-start canvas-mb" id="mobileMenu">
            <span class="icon-close icon-close-popup" data-bs-dismiss="offcanvas" aria-label="Close"></span>
            <div class="mb-canvas-content">
                <div class="mb-body">
                    <ul class="nav-ul-mb" id="wrapper-menu-navigation">
                        <li class="nav-mb-item">
                            <a href="{{ route('home') }}" class="mb-menu-link">
                                <span>Home</span>
                            </a>
                        </li>
                        <li class="nav-mb-item">
                            <a href="{{ route('shop.index') }}" class="mb-menu-link">
                                <span>Shop</span>
                            </a>
                        </li>

                        @foreach($categories as $cat)
                            <li class="nav-mb-item">
                                <a href="{{ route('categories.show', $cat->slug) }}" class="mb-menu-link">
                                    <span>{{ $cat->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mb-other-content">
                        <div class="d-flex group-icon">
                            <a href="{{ route('wishlist.index') }}" class="site-nav-icon">
                                <i class="icon icon-heart"></i>Wishlist
                            </a>
                            <a href="#" class="site-nav-icon" data-bs-toggle="offcanvas" data-bs-target="#canvasSearch">
                                <i class="icon icon-search"></i>Search
                            </a>
                        </div>
                        <div class="mb-notice">
                            <a href="{{ route('pages.contact') }}" class="text-need">Need help ?</a>
                        </div>
                    </div>
                </div>
                <div class="mb-bottom">
                    @guest
                        <a href="{{ route('login') }}" class="site-nav-icon"><i class="icon icon-account"></i>Login</a>
                    @else
                        <a href="{{ route('account.dashboard') }}" class="site-nav-icon"><i class="icon icon-account"></i>My Account</a>
                    @endguest
                </div>
            </div>
        </div>
        <!-- /New releases -->

        <section>
            <div class="container">
                <div class="tf-hero-image-liquid radius-10 o-hidden banner-countdown-v2">
                    <div class="banner-bg"
                         @if(!empty($promoBanner?->desktopImageUrl))
                             style="background-image: url('{{ $promoBanner->desktopImageUrl }}')"
                        @endif
                    ></div>

                    <div class="floating-elements">
                        <div class="floating-shape"></div>
                        <div class="floating-shape"></div>
                        <div class="floating-shape"></div>
                        <div class="sparkle"></div>
                        <div class="sparkle"></div>
                        <div class="sparkle"></div>
                        <div class="sparkle"></div>
                    </div>

                    <div class="box-content">
                        <h2 class="heading font-young-serif">{{ $promoBanner->heading ?? 'Bra Spotlight' }}</h2>
                        <p class="subtitle">{{ $promoBanner->subtitle ?? 'Discover comfort that embraces your confidence' }}</p>

                        @php
                            $features = $promoBanner->features ?? ['Premium Materials','Perfect Fit','All-Day Comfort','Elegant Design'];
                        @endphp
                        <div class="features-grid">
                            @foreach($features as $feat)
                                <div class="feature-item">
                                    <div class="feature-icon">✨</div>
                                    <span class="feature-text">{{ $feat }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="cta-section">
                            @if(!empty($promoBanner?->cta_text) && !empty($promoBanner?->cta_link))
                                <a href="{{ $promoBanner->cta_link }}" class="tf-btn btn-fill btn-md animate-hover-btn radius-60">
                                    {{ $promoBanner->cta_text }}
                                </a>
                            @endif
                            @if(!empty($promoBanner?->price_badge))
                                <div class="tf-btn price-badge">{{ $promoBanner->price_badge }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /Banner Countdown -->

        <!-- Trending Now -->
        <section class="flat-spacing-5">
            <div class="container">
                <div class="flat-title mb_1 flex-row justify-content-between px-0">
                    <span class="title wow fadeInUp font-young-serif" data-wow-delay="0s">Trending Now</span>
                    <div class="box-sw-navigation">
                        <div class="nav-sw round nav-next-brand nav-next-slider" tabindex="0" role="button" aria-label="Previous slide">
                            <span class="icon icon-arrow1-left"></span>
                        </div>
                        <div class="nav-sw round nav-prev-brand nav-prev-slider" tabindex="0" role="button" aria-label="Next slide">
                            <span class="icon icon-arrow1-right"></span>
                        </div>
                    </div>
                </div>

                <div class="flat-animate-tab">
                    <ul class="widget-tab-3 style-3 d-flex justify-content-center" role="tablist">
                        <li class="nav-tab-item" role="presentation">
                            <a href="#featured-tab" class="active" data-bs-toggle="tab" aria-selected="true" role="tab">Featured</a>
                        </li>
                        <li class="nav-tab-item" role="presentation">
                            <a href="#onsale-tab" data-bs-toggle="tab" aria-selected="false" role="tab">On Sale</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane hover-sw-nav active show" id="featured-tab" role="tabpanel">
                            <div class="hover-sw-nav hover-sw-3">
                                <div class="swiper tf-sw-brand rounded-0 border-0 wrap-sw-over"
                                     data-loop="false" data-play="false" data-preview="4" data-tablet="3" data-mobile="2"
                                     data-space-lg="30" data-space-md="15">
                                    <div class="swiper-wrapper" aria-live="polite">
                                        @forelse($featuredProducts as $product)
                                            @php
                                                $galleryPaths = is_array($product->gallery) ? $product->gallery : [];

                                                // The main image is the FIRST image from the gallery, falling back to the featured image.
                                                $primaryPath = $galleryPaths[0] ?? $product->featured_image;
                                                $img = $product->getStorageImageUrl($primaryPath);

                                                // The hover image is the featured_image, falling back to the primary image if it doesn't exist.
                                                $hoverPath = $product->featured_image ?? $primaryPath;
                                                $hover = $product->getStorageImageUrl($hoverPath);

                                                $isOnSale = $product->sale_price && $product->sale_price < $product->price;
                                            @endphp
                                            <div class="swiper-slide" lazy="true" role="group" aria-label="{{ $loop->iteration }} / {{ $featuredProducts->count() }}">
                                                <div class="card-product">
                                                    <div class="card-product-wrapper">
                                                        <a href="{{ route('products.show', $product->slug) }}" class="product-img">
                                                            <img class="img-product" src="{{ $img }}" alt="{{ $product->name }}">
                                                            <img class="img-hover" src="{{ $hover }}" alt="{{ $product->name }}">
                                                        </a>
                                                        <div class="list-product-btn">
                                                            <a href="javascript:void(0)" class="box-icon bg_white quick-add" data-product-id="{{ $product->id }}">
                                                                <span class="icon icon-bag"></span>
                                                                <span class="tooltip">Quick Add</span>
                                                            </a>
                                                            <a href="javascript:void(0)" class="box-icon bg_white wishlist btn-icon-action" data-product-id="{{ $product->id }}">
                                                                <span class="icon icon-heart"></span>
                                                                <span class="tooltip">Add to Wishlist</span>
                                                                <span class="icon icon-delete"></span>
                                                            </a>
                                                            <a href="javascript:void(0)" class="box-icon bg_white quickview" data-product-id="{{ $product->id }}">
                                                                <span class="icon icon-view"></span>
                                                                <span class="tooltip">Quick View</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="card-product-info">
                                                        <a href="{{ route('products.show', $product->slug) }}" class="title link">{{ $product->name }}</a>
                                                        @if($isOnSale)
                                                            <span class="price">
                                                                <span class="text-danger fw-6">{{ money_format_ugx($product->sale_price) }}</span>
                                                                <span class="text-muted text-decoration-line-through ms-1">{{ money_format_ugx($product->price) }}</span>
                                                            </span>
                                                        @else
                                                            <span class="price">{{ money_format_ugx($product->price) }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="swiper-slide w-100">
                                                <div class="text-center py-4 w-100">No featured products yet.</div>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane hover-sw-nav" id="onsale-tab" role="tabpanel">
                            <div class="hover-sw-nav hover-sw-3">
                                <div class="swiper tf-sw-brand rounded-0 border-0 wrap-sw-over"
                                     data-loop="false" data-play="false" data-preview="4" data-tablet="3" data-mobile="2"
                                     data-space-lg="30" data-space-md="15">
                                    <div class="swiper-wrapper" aria-live="polite">
                                        @forelse($onSaleProducts as $product)
                                            @php
                                                $galleryPaths = is_array($product->gallery) ? $product->gallery : [];

                                                // The main image is the FIRST image from the gallery, falling back to the featured image.
                                                $primaryPath = $galleryPaths[0] ?? $product->featured_image;
                                                $img = $product->getStorageImageUrl($primaryPath);

                                                // The hover image is the featured_image, falling back to the primary image if it doesn't exist.
                                                $hoverPath = $product->featured_image ?? $primaryPath;
                                                $hover = $product->getStorageImageUrl($hoverPath);
                                            @endphp
                                            <div class="swiper-slide" lazy="true" role="group" aria-label="{{ $loop->iteration }} / {{ $onSaleProducts->count() }}">
                                                <div class="card-product">
                                                    <div class="card-product-wrapper">
                                                        <a href="{{ route('products.show', $product->slug) }}" class="product-img">
                                                            <img class="img-product" src="{{ $img }}" alt="{{ $product->name }}">
                                                            <img class="img-hover" src="{{ $hover }}" alt="{{ $product->name }}">
                                                        </a>
                                                        <div class="list-product-btn">
                                                            <a href="javascript:void(0)" class="box-icon bg_white quick-add" data-product-id="{{ $product->id }}">
                                                                <span class="icon icon-bag"></span>
                                                                <span class="tooltip">Quick Add</span>
                                                            </a>
                                                            <a href="javascript:void(0)" class="box-icon bg_white wishlist btn-icon-action" data-product-id="{{ $product->id }}">
                                                                <span class="icon icon-heart"></span>
                                                                <span class="tooltip">Add to Wishlist</span>
                                                                <span class="icon icon-delete"></span>
                                                            </a>
                                                            <a href="javascript:void(0)" class="box-icon bg_white quickview" data-product-id="{{ $product->id }}">
                                                                <span class="icon icon-view"></span>
                                                                <span class="tooltip">Quick View</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="card-product-info">
                                                        <a href="{{ route('products.show', $product->slug) }}" class="title link">{{ $product->name }}</a>
                                                        <span class="price">
                                                            <span class="text-danger fw-6">{{ money_format_ugx($product->sale_price) }}</span>
                                                            <span class="text-muted text-decoration-line-through ms-1">{{ money_format_ugx($product->price) }}</span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="swiper-slide w-100">
                                                <div class="text-center py-4 w-100">No products on sale right now.</div>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- /Trending Now -->

        <!-- Lookbook -->
        <section class="section-lookbook-activewear">
            <div class="container-full">
                <div class="flat-activewear-lookbook radius-20 o-hidden bg_grey-11 flat-spacing-12">
                    <div class="container">
                        <div class="flat-lookbook-v3 d-flex">
                            <div class="col-left w-50">
                                <div class="slider-wrap-lookbook">
                                    <div class="inner-sw-lookbook">
                                        <div class="flat-title-lookbook">
                                            <p class="label text_black">{{ $lookbook?->label ?? 'SHOP THIS LOOK' }}</p>
                                            <h3 class="heading font-young-serif">{{ $lookbook?->title ?? 'Bundle & Save' }}</h3>
                                        </div>
                                        <form>
                                            <div class="swiper tf-lookbook" data-preview="3" data-tablet="1" data-mobile="1" data-space-lg="0" data-space-md="0">
                                                <div class="swiper-wrapper" aria-live="polite">
                                                    @forelse(($lookbook?->items) ?? [] as $item)
                                                        @php
                                                            $p = $item->product;
                                                            // Get product image, ensuring consistency with other sections
                                                            $imagePath = null;
                                                            if ($p) {
                                                                $galleryPaths = is_array($p->gallery) ? $p->gallery : [];
                                                                $imagePath = $galleryPaths[0] ?? $p->featured_image;
                                                            }
                                                            $img = $p ? $p->getStorageImageUrl($imagePath) : asset('images/placeholder-product.jpg');
                                                            $price = (float) ($p->price ?? 0);
                                                            $sale = (float) ($p->sale_price ?? 0);
                                                            $onSale = $sale > 0 && $sale < $price;
                                                        @endphp
                                                        <div class="swiper-slide" lazy="true">
                                                            <div class="tf-bundle-product-item type-lg">
                                                                <div class="tf-product-bundle-image">
                                                                    <a href="{{ $p ? route('products.show', $p->slug) : 'javascript:void(0)' }}" class="radius-5 o-hidden">
                                                                        <img src="{{ $img }}" alt="{{ $p?->name }}">
                                                                    </a>
                                                                </div>
                                                                <div class="tf-product-bundle-infos">
                                                                    <div class="d-flex align-items-center">
                                                                        <input class="form-check-input lookbook-item-checkbox me-2" type="checkbox" value="{{ $p->id ?? '' }}" id="lookbook-item-{{ $p->id ?? $loop->index }}" checked style="width: 1.5em; height: 1.5em; margin-top: 0;">
                                                                        <div>
                                                                            <a href="{{ $p ? route('products.show', $p->slug) : 'javascript:void(0)' }}"
                                                                               class="tf-product-bundle-title fs-16">{{ $p?->name ?? 'Product' }}</a>
                                                                            <div class="tf-product-bundle-price">
                                                                                <div class="price fs-16">
                                                                                    @if($onSale)
                                                                                        <span class="text-danger fw-6">UGX {{ number_format($sale, 0) }}</span>
                                                                                        <span class="text-muted text-decoration-line-through ms-1">UGX {{ number_format($price, 0) }}</span>
                                                                                    @elseif($p)
                                                                                        UGX {{ number_format($price, 0) }}
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <div class="swiper-slide">
                                                            <div class="p-4">Lookbook coming soon.</div>
                                                        </div>
                                                    @endforelse
                                                </div>
                                            </div>

                                            @if(($lookbook?->items?->count() ?? 0) > 0)
                                                <button type="button"
                                                        class="tf-btn justify-content-center style-2 btn-fill radius-3 animate-hover-btn"
                                                        onclick="addLookbookToCart('{{ $lookbook->id }}')">
                                                    Add selected to cart
                                                </button>
                                            @endif
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-right w-50">
                                <div class="wrap-lookbook lookbook-sw">
                                    <div class="image">
                                        @if(!empty($lookbook))
                                            <img src="{{ $lookbook->imageUrl }}" alt="{{ $lookbook->title }}">
                                        @endif
                                    </div>
                                    <div class="navigation-sw-dot type-black item-1"><span></span></div>
                                    <div class="navigation-sw-dot type-black item-2"><span></span></div>
                                    <div class="navigation-sw-dot type-black item-3"><span></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>

        <!-- /Lookbook -->
        <!-- Banner Collection -->
        <section class="flat-spacing-27 bg-white">
            <div class="container">
                <div class="tf-grid-layout md-col-2 tf-img-with-text style-5">
                    <div class="tf-image-wrap wow fadeInUp" data-wow-delay="0s">
                        <img class="lazyload" data-src="images/products/shop_with_carl-18.jpg"
                             src="images/products/shop_with_carl-18.jpg" alt="collection-img">
                    </div>
                    <div class="tf-content-wrap wow fadeInUp" data-wow-delay="0s">
                        <div class="sub-heading fw-7">LEAVE YOUR MARK</div>
                        <div class="heading font-young-serif">Shop With Carl</div>
                        <p class="description text_black-2 fs-14">Since our inception, Shop With Carl has had one
                            intention;
                            to help build resilient human beings through performance, innovation, sustainability and
                            functionality.</p>
                        <a href="shop-collection-list.html"
                           class="tf-btn style-2 btn-fill btn-icon rounded-full animate-hover-btn">Learn more <i
                                class="icon icon-arrow1-top-left"></i></a>
                    </div>
                </div>
            </div>
        </section>
        <!-- /Banner Collection -->
        <!-- iconbox -->
        <section class="flat-spacing-8 bg-white">
            <div class="container">
                <div class="flat-iconbox-v3">
                    <div class="wrap-carousel wrap-mobile">
                        <div dir="ltr" class="swiper tf-sw-mobile" data-preview="1" data-space="15">
                            <div class="swiper-wrapper wrap-iconbox">
                                <div class="swiper-slide">
                                    <div class="tf-icon-box small text-center">
                                        <div class="icon w-50 no-border">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="51" height="51"
                                                 viewBox="0 0 51 51" fill="none">
                                                <g clip-path="url(#clip0_8522_2)">
                                                    <path
                                                        d="M48.0287 20.3573L38.7284 13.3494C38.4375 13.1305 38.0841 13.0127 37.7203 13.0127H33.8327C33.1368 13.0127 32.5057 13.4496 32.2585 14.1146C31.2868 16.7251 28.7598 18.5349 25.8949 18.5349C23.0488 18.5349 20.5095 16.7428 19.5313 14.1146C19.284 13.4493 18.6527 13.0127 17.9571 13.0127H14.0695C13.7057 13.0127 13.3523 13.1305 13.0614 13.3494L3.76107 20.3573C3.06258 20.8838 2.88639 21.8585 3.35657 22.5956L6.26938 27.1712C6.7479 27.9229 7.72996 28.1679 8.50664 27.7311L13.6505 24.8297V48.0149C13.6505 48.9407 14.4011 49.6908 15.3268 49.6908H36.463C37.3887 49.6908 38.1394 48.9412 38.1394 48.0155V24.8297L43.2832 27.7311C44.0599 28.1679 45.0419 27.9229 45.5204 27.1712L48.4332 22.5956C48.9034 21.8585 48.7272 20.8838 48.0287 20.3573Z"
                                                        fill="#CBE3FA"></path>
                                                    <path
                                                        d="M32.7642 28.2889C33.2396 30.8702 32.0563 33.6704 29.7033 35.6783C29.0194 36.2621 27.9498 36.0547 27.5505 35.2488C26.3943 32.9167 22.095 31.6823 22.9676 27.1588C24.1284 21.1451 31.4787 21.313 32.7642 28.2889Z"
                                                        fill="#BEFA91"></path>
                                                    <path
                                                        d="M28.6262 22.9918C29.6748 23.275 29.6422 24.7838 28.5875 25.0436C26.6402 25.5231 25.4181 27.2534 25.0407 29.2988C24.8299 30.4414 23.2009 30.479 22.9567 29.343C22.5401 27.4057 23.3598 24.5454 25.4504 23.3744C26.4203 22.8309 27.5462 22.7001 28.6262 22.9918Z"
                                                        fill="#DAFFBF"></path>
                                                    <path
                                                        d="M19.5351 14.1251C19.7108 14.5956 19.3732 15.0978 18.871 15.0978H17.1971C16.8333 15.0978 16.4799 15.2157 16.189 15.4346L6.88864 22.4424C6.19015 22.9689 6.01396 23.9437 6.48414 24.6807L7.56962 26.3856C8.09244 27.2068 6.863 28.1034 6.26938 27.1712L3.35657 22.5956C2.88639 21.8585 3.06258 20.8838 3.76107 20.3573L13.0614 13.3494C13.3523 13.1305 13.7057 13.0127 14.0696 13.0127H17.9571C18.6362 13.0129 19.2756 13.43 19.5351 14.1251ZM16.778 29.5923V48.2398C16.778 49.0413 16.1283 49.691 15.3268 49.691C14.401 49.691 13.6505 48.941 13.6505 48.0151V29.5923C13.6504 29.3151 13.7241 29.0429 13.8639 28.8036C14.0037 28.5643 14.2046 28.3664 14.446 28.2302C15.4884 27.6422 16.778 28.3955 16.778 29.5923ZM38.7284 13.3496C39.4743 13.9112 39.0772 15.0988 38.1434 15.0985H36.9603C36.2644 15.0981 35.6332 15.5349 35.3861 16.2C34.0621 19.7568 30.2425 21.3354 26.972 20.3088C26.0113 20.0072 26.0962 18.6071 27.0871 18.4276C29.3692 18.0143 31.3929 16.4407 32.2585 14.1149C32.5058 13.4495 33.1371 13.0129 33.8327 13.0129H37.7203C38.0841 13.0129 38.4375 13.1306 38.7284 13.3496Z"
                                                        fill="#EEF7FF"></path>
                                                    <path
                                                        d="M48.5321 19.6911L39.2323 12.6837C38.7965 12.3567 38.2667 12.1794 37.722 12.1783H33.8348C32.7922 12.1783 31.8455 12.8393 31.4792 13.8233C30.689 15.9457 28.7428 17.4301 26.5208 17.6667C26.5418 17.2751 26.2835 16.911 25.8887 16.8132C25.674 16.7601 25.4469 16.7944 25.2575 16.9086C25.0681 17.0229 24.9319 17.2077 24.8787 17.4224C24.4121 19.3066 24.3214 21.1759 24.5466 22.9637C23.3274 23.8425 22.4828 25.2533 22.1453 27.0011C21.1751 32.0278 25.7817 33.5661 26.7994 35.6194C27.4391 36.9099 29.141 37.2514 30.2411 36.3129C32.8242 34.109 34.104 30.9767 33.581 28.1383C33.2119 26.1351 32.347 24.4952 31.0799 23.3956C30.9972 23.3238 30.9012 23.269 30.7973 23.2343C30.6934 23.1997 30.5837 23.1858 30.4744 23.1935C30.3652 23.2013 30.2585 23.2305 30.1606 23.2794C30.0626 23.3284 29.9752 23.3962 29.9035 23.4789C29.7585 23.646 29.6858 23.8638 29.7015 24.0844C29.7171 24.305 29.8197 24.5104 29.9868 24.6554C30.9653 25.5045 31.6408 26.8132 31.9407 28.4405C32.3559 30.6952 31.29 33.2255 29.1585 35.0441C28.8861 35.2764 28.4516 35.1969 28.294 34.8787C27.009 32.2861 23.0103 31.3215 23.7832 27.3174C23.9739 26.3293 24.3646 25.4944 24.9134 24.8723C25.4995 27.1323 26.5176 28.9407 27.4047 30.1972C27.4817 30.3064 27.5839 30.3956 27.7026 30.4571C27.8213 30.5185 27.9531 30.5505 28.0868 30.5504C28.7503 30.5504 29.1619 29.7941 28.7673 29.2353C26.7562 26.3861 25.7446 22.9563 26.2101 19.3622C29.2535 19.2356 31.9737 17.2761 33.0424 14.4054C33.1669 14.0711 33.4853 13.8465 33.8348 13.8465H37.722C37.9035 13.8465 38.0835 13.9068 38.2285 14.0161L47.5283 21.0236C47.8768 21.2862 47.9661 21.7801 47.7318 22.1481L44.8189 26.7234C44.7031 26.9052 44.522 27.0357 44.313 27.0879C44.1039 27.1402 43.8827 27.1103 43.695 27.0044L38.5504 24.1032C37.9939 23.7896 37.3066 24.1933 37.3066 24.8297V48.0156C37.3066 48.4798 36.9289 48.8575 36.4647 48.8575H15.3286C14.8644 48.8575 14.4867 48.4798 14.4867 48.0156V24.8295C14.4867 24.1919 13.7982 23.79 13.243 24.103L8.09832 27.0043C7.91059 27.1101 7.68944 27.14 7.48038 27.0877C7.27132 27.0354 7.09026 26.9049 6.97448 26.7232L4.06156 22.1478C3.8272 21.7799 3.91655 21.2859 4.26507 21.0234L13.5647 14.0159C13.7109 13.9062 13.8886 13.8467 14.0714 13.8463H17.9585C18.3081 13.8463 18.6264 14.0709 18.7509 14.4053C19.1001 15.3427 19.6247 16.1916 20.3101 16.9281C20.4616 17.0869 20.6696 17.1797 20.8889 17.1862C21.1083 17.1928 21.3214 17.1126 21.4821 16.9631C21.6427 16.8135 21.738 16.6067 21.7472 16.3875C21.7564 16.1682 21.6788 15.9541 21.5313 15.7917C20.6568 14.8522 20.4049 14.0079 20.2449 13.6571L23.1458 12.2201C24.8684 11.3667 26.925 11.3667 28.6475 12.2201L29.1079 12.4482C29.3057 12.5428 29.5328 12.5558 29.74 12.4843C29.9473 12.4127 30.1181 12.2625 30.2154 12.066C30.3127 11.8695 30.3288 11.6426 30.2601 11.4344C30.1914 11.2262 30.0434 11.0534 29.8483 10.9534C28.9469 10.5068 28.112 10.1032 26.7307 9.95769V9.2935C26.7307 8.77954 27.0389 8.3202 27.535 8.0947C29.0445 7.40841 29.9534 5.89362 29.8505 4.23559C29.7271 2.24833 28.1353 0.656497 26.1479 0.533062C23.8076 0.388047 21.9351 2.25563 21.9351 4.48684C21.9351 4.70803 22.023 4.92017 22.1794 5.07658C22.3358 5.23299 22.5479 5.32086 22.7691 5.32086C22.9903 5.32086 23.2025 5.23299 23.3589 5.07658C23.5153 4.92017 23.6032 4.70803 23.6032 4.48684C23.6032 3.18483 24.6927 2.11614 26.0445 2.19787C27.1751 2.26814 28.1154 3.20849 28.1857 4.3389C28.2453 5.30053 27.7188 6.17865 26.8447 6.57616C25.7621 7.06823 25.0627 8.13484 25.0627 9.2934V9.95759C24.1464 10.0541 23.2446 10.3095 22.4055 10.7253L19.0028 12.4109C18.6758 12.2583 18.3195 12.1789 17.9586 12.1782H14.0715C13.5267 12.1793 12.9968 12.3566 12.5611 12.6836L3.26132 19.6911C2.22234 20.4741 1.95598 21.9467 2.65468 23.0439L5.5676 27.6191C6.28423 28.7445 7.75585 29.1123 8.91785 28.4573L12.8188 26.2574V48.0154C12.8188 49.3993 13.9448 50.5253 15.3288 50.5253H36.4647C37.8486 50.5253 38.9747 49.3993 38.9747 48.0154V26.2574L42.8756 28.4572C44.0376 29.1124 45.509 28.7444 46.2258 27.6191L49.1388 23.044C49.8375 21.9466 49.5711 20.4739 48.5321 19.6911Z"
                                                        fill="black"></path>
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_8522_2">
                                                        <rect width="50" height="50" fill="white"
                                                              transform="translate(0.898438 0.525391)"></rect>
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        </div>
                                        <div class="content">
                                            <p>Made with recycled materials, like <br> old plastic water bottles</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="tf-icon-box small text-center">
                                        <div class="icon w-50 no-border">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="51"
                                                 viewBox="0 0 50 51" fill="none">
                                                <path
                                                    d="M43.3405 32.3108L48.0512 27.6001C48.2904 27.3611 48.4802 27.0772 48.6097 26.7648C48.7392 26.4524 48.8058 26.1175 48.8058 25.7793C48.8058 25.4411 48.7392 25.1062 48.6097 24.7938C48.4802 24.4813 48.2904 24.1975 48.0512 23.9585L43.3405 19.2478C42.8577 18.7648 42.5862 18.1099 42.5858 17.427V10.7698C42.5858 10.0867 42.3145 9.43164 41.8315 8.94865C41.3485 8.46565 40.6934 8.19431 40.0103 8.19431H33.3501C32.6672 8.19392 32.0123 7.9225 31.5293 7.43966L26.8186 2.72887C26.5795 2.48964 26.2957 2.29986 25.9833 2.17037C25.6708 2.04089 25.336 1.97424 24.9978 1.97424C24.6596 1.97424 24.3247 2.04089 24.0123 2.17037C23.6999 2.29986 23.416 2.48964 23.177 2.72887L18.4723 7.44259C17.9893 7.92542 17.3344 8.19684 16.6515 8.19723H9.99122C9.30817 8.19723 8.65309 8.46858 8.17009 8.95157C7.6871 9.43456 7.41576 10.0896 7.41576 10.7727V17.433C7.41535 18.1159 7.14391 18.7708 6.66105 19.2538L1.95336 23.9615C1.71412 24.2005 1.52433 24.4844 1.39484 24.7968C1.26535 25.1092 1.1987 25.4441 1.1987 25.7823C1.1987 26.1205 1.26535 26.4554 1.39484 26.7678C1.52433 27.0802 1.71412 27.3641 1.95336 27.6031L6.66403 32.3138C7.14689 32.7968 7.41833 33.4517 7.41874 34.1346V40.7949C7.41874 41.4779 7.69008 42.133 8.17308 42.616C8.65607 43.099 9.31115 43.3703 9.99421 43.3703H16.6545C17.3375 43.3707 17.9923 43.6422 18.4753 44.125L23.186 48.8357C23.425 49.0749 23.7089 49.2647 24.0213 49.3942C24.3337 49.5237 24.6686 49.5903 25.0068 49.5903C25.345 49.5903 25.6798 49.5237 25.9923 49.3942C26.3047 49.2647 26.5885 49.0749 26.8276 48.8357L31.5383 44.125C32.0213 43.6422 32.6762 43.3707 33.3591 43.3703H40.0193C40.7024 43.3703 41.3575 43.099 41.8405 42.616C42.3235 42.133 42.5948 41.4779 42.5948 40.7949V34.1346C42.591 33.7962 42.655 33.4605 42.7831 33.1472C42.9112 32.834 43.1007 32.5496 43.3405 32.3108Z"
                                                    fill="#7F9AFF"></path>
                                                <path
                                                    d="M43.4274 41.1153V34.3891C43.4274 33.7003 43.7058 33.0386 44.2 32.5504L49.0245 27.7947C49.2691 27.5551 49.4634 27.269 49.5961 26.9532C49.7288 26.6375 49.7971 26.2985 49.7971 25.956C49.7971 25.6135 49.7288 25.2745 49.5961 24.9587C49.4634 24.643 49.2691 24.3569 49.0245 24.1172L44.2 19.3616C43.9559 19.1215 43.7619 18.8353 43.6293 18.5197C43.4966 18.204 43.428 17.8652 43.4273 17.5228V10.7967C43.4273 9.3622 42.2474 8.19723 40.792 8.19723H38.7885L38.6747 8.55958V17.3461C38.6747 19.3287 39.4982 21.2273 40.9567 22.5959L44.4695 25.8931L40.5704 30.1576C39.3515 31.4932 38.6747 33.2272 38.6747 35.024V40.3007H32.3499C30.8435 40.3007 29.412 40.9356 28.4118 42.0437L25.3901 45.3978L21.1675 41.1962C20.0865 40.1247 18.6257 39.524 17.1037 39.5251H7.97867L7.70616 42.3072C7.93056 42.7337 8.26743 43.0906 8.68026 43.3392C9.09308 43.5879 9.5661 43.7188 10.048 43.7177H16.87C17.5678 43.7177 18.2386 43.9903 18.7327 44.4784L23.5573 49.234C24.5874 50.2492 26.2555 50.2492 27.2857 49.234L32.1102 44.4784C32.6067 43.9894 33.2761 43.716 33.9729 43.7177H40.7979C42.2504 43.7177 43.4274 42.5528 43.4274 41.1153Z"
                                                    fill="#5078FF"></path>
                                                <path
                                                    d="M25.0008 50.7793C24.0335 50.7793 23.0692 50.4109 22.3324 49.6772L17.6218 44.9665C17.4944 44.838 17.3428 44.7361 17.1757 44.6667C17.0086 44.5973 16.8294 44.5618 16.6485 44.5622H9.99122C7.90989 44.5622 6.21786 42.8702 6.21786 40.7889V34.1286C6.21786 33.7663 6.07105 33.4128 5.81358 33.1553L1.10284 28.4446C-0.367615 26.9742 -0.367615 24.5814 1.10284 23.111L5.81358 18.4003C6.06813 18.1427 6.21786 17.7894 6.21786 17.427V10.7697C6.21786 8.68838 7.90989 6.99636 9.99122 6.99636H16.6515C17.0139 6.99636 17.3672 6.84961 17.6247 6.59207L22.3355 1.88438C22.685 1.53279 23.1008 1.25407 23.5589 1.06438C24.0169 0.87469 24.5081 0.777795 25.0038 0.779314C26.01 0.779314 26.9593 1.17161 27.6721 1.88438L32.3828 6.59505C32.6403 6.84961 32.9937 6.99934 33.3561 6.99934H40.0164C42.0977 6.99934 43.7897 8.69136 43.7897 10.7727V17.433C43.7897 17.7953 43.9365 18.1487 44.194 18.4063L48.9047 23.114C50.3751 24.5844 50.3751 26.9772 48.9047 28.4476L44.194 33.1583C43.9361 33.4167 43.7908 33.7665 43.7897 34.1316V40.7919C43.7897 42.8732 42.0977 44.5652 40.0164 44.5652H33.3561C32.9877 44.5652 32.6433 44.709 32.3828 44.9695L27.6721 49.6803C26.9619 50.3853 26.0015 50.7805 25.0008 50.7793ZM9.99122 9.39513C9.23353 9.39513 8.61365 10.012 8.61365 10.7727V17.433C8.61365 18.4392 8.22129 19.3886 7.50858 20.1013L2.80083 24.806C2.54335 25.0645 2.39878 25.4144 2.39878 25.7793C2.39878 26.1441 2.54335 26.4941 2.80083 26.7526L7.51156 31.4633C7.86315 31.8128 8.14187 32.2287 8.33156 32.6867C8.52125 33.1447 8.61815 33.6359 8.61663 34.1316V40.7919C8.61663 41.5496 9.23353 42.1694 9.9942 42.1694H16.6545C17.6607 42.1694 18.61 42.5617 19.3228 43.2745L24.0335 47.9852C24.2919 48.2427 24.6419 48.3872 25.0067 48.3872C25.3716 48.3872 25.7216 48.2427 25.98 47.9852L30.6908 43.2745C31.0403 42.9229 31.4561 42.6442 31.9142 42.4545C32.3722 42.2648 32.8633 42.1679 33.3591 42.1694H40.0193C40.777 42.1694 41.3969 41.5525 41.3969 40.7919V34.1316C41.3969 33.1254 41.7892 32.1761 42.502 31.4633L47.2127 26.7526C47.4701 26.4941 47.6147 26.1441 47.6147 25.7793C47.6147 25.4144 47.4701 25.0645 47.2127 24.806L42.502 20.0953C42.1504 19.7458 41.8717 19.3299 41.682 18.8719C41.4923 18.4139 41.3954 17.9227 41.3969 17.427V10.7698C41.3969 10.0121 40.78 9.3922 40.0193 9.3922H33.3591C32.3528 9.3922 31.4035 8.9999 30.6908 8.28713L25.98 3.57646C25.7195 3.31589 25.3751 3.17217 25.0067 3.17217C24.6384 3.17217 24.294 3.31589 24.0335 3.57646L19.3198 8.29012C18.9703 8.64171 18.5544 8.92042 18.0964 9.11012C17.6384 9.29981 17.1472 9.3967 16.6515 9.39518L9.99122 9.39513Z"
                                                    fill="#211D38"></path>
                                                <path
                                                    d="M9.20054 32.7331C9.00893 32.7331 8.81725 32.6612 8.6705 32.5144L3.04643 26.8904C2.89999 26.7448 2.78383 26.5717 2.70467 26.381C2.62551 26.1903 2.58492 25.9858 2.58523 25.7793C2.58523 25.36 2.74994 24.9647 3.04643 24.6682L7.79606 19.9186C8.37407 19.3406 8.6915 18.571 8.6915 17.7534V16.849C8.6915 16.6505 8.77038 16.46 8.91078 16.3196C9.05119 16.1792 9.24162 16.1003 9.44018 16.1003C9.63874 16.1003 9.82917 16.1792 9.96958 16.3196C10.11 16.46 10.1889 16.6505 10.1889 16.849V17.7564C10.1889 18.9723 9.71567 20.1192 8.85323 20.9787L4.10354 25.7284C4.08856 25.7434 4.0826 25.7613 4.0826 25.7793C4.0826 25.7972 4.08856 25.8182 4.10354 25.8302L9.72766 31.4543C9.83261 31.5588 9.90421 31.692 9.93339 31.8372C9.96257 31.9824 9.94803 32.1329 9.8916 32.2699C9.83517 32.4068 9.73939 32.5239 9.61639 32.6063C9.49339 32.6888 9.34863 32.7329 9.20054 32.7331ZM9.44018 14.2107C9.34186 14.2107 9.24451 14.1913 9.15367 14.1537C9.06284 14.1161 8.9803 14.0609 8.91078 13.9914C8.84126 13.9219 8.78611 13.8393 8.74849 13.7485C8.71086 13.6577 8.6915 13.5603 8.6915 13.462V11.0392C8.6915 10.1738 9.39526 9.46701 10.2637 9.46701H16.9779C17.7955 9.46701 18.5651 9.14958 19.1431 8.57157L20.0505 7.66421C20.344 7.3707 20.8172 7.3707 21.1106 7.66421C21.4041 7.95771 21.4041 8.43085 21.1106 8.72429L20.2032 9.63172C19.3437 10.4942 18.1968 10.9674 16.9809 10.9674H10.2637C10.2218 10.9674 10.1889 11.0003 10.1889 11.0422V13.465C10.1889 13.8752 9.85342 14.2107 9.44018 14.2107ZM22.7847 6.73882C22.6363 6.73923 22.4912 6.69555 22.3678 6.61332C22.2443 6.53109 22.1481 6.41403 22.0913 6.277C22.0345 6.13996 22.0197 5.98914 22.0488 5.84368C22.0778 5.69822 22.1495 5.56469 22.2546 5.46004L23.8897 3.82493C24.1832 3.53148 24.6564 3.53148 24.9499 3.82493C25.2434 4.11838 25.2433 4.59157 24.9499 4.88508L23.3147 6.52018C23.168 6.66694 22.9764 6.73882 22.7847 6.73882Z"
                                                    fill="white"></path>
                                                <path
                                                    d="M23.3596 32.8858H23.3537C22.6409 32.8828 21.9552 32.5863 21.464 32.0712L16.2022 26.537C15.9834 26.3064 15.865 25.9985 15.8728 25.6808C15.8807 25.363 16.0142 25.0613 16.2442 24.8419C16.4747 24.6231 16.7826 24.5046 17.1004 24.5125C17.4181 24.5204 17.7198 24.6539 17.9392 24.8839L23.201 30.4181C23.2212 30.44 23.2458 30.4574 23.2731 30.4692C23.3004 30.4811 23.3299 30.4871 23.3596 30.487C23.3926 30.481 23.4645 30.475 23.5214 30.4181L33.3231 20.2241C33.5438 19.9953 33.8461 19.8633 34.1638 19.8572C34.4816 19.851 34.7888 19.9711 35.0181 20.1911C35.2469 20.4117 35.3789 20.7141 35.3851 21.0318C35.3912 21.3496 35.2711 21.6569 35.0511 21.8862L25.2494 32.0801C25.0048 32.3346 24.7113 32.5371 24.3866 32.6756C24.0619 32.814 23.7126 32.8855 23.3596 32.8858Z"
                                                    fill="#211D38"></path>
                                            </svg>
                                        </div>
                                        <div class="content">
                                            <p>Ethically made in certified <br> factories</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="tf-icon-box small text-center">
                                        <div class="icon w-50 no-border">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="51" height="51"
                                                 viewBox="0 0 51 51" fill="none">
                                                <g clip-path="url(#clip0_8522_10)">
                                                    <path
                                                        d="M25.5035 7.58789C32.2077 10.0444 37.0184 16.4927 37.0184 24.0669C37.0184 31.6411 32.1565 38.0382 25.4523 40.4436C18.697 37.9871 13.9375 31.5899 13.9375 24.0157C13.9375 16.4415 18.7993 10.0444 25.5035 7.58789Z"
                                                        fill="white"></path>
                                                    <path
                                                        d="M25.4524 41.5694L25.0942 41.4671C17.7759 38.8059 12.9141 31.7946 12.9141 24.0669C12.9141 16.2879 17.8782 9.32786 25.1966 6.71783L25.5548 6.61548L25.8619 6.66666C33.1802 9.32786 38.042 16.3391 38.042 24.0669C38.042 31.8458 33.0778 38.8059 25.8107 41.4159L25.4524 41.5694ZM25.5036 8.71374C19.2088 11.1191 14.9611 17.2603 14.9611 24.0157C14.9611 30.7711 19.1577 36.9123 25.4524 39.3688C31.7472 36.9123 35.9437 30.8222 35.9949 24.0669C35.9949 17.3115 31.7984 11.1702 25.5036 8.71374Z"
                                                        fill="#282D33"></path>
                                                    <path
                                                        d="M31.8005 31.1292C30.3164 33.6881 28.0646 35.684 25.4545 36.9122C20.4904 34.6093 17.215 29.5427 17.2662 24.0156C17.2662 21.5079 17.9315 19.1026 19.1598 16.9532C20.6439 14.3943 22.8957 12.3984 25.5569 11.1702C30.5211 13.4731 33.7964 18.5397 33.7452 24.0668C33.6941 26.5233 33.0288 28.9798 31.8005 31.1292Z"
                                                        fill="#5EB4E7"></path>
                                                    <path
                                                        d="M24.4219 39.4629L24.4858 7.57959L26.5329 7.58368L26.469 39.467L24.4219 39.4629Z"
                                                        fill="#282D33"></path>
                                                    <path
                                                        d="M25.4064 45.4589C24.8434 45.4589 24.3828 44.9983 24.3828 44.4354V40.4947C24.3828 39.9318 24.8434 39.4712 25.4064 39.4712C25.9693 39.4712 26.4299 39.9318 26.4299 40.4947V44.4354C26.4299 44.9983 25.9693 45.4589 25.4064 45.4589Z"
                                                        fill="#282D33"></path>
                                                    <path
                                                        d="M16.3984 16.1825L17.7823 14.6738L25.7403 21.9737L24.3565 23.4824L16.3984 16.1825Z"
                                                        fill="#282D33"></path>
                                                    <path
                                                        d="M13.6016 24.2189L14.9854 22.7102L25.7346 32.57L24.3508 34.0787L13.6016 24.2189Z"
                                                        fill="#282D33"></path>
                                                    <path
                                                        d="M25.2266 21.9822L33.2174 14.7151L34.595 16.2299L26.6042 23.4971L25.2266 21.9822Z"
                                                        fill="#282D33"></path>
                                                    <path
                                                        d="M25.1058 32.5888L35.8991 22.7874L37.2747 24.3022L26.4815 34.1036L25.1058 32.5888ZM4.06337 38.857C-2.12906 28.9286 -0.644925 16.1855 7.64576 7.89484C17.4206 -1.87998 33.3366 -1.87998 43.1115 7.89484L41.6785 9.3278C32.6713 0.32063 18.0859 0.32063 9.07872 9.3278C1.50451 16.902 0.122731 28.6216 5.80339 37.7311L4.06337 38.857Z"
                                                        fill="#282D33"></path>
                                                    <path
                                                        d="M43.3649 9.99304H36.4049V7.94596H41.3179V3.03296H43.3649V9.99304ZM25.5553 50.5253C19.107 50.5253 12.7099 48.0688 7.79688 43.207L9.22983 41.774C18.237 50.7812 32.8225 50.7812 41.8296 41.774C49.4038 34.1998 50.7344 22.1732 45.0026 13.166L46.7426 12.0913C52.9862 21.9173 51.5021 35.0186 43.2626 43.2581C38.4008 48.12 31.9525 50.5253 25.5553 50.5253Z"
                                                        fill="#282D33"></path>
                                                    <path
                                                        d="M9.64083 48.0177H7.59375V41.0576H14.5538V43.1047H9.64083V48.0177Z"
                                                        fill="#282D33"></path>
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_8522_10">
                                                        <rect width="50.3071" height="50" fill="white"
                                                              transform="translate(0.273438 0.525391)"></rect>
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        </div>
                                        <div class="content">
                                            <p>Uses eco-friendly dyes <br> responsible water recycling</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sw-dots style-2 sw-pagination-mb justify-content-center"></div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /iconbox -->
        <!-- Trust indicators -->
        <section class="flat-spacing-10 bg-white">
            <div class="container">
                <!-- Trust Stats -->
                <div class="row text-center mb-5">
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="trust-stat">
                            <div class="trust-number">{{ number_format($trustData['total_customers']) }}+</div>
                            <div class="trust-label">Happy Customers</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="trust-stat">
                            <div class="trust-number">{{ number_format($trustData['products_sold']) }}+</div>
                            <div class="trust-label">Products Sold</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="trust-stat">
                            <div class="trust-number">{{ $trustData['average_rating'] }}/5</div>
                            <div class="trust-label">Average Rating</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="trust-stat">
                            <div class="trust-number">{{ $trustData['satisfaction_rate'] }}%</div>
                            <div class="trust-label">Satisfaction Rate</div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                @if(count($trustData['recent_purchases']) > 0)
                    <div class="recent-activity">
                        <h6 class="text-center mb-4">Recent Activity</h6>
                        <div class="activity-list">
                            @foreach($trustData['recent_purchases'] as $purchase)
                                <div class="activity-item">
                                    <div class="activity-avatar">
                                        {{ substr($purchase['customer_name'], 0, 1) }}
                                    </div>
                                    <div class="activity-content">
                                        <strong>{{ $purchase['customer_name'] }}</strong>
                                        from {{ $purchase['location'] }}
                                        purchased <strong>{{ $purchase['product'] }}</strong>
                                        <span class="activity-time">{{ $purchase['time_ago'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </section>
        <!-- /Trust indicators -->

        <!-- Shop gram (instagram gallery) -->
        <section class="flat-spacing-10 bg-color-white">
            <div class="container">
                <div class="flat-title mb_1 wow fadeInUp" data-wow-delay="0s">
                    <span class="title font-young-serif">Be active. Be social</span>
                    <p class="sub-title text_black-2">Follow <strong>@shop_with_carl</strong> on Instagram</p>
                </div>

                <div class="wrap-carousel wrap-shop-gram">
                    <div dir="ltr"
                         class="swiper tf-sw-shop-gallery swiper-initialized swiper-horizontal swiper-pointer-events swiper-backface-hidden"
                         data-preview="5" data-tablet="3" data-mobile="2" data-space-lg="7" data-space-md="7">
                        <div class="swiper-wrapper" id="swiper-wrapper-gallery" aria-live="polite">
                            @forelse($galleryItems as $item)
                                <div class="swiper-slide" role="group"
                                     aria-label="{{ $loop->iteration }} / {{ $galleryItems->count() }}">
                                    <div class="gallery-item hover-img wow fadeInUp"
                                         data-wow-delay="{{ $loop->index * 0.1 }}s">
                                        <div class="img-style">
                                            <img class="img-hover lazyloaded"
                                                 src="{{ $item->image_url }}"
                                                 alt="{{ $item->caption ?? 'Gallery Image' }}">
                                        </div>

                                        @if($item->product)
                                            <a href="{{ route('products.show', $item->product->slug) }}"
                                               class="box-icon">
                                                <span class="icon icon-bag"></span>
                                                <span class="tooltip">Shop This Look</span>
                                            </a>
                                        @elseif($item->link)
                                            <a href="{{ $item->link }}" class="box-icon" target="_blank">
                                                <span class="icon icon-view"></span>
                                                <span class="tooltip">View Post</span>
                                            </a>
                                        @else
                                            <div class="box-icon">
                                                <span class="icon icon-heart"></span>
                                                <span class="tooltip">{{ $item->caption ?? 'Love This' }}</span>
                                            </div>
                                        @endif

                                        @if($item->hashtags)
                                            <div class="gallery-hashtags">
                                                {{ $item->hashtags_string }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                 Fallback to original hardcoded gallery
                                <div class="swiper-slide" role="group" aria-label="1 / 1">
                                    <div class="gallery-item hover-img wow fadeInUp" data-wow-delay="0s">
                                        <div class="img-style">
                                            <img class="img-hover lazyloaded"
                                                 src="images/products/shop_with_carl-10.jpg"
                                                 alt="Gallery Image">
                                        </div>
                                        <div class="box-icon">
                                            <span class="icon icon-bag"></span>
                                            <span class="tooltip">Shop This Look</span>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div
                        class="sw-dots sw-pagination-gallery justify-content-center swiper-pagination-clickable swiper-pagination-bullets swiper-pagination-horizontal">
                        @forelse($galleryItems as $item)
                            <span
                                class="swiper-pagination-bullet {{ $loop->first ? 'swiper-pagination-bullet-active' : '' }}"
                                tabindex="0" role="button" aria-label="Go to slide {{ $loop->iteration }}"
                          {{ $loop->first ? 'aria-current="true"' : '' }}></span>
                        @empty
                            <span class="swiper-pagination-bullet swiper-pagination-bullet-active" tabindex="0"
                                  role="button" aria-label="Go to slide 1" aria-current="true"></span>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
        <!-- Shop gram -->

    </div>

    <!-- gotop -->
    <button id="goTop">
        <span class="border-progress"></span>
        <span class="icon icon-arrow-up"></span>
    </button>
    <!-- /gotop -->

    <!-- toolbar-bottom -->
    <div class="tf-toolbar-bottom type-1150">
        <div class="toolbar-item">
            <a href="{{ route('shop.index') }}">
                <div class="toolbar-icon">
                    <i class="icon-shop"></i>
                </div>
                <div class="toolbar-label">Shop</div>
            </a>
        </div>

        <div class="toolbar-item">
            <a href="#canvasSearch" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft">
                <div class="toolbar-icon">
                    <i class="icon-search"></i>
                </div>
                <div class="toolbar-label">Search</div>
            </a>
        </div>
        <div class="toolbar-item">
            @guest
                <a href="{{ route('login') }}">
                    <div class="toolbar-icon">
                        <i class="icon-account"></i>
                    </div>
                    <div class="toolbar-label">Account</div>
                </a>
            @else
                <a href="{{ route('account.dashboard') }}">
                    <div class="toolbar-icon">
                        <i class="icon-account"></i>
                    </div>
                    <div class="toolbar-label">Account</div>
                </a>
            @endguest
        </div>
        <div class="toolbar-item">
            <a href="{{ route('wishlist.index') }}">
                <div class="toolbar-icon">
                    <i class="icon-heart"></i>
                    <div class="toolbar-count">{{ $layoutData['wishlistCount'] ?? 0 }}</div>
                </div>
                <div class="toolbar-label">Wishlist</div>
            </a>
        </div>
        <div class="toolbar-item">
            <a href="{{ route('cart.index') }}">
                <div class="toolbar-icon">
                    <i class="icon-bag"></i>
                    <div class="toolbar-count">{{ $layoutData['cartCount'] ?? 0 }}</div>
                </div>
                <div class="toolbar-label">Cart</div>
            </a>
        </div>
    </div>
    <!-- /toolbar-bottom -->

    <!-- mobile menu -->
    <div class="offcanvas offcanvas-start canvas-mb" id="mobileMenu">
        <span class="icon-close icon-close-popup" data-bs-dismiss="offcanvas" aria-label="Close"></span>
        <div class="mb-canvas-content">
            <div class="mb-body">
                <ul class="nav-ul-mb" id="wrapper-menu-navigation">
                    <li class="nav-mb-item"><a href="{{ route('home') }}" class="mb-menu-link"><span>Home</span></a></li>
                    <li class="nav-mb-item"><a href="{{ route('shop.index') }}" class="mb-menu-link"><span>Shop</span></a></li>

                    @foreach($categories as $cat)
                        <li class="nav-mb-item">
                            <a href="{{ route('categories.show', $cat->slug) }}" class="mb-menu-link">
                                <span>{{ $cat->name }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>

                <div class="mb-other-content">
                    <div class="d-flex group-icon">
                        <a href="{{ route('wishlist.index') }}" class="site-nav-icon"><i class="icon icon-heart"></i>Wishlist</a>
                        <a href="#" class="site-nav-icon" data-bs-toggle="offcanvas" data-bs-target="#canvasSearch"><i class="icon icon-search"></i>Search</a>
                    </div>
                    <div class="mb-notice">
                        <a href="{{ route('pages.contact') }}" class="text-need">Need help ?</a>
                    </div>
                </div>
            </div>
            <div class="mb-bottom">
                @guest
                    <a href="{{ route('login') }}" class="site-nav-icon"><i class="icon icon-account"></i>Login</a>
                @else
                    <a href="{{ route('account.dashboard') }}" class="site-nav-icon"><i class="icon icon-account"></i>My Account</a>
                @endguest
            </div>
        </div>
    </div>
    <!-- /mobile menu -->


    <!-- canvasSearch -->
    <div class="offcanvas offcanvas-end canvas-search" id="canvasSearch">
        <div class="canvas-wrapper">
            <header class="tf-search-head">
                <div class="title fw-5">
                    Search our site
                    <div class="close">
                        <span class="icon-close icon-close-popup" data-bs-dismiss="offcanvas" aria-label="Close"></span>
                    </div>
                </div>
                <div class="tf-search-sticky">
                    <form class="tf-mini-search-frm">
                        <fieldset class="text">
                            <input type="text" placeholder="Search" class="" name="text" tabindex="0" value=""
                                   aria-required="true" required="">
                        </fieldset>
                        <button class="" type="submit"><i class="icon-search"></i></button>
                    </form>
                </div>
            </header>
            <div class="canvas-body p-0">
                <div class="tf-search-content">
                    <div class="tf-cart-hide-has-results">
                        <div class="tf-col-quicklink">
                            <div class="tf-search-content-title fw-5">Quick link</div>
                            <ul class="tf-quicklink-list">
                                <li class="tf-quicklink-item">
                                    <a href="shop-default.html" class="">Fashion</a>
                                </li>
                                <li class="tf-quicklink-item">
                                    <a href="shop-default.html" class="">Men</a>
                                </li>
                                <li class="tf-quicklink-item">
                                    <a href="shop-default.html" class="">Women</a>
                                </li>
                                <li class="tf-quicklink-item">
                                    <a href="shop-default.html" class="">Accessories</a>
                                </li>
                            </ul>
                        </div>
                        <div class="tf-col-content">
                            <div class="tf-search-content-title fw-5">Need some inspiration?</div>
                            <div class="tf-search-hidden-inner">
                                <div class="tf-loop-item">
                                    <div class="image">
                                        <a href="product-detail.html">
                                            <img src="images/products/white-3.jpg" alt="">
                                        </a>
                                    </div>
                                    <div class="content">
                                        <a href="product-detail.html">Cotton jersey top</a>
                                        <div class="tf-product-info-price">
                                            <div class="compare-at-price">UGX 10,000</div>
                                            <div class="price-on-sale fw-6">UGX 8,000</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tf-loop-item">
                                    <div class="image">
                                        <a href="product-detail.html">
                                            <img src="images/products/white-2.jpg" alt="">
                                        </a>
                                    </div>
                                    <div class="content">
                                        <a href="product-detail.html">Mini crossbody bag</a>
                                        <div class="tf-product-info-price">
                                            <div class="price fw-6">UGX 18,000</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tf-loop-item">
                                    <div class="image">
                                        <a href="product-detail.html">
                                            <img src="images/products/white-1.jpg" alt="">
                                        </a>
                                    </div>
                                    <div class="content">
                                        <a href="product-detail.html">Oversized Printed T-shirt</a>
                                        <div class="tf-product-info-price">
                                            <div class="price fw-6">UGX 18,000</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /canvasSearch -->


    @push('styles')
        <style>
            /* Trust Indicators Styles */
            .trust-stat {
                padding: 2rem 1rem;
                background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
                border-radius: 15px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.08);
                transition: transform 0.3s ease;
            }

            .trust-stat:hover {
                transform: translateY(-5px);
            }

            .trust-number {
                font-size: 2.5rem;
                font-weight: 700;
                color: #2c3e50;
                margin-bottom: 0.5rem;
            }

            .trust-label {
                color: #6c757d;
                font-weight: 500;
                text-transform: uppercase;
                font-size: 0.875rem;
                letter-spacing: 1px;
            }

            /* Recent Activity Styles */
            .recent-activity {
                background: #f8f9fa;
                border-radius: 15px;
                padding: 2rem;
                margin-top: 3rem;
            }

            .activity-list {
                display: flex;
                flex-direction: column;
                gap: 1rem;
                max-height: 300px;
                overflow-y: auto;
            }

            .activity-item {
                display: flex;
                align-items: center;
                gap: 1rem;
                padding: 1rem;
                background: white;
                border-radius: 10px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.05);
                animation: slideInUp 0.5s ease-out;
            }

            .activity-avatar {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: linear-gradient(135deg, #007bff, #0056b3);
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: bold;
                font-size: 1.1rem;
            }

            .activity-content {
                flex: 1;
                font-size: 0.9rem;
                line-height: 1.4;
            }

            .activity-time {
                display: block;
                color: #6c757d;
                font-size: 0.8rem;
                margin-top: 0.25rem;
            }

            /* Gallery Enhancements */
            .gallery-hashtags {
                position: absolute;
                bottom: 10px;
                left: 10px;
                right: 10px;
                background: rgba(0,0,0,0.7);
                color: white;
                padding: 0.5rem;
                font-size: 0.8rem;
                border-radius: 5px;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .gallery-item:hover .gallery-hashtags {
                opacity: 1;
            }

            @keyframes slideInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @media (max-width: 768px) {
                .trust-number {
                    font-size: 2rem;
                }

                .activity-item {
                    padding: 0.75rem;
                }

                .activity-avatar {
                    width: 35px;
                    height: 35px;
                }
            }
        </style>
    @endpush

    <script>
        document.getElementById('goTop').addEventListener('click', () => {
            window.scrollTo({top: 0, behavior: 'smooth'});
        });

        class UltraModernSlider {
            constructor() {
                this.currentSlide = 0;
                this.totalSlides = 3;
                this.autoplayInterval = null;
                this.slideInterval = 6000;
                this.isPlaying = true;
                this.isDragging = false;
                this.startX = 0;
                this.currentX = 0;

                this.init();
            }

            init() {
                this.bindEvents();
                this.startAutoplay();
                this.updateSlideCounter();
            }

            bindEvents() {
                // Navigation arrows
                document.querySelector('.prev-arrow').addEventListener('click', () => this.prevSlide());
                document.querySelector('.next-arrow').addEventListener('click', () => this.nextSlide());

                // Pagination dots
                document.querySelectorAll('.dot').forEach((dot, index) => {
                    dot.addEventListener('click', () => this.goToSlide(index));
                });

                // Side navigation
                document.querySelectorAll('.side-nav-dot').forEach((dot, index) => {
                    dot.addEventListener('click', () => this.goToSlide(index));
                });

                // Keyboard navigation
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'ArrowLeft') this.prevSlide();
                    if (e.key === 'ArrowRight') this.nextSlide();
                });

                // Only bind drag events to the slider content area, not the entire container
                const slidesWrapper = document.querySelector('.slides-wrapper');

                // Mouse events
                slidesWrapper.addEventListener('mousedown', (e) => this.handleDragStart(e));
                document.addEventListener('mousemove', (e) => this.handleDragMove(e));
                document.addEventListener('mouseup', () => this.handleDragEnd());

                // Touch events - only horizontal dragging
                slidesWrapper.addEventListener('touchstart', (e) => this.handleDragStart(e), {passive: true});
                slidesWrapper.addEventListener('touchmove', (e) => {
                    if (this.isDragging) {
                        const touch = e.touches[0];
                        const deltaX = Math.abs(touch.clientX - this.startX);
                        const deltaY = Math.abs(touch.clientY - this.startY);

                        // Only handle horizontal swipes
                        if (deltaX > deltaY && deltaX > 10) {
                            e.preventDefault();
                            this.handleDragMove(e);
                        }
                    }
                }, {passive: false});
                slidesWrapper.addEventListener('touchend', () => this.handleDragEnd(), {passive: true});

                // Pause on hover - only on slider area
                slidesWrapper.addEventListener('mouseenter', () => this.pauseAutoplay());
                slidesWrapper.addEventListener('mouseleave', () => this.resumeAutoplay());
            }

            handleDragStart(e) {
                this.isDragging = true;
                this.startX = e.type === 'mousedown' ? e.clientX : e.touches[0].clientX;
                this.startY = e.type === 'mousedown' ? e.clientY : e.touches[0].clientY;
                this.pauseAutoplay();
            }

            handleDragMove(e) {
                if (!this.isDragging) return;
                e.preventDefault();
                this.currentX = e.type === 'mousemove' ? e.clientX : e.touches[0].clientX;
            }

            handleDragEnd() {
                if (!this.isDragging) return;
                this.isDragging = false;

                const deltaX = this.currentX - this.startX;
                const threshold = 50;

                if (Math.abs(deltaX) > threshold) {
                    if (deltaX > 0) {
                        this.prevSlide();
                    } else {
                        this.nextSlide();
                    }
                }

                this.resumeAutoplay();
            }

            goToSlide(index) {
                if (index === this.currentSlide) return;

                this.currentSlide = index;
                this.updateSlider();
                this.restartAutoplay();
            }

            nextSlide() {
                this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
                this.updateSlider();
                this.restartAutoplay();
            }

            prevSlide() {
                this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
                this.updateSlider();
                this.restartAutoplay();
            }

            updateSlider() {
                const slides = document.querySelectorAll('.slide');
                const slidesWrapper = document.querySelector('.slides-wrapper');

                // Update slide positions
                slidesWrapper.style.transform = `translateX(-${this.currentSlide * 100}%)`;

                // Update active states
                slides.forEach((slide, index) => {
                    slide.classList.toggle('active', index === this.currentSlide);
                });

                // Update navigation dots
                document.querySelectorAll('.dot').forEach((dot, index) => {
                    dot.classList.toggle('active', index === this.currentSlide);
                });

                // Update side navigation
                document.querySelectorAll('.side-nav-dot').forEach((dot, index) => {
                    dot.classList.toggle('active', index === this.currentSlide);
                });

                // Update slide counter
                this.updateSlideCounter();

                // Restart progress bar
                this.startProgressBar();
            }

            updateSlideCounter() {
                const currentSlideEl = document.querySelector('.current-slide');
                currentSlideEl.textContent = String(this.currentSlide + 1).padStart(2, '0');
            }

            startProgressBar() {
                const progressFill = document.querySelector('.progress-fill');
                progressFill.style.transition = 'none';
                progressFill.style.width = '0%';

                // Force reflow
                progressFill.offsetHeight;

                progressFill.style.transition = `width ${this.slideInterval}ms linear`;
                progressFill.style.width = '100%';
            }

            startAutoplay() {
                if (this.autoplayInterval) return;

                this.autoplayInterval = setInterval(() => {
                    this.nextSlide();
                }, this.slideInterval);

                this.startProgressBar();
            }

            pauseAutoplay() {
                if (this.autoplayInterval) {
                    clearInterval(this.autoplayInterval);
                    this.autoplayInterval = null;
                }

                // Pause progress bar
                const progressFill = document.querySelector('.progress-fill');
                const currentWidth = getComputedStyle(progressFill).width;
                progressFill.style.transition = 'none';
                progressFill.style.width = currentWidth;
            }

            resumeAutoplay() {
                if (!this.autoplayInterval) {
                    this.startAutoplay();
                }
            }

            restartAutoplay() {
                this.pauseAutoplay();
                this.startAutoplay();
            }
        }

        // Initialize slider when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            new UltraModernSlider();
        });

        // Add some interactive particles on mouse move
        document.addEventListener('mousemove', (e) => {
            const particles = document.querySelectorAll('.particle');
            const mouseX = e.clientX / window.innerWidth;
            const mouseY = e.clientY / window.innerHeight;

            particles.forEach((particle, index) => {
                const speed = (index % 3 + 1) * 0.5;
                const x = mouseX * 20 * speed;
                const y = mouseY * 20 * speed;

                particle.style.transform = `translate(${x}px, ${y}px)`;
            });
        });

        // Add scroll-based interactions (if needed for future enhancements)
        let ticking = false;

        function updateParallax() {
            const slides = document.querySelectorAll('.slide.active');
            slides.forEach(slide => {
                const bg = slide.querySelector('.slide-bg');
                if (bg) {
                    // Subtle parallax effect on mouse movement
                    const rect = slide.getBoundingClientRect();
                    const centerX = rect.left + rect.width / 2;
                    const centerY = rect.top + rect.height / 2;

                    // This could be enhanced with actual mouse position tracking
                    bg.style.transform = `scale(1.05) translate(0px, 0px)`;
                }
            });
            ticking = false;
        }

        // Intersection Observer for performance optimization
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const slideObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in-view');
                } else {
                    entry.target.classList.remove('in-view');
                }
            });
        }, observerOptions);

        // Observe all slides for performance
        document.querySelectorAll('.slide').forEach(slide => {
            slideObserver.observe(slide);
        });


    </script>

    <!-- Swiper Initialization Script -->

    <script>
        (function initSwipersWhenReady() {
            function startInit() {
                // Initialize Season Collection Swiper with better error handling
                const collectionEl = document.querySelector('.tf-sw-collection');
                if (collectionEl && !collectionEl.swiper) {
                    try {
                        new (window.Swiper)('.tf-sw-collection', {
                            slidesPerView: 2,
                            spaceBetween: 15,
                            loop: false,
                            autoplay: false,
                            watchOverflow: true,
                            navigation: {
                                nextEl: '.nav-next-collection',
                                prevEl: '.nav-prev-collection',
                            },
                            pagination: {
                                el: '.sw-pagination-collection',
                                clickable: true,
                            },
                            breakpoints: {
                                768: {
                                    slidesPerView: 3,
                                    spaceBetween: 30,
                                },
                                1024: {
                                    slidesPerView: 6,
                                    spaceBetween: 50,
                                }
                            },
                            on: {
                                init: function() {
                                    console.log('Collection swiper initialized');
                                    // Force image loading
                                    this.slides.forEach(slide => {
                                        const img = slide.querySelector('img');
                                        if (img && img.dataset.src) {
                                            img.src = img.dataset.src;
                                        }
                                    });
                                },
                                slideChange: function() {
                                    // Ensure images are loaded when slides change
                                    const activeSlide = this.slides[this.activeIndex];
                                    if (activeSlide) {
                                        const img = activeSlide.querySelector('img[data-src]');
                                        if (img && !img.src) {
                                            img.src = img.dataset.src;
                                        }
                                    }
                                }
                            }
                        });
                    } catch (error) {
                        console.error('Error initializing collection swiper:', error);
                    }
                }

                // Initialize New Releases Swiper with better error handling
                const brandEl = document.querySelector('.tf-sw-brand');
                if (brandEl && !brandEl.swiper) {
                    try {
                        new (window.Swiper)('.tf-sw-brand', {
                            slidesPerView: 2,
                            spaceBetween: 15,
                            loop: false,
                            autoplay: false,
                            watchOverflow: true,
                            navigation: {
                                nextEl: '.nav-next-brand',
                                prevEl: '.nav-prev-brand',
                            },
                            breakpoints: {
                                768: {
                                    slidesPerView: 3,
                                    spaceBetween: 15,
                                },
                                1024: {
                                    slidesPerView: 4,
                                    spaceBetween: 30,
                                }
                            },
                            on: {
                                init: function() {
                                    console.log('Brand swiper initialized');
                                    // Force image loading
                                    this.slides.forEach(slide => {
                                        const imgs = slide.querySelectorAll('img');
                                        imgs.forEach(img => {
                                            if (img.dataset.src && !img.src) {
                                                img.src = img.dataset.src;
                                            }
                                        });
                                    });
                                },
                                slideChange: function() {
                                    // Ensure images are loaded when slides change
                                    const activeSlide = this.slides[this.activeIndex];
                                    if (activeSlide) {
                                        const imgs = activeSlide.querySelectorAll('img[data-src]');
                                        imgs.forEach(img => {
                                            if (!img.src) {
                                                img.src = img.dataset.src;
                                            }
                                        });
                                    }
                                }
                            }
                        });
                    } catch (error) {
                        console.error('Error initializing brand swiper:', error);
                    }
                }

                // Initialize Gallery Swiper if it exists
                const galleryEl = document.querySelector('.tf-sw-shop-gallery');
                if (galleryEl && !galleryEl.swiper) {
                    try {
                        new (window.Swiper)('.tf-sw-shop-gallery', {
                            slidesPerView: 2,
                            spaceBetween: 7,
                            loop: false,
                            autoplay: false,
                            navigation: {
                                nextEl: '.nav-next-gallery',
                                prevEl: '.nav-prev-gallery',
                            },
                            pagination: {
                                el: '.sw-pagination-gallery',
                                clickable: true,
                            },
                            breakpoints: {
                                768: {
                                    slidesPerView: 3,
                                    spaceBetween: 7,
                                },
                                1024: {
                                    slidesPerView: 5,
                                    spaceBetween: 7,
                                }
                            },
                            on: {
                                init: function() {
                                    console.log('Gallery swiper initialized');
                                }
                            }
                        });
                    } catch (error) {
                        console.error('Error initializing gallery swiper:', error);
                    }
                }
            }

            function tryInit() {
                if (window.Swiper) {
                    startInit();
                } else {
                    // Retry with exponential backoff
                    let attempts = 0;
                    const maxAttempts = 20;
                    const timer = setInterval(() => {
                        attempts++;
                        if (window.Swiper) {
                            clearInterval(timer);
                            startInit();
                        } else if (attempts >= maxAttempts) {
                            clearInterval(timer);
                            console.warn('Swiper library not found after maximum attempts');

                            // Fallback: try to load from CDN
                            if (!window.__loadingSwiperCDN && !window.Swiper) {
                                window.__loadingSwiperCDN = true;
                                const link = document.createElement('link');
                                link.rel = 'stylesheet';
                                link.href = 'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css';
                                document.head.appendChild(link);

                                const script = document.createElement('script');
                                script.src = 'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js';
                                script.async = true;
                                script.onload = () => {
                                    window.__loadingSwiperCDN = false;
                                    if (window.Swiper) {
                                        startInit();
                                    }
                                };
                                script.onerror = () => {
                                    window.__loadingSwiperCDN = false;
                                    console.error('Failed to load Swiper from CDN.');
                                };
                                document.head.appendChild(script);
                            }
                        }
                    }, Math.min(100 * Math.pow(1.5, attempts), 1000)); // Exponential backoff
                }
            }

            // Initialize when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', tryInit);
            } else {
                // DOM is already loaded
                setTimeout(tryInit, 100); // Small delay to ensure all scripts are loaded
            }
        })();

        // Additional image error handling
        document.addEventListener('DOMContentLoaded', function() {
            // Handle image loading errors globally
            document.addEventListener('error', function(e) {
                if (e.target.tagName === 'IMG') {
                    const img = e.target;
                    if (!img.getAttribute('data-error-handled')) {
                        img.setAttribute('data-error-handled', 'true');
                        img.src = '{{ asset('images/products/shop_with_carl-1.jpg') }}';
                        console.warn('Image load error, using fallback:', img.alt || 'Unknown image');
                    }
                }
            }, true);

            // Pre-load critical images
            const criticalImages = [
                '{{ asset('images/products/shop_with_carl-1.jpg') }}',
                '{{ asset('images/placeholder-product.jpg') }}',
                '{{ asset('images/placeholder-category.svg') }}'
            ];

            criticalImages.forEach(src => {
                const img = new Image();
                img.src = src;
            });
        });
    </script>

</x-app-layout>

