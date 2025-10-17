<div>
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
                            <div dir="ltr" class="swiper tf-single-slide" id="productQuickViewSwiper">
                                <div class="swiper-wrapper">
                                    @if($this->currentVariant && $this->currentVariant->image_url)
                                        <div class="swiper-slide" role="group" aria-label="1 / 1">
                                            <div class="item">
                                                <img src="{{ $this->imageUrl }}" alt="{{ $product->name }}">
                                            </div>
                                        </div>
                                    @elseif($product->images && count($product->images) > 0)
                                        @foreach($product->images as $index => $image)
                                            <div class="swiper-slide" role="group"
                                                 aria-label="{{ $index + 1 }} / {{ count($product->images) }}">
                                                <div class="item">
                                                    <img src="{{ $image['large'] ?? $image['original'] }}"
                                                         alt="{{ $product->name }}">
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="swiper-slide" role="group" aria-label="1 / 1">
                                            <div class="item">
                                                <img src="{{ $this->imageUrl }}" alt="{{ $product->name }}">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                @if($product->images && count($product->images) > 1)
                                    <div class="swiper-button-next button-style-arrow single-slide-next" tabindex="0"
                                         role="button" aria-label="Next slide"></div>
                                    <div class="swiper-button-prev button-style-arrow single-slide-prev" tabindex="-1"
                                         role="button" aria-label="Previous slide"></div>
                                @endif
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

                                @if($product->variants()->exists())
                                    <div class="tf-product-info-variant-picker">
                                        <!-- Color Selection -->
                                        @if($this->availableColors->isNotEmpty())
                                            <div class="variant-picker-item">
                                                <div class="variant-picker-label">
                                                    Color: <span
                                                        class="fw-6 variant-picker-label-value">{{ $selectedColor ?? 'Please select' }}</span>
                                                </div>
                                                <div class="variant-picker-values">
                                                    @foreach($this->availableColors as $colorObj)
                                                        <input
                                                            id="values-{{ strtolower(str_replace(' ', '-', $colorObj['name'])) }}-qv"
                                                            type="radio" name="color-qv"
                                                            value="{{ $colorObj['name'] }}" wire:model.live="selectedColor"
                                                            @if($selectedColor === $colorObj['name']) checked @endif>
                                                        <label class="hover-tooltip radius-60"
                                                               for="values-{{ strtolower(str_replace(' ', '-', $colorObj['name'])) }}-qv"
                                                               data-value="{{ $colorObj['name'] }}">
                                                            <span
                                                                class="btn-checkbox" style="background-color: {{ $colorObj['hex_code'] }} !important;"></span>
                                                            <span class="tooltip">{{ $colorObj['name'] }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Size Selection -->
                                        @if($this->availableSizes->isNotEmpty())
                                            <div class="variant-picker-item">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="variant-picker-label">
                                                        Size: <span
                                                            class="fw-6 variant-picker-label-value">{{ $selectedSize ?? 'Please select' }}</span>
                                                    </div>
                                                    <div class="find-size btn-choose-size fw-6">Find your size</div>
                                                </div>
                                                <div class="variant-picker-values">
                                                    @foreach($this->availableSizes as $size)
                                                        <input type="radio" name="size-qv"
                                                               id="size-{{ strtolower(str_replace(' ', '-', $size)) }}-qv"
                                                               value="{{ $size }}" wire:model.live="selectedSize"
                                                               @if($selectedSize === $size) checked @endif>
                                                        <label class="style-text"
                                                               for="size-{{ strtolower(str_replace(' ', '-', $size)) }}-qv"
                                                               data-value="{{ $size }}">
                                                            <p>{{ $size }}</p>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Material Selection -->
                                        @if($this->availableMaterials->isNotEmpty())
                                            <div class="variant-picker-item">
                                                <div class="variant-picker-label">
                                                    Material: <span
                                                        class="fw-6 variant-picker-label-value">{{ $selectedMaterial ?? 'Please select' }}</span>
                                                </div>
                                                <div class="variant-picker-values">
                                                    @foreach($this->availableMaterials as $material)
                                                        <input type="radio" name="material-qv"
                                                               id="material-{{ strtolower(str_replace(' ', '-', $material)) }}-qv"
                                                               value="{{ $material }}"
                                                               wire:model.live="selectedMaterial"
                                                               @if($selectedMaterial === $material) checked @endif>
                                                        <label class="style-text"
                                                               for="material-{{ strtolower(str_replace(' ', '-', $material)) }}-qv"
                                                               data-value="{{ $material }}">
                                                            <p>{{ $material }}</p>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Variant Info -->
                                        @if($this->currentVariant)
                                            <div class="bg-light p-3 rounded mt-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small
                                                        class="text-muted">Selected: {{ $this->currentVariant->display_name }}</small>
                                                    <small
                                                        class="fw-bold">Stock: {{ $this->currentVariant->stock_quantity }}</small>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif

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
{{--                                        <div class="w-100">--}}
{{--                                            <a href="#" class="btns-full">Buy with <img--}}
{{--                                                    src="{{ asset('images/payments/paypal.png') }}"--}}
{{--                                                    alt=""></a>--}}
{{--                                            <a href="#" class="payment-more-option">More payment options</a>--}}
{{--                                        </div>--}}
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Swiper when modal is shown
            document.addEventListener('livewire:initialized', function () {
                Livewire.on('product:quickViewReady', function () {
                    setTimeout(function () {
                        const swiperEl = document.getElementById('productQuickViewSwiper');
                        if (swiperEl && !swiperEl.swiper) {
                            new Swiper('#productQuickViewSwiper', {
                                loop: true,
                                navigation: {
                                    nextEl: '.swiper-button-next',
                                    prevEl: '.swiper-button-prev',
                                },
                                pagination: {
                                    el: '.swiper-pagination',
                                    clickable: true,
                                },
                                autoplay: false,
                                speed: 300,
                                effect: 'slide',
                            });
                        }
                    }, 100);
                });
            });
        });
    </script>

</div>
