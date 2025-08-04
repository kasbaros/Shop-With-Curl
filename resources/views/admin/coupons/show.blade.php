@extends('admin.layouts.app')

@section('title', 'Coupon Details')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Coupon Details</h1>
            <p class="text-muted">
                <strong>{{ $coupon->code }}</strong> - {{ $coupon->name }}
                @if($coupon->is_active)
                    @if($coupon->expires_at && $coupon->expires_at->isPast())
                        <span class="badge bg-danger ms-2">Expired</span>
                    @elseif($coupon->starts_at && $coupon->starts_at->isFuture())
                        <span class="badge bg-warning ms-2">Scheduled</span>
                    @else
                        <span class="badge bg-success ms-2">Active</span>
                    @endif
                @else
                    <span class="badge bg-secondary ms-2">Inactive</span>
                @endif
            </p>
        </div>
        <div>
            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-outline-primary btn-admin me-2">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <form action="{{ route('admin.coupons.toggle', $coupon) }}" method="POST" class="d-inline me-2">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-outline-{{ $coupon->is_active ? 'warning' : 'success' }} btn-admin">
                    <i class="bi bi-{{ $coupon->is_active ? 'pause' : 'play' }}"></i>
                    {{ $coupon->is_active ? 'Deactivate' : 'Activate' }}
                </button>
            </form>
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary btn-admin">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Usage Statistics -->
            <div class="table-admin mb-4">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0">Usage Statistics</h5>
                </div>
                <div class="p-4">
                    <div class="row g-4">
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="h2 mb-1 text-primary">{{ $coupon->used_count }}</div>
                                <div class="text-muted small">Total Uses</div>
                                @if($coupon->usage_limit)
                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar" role="progressbar"
                                             style="width: {{ ($coupon->used_count / $coupon->usage_limit) * 100 }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ $coupon->usage_limit - $coupon->used_count }} remaining</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="h2 mb-1 text-success">${{ number_format($usage_stats['total_discount_given'] ?? 0, 2) }}</div>
                                <div class="text-muted small">Total Discount Given</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="h2 mb-1 text-info">{{ $usage_stats['total_orders'] ?? 0 }}</div>
                                <div class="text-muted small">Orders</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="h2 mb-1 text-warning">${{ number_format($usage_stats['average_order_value'] ?? 0, 2) }}</div>
                                <div class="text-muted small">Avg Order Value</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            @if($coupon->orders && $coupon->orders->count() > 0)
                <div class="table-admin mb-4">
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Orders Using This Coupon</h5>
                        <small class="text-muted">Last 10 orders</small>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Order Total</th>
                                <th>Discount Applied</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($coupon->orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none">
                                            #{{ $order->id }}
                                        </a>
                                    </td>
                                    <td>
                                        <div>{{ $order->user->name ?? 'Guest' }}</div>
                                        <small class="text-muted">{{ $order->user->email ?? $order->email }}</small>
                                    </td>
                                    <td>${{ number_format($order->total_amount, 2) }}</td>
                                    <td class="text-success">${{ number_format($order->discount_amount, 2) }}</td>
                                    <td>{{ $order->created_at->format('M j, Y') }}</td>
                                    <td>
                                <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($usage_stats['total_orders'] > 10)
                        <div class="p-3 border-top text-center">
                            <a href="{{ route('admin.orders.index', ['coupon' => $coupon->code]) }}" class="btn btn-outline-primary btn-sm">
                                View All {{ $usage_stats['total_orders'] }} Orders
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Coupon Configuration -->
            <div class="table-admin mb-4">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0">Configuration Details</h5>
                </div>
                <div class="p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3">Discount Settings</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Type:</strong></td>
                                    <td>
                                        @switch($coupon->type)
                                            @case('percentage')
                                                <span class="badge bg-primary">Percentage</span>
                                                @break
                                            @case('fixed_amount')
                                                <span class="badge bg-success">Fixed Amount</span>
                                                @break
                                            @case('free_shipping')
                                                <span class="badge bg-info">Free Shipping</span>
                                                @break
                                        @endswitch
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Value:</strong></td>
                                    <td>
                                        @if($coupon->type === 'percentage')
                                            {{ number_format($coupon->value * 100, 1) }}%
                                        @elseif($coupon->type === 'fixed_amount')
                                            ${{ number_format($coupon->value, 2) }}
                                        @else
                                            Free Shipping
                                        @endif
                                    </td>
                                </tr>
                                @if($coupon->minimum_amount)
                                    <tr>
                                        <td><strong>Minimum Order:</strong></td>
                                        <td>${{ number_format($coupon->minimum_amount, 2) }}</td>
                                    </tr>
                                @endif
                                @if($coupon->maximum_discount)
                                    <tr>
                                        <td><strong>Maximum Discount:</strong></td>
                                        <td>${{ number_format($coupon->maximum_discount, 2) }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">Usage Limits</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Total Limit:</strong></td>
                                    <td>{{ $coupon->usage_limit ?: 'Unlimited' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Per Customer:</strong></td>
                                    <td>{{ $coupon->usage_limit_per_user ?: 'Unlimited' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Used Count:</strong></td>
                                    <td>{{ $coupon->used_count }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($coupon->applicable_products || $coupon->applicable_categories)
                        <hr class="my-4">
                        <h6 class="mb-3">Product Restrictions</h6>
                        <div class="row">
                            @if($coupon->applicable_products)
                                <div class="col-md-6">
                                    <strong>Applicable Products:</strong>
                                    <ul class="list-unstyled mt-2">
                                        @foreach($coupon->applicable_products as $productId)
                                            @php $product = \App\Models\Product::find($productId) @endphp
                                            @if($product)
                                                <li><small class="badge bg-light text-dark me-1">{{ $product->name }}</small></li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if($coupon->applicable_categories)
                                <div class="col-md-6">
                                    <strong>Applicable Categories:</strong>
                                    <ul class="list-unstyled mt-2">
                                        @foreach($coupon->applicable_categories as $categoryId)
                                            @php $category = \App\Models\Category::find($categoryId) @endphp
                                            @if($category)
                                                <li><small class="badge bg-light text-dark me-1">{{ $category->name }}</small></li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Coupon Preview -->
            <div class="table-admin mb-4">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0">Coupon Preview</h5>
                </div>
                <div class="p-4">
                    <div class="coupon-preview border rounded p-4 text-center bg-gradient"
                         style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <div class="coupon-code h3 mb-2 font-monospace">{{ $coupon->code }}</div>
                        <div class="coupon-name mb-3">{{ $coupon->name }}</div>
                        <div class="coupon-discount h4 mb-3">
                            @if($coupon->type === 'percentage')
                                {{ number_format($coupon->value * 100, 1) }}% OFF
                            @elseif($coupon->type === 'fixed_amount')
                                ${{ number_format($coupon->value, 2) }} OFF
                            @else
                                FREE SHIPPING
                            @endif
                        </div>
                        <div class="coupon-details small opacity-75">
                            @if($coupon->minimum_amount)
                                <div>Min order: ${{ number_format($coupon->minimum_amount, 2) }}</div>
                            @endif
                            <div>
                                {{ $coupon->expires_at ? 'Valid until: ' . $coupon->expires_at->format('M j, Y') : 'Never expires' }}
                            </div>
                        </div>
                    </div>

                    <!-- QR Code or Share Options -->
                    <div class="text-center mt-3">
                        <button class="btn btn-outline-primary btn-sm" onclick="copyToClipboard('{{ $coupon->code }}')">
                            <i class="bi bi-copy"></i> Copy Code
                        </button>
                        <button class="btn btn-outline-secondary btn-sm ms-2" onclick="shareCoupon()">
                            <i class="bi bi-share"></i> Share
                        </button>
                    </div>
                </div>
            </div>

            <!-- Schedule Information -->
            <div class="table-admin mb-4">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0">Schedule</h5>
                </div>
                <div class="p-4">
                    <div class="mb-3">
                        <strong>Start Date:</strong>
                        <div class="text-muted">
                            {{ $coupon->starts_at ? $coupon->starts_at->format('M j, Y g:i A') : 'Immediate' }}
                            @if($coupon->starts_at)
                                <small class="d-block">({{ $coupon->starts_at->diffForHumans() }})</small>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <strong>End Date:</strong>
                        <div class="text-muted">
                            {{ $coupon->expires_at ? $coupon->expires_at->format('M j, Y g:i A') : 'Never expires' }}
                            @if($coupon->expires_at)
                                <small class="d-block">({{ $coupon->expires_at->diffForHumans() }})</small>
                            @endif
                        </div>
                    </div>
                    <div>
                        <strong>Duration:</strong>
                        <div class="text-muted">
                            @if($coupon->starts_at && $coupon->expires_at)
                                {{ $coupon->starts_at->diffInDays($coupon->expires_at) }} days
                            @else
                                Unlimited
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="table-admin mb-4">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="p-4">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-outline-primary btn-admin">
                            <i class="bi bi-pencil"></i> Edit Coupon
                        </a>

                        <form action="{{ route('admin.coupons.toggle', $coupon) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-outline-{{ $coupon->is_active ? 'warning' : 'success' }} btn-admin w-100">
                                <i class="bi bi-{{ $coupon->is_active ? 'pause' : 'play' }}"></i>
                                {{ $coupon->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>

                        <button class="btn btn-outline-info btn-admin" onclick="duplicateCoupon()">
                            <i class="bi bi-files"></i> Duplicate
                        </button>

                        @if($coupon->used_count === 0)
                            <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this coupon?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-admin w-100">
                                    <i class="bi bi-trash"></i> Delete Coupon
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- System Information -->
            <div class="table-admin">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0">System Information</h5>
                </div>
                <div class="p-4">
                    <small class="text-muted">
                        <div class="mb-2">
                            <strong>Created:</strong><br>
                            {{ $coupon->created_at->format('M j, Y g:i A') }}<br>
                            <span class="text-muted">{{ $coupon->created_at->diffForHumans() }}</span>
                        </div>
                        @if($coupon->updated_at != $coupon->created_at)
                            <div class="mb-2">
                                <strong>Last Updated:</strong><br>
                                {{ $coupon->updated_at->format('M j, Y g:i A') }}<br>
                                <span class="text-muted">{{ $coupon->updated_at->diffForHumans() }}</span>
                            </div>
                        @endif
                        <div>
                            <strong>Coupon ID:</strong> {{ $coupon->id }}
                        </div>
                    </small>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Show toast notification
                const toast = document.createElement('div');
                toast.className = 'toast align-items-center text-white bg-success border-0 position-fixed top-0 end-0 m-3';
                toast.style.zIndex = '9999';
                toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-check-circle me-2"></i>Coupon code copied to clipboard!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
                document.body.appendChild(toast);

                const bsToast = new bootstrap.Toast(toast);
                bsToast.show();

                // Remove element after toast hides
                toast.addEventListener('hidden.bs.toast', function() {
                    document.body.removeChild(toast);
                });
            });
        }

        function shareCoupon() {
            const couponData = {
                code: '{{ $coupon->code }}',
                name: '{{ $coupon->name }}',
                @if($coupon->type === 'percentage')
                discount: '{{ number_format($coupon->value * 100, 1) }}% OFF',
                @elseif($coupon->type === 'fixed_amount')
                discount: '${{ number_format($coupon->value, 2) }} OFF',
                @else
                discount: 'FREE SHIPPING',
                @endif
                url: window.location.origin
            };

            if (navigator.share) {
                navigator.share({
                    title: `${couponData.discount} - ${couponData.name}`,
                    text: `Use coupon code "${couponData.code}" for ${couponData.discount}!`,
                    url: couponData.url
                });
            } else {
                // Fallback - copy to clipboard
                const shareText = `🎉 ${couponData.discount} with code "${couponData.code}" at ${couponData.url}`;
                copyToClipboard(shareText);
            }
        }

        function duplicateCoupon() {
            const url = '{{ route("admin.coupons.create") }}';
            const params = new URLSearchParams({
                duplicate: '{{ $coupon->id }}',
                name: '{{ $coupon->name }} (Copy)',
                type: '{{ $coupon->type }}',
                value: '{{ $coupon->type === "percentage" ? $coupon->value * 100 : $coupon->value }}',
                @if($coupon->minimum_amount)
                minimum_amount: '{{ $coupon->minimum_amount }}',
                @endif
                    @if($coupon->maximum_discount)
                maximum_discount: '{{ $coupon->maximum_discount }}',
                @endif
                    @if($coupon->usage_limit)
                usage_limit: '{{ $coupon->usage_limit }}',
                @endif
                    @if($coupon->usage_limit_per_user)
                usage_limit_per_user: '{{ $coupon->usage_limit_per_user }}',
                @endif
                description: '{{ $coupon->description }}'
            });

            window.location.href = `${url}?${params.toString()}`;
        }
    </script>
@endpush
