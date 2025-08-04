@extends('admin.layouts.app')

@section('title', 'Orders')
@section('page-title', 'Orders Management')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0">Orders Management</h2>
            <p class="text-muted mb-0">Track and manage customer orders</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary" onclick="exportOrders()">
                <i class="bi bi-download me-1"></i> Export
            </button>
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-funnel me-1"></i> Quick Filters
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}">Pending Orders</a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'processing']) }}">Processing Orders</a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'shipped']) }}">Shipped Orders</a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => 'delivered']) }}">Delivered Orders</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['date_from' => today()->format('Y-m-d')]) }}">Today's Orders</a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['date_from' => now()->subDays(7)->format('Y-m-d')]) }}">Last 7 Days</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Orders</h6>
                        <h4 class="mb-0">{{ number_format($stats['total_orders']) }}</h4>
                    </div>
                    <div class="stat-icon bg-primary">
                        <i class="bi bi-bag"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Pending Orders</h6>
                        <h4 class="mb-0 text-warning">{{ number_format($stats['pending_orders']) }}</h4>
                    </div>
                    <div class="stat-icon bg-warning">
                        <i class="bi bi-clock"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Today's Revenue</h6>
                        <h4 class="mb-0 text-success">${{ number_format($stats['today_revenue'], 2) }}</h4>
                    </div>
                    <div class="stat-icon bg-success">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Revenue</h6>
                        <h4 class="mb-0 text-info">${{ number_format($stats['total_revenue'], 2) }}</h4>
                    </div>
                    <div class="stat-icon bg-info">
                        <i class="bi bi-graph-up"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="stat-card p-3 mb-4">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="search" class="form-label">Search</label>
                <input type="text" class="form-control" id="search" name="search"
                       placeholder="Order #, email, customer..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="date_from" class="form-label">From Date</label>
                <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label for="date_to" class="form-label">To Date</label>
                <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-1">
                <label for="min_amount" class="form-label">Min $</label>
                <input type="number" class="form-control" id="min_amount" name="min_amount"
                       step="0.01" placeholder="0.00" value="{{ request('min_amount') }}">
            </div>
            <div class="col-md-1">
                <label for="max_amount" class="form-label">Max $</label>
                <input type="number" class="form-control" id="max_amount" name="max_amount"
                       step="0.01" placeholder="1000" value="{{ request('max_amount') }}">
            </div>
            <div class="col-md-1">
                <div class="d-flex gap-1">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-funnel"></i>
                    </button>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="table-admin">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                <tr>
                    <th>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'order_number', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                           class="text-decoration-none text-dark">
                            Order #
                            @if(request('sort') === 'order_number')
                                <i class="bi bi-chevron-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </a>
                    </th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'total_amount', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                           class="text-decoration-none text-dark">
                            Total
                            @if(request('sort') === 'total_amount')
                                <i class="bi bi-chevron-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </a>
                    </th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                           class="text-decoration-none text-dark">
                            Date
                            @if(request('sort') === 'created_at')
                                <i class="bi bi-chevron-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </a>
                    </th>
                    <th width="120">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>
                            <strong>{{ $order->order_number }}</strong>
                        </td>
                        <td>
                            <div>
                                @if($order->user)
                                    <h6 class="mb-0">{{ $order->user->name }}</h6>
                                    <small class="text-muted">{{ $order->user->email }}</small>
                                @else
                                    <h6 class="mb-0">{{ $order->customer_name ?? 'Guest' }}</h6>
                                    <small class="text-muted">{{ $order->email }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $order->items->count() }} items</span>
                            @if($order->items->count() > 0)
                                <div class="small text-muted mt-1">
                                    {{ $order->items->sum('quantity') }} total qty
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong class="text-success">${{ number_format($order->total_amount, 2) }}</strong>
                            @if($order->subtotal != $order->total_amount)
                                <div class="small text-muted">
                                    Subtotal: ${{ number_format($order->subtotal, 2) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm border-0 p-0 dropdown-toggle" type="button" data-bs-toggle="dropdown">
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
                                </button>
                                <ul class="dropdown-menu">
                                    <li><button class="dropdown-item" onclick="updateStatus({{ $order->id }}, 'pending')">Pending</button></li>
                                    <li><button class="dropdown-item" onclick="updateStatus({{ $order->id }}, 'processing')">Processing</button></li>
                                    <li><button class="dropdown-item" onclick="updateStatus({{ $order->id }}, 'shipped')">Shipped</button></li>
                                    <li><button class="dropdown-item" onclick="updateStatus({{ $order->id }}, 'delivered')">Delivered</button></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><button class="dropdown-item text-danger" onclick="updateStatus({{ $order->id }}, 'cancelled')">Cancel</button></li>
                                    <li><button class="dropdown-item text-warning" onclick="updateStatus({{ $order->id }}, 'refunded')">Refund</button></li>
                                </ul>
                            </div>
                        </td>
                        <td>
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
                            @if($order->payment_method)
                                <div class="small text-muted">{{ ucfirst($order->payment_method) }}</div>
                            @endif
                        </td>
                        <td>
                            <div>{{ $order->created_at->format('M j, Y') }}</div>
                            <small class="text-muted">{{ $order->created_at->format('g:i A') }}</small>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('admin.orders.show', $order) }}">
                                            <i class="bi bi-eye me-2"></i>View Details
                                        </a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.orders.edit', $order) }}">
                                            <i class="bi bi-pencil me-2"></i>Edit
                                        </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><button class="dropdown-item" onclick="printInvoice({{ $order->id }})">
                                            <i class="bi bi-printer me-2"></i>Print Invoice
                                        </button></li>
                                    <li><button class="dropdown-item" onclick="sendEmail({{ $order->id }})">
                                            <i class="bi bi-envelope me-2"></i>Send Email
                                        </button></li>
                                    @if(in_array($order->status, ['cancelled', 'refunded']))
                                        <li><hr class="dropdown-divider"></li>
                                        <li><button class="dropdown-item text-danger" onclick="deleteOrder({{ $order->id }}, '{{ $order->order_number }}')">
                                                <i class="bi bi-trash me-2"></i>Delete
                                            </button></li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-bag mb-3" style="font-size: 3rem;"></i>
                                <h5>No orders found</h5>
                                <p>No orders match your current filters.</p>
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-arrow-clockwise me-1"></i> Reset Filters
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-3 border-top">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        // Update Order Status
        function updateStatus(orderId, status) {
            if (!confirm(`Are you sure you want to change the order status to "${status}"?`)) {
                return;
            }

            fetch(`/admin/orders/${orderId}/status`, {
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
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating order status');
                });
        }

        // Delete Order
        function deleteOrder(orderId, orderNumber) {
            if (!confirm(`Are you sure you want to delete order ${orderNumber}?\n\nThis action cannot be undone.`)) {
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/orders/${orderId}`;
            form.innerHTML = `
        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
        <input type="hidden" name="_method" value="DELETE">
    `;
            document.body.appendChild(form);
            form.submit();
        }

        // Print Invoice
        function printInvoice(orderId) {
            window.open(`/admin/orders/${orderId}/invoice`, '_blank');
        }

        // Send Email
        function sendEmail(orderId) {
            if (!confirm('Send order confirmation email to customer?')) {
                return;
            }

            fetch(`/admin/orders/${orderId}/send-email`, {
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

        // Export Orders
        function exportOrders() {
            const params = new URLSearchParams(window.location.search);
            params.append('export', 'csv');
            window.location.href = '/admin/orders?' + params.toString();
        }
    </script>
@endpush
