<x-app-layout>
    <x-slot name="title">My Cart - ShopWithCarl</x-slot>

    <div class="tf-page-title">
        <div class="container-full">
            <div class="heading text-center">My Cart</div>
        </div>
    </div>

    <section class="flat-spacing-11">
        <div class="container">
            {{--            @php--}}
            {{--                $currency = config('app.currency_symbol', '$');--}}
            {{--                $applied = session('applied_coupon');--}}
            {{--                $subtotalFormatted = $currency . number_format((float)$subtotal, 2);--}}
            {{--            @endphp--}}

            {{--            @if(empty($cart))--}}
            {{--                <div class="text-center py-5 border rounded bg-light">--}}
            {{--                    <span class="icon icon-bag d-inline-block mb-3" style="font-size:40px"></span>--}}
            {{--                    <h6 class="fw-6 mb-2">Your cart is empty</h6>--}}
            {{--                    <p class="text-muted mb-3">Looks like you haven't added any items to your cart yet.</p>--}}
            {{--                    <a href="{{ route('products.index') }}" class="tf-btn btn-fill animate-hover-btn rounded-0 justify-content-center">Start Shopping</a>--}}
            {{--                </div>--}}
            {{--            @else--}}
            {{--                <div class="row g-4">--}}
            {{--                    <div class="col-lg-8">--}}
            {{--                        <div class="table-responsive">--}}
            {{--                            <table class="table align-middle">--}}
            {{--                                <thead>--}}
            {{--                                <tr>--}}
            {{--                                    <th>Product</th>--}}
            {{--                                    <th class="text-center" style="width: 160px;">Quantity</th>--}}
            {{--                                    <th class="text-end">Price</th>--}}
            {{--                                    <th class="text-end">Total</th>--}}
            {{--                                    <th></th>--}}
            {{--                                </tr>--}}
            {{--                                </thead>--}}
            {{--                                <tbody>--}}
            {{--                                @foreach($cart as $key => $item)--}}
            {{--                                    @php--}}
            {{--                                        $price = (float)$item['price'];--}}
            {{--                                        $total = (float)$item['total'];--}}
            {{--                                    @endphp--}}
            {{--                                    <tr>--}}
            {{--                                        <td>--}}
            {{--                                            <div class="d-flex align-items-center gap-3">--}}
            {{--                                                <a href="{{ route('products.show', $item['product']->slug) }}" class="d-inline-block" style="width: 64px; height:64px;">--}}
            {{--                                                    <img src="{{ \App\Helpers\ImageStorageHelper::url($item['product']->featured_image ?? null) }}" alt="{{ $item['name'] }}" class="img-fluid rounded object-fit-cover" style="width: 64px; height:64px;">--}}
            {{--                                                </a>--}}
            {{--                                                <div>--}}
            {{--                                                    <a href="{{ route('products.show', $item['product']->slug) }}" class="fw-6 text-decoration-none">{{ $item['name'] }}</a>--}}
            {{--                                                    @if($item['variant'])--}}
            {{--                                                        <div class="text-muted small">Variant: {{ $item['variant']->display_name }}</div>--}}
            {{--                                                    @endif--}}
            {{--                                                </div>--}}
            {{--                                            </div>--}}
            {{--                                        </td>--}}
            {{--                                        <td class="text-center">--}}
            {{--                                            <form action="{{ route('cart.update', $key) }}" method="post" class="d-inline-flex align-items-center gap-2">--}}
            {{--                                                @csrf--}}
            {{--                                                @method('PATCH')--}}
            {{--                                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control" style="width: 80px;">--}}
            {{--                                                <button type="submit" class="btn btn-sm btn-outline-secondary">Update</button>--}}
            {{--                                            </form>--}}
            {{--                                        </td>--}}
            {{--                                        <td class="text-end">{{ $currency . number_format($price, 2) }}</td>--}}
            {{--                                        <td class="text-end">{{ $currency . number_format($total, 2) }}</td>--}}
            {{--                                        <td class="text-end">--}}
            {{--                                            <form action="{{ route('cart.remove', $key) }}" method="post">--}}
            {{--                                                @csrf--}}
            {{--                                                @method('DELETE')--}}
            {{--                                                <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>--}}
            {{--                                            </form>--}}
            {{--                                        </td>--}}
            {{--                                    </tr>--}}
            {{--                                @endforeach--}}
            {{--                                </tbody>--}}
            {{--                            </table>--}}
            {{--                        </div>--}}

            {{--                        <div class="d-flex justify-content-between mt-3">--}}
            {{--                            <form action="{{ route('cart.clear') }}" method="post">--}}
            {{--                                @csrf--}}
            {{--                                @method('DELETE')--}}
            {{--                                <button type="submit" class="btn btn-outline-danger">Clear Cart</button>--}}
            {{--                            </form>--}}
            {{--                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Continue Shopping</a>--}}
            {{--                        </div>--}}
            {{--                    </div>--}}

            {{--                    <div class="col-lg-4">--}}
            {{--                        <div class="card border-0 shadow-sm">--}}
            {{--                            <div class="card-body">--}}
            {{--                                <h6 class="fw-6 mb-3">Order Summary</h6>--}}

            {{--                                <div class="d-flex justify-content-between mb-2">--}}
            {{--                                    <span>Subtotal</span>--}}
            {{--                                    <span class="fw-6">{{ $subtotalFormatted }}</span>--}}
            {{--                                </div>--}}

            {{--                                @if($applied)--}}
            {{--                                    <div class="d-flex justify-content-between mb-2 text-success">--}}
            {{--                                        <span>Coupon ({{ $applied['code'] }})</span>--}}
            {{--                                        <span>- {{ $currency . number_format((float)$applied['discount'], 2) }}</span>--}}
            {{--                                    </div>--}}
            {{--                                @endif--}}

            {{--                                <div class="text-muted small mb-3">Taxes and shipping calculated at checkout.</div>--}}

            {{--                                <form action="{{ route('cart.coupon.apply') }}" method="post" class="d-flex gap-2 mb-3">--}}
            {{--                                    @csrf--}}
            {{--                                    <input type="text" name="coupon_code" class="form-control" placeholder="Coupon code" required>--}}
            {{--                                    <button class="btn btn-outline-primary" type="submit">Apply</button>--}}
            {{--                                </form>--}}
            {{--                                @error('coupon_code')--}}
            {{--                                    <div class="text-danger small mb-2">{{ $message }}</div>--}}
            {{--                                @enderror--}}

            {{--                                @if($applied)--}}
            {{--                                    <form action="{{ route('cart.coupon.remove') }}" method="post" class="mb-3">--}}
            {{--                                        @csrf--}}
            {{--                                        @method('DELETE')--}}
            {{--                                        <button class="btn btn-sm btn-outline-danger" type="submit">Remove Coupon</button>--}}
            {{--                                    </form>--}}
            {{--                                @endif--}}

            {{--                                <a href="{{ route('checkout.index') }}" class="tf-btn btn-fill animate-hover-btn w-100 justify-content-center">--}}
            {{--                                    <span>Proceed to Checkout</span>--}}
            {{--                                </a>--}}
            {{--                            </div>--}}
            {{--                        </div>--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            {{--            @endif--}}

            @php
                $currency = config('app.currency_symbol', '$');
                $subtotal = $subtotal ?? collect($cart ?? [])->sum('total');
            @endphp

            @if(empty($cart))
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="text-center py-5 border rounded bg-light">
                            <span class="icon icon-bag d-inline-block mb-4 text-muted" style="font-size:60px"></span>
                            <h4 class="fw-6 mb-3">Your cart is empty</h4>
                            <p class="text-muted mb-4 fs-5">Looks like you haven't added any items to your cart yet.</p>
                            <a href="{{ route('products.index') }}"
                               class="tf-btn btn-fill animate-hover-btn rounded-0 justify-content-center px-4 py-3">
                                <span>Start Shopping</span>
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="row g-5">
                    <!-- Cart Items Section -->
                    <div class="col-lg-8">
                        <div class="mb-4">
                            <h3 class="fw-6 mb-1">Shopping Cart</h3>
                            <p class="text-muted mb-0">{{ count($cart) }} {{ Str::plural('item', count($cart)) }} in
                                your cart</p>
                        </div>

                        <div class="border rounded">
                            <div class="table-responsive">
                                <table class="table table-borderless align-middle mb-0">
                                    <thead class="border-bottom bg-light">
                                    <tr>
                                        <th class="fw-6 py-3 ps-4">Product</th>
                                        <th class="fw-6 py-3 text-center">Quantity</th>
                                        <th class="fw-6 py-3 text-end">Price</th>
                                        <th class="fw-6 py-3 text-end">Total</th>
                                        <th class="fw-6 py-3 pe-4"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($cart as $key => $item)
                                        @php
                                            $price = (float)$item['price'];
                                            $total = (float)$item['total'];
                                        @endphp
                                        <tr class="border-bottom">
                                            <td class="py-4 ps-4">
                                                <div class="d-flex align-items-center gap-3">
                                                    <a href="{{ route('products.show', $item['product']->slug) }}"
                                                       class="d-block border rounded overflow-hidden"
                                                       style="width: 80px; height: 80px;">
                                                        <img
                                                            src="{{ \App\Helpers\ImageStorageHelper::url($item['product']->featured_image ?? null) }}"
                                                            alt="{{ $item['name'] }}"
                                                            class="img-fluid object-fit-cover w-100 h-100">
                                                    </a>
                                                    <div class="flex-grow-1">
                                                        <a href="{{ route('products.show', $item['product']->slug) }}"
                                                           class="fw-6 text-decoration-none d-block mb-1 text-dark">
                                                            {{ $item['name'] }}
                                                        </a>
                                                        @if($item['variant'])
                                                            <div class="text-muted small mb-1">
                                                                <span
                                                                    class="fw-5">Variant:</span> {{ $item['variant']->display_name }}
                                                            </div>
                                                        @endif
                                                        <div class="text-muted small">
                                                            SKU: {{ $item['product']->sku ?? 'N/A' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center py-4">
                                                <form action="{{ route('cart.update', $key) }}" method="post"
                                                      class="d-inline-flex flex-column align-items-center gap-2">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="input-group" style="width: 100px;">
                                                        <input type="number"
                                                               name="quantity"
                                                               value="{{ $item['quantity'] }}"
                                                               min="1"
                                                               class="form-control text-center border">
                                                    </div>
                                                    <button type="submit" class="btn btn-sm btn-outline-primary fw-5">
                                                        Update
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="text-end py-4">
                                                <span class="fw-5">{{ $currency . number_format($price, 2) }}</span>
                                            </td>
                                            <td class="text-end py-4">
                                                <span
                                                    class="fw-6 fs-5">{{ $currency . number_format($total, 2) }}</span>
                                            </td>
                                            <td class="text-end py-4 pe-4">
                                                <form action="{{ route('cart.remove', $key) }}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger fw-5"
                                                            title="Remove item">
                                                        ×
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Cart Actions -->
                        <div class="d-flex justify-content-between align-items-center mt-4 py-3 border-top">
                            <form action="{{ route('cart.clear') }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger fw-5"
                                        onclick="return confirm('Are you sure you want to clear your cart?')">
                                    Clear Cart
                                </button>
                            </form>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary fw-5">
                                Continue Shopping
                            </a>
                        </div>
                    </div>

                    <!-- Order Summary Section -->
                    <div class="col-lg-4">
                        <div class="position-sticky" style="top: 20px;">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light border-bottom">
                                    <h5 class="fw-6 mb-0">Order Summary</h5>
                                </div>
                                <div class="card-body">
                                    <!-- Summary Details -->
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="text-muted">Items ({{ count($cart) }})</span>
                                            <span
                                                class="fw-5">{{ $currency . number_format((float)$subtotal, 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="text-muted">Shipping</span>
                                            <span class="fw-5">Calculated at checkout</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="text-muted">Tax</span>
                                            <span class="fw-5">Calculated at checkout</span>
                                        </div>
                                        <hr class="my-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-6 fs-5">Subtotal</span>
                                            <span
                                                class="fw-6 fs-4 text-primary">{{ $currency . number_format((float)$subtotal, 2) }}</span>
                                        </div>
                                    </div>

                                    <!-- Coupon Section -->
                                    <div class="mb-4 p-3 bg-light rounded">
                                        <h6 class="fw-6 mb-3">Have a coupon?</h6>
                                        <form action="{{ route('cart.coupon.apply') }}" method="post"
                                              class="d-flex gap-2">
                                            @csrf
                                            <input type="text"
                                                   name="coupon_code"
                                                   class="form-control"
                                                   placeholder="Enter coupon code"
                                                   required>
                                            <button class="btn btn-outline-primary fw-5" type="submit">
                                                Apply
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Checkout Button -->
                                    <div class="d-grid">
                                        <a href="{{ route('checkout.index') }}"
                                           class="tf-btn btn-fill animate-hover-btn justify-content-center py-3">
                                            <span class="fw-6">Proceed to Checkout</span>
                                        </a>
                                    </div>

                                    <!-- Security Notice -->
                                    <div class="text-center mt-3">
                                        <small class="text-muted">
                                            <i class="icon-lock me-1"></i>
                                            Secure checkout guaranteed
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Info -->
                            <div class="mt-4 p-3 border rounded bg-light">
                                <h6 class="fw-6 mb-2">Need Help?</h6>
                                <p class="small text-muted mb-2">Contact our customer service for any questions.</p>
                                <a href="#" class="small text-decoration-none">Get Support →</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
</x-app-layout>
