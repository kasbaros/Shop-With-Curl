@php
    $currency = config('app.currency_symbol', '$');
    $subtotal = $subtotal ?? collect($cart ?? [])->sum('total');
@endphp

@if(empty($cart))
    <div class="text-center py-5 border rounded bg-light">
        <span class="icon icon-bag d-inline-block mb-3" style="font-size:40px"></span>
        <h6 class="fw-6 mb-2">Your cart is empty</h6>
        <p class="text-muted mb-3">Looks like you haven't added any items to your cart yet.</p>
        <a href="{{ route('products.index') }}" class="tf-btn btn-fill animate-hover-btn rounded-0 justify-content-center">Start Shopping</a>
    </div>
@else
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                    <tr>
                        <th>Product</th>
                        <th class="text-center" style="width: 160px;">Quantity</th>
                        <th class="text-end">Price</th>
                        <th class="text-end">Total</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($cart as $key => $item)
                        @php
                            $price = (float)$item['price'];
                            $total = (float)$item['total'];
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <a href="{{ route('products.show', $item['product']->slug) }}" class="d-inline-block" style="width: 64px; height:64px;">
                                        <img src="{{ \App\Helpers\ImageStorageHelper::url($item['product']->featured_image ?? null) }}" alt="{{ $item['name'] }}" class="img-fluid rounded object-fit-cover" style="width: 64px; height:64px;">
                                    </a>
                                    <div>
                                        <a href="{{ route('products.show', $item['product']->slug) }}" class="fw-6 text-decoration-none">{{ $item['name'] }}</a>
                                        @if($item['variant'])
                                            <div class="text-muted small">Variant: {{ $item['variant']->display_name }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('cart.update', $key) }}" method="post" class="d-inline-flex align-items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control" style="width: 80px;">
                                    <button type="submit" class="btn btn-sm btn-outline-secondary">Update</button>
                                </form>
                            </td>
                            <td class="text-end">{{ $currency . number_format($price, 2) }}</td>
                            <td class="text-end">{{ $currency . number_format($total, 2) }}</td>
                            <td class="text-end">
                                <form action="{{ route('cart.remove', $key) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between mt-3">
                <form action="{{ route('cart.clear') }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">Clear Cart</button>
                </form>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Continue Shopping</a>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-6 mb-3">Order Summary</h6>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span class="fw-6">{{ $currency . number_format((float)$subtotal, 2) }}</span>
                    </div>

                    <div class="text-muted small mb-3">Taxes and shipping calculated at checkout.</div>

                    <form action="{{ route('cart.coupon.apply') }}" method="post" class="d-flex gap-2 mb-3">
                        @csrf
                        <input type="text" name="coupon_code" class="form-control" placeholder="Coupon code" required>
                        <button class="btn btn-outline-primary" type="submit">Apply</button>
                    </form>

                    <a href="{{ route('checkout.index') }}" class="tf-btn btn-fill animate-hover-btn w-100 justify-content-center">
                        <span>Proceed to Checkout</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif
