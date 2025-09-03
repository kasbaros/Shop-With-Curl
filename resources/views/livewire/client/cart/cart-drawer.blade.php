<div
    class="modal fullRight fade modal-shopping-cart"
    id="shoppingCart"
    tabindex="-1"
    aria-labelledby="shoppingCartLabel"
    aria-hidden="true"
    wire:ignore.self
>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="header">
                <div class="title fw-5">Shopping cart</div>
                <span class="icon-close icon-close-popup" data-bs-dismiss="modal"></span>
            </div>
            <div class="wrap">
                @if(count($cart) > 0)
                    <div class="tf-mini-cart-threshold">
                        <div class="tf-progress-bar">
                            <span style="width: {{ $this->freeShippingProgress }}%;">
                                <div class="progress-car"></div>
                            </span>
                        </div>
                        <div class="tf-progress-msg">
                            @if ($this->qualifiesForFreeShipping)
                                🎉 You qualify for <span class="fw-6">Free Shipping</span>
                            @else
                                Buy <span class="price fw-6">${{ number_format($this->freeShippingRemaining, 2) }}</span> more to enjoy <span class="fw-6">Free Shipping</span>
                            @endif
                        </div>
                    </div>
                @endif
                <div class="tf-mini-cart-wrap">
                    <div class="tf-mini-cart-main">
                        <div class="tf-mini-cart-sroll">
                            <div class="tf-mini-cart-items">
                                @forelse($cart as $key => $item)
                                    <div class="tf-mini-cart-item">
                                        <div class="tf-mini-cart-image">
                                            <a href="{{ route('products.show', $item['product_slug'] ?? '#') }}">
                                                <img src="{{ $item['image'] ?? asset('images/placeholder-product.jpg') }}" alt="{{ $item['name'] ?? 'Item' }}">
                                            </a>
                                        </div>
                                        <div class="tf-mini-cart-info">
                                            <a class="title link" href="{{ route('products.show', $item['product_slug'] ?? '#') }}">{{ $item['name'] ?? 'T-shirt' }}</a>
                                            @if(isset($item['variant_name']) && $item['variant_name'])
                                                <div class="meta-variant">{{ $item['variant_name'] }}</div>
                                            @endif
                                            <div class="price fw-6">{{ money_format_ugx($item['price'] ?? 0) }}</div>
                                            <div class="tf-mini-cart-btns">
                                                <div class="wg-quantity small">
                                                    <span class="btn-quantity minus-btn" wire:click="updateQuantity('{{ $key }}', {{ $item['quantity'] - 1 }})">-</span>
                                                    <input type="text" name="number" value="{{ $item['quantity'] }}" readonly>
                                                    <span class="btn-quantity plus-btn" wire:click="updateQuantity('{{ $key }}', {{ $item['quantity'] + 1 }})">+</span>
                                                </div>
                                                <div class="tf-mini-cart-remove" wire:click="removeItem('{{ $key }}')">Remove</div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <p class="text-gray-500">Your cart is empty</p>
                                    </div>
                                @endforelse
                            </div>

                            @if(count($recommendations) > 0)
                                <div class="tf-minicart-recommendations">
                                    <div class="tf-minicart-recommendations-heading">
                                        <div class="tf-minicart-recommendations-title">You may also like</div>
                                    </div>
                                    <div class="swiper tf-cart-slide">
                                        <div class="swiper-wrapper">
                                            @foreach($recommendations as $product)
                                                <div class="swiper-slide">
                                                    <div class="tf-minicart-recommendations-item">
                                                        <div class="tf-minicart-recommendations-item-image">
                                                            <a href="{{ route('products.show', $product['slug']) }}">
                                                                <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}">
                                                            </a>
                                                        </div>
                                                        <div class="tf-minicart-recommendations-item-infos flex-grow-1">
                                                            <a class="title" href="{{ route('products.show', $product['slug']) }}">{{ $product['name'] }}</a>
                                                            <div class="price">{{ money_format_ugx($product['price']) }}</div>
                                                        </div>
                                                        <div class="tf-minicart-recommendations-item-quickview">
                                                            <button class="btn-show-quickview quickview hover-tooltip" wire:click="$dispatch('product:quickAdd', { productId: {{ $product['id'] }} })">
                                                                <span class="icon icon-bag"></span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    @if(count($cart) > 0)
                        <div class="tf-mini-cart-bottom">
                            <div class="tf-mini-cart-tool">
                                <div class="tf-mini-cart-tool-btn btn-add-note" wire:click="toggleAddNote"></div>
                                <div class="tf-mini-cart-tool-btn btn-add-gift" wire:click="toggleAddGift"></div>
                                <div class="tf-mini-cart-tool-btn btn-estimate-shipping" wire:click="toggleEstimateShipping"></div>
                            </div>
                            <div class="tf-mini-cart-bottom-wrap">
                                <div class="tf-cart-totals-discounts">
                                    <div class="tf-cart-total">Subtotal</div>
                                    <div class="tf-totals-total-value fw-6">{{ $this->formattedTotal }}</div>
                                </div>
                                <div class="tf-cart-tax">Taxes and <a href="#">shipping</a> calculated at checkout</div>
                                <div class="tf-mini-cart-line"></div>
                                <div class="tf-cart-checkbox">
                                    <div class="tf-checkbox-wrapp">
                                        <input type="checkbox" id="CartDrawer-Form_agree" wire:model.live="agreeToTerms">
                                        <div><i class="icon-check"></i></div>
                                    </div>
                                    <label for="CartDrawer-Form_agree">I agree with the <a href="#" title="Terms of Service">terms and conditions</a></label>
                                </div>
                                <div class="tf-mini-cart-view-checkout">
                                    <a href="{{ route('cart.index') }}" class="tf-btn btn-outline radius-3 link w-100 justify-content-center">View cart</a>
                                    <a href="{{ route('checkout.index') }}" class="tf-btn btn-fill animate-hover-btn radius-3 w-100 justify-content-center @if(!$agreeToTerms) disabled @endif">
                                        <span>Check out</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    @include('livewire.components.shopping-cart.modals.add-note')
                    @include('livewire.components.shopping-cart.modals.add-gift')
                    @include('livewire.components.shopping-cart.modals.estimate-shipping')
                </div>
            </div>
        </div>
    </div>
</div>
