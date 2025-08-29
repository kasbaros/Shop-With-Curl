<div class="product-detail-page">

    <!-- breadcrumb -->
    <div class="tf-breadcrumb">
        <div class="container">
            <div class="tf-breadcrumb-wrap d-flex justify-content-between flex-wrap align-items-center">
                <div class="tf-breadcrumb-list">
                    <a href="{{ route('home') }}" class="text">Home</a>
                    <i class="icon icon-arrow-right"></i>
                    @foreach($product->categories as $category)
                        <a href="{{ route('categories.show', $category->slug) }}" class="text">{{ $category->name }}</a>
                        @if(!$loop->last)
                            <i class="icon icon-arrow-right"></i>
                        @endif
                    @endforeach
                    <i class="icon icon-arrow-right"></i>
                    <span class="text">{{ $product->name }}</span>
                </div>
                <div class="tf-breadcrumb-prev-next">
                    @php
                        $prev = $product->previous();
                        $next = $product->next();
                    @endphp
                    @if(!empty($prev))
                        <a href="{{ route('products.show', $prev->slug) }}"
                           class="tf-breadcrumb-prev hover-tooltip center">
                            <i class="icon icon-arrow-left"></i>
                        </a>
                    @endif
                    <a href="{{ route('shop.index') }}" class="tf-breadcrumb-back hover-tooltip center">
                        <i class="icon icon-shop"></i>
                    </a>
                    @if(!empty($next))
                        <a href="{{ route('products.show', $next->slug) }}"
                           class="tf-breadcrumb-next hover-tooltip center">
                            <i class="icon icon-arrow-right"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- /breadcrumb -->

        <!-- default -->
        <section class="flat-spacing-4 pt_0">
            <div class="tf-main-product">
                <div class="container">
                    <div class="row">
                        <!-- Media/Gallery -->
                        <div class="col-md-6">
                                <div class="tf-product-media-wrap sticky-top">
                                    @php
                                        $gallery = $product->images ?? []; // Use the product's images
                                        $hasImages = !empty($gallery);
                                    @endphp

                                    <div class="thumbs-slider">
                                        <!-- Thumbs -->
                                        <div dir="ltr" class="swiper tf-product-media-thumbs" data-direction="vertical">
                                            <div class="swiper-wrapper stagger-wrap">
                                                @foreach($gallery as $index => $image)
                                                    <div class="swiper-slide stagger-item" wire:key="thumb-{{ $index }}">
                                                        <div class="item">
                                                            <img class="lazyload" data-src="{{ $image['thumb'] }}"
                                                                 src="{{ $image['thumb'] }}" alt="{{ $product->name }}">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Main -->
                                        <div dir="ltr" class="swiper tf-product-media-main tf-product-zoom-inner"
                                             id="gallery-swiper-started">
                                            <div class="swiper-wrapper">
                                                @foreach($gallery as $index => $image)
                                                    <div class="swiper-slide" wire:key="main-{{ $index }}">
                                                        <a href="{{ $image['large'] }}" target="_blank" class="item"
                                                           data-pswp-width="1200" data-pswp-height="1200">
                                                            <img class="tf-image-zoom-inner lazyload"
                                                                 data-zoom="{{ $image['large'] }}"
                                                                 data-src="{{ $image['large'] }}"
                                                                 src="{{ $image['large'] }}"
                                                                 alt="{{ $product->name }}">
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div class="swiper-button-next button-style-arrow thumbs-next"></div>
                                            <div class="swiper-button-prev button-style-arrow thumbs-prev"></div>
                                        </div>
                                    </div>
                                </div>
                        </div>

                        <!-- Info -->
                        <div class="col-md-6">
                            <div class="tf-product-info-wrap position-relative">
                                <div class="tf-zoom-main"></div>

                                <div class="tf-product-info-list">
                                    <div class="tf-product-info-title">
                                            <h5>{{ $product->name }}</h5>
                                            @if(!empty($product->sku))
                                                <div class="text_black-2 fs-12 mt_4">SKU: {{ $product->sku }}</div>
                                            @endif
                                    </div>

                                    <!-- Badges / Status -->
                                    <div class="tf-product-info-badges">
                                            @if(!empty($product->is_featured))
                                                <div class="badges">Best seller</div>
                                            @endif
                                            @if($product->stock_quantity <= 5 && $product->stock_quantity > 0)
                                                <div class="product-status-content">
                                                    <i class="icon-lightning"></i>
                                                    <p class="fw-6">Hurry! Only {{ $product->stock_quantity }} left in
                                                        stock.</p>
                                                </div>
                                            @endif
                                    </div>

                                    <!-- Price -->
                                    <div class="tf-product-info-price">
                                            @php
                                                $price = (float) ($product->price ?? 0);
                                                $sale  = (float) ($product->sale_price ?? 0);
                                                $onSale = $sale > 0 && $sale < $price;
                                                $effective = $onSale ? $sale : $price;
                                                $discountPct = $onSale ? round((($price - $sale) / max($price, 1)) * 100) : 0;
                                            @endphp

                                            @if($onSale)
                                                <div class="price-on-sale">${{ number_format($effective, 2) }}</div>
                                                <div class="compare-at-price">${{ number_format($price, 2) }}</div>
                                                <div class="badges-on-sale"><span>{{ $discountPct }}</span>% OFF</div>
                                            @else
                                                <div class="price">${{ number_format($effective, 2) }}</div>
                                            @endif
                                    </div>

                                    <!-- Rating summary (read-only) -->
                                    @if($product->reviews_count > 0)
                                        <div class="d-flex align-items-center gap-10">
                                            <div class="list-star">
                                                @for($i=1; $i<=5; $i++)
                                                    <i class="icon icon-star{{ $i <= round($product->average_rating) ? '' : '-o' }}"></i>
                                                @endfor
                                            </div>
                                            <span class="text_black-2 fs-14">
                                                {{ number_format($product->average_rating, 1) }} ({{ $product->reviews_count }})
                                            </span>
                                        </div>
                                    @endif

                                    <!-- Short Description -->
                                    @if($product->short_description)
                                        <div class="tf-product-description">
                                            <p>{{ $product->short_description }}</p>
                                        </div>
                                    @endif

                                    <!-- Add to Cart (existing Livewire) -->
                                    <div id="atc-root" class="mt_16">
                                        <livewire:client.cart.cart :product="$product"/>
                                    </div>

                                    <!-- Wishlist / Compare -->
                                    <div class="d-flex align-items-center gap-10 mt_12">
                                        @auth
                                            <livewire:client.wishlist-toggle :product="$product"/>
                                        @else
                                            <a href="{{ route('login') }}"
                                               class="tf-product-btn-wishlist hover-tooltip box-icon bg_white wishlist btn-icon-action">
                                                <span class="icon icon-heart"></span>
                                                <span class="tooltip">Add to Wishlist</span>
                                            </a>
                                        @endauth

                                        <a href="#compare" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft"
                                           class="tf-product-btn-wishlist hover-tooltip box-icon bg_white compare btn-icon-action"
                                           onclick="if(window.Livewire?.dispatch){window.Livewire.dispatch('compare:toggle',{id: {{ (int)$product->id }} });}">
                                            <span class="icon icon-compare"></span>
                                            <span class="tooltip">Add to Compare</span>
                                            <span class="icon icon-check"></span>
                                        </a>
                                    </div>

                                    <!-- Delivery / Return -->
                                    <div class="tf-product-info-delivery-return mt_18">
                                        <div class="row">
                                            <div class="col-xl-6 col-12">
                                                <div class="tf-product-delivery">
                                                    <div class="icon"><i class="icon-delivery-time"></i></div>
                                                    <p>Estimate delivery times:
                                                        <span class="fw-7">2-5 days</span> (Domestic),
                                                        <span class="fw-7">7-14 days</span> (International).
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-12">
                                                <div class="tf-product-delivery mb-0">
                                                    <div class="icon"><i class="icon-return-order"></i></div>
                                                    <p>Return within <span class="fw-7">30 days</span> of purchase.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Trust / Payments -->
                                    <div class="tf-product-info-trust-seal">
                                        <div class="tf-product-trust-mess">
                                            <i class="icon-safe"></i>
                                            <p class="fw-6">Guarantee Safe <br> Checkout</p>
                                        </div>
                                        <div class="tf-payment">
                                            <img src="{{ asset('images/payments/visa.png') }}" alt="Visa">
                                            <img src="{{ asset('images/payments/img-1.png') }}" alt="">
                                            <img src="{{ asset('images/payments/img-2.png') }}" alt="">
                                            <img src="{{ asset('images/payments/img-3.png') }}" alt="">
                                            <img src="{{ asset('images/payments/img-4.png') }}" alt="">
                                        </div>
                                    </div>
                                </div> <!-- /info list -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sticky Add to Cart (mirrors template) -->
                    <div class="tf-sticky-btn-atc">
                        <div class="container">
                            <div class="tf-height-observer w-100 d-flex align-items-center">
                                <div class="tf-sticky-atc-product d-flex align-items-center">
                                    <div class="tf-sticky-atc-img">
                                        @php
                                            $thumb = $hasImages && isset($gallery[0]['thumb']) ? $gallery[0]['thumb'] : asset('images/placeholder-product.jpg');
                                        @endphp
                                        <img class="lazyload" data-src="{{ $thumb }}" alt="{{ $product->name }}" src="{{ $thumb }}">
                                    </div>
                                    <div class="tf-sticky-atc-title fw-5 d-xl-block d-none">{{ $product->name }}</div>
                                </div>
                                <div class="tf-sticky-atc-infos">
                                    <form onsubmit="return false;">
                                        <div class="tf-sticky-atc-variant-price text-center">
                                            <span class="fw-6">
                                                @if($onSale)
                                                    <span class="price-on-sale">${{ number_format($effective, 2) }}</span>
                                                    <span class="compare-at-price ms-1">${{ number_format($price, 2) }}</span>
                                                @else
                                                    <span class="price">${{ number_format($effective, 2) }}</span>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="tf-sticky-atc-btns">
                                            <div class="tf-product-info-quantity">
                                                <div class="wg-quantity">
                                                    <span class="btn-quantity minus-btn"
                                                          onclick="window.__pdQty && window.__pdQty(-1)">-</span>
                                                    <input id="stickyQty" type="text" name="number" value="1">
                                                    <span class="btn-quantity plus-btn"
                                                          onclick="window.__pdQty && window.__pdQty(1)">+</span>
                                                </div>
                                            </div>
                                            <a href="javascript:void(0);"
                                               class="tf-btn btn-fill radius-3 justify-content-center fw-6 fs-14 flex-grow-1 animate-hover-btn btn-add-to-cart"
                                               onclick="document.getElementById('atc-root')?.scrollIntoView({behavior:'smooth', block:'center'});">
                                                <span>Add to cart</span>
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
        </section>
        <!-- /default -->


            <!-- Related Products -->
            @if(isset($relatedProducts) && $relatedProducts->isNotEmpty())
                <section class="flat-spacing-1 pt_0">
                    <div class="container">
                        <div class="flat-title">
                            <span class="title">People Also Bought</span>
                        </div>
                        <div class="hover-sw-nav hover-sw-2">
                            <div dir="ltr" class="swiper tf-sw-product-sell wrap-sw-over" data-preview="4" data-tablet="3"
                                 data-mobile="2" data-space-lg="30" data-space-md="15">
                                <div class="swiper-wrapper">
                                    @foreach($relatedProducts as $relatedProduct)
                                        <div class="swiper-slide" lazy="true">
                                            <x-product-card :product="$relatedProduct"/>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="nav-sw nav-next-slider nav-next-product box-icon w_46 round"><span
                                    class="icon icon-arrow-left"></span></div>
                            <div class="nav-sw nav-prev-slider nav-prev-product box-icon w_46 round"><span
                                    class="icon icon-arrow-right"></span></div>
                            <div class="sw-dots style-2 sw-pagination-product justify-content-center"></div>
                        </div>
                    </div>
                </section>
            @endif
            <!-- /Related Products -->

            @push('scripts')
                <script>
                    // Guarded gallery init, uses Swiper already included globally in the template
                    document.addEventListener('DOMContentLoaded', function () {
                        try {
                            const thumbsEl = document.querySelector('.tf-product-media-thumbs');
                            const mainEl = document.querySelector('.tf-product-media-main');

                            if (thumbsEl && mainEl && !thumbsEl.swiper && !mainEl.swiper) {
                                const thumbsSwiper = new Swiper('.tf-product-media-thumbs', {
                                    direction: 'vertical',
                                    slidesPerView: 4,
                                    spaceBetween: 10,
                                    watchSlidesProgress: true,
                                    breakpoints: {
                                        0: {direction: 'horizontal', slidesPerView: 5},
                                        768: {direction: 'vertical'}
                                    }
                                });

                                new Swiper('.tf-product-media-main', {
                                    spaceBetween: 10,
                                    thumbs: {swiper: thumbsSwiper},
                                    navigation: {
                                        nextEl: '.thumbs-next',
                                        prevEl: '.thumbs-prev'
                                    }
                                });
                            }

                            // Simple shared qty control for sticky (delegates to visible input)
                            window.__pdQty = function (delta) {
                                const input = document.getElementById('stickyQty');
                                if (!input) return;
                                const val = Math.max(1, parseInt(input.value || '1', 10) + delta);
                                input.value = val;
                            };
                        } catch (e) {
                            console.warn('Gallery init error', e);
                        }
                    });
                </script>
            @endpush


