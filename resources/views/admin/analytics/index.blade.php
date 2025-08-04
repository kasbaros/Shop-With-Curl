@extends('admin.layouts.app')

@section('title', 'Analytics Dashboard')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Analytics Dashboard</h1>
            <p class="text-muted">Comprehensive business insights and metrics</p>
        </div>
        <div class="d-flex gap-2">
            <!-- Date Range Selector -->
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-calendar3"></i> {{ $dateRange['label'] }}
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['period' => '7days']) }}">Last 7 Days</a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['period' => '30days']) }}">Last 30 Days</a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['period' => '90days']) }}">Last 90 Days</a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['period' => 'year']) }}">Last Year</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#customRangeModal">Custom Range</a></li>
                </ul>
            </div>

            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-download"></i> Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.analytics.export', ['type' => 'sales', 'format' => 'csv'] + request()->query()) }}">Sales Report (CSV)</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.analytics.export', ['type' => 'products', 'format' => 'csv'] + request()->query()) }}">Products Report (CSV)</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.analytics.export', ['type' => 'customers', 'format' => 'csv'] + request()->query()) }}">Customers Report (CSV)</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Key Metrics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="table-admin">
                <div class="p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small mb-1">Total Revenue</div>
                            <div class="h3 mb-0">${{ number_format($metrics['total_revenue']['current'], 2) }}</div>
                            @php
                                $revenueChange = $metrics['total_revenue']['previous'] > 0
                                    ? (($metrics['total_revenue']['current'] - $metrics['total_revenue']['previous']) / $metrics['total_revenue']['previous']) * 100
                                    : ($metrics['total_revenue']['current'] > 0 ? 100 : 0);
                            @endphp
                            <div class="small {{ $revenueChange >= 0 ? 'text-success' : 'text-danger' }}">
                                <i class="bi bi-{{ $revenueChange >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                {{ number_format(abs($revenueChange), 1) }}% vs previous period
                            </div>
                        </div>
                        <div class="text-primary">
                            <i class="bi bi-currency-dollar display-6"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="table-admin">
                <div class="p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small mb-1">Total Orders</div>
                            <div class="h3 mb-0">{{ number_format($metrics['total_orders']['current']) }}</div>
                            @php
                                $ordersChange = $metrics['total_orders']['previous'] > 0
                                    ? (($metrics['total_orders']['current'] - $metrics['total_orders']['previous']) / $metrics['total_orders']['previous']) * 100
                                    : ($metrics['total_orders']['current'] > 0 ? 100 : 0);
                            @endphp
                            <div class="small {{ $ordersChange >= 0 ? 'text-success' : 'text-danger' }}">
                                <i class="bi bi-{{ $ordersChange >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                {{ number_format(abs($ordersChange), 1) }}% vs previous period
                            </div>
                        </div>
                        <div class="text-info">
                            <i class="bi bi-bag-check display-6"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="table-admin">
                <div class="p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small mb-1">Average Order Value</div>
                            <div class="h3 mb-0">${{ number_format($metrics['average_order_value']['current'], 2) }}</div>
                            @php
                                $aovChange = $metrics['average_order_value']['previous'] > 0
                                    ? (($metrics['average_order_value']['current'] - $metrics['average_order_value']['previous']) / $metrics['average_order_value']['previous']) * 100
                                    : ($metrics['average_order_value']['current'] > 0 ? 100 : 0);
                            @endphp
                            <div class="small {{ $aovChange >= 0 ? 'text-success' : 'text-danger' }}">
                                <i class="bi bi-{{ $aovChange >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                {{ number_format(abs($aovChange), 1) }}% vs previous period
                            </div>
                        </div>
                        <div class="text-warning">
                            <i class="bi bi-graph-up-arrow display-6"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="table-admin">
                <div class="p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small mb-1">New Customers</div>
                            <div class="h3 mb-0">{{ number_format($metrics['new_customers']['current']) }}</div>
                            @php
                                $customersChange = $metrics['new_customers']['previous'] > 0
                                    ? (($metrics['new_customers']['current'] - $metrics['new_customers']['previous']) / $metrics['new_customers']['previous']) * 100
                                    : ($metrics['new_customers']['current'] > 0 ? 100 : 0);
                            @endphp
                            <div class="small {{ $customersChange >= 0 ? 'text-success' : 'text-danger' }}">
                                <i class="bi bi-{{ $customersChange >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                {{ number_format(abs($customersChange), 1) }}% vs previous period
                            </div>
                        </div>
                        <div class="text-success">
                            <i class="bi bi-people display-6"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Sales Overview Chart -->
            <div class="table-admin mb-4">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Sales Overview</h5>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-primary active" data-chart="revenue">Revenue</button>
                        <button type="button" class="btn btn-outline-primary" data-chart="orders">Orders</button>
                    </div>
                </div>
                <div class="p-4">
                    <canvas id="salesChart" height="80"></canvas>
                </div>
            </div>

            <!-- Order Status Distribution -->
            <div class="table-admin mb-4">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0">Order Status Distribution</h5>
                </div>
                <div class="p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <canvas id="orderStatusChart" height="120"></canvas>
                        </div>
                        <div class="col-md-6">
                            <div class="mt-3">
                                @foreach($ordersChart as $status => $data)
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="d-flex align-items-center">
                                            <div class="me-2" style="width: 12px; height: 12px; background-color: {{ $this->getStatusColor($status) }}; border-radius: 50%;"></div>
                                            <span class="fw-medium">{{ ucfirst($status) }}</span>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold">{{ $data['count'] }}</div>
                                            <small class="text-muted">${{ number_format($data['total'], 2) }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Products -->
            <div class="table-admin">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Top Products</h5>
                    <a href="{{ route('admin.analytics.products') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Sold</th>
                            <th>Revenue</th>
                            <th>Orders</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($topProducts as $item)
                            <tr>
                                <td>
                                    <div class="fw-medium">{{ $item['product']['name'] ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $item['product']['sku'] ?? 'N/A' }}</small>
                                </td>
                                <td><span class="badge bg-primary">{{ $item['total_sold'] }}</span></td>
                                <td class="fw-medium">${{ number_format($item['revenue'], 2) }}</td>
                                <td>{{ $item['orders_count'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No data available</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Customer Metrics -->
            <div class="table-admin mb-4">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0">Customer Insights</h5>
                </div>
                <div class="p-4">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-medium">Total Customers</span>
                            <span class="h5 mb-0 text-primary">{{ number_format($customerMetrics['total_customers']) }}</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-medium">New Customers</span>
                            <span class="text-success fw-bold">+{{ number_format($customerMetrics['new_customers']) }}</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success"
                                 style="width: {{ $customerMetrics['total_customers'] > 0 ? ($customerMetrics['new_customers'] / $customerMetrics['total_customers']) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-medium">Returning Customers</span>
                            <span class="text-info fw-bold">{{ number_format($customerMetrics['returning_customers']) }}</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-info"
                                 style="width: {{ $customerMetrics['total_customers'] > 0 ? ($customerMetrics['returning_customers'] / $customerMetrics['total_customers']) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div class="alert alert-light">
                        <small class="text-muted">
                            <strong>Retention Rate:</strong> {{ number_format($customerMetrics['customer_retention_rate'], 1) }}%
                        </small>
                    </div>
                </div>
            </div>

            <!-- Top Categories -->
            <div class="table-admin mb-4">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0">Top Categories</h5>
                </div>
                <div class="p-4">
                    @forelse($topCategories as $category)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <div class="fw-medium">{{ $category['name'] }}</div>
                                <small class="text-muted">{{ $category['total_sold'] }} items sold</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold">${{ number_format($category['revenue'], 2) }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted">
                            No data available
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="table-admin">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Orders</h5>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="p-4">
                    @forelse($recentOrders as $order)
                        <div class="d-flex justify-content-between align-items-center mb-3 {{ !$loop->last ? 'pb-3 border-bottom' : '' }}">
                            <div>
                                <div class="fw-medium">#{{ $order['order_number'] ?? $order['id'] }}</div>
                                <small class="text-muted">{{ $order['user']['name'] ?? 'Guest' }}</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold">${{ number_format($order['total_amount'], 2) }}</div>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($order['created_at'])->diffForHumans() }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted">
                            No recent orders
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Date Range Modal -->
    <div class="modal fade" id="customRangeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Custom Date Range</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="GET">
                    <div class="modal-body">
                        <input type="hidden" name="period" value="custom">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date"
                                       value="{{ request('start_date', now()->subDays(30)->format('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date"
                                       value="{{ request('end_date', now()->format('Y-m-d')) }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Apply Range</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sales Chart
            const salesCtx = document.getElementById('salesChart').getContext('2d');
            const salesData = @json($salesChart);

            const salesChart = new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: salesData.map(item => item.period),
                    datasets: [{
                        label: 'Revenue',
                        data: salesData.map(item => item.revenue),
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }, {
                        label: 'Orders',
                        data: salesData.map(item => item.orders),
                        borderColor: '#198754',
                        backgroundColor: 'rgba(25, 135, 84, 0.1)',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.4,
                        hidden: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Chart toggle buttons
            document.querySelectorAll('[data-chart]').forEach(button => {
                button.addEventListener('click', function() {
                    document.querySelectorAll('[data-chart]').forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');

                    const chartType = this.dataset.chart;
                    salesChart.data.datasets.forEach((dataset, index) => {
                        dataset.hidden = (chartType === 'revenue' && index !== 0) || (chartType === 'orders' && index !== 1);
                    });
                    salesChart.update();
                });
            });

            // Order Status Chart
            const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
            const statusData = @json($ordersChart);

            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(statusData).map(status => status.charAt(0).toUpperCase() + status.slice(1)),
                    datasets: [{
                        data: Object.values(statusData).map(item => item.count),
                        backgroundColor: [
                            '#ffc107', // pending
                            '#0dcaf0', // processing
                            '#0d6efd', // shipped
                            '#198754', // delivered
                            '#dc3545', // cancelled
                            '#6c757d'  // refunded
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
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
