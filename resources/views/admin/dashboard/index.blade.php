@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
    <div class="row">
        <!-- Statistics Cards -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card p-4 h-100">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-1">Total Orders</h6>
                        <h3 class="mb-0">{{ number_format($stats['total_orders']) }}</h3>
                        <small class="text-success">
                            +{{ $stats['today_orders'] }} today
                        </small>
                    </div>
                    <div class="text-primary">
                        <i class="bi bi-cart-check" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card p-4 h-100">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-1">Total Revenue</h6>
                        <h3 class="mb-0">${{ number_format($stats['total_revenue'], 2) }}</h3>
                        <small class="text-success">
                            +${{ number_format($stats['today_revenue'], 2) }} today
                        </small>
                    </div>
                    <div class="text-success">
                        <i class="bi bi-currency-dollar" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card p-4 h-100">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-1">Products</h6>
                        <h3 class="mb-0">{{ number_format($stats['total_products']) }}</h3>
                        <small class="text-{{ $stats['out_of_stock'] > 0 ? 'danger' : 'success' }}">
                            {{ $stats['out_of_stock'] }} out of stock
                        </small>
                    </div>
                    <div class="text-info">
                        <i class="bi bi-box-seam" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card p-4 h-100">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-1">Customers</h6>
                        <h3 class="mb-0">{{ number_format($stats['total_customers']) }}</h3>
                        <small class="text-primary">
                            +{{ $stats['new_customers_today'] }} today
                        </small>
                    </div>
                    <div class="text-warning">
                        <i class="bi bi-people" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sales Chart -->
        <div class="col-xl-8 mb-4">
            <div class="stat-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Sales Overview (Last 30 Days)</h5>
                    <div class="d-flex gap-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary" style="width: 12px; height: 12px; border-radius: 2px; margin-right: 6px;"></div>
                            <small>Orders</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="bg-success" style="width: 12px; height: 12px; border-radius: 2px; margin-right: 6px;"></div>
                            <small>Revenue</small>
                        </div>
                    </div>
                </div>
                <canvas id="salesChart" height="100"></canvas>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-xl-4 mb-4">
            <div class="stat-card p-4 h-100">
                <h5 class="mb-3">Quick Stats</h5>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="text-muted">Active Products</small>
                        <small class="fw-bold">{{ $stats['active_products'] }}</small>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: {{ $stats['total_products'] > 0 ? ($stats['active_products'] / $stats['total_products']) * 100 : 0 }}%"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="text-muted">Pending Reviews</small>
                        <small class="fw-bold text-warning">{{ $stats['pending_reviews'] }}</small>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-warning" style="width: {{ $stats['pending_reviews'] > 0 ? min(($stats['pending_reviews'] / 10) * 100, 100) : 0 }}%"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="text-muted">Out of Stock</small>
                        <small class="fw-bold text-danger">{{ $stats['out_of_stock'] }}</small>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-danger" style="width: {{ $stats['total_products'] > 0 ? ($stats['out_of_stock'] / $stats['total_products']) * 100 : 0 }}%"></div>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">System Status</small>
                    <span class="badge bg-success">Online</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Orders -->
        <div class="col-xl-8 mb-4">
            <div class="table-admin">
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                    <h5 class="mb-0">Recent Orders</h5>
                    <a href="#" class="btn btn-outline-primary btn-sm">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($recentOrders as $order)
                            <tr>
                                <td>
                                    <span class="fw-bold">#{{ $order->order_number ?? $order->id }}</span>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-medium">{{ $order->user->name ?? 'Guest' }}</div>
                                        <small class="text-muted">{{ $order->user->email ?? 'N/A' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold">${{ number_format($order->total_amount, 2) }}</span>
                                </td>
                                <td>
                                <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                                </td>
                                <td>
                                    <small>{{ $order->created_at->format('M j, Y') }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    No orders found
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Top Products & Low Stock -->
        <div class="col-xl-4">
            <!-- Top Products -->
            <div class="table-admin mb-4">
                <div class="p-3 border-bottom">
                    <h6 class="mb-0">Top Products (30 days)</h6>
                </div>
                <div class="p-3">
                    @forelse($topProducts as $product)
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light rounded p-2 me-3">
                                <i class="bi bi-box text-muted"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-medium">{{ Str::limit($product->name, 20) }}</div>
                                <small class="text-muted">{{ $product->total_sold }} sold</small>
                            </div>
                            <div class="text-end">
                                <small class="text-success fw-bold">${{ number_format($product->price, 2) }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-3 text-muted">
                            <i class="bi bi-box-seam mb-2" style="font-size: 2rem;"></i>
                            <div>No sales data available</div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Low Stock Alert -->
            @if($lowStockProducts->count() > 0)
                <div class="table-admin">
                    <div class="p-3 border-bottom bg-danger bg-opacity-10">
                        <h6 class="mb-0 text-danger">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            Low Stock Alert
                        </h6>
                    </div>
                    <div class="p-3">
                        @foreach($lowStockProducts as $product)
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-danger bg-opacity-10 rounded p-2 me-3">
                                    <i class="bi bi-box text-danger"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-medium">{{ Str::limit($product->name, 20) }}</div>
                                    <small class="text-danger">{{ $product->stock_quantity }} left</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sales Chart
            const ctx = document.getElementById('salesChart').getContext('2d');
            const salesData = @json($salesData);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: salesData.labels,
                    datasets: [{
                        label: 'Orders',
                        data: salesData.orders,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        tension: 0.4,
                        yAxisID: 'y'
                    }, {
                        label: 'Revenue ($)',
                        data: salesData.revenue,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        yAxisID: 'y1'
                    }]
                },
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    scales: {
                        x: {
                            display: true,
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            },
                            title: {
                                display: true,
                                text: 'Orders'
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false,
                            },
                            title: {
                                display: true,
                                text: 'Revenue ($)'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>
@endpush
