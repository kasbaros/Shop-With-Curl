<div class="col-lg-9">
    <div class="my-account-content account-order">
        <!-- Header with Filter -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h4 class="mb-0">Order History</h4>

            <!-- Status Filter -->
            <div class="d-flex align-items-center">
                <label class="fw-5 me-2 mb-0">Filter by status:</label>
                <select
                    wire:model.live="selectedStatus"
                    class="form-select"
                    style="width: auto;"
                >
                    @foreach($this->statusOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if($this->orders->isNotEmpty())
            <div class="wrap-account-order">
                <table class="table">
                    <thead>
                    <tr>
                        <th class="fw-6">Order</th>
                        <th class="fw-6">Date</th>
                        <th class="fw-6">Status</th>
                        <th class="fw-6">Total</th>
                        <th class="fw-6">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($this->orders as $order)
                        <tr class="tf-order-item">
                            <td>
                                <div>
                                    <div class="fw-5">#{{ $order->order_number }}</div>
                                    @if($order->tracking_number)
                                        <small class="text-muted">
                                            Tracking: {{ $order->tracking_number }}
                                        </small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div>{{ $order->created_at->format('M d, Y') }}</div>
                                    @if($order->shipped_at)
                                        <small class="text-muted">
                                            Shipped: {{ $order->shipped_at->format('M d, Y') }}
                                        </small>
                                    @elseif($order->delivered_at)
                                        <small class="text-muted">
                                            Delivered: {{ $order->delivered_at->format('M d, Y') }}
                                        </small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                    <span class="badge
                                        {{ $order->status === 'delivered' ? 'bg-success' :
                                           ($order->status === 'cancelled' ? 'bg-danger' :
                                            ($order->status === 'shipped' ? 'bg-info' :
                                             ($order->status === 'processing' ? 'bg-warning' : 'bg-secondary'))) }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-5">{{ $order->formatted_total }}</div>
                                    <small class="text-muted">for {{ $order->total_items }} items</small>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="{{ route('orders.show', $order) }}"
                                       class="tf-btn btn-fill animate-hover-btn rounded-0 justify-content-center btn-sm">
                                        <span>View</span>
                                    </a>

                                    @if($order->status === 'delivered')
                                        <button
                                            class="tf-btn btn-outline animate-hover-btn rounded-0 justify-content-center btn-sm"
                                            wire:click="reorder({{ $order->id }})"
                                        >
                                            <span>Reorder</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($this->orders->hasPages())
                <div class="mt-4">
                    {{ $this->orders->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="wrap-account-order">
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="icon-bag2 fs-48 text-muted"></i>
                    </div>
                    <h4 class="fw-6 mb-3">No orders found</h4>
                    <p class="text-muted mb-4">
                        @if($selectedStatus !== 'all')
                            No orders found with the selected status.
                        @else
                            You haven't placed any orders yet.
                        @endif
                    </p>
                    <a href="{{ route('products.index') }}"
                       class="tf-btn btn-fill animate-hover-btn rounded-0 justify-content-center">
                        <span>Start Shopping</span>
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
