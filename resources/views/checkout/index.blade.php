<x-app-layout>
    <x-slot name="title">Checkout - ShopWithCarl</x-slot>

    <div class="tf-page-title">
        <div class="container-full">
            <div class="heading text-center">Checkout</div>
        </div>
    </div>

    <section class="flat-spacing-11">
        <div class="container">
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="row g-5">
                <div class="col-lg-8">
                    <div class="card border shadow-sm">
                        <div class="card-header bg-light border-bottom">
                            <h5 class="fw-6 mb-0">Shipping & Billing</h5>
                        </div>
                        <div class="card-body">
                            @if($addresses->isEmpty())
                                <div class="alert alert-warning">
                                    You have no saved addresses. Please add your address in your account first.
                                    <a href="{{ route('account.page', ['section' => 'address']) }}" class="alert-link">Manage Addresses</a>
                                </div>
                            @endif

                            <form action="{{ route('checkout.store') }}" method="post">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-6">Shipping Address</label>
                                        <select name="shipping_address_id" class="form-select" required>
                                            <option value="" disabled selected>Select Shipping Address</option>
                                            @foreach($addresses as $address)
                                                <option value="{{ $address->id }}" @selected(old('shipping_address_id')==$address->id)>
                                                    {{ $address->label ?? 'Address' }} — {{ $address->full_address['line1'] ?? '' }} {{ $address->full_address['city'] ?? '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('shipping_address_id')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-6">Billing Address</label>
                                        <select name="billing_address_id" class="form-select" required>
                                            <option value="" disabled selected>Select Billing Address</option>
                                            @foreach($addresses as $address)
                                                <option value="{{ $address->id }}" @selected(old('billing_address_id')==$address->id)>
                                                    {{ $address->label ?? 'Address' }} — {{ $address->full_address['line1'] ?? '' }} {{ $address->full_address['city'] ?? '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('billing_address_id')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-6">Order Notes (Optional)</label>
                                        <textarea name="notes" rows="3" class="form-control" placeholder="Delivery notes, directions, etc.">{{ old('notes') }}</textarea>
                                        @error('notes')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div>
                                    <h6 class="fw-6 mb-3">Payment Method</h6>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="payment_method" id="pm_cod" value="cash_on_delivery" checked>
                                        <label class="form-check-label" for="pm_cod">
                                            Cash on Delivery (Pay when you receive your order)
                                        </label>
                                    </div>
                                    <div class="form-check mb-2 disabled">
                                        <input class="form-check-input" type="radio" name="payment_method" id="pm_card" value="credit_card" disabled>
                                        <label class="form-check-label text-muted" for="pm_card">
                                            Credit/Debit Card (Coming soon)
                                        </label>
                                    </div>
                                    <div class="form-check mb-2 disabled">
                                        <input class="form-check-input" type="radio" name="payment_method" id="pm_paypal" value="paypal" disabled>
                                        <label class="form-check-label text-muted" for="pm_paypal">
                                            PayPal (Coming soon)
                                        </label>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="tf-btn btn-fill animate-hover-btn px-4 py-3">
                                        <span class="fw-6">Place Order</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="position-sticky" style="top: 20px;">
                        <div class="card border shadow-sm">
                            <div class="card-header bg-light border-bottom">
                                <h5 class="fw-6 mb-0">Order Summary</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Subtotal</span>
                                    <span class="fw-6">{{ money_format_ugx((float)$subtotal) }}</span>
                                </div>
                                @if(($appliedCoupon['code'] ?? null))
                                    <div class="d-flex justify-content-between mb-2 text-success">
                                        <span>Coupon ({{ $appliedCoupon['code'] }})</span>
                                        <span>- {{ money_format_ugx((float)$discount) }}</span>
                                    </div>
                                @endif
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Shipping</span>
                                    <span>{{ money_format_ugx((float)$shipping) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Delivery</span>
                                    <span>{{ money_format_ugx((float)$tax) }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-6 fs-5">Total</span>
                                    <span class="fw-6 fs-5 text-primary">{{ money_format_ugx((float)$total) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 p-3 border rounded bg-light">
                            <h6 class="fw-6 mb-2">Security</h6>
                            <small class="text-muted">
                                <i class="icon-lock me-1"></i> Your information is securely processed.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
