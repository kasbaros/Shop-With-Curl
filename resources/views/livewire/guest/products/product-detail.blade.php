{{--<div class="product-detail-page">--}}

{{--    <!-- breadcrumb -->--}}
{{--    <div class="tf-breadcrumb">--}}
{{--        <div class="container">--}}
{{--            <div class="tf-breadcrumb-wrap d-flex justify-content-between flex-wrap align-items-center">--}}
{{--                <div class="tf-breadcrumb-list">--}}
{{--                    <a href="{{ route('home') }}" class="text">Home</a>--}}
{{--                    <i class="icon icon-arrow-right"></i>--}}
{{--                    @foreach($product->categories as $category)--}}
{{--                        <a href="{{ route('categories.show', $category->slug) }}" class="text">{{ $category->name }}</a>--}}
{{--                        @if(!$loop->last)--}}
{{--                            <i class="icon icon-arrow-right"></i>--}}
{{--                        @endif--}}
{{--                    @endforeach--}}
{{--                    <i class="icon icon-arrow-right"></i>--}}
{{--                    <span class="text">{{ $product->name }}</span>--}}
{{--                </div>--}}
{{--                <div class="tf-breadcrumb-prev-next">--}}
{{--                    @php--}}
{{--                        $prev = $product->previous();--}}
{{--                        $next = $product->next();--}}
{{--                    @endphp--}}
{{--                    @if(!empty($prev))--}}
{{--                        <a href="{{ route('products.show', $prev->slug) }}"--}}
{{--                           class="tf-breadcrumb-prev hover-tooltip center">--}}
{{--                            <i class="icon icon-arrow-left"></i>--}}
{{--                        </a>--}}
{{--                    @endif--}}
{{--                    <a href="{{ route('shop.index') }}" class="tf-breadcrumb-back hover-tooltip center">--}}
{{--                        <i class="icon icon-shop"></i>--}}
{{--                    </a>--}}
{{--                    @if(!empty($next))--}}
{{--                        <a href="{{ route('products.show', $next->slug) }}"--}}
{{--                           class="tf-breadcrumb-next hover-tooltip center">--}}
{{--                            <i class="icon icon-arrow-right"></i>--}}
{{--                        </a>--}}
{{--                    @endif--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <!-- /breadcrumb -->--}}

{{--    <!-- default -->--}}
{{--    <section class="flat-spacing-4 pt_0">--}}
{{--        <div class="tf-main-product">--}}
{{--            <div class="container">--}}
{{--                <div class="row">--}}
{{--                    <!-- Media/Gallery -->--}}

