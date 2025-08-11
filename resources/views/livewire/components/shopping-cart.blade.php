<div>
    <!-- Cart Modal -->
    @if($showCart)
        <div class="modal fullRight fade show d-block modal-shopping-cart">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="header">
                        <div class="title fw-5">Shopping cart</div>
                        <span class="icon-close icon-close-popup" wire:click="closeCart"></span>
                    </div>
                    <div class="wrap">
                        @if($cartItems->isNotEmpty())
                            <div class="tf-mini-cart-wrap">
                                <div class="tf-mini-cart-main">
                                    <div class="tf-mini-cart-sroll">
                                        <div class="tf-mini-cart-items">
                                            @foreach($cartItems as $key => $item)
                                                <div class="tf-mini-cart-item" wire:key="cart-item-{{ $key }}">
                                                    <div class="tf-mini-cart-image">
                                                        <a href="{{ route('products.show', $item['product_slug']) }}">
                                                            <img src="{{ $item['product_image'] ?: asset('images/placeholder-product.jpg') }}" alt="{{ $item['product_name'] }}">
                                                        </a>
                                                    </div>
                                                    <div class="tf-mini-cart-info">
                                                        <a class="title link" href="{{ route('products.show', $item['product_slug']) }}">
                                                            {{ $item['product_name'] }}
                                                        </a>

                                                        @if(!empty($item['variants']))
                                                            <div class="meta-variant">
                                                                @foreach($item['variants'] as $variant => $value)
                                                                    {{ ucfirst($variant) }}: {{ $value }}
                                                                @endforeach
                                                            </div>
                                                        @endif

                                                        <div class="price fw-6">
                                                            @php
                                                                $price = is_int($item['price']) || ctype_digit((string)$item['price'])
                                                                    ? $item['price'] / 100
                                                                    : (float)$item['price'];
                                                                $formatted = config('app.currency_symbol', '$') . number_format($price, 2);
                                                            @endphp
                                                            {{ $formatted }}
                                                        </div>

                                                        <div class="tf-mini-cart-btns">
                                                            <div class="wg-quantity small">
                                                                <span class="btn-quantity minus-btn"
                                                                      wire:click="$dispatch('cart:update', {itemKey: '{{ $key }}', quantity: {{ max(1, $item['quantity'] - 1) }}})">-</span>
                                                                <input type="text" name="number" value="{{ $item['quantity'] }}" readonly>
                                                                <span class="btn-quantity plus-btn"
                                                                      wire:click="$dispatch('cart:update', {itemKey: '{{ $key }}', quantity: {{ $item['quantity'] + 1 }}})">+</span>
                                                            </div>
                                                            <div class="tf-mini-cart-remove"
                                                                 wire:click="$dispatch('cart:remove', {itemKey: '{{ $key }}'})">Remove</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="tf-mini-cart-bottom">
                                    <div class="tf-mini-cart-bottom-wrap">
                                        <div class="tf-cart-totals-discounts">
                                            <div class="tf-cart-total">Subtotal</div>
                                            <div class="tf-totals-total-value fw-6">{{ $this->cartSubtotal }}</div>
                                        </div>

                                        <div class="tf-cart-tax">Taxes and <a href="#">shipping</a> calculated at checkout</div>
                                        <div class="tf-mini-cart-line"></div>

                                        <div class="tf-mini-cart-view-checkout">
                                            <a href="{{ route('cart.index') }}"
                                               class="tf-btn btn-outline radius-3 link w-100 justify-content-center"
                                               wire:click="closeCart">View cart</a>
                                            <a href="{{ route('checkout.index') }}"
                                               class="tf-btn btn-fill animate-hover-btn radius-3 w-100 justify-content-center"
                                               wire:click="closeCart">
                                               <span>Check out</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="tf-mini-cart-wrap">
                                <div class="tf-mini-cart-main text-center py-5">
                                    <div class="mb-3">
                                        <i class="icon-bag" style="font-size: 3rem; color: #ccc;"></i>
                                    </div>
                                    <h6 class="mb-2">Your cart is empty</h6>
                                    <p class="text-muted mb-4">Looks like you haven't added any items to your cart yet.</p>
                                    <a href="{{ route('products.index') }}"
                                       class="tf-btn btn-fill animate-hover-btn radius-3"
                                       wire:click="closeCart">
                                        <span>Start Shopping</span>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
