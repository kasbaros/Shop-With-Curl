<div>
{{--    @if($showModal && $product)--}}
{{--        <div class="modal fade show modalDemo popup-quickview" id="quick_view" tabindex="-1"--}}
{{--             style="display: block; background-color: rgba(0,0,0,0.5);">--}}
{{--            <div class="modal-dialog modal-dialog-centered modal-lg">--}}
{{--                <div class="modal-content">--}}
{{--                    <div class="header">--}}
{{--                        <span class="icon-close icon-close-popup" wire:click="closeModal"--}}
{{--                              data-bs-dismiss="modal"></span>--}}
{{--                    </div>--}}
{{--                    <div class="wrap">--}}
{{--                        <div class="tf-product-media-wrap">--}}
{{--                            <div dir="ltr" class="swiper tf-single-slide">--}}
{{--                                <div class="swiper-wrapper">--}}
{{--                                    <div class="swiper-slide">--}}
{{--                                        <div class="item">--}}
{{--                                            <img src="{{ $this->imageUrl }}" alt="{{ $product->name }}">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <!-- If there are multiple images, you'd add them here -->--}}
{{--                                </div>--}}
{{--                                <div class="swiper-button-next button-style-arrow single-slide-prev"></div>--}}
{{--                                <div class="swiper-button-prev button-style-arrow single-slide-next"></div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="tf-product-info-wrap position-relative">--}}
{{--                            <div class="tf-product-info-list">--}}
{{--                                <div class="tf-product-info-title">--}}
{{--                                    <h5><a class="link"--}}
{{--                                           href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>--}}
{{--                                    </h5>--}}
{{--                                </div>--}}

{{--                                <div class="tf-product-info-badges">--}}
{{--                                    @if($product->is_featured)--}}
{{--                                        <div class="badges text-uppercase">Best seller</div>--}}
{{--                                    @endif--}}
{{--                                    @if($product->sale_price)--}}
{{--                                        <div class="product-status-content">--}}
{{--                                            <i class="icon-lightning"></i>--}}
{{--                                            <p class="fw-6">On Sale! Get it while it lasts.</p>--}}
{{--                                        </div>--}}
{{--                                    @endif--}}
{{--                                </div>--}}

{{--                                <div class="tf-product-info-price">--}}
{{--                                    @if($product->sale_price)--}}
{{--                                        <div class="price text-decoration-line-through">{{ $this->originalPrice }}</div>--}}
{{--                                        <div class="price">{{ $this->formattedPrice }}</div>--}}
{{--                                    @else--}}
{{--                                        <div class="price">{{ $this->formattedPrice }}</div>--}}
{{--                                    @endif--}}
{{--                                </div>--}}

{{--                                @if($product->short_description)--}}
{{--                                    <div class="tf-product-description">--}}
{{--                                        <p>{{ $product->short_description }}</p>--}}
{{--                                    </div>--}}
{{--                                @endif--}}

{{--                                <!-- Variant Selectors would go here if applicable -->--}}
{{--                                <div class="tf-product-info-variant-picker">--}}
{{--                                    <!-- Add color variant picker if applicable -->--}}
{{--                                     <div class="variant-picker-item">--}}
{{--                                        <div class="variant-picker-label">--}}
{{--                                            Color: <span class="fw-6 variant-picker-label-value">Orange</span>--}}
{{--                                        </div>--}}
{{--                                        <div class="variant-picker-values">--}}
{{--                                            <input id="values-orange-1" type="radio" name="color-1" checked>--}}
{{--                                            <label class="hover-tooltip radius-60" for="values-orange-1" data-value="Orange">--}}
{{--                                                <span class="btn-checkbox bg-color-orange"></span>--}}
{{--                                                <span class="tooltip">Orange</span>--}}
{{--                                            </label>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    <!-- Add size variant picker if applicable -->--}}
{{--                                     <div class="variant-picker-item">--}}
{{--                                        <div class="d-flex justify-content-between align-items-center">--}}
{{--                                            <div class="variant-picker-label">--}}
{{--                                                Size: <span class="fw-6 variant-picker-label-value">S</span>--}}
{{--                                            </div>--}}
{{--                                            <div class="find-size btn-choose-size fw-6">Find your size</div>--}}
{{--                                        </div>--}}
{{--                                        <div class="variant-picker-values">--}}
{{--                                            <input type="radio" name="size-1" id="values-s-1" checked>--}}
{{--                                            <label class="style-text" for="values-s-1" data-value="S">--}}
{{--                                                <p>S</p>--}}
{{--                                            </label>--}}
{{--                                        </div>--}}
{{--                                    </div> --}}
{{--                                </div>--}}

{{--                                <div class="tf-product-info-quantity">--}}
{{--                                    <div class="quantity-title fw-6">Quantity</div>--}}
{{--                                    <div class="wg-quantity">--}}
{{--                                        <span class="btn-quantity minus-btn" wire:click="decrementQuantity">-</span>--}}
{{--                                        <input type="text" name="number" value="{{ $quantity }}" readonly>--}}
{{--                                        <span class="btn-quantity plus-btn" wire:click="incrementQuantity">+</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="tf-product-info-buy-button">--}}
{{--                                    <form class="">--}}
{{--                                        <a href="javascript:void(0);"--}}
{{--                                           class="tf-btn btn-fill justify-content-center fw-6 fs-16 flex-grow-1 animate-hover-btn btn-add-to-cart"--}}
{{--                                           wire:click="addToCart">--}}
{{--                                            <span>Add to cart -&nbsp;</span>--}}
{{--                                            <span class="tf-qty-price">{{ $this->formattedPrice }}</span>--}}
{{--                                        </a>--}}
{{--                                        <a href="javascript:void(0);"--}}
{{--                                           class="tf-product-btn-wishlist hover-tooltip box-icon bg_white wishlist btn-icon-action"--}}
{{--                                           wire:click="toggleWishlist">--}}
{{--                                            <span class="icon icon-heart"></span>--}}
{{--                                            <span class="tooltip">Add to Wishlist</span>--}}
{{--                                            <span class="icon icon-delete"></span>--}}
{{--                                        </a>--}}
{{--                                        <a href="javascript:void(0);"--}}
{{--                                           class="tf-product-btn-wishlist hover-tooltip box-icon bg_white compare btn-icon-action"--}}
{{--                                           wire:click="toggleCompare">--}}
{{--                                            <span class="icon icon-compare"></span>--}}
{{--                                            <span class="tooltip">Add to Compare</span>--}}
{{--                                            <span class="icon icon-check"></span>--}}
{{--                                        </a>--}}
{{--                                        <div class="w-100">--}}
{{--                                            <a href="#" class="btns-full">Buy with <img src="images/payments/paypal.png"--}}
{{--                                                                                        alt=""></a>--}}
{{--                                            <a href="#" class="payment-more-option">More payment options</a>--}}
{{--                                        </div>--}}
{{--                                    </form>--}}
{{--                                </div>--}}
{{--                                <div>--}}
{{--                                    <a href="{{ route('products.show', $product->slug) }}" class="btn fw-6 btn-line">View--}}
{{--                                        full details<i class="icon icon-arrow1-top-left"></i></a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    @endif--}}

    @if($showModal && $product)
    <div class="modal fade show modalDemo popup-quickview" id="quick_view" tabindex="-1"
         style="z-index: 1060; display: block; background-color: rgba(0,0,0,0.5);"
         aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="header">
                    <span class="icon-close icon-close-popup" wire:click="closeModal"
                          data-bs-dismiss="modal"></span>
                </div>
                <div class="wrap">
                    <div class="tf-product-media-wrap">
                        <div dir="ltr" class="swiper tf-single-slide swiper-initialized swiper-horizontal swiper-pointer-events swiper-backface-hidden">
                            <div class="swiper-wrapper" style="transition-duration: 0ms; transform: translate3d(0px, 0px, 0px);">
                                <div class="swiper-slide swiper-slide-active" style="width: 565px;" role="group" aria-label="1 / 1">
                                    <div class="item">
                                        <img src="{{ $this->imageUrl }}" alt="{{ $product->name }}">
                                    </div>
                                </div>
                                @if($product->additional_images)
                                    @foreach($product->additional_images as $index => $image)
                                        <div class="swiper-slide" style="width: 565px;" role="group" aria-label="{{ $index + 2 }} / {{ count($product->additional_images) + 1 }}">
                                            <div class="item">
                                                <img src="{{ $image }}" alt="{{ $product->name }}">
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="swiper-button-next button-style-arrow single-slide-prev" tabindex="0" role="button" aria-label="Next slide"></div>
                            <div class="swiper-button-prev button-style-arrow single-slide-next" tabindex="-1" role="button" aria-label="Previous slide"></div>
                            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                        </div>
                    </div>
                    <div class="tf-product-info-wrap position-relative">
                        <div class="tf-product-info-list">
                            <div class="tf-product-info-title">
                                <h5><a class="link"
                                       href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                                </h5>
                            </div>

                            <div class="tf-product-info-badges">
                                @if($product->is_featured)
                                    <div class="badges text-uppercase">Best seller</div>
                                @endif
                                @if($product->sale_price)
                                    <div class="product-status-content">
                                        <i class="icon-lightning"></i>
                                        <p class="fw-6">Selling fast! 48 people have this in their carts.</p>
                                    </div>
                                @endif
                            </div>

                            <div class="tf-product-info-price">
                                @if($product->sale_price)
                                    <div class="price text-decoration-line-through">{{ $this->originalPrice }}</div>
                                    <div class="price">{{ $this->formattedPrice }}</div>
                                @else
                                    <div class="price">{{ $this->formattedPrice }}</div>
                                @endif
                            </div>

                            @if($product->short_description)
                                <div class="tf-product-description">
                                    <p>{{ $product->short_description }}</p>
                                </div>
                            @endif

                            <div class="tf-product-info-variant-picker">
                                @if($product->colors && count($product->colors) > 0)
                                    <div class="variant-picker-item">
                                        <div class="variant-picker-label">
                                            Color: <span class="fw-6 variant-picker-label-value">{{ $selectedColor ?? $product->colors[0] }}</span>
                                        </div>
                                        <div class="variant-picker-values">
                                            @foreach($product->colors as $color)
                                                <input id="values-{{ strtolower($color) }}-1" type="radio" name="color-1"
                                                       value="{{ $color }}" wire:model="selectedColor"
                                                       @if($selectedColor === $color || (empty($selectedColor) && $loop->first)) checked @endif>
                                                <label class="hover-tooltip radius-60" for="values-{{ strtolower($color) }}-1" data-value="{{ $color }}">
                                                    <span class="btn-checkbox bg-color-{{ strtolower($color) }}"></span>
                                                    <span class="tooltip">{{ $color }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if($product->sizes && count($product->sizes) > 0)
                                    <div class="variant-picker-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="variant-picker-label">
                                                Size: <span class="fw-6 variant-picker-label-value">{{ $selectedSize ?? $product->sizes[0] }}</span>
                                            </div>
                                            <div class="find-size btn-choose-size fw-6">Find your size</div>
                                        </div>
                                        <div class="variant-picker-values">
                                            @foreach($product->sizes as $size)
                                                <input type="radio" name="size-1" id="values-{{ strtolower($size) }}-1"
                                                       value="{{ $size }}" wire:model="selectedSize"
                                                       @if($selectedSize === $size || (empty($selectedSize) && $loop->first)) checked @endif>
                                                <label class="style-text" for="values-{{ strtolower($size) }}-1" data-value="{{ $size }}">
                                                    <p>{{ $size }}</p>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="tf-product-info-quantity">
                                <div class="quantity-title fw-6">Quantity</div>
                                <div class="wg-quantity">
                                    <span class="btn-quantity minus-btn" wire:click="decrementQuantity">-</span>
                                    <input type="text" name="number" value="{{ $quantity }}" readonly>
                                    <span class="btn-quantity plus-btn" wire:click="incrementQuantity">+</span>
                                </div>
                            </div>

                            <div class="tf-product-info-buy-button">
                                <form class="">
                                    <a href="javascript:void(0);"
                                       class="tf-btn btn-fill justify-content-center fw-6 fs-16 flex-grow-1 animate-hover-btn btn-add-to-cart"
                                       wire:click="addToCart">
                                        <span>Add to cart -&nbsp;</span>
                                        <span class="tf-qty-price">{{ $this->formattedPrice }}</span>
                                    </a>
                                    <a href="javascript:void(0);"
                                       class="tf-product-btn-wishlist hover-tooltip box-icon bg_white wishlist btn-icon-action"
                                       wire:click="toggleWishlist">
                                        <span class="icon icon-heart"></span>
                                        <span class="tooltip">Add to Wishlist</span>
                                        <span class="icon icon-delete"></span>
                                    </a>
                                    <a href="javascript:void(0);"
                                       class="tf-product-btn-wishlist hover-tooltip box-icon bg_white compare btn-icon-action"
                                       wire:click="toggleCompare">
                                        <span class="icon icon-compare"></span>
                                        <span class="tooltip">Add to Compare</span>
                                        <span class="icon icon-check"></span>
                                    </a>
                                    <div class="w-100">
                                        <a href="#" class="btns-full">Buy with <img src="{{ asset('images/payments/paypal.png') }}"
                                                                                    alt=""></a>
                                        <a href="#" class="payment-more-option">More payment options</a>
                                    </div>
                                </form>
                            </div>
                            <div>
                                <a href="{{ route('products.show', $product->slug) }}" class="tf-btn fw-6 btn-line">View
                                    full details<i class="icon icon-arrow1-top-left"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

</div>
