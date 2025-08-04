@extends('admin.layouts.app')

@section('title', 'Order #' . $order->order_number)
@section('page-title', 'Order Details')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0">Order #{{ $order->order_number }}</h2>
            <p class="text-muted mb-0">{{ $order->created_at->format('M j, Y \a\t g:i A') }}</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary" onclick="printInvoice()">
                <i class="bi bi-printer me-1"></i> Print
            </button>
            <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-primary btn-admin">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-three-dots"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><button class="dropdown-item" onclick="sendEmail()">
                            <i class="bi bi-envelope me-2"></i>Send Email
                        </button></li>
                    <li><button class="dropdown-item" onclick="duplicateOrder()">
                            <i class="bi bi-files me-2"></i>Duplicate Order
                        </button></li>
                    <li><hr class="dropdown-divider"></li>
                    @if(in_array($order->status, ['cancelled', 'refunded']))
                        <li><button class="dropdown-item text-danger" onclick="deleteOrder()">
                                <i class="bi bi-trash me-2"></i>Delete Order
                            </button></li>
                    @endif
                </ul>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Order Details -->
        <div class="col-lg-8">
            <!-- Order Status -->
            <div class="stat-card p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Order Status</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm dropdown-toggle border-0" type="button" data-bs-toggle="dropdown">
                            @switch($order->status)
                                @case('pending')
                                    <span class="badge bg-warning fs-6">Pending</span>
                                    @break
                                @case('processing')
                                    <span class="badge bg-info fs-6">Processing</span>
                                    @break
                                @case('shipped')
                                    <span class="badge bg-primary fs-6">Shipped</span>
                                    @break
                                @case('delivered')
                                    <span class="badge bg-success fs-6">Delivered</span>
                                    @break
                                @case('cancelled')
                                    <span class="badge bg-danger fs-6">Cancelled</span>
                                    @break
                                @case('refunded')
                                    <span class="badge bg-secondary fs-6">Refunded</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary fs-6">{{ ucfirst($order->status) }}</span>
                            @endswitch
                        </button>
                        <ul class="dropdown-menu">
                            <li><button class="dropdown-item" onclick="updateStatus('pending')">Pending</button></li>
                            <li><button class="dropdown-item" onclick="updateStatus('processing')">Processing</button></li>
                            <li><button class="dropdown-item" onclick="updateStatus('shipped')">Shipped</button></li>
                            <li><button class="dropdown-item" onclick="updateStatus('delivered')">Delivered</button></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><button class="dropdown-item text-danger" onclick="updateStatus('cancelled')">Cancel Order</button></li>
                            <li><button class="dropdown-item text-warning" onclick="updateStatus('refunded')">Refund Order</button></li>
                        </ul>
                    </div>
                </div>

                @if($order->tracking_number)
                    <div class="alert alert-info mb-3">
                        <strong>Tracking Number:</strong> {{ $order->tracking_number }}
                        @if($order->shipping_method)
                            <span class="ms-2">via {{ $order->shipping_method }}</span>
                        @endif
                    </div>
                @endif

                @if($order->notes)
                    <div class="alert alert-light">
                        <strong>Notes:</strong> {{ $order->notes }}
                    </div>
                @endif
            </div>

            <!-- Order Items -->
            <div class="stat-card p-4 mb-4">
                <h5 class="mb-3">Order Items ({{ $order->items->count() }})</h5>

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
                                        @if($item->product && $item->product->images->first())
                                            <img src="{{ Storage::url($item->product->images->first()->image_path) }}"
                                                 alt="{{ $item->product_name }}"
                                                 class="rounded me-3"
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                                 style="width: 50px; height: 50px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif

                                        <div>
                                            <h6 class="mb-1">
                                                @if($item->product)
                                                    <a href="{{ route('admin.products.show', $item->product) }}"
                                                       class="text-decoration-none">
                                                        {{ $item->product_name }}
                                                    </a>
                                                @else
                                                    {{ $item->product_name }}
                                                    <small class="text-danger">(Product Deleted)</small>
                                                @endif
                                            </h6>
                                            @if($item->product_sku)
                                                <small class="text-muted">SKU: {{ $item->product_sku }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td><strong>${{ number_format($item->price * $item->quantity, 2) }}</strong></td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot class="table-light">
                        <tr>
                            <th colspan="3">Subtotal</th>
                            <th>${{ number_format($order->subtotal, 2) }}</th>
                        </tr>
                        @if($order->tax_amount > 0)
                            <tr>
                                <th colspan="3">Tax</th>
                                <th>${{ number_format($order->tax_amount, 2) }}</th>
                            </tr>
                        @endif
                        @if($order->shipping_cost > 0)
                            <tr>
                                <th colspan="3">Shipping</th>
                                <th>${{ number_format($order->shipping_cost, 2) }}</th>
                            </tr>
                        @endif
                        @if($order->discount_amount > 0)
                            <tr>
                                <th colspan="3">Discount</th>
                                <th class="text-success">-${{ number_format($order->discount_amount, 2) }}</th>
                            </tr>
                        @endif
                        <tr class="table-dark">
                            <th colspan="3">Total</th>
                            <th>${{ number_format($order->total_amount, 2) }}</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Shipping & Billing Addresses -->
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
            <!-- Order Summary -->
            <div class="stat-card p-4 mb-4">
                <h5 class="mb-3">Order Summary</h5>

                <div class="d-flex justify-content-between mb-2">
                    <span>Order Number:</span>
                    <strong>{{ $order->order_number }}</strong>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Order Date:</span>
                    <span>{{ $order->created_at->format('M j, Y g:i A') }}</span>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Total Amount:</span>
                    <strong class="text-success">${{ number_format($order->total_amount, 2) }}</strong>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Payment Status:</span>
                    @switch($order->payment_status)
                        @case('paid')
                            <span class="badge bg-success">Paid</span>
                            @break
                        @case('pending')
                            <span class="badge bg-warning">Pending</span>
                            @break
                        @case('failed')
                            <span class="badge bg-danger">Failed</span>
                            @break
                        @case('refunded')
                            <span class="badge bg-secondary">Refunded</span>
                            @break
                        @default
                            <span class="badge bg-secondary">{{ ucfirst($order->payment_status ?? 'Unknown') }}</span>
                    @endswitch
                </div>

                @if($order->payment_method)
                    <div class="d-flex justify-content-between mb-2">
                        <span>Payment Method:</span>
                        <span>{{ ucfirst($order->payment_method) }}</span>
                    </div>
                @endif

                <div class="d-flex justify-content-between">
                    <span>Items Count:</span>
                    <span>{{ $order->items->sum('quantity') }}</span>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="stat-card p-4 mb-4">
                <h5 class="mb-3">Customer Information</h5>

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

                    <div class="mb-2">
                        <strong>Total Orders:</strong>
                        {{ $order->user->orders()->count() }}
                    </div>

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

            <!-- Quick Actions -->
            <div class="stat-card p-4">
                <h5 class="mb-3">Quick Actions</h5>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil me-1"></i> Edit Order
                    </a>
                    <button class="btn btn-outline-info btn-sm" onclick="printInvoice()">
                        <i class="bi bi-printer me-1"></i> Print Invoice
                    </button>
                    <button class="btn btn-outline-success btn-sm" onclick="sendEmail()">
                        <i class="bi bi-envelope me-1"></i> Send Email
                    </button>
                    @if($order->status !== 'delivered')
                        <button class="btn btn-outline-warning btn-sm" onclick="updateStatus('delivered')">
                            <i class="bi bi-check-circle me-1"></i> Mark Delivered
                        </button>
                    @endif
                    @if(!in_array($order->status, ['cancelled', 'refunded']))
                        <button class="btn btn-outline-danger btn-sm" onclick="updateStatus('cancelled')">
                            <i class="bi bi-x-circle me-1"></i> Cancel Order
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function updateStatus(status) {
            if (!confirm(`Are you sure you want to change the order status to "${status}"?`)) {
                return;
            }

            fetch(`/admin/orders/{{ $order->id }}/status`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    status: status,
                    notes: `Status changed to ${status} by admin`
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error updating order status');
                    }
                });
        }

        function printInvoice() {
            window.print();
        }

        function sendEmail() {
            if (!confirm('Send order confirmation email to customer?')) {
                return;
            }

            fetch(`/admin/orders/{{ $order->id }}/send-email`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Email sent successfully!');
                    } else {
                        alert('Error sending email');
                    }
                });
        }

        function deleteOrder() {
            if (!confirm(`Are you sure you want to delete order {{ $order->order_number }}?\n\nThis action cannot be undone.`)) {
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/orders/{{ $order->id }}`;
            form.innerHTML = `
        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
        <input type="hidden" name="_method" value="DELETE">
    `;
            document.body.appendChild(form);
            form.submit();
        }

        function duplicateOrder() {
            if (!confirm('Create a new order with the same items?')) {
                return;
            }

            // This would need to be implemented in the controller
            alert('Duplicate order functionality would be implemented here');
        }
    </script>
@endpush
