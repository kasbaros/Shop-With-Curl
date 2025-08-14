{{--<div>--}}
{{--    @if($showModal && $product)--}}
{{--        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">--}}
{{--            <div class="modal-dialog modal-dialog-centered">--}}
{{--                <div class="modal-content">--}}
{{--                    <div class="header border-bottom p-3 d-flex justify-content-between align-items-center">--}}
{{--                        <h6 class="mb-0">Quick Add</h6>--}}
{{--                        <button type="button" class="btn-close" wire:click="closeModal"></button>--}}
{{--                    </div>--}}
{{--                    <div class="modal-body">--}}
{{--                        <div class="wrap">--}}
{{--                            <div class="tf-product-info-item d-flex gap-3 mb-4">--}}
{{--                                <div class="image">--}}
{{--                                    <img src="{{ $this->imageUrl }}"--}}
{{--                                         alt="{{ $product->name }}"--}}
{{--                                         style="width: 80px; height: 80px; object-fit: cover;"--}}
{{--                                         class="rounded">--}}
{{--                                </div>--}}
{{--                                <div class="content flex-grow-1">--}}
{{--                                    <h6 class="mb-1">--}}
{{--                                        <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none">--}}
{{--                                            {{ $product->name }}--}}
{{--                                        </a>--}}
{{--                                    </h6>--}}
{{--                                    <div class="tf-product-info-price">--}}
{{--                                        <div class="fw-6 text-primary">{{ $this->formattedPrice }}</div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <!-- Quantity -->--}}
{{--                            <div class="tf-product-info-quantity mb-4">--}}
{{--                                <div class="fw-6 mb-2">Quantity</div>--}}
{{--                                <div class="input-group" style="width: 140px;">--}}
{{--                                    <button class="btn btn-outline-secondary" type="button" wire:click="decrementQuantity">-</button>--}}
{{--                                    <input type="text" class="form-control text-center" readonly value="{{ $quantity }}">--}}
{{--                                    <button class="btn btn-outline-secondary" type="button" wire:click="incrementQuantity">+</button>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <!-- Action Button -->--}}
{{--                            <div class="tf-product-info-buy-button">--}}
{{--                                <button type="button"--}}
{{--                                        class="btn btn-primary w-100 fw-6 mb-3"--}}
{{--                                        wire:click="addToCart">--}}
{{--                                    Add to cart - <span class="tf-qty-price">{{ $this->formattedPrice }}</span>--}}
{{--                                </button>--}}

{{--                                <div class="text-center">--}}
{{--                                    <a href="{{ route('products.show', $product->slug) }}"--}}
{{--                                       class="btn btn-link">--}}
{{--                                        View full details--}}
{{--                                        <i class="icon icon-arrow1-top-left ms-1"></i>--}}
{{--                                    </a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    @endif--}}
{{--</div>--}}


<div>
    @if($showModal && $product)
        <div class="modal fade modalDemo popup-quickadd show d-block" id="quick_add" x-data="{ color: '{{ $selectedColor ?? '' }}', size: '{{ $selectedSize ?? '' }}' }" wire:model.debounce.500ms="selectedColor, selectedSize, quantity" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="header">
                        <span class="icon-close icon-close-popup" wire:click="closeModal"></span>
                    </div>
                    <div class="wrap">
                        <div class="tf-product-info-item">
                            <div class="image">
                                <img src="{{ $this->imageUrl }}" alt="{{ $product->name }}">
                            </div>
                            <div class="content">
                                <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                                <div class="tf-product-info-price">
                                    <div class="price">{{ $this->formattedPrice }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="tf-product-info-variant-picker mb_15">
                            <div class="variant-picker-item">
                                <div class="variant-picker-label">
                                    Color: <span class="fw-6 variant-picker-label-value" x-text="color"></span>
                                </div>
                                <div class="variant-picker-values">
                                    @foreach (['Orange', 'Black', 'White'] as $color)
                                        <input id="values-{{ strtolower($color) }}" type="radio" name="color" value="{{ $color }}" wire:model="selectedColor" x-model="color">
                                        <label class="hover-tooltip radius-60" for="values-{{ strtolower($color) }}" data-value="{{ $color }}">
                                            <span class="btn-checkbox bg-color-{{ strtolower($color) }}"></span>
                                            <span class="tooltip">{{ $color }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="variant-picker-item">
                                <div class="variant-picker-label">
                                    Size: <span class="fw-6 variant-picker-label-value" x-text="size"></span>
                                </div>
                                <div class="variant-picker-values">
                                    @foreach (['S', 'M', 'L', 'XL'] as $size)
                                        <input type="radio" name="size" id="values-{{ strtolower($size) }}" value="{{ $size }}" wire:model="selectedSize" x-model="size">
                                        <label class="style-text" for="values-{{ strtolower($size) }}" data-value="{{ $size }}">
                                            <p>{{ $size }}</p>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="tf-product-info-quantity mb_15">
                            <div class="quantity-title fw-6">Quantity</div>
                            <div class="wg-quantity">
                                <span class="btn-quantity minus-btn" wire:click="decrementQuantity">-</span>
                                <input type="text" name="number" value="{{ $quantity }}" readonly>
                                <span class="btn-quantity plus-btn" wire:click="incrementQuantity">+</span>
                            </div>
                        </div>
                        <div class="tf-product-info-buy-button">
                            <div>
                                <a href="javascript:void(0);"
                                   class="tf-btn btn-fill justify-content-center fw-6 fs-16 flex-grow-1 animate-hover-btn btn-add-to-cart"
                                   wire:click="addToCart">
                                    <span>Add to cart -&nbsp;</span><span class="tf-qty-price">{{ $this->formattedPrice }}</span>
                                </a>
                                <div class="tf-product-btn-wishlist btn-icon-action">
                                    <i class="icon-heart"></i>
                                    <i class="icon-delete"></i>
                                </div>
                                <a href="#compare" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft"
                                   class="tf-product-btn-wishlist box-icon bg_white compare btn-icon-action">
                                    <span class="icon icon-compare"></span>
                                    <span class="icon icon-check"></span>
                                </a>
                                <div class="w-100">
                                    <a href="#" class="btns-full">Buy with <img src="{{ asset('images/payments/paypal.png') }}" alt="PayPal"></a>
                                    <a href="#" class="payment-more-option">More payment options</a>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('products.show', $product->slug) }}" class="tf-btn fw-6 btn-line">View full details<i class="icon icon-arrow1-top-left"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