{{--    <section class="flat-spacing-4 pt_0">--}}
{{--        <div class="tf-main-product">--}}
{{--            <div class="container">--}}
{{--                <div class="row">--}}
{{--                    <div class="col-md-6">--}}
{{--                        <div class="tf-product-media-wrap sticky-top">--}}
{{--                            @php--}}
{{--                                $gallery = $product->images ?? []; // Use the product's images--}}
{{--                                $hasImages = !empty($gallery);--}}
{{--                            @endphp--}}

{{--                            <div class="thumbs-slider">--}}
{{--                                <!-- Thumbs -->--}}
{{--                                <div dir="ltr" class="swiper tf-product-media-thumbs" data-direction="vertical">--}}
{{--                                    <div class="swiper-wrapper stagger-wrap">--}}
{{--                                        @foreach($gallery as $index => $image)--}}
{{--                                            <div class="swiper-slide stagger-item" wire:key="thumb-{{ $index }}">--}}
{{--                                                <div class="item">--}}
{{--                                                    <img class="lazyload" data-src="{{ $image['thumb'] }}"--}}
{{--                                                         src="{{ $image['thumb'] }}" alt="{{ $product->name }}">--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        @endforeach--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <!-- Main -->--}}
{{--                                <div dir="ltr" class="swiper tf-product-media-main tf-product-zoom-inner"--}}
{{--                                     id="gallery-swiper-started">--}}
{{--                                    <div class="swiper-wrapper">--}}
{{--                                        @foreach($gallery as $index => $image)--}}
{{--                                            <div class="swiper-slide" wire:key="main-{{ $index }}">--}}
{{--                                                <a href="{{ $image['large'] }}" target="_blank" class="item"--}}
{{--                                                   data-pswp-width="1200" data-pswp-height="1200">--}}
{{--                                                    <img class="tf-image-zoom-inner lazyload"--}}
{{--                                                         data-zoom="{{ $image['large'] }}"--}}
{{--                                                         data-src="{{ $image['large'] }}"--}}
{{--                                                         src="{{ $image['large'] }}"--}}
{{--                                                         alt="{{ $product->name }}">--}}
{{--                                                </a>--}}
{{--                                            </div>--}}
{{--                                        @endforeach--}}
{{--                                    </div>--}}

{{--                                    <div class="swiper-button-next button-style-arrow thumbs-next"></div>--}}
{{--                                    <div class="swiper-button-prev button-style-arrow thumbs-prev"></div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-6">--}}
{{--                        <div class="tf-product-info-wrap position-relative">--}}
{{--                            <div class="tf-zoom-main"></div>--}}
{{--                            <div class="tf-product-info-list">--}}
{{--                                <div class="tf-product-info-title">--}}
{{--                                    <h5>{{ $product->name }}</h5>--}}
{{--                                    @if(!empty($product->sku))--}}
{{--                                        <div class="text_black-2 fs-12 mt_4">SKU: {{ $product->sku }}</div>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                                <div class="tf-product-info-badges">--}}
{{--                                    @if(!empty($product->is_featured))--}}
{{--                                        <div class="badges">Best seller</div>--}}
{{--                                    @endif--}}
{{--                                    @if($product->stock_quantity <= 5 && $product->stock_quantity > 0)--}}
{{--                                        <div class="product-status-content">--}}
{{--                                            <i class="icon-lightning"></i>--}}
{{--                                            <p class="fw-6">Hurry! Only {{ $product->stock_quantity }} left in--}}
{{--                                                stock.</p>--}}
{{--                                        </div>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                                <div class="tf-product-info-price">--}}
{{--                                    @php--}}
{{--                                        $price = (float) ($product->price ?? 0);--}}
{{--                                        $sale  = (float) ($product->sale_price ?? 0);--}}
{{--                                        $onSale = $sale > 0 && $sale < $price;--}}
{{--                                        $effective = $onSale ? $sale : $price;--}}
{{--                                        $discountPct = $onSale ? round((($price - $sale) / max($price, 1)) * 100) : 0;--}}
{{--                                    @endphp--}}

{{--                                    @if($onSale)--}}
{{--                                        <div class="price-on-sale">${{ number_format($effective, 2) }}</div>--}}
{{--                                        <div class="compare-at-price">${{ number_format($price, 2) }}</div>--}}
{{--                                        <div class="badges-on-sale"><span>{{ $discountPct }}</span>% OFF</div>--}}
{{--                                    @else--}}
{{--                                        <div class="price">${{ number_format($effective, 2) }}</div>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                                <div class="tf-product-info-variant-picker">--}}
{{--                                <div class="tf-product-info-quantity">--}}
{{--                                    <div class="wg-quantity">--}}
{{--                                                <span class="btn-quantity minus-btn"--}}
{{--                                                      onclick="window.__pdQty && window.__pdQty(-1)">-</span>--}}
{{--                                        <input id="stickyQty" type="text" name="number" value="1">--}}
{{--                                        <span class="btn-quantity plus-btn"--}}
{{--                                              onclick="window.__pdQty && window.__pdQty(1)">+</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="tf-product-info-delivery-return">--}}
{{--                                    <div class="row">--}}
{{--                                        <div class="col-xl-6 col-12">--}}
{{--                                            <div class="tf-product-delivery">--}}
{{--                                                <div class="icon">--}}
{{--                                                    <i class="icon-delivery-time"></i>--}}
{{--                                                </div>--}}
{{--                                                <p>Estimate delivery times: <span class="fw-7">12-26 days</span>--}}
{{--                                                    (International), <span class="fw-7">3-6 days</span> (United--}}
{{--                                                    States).</p>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-xl-6 col-12">--}}
{{--                                            <div class="tf-product-delivery mb-0">--}}
{{--                                                <div class="icon">--}}
{{--                                                    <i class="icon-return-order"></i>--}}
{{--                                                </div>--}}
{{--                                                <p>Return within <span class="fw-7">same day</span> of purchase.--}}
{{--                                                    Duties &amp; taxes are non-refundable.</p>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="tf-product-info-trust-seal">--}}
{{--                                    <div class="tf-product-trust-mess">--}}
{{--                                        <i class="icon-safe"></i>--}}
{{--                                        <p class="fw-6">Guarantee Safe <br> Checkout</p>--}}
{{--                                    </div>--}}
{{--                                    <div class="tf-payment">--}}
{{--                                        <img src="images/payments/visa.png" alt="">--}}
{{--                                        <img src="images/payments/img-1.png" alt="">--}}
{{--                                        <img src="images/payments/img-2.png" alt="">--}}
{{--                                        <img src="images/payments/img-3.png" alt="">--}}
{{--                                        <img src="images/payments/img-4.png" alt="">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="tf-sticky-btn-atc">--}}
{{--            <div class="container">--}}
{{--                <div class="tf-height-observer w-100 d-flex align-items-center">--}}
{{--                    <div class="tf-sticky-atc-product d-flex align-items-center">--}}
{{--                        <div class="tf-sticky-atc-img">--}}
{{--                            @php--}}
{{--                                $thumb = $hasImages && isset($gallery[0]['thumb']) ? $gallery[0]['thumb'] : asset('images/placeholder-product.jpg');--}}
{{--                            @endphp--}}
{{--                            <img class="lazyload" data-src="{{ $thumb }}" alt="{{ $product->name }}" src="{{ $thumb }}">--}}
{{--                        </div>--}}
{{--                        <div class="tf-sticky-atc-title fw-5 d-xl-block d-none">{{ $product->name }}</div>--}}
{{--                    </div>--}}
{{--                    <div class="tf-sticky-atc-infos">--}}
{{--                        <form onsubmit="return false;">--}}
{{--                            <div class="tf-sticky-atc-variant-price text-center">--}}
{{--                                        <span class="fw-6">--}}
{{--                                            @if($onSale)--}}
{{--                                                <span class="price-on-sale">${{ number_format($effective, 2) }}</span>--}}
{{--                                                <span--}}
{{--                                                    class="compare-at-price ms-1">${{ number_format($price, 2) }}</span>--}}
{{--                                            @else--}}
{{--                                                <span class="price">${{ number_format($effective, 2) }}</span>--}}
{{--                                            @endif--}}
{{--                                        </span>--}}
{{--                            </div>--}}
{{--                            <div class="tf-sticky-atc-btns">--}}
{{--                                <div class="tf-product-info-quantity">--}}
{{--                                    <div class="wg-quantity">--}}
{{--                                                <span class="btn-quantity minus-btn"--}}
{{--                                                      onclick="window.__pdQty && window.__pdQty(-1)">-</span>--}}
{{--                                        <input id="stickyQty" type="text" name="number" value="1">--}}
{{--                                        <span class="btn-quantity plus-btn"--}}
{{--                                              onclick="window.__pdQty && window.__pdQty(1)">+</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <a href="javascript:void(0);"--}}
{{--                                   class="tf-btn btn-fill radius-3 justify-content-center fw-6 fs-14 flex-grow-1 animate-hover-btn btn-add-to-cart"--}}
{{--                                   onclick="document.getElementById('atc-root')?.scrollIntoView({behavior:'smooth', block:'center'});">--}}
{{--                                    <span>Add to cart</span>--}}
{{--                                </a>--}}
{{--                            </div>--}}
{{--                        </form>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}

{{--    <!-- Related Products -->--}}
{{--    @if(isset($relatedProducts) && $relatedProducts->isNotEmpty())--}}
{{--        <section class="flat-spacing-1 pt_0">--}}
{{--            <div class="container">--}}
{{--                <div class="flat-title">--}}
{{--                    <span class="title">People Also Bought</span>--}}
{{--                </div>--}}
{{--                <div class="hover-sw-nav hover-sw-2">--}}
{{--                    <div dir="ltr" class="swiper tf-sw-product-sell wrap-sw-over" data-preview="4" data-tablet="3"--}}
{{--                         data-mobile="2" data-space-lg="30" data-space-md="15">--}}
{{--                        <div class="swiper-wrapper">--}}
{{--                            @foreach($relatedProducts as $relatedProduct)--}}
{{--                                <div class="swiper-slide" lazy="true">--}}
{{--                                    <x-product-card :product="$relatedProduct"/>--}}
{{--                                </div>--}}
{{--                            @endforeach--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="nav-sw nav-next-slider nav-next-product box-icon w_46 round"><span--}}
{{--                            class="icon icon-arrow-left"></span></div>--}}
{{--                    <div class="nav-sw nav-prev-slider nav-prev-product box-icon w_46 round"><span--}}
{{--                            class="icon icon-arrow-right"></span></div>--}}
{{--                    <div class="sw-dots style-2 sw-pagination-product justify-content-center"></div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </section>--}}
{{--    @endif--}}
{{--    <!-- /Related Products -->--}}

{{--    @push('scripts')--}}
{{--        <script>--}}
{{--            // Guarded gallery init, uses Swiper already included globally in the template--}}
{{--            document.addEventListener('DOMContentLoaded', function () {--}}
{{--                try {--}}
{{--                    const thumbsEl = document.querySelector('.tf-product-media-thumbs');--}}
{{--                    const mainEl = document.querySelector('.tf-product-media-main');--}}

{{--                    if (thumbsEl && mainEl && !thumbsEl.swiper && !mainEl.swiper) {--}}
{{--                        const thumbsSwiper = new Swiper('.tf-product-media-thumbs', {--}}
{{--                            direction: 'vertical',--}}
{{--                            slidesPerView: 4,--}}
{{--                            spaceBetween: 10,--}}
{{--                            watchSlidesProgress: true,--}}
{{--                            breakpoints: {--}}
{{--                                0: {direction: 'horizontal', slidesPerView: 5},--}}
{{--                                768: {direction: 'vertical'}--}}
{{--                            }--}}
{{--                        });--}}

{{--                        new Swiper('.tf-product-media-main', {--}}
{{--                            spaceBetween: 10,--}}
{{--                            thumbs: {swiper: thumbsSwiper},--}}
{{--                            navigation: {--}}
{{--                                nextEl: '.thumbs-next',--}}
{{--                                prevEl: '.thumbs-prev'--}}
{{--                            }--}}
{{--                        });--}}
{{--                    }--}}

{{--                    // Simple shared qty control for sticky (delegates to visible input)--}}
{{--                    window.__pdQty = function (delta) {--}}
{{--                        const input = document.getElementById('stickyQty');--}}
{{--                        if (!input) return;--}}
{{--                        const val = Math.max(1, parseInt(input.value || '1', 10) + delta);--}}
{{--                        input.value = val;--}}
{{--                    };--}}
{{--                } catch (e) {--}}
{{--                    console.warn('Gallery init error', e);--}}
{{--                }--}}
{{--            });--}}
{{--        </script>--}}
{{--    @endpush--}}


</div>
