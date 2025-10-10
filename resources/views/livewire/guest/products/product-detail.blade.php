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
                                // Build gallery from existing columns only (no new DB fields)
                                $gallery = [];

                                // 1) Prefer the gallery attribute if it has images
                                $rawGallery = $product->gallery ?? [];
                                if (is_array($rawGallery) && count($rawGallery) > 0) {
                                    foreach ($rawGallery as $imagePath) {
                                        $url = \App\Helpers\ImageStorageHelper::url($imagePath);
                                        $gallery[] = [
                                            'thumb'    => $url,
                                            'large'    => $url,
                                            'original' => $url,
                                        ];
                                    }
                                }

                                // 2) Fallback to model accessor (if any images resolved there)
                                if (empty($gallery) && !empty($product->images) && is_array($product->images)) {
                                    $gallery = $product->images;
                                }

                                // 3) Final fallback to placeholder
                                if (empty($gallery)) {
                                    $placeholder = asset('images/placeholder-product.jpg');
                                    $gallery[] = [
                                        'thumb'    => $placeholder,
                                        'large'    => $placeholder,
                                        'original' => $placeholder,
                                    ];
                                }

                                $hasImages = is_array($gallery) && count($gallery) > 0;
                                $hasMultipleImages = $hasImages && count($gallery) > 1;
                            @endphp

                            <div class="thumbs-slider">
                                <!-- Thumbs -->
                                @if($hasMultipleImages)
                                    <div dir="ltr" class="swiper tf-product-media-thumbs other-image-zoom" data-direction="vertical">
                                        <div class="swiper-wrapper stagger-wrap">
                                            @foreach($gallery as $index => $image)
                                                <div class="swiper-slide stagger-item" wire:key="thumb-{{ $index }}">
                                                    <div class="item">
                                                        <img class="lazyload"
                                                             data-src="{{ $image['thumb'] }}"
                                                             src="{{ $image['thumb'] }}"
                                                             alt="{{ $product->name }}">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Main -->
                                <div dir="ltr" class="swiper tf-product-media-main tf-product-zoom-inner"
                                     id="gallery-swiper-started">
                                    <div class="swiper-wrapper">
                                        @foreach($gallery as $index => $image)
                                            <div class="swiper-slide" wire:key="main-{{ $index }}">
                                                <a href="{{ $image['large'] }}" target="_blank" class="item"
                                                   data-pswp-width="1200" data-pswp-height="1200">
                                                    <img class="tf-image-zoom lazyload"
                                                         data-zoom="{{ $image['large'] }}"
                                                         data-src="{{ $image['large'] }}"
                                                         src="{{ $image['large'] }}"
                                                         alt="{{ $product->name }}">
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>

                                    @if($hasMultipleImages)
                                        <div class="swiper-button-next button-style-arrow thumbs-next"></div>
                                        <div class="swiper-button-prev button-style-arrow thumbs-prev"></div>
                                    @endif
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
                                        <div class="price-on-sale">{{ money_format_ugx($effective) }}</div>
                                        <div class="compare-at-price">{{ money_format_ugx($price) }}</div>
                                        <div class="badges-on-sale"><span>{{ $discountPct }}</span>% OFF</div>
                                    @else
                                        <div class="price">{{ money_format_ugx($effective) }}</div>
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
                                        <livewire:client.wish-list-toggle :product="$product"/>
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
                                <div class="tf-product-info-delivery-return">
                                    <div class="row">
                                        <div class="col-xl-4 col-12">
                                            <div class="tf-product-delivery">
                                                <div class="icon">
                                                    <i class="icon-delivery-time"></i>
                                                </div>
                                                <p>Estimate delivery times: <span class="fw-7">Same day</span>
                                                    (CBD), <span class="fw-7">3-6 days</span> (Up country).</p>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-12">
                                            <div class="tf-product-delivery mb-0">
                                                <div class="icon">
                                                    <i class="icon-return-order"></i>
                                                </div>
                                                <p>Return within <span class="fw-7">1 day</span> of purchase.
                                                    Duties &amp; fees are non-refundable.</p>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-12">
                                            <div class="tf-product-delivery mb-0">
                                                <div class="icon">
                                                    <i class="icon-safe"></i>
                                                </div>
                                                <p class="fw-6">Guaranteed Safe <br> Checkout</p>
                                            </div>
                                            <div class="tf-payment">
                                                <div class="payment-method" title="MTN Mobile Money"
                                                     style="width: 48px; height: 32px; background: rgba(255, 255, 255, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); transition: var(--transition); font-size: 10px; font-weight: bold; color: #FFD700;">
                                                    MTN
                                                </div>
                                                <div class="payment-method" title="Airtel Money"
                                                     style="width: 48px; height: 32px; background: rgba(255, 255, 255, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); transition: var(--transition); font-size: 10px; font-weight: bold; color: #FF0000;">
                                                    AIRTEL
                                                </div>
                                            </div>
                                        </div>
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
                                                    <span
                                                        class="price-on-sale">{{ money_format_ugx($effective) }}</span>
                                                    <span
                                                        class="compare-at-price ms-1">{{ money_format_ugx($price) }}</span>
                                                @else
                                                    <span class="price">{{ money_format_ugx($effective) }}</span>
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
                                    <span>Add to cart - UGX</span>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /default -->

    <!-- Product Details Tabs -->
    <section class="flat-spacing-17 pt_0">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="widget-tabs style-has-border">
                        <ul class="widget-menu-tab">
                            <li class="item-title active">
                                <span class="inner">Description</span>
                            </li>
                            <li class="item-title">
                                <span class="inner">Additional Information</span>
                            </li>
                            <li class="item-title">
                                <span class="inner">Review</span>
                            </li>
                            <li class="item-title">
                                <span class="inner">Delivery</span>
                            </li>
                            <li class="item-title">
                                <span class="inner">Return Policies</span>
                            </li>
                        </ul>
                        <div class="widget-content-tab">
                            <!-- Description Tab -->
                            <div class="widget-content-inner active">
                                <div class="">
                                    @if($product->description)
                                        <p class="mb_30">{{ $product->description }}</p>
                                    @else
                                        <p class="mb_30">
                                            This product features high-quality materials and excellent craftsmanship.
                                            It's designed to meet your needs with style and functionality.
                                        </p>
                                    @endif

                                    <div class="tf-product-des-demo">
                                        <div class="right">
                                            <h3 class="fs-16 fw-5">Features</h3>
                                            <ul>
                                                <li>Premium quality materials</li>
                                                <li>Expert craftsmanship</li>
                                                <li>Modern design</li>
                                                <li>Long-lasting durability</li>
                                            </ul>
                                            <h3 class="fs-16 fw-5">Product Care</h3>
                                            <ul class="mb-0">
                                                <li>Follow care instructions</li>
                                                <li>Store in appropriate conditions</li>
                                                <li>Handle with care</li>
                                            </ul>
                                        </div>
                                        <div class="left">
                                            <h3 class="fs-16 fw-5">Care Instructions</h3>
                                            <div class="d-flex gap-10 mb_15 align-items-center">
                                                <div class="icon">
                                                    <i class="icon-machine"></i>
                                                </div>
                                                <span>Follow manufacturer guidelines.</span>
                                            </div>
                                            <div class="d-flex gap-10 mb_15 align-items-center">
                                                <div class="icon">
                                                    <i class="icon-iron"></i>
                                                </div>
                                                <span>Handle with appropriate care.</span>
                                            </div>
                                            <div class="d-flex gap-10 mb_15 align-items-center">
                                                <div class="icon">
                                                    <i class="icon-bleach"></i>
                                                </div>
                                                <span>Avoid harsh chemicals.</span>
                                            </div>
                                            <div class="d-flex gap-10 mb_15 align-items-center">
                                                <div class="icon">
                                                    <i class="icon-dry-clean"></i>
                                                </div>
                                                <span>Professional cleaning when needed.</span>
                                            </div>
                                            <div class="d-flex gap-10 align-items-center">
                                                <div class="icon">
                                                    <i class="icon-tumble-dry"></i>
                                                </div>
                                                <span>Proper storage recommended.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information Tab -->
                            <div class="widget-content-inner">
                                <table class="tf-pr-attrs">
                                    <tbody>
                                    @if($product->brand)
                                        <tr class="tf-attr-pa-brand">
                                            <th class="tf-attr-label">Brand</th>
                                            <td class="tf-attr-value">
                                                <p>{{ $product->brand->name }}</p>
                                            </td>
                                        </tr>
                                    @endif
                                    @if($product->sku)
                                        <tr class="tf-attr-pa-sku">
                                            <th class="tf-attr-label">SKU</th>
                                            <td class="tf-attr-value">
                                                <p>{{ $product->sku }}</p>
                                            </td>
                                        </tr>
                                    @endif
                                    @if($product->weight)
                                        <tr class="tf-attr-pa-weight">
                                            <th class="tf-attr-label">Weight</th>
                                            <td class="tf-attr-value">
                                                <p>{{ $product->weight }} kg</p>
                                            </td>
                                        </tr>
                                    @endif
                                    @if($product->dimensions)
                                        <tr class="tf-attr-pa-dimensions">
                                            <th class="tf-attr-label">Dimensions</th>
                                            <td class="tf-attr-value">
                                                <p>{{ $product->dimensions }}</p>
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>

                            <!-- Reviews Tab -->
                            <div class="widget-content-inner">
                                <div class="tab-reviews write-cancel-review-wrap">
                                    @if($product->reviews_count > 0)
                                        <div class="tab-reviews-heading">
                                            <div class="top">
                                                <div class="text-center">
                                                    <h1 class="number fw-6">{{ number_format($product->average_rating, 1) }}</h1>
                                                    <div class="list-star">
                                                        @for($i=1; $i<=5; $i++)
                                                            <i class="icon icon-star{{ $i <= round($product->average_rating) ? '' : '-o' }}"></i>
                                                        @endfor
                                                    </div>
                                                    <p>({{ $product->reviews_count }} Reviews)</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="reply-comment cancel-review-wrap">
                                            <div
                                                class="d-flex mb_24 gap-20 align-items-center justify-content-between flex-wrap">
                                                <h5 class="">{{ $product->reviews_count }} Reviews</h5>
                                            </div>
                                            <div class="reply-comment-wrap">
                                                <p class="text-center text-muted">Reviews will be displayed here when
                                                    available.</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center py-5">
                                            <h5>No Reviews Yet</h5>
                                            <p class="text-muted">Be the first to review this product!</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Shipping Tab -->
                            <div class="widget-content-inner">
                                <div class="tf-page-privacy-policy">
                                    <div class="title">Shipping Information</div>
                                    <p>We offer reliable shipping options to ensure your order reaches you safely and on
                                        time.</p>

                                    <h4 class="mt-4 mb-2">Delivery Times</h4>
                                    <ul>
                                        <li><strong>Domestic Shipping:</strong> 2-5 business days</li>
                                        <li><strong>International Shipping:</strong> 7-14 business days</li>
                                        <li><strong>Express Shipping:</strong> 1-2 business days (additional charges
                                            apply)
                                        </li>
                                    </ul>

                                    <h4 class="mt-4 mb-2">Shipping Costs</h4>
                                    <p>Shipping costs are calculated based on the weight and destination of your order.
                                        Free shipping is available for orders over a certain amount.</p>

                                    <h4 class="mt-4 mb-2">Order Processing</h4>
                                    <p>Orders are processed within 1-2 business days. You will receive a tracking number
                                        once your order has been shipped.</p>
                                </div>
                            </div>

                            <!-- Return Policies Tab -->
                            <div class="widget-content-inner">
                                <div class="tf-page-privacy-policy">
                                    <div class="title">Return & Exchange Policy</div>
                                    <p>We want you to be completely satisfied with your purchase. If you're not happy
                                        with your order,
                                        we're here to help.</p>

                                    <h4 class="mt-4 mb-2">Return Window</h4>
                                    <p>You have <strong>30 days</strong> from the date of delivery to return your item
                                        for a full refund
                                        or exchange.</p>

                                    <h4 class="mt-4 mb-2">Return Conditions</h4>
                                    <ul>
                                        <li>Items must be in original condition</li>
                                        <li>Items must be unused and with tags attached</li>
                                        <li>Original packaging must be included</li>
                                        <li>Proof of purchase required</li>
                                    </ul>

                                    <h4 class="mt-4 mb-2">How to Return</h4>
                                    <ol>
                                        <li>Contact our customer service team</li>
                                        <li>Receive return authorization and shipping label</li>
                                        <li>Package your item securely</li>
                                        <li>Ship using provided label</li>
                                    </ol>

                                    <h4 class="mt-4 mb-2">Refund Processing</h4>
                                    <p>Once we receive and inspect your return, we'll process your refund within 5-7
                                        business days.
                                        Refunds will be credited to your original payment method.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /Product Details Tabs -->

    <!-- Related Products -->
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


    <!-- Enhanced Related Products -->
    @if(isset($relatedProducts) && $relatedProducts->isNotEmpty())
        @php
            $primaryCategory = $product->categories->first();
            $viewAllUrl = $primaryCategory
                ? route('shop.index', ['category' => $primaryCategory->slug])
                : route('shop.index');
            $relatedCount = $relatedProducts->count();
        @endphp

        <section class="flat-spacing-1 pt_0 tf-related-products">
            <div class="container">
                <!-- Enhanced Header -->
                <div class="d-flex justify-content-between align-items-end mb-4 pb-2 border-bottom">
                    <div>
                        <h3 class="fs-24 fw-6 mb-2">People Also Bought</h3>
                        <p class="text-muted fs-14 mb-0">Discover similar products you might love</p>
                    </div>

                    @if($relatedCount > 3)
                        <a href="{{ $viewAllUrl }}"
                           class="tf-btn btn-outline radius-3 fs-14 d-flex align-items-center">
                            View All {{ $relatedCount }}+ Products
                            <i class="icon icon-arrow-right ms-2 fs-12"></i>
                        </a>
                    @endif
                </div>

                <!-- Carousel - Show 3 items -->
                <div class="hover-sw-nav hover-sw-2 position-relative">
                    <div dir="ltr" class="swiper tf-sw-related-products"
                         data-preview="3" data-tablet="2" data-mobile="1"
                         data-space-lg="24" data-space-md="16">
                        <div class="swiper-wrapper">
                            @foreach($relatedProducts->take(6) as $relatedProduct)
                                <div class="swiper-slide">
                                    <div class="product-card-wrapper">
                                        <x-product-card :product="$relatedProduct"/>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Enhanced Navigation -->
                    @if($relatedProducts->count() > 3)
                        <div class="tf-carousel-controls">
                            <div class="nav-sw nav-prev-related box-icon w_40 round bg-white shadow-sm">
                                <span class="icon icon-arrow-left"></span>
                            </div>
                            <div class="nav-sw nav-next-related box-icon w_40 round bg-white shadow-sm">
                                <span class="icon icon-arrow-right"></span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif


    <!-- /Related Products -->
    @push('scripts')
        <script>
            // Dependencies are now loaded globally in app-layout.blade.php

            async function initGallerySwiper() {
                const thumbsEl = document.querySelector('.tf-product-media-thumbs');
                const mainEl = document.querySelector('.tf-product-media-main');
                if (!mainEl || mainEl.swiper) return;

                let thumbsSwiper = null;
                if (thumbsEl && !thumbsEl.swiper) {
                    thumbsSwiper = new Swiper('.tf-product-media-thumbs', {
                        direction: 'vertical',
                        slidesPerView: 'auto',
                        spaceBetween: 10,
                        freeMode: true,
                        watchSlidesProgress: true,
                        slideActiveClass: 'swiper-slide-active',
                        slideNextClass: 'swiper-slide-next',
                        slidePrevClass: 'swiper-slide-prev',
                        breakpoints: {
                            0:   { direction: 'horizontal', slidesPerView: 'auto', spaceBetween: 8 },
                            768: { direction: 'vertical',   slidesPerView: 'auto', spaceBetween: 10 }
                        }
                    });
                }

                const mainConfig = {
                    spaceBetween: 10,
                    loop: false,
                    autoplay: false,
                    speed: 300
                };

                if (thumbsSwiper) {
                    mainConfig.thumbs = {
                        swiper: thumbsSwiper,
                        slideThumbActiveClass: 'swiper-slide-thumb-active'
                    };
                    mainConfig.navigation = {
                        nextEl: '.thumbs-next',
                        prevEl: '.thumbs-prev'
                    };
                }

                const mainSwiper = new Swiper('.tf-product-media-main', mainConfig);

                // Enable navigation even without thumbs when multiple slides exist
                const slideCount = mainEl.querySelectorAll('.swiper-slide').length;
                if (slideCount > 1 && !mainConfig.navigation) {
                    mainSwiper.params.navigation = {
                        nextEl: '.thumbs-next',
                        prevEl: '.thumbs-prev'
                    };
                    mainSwiper.navigation.init();
                    mainSwiper.navigation.update();
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                try {
                    // Initialize gallery after DOM is ready and dependencies are loaded
                    setTimeout(initGallerySwiper, 200);
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

</div>
