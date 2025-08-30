@extends('admin.layouts.app')

@section('title', 'Edit Order #' . $order->order_number)
@section('page-title', 'Edit Order')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0">Edit Order #{{ $order->order_number }}</h2>
            <p class="text-muted mb-0">{{ $order->created_at->format('M j, Y \a\t g:i A') }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-secondary">
                <i class="bi bi-eye me-1"></i> View
            </a>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Order Status & Details -->
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Order Information</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Order Status *</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="refunded" {{ $order->status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                            @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="payment_status" class="form-label">Payment Status</label>
                            <select class="form-select" id="payment_status" name="payment_status">
                                <option value="pending" {{ ($order->payment_status ?? 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ ($order->payment_status ?? 'pending') === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="failed" {{ ($order->payment_status ?? 'pending') === 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="refunded" {{ ($order->payment_status ?? 'pending') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tracking_number" class="form-label">Tracking Number</label>
                            <input type="text" class="form-control @error('tracking_number') is-invalid @enderror"
                                   id="tracking_number" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}">
                            @error('tracking_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="shipping_method" class="form-label">Shipping Method</label>
                            <input type="text" class="form-control @error('shipping_method') is-invalid @enderror"
                                   id="shipping_method" name="shipping_method" value="{{ old('shipping_method', $order->shipping_method) }}">
                            @error('shipping_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="shipping_cost" class="form-label">Shipping Cost</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control @error('shipping_cost') is-invalid @enderror"
                                       id="shipping_cost" name="shipping_cost" step="0.01" min="0"
                                       value="{{ old('shipping_cost', $order->shipping_cost) }}">
                                @error('shipping_cost')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select class="form-select" id="payment_method" name="payment_method">
                                <option value="">Select Method</option>
                                <option value="credit_card" {{ ($order->payment_method ?? '') === 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                <option value="debit_card" {{ ($order->payment_method ?? '') === 'debit_card' ? 'selected' : '' }}>Debit Card</option>
                                <option value="paypal" {{ ($order->payment_method ?? '') === 'paypal' ? 'selected' : '' }}>PayPal</option>
                                <option value="bank_transfer" {{ ($order->payment_method ?? '') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="cash_on_delivery" {{ ($order->payment_method ?? '') === 'cash_on_delivery' ? 'selected' : '' }}>Cash on Delivery</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Order Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                  id="notes" name="notes" rows="3"
                                  placeholder="Add any notes about this order...">{{ old('notes', $order->notes) }}</textarea>
                        @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Order Items (Read-only for now) -->
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Order Items</h5>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Order items cannot be modified after order creation. Contact support for item changes.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th width="80">Price</th>
                                <th width="80">Qty</th>
                                <th width="100">Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @php($imgUrl = $item->product?->featured_image_url)
                                            @if($item->product && $imgUrl)
                                                <img src="{{ $imgUrl }}"
                                                     alt="{{ $item->product_name }}"
                                                     class="rounded me-3"
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                                     style="width: 40px; height: 40px;">
                                                    <i class="bi bi-image text-muted"></i>
                                                </div>
                                            @endif

                                            <div>
                                                <h6 class="mb-0">{{ $item->product_name }}</h6>
                                                @if($item->product_sku)
                                                    <small class="text-muted">SKU: {{ $item->product_sku }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ function_exists('money_format_ugx') ? money_format_ugx((float)$item->price) : ('UGX ' . number_format((float)$item->price)) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td><strong>{{ function_exists('money_format_ugx') ? money_format_ugx((float)($item->price * $item->quantity)) : ('UGX ' . number_format((float)($item->price * $item->quantity))) }}</strong></td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot class="table-light">
                            <tr>
                                <th colspan="3">Subtotal</th>
                                <th>{{ function_exists('money_format_ugx') ? money_format_ugx((float)$order->subtotal) : ('UGX ' . number_format((float)$order->subtotal)) }}</th>
                            </tr>
                            @if($order->tax_amount > 0)
                                <tr>
                                    <th colspan="3">Tax</th>
                                    <th>{{ function_exists('money_format_ugx') ? money_format_ugx((float)$order->tax_amount) : ('UGX ' . number_format((float)$order->tax_amount)) }}</th>
                                </tr>
                            @endif
                            @if($order->shipping_cost > 0)
                                <tr>
                                    <th colspan="3">Shipping</th>
                                    <th>{{ function_exists('money_format_ugx') ? money_format_ugx((float)$order->shipping_cost) : ('UGX ' . number_format((float)$order->shipping_cost)) }}</th>
                                </tr>
                            @endif
                            @if($order->discount_amount > 0)
                                <tr>
                                    <th colspan="3">Discount</th>
                                    <th class="text-success">-{{ function_exists('money_format_ugx') ? money_format_ugx((float)$order->discount_amount) : ('UGX ' . number_format((float)$order->discount_amount)) }}</th>
                                </tr>
                            @endif
                            <tr class="table-dark">
                                <th colspan="3">Total</th>
                                <th>{{ function_exists('money_format_ugx') ? money_format_ugx((float)$order->total_amount) : ('UGX ' . number_format((float)$order->total_amount)) }}</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Addresses (Read-only) -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="stat-card p-4 mb-4">
                            <h5 class="mb-3">Shipping Address</h5>
                            @if($order->shippingAddress)
                                <address class="mb-0">
                                    <strong>{{ $order->shippingAddress->first_name }} {{ $order->shippingAddress->last_name }}</strong><br>
                                    {{ $order->shippingAddress->address_line_1 }}<br>
                                    @if($order->shippingAddress->address_line_2)
                                        {{ $order->shippingAddress->address_line_2 }}<br>
                                    @endif
                                    {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->postal_code }}<br>
                                    {{ $order->shippingAddress->country }}<br>
                                    @if($order->shippingAddress->phone)
                                        <strong>Phone:</strong> {{ $order->shippingAddress->phone }}
                                    @endif
                                </address>
                            @else
                                <p class="text-muted">No shipping address on file</p>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stat-card p-4 mb-4">
                            <h5 class="mb-3">Billing Address</h5>
                            @if($order->billingAddress)
                                <address class="mb-0">
                                    <strong>{{ $order->billingAddress->first_name }} {{ $order->billingAddress->last_name }}</strong><br>
                                    {{ $order->billingAddress->address_line_1 }}<br>
                                    @if($order->billingAddress->address_line_2)
                                        {{ $order->billingAddress->address_line_2 }}<br>
                                    @endif
                                    {{ $order->billingAddress->city }}, {{ $order->billingAddress->state }} {{ $order->billingAddress->postal_code }}<br>
                                    {{ $order->billingAddress->country }}<br>
                                    @if($order->billingAddress->phone)
                                        <strong>Phone:</strong> {{ $order->billingAddress->phone }}
                                    @endif
                                </address>
                            @else
                                <p class="text-muted">Same as shipping address</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Actions -->
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Save Changes</h5>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Update Order
                        </button>
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i> Cancel
                        </a>
                    </div>

                    <hr>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="send_notification" name="send_notification" value="1">
                        <label class="form-check-label" for="send_notification">
                            Send status update email to customer
                        </label>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Order Summary</h5>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Order Number:</span>
                        <strong>{{ $order->order_number }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Order Date:</span>
                        <span>{{ $order->created_at->format('M j, Y') }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Amount:</span>
                        <strong class="text-success">{{ function_exists('money_format_ugx') ? money_format_ugx((float)$order->total_amount) : ('UGX ' . number_format((float)$order->total_amount)) }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Current Status:</span>
                        @switch($order->status)
                            @case('pending')
                                <span class="badge bg-warning">Pending</span>
                                @break
                            @case('processing')
                                <span class="badge bg-info">Processing</span>
                                @break
                            @case('shipped')
                                <span class="badge bg-primary">Shipped</span>
                                @break
                            @case('delivered')
                                <span class="badge bg-success">Delivered</span>
                                @break
                            @case('cancelled')
                                <span class="badge bg-danger">Cancelled</span>
                                @break
                            @case('refunded')
                                <span class="badge bg-secondary">Refunded</span>
                                @break
                            @default
                                <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                        @endswitch
                    </div>

                    <div class="d-flex justify-content-between">
                        <span>Items Count:</span>
                        <span>{{ $order->items->sum('quantity') }}</span>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Customer</h5>

                    @if($order->user)
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary rounded-circle text-white d-flex align-items-center justify-content-center me-3"
                                 style="width: 40px; height: 40px;">
                                {{ strtoupper(substr($order->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $order->user->name }}</h6>
                                <small class="text-muted">Customer ID: {{ $order->user->id }}</small>
                            </div>
                        </div>

                        <div class="mb-2">
                            <strong>Email:</strong><br>
                            <a href="mailto:{{ $order->user->email }}">{{ $order->user->email }}</a>
                        </div>

                        @if($order->user->phone)
                            <div class="mb-2">
                                <strong>Phone:</strong><br>
                                <a href="tel:{{ $order->user->phone }}">{{ $order->user->phone }}</a>
                            </div>
                        @endif

                        <div class="d-grid mt-3">
                            <a href="{{ route('admin.users.show', $order->user) }}" class="btn btn-outline-primary btn-sm">
                                View Customer Profile
                            </a>
                        </div>
                    @else
                        <div class="text-muted">
                            <h6>Guest Customer</h6>
                            <div class="mb-2">
                                <strong>Email:</strong><br>
                                <a href="mailto:{{ $order->email }}">{{ $order->email }}</a>
                            </div>
                            @if($order->phone)
                                <div>
                                    <strong>Phone:</strong><br>
                                    <a href="tel:{{ $order->phone }}">{{ $order->phone }}</a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Status Change Warning -->
                <div class="stat-card p-4 bg-light">
                    <h6 class="mb-2">Status Change Effects</h6>
                    <ul class="small mb-0">
                        <li><strong>Cancelled/Refunded:</strong> Will restore product inventory</li>
                        <li><strong>From Cancelled:</strong> Will reduce inventory again</li>
                        <li><strong>Shipped:</strong> Tracking number recommended</li>
                        <li><strong>Delivered:</strong> Marks order complete</li>
                    </ul>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        // Show confirmation for status changes that affect inventory
        document.getElementById('status').addEventListener('change', function() {
            const status = this.value;
            const originalStatus = '{{ $order->status }}';

            if ((status === 'cancelled' || status === 'refunded') &&
                !['cancelled', 'refunded'].includes(originalStatus)) {
                if (!confirm('Changing status to "' + status + '" will restore product inventory. Continue?')) {
                    this.value = originalStatus;
                    return;
                }
            }

            if (!['cancelled', 'refunded'].includes(status) &&
                ['cancelled', 'refunded'].includes(originalStatus)) {
                if (!confirm('Changing from "' + originalStatus + '" will reduce product inventory again. Continue?')) {
                    this.value = originalStatus;
                    return;
                }
            }
        });

        // Auto-check notification if status changes
        document.getElementById('status').addEventListener('change', function() {
            const originalStatus = '{{ $order->status }}';
            if (this.value !== originalStatus) {
                document.getElementById('send_notification').checked = true;
            }
        });
    </script>
@endpush