{{--                    <div class="col-md-6">--}}
{{--                        <div class="tf-product-media-wrap sticky-top">--}}
{{--                            @php--}}
{{--                                // Build gallery from product and variant images--}}
{{--                                $gallery = [];--}}
{{--                                $placeholder = asset('images/placeholder-product.jpg');--}}

{{--                                // Add product-level images--}}
{{--                                $rawGallery = $product->gallery ?? [];--}}
{{--                                if (is_array($rawGallery) && count($rawGallery) > 0) {--}}
{{--                                    foreach ($rawGallery as $imagePath) {--}}
{{--                                        $url = \App\Helpers\ImageStorageHelper::url($imagePath);--}}
{{--                                        $gallery[] = [--}}
{{--                                            'thumb' => $url,--}}
{{--                                            'large' => $url,--}}
{{--                                            'original' => $url,--}}
{{--                                            'type' => 'product',--}}
{{--                                        ];--}}
{{--                                    }--}}
{{--                                }--}}

{{--                                // Add variant images--}}
{{--                                if ($product->variants->count() > 0) {--}}
{{--                                    foreach ($product->variants as $variant) {--}}
{{--                                        if (!empty($variant->images) && is_array($variant->images)) {--}}
{{--                                            $gallery[] = [--}}
{{--                                                'thumb' => $variant->primary_image_url,--}}
{{--                                                'large' => $variant->primary_image_url,--}}
{{--                                                'original' => $variant->primary_image_url,--}}
{{--                                                'type' => 'variant',--}}
{{--                                                'variant_id' => $variant->id,--}}
{{--                                                'color' => $variant->color,--}}
{{--                                                'size' => $variant->size,--}}
{{--                                            ];--}}
{{--                                        }--}}
{{--                                    }--}}
{{--                                }--}}

{{--                                // Fallback to placeholder if no images--}}
{{--                                if (empty($gallery)) {--}}
{{--                                    $gallery[] = [--}}
{{--                                        'thumb' => $placeholder,--}}
{{--                                        'large' => $placeholder,--}}
{{--                                        'original' => $placeholder,--}}
{{--                                        'type' => 'placeholder',--}}
{{--                                    ];--}}
{{--                                }--}}

{{--                                $hasImages = is_array($gallery) && count($gallery) > 0;--}}
{{--                                $hasMultipleImages = $hasImages && count($gallery) > 1;--}}
{{--                            @endphp--}}

{{--                            <div class="thumbs-slider">--}}
{{--                                <!-- Thumbs -->--}}
{{--                                @if($hasMultipleImages)--}}
{{--                                    <div dir="ltr" class="swiper tf-product-media-thumbs other-image-zoom"--}}
{{--                                         data-direction="vertical">--}}
{{--                                        <div class="swiper-wrapper stagger-wrap">--}}
{{--                                            @foreach($gallery as $index => $image)--}}
{{--                                                <div class="swiper-slide stagger-item" wire:key="thumb-{{ $index }}"--}}
{{--                                                     data-variant-id="{{ $image['variant_id'] ?? '' }}"--}}
{{--                                                     data-type="{{ $image['type'] }}"--}}
{{--                                                     data-color="{{ $image['color'] ?? '' }}"--}}
{{--                                                     data-size="{{ $image['size'] ?? '' }}">--}}
{{--                                                    <div class="item">--}}
{{--                                                        <img class="lazyload"--}}
{{--                                                             data-src="{{ $image['thumb'] }}"--}}
{{--                                                             src="{{ $image['thumb'] }}"--}}
{{--                                                             alt="{{ $product->name }}"--}}
{{--                                                             onerror="this.onerror=null; this.src='{{ $placeholder }}'">--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            @endforeach--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                @endif--}}

{{--                                <!-- Main -->--}}
{{--                                <div dir="ltr" class="swiper tf-product-media-main tf-product-zoom-inner"--}}
{{--                                     id="gallery-swiper-started">--}}
{{--                                    <div class="swiper-wrapper">--}}
{{--                                        @foreach($gallery as $index => $image)--}}
{{--                                            <div class="swiper-slide" wire:key="main-{{ $index }}"--}}
{{--                                                 data-variant-id="{{ $image['variant_id'] ?? '' }}"--}}
{{--                                                 data-type="{{ $image['type'] }}"--}}
{{--                                                 data-color="{{ $image['color'] ?? '' }}"--}}
{{--                                                 data-size="{{ $image['size'] ?? '' }}">--}}
{{--                                                <a href="{{ $image['large'] }}" target="_blank" class="item"--}}
{{--                                                   data-pswp-width="1200" data-pswp-height="1200">--}}
{{--                                                    <img class="tf-image-zoom lazyload"--}}
{{--                                                         data-zoom="{{ $image['large'] }}"--}}
{{--                                                         data-src="{{ $image['large'] }}"--}}
{{--                                                         src="{{ $image['large'] }}"--}}
{{--                                                         alt="{{ $product->name }}"--}}
{{--                                                         onerror="this.onerror=null; this.src='{{ $placeholder }}'">--}}
{{--                                                </a>--}}
{{--                                            </div>--}}
{{--                                        @endforeach--}}
{{--                                    </div>--}}

{{--                                    @if($hasMultipleImages)--}}
{{--                                        <div class="swiper-button-next button-style-arrow thumbs-next"></div>--}}
{{--                                        <div class="swiper-button-prev button-style-arrow thumbs-prev"></div>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <!-- Info -->--}}

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

{{--                                <!-- Badges / Status -->--}}
{{--                                <div class="tf-product-info-badges">--}}
{{--                                    @if(!empty($product->is_featured))--}}
{{--                                        <div class="badges">Best seller</div>--}}
{{--                                    @endif--}}
{{--                                    @if($product->variants->count() > 0)--}}
{{--                                        <div class="product-status-content">--}}
{{--                                            <i class="icon-lightning"></i>--}}
{{--                                            <p class="fw-6">Select a variant to check stock.</p>--}}
{{--                                        </div>--}}
{{--                                    @elseif($product->stock_quantity <= 5 && $product->stock_quantity > 0)--}}
{{--                                        <div class="product-status-content">--}}
{{--                                            <i class="icon-lightning"></i>--}}
{{--                                            <p class="fw-6">Hurry! Only {{ $product->stock_quantity }} left in--}}
{{--                                                stock.</p>--}}
{{--                                        </div>--}}
{{--                                    @endif--}}
{{--                                </div>--}}

{{--                                <!-- Variant Selector -->--}}
{{--                                @if($product->variants->count() > 0)--}}
{{--                                    <div class="tf-product-info-variants mt-4">--}}
{{--                                        <div class="mb-3">--}}
{{--                                            <label class="form-label fw-6">Color</label>--}}
{{--                                            <div class="variant-options d-flex gap-2 flex-wrap">--}}
{{--                                                @foreach($product->variants->groupBy('color') as $color => $variants)--}}
{{--                                                    <div class="form-check form-check-inline">--}}
{{--                                                        <input class="form-check-input variant-color-radio" type="radio"--}}
{{--                                                               name="variant_color" id="color_{{ Str::slug($color) }}"--}}
{{--                                                               value="{{ $color }}"--}}
{{--                                                               data-variants="{{ json_encode($variants->pluck('id')->toArray()) }}"--}}
{{--                                                            {{ $loop->first ? 'checked' : '' }}>--}}
{{--                                                        <label class="form-check-label badge bg-info"--}}
{{--                                                               for="color_{{ Str::slug($color) }}">--}}
{{--                                                            {{ $color }}--}}
{{--                                                        </label>--}}
{{--                                                    </div>--}}
{{--                                                @endforeach--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="mb-3">--}}
{{--                                            <label class="form-label fw-6">Size</label>--}}
{{--                                            <select class="form-select variant-size-select" name="variant_id">--}}
{{--                                                @foreach($product->variants->where('color', $product->variants->groupBy('color')->keys()->first()) as $variant)--}}
{{--                                                    <option value="{{ $variant->id }}"--}}
{{--                                                            data-color="{{ $variant->color }}"--}}
{{--                                                            data-size="{{ $variant->size }}"--}}
{{--                                                            data-price="{{ $variant->effective_price }}"--}}
{{--                                                            data-stock="{{ $variant->stock_quantity }}"--}}
{{--                                                            data-image="{{ $variant->primary_image_url }}">--}}
{{--                                                        {{ $variant->size }}--}}
{{--                                                    </option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                            <div class="variant-status mt-2">--}}
{{--                                                <span class="stock-status text-muted"></span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="variant-price mt-2">--}}
{{--                                            <span class="fw-6 price"></span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                @else--}}
{{--                                    <!-- Price -->--}}
{{--                                    <div class="tf-product-info-price">--}}
{{--                                        @php--}}
{{--                                            $price = (float) ($product->price ?? 0);--}}
{{--                                            $sale = (float) ($product->sale_price ?? 0);--}}
{{--                                            $onSale = $sale > 0 && $sale < $price;--}}
{{--                                            $effective = $onSale ? $sale : $price;--}}
{{--                                            $discountPct = $onSale ? round((($price - $sale) / max($price, 1)) * 100) : 0;--}}
{{--                                        @endphp--}}
{{--                                        @if($onSale)--}}
{{--                                            <div class="price-on-sale">{{ money_format_ugx($effective) }}</div>--}}
{{--                                            <div class="compare-at-price">{{ money_format_ugx($price) }}</div>--}}
{{--                                            <div class="badges-on-sale"><span>{{ $discountPct }}</span>% OFF</div>--}}
{{--                                        @else--}}
{{--                                            <div class="price">{{ money_format_ugx($effective) }}</div>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                @endif--}}

{{--                                <!-- Rating summary -->--}}
{{--                                @if($product->reviews_count > 0)--}}
{{--                                    <div class="d-flex align-items-center gap-10">--}}
{{--                                        <div class="list-star">--}}
{{--                                            @for($i = 1; $i <= 5; $i++)--}}
{{--                                                <i class="icon icon-star{{ $i <= round($product->average_rating) ? '' : '-o' }}"></i>--}}
{{--                                            @endfor--}}
{{--                                        </div>--}}
{{--                                        <span class="text_black-2 fs-14">--}}
{{--                        {{ number_format($product->average_rating, 1) }} ({{ $product->reviews_count }})--}}
{{--                    </span>--}}
{{--                                    </div>--}}
{{--                                @endif--}}

{{--                                <!-- Short Description -->--}}
{{--                                @if($product->short_description)--}}
{{--                                    <div class="tf-product-description">--}}
{{--                                        <p>{{ $product->short_description }}</p>--}}
{{--                                    </div>--}}
{{--                                @endif--}}

{{--                                <!-- Add to Cart -->--}}
{{--                                <div id="atc-root" class="mt_16">--}}
{{--                                    <livewire:client.cart.cart :product="$product"--}}
{{--                                                               :variantId="$product->variants->count() > 0 ? $product->variants->first()->id : null"/>--}}
{{--                                </div>--}}

{{--                                <!-- Wishlist / Compare -->--}}
{{--                                <div class="d-flex align-items-center gap-10 mt_12">--}}
{{--                                    @auth--}}
{{--                                        <livewire:client.wish-list-toggle :product="$product"/>--}}
{{--                                    @else--}}
{{--                                        <a href="{{ route('login') }}"--}}
{{--                                           class="tf-product-btn-wishlist hover-tooltip box-icon bg_white wishlist btn-icon-action">--}}
{{--                                            <span class="icon icon-heart"></span>--}}
{{--                                            <span class="tooltip">Add to Wishlist</span>--}}
{{--                                        </a>--}}
{{--                                    @endauth--}}
{{--                                    <a href="#compare" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft"--}}
{{--                                       class="tf-product-btn-wishlist hover-tooltip box-icon bg_white compare btn-icon-action"--}}
{{--                                       onclick="if(window.Livewire?.dispatch){window.Livewire.dispatch('compare:toggle',{id: {{ (int)$product->id }} });}">--}}
{{--                                        <span class="icon icon-compare"></span>--}}
{{--                                        <span class="tooltip">Add to Compare</span>--}}
{{--                                        <span class="icon icon-check"></span>--}}
{{--                                    </a>--}}
{{--                                </div>--}}

{{--                                <!-- Delivery / Return -->--}}
{{--                                <div class="tf-product-info-delivery-return">--}}
{{--                                    <div class="row">--}}
{{--                                        <div class="col-xl-4 col-12">--}}
{{--                                            <div class="tf-product-delivery">--}}
{{--                                                <div class="icon">--}}
{{--                                                    <i class="icon-delivery-time"></i>--}}
{{--                                                </div>--}}
{{--                                                <p>Estimate delivery times: <span class="fw-7">Same day</span>--}}
{{--                                                    (CBD), <span class="fw-7">3-6 days</span> (Up country).</p>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-xl-4 col-12">--}}
{{--                                            <div class="tf-product-delivery mb-0">--}}
{{--                                                <div class="icon">--}}
{{--                                                    <i class="icon-return-order"></i>--}}
{{--                                                </div>--}}
{{--                                                <p>Return within <span class="fw-7">1 day</span> of purchase.--}}
{{--                                                    Duties &amp; fees are non-refundable.</p>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-xl-4 col-12">--}}
{{--                                            <div class="tf-product-delivery mb-0">--}}
{{--                                                <div class="icon">--}}
{{--                                                    <i class="icon-safe"></i>--}}
{{--                                                </div>--}}
{{--                                                <p class="fw-6">Guaranteed Safe <br> Checkout</p>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <!-- Sticky Add to Cart (mirrors template) -->--}}
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
{{--                                            <span class="fw-6">--}}
{{--                                                @php--}}
{{--                                                    $price = (float) ($relatedProduct->price ?? 0);--}}
{{--                                                    $sale  = (float) ($relatedProduct->sale_price ?? 0);--}}
{{--                                                    $onSale = $sale > 0 && $sale < $price;--}}
{{--                                                    $effective = $onSale ? $sale : $price;--}}
{{--                                                    $discountPct = $onSale ? round((($price - $sale) / max($price, 1)) * 100) : 0;--}}
{{--                                                @endphp--}}
{{--                                                @if($onSale)--}}
{{--                                                    <span--}}
{{--                                                        class="price-on-sale">{{ money_format_ugx($effective) }}</span>--}}
{{--                                                    <span--}}
{{--                                                        class="compare-at-price ms-1">{{ money_format_ugx($price) }}</span>--}}
{{--                                                @else--}}
{{--                                                    <span class="price">{{ money_format_ugx($effective) }}</span>--}}
{{--                                                @endif--}}
{{--                                            </span>--}}
{{--                            </div>--}}
{{--                            <div class="tf-sticky-atc-btns">--}}
{{--                                <div class="tf-product-info-quantity">--}}
{{--                                    <div class="wg-quantity">--}}
{{--                                                    <span class="btn-quantity minus-btn"--}}
{{--                                                          onclick="window.__pdQty && window.__pdQty(-1)">-</span>--}}
{{--                                        <input id="stickyQty" type="text" name="number" value="1">--}}
{{--                                        <span class="btn-quantity plus-btn"--}}
{{--                                              onclick="window.__pdQty && window.__pdQty(1)">+</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <a href="javascript:void(0);"--}}
{{--                                   class="tf-btn btn-fill radius-3 justify-content-center fw-6 fs-14 flex-grow-1 animate-hover-btn btn-add-to-cart"--}}
{{--                                   onclick="document.getElementById('atc-root')?.scrollIntoView({behavior:'smooth', block:'center'});">--}}
{{--                                    <span>Add to cart - UGX</span>--}}
{{--                                </a>--}}
{{--                            </div>--}}
{{--                        </form>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}
{{--    <!-- /default -->--}}

{{--    <!-- Product Details Tabs -->--}}

{{--    <section class="flat-spacing-17 pt_0">--}}
{{--        <div class="container">--}}
{{--            <div class="row">--}}
{{--                <div class="col-12">--}}
{{--                    <div class="widget-tabs style-has-border">--}}
{{--                        <ul class="widget-menu-tab">--}}
{{--                            <li class="item-title active">--}}
{{--                                <span class="inner">Description</span>--}}
{{--                            </li>--}}
{{--                            <li class="item-title">--}}
{{--                                <span class="inner">Additional Information</span>--}}
{{--                            </li>--}}
{{--                            <li class="item-title">--}}
{{--                                <span class="inner">Review</span>--}}
{{--                            </li>--}}
{{--                            <li class="item-title">--}}
{{--                                <span class="inner">Delivery</span>--}}
{{--                            </li>--}}
{{--                            <li class="item-title">--}}
{{--                                <span class="inner">Return Policies</span>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                        <div class="widget-content-tab">--}}
{{--                            <!-- Description Tab -->--}}
{{--                            <div class="widget-content-inner active">--}}
{{--                                <div class="">--}}
{{--                                    @if($product->description)--}}
{{--                                        <p class="mb_30">{{ $product->description }}</p>--}}
{{--                                    @else--}}
{{--                                        <p class="mb_30">--}}
{{--                                            This product features high-quality materials and excellent craftsmanship.--}}
{{--                                            It's designed to meet your needs with style and functionality.--}}
{{--                                        </p>--}}
{{--                                    @endif--}}

{{--                                    <div class="tf-product-des-demo">--}}
{{--                                        <div class="right">--}}
{{--                                            <h3 class="fs-16 fw-5">Features</h3>--}}
{{--                                            <ul>--}}
{{--                                                <li>Premium quality materials</li>--}}
{{--                                                <li>Expert craftsmanship</li>--}}
{{--                                                <li>Modern design</li>--}}
{{--                                                <li>Long-lasting durability</li>--}}
{{--                                            </ul>--}}
{{--                                            <h3 class="fs-16 fw-5">Product Care</h3>--}}
{{--                                            <ul class="mb-0">--}}
{{--                                                <li>Follow care instructions</li>--}}
{{--                                                <li>Store in appropriate conditions</li>--}}
{{--                                                <li>Handle with care</li>--}}
{{--                                            </ul>--}}
{{--                                        </div>--}}
{{--                                        <div class="left">--}}
{{--                                            <h3 class="fs-16 fw-5">Care Instructions</h3>--}}
{{--                                            <div class="d-flex gap-10 mb_15 align-items-center">--}}
{{--                                                <div class="icon">--}}
{{--                                                    <i class="icon-machine"></i>--}}
{{--                                                </div>--}}
{{--                                                <span>Follow manufacturer guidelines.</span>--}}
{{--                                            </div>--}}
{{--                                            <div class="d-flex gap-10 mb_15 align-items-center">--}}
{{--                                                <div class="icon">--}}
{{--                                                    <i class="icon-iron"></i>--}}
{{--                                                </div>--}}
{{--                                                <span>Handle with appropriate care.</span>--}}
{{--                                            </div>--}}
{{--                                            <div class="d-flex gap-10 mb_15 align-items-center">--}}
{{--                                                <div class="icon">--}}
{{--                                                    <i class="icon-bleach"></i>--}}
{{--                                                </div>--}}
{{--                                                <span>Avoid harsh chemicals.</span>--}}
{{--                                            </div>--}}
{{--                                            <div class="d-flex gap-10 mb_15 align-items-center">--}}
{{--                                                <div class="icon">--}}
{{--                                                    <i class="icon-dry-clean"></i>--}}
{{--                                                </div>--}}
{{--                                                <span>Professional cleaning when needed.</span>--}}
{{--                                            </div>--}}
{{--                                            <div class="d-flex gap-10 align-items-center">--}}
{{--                                                <div class="icon">--}}
{{--                                                    <i class="icon-tumble-dry"></i>--}}
{{--                                                </div>--}}
{{--                                                <span>Proper storage recommended.</span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <!-- Additional Information Tab -->--}}
{{--                            <div class="widget-content-inner">--}}
{{--                                <div class="table-responsive">--}}
{{--                                    <table class="tf-pr-attrs table table-striped table-hover">--}}
{{--                                        <tbody>--}}
{{--                                        @if($product->brand)--}}
{{--                                            <tr class="tf-attr-pa-brand">--}}
{{--                                                <th class="tf-attr-label w-25 fw-semibold">Brand</th>--}}
{{--                                                <td class="tf-attr-value">--}}
{{--                                                    <p class="mb-0">{{ $product->brand->name }}</p>--}}
{{--                                                </td>--}}
{{--                                            </tr>--}}
{{--                                        @endif--}}
{{--                                        @if($product->sku)--}}
{{--                                            <tr class="tf-attr-pa-sku">--}}
{{--                                                <th class="tf-attr-label w-25 fw-semibold">SKU</th>--}}
{{--                                                <td class="tf-attr-value">--}}
{{--                                                    <p class="mb-0 font-monospace">{{ $product->sku }}</p>--}}
{{--                                                </td>--}}
{{--                                            </tr>--}}
{{--                                        @endif--}}
{{--                                        @if($product->weight)--}}
{{--                                            <tr class="tf-attr-pa-weight">--}}
{{--                                                <th class="tf-attr-label w-25 fw-semibold">Weight</th>--}}
{{--                                                <td class="tf-attr-value">--}}
{{--                                                    <p class="mb-0">{{ $product->weight }} kg</p>--}}
{{--                                                </td>--}}
{{--                                            </tr>--}}
{{--                                        @endif--}}
{{--                                        @if($product->dimensions)--}}
{{--                                            <tr class="tf-attr-pa-dimensions">--}}
{{--                                                <th class="tf-attr-label w-25 fw-semibold">Dimensions</th>--}}
{{--                                                <td class="tf-attr-value">--}}
{{--                                                    <p class="mb-0">{{ $product->dimensions }}</p>--}}
{{--                                                </td>--}}
{{--                                            </tr>--}}
{{--                                        @endif--}}
{{--                                        </tbody>--}}
{{--                                    </table>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <!-- Reviews Tab -->--}}
{{--                            <div class="widget-content-inner">--}}
{{--                                <div class="tab-reviews write-cancel-review-wrap">--}}
{{--                                    @if($product->reviews_count > 0)--}}
{{--                                        <div class="tab-reviews-heading mb-4">--}}
{{--                                            <div class="top">--}}
{{--                                                <div class="text-center p-4 bg-light rounded">--}}
{{--                                                    <h1 class="number fw-6 display-3 mb-3">{{ number_format($product->average_rating, 1) }}</h1>--}}
{{--                                                    <div class="list-star mb-3">--}}
{{--                                                        @for($i=1; $i<=5; $i++)--}}
{{--                                                            <i class="icon icon-star{{ $i <= round($product->average_rating) ? '' : '-o' }} text-warning fs-4"></i>--}}
{{--                                                        @endfor--}}
{{--                                                    </div>--}}
{{--                                                    <p class="text-muted mb-0">({{ $product->reviews_count }}--}}
{{--                                                        Reviews)</p>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="reply-comment cancel-review-wrap">--}}
{{--                                            <div--}}
{{--                                                class="d-flex mb_24 gap-20 align-items-center justify-content-between flex-wrap mb-4 pb-3 border-bottom">--}}
{{--                                                <h5 class="mb-0">{{ $product->reviews_count }} Reviews</h5>--}}
{{--                                            </div>--}}
{{--                                            <div class="reply-comment-wrap">--}}
{{--                                                <div class="alert alert-info text-center" role="alert">--}}
{{--                                                    <i class="bi bi-info-circle me-2"></i>--}}
{{--                                                    Reviews will be displayed here when available.--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    @else--}}
{{--                                        <div class="text-center py-5">--}}
{{--                                            <div class="mb-4">--}}
{{--                                                <i class="bi bi-chat-left-text display-1 text-muted"></i>--}}
{{--                                            </div>--}}
{{--                                            <h5 class="mb-3">No Reviews Yet</h5>--}}
{{--                                            <p class="text-muted">Be the first to review this product!</p>--}}
{{--                                        </div>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <!-- Shipping Tab -->--}}
{{--                            <div class="widget-content-inner">--}}
{{--                                <div class="tf-page-privacy-policy">--}}
{{--                                    <div class="title mb-4 pb-3 border-bottom">--}}
{{--                                        <h5 class="mb-0">Shipping Information</h5>--}}
{{--                                    </div>--}}
{{--                                    <p class="lead mb-4">We offer reliable shipping options to ensure your order reaches--}}
{{--                                        you safely and on time.</p>--}}

{{--                                    <div class="row g-4">--}}
{{--                                        <div class="col-md-4">--}}
{{--                                            <div class="card h-100 border-0 shadow-sm">--}}
{{--                                                <div class="card-body">--}}
{{--                                                    <h4 class="mt-4 mb-2 h5 card-title"><i--}}
{{--                                                            class="icon-delivery-time me-2"></i>Delivery Times</h4>--}}
{{--                                                    <ul class="list-unstyled mb-0">--}}
{{--                                                        <li class="mb-2"><strong>Within CBD:</strong> Same day of--}}
{{--                                                            purchase--}}
{{--                                                        </li>--}}
{{--                                                        <li class="mb-2"><strong>Upcountry:</strong> 2-5 business days--}}
{{--                                                        </li>--}}
{{--                                                    </ul>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

{{--                                        <div class="col-md-4">--}}
{{--                                            <div class="card h-100 border-0 shadow-sm">--}}
{{--                                                <div class="card-body">--}}
{{--                                                    <h4 class="mt-4 mb-2 h5 card-title"><i--}}
{{--                                                            class="icon-fast-shipping me-2"></i>Shipping Costs</h4>--}}
{{--                                                    <p class="mb-0">Shipping costs are calculated based on the weight--}}
{{--                                                        and destination of your order. Free shipping is available for--}}
{{--                                                        orders over a certain amount.</p>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

{{--                                        <div class="col-md-4">--}}
{{--                                            <div class="card h-100 border-0 shadow-sm">--}}
{{--                                                <div class="card-body">--}}
{{--                                                    <h4 class="mt-4 mb-2 h5 card-title"><i--}}
{{--                                                            class="icon-car-order me-2"></i>Order Processing</h4>--}}
{{--                                                    <p class="mb-0">Orders are processed within 1-2 business days. You--}}
{{--                                                        will receive a tracking number once your order has been--}}
{{--                                                        shipped.</p>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <!-- Return Policies Tab -->--}}
{{--                            <div class="widget-content-inner">--}}
{{--                                <div class="tf-page-privacy-policy">--}}
{{--                                    <div class="title mb-4 pb-3 border-bottom">--}}
{{--                                        <h5 class="mb-0">Return & Exchange Policy</h5>--}}
{{--                                    </div>--}}
{{--                                    <p class="lead mb-4">We want you to be completely satisfied with your purchase. If--}}
{{--                                        you're not happy with your order, we're here to help.</p>--}}

{{--                                    <div class="row g-4">--}}
{{--                                        <div class="col-lg-6">--}}
{{--                                            <div class="card border-0 shadow-sm h-100">--}}
{{--                                                <div class="card-body">--}}
{{--                                                    <h5 class="mt-4 mb-2 h5 card-title"><i--}}
{{--                                                            class="icon-calendar me-2"></i>Return Window</h5>--}}
{{--                                                    <p class="mb-4">You have <strong class="text-primary">1 day</strong>--}}
{{--                                                        from the date of delivery to return your item for a full refund--}}
{{--                                                        or exchange.</p>--}}

{{--                                                    <h5 class="mt-4 mb-2 h5 card-title"><i--}}
{{--                                                            class="icon-return-order me-2"></i>Return Conditions</h5>--}}
{{--                                                    <ul class="list-unstyled mb-0">--}}
{{--                                                        <li class="mb-2"><i class="icon-check text-success me-2"></i>Items--}}
{{--                                                            must be in original condition--}}
{{--                                                        </li>--}}
{{--                                                        <li class="mb-2"><i class="icon-check text-success me-2"></i>Items--}}
{{--                                                            must be unused and with tags attached--}}
{{--                                                        </li>--}}
{{--                                                        <li class="mb-2"><i class="icon-check text-success me-2"></i>Original--}}
{{--                                                            packaging must be included--}}
{{--                                                        </li>--}}
{{--                                                        <li class="mb-0"><i class="icon-check text-success me-2"></i>Proof--}}
{{--                                                            of purchase required--}}
{{--                                                        </li>--}}
{{--                                                    </ul>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

{{--                                        <div class="col-lg-6">--}}
{{--                                            <div class="card border-0 shadow-sm h-100">--}}
{{--                                                <div class="card-body">--}}
{{--                                                    <h4 class="mt-4 mb-2 h5 card-title"><i class="icon-return me-2"></i>How--}}
{{--                                                        to Return</h4>--}}
{{--                                                    <ol class="mb-4">--}}
{{--                                                        <li class="mb-2"><i class="icon-check text-success me-2"></i>Contact--}}
{{--                                                            our customer service team--}}
{{--                                                        </li>--}}
{{--                                                        <li class="mb-2"><i class="icon-check text-success me-2"></i>Receive--}}
{{--                                                            return authorization and shipping label--}}
{{--                                                        </li>--}}
{{--                                                        <li class="mb-2"><i class="icon-check text-success me-2"></i>Package--}}
{{--                                                            your item securely--}}
{{--                                                        </li>--}}
{{--                                                        <li class="mb-0"><i class="icon-check text-success me-2"></i>Ship--}}
{{--                                                            using provided label--}}
{{--                                                        </li>--}}
{{--                                                    </ol>--}}

{{--                                                    <h4 class="mt-4 mb-2 h5 card-title"><i class="icon-card me-2"></i>Refund--}}
{{--                                                        Processing</h4>--}}
{{--                                                    <p class="mb-0">Once we receive and inspect your return, we'll--}}
{{--                                                        process your refund within 5-7 business days. Refunds will be--}}
{{--                                                        credited to your original payment method.</p>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}

{{--    <!-- /Product Details Tabs -->--}}

{{--    <!-- Related Products -->--}}

{{--    @if(isset($relatedProducts) && $relatedProducts->isNotEmpty())--}}
{{--        <section class="flat-spacing-1 pt_0">--}}
{{--            <div class="container">--}}
{{--                <div class="flat-title">--}}
{{--                    <span class="title">People Also Bought</span>--}}
{{--                </div>--}}
{{--                <div class="hover-sw-nav hover-sw-3">--}}
{{--                    <div dir="ltr" class="swiper tf-sw-product-sell rounded-0 border-0 wrap-sw-over"--}}
{{--                         data-loop="false" data-play="false" data-preview="3" data-tablet="2" data-mobile="1"--}}
{{--                         data-space-lg="30" data-space-md="15">--}}
{{--                        <div class="swiper-wrapper" aria-live="polite">--}}
{{--                            @foreach($relatedProducts->take(6) as $relatedProduct)--}}
{{--                                <div class="swiper-slide" lazy="true" role="group"--}}
{{--                                     aria-label="{{ $loop->iteration }} / {{ $relatedProducts->take(6)->count() }}">--}}
{{--                                    <div class="card-product">--}}
{{--                                        <div class="card-product-wrapper">--}}
{{--                                            <a href="{{ route('products.show', $relatedProduct->slug) }}"--}}
{{--                                               class="product-img">--}}
{{--                                                @php--}}
{{--                                                    $mainImage = $relatedProduct->images[0]['large'] ?? asset('images/placeholder-product.jpg');--}}
{{--                                                    $hoverImage = $relatedProduct->images[1]['large'] ?? $mainImage;--}}
{{--                                                @endphp--}}
{{--                                                <img class="img-product lazyload" data-src="{{ $mainImage }}"--}}
{{--                                                     src="{{ $mainImage }}" alt="{{ $relatedProduct->name }}">--}}
{{--                                                <img class="img-hover lazyload" data-src="{{ $hoverImage }}"--}}
{{--                                                     src="{{ $hoverImage }}" alt="{{ $relatedProduct->name }}">--}}
{{--                                            </a>--}}
{{--                                            <div class="list-product-btn">--}}
{{--                                                <a href="#quick_add" data-bs-toggle="modal"--}}
{{--                                                   class="box-icon bg_white quick-add tf-btn-loading">--}}
{{--                                                    <span class="icon icon-bag"></span>--}}
{{--                                                    <span class="tooltip">Quick Add</span>--}}
{{--                                                </a>--}}
{{--                                                @auth--}}
{{--                                                    <livewire:client.wish-list-toggle :product="$relatedProduct"--}}
{{--                                                                                      :cardView="true"/>--}}
{{--                                                @else--}}
{{--                                                    <a href="{{ route('login') }}"--}}
{{--                                                       class="box-icon bg_white wishlist btn-icon-action">--}}
{{--                                                        <span class="icon icon-heart"></span>--}}
{{--                                                        <span class="tooltip">Add to Wishlist</span>--}}
{{--                                                        <span class="icon icon-delete"></span>--}}
{{--                                                    </a>--}}
{{--                                                @endauth--}}
{{--                                                <a href="#compare" data-bs-toggle="offcanvas"--}}
{{--                                                   aria-controls="offcanvasLeft"--}}
{{--                                                   class="box-icon bg_white compare btn-icon-action"--}}
{{--                                                   onclick="if(window.Livewire?.dispatch){window.Livewire.dispatch('compare:toggle',{id: {{ (int)$relatedProduct->id }} });}">--}}
{{--                                                    <span class="icon icon-compare"></span>--}}
{{--                                                    <span class="tooltip">Add to Compare</span>--}}
{{--                                                    <span class="icon icon-check"></span>--}}
{{--                                                </a>--}}
{{--                                                <a href="#quick_view" data-bs-toggle="modal"--}}
{{--                                                   class="box-icon bg_white quickview tf-btn-loading">--}}
{{--                                                    <span class="icon icon-view"></span>--}}
{{--                                                    <span class="tooltip">Quick View</span>--}}
{{--                                                </a>--}}
{{--                                            </div>--}}

{{--                                            <!-- Size List (if product has variants) -->--}}
{{--                                            @if($relatedProduct->variants && $relatedProduct->variants->isNotEmpty())--}}
{{--                                                <div class="size-list">--}}
{{--                                                    @foreach($relatedProduct->variants->take(4) as $variant)--}}
{{--                                                        <span>{{ $variant->size ?? $variant->name }}</span>--}}
{{--                                                    @endforeach--}}
{{--                                                </div>--}}
{{--                                            @endif--}}

{{--                                            <!-- Sale Badge -->--}}
{{--                                            @php--}}
{{--                                                $price = (float) ($relatedProduct->price ?? 0);--}}
{{--                                                $sale  = (float) ($relatedProduct->sale_price ?? 0);--}}
{{--                                                $onSale = $sale > 0 && $sale < $price;--}}
{{--                                                $discountPct = $onSale ? round((($price - $sale) / max($price, 1)) * 100) : 0;--}}
{{--                                            @endphp--}}

{{--                                            @if($onSale && $discountPct > 0)--}}
{{--                                                <div class="on-sale-wrap">--}}
{{--                                                    <div class="on-sale-item">-{{ $discountPct }}%</div>--}}
{{--                                                </div>--}}
{{--                                            @endif--}}

{{--                                            <!-- Countdown (if product has sale end date) -->--}}
{{--                                            @if($relatedProduct->sale_price && $relatedProduct->sale_end_date)--}}
{{--                                                <div class="countdown-box">--}}
{{--                                                    <div class="js-countdown"--}}
{{--                                                         data-timer="{{ \Carbon\Carbon::parse($relatedProduct->sale_end_date)->timestamp * 1000 }}"--}}
{{--                                                         data-labels="d :,h :,m :,s">--}}
{{--                                                        <!-- Countdown will be initialized by JavaScript -->--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                        <div class="card-product-info">--}}
{{--                                            <a href="{{ route('products.show', $relatedProduct->slug) }}"--}}
{{--                                               class="title link">{{ $relatedProduct->name }}</a>--}}

{{--                                            <!-- Price -->--}}
{{--                                            @if($onSale)--}}
{{--                                                <span class="price">{{ money_format_ugx($sale) }}</span>--}}
{{--                                                @if($price > $sale)--}}
{{--                                                    <span class="compare-price"--}}
{{--                                                          style="text-decoration: line-through; color: #999; font-size: 0.9em; margin-left: 5px;">--}}
{{--                                                {{ money_format_ugx($price) }}--}}
{{--                                            </span>--}}
{{--                                                @endif--}}
{{--                                            @else--}}
{{--                                                <span class="price">{{ money_format_ugx($price) }}</span>--}}
{{--                                            @endif--}}

{{--                                            <!-- Color Swatches -->--}}
{{--                                            @if($relatedProduct->colors && $relatedProduct->colors->isNotEmpty())--}}
{{--                                                <ul class="list-color-product">--}}
{{--                                                    @foreach($relatedProduct->colors as $index => $color)--}}
{{--                                                        <li class="list-color-item color-swatch {{ $index === 0 ? 'active' : '' }}">--}}
{{--                                                            <span class="tooltip">{{ $color->name }}</span>--}}
{{--                                                            <span class="swatch-value"--}}
{{--                                                                  style="background-color: {{ $color->hex_code }}"></span>--}}
{{--                                                            <img class="lazyload"--}}
{{--                                                                 data-src="{{ $color->image_url ?? $mainImage }}"--}}
{{--                                                                 src="{{ $color->image_url ?? $mainImage }}"--}}
{{--                                                                 alt="{{ $relatedProduct->name }}">--}}
{{--                                                        </li>--}}
{{--                                                    @endforeach--}}
{{--                                                </ul>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            @endforeach--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <!-- Navigation Arrows -->--}}
{{--                    <div class="nav-sw nav-next-slider nav-next-product box-icon w_46 round">--}}
{{--                        <span class="icon icon-arrow-left"></span>--}}
{{--                    </div>--}}
{{--                    <div class="nav-sw nav-prev-slider nav-prev-product box-icon w_46 round">--}}
{{--                        <span class="icon icon-arrow-right"></span>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <!-- View All Button -->--}}
{{--                @if($relatedProducts->count() > 3)--}}
{{--                    <div class="text-center mt-4">--}}
{{--                        @php--}}
{{--                            $primaryCategory = $product->categories->first();--}}
{{--                            $viewAllUrl = $primaryCategory--}}
{{--                                ? route('shop.index', ['category' => $primaryCategory->slug])--}}
{{--                                : route('shop.index');--}}
{{--                        @endphp--}}
{{--                        <a href="{{ $viewAllUrl }}" class="tf-btn btn-outline radius-3 fs-14">--}}
{{--                            View All Related Products--}}
{{--                            <i class="icon icon-arrow-right ms-2"></i>--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                @endif--}}
{{--            </div>--}}
{{--        </section>--}}
{{--    @endif--}}


{{--    <!-- /Related Products -->--}}
{{--    @push('scripts')--}}
{{--        <script>--}}
{{--            // Dependencies are now loaded globally in app-layout.blade.php--}}

{{--            async function initGallerySwiper() {--}}
{{--                const thumbsEl = document.querySelector('.tf-product-media-thumbs');--}}
{{--                const mainEl = document.querySelector('.tf-product-media-main');--}}
{{--                if (!mainEl || mainEl.swiper) return;--}}

{{--                let thumbsSwiper = null;--}}
{{--                if (thumbsEl && !thumbsEl.swiper) {--}}
{{--                    thumbsSwiper = new Swiper('.tf-product-media-thumbs', {--}}
{{--                        direction: 'vertical',--}}
{{--                        slidesPerView: 'auto',--}}
{{--                        spaceBetween: 10,--}}
{{--                        freeMode: true,--}}
{{--                        watchSlidesProgress: true,--}}
{{--                        slideActiveClass: 'swiper-slide-active',--}}
{{--                        slideNextClass: 'swiper-slide-next',--}}
{{--                        slidePrevClass: 'swiper-slide-prev',--}}
{{--                        breakpoints: {--}}
{{--                            0: {direction: 'horizontal', slidesPerView: 'auto', spaceBetween: 8},--}}
{{--                            768: {direction: 'vertical', slidesPerView: 'auto', spaceBetween: 10}--}}
{{--                        }--}}
{{--                    });--}}
{{--                }--}}

{{--                const mainConfig = {--}}
{{--                    spaceBetween: 10,--}}
{{--                    loop: false,--}}
{{--                    autoplay: false,--}}
{{--                    speed: 300--}}
{{--                };--}}

{{--                if (thumbsSwiper) {--}}
{{--                    mainConfig.thumbs = {--}}
{{--                        swiper: thumbsSwiper,--}}
{{--                        slideThumbActiveClass: 'swiper-slide-thumb-active'--}}
{{--                    };--}}
{{--                    mainConfig.navigation = {--}}
{{--                        nextEl: '.thumbs-next',--}}
{{--                        prevEl: '.thumbs-prev'--}}
{{--                    };--}}
{{--                }--}}

{{--                const mainSwiper = new Swiper('.tf-product-media-main', mainConfig);--}}

{{--                // Enable navigation even without thumbs when multiple slides exist--}}
{{--                const slideCount = mainEl.querySelectorAll('.swiper-slide').length;--}}
{{--                if (slideCount > 1 && !mainConfig.navigation) {--}}
{{--                    mainSwiper.params.navigation = {--}}
{{--                        nextEl: '.thumbs-next',--}}
{{--                        prevEl: '.thumbs-prev'--}}
{{--                    };--}}
{{--                    mainSwiper.navigation.init();--}}
{{--                    mainSwiper.navigation.update();--}}
{{--                }--}}
{{--            }--}}

{{--            async function initRelatedProductsSwiper() {--}}
{{--                const relatedEl = document.querySelector('.tf-sw-product-sell');--}}
{{--                if (!relatedEl || relatedEl.swiper) return;--}}

{{--                try {--}}
{{--                    new Swiper('.tf-sw-product-sell', {--}}
{{--                        slidesPerView: 1,--}}
{{--                        spaceBetween: 15,--}}
{{--                        loop: false,--}}
{{--                        autoplay: false,--}}
{{--                        watchOverflow: true,--}}
{{--                        navigation: {--}}
{{--                            nextEl: '.nav-next-product',--}}
{{--                            prevEl: '.nav-prev-product',--}}
{{--                        },--}}
{{--                        breakpoints: {--}}
{{--                            768: {--}}
{{--                                slidesPerView: 2,--}}
{{--                                spaceBetween: 15,--}}
{{--                            },--}}
{{--                            1024: {--}}
{{--                                slidesPerView: 3,--}}
{{--                                spaceBetween: 30,--}}
{{--                            }--}}
{{--                        },--}}
{{--                        on: {--}}
{{--                            init: function () {--}}
{{--                                console.log('Related products swiper initialized');--}}
{{--                            }--}}
{{--                        }--}}
{{--                    });--}}
{{--                } catch (error) {--}}
{{--                    console.error('Error initializing related products swiper:', error);--}}
{{--                }--}}
{{--            }--}}

{{--            // Tab switching functionality--}}
{{--            function initProductTabs() {--}}
{{--                const tabItems = document.querySelectorAll('.widget-menu-tab .item-title');--}}
{{--                const contentItems = document.querySelectorAll('.widget-content-tab .widget-content-inner');--}}

{{--                tabItems.forEach((tab, index) => {--}}
{{--                    tab.addEventListener('click', function () {--}}
{{--                        // Remove active class from all tabs and content--}}
{{--                        tabItems.forEach(t => t.classList.remove('active'));--}}
{{--                        contentItems.forEach(c => c.classList.remove('active'));--}}

{{--                        // Add active class to clicked tab and corresponding content--}}
{{--                        tab.classList.add('active');--}}
{{--                        contentItems[index].classList.add('active');--}}
{{--                    });--}}
{{--                });--}}
{{--            }--}}

{{--            document.addEventListener('DOMContentLoaded', function () {--}}
{{--                try {--}}
{{--                    // Initialize tabs--}}
{{--                    initProductTabs();--}}
{{--                    // Initialize gallery after DOM is ready and dependencies are loaded--}}
{{--                    setTimeout(initGallerySwiper, 200);--}}
{{--                    // Initialize related products carousel--}}
{{--                    setTimeout(initRelatedProductsSwiper, 300);--}}
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

{{--            // Variant selection and gallery update--}}
{{--            function updateVariantDetails() {--}}
{{--                const colorRadios = document.querySelectorAll('.variant-color-radio');--}}
{{--                const sizeSelect = document.querySelector('.variant-size-select');--}}
{{--                const priceDisplay = document.querySelector('.variant-price .price');--}}
{{--                const stockStatus = document.querySelector('.variant-status .stock-status');--}}
{{--                const mainSwiper = document.querySelector('.tf-product-media-main').swiper;--}}

{{--                function updateSizeOptions(selectedColor) {--}}
{{--                    const selectedRadio = Array.from(colorRadios).find(radio => radio.value === selectedColor && radio.checked);--}}
{{--                    if (!selectedRadio) {--}}
{{--                        console.warn('No matching radio button found for selected color:', selectedColor);--}}
{{--                        return;--}}
{{--                    }--}}

{{--                    // Parse variant IDs from data attribute--}}
{{--                    let variantIds;--}}
{{--                    try {--}}
{{--                        variantIds = JSON.parse(selectedRadio.dataset.variants);--}}
{{--                    } catch (error) {--}}
{{--                        console.error('Error parsing variant IDs:', error);--}}
{{--                        return;--}}
{{--                    }--}}

{{--                    // Define variants from Blade JSON--}}

{{--                    const variants = @json($variantsData ?? []);--}}

{{--                    // Update size dropdown--}}
{{--                    sizeSelect.innerHTML = '';--}}
{{--                    variants--}}
{{--                        .filter(v => variantIds.includes(v.id))--}}
{{--                        .forEach(v => {--}}
{{--                            const option = document.createElement('option');--}}
{{--                            option.value = v.id;--}}
{{--                            option.textContent = v.size;--}}
{{--                            option.dataset.color = v.color;--}}
{{--                            option.dataset.size = v.size;--}}
{{--                            option.dataset.price = v.price;--}}
{{--                            option.dataset.stock = v.stock;--}}
{{--                            option.dataset.image = v.image;--}}
{{--                            sizeSelect.appendChild(option);--}}
{{--                        });--}}

{{--                    // Update details based on first size option--}}
{{--                    if (sizeSelect.options.length > 0) {--}}
{{--                        updateSelectedVariant();--}}
{{--                    } else {--}}
{{--                        console.warn('No size options available for the selected color.');--}}
{{--                    }--}}
{{--                }--}}

{{--                function updateSelectedVariant() {--}}
{{--                    const selectedOption = sizeSelect.options[sizeSelect.selectedIndex];--}}
{{--                    if (!selectedOption) return;--}}

{{--                    // Update price--}}
{{--                    priceDisplay.textContent = `UGX ${Number(selectedOption.dataset.price).toLocaleString('en-UG')}`;--}}

{{--                    // Update stock status--}}
{{--                    const stock = parseInt(selectedOption.dataset.stock, 10);--}}
{{--                    if (stock <= 0) {--}}
{{--                        stockStatus.textContent = 'Out of Stock';--}}
{{--                        stockStatus.className = 'stock-status text-danger';--}}
{{--                    } else if (stock <= 5) {--}}
{{--                        stockStatus.textContent = `Hurry! Only ${stock} left in stock.`;--}}
{{--                        stockStatus.className = 'stock-status text-warning';--}}
{{--                    } else {--}}
{{--                        stockStatus.textContent = 'In Stock';--}}
{{--                        stockStatus.className = 'stock-status text-success';--}}
{{--                    }--}}

{{--                    // Update gallery to show variant image--}}
{{--                    const variantImage = selectedOption.dataset.image;--}}
{{--                    const slides = document.querySelectorAll('.tf-product-media-main .swiper-slide');--}}
{{--                    let targetIndex = 0;--}}
{{--                    slides.forEach((slide, index) => {--}}
{{--                        if (slide.dataset.type === 'variant' && slide.dataset.variantId === selectedOption.value) {--}}
{{--                            targetIndex = index;--}}
{{--                        }--}}
{{--                    });--}}
{{--                    mainSwiper.slideTo(targetIndex);--}}

{{--                    // Update Livewire cart component--}}
{{--                    if (window.Livewire) {--}}
{{--                        window.Livewire.dispatch('update-cart-variant', {variantId: parseInt(selectedOption.value)});--}}
{{--                    }--}}
{{--                }--}}

{{--                colorRadios.forEach(radio => {--}}
{{--                    radio.addEventListener('change', function () {--}}
{{--                        updateSizeOptions(this.value);--}}
{{--                    });--}}
{{--                });--}}

{{--                sizeSelect.addEventListener('change', updateSelectedVariant);--}}

{{--                // Initialize on page load--}}
{{--                if (colorRadios.length > 0) {--}}
{{--                    const selectedColor = document.querySelector('.variant-color-radio:checked').value;--}}
{{--                    updateSizeOptions(selectedColor);--}}
{{--                }--}}
{{--            }--}}

{{--            document.addEventListener('DOMContentLoaded', function () {--}}
{{--                // Existing DOMContentLoaded code...--}}

{{--                // Initialize variant selector--}}
{{--                updateVariantDetails();--}}
{{--            });--}}

{{--        </script>--}}
{{--    @endpush--}}

{{--</div>--}}

<div class="product-detail-page">
    <!-- Consolidated PHP Logic at the Top -->
    @php
        // Pricing Logic
        $price = (float) ($product->price ?? 0);
        $sale = (float) ($product->sale_price ?? 0);
        $onSale = $sale > 0 && $sale < $price;
        $effective = $onSale ? $sale : $price;
        $discountPct = $onSale ? round((($price - $sale) / max($price, 1)) * 100) : 0;

        // Gallery Logic
        $gallery = [];
        $placeholder = asset('images/placeholder-product.jpg');
        $rawGallery = $product->gallery ?? [];
        if (is_array($rawGallery) && count($rawGallery) > 0) {
            foreach ($rawGallery as $imagePath) {
                $url = \App\Helpers\ImageStorageHelper::url($imagePath);
                $gallery[] = [
                    'thumb' => $url,
                    'large' => $url,
                    'original' => $url,
                    'type' => 'product',
                ];
            }
        }
        if ($product->variants->count() > 0) {
            foreach ($product->variants as $variant) {
                if (!empty($variant->images) && is_array($variant->images)) {
                    $gallery[] = [
                        'thumb' => $variant->primary_image_url,
                        'large' => $variant->primary_image_url,
                        'original' => $variant->primary_image_url,
                        'type' => 'variant',
                        'variant_id' => $variant->id,
                        'color' => $variant->color,
                        'size' => $variant->size,
                    ];
                }
            }
        }
        if (empty($gallery)) {
            $gallery[] = [
                'thumb' => $placeholder,
                'large' => $placeholder,
                'original' => $placeholder,
                'type' => 'placeholder',
            ];
        }
        $hasImages = is_array($gallery) && count($gallery) > 0;
        $hasMultipleImages = $hasImages && count($gallery) > 1;
        $thumb = $hasImages && isset($gallery[0]['thumb']) ? $gallery[0]['thumb'] : $placeholder;

        // Previous/Next Product Logic
        $prev = $product->previous();
        $next = $product->next();
    @endphp

        <!-- Breadcrumb -->
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

    <!-- Default -->
    <section class="flat-spacing-4 pt_0">
        <div class="tf-main-product">
            <div class="container">
                <div class="row">
                    <!-- Media/Gallery -->
                    <div class="col-md-6">
                        <div class="tf-product-media-wrap sticky-top">
                            <div class="thumbs-slider">
                                <!-- Thumbs -->
                                @if($hasMultipleImages)
                                    <div dir="ltr" class="swiper tf-product-media-thumbs other-image-zoom"
                                         data-direction="vertical">
                                        <div class="swiper-wrapper stagger-wrap">
                                            @foreach($gallery as $index => $image)
                                                <div class="swiper-slide stagger-item" wire:key="thumb-{{ $index }}"
                                                     data-variant-id="{{ $image['variant_id'] ?? '' }}"
                                                     data-type="{{ $image['type'] }}"
                                                     data-color="{{ $image['color'] ?? '' }}"
                                                     data-size="{{ $image['size'] ?? '' }}">
                                                    <div class="item">
                                                        <img class="lazyload"
                                                             data-src="{{ $image['thumb'] }}"
                                                             src="{{ $image['thumb'] }}"
                                                             alt="{{ $product->name }}"
                                                             onerror="this.onerror=null; this.src='{{ $placeholder }}'">
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
                                            <div class="swiper-slide" wire:key="main-{{ $index }}"
                                                 data-variant-id="{{ $image['variant_id'] ?? '' }}"
                                                 data-type="{{ $image['type'] }}"
                                                 data-color="{{ $image['color'] ?? '' }}"
                                                 data-size="{{ $image['size'] ?? '' }}">
                                                <a href="{{ $image['large'] }}" target="_blank" class="item"
                                                   data-pswp-width="1200" data-pswp-height="1200">
                                                    <img class="tf-image-zoom lazyload"
                                                         data-zoom="{{ $image['large'] }}"
                                                         data-src="{{ $image['large'] }}"
                                                         src="{{ $image['large'] }}"
                                                         alt="{{ $product->name }}"
                                                         onerror="this.onerror=null; this.src='{{ $placeholder }}'">
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
                                    @if($product->variants->count() > 0)
                                        <div class="product-status-content">
                                            <i class="icon-lightning"></i>
                                            <p class="fw-6">Select a variant to check stock.</p>
                                        </div>
                                    @elseif($product->stock_quantity <= 5 && $product->stock_quantity > 0)
                                        <div class="product-status-content">
                                            <i class="icon-lightning"></i>
                                            <p class="fw-6">Hurry! Only {{ $product->stock_quantity }} left in stock.</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Variant Selector -->
                                @if($product->variants->count() > 0)
                                    <div class="tf-product-info-variants mt-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-6">Color</label>
                                            <div class="variant-options d-flex gap-2 flex-wrap">
                                                @foreach($product->variants->groupBy('color') as $color => $variants)
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input variant-color-radio" type="radio"
                                                               name="variant_color" id="color_{{ Str::slug($color) }}"
                                                               value="{{ $color }}"
                                                               data-variants="{{ json_encode($variants->pluck('id')->toArray()) }}"
                                                            {{ $loop->first ? 'checked' : '' }}>
                                                        <label class="form-check-label badge bg-info"
                                                               for="color_{{ Str::slug($color) }}">
                                                            {{ $color }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-6">Size</label>
                                            <select class="form-select variant-size-select" name="variant_id">
                                                @foreach($product->variants->where('color', $product->variants->groupBy('color')->keys()->first()) as $variant)
                                                    <option value="{{ $variant->id }}"
                                                            data-color="{{ $variant->color }}"
                                                            data-size="{{ $variant->size }}"
                                                            data-price="{{ $variant->effective_price }}"
                                                            data-stock="{{ $variant->stock_quantity }}"
                                                            data-image="{{ $variant->primary_image_url }}">
                                                        {{ $variant->size }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="variant-status mt-2">
                                                <span class="stock-status text-muted"></span>
                                            </div>
                                        </div>
                                        <div class="variant-price mt-2">
                                            <span class="fw-6 price"></span>
                                        </div>
                                    </div>
                                @else
                                    <!-- Price -->
                                    <div class="tf-product-info-price">
                                        @if($onSale)
                                            <div class="price-on-sale">{{ money_format_ugx($effective) }}</div>
                                            <div class="compare-at-price">{{ money_format_ugx($price) }}</div>
                                            <div class="badges-on-sale"><span>{{ $discountPct }}</span>% OFF</div>
                                        @else
                                            <div class="price">{{ money_format_ugx($effective) }}</div>
                                        @endif
                                    </div>
                                @endif

                                <!-- Rating summary -->
                                @if($product->reviews_count > 0)
                                    <div class="d-flex align-items-center gap-10">
                                        <div class="list-star">
                                            @for($i = 1; $i <= 5; $i++)
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

                                <!-- Add to Cart -->
                                <div id="atc-root" class="mt_16">
                                    <livewire:client.cart.cart :product="$product"
                                                               :variantId="$product->variants->count() > 0 ? $product->variants->first()->id : null"/>
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Add to Cart -->
        <div class="tf-sticky-btn-atc">
            <div class="container">
                <div class="tf-height-observer w-100 d-flex align-items-center">
                    <div class="tf-sticky-atc-product d-flex align-items-center">
                        <div class="tf-sticky-atc-img">
                            <img class="lazyload" data-src="{{ $thumb }}" alt="{{ $product->name }}" src="{{ $thumb }}">
                        </div>
                        <div class="tf-sticky-atc-title fw-5 d-xl-block d-none">{{ $product->name }}</div>
                    </div>

                    <div class="tf-sticky-atc-infos">
                        <form onsubmit="return false;">
                            <div class="tf-sticky-atc-variant-price text-center">
                                <span class="fw-6">
                                    @if($onSale)
                                        <span class="price-on-sale">{{ money_format_ugx($effective) }}</span>
                                        <span class="compare-at-price ms-1">{{ money_format_ugx($price) }}</span>
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
                                <div class="table-responsive">
                                    <table class="tf-pr-attrs table table-striped table-hover">
                                        <tbody>
                                        @if($product->brand)
                                            <tr class="tf-attr-pa-brand">
                                                <th class="tf-attr-label w-25 fw-semibold">Brand</th>
                                                <td class="tf-attr-value">
                                                    <p class="mb-0">{{ $product->brand->name }}</p>
                                                </td>
                                            </tr>
                                        @endif
                                        @if($product->sku)
                                            <tr class="tf-attr-pa-sku">
                                                <th class="tf-attr-label w-25 fw-semibold">SKU</th>
                                                <td class="tf-attr-value">
                                                    <p class="mb-0 font-monospace">{{ $product->sku }}</p>
                                                </td>
                                            </tr>
                                        @endif
                                        @if($product->weight)
                                            <tr class="tf-attr-pa-weight">
                                                <th class="tf-attr-label w-25 fw-semibold">Weight</th>
                                                <td class="tf-attr-value">
                                                    <p class="mb-0">{{ $product->weight }} kg</p>
                                                </td>
                                            </tr>
                                        @endif
                                        @if($product->dimensions)
                                            <tr class="tf-attr-pa-dimensions">
                                                <th class="tf-attr-label w-25 fw-semibold">Dimensions</th>
                                                <td class="tf-attr-value">
                                                    <p class="mb-0">{{ $product->dimensions }}</p>
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Reviews Tab -->
                            <div class="widget-content-inner">
                                <div class="tab-reviews write-cancel-review-wrap">
                                    @if($product->reviews_count > 0)
                                        <div class="tab-reviews-heading mb-4">
                                            <div class="top">
                                                <div class="text-center p-4 bg-light rounded">
                                                    <h1 class="number fw-6 display-3 mb-3">{{ number_format($product->average_rating, 1) }}</h1>
                                                    <div class="list-star mb-3">
                                                        @for($i=1; $i<=5; $i++)
                                                            <i class="icon icon-star{{ $i <= round($product->average_rating) ? '' : '-o' }} text-warning fs-4"></i>
                                                        @endfor
                                                    </div>
                                                    <p class="text-muted mb-0">({{ $product->reviews_count }}
                                                        Reviews)</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="reply-comment cancel-review-wrap">
                                            <div
                                                class="d-flex mb_24 gap-20 align-items-center justify-content-between flex-wrap mb-4 pb-3 border-bottom">
                                                <h5 class="mb-0">{{ $product->reviews_count }} Reviews</h5>
                                            </div>
                                            <div class="reply-comment-wrap">
                                                <div class="alert alert-info text-center" role="alert">
                                                    <i class="bi bi-info-circle me-2"></i>
                                                    Reviews will be displayed here when available.
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center py-5">
                                            <div class="mb-4">
                                                <i class="bi bi-chat-left-text display-1 text-muted"></i>
                                            </div>
                                            <h5 class="mb-3">No Reviews Yet</h5>
                                            <p class="text-muted">Be the first to review this product!</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Shipping Tab -->
                            <div class="widget-content-inner">
                                <div class="tf-page-privacy-policy">
                                    <div class="title mb-4 pb-3 border-bottom">
                                        <h5 class="mb-0">Shipping Information</h5>
                                    </div>
                                    <p class="lead mb-4">We offer reliable shipping options to ensure your order reaches
                                        you safely and on time.</p>

                                    <div class="row g-4">
                                        <div class="col-md-4">
                                            <div class="card h-100 border-0 shadow-sm">
                                                <div class="card-body">
                                                    <h4 class="mt-4 mb-2 h5 card-title"><i
                                                            class="icon-delivery-time me-2"></i>Delivery Times</h4>
                                                    <ul class="list-unstyled mb-0">
                                                        <li class="mb-2"><strong>Within CBD:</strong> Same day of
                                                            purchase
                                                        </li>
                                                        <li class="mb-2"><strong>Upcountry:</strong> 2-5 business days
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="card h-100 border-0 shadow-sm">
                                                <div class="card-body">
                                                    <h4 class="mt-4 mb-2 h5 card-title"><i
                                                            class="icon-fast-shipping me-2"></i>Shipping Costs</h4>
                                                    <p class="mb-0">Shipping costs are calculated based on the weight
                                                        and destination of your order. Free shipping is available for
                                                        orders over a certain amount.</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="card h-100 border-0 shadow-sm">
                                                <div class="card-body">
                                                    <h4 class="mt-4 mb-2 h5 card-title"><i
                                                            class="icon-car-order me-2"></i>Order Processing</h4>
                                                    <p class="mb-0">Orders are processed within 1-2 business days. You
                                                        will receive a tracking number once your order has been
                                                        shipped.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Return Policies Tab -->
                            <div class="widget-content-inner">
                                <div class="tf-page-privacy-policy">
                                    <div class="title mb-4 pb-3 border-bottom">
                                        <h5 class="mb-0">Return & Exchange Policy</h5>
                                    </div>
                                    <p class="lead mb-4">We want you to be completely satisfied with your purchase. If
                                        you're not happy with your order, we're here to help.</p>

                                    <div class="row g-4">
                                        <div class="col-lg-6">
                                            <div class="card border-0 shadow-sm h-100">
                                                <div class="card-body">
                                                    <h5 class="mt-4 mb-2 h5 card-title"><i
                                                            class="icon-calendar me-2"></i>Return Window</h5>
                                                    <p class="mb-4">You have <strong class="text-primary">1 day</strong>
                                                        from the date of delivery to return your item for a full refund
                                                        or exchange.</p>

                                                    <h5 class="mt-4 mb-2 h5 card-title"><i
                                                            class="icon-return-order me-2"></i>Return Conditions</h5>
                                                    <ul class="list-unstyled mb-0">
                                                        <li class="mb-2"><i class="icon-check text-success me-2"></i>Items
                                                            must be in original condition
                                                        </li>
                                                        <li class="mb-2"><i class="icon-check text-success me-2"></i>Items
                                                            must be unused and with tags attached
                                                        </li>
                                                        <li class="mb-2"><i class="icon-check text-success me-2"></i>Original
                                                            packaging must be included
                                                        </li>
                                                        <li class="mb-0"><i class="icon-check text-success me-2"></i>Proof
                                                            of purchase required
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="card border-0 shadow-sm h-100">
                                                <div class="card-body">
                                                    <h4 class="mt-4 mb-2 h5 card-title"><i class="icon-return me-2"></i>How
                                                        to Return</h4>
                                                    <ol class="mb-4">
                                                        <li class="mb-2"><i class="icon-check text-success me-2"></i>Contact
                                                            our customer service team
                                                        </li>
                                                        <li class="mb-2"><i class="icon-check text-success me-2"></i>Receive
                                                            return authorization and shipping label
                                                        </li>
                                                        <li class="mb-2"><i class="icon-check text-success me-2"></i>Package
                                                            your item securely
                                                        </li>
                                                        <li class="mb-0"><i class="icon-check text-success me-2"></i>Ship
                                                            using provided label
                                                        </li>
                                                    </ol>

                                                    <h4 class="mt-4 mb-2 h5 card-title"><i class="icon-card me-2"></i>Refund
                                                        Processing</h4>
                                                    <p class="mb-0">Once we receive and inspect your return, we'll
                                                        process your refund within 5-7 business days. Refunds will be
                                                        credited to your original payment method.</p>
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
    </section>

    <!-- Related Products -->
    @if(isset($relatedProducts) && $relatedProducts->isNotEmpty())
        <section class="flat-spacing-1 pt_0">
            <div class="container">
                <div class="flat-title">
                    <span class="title">People Also Bought</span>
                </div>
                <!-- Consolidated Related Products Image Logic -->
                @php
                    $relatedImages = [];
                    $placeholder = asset('images/placeholder-product.jpg');
                    foreach ($relatedProducts->take(6) as $relatedProduct) {
                        $relatedImages[$relatedProduct->id] = [
                            'main' => $relatedProduct->images[0]['large'] ?? $placeholder,
                            'hover' => $relatedProduct->images[1]['large'] ?? ($relatedProduct->images[0]['large'] ?? $placeholder),
                            'pricing' => [
                                'price' => (float) ($relatedProduct->price ?? 0),
                                'sale' => (float) ($relatedProduct->sale_price ?? 0),
                                'onSale' => false,
                                'effective' => (float) ($relatedProduct->price ?? 0),
                                'discountPct' => 0,
                            ]
                        ];
                        if ($relatedImages[$relatedProduct->id]['pricing']['sale'] > 0 && $relatedImages[$relatedProduct->id]['pricing']['sale'] < $relatedImages[$relatedProduct->id]['pricing']['price']) {
                            $relatedImages[$relatedProduct->id]['pricing']['onSale'] = true;
                            $relatedImages[$relatedProduct->id]['pricing']['effective'] = $relatedImages[$relatedProduct->id]['pricing']['sale'];
                            $relatedImages[$relatedProduct->id]['pricing']['discountPct'] = round((($relatedImages[$relatedProduct->id]['pricing']['price'] - $relatedImages[$relatedProduct->id]['pricing']['sale']) / max($relatedImages[$relatedProduct->id]['pricing']['price'], 1)) * 100);
                        }
                    }
                @endphp
                <div class="hover-sw-nav hover-sw-3">
                    <div dir="ltr" class="swiper tf-sw-product-sell rounded-0 border-0 wrap-sw-over"
                         data-loop="false" data-play="false" data-preview="3" data-tablet="2" data-mobile="1"
                         data-space-lg="30" data-space-md="15">
                        <div class="swiper-wrapper" aria-live="polite">
                            @foreach($relatedProducts->take(6) as $relatedProduct)
                                <div class="swiper-slide" lazy="true" role="group"
                                     aria-label="{{ $loop->iteration }} / {{ $relatedProducts->take(6)->count() }}">
                                    <div class="card-product">
                                        <div class="card-product-wrapper">
                                            <a href="{{ route('products.show', $relatedProduct->slug) }}"
                                               class="product-img">
                                                <img class="img-product lazyload" data-src="{{ $relatedImages[$relatedProduct->id]['main'] }}"
                                                     src="{{ $relatedImages[$relatedProduct->id]['main'] }}" alt="{{ $relatedProduct->name }}">
                                                <img class="img-hover lazyload" data-src="{{ $relatedImages[$relatedProduct->id]['hover'] }}"
                                                     src="{{ $relatedImages[$relatedProduct->id]['hover'] }}" alt="{{ $relatedProduct->name }}">
                                            </a>
                                            <div class="list-product-btn">
                                                <a href="#quick_add" data-bs-toggle="modal"
                                                   class="box-icon bg_white quick-add tf-btn-loading">
                                                    <span class="icon icon-bag"></span>
                                                    <span class="tooltip">Quick Add</span>
                                                </a>
                                                @auth
                                                    <livewire:client.wish-list-toggle :product="$relatedProduct"
                                                                                      :cardView="true"/>
                                                @else
                                                    <a href="{{ route('login') }}"
                                                       class="box-icon bg_white wishlist btn-icon-action">
                                                        <span class="icon icon-heart"></span>
                                                        <span class="tooltip">Add to Wishlist</span>
                                                        <span class="icon icon-delete"></span>
                                                    </a>
                                                @endauth
                                                <a href="#compare" data-bs-toggle="offcanvas"
                                                   aria-controls="offcanvasLeft"
                                                   class="box-icon bg_white compare btn-icon-action"
                                                   onclick="if(window.Livewire?.dispatch){window.Livewire.dispatch('compare:toggle',{id: {{ (int)$relatedProduct->id }} });}">
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

                                            <!-- Size List (if product has variants) -->
                                            @if($relatedProduct->variants && $relatedProduct->variants->isNotEmpty())
                                                <div class="size-list">
                                                    @foreach($relatedProduct->variants->take(4) as $variant)
                                                        <span>{{ $variant->size ?? $variant->name }}</span>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <!-- Sale Badge -->
                                            @if($relatedImages[$relatedProduct->id]['pricing']['onSale'] && $relatedImages[$relatedProduct->id]['pricing']['discountPct'] > 0)
                                                <div class="on-sale-wrap">
                                                    <div class="on-sale-item">-{{ $relatedImages[$relatedProduct->id]['pricing']['discountPct'] }}%</div>
                                                </div>
                                            @endif

                                            <!-- Countdown (if product has sale end date) -->
                                            @if($relatedProduct->sale_price && $relatedProduct->sale_end_date)
                                                <div class="countdown-box">
                                                    <div class="js-countdown"
                                                         data-timer="{{ \Carbon\Carbon::parse($relatedProduct->sale_end_date)->timestamp * 1000 }}"
                                                         data-labels="d :,h :,m :,s">
                                                        <!-- Countdown will be initialized by JavaScript -->
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-product-info">
                                            <a href="{{ route('products.show', $relatedProduct->slug) }}"
                                               class="title link">{{ $relatedProduct->name }}</a>

                                            <!-- Price -->
                                            @if($relatedImages[$relatedProduct->id]['pricing']['onSale'])
                                                <span class="price">{{ money_format_ugx($relatedImages[$relatedProduct->id]['pricing']['effective']) }}</span>
                                                @if($relatedImages[$relatedProduct->id]['pricing']['price'] > $relatedImages[$relatedProduct->id]['pricing']['sale'])
                                                    <span class="compare-price"
                                                          style="text-decoration: line-through; color: #999; font-size: 0.9em; margin-left: 5px;">
                                                        {{ money_format_ugx($relatedImages[$relatedProduct->id]['pricing']['price']) }}
                                                    </span>
                                                @endif
                                            @else
                                                <span class="price">{{ money_format_ugx($relatedImages[$relatedProduct->id]['pricing']['effective']) }}</span>
                                            @endif

                                            <!-- Color Swatches -->
                                            @if($relatedProduct->colors && $relatedProduct->colors->isNotEmpty())
                                                <ul class="list-color-product">
                                                    @foreach($relatedProduct->colors as $index => $color)
                                                        <li class="list-color-item color-swatch {{ $index === 0 ? 'active' : '' }}">
                                                            <span class="tooltip">{{ $color->name }}</span>
                                                            <span class="swatch-value"
                                                                  style="background-color: {{ $color->hex_code }}"></span>
                                                            <img class="lazyload"
                                                                 data-src="{{ $color->image_url ?? $relatedImages[$relatedProduct->id]['main'] }}"
                                                                 src="{{ $color->image_url ?? $relatedImages[$relatedProduct->id]['main'] }}"
                                                                 alt="{{ $relatedProduct->name }}">
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Navigation Arrows -->
                    <div class="nav-sw nav-next-slider nav-next-product box-icon w_46 round">
                        <span class="icon icon-arrow-left"></span>
                    </div>
                    <div class="nav-sw nav-prev-slider nav-prev-product box-icon w_46 round">
                        <span class="icon icon-arrow-right"></span>
                    </div>
                </div>

                <!-- View All Button -->
                @if($relatedProducts->count() > 3)
                    <div class="text-center mt-4">
                        @php
                            $primaryCategory = $product->categories->first();
                            $viewAllUrl = $primaryCategory
                                ? route('shop.index', ['category' => $primaryCategory->slug])
                                : route('shop.index');
                        @endphp
                        <a href="{{ $viewAllUrl }}" class="tf-btn btn-outline radius-3 fs-14">
                            View All Related Products
                            <i class="icon icon-arrow-right ms-2"></i>
                        </a>
                    </div>
                @endif
            </div>
        </section>
    @endif

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
                            0: {direction: 'horizontal', slidesPerView: 'auto', spaceBetween: 8},
                            768: {direction: 'vertical', slidesPerView: 'auto', spaceBetween: 10}
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

            async function initRelatedProductsSwiper() {
                const relatedEl = document.querySelector('.tf-sw-product-sell');
                if (!relatedEl || relatedEl.swiper) return;

                try {
                    new Swiper('.tf-sw-product-sell', {
                        slidesPerView: 1,
                        spaceBetween: 15,
                        loop: false,
                        autoplay: false,
                        watchOverflow: true,
                        navigation: {
                            nextEl: '.nav-next-product',
                            prevEl: '.nav-prev-product',
                        },
                        breakpoints: {
                            768: {
                                slidesPerView: 2,
                                spaceBetween: 15,
                            },
                            1024: {
                                slidesPerView: 3,
                                spaceBetween: 30,
                            }
                        },
                        on: {
                            init: function () {
                                console.log('Related products swiper initialized');
                            }
                        }
                    });
                } catch (error) {
                    console.error('Error initializing related products swiper:', error);
                }
            }

            // Tab switching functionality
            function initProductTabs() {
                const tabItems = document.querySelectorAll('.widget-menu-tab .item-title');
                const contentItems = document.querySelectorAll('.widget-content-tab .widget-content-inner');

                tabItems.forEach((tab, index) => {
                    tab.addEventListener('click', function () {
                        // Remove active class from all tabs and content
                        tabItems.forEach(t => t.classList.remove('active'));
                        contentItems.forEach(c => c.classList.remove('active'));

                        // Add active class to clicked tab and corresponding content
                        tab.classList.add('active');
                        contentItems[index].classList.add('active');
                    });
                });
            }

            document.addEventListener('DOMContentLoaded', function () {
                try {
                    // Initialize tabs
                    initProductTabs();
                    // Initialize gallery after DOM is ready and dependencies are loaded
                    setTimeout(initGallerySwiper, 200);
                    // Initialize related products carousel
                    setTimeout(initRelatedProductsSwiper, 300);
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

            // Variant selection and gallery update
            function updateVariantDetails() {
                const colorRadios = document.querySelectorAll('.variant-color-radio');
                const sizeSelect = document.querySelector('.variant-size-select');
                const priceDisplay = document.querySelector('.variant-price .price');
                const stockStatus = document.querySelector('.variant-status .stock-status');
                const mainSwiper = document.querySelector('.tf-product-media-main').swiper;

                function updateSizeOptions(selectedColor) {
                    const selectedRadio = Array.from(colorRadios).find(radio => radio.value === selectedColor && radio.checked);
                    if (!selectedRadio) {
                        console.warn('No matching radio button found for selected color:', selectedColor);
                        return;
                    }

                    // Parse variant IDs from data attribute
                    let variantIds;
                    try {
                        variantIds = JSON.parse(selectedRadio.dataset.variants);
                    } catch (error) {
                        console.error('Error parsing variant IDs:', error);
                        return;
                    }

                    // Define variants from Blade JSON
                    const variants = @json($variantsData ?? []);

                    // Update size dropdown
                    sizeSelect.innerHTML = '';
                    variants
                        .filter(v => variantIds.includes(v.id))
                        .forEach(v => {
                            const option = document.createElement('option');
                            option.value = v.id;
                            option.textContent = v.size;
                            option.dataset.color = v.color;
                            option.dataset.size = v.size;
                            option.dataset.price = v.price;
                            option.dataset.stock = v.stock;
                            option.dataset.image = v.image;
                            sizeSelect.appendChild(option);
                        });

                    // Update details based on first size option
                    if (sizeSelect.options.length > 0) {
                        updateSelectedVariant();
                    } else {
                        console.warn('No size options available for the selected color.');
                    }
                }

                function updateSelectedVariant() {
                    const selectedOption = sizeSelect.options[sizeSelect.selectedIndex];
                    if (!selectedOption) return;

                    // Update price
                    priceDisplay.textContent = `UGX ${Number(selectedOption.dataset.price).toLocaleString('en-UG')}`;

                    // Update stock status
                    const stock = parseInt(selectedOption.dataset.stock, 10);
                    if (stock <= 0) {
                        stockStatus.textContent = 'Out of Stock';
                        stockStatus.className = 'stock-status text-danger';
                    } else if (stock <= 5) {
                        stockStatus.textContent = `Hurry! Only ${stock} left in stock.`;
                        stockStatus.className = 'stock-status text-warning';
                    } else {
                        stockStatus.textContent = 'In Stock';
                        stockStatus.className = 'stock-status text-success';
                    }

                    // Update gallery to show variant image
                    const variantImage = selectedOption.dataset.image;
                    const slides = document.querySelectorAll('.tf-product-media-main .swiper-slide');
                    let targetIndex = 0;
                    slides.forEach((slide, index) => {
                        if (slide.dataset.type === 'variant' && slide.dataset.variantId === selectedOption.value) {
                            targetIndex = index;
                        }
                    });
                    mainSwiper.slideTo(targetIndex);

                    // Update Livewire cart component
                    if (window.Livewire) {
                        window.Livewire.dispatch('update-cart-variant', {variantId: parseInt(selectedOption.value)});
                    }
                }

                colorRadios.forEach(radio => {
                    radio.addEventListener('change', function () {
                        updateSizeOptions(this.value);
                    });
                });

                sizeSelect.addEventListener('change', updateSelectedVariant);

                // Initialize on page load
                if (colorRadios.length > 0) {
                    const selectedColor = document.querySelector('.variant-color-radio:checked').value;
                    updateSizeOptions(selectedColor);
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                // Existing DOMContentLoaded code...
                // Initialize variant selector
                updateVariantDetails();
            });
        </script>
    @endpush
</div>
