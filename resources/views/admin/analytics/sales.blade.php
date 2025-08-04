@extends('admin.layouts.app')

@section('title', 'Sales Analytics')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Sales Analytics</h1>
            <p class="text-muted">Detailed sales performance and trends</p>
        </div>
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-calendar3"></i> {{ $dateRange['label'] }}
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['period' => '7days']) }}">Last 7 Days</a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['period' => '30days']) }}">Last 30 Days</a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['period' => '90days']) }}">Last 90 Days</a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['period' => 'year']) }}">Last Year</a></li>
                </ul>
            </div>
            <a href="{{ route('admin.analytics.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Sales Summary Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="table-admin">
                <div class="p-4 text-center">
                    <div class="h2 mb-1 text-primary">${{ number_format($salesData['gross_sales'], 2) }}</div>
                    <div class="text-muted">Gross Sales</div>
                    <div class="progress mt-2" style="height: 4px;">
                        <div class="progress-bar bg-primary" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="table-admin">
                <div class="p-4 text-center">
                    <div class="h2 mb-1 text-success">${{ number_format($salesData['net_sales'], 2) }}</div>
                    <div class="text-muted">Net Sales</div>
                    <div class="progress mt-2" style="height: 4px;">
                        <div class="progress-bar bg-success" style="width: {{ $salesData['gross_sales'] > 0 ? ($salesData['net_sales'] / $salesData['gross_sales']) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="table-admin">
                <div class="p-4 text-center">
                    <div class="h2 mb-1 text-info">${{ number_format($salesData['total_tax'], 2) }}</div>
                    <div class="text-muted">Total Tax</div>
                    <div class="progress mt-2" style="height: 4px;">
                        <div class="progress-bar bg-info" style="width: {{ $salesData['gross_sales'] > 0 ? ($salesData['total_tax'] / $salesData['gross_sales']) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="table-admin">
                <div class="p-4 text-center">
                    <div class="h2 mb-1 text-warning">${{ number_format($salesData['total_discounts'], 2) }}</div>
                    <div class="text-muted">Total Discounts</div>
                    <div class="progress mt-2" style="height: 4px;">
                        <div class="progress-bar bg-warning" style="width: {{ $salesData['gross_sales'] > 0 ? ($salesData['total_discounts'] / $salesData['gross_sales']) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Monthly Sales Trends -->
            <div class="table-admin mb-4">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0">Monthly Sales Trends</h5>
                </div>
                <div class="p-4">
                    <canvas id="monthlyTrendsChart" height="80"></canvas>
                </div>
            </div>

            <!-- Sales by Category -->
            <div class="table-admin mb-4">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0">Sales by Category</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>Category</th>
                            <th>Orders</th>
                            <th>Revenue</th>
                            <th>Avg Order Value</th>
                            <th>% of Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $totalRevenue = collect($salesByCategory)->sum('revenue'); @endphp
                        @forelse($salesByCategory as $category)
                            <tr>
                                <td class="fw-medium">{{ $category['name'] }}</td>
                                <td>{{ number_format($category['orders']) }}</td>
                                <td class="fw-medium">${{ number_format($category['revenue'], 2) }}</td>
                                <td>${{ number_format($category['orders'] > 0 ? $category['revenue'] / $category['orders'] : 0, 2) }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress me-2" style="width: 60px; height: 8px;">
                                            <div class="progress-bar" style="width: {{ $totalRevenue > 0 ? ($category['revenue'] / $totalRevenue) * 100 : 0 }}%"></div>
                                        </div>
                                        <span class="small">{{ number_format($totalRevenue > 0 ? ($category['revenue'] / $totalRevenue) * 100 : 0, 1) }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No category data available</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Top Products by Revenue -->
            <div class="table-admin">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0">Top Products by Revenue</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Units Sold</th>
                            <th>Revenue</th>
                            <th>Avg Price</th>
                            <th>Performance</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $maxRevenue = collect($salesByProduct)->max('revenue') ?: 1; @endphp
                        @forelse($salesByProduct as $product)
                            <tr>
                                <td>
                                    <div class="fw-medium">{{ $product['name'] }}</div>
                                    <small class="text-muted">{{ $product['sku'] }}</small>
                                </td>
                                <td><span class="badge bg-primary">{{ number_format($product['units_sold']) }}</span></td>
                                <td class="fw-medium">${{ number_format($product['revenue'], 2) }}</td>
                                <td>${{ number_format($product['units_sold'] > 0 ? $product['revenue'] / $product['units_sold'] : 0, 2) }}</td>
                                <td>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-success" style="width: {{ ($product['revenue'] / $maxRevenue) * 100 }}%"></div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No product data available</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Payment Methods -->
            <div class="table-admin mb-4">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0">Payment Methods</h5>
                </div>
                <div class="p-4">
                    <canvas id="paymentMethodsChart" height="120"></canvas>
                    <div class="mt-3">
                        @foreach($paymentMethods as $method => $data)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="me-2" style="width: 12px; height: 12px; background-color: {{ $this->getPaymentMethodColor($method) }}; border-radius: 50%;"></div>
                                    <span class="fw-medium">{{ ucwords(str_replace('_', ' ', $method)) }}</span>
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

            <!-- Sales Insights -->
            <div class="table-admin mb-4">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0">Sales Insights</h5>
                </div>
                <div class="p-4">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-medium">Revenue Growth</span>
                            <span class="text-success fw-bold">+12.5%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: 75%"></div>
                        </div>
                        <small class="text-muted">Compared to previous period</small>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-medium">Order Volume</span>
                            <span class="text-info fw-bold">+8.3%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-info" style="width: 60%"></div>
                        </div>
                        <small class="text-muted">More orders than last period</small>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-medium">Customer Acquisition</span>
                            <span class="text-primary fw-bold">+15.2%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" style="width: 85%"></div>
                        </div>
                        <small class="text-muted">New customers gained</small>
                    </div>

                    <div class="alert alert-light">
                        <i class="bi bi-lightbulb text-warning"></i>
                        <strong>Tip:</strong> Consider promoting your top-performing categories to maximize revenue.
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="table-admin">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="p-4">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.analytics.export', ['type' => 'sales'] + request()->query()) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-download me-1"></i> Export Sales Report
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-list-ul me-1"></i> View All Orders
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-box-seam me-1"></i> Manage Products
                        </a>
                        <button class="btn btn-outline-info btn-sm" onclick="printReport()">
                            <i class="bi bi-printer me-1"></i> Print Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Monthly Trends Chart
            const trendsCtx = document.getElementById('monthlyTrendsChart').getContext('2d');
            const trendsData = @json($monthlyTrends);

            new Chart(trendsCtx, {
                type: 'line',
                data: {
                    labels: trendsData.map(item => item.month),
                    datasets: [{
                        label: 'Revenue',
                        data: trendsData.map(item => item.revenue),
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    }, {
                        label: 'Orders',
                        data: trendsData.map(item => item.orders),
                        borderColor: '#198754',
                        backgroundColor: 'rgba(25, 135, 84, 0.1)',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.4,
                        yAxisID: 'y1'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            beginAtZero: true
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            beginAtZero: true,
                            grid: {
                                drawOnChartArea: false,
                            },
                        }
                    }
                }
            });

            // Payment Methods Chart
            const paymentCtx = document.getElementById('paymentMethodsChart').getContext('2d');
            const paymentData = @json($paymentMethods);

            new Chart(paymentCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(paymentData).map(method => method.replace('_', ' ').toUpperCase()),
                    datasets: [{
                        data: Object.values(paymentData).map(item => item.count),
                        backgroundColor: [
                            '#0d6efd', // credit_card
                            '#198754', // paypal
                            '#ffc107', // bank_transfer
                            '#dc3545', // cash_on_delivery
                            '#6c757d'  // other
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

        function printReport() {
            window.print();
        }
    </script>
@endpush
