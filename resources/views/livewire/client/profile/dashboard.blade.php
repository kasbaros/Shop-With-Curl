<div>
    <div class="container">
        <!-- Main dashboard content -->
        <div class="col-lg-12">
            <div class="my-account-content account-dashboard">
                <!-- Greeting & quick actions -->
                <div class="mb_40 d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h5 class="fw-5 mb_8">Hello {{ $user->name ?? 'Shopper' }} 👋</h5>
                        <p class="text-muted m-0">Here’s a quick snapshot of your shopping activity.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('products.index') }}" class="tf-btn btn-fill animate-hover-btn rounded-0 justify-content-center">Continue Shopping</a>
                        <a href="{{ route('account.page', ['section' => 'details']) }}" class="tf-btn btn-fill animate-hover-btn rounded-0 justify-content-center">Update Profile</a>
                    </div>
                </div>

                <!-- Stat cards -->
                <div class="row g-3 mb_40">
                    <div class="col-6 col-md-3">
                        <div class="card p-3 h-100">
                            <div class="text-muted small">Orders</div>
                            <div class="h4 m-0">{{ $user?->orders()->count() ?? 0 }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card p-3 h-100">
                            <div class="text-muted small">Pending</div>
                            <div
                                class="h4 m-0">{{ $user?->orders()->whereIn('status', ['pending','processing'])->count() ?? 0 }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card p-3 h-100">
                            <div class="text-muted small">Wishlist</div>
                            <div class="h4 m-0">{{ $wishlistCount }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card p-3 h-100">
                            <div class="text-muted small">Addresses</div>
                            <div class="h4 m-0">{{ $addressCount }}</div>
                        </div>
                    </div>
                </div>

                <!-- Recent orders -->
                <div class="mb_40">
                    <div class="d-flex justify-content-between align-items-center mb_16">
                        <h6 class="fw-6 m-0">Recent Orders</h6>
                        <a href="{{ route('orders.index') }}" class="text_primary small">View all</a>
                    </div>
                    @if(!empty($recentOrders) && count($recentOrders))
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($recentOrders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->created_at?->format('M d, Y') }}</td>
                                        <td>${{ number_format($order->total ?? 0, 2) }}</td>
                                        <td>
                                                        <span class="badge {{ match($order->status){
                                                            'pending' => 'bg-warning',
                                                            'processing' => 'bg-info',
                                                            'shipped' => 'bg-primary',
                                                            'delivered' => 'bg-success',
                                                            'cancelled' => 'bg-danger',
                                                            default => 'bg-secondary'
                                                        } }}">{{ ucfirst($order->status ?? 'unknown') }}</span>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('orders.show', $order->id) }}" class="text_primary">Details</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center p-4 border rounded">
                            <p class="mb-2">You have no recent orders.</p>
                            <a href="{{ route('products.index') }}" class="tf-btn btn-fill animate-hover-btn rounded-0 justify-content-center">Shop Bestsellers</a>
                        </div>
                    @endif
                </div>

                <!-- Personalized picks / recommendations placeholder -->
                <div>
                    <div class="d-flex justify-content-between align-items-center mb_16">
                        <h6 class="fw-6 m-0">Recommended for You</h6>
                        <a href="{{ route('products.index') }}" class="text_primary small">Explore products</a>
                    </div>
                    <!-- If you have a product grid component, you can include it here. Otherwise keep a simple promo. -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="banner p-4 bg-light rounded h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="fw-6">New Arrivals</h6>
                                    <p class="text-muted">Discover the latest drops handpicked for you.</p>
                                </div>
                                <a href="{{ route('products.index') }}" class="tf-btn btn-fill animate-hover-btn rounded-0 justify-content-center align-self-start">Shop New In</a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="banner p-4 bg-light rounded h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="fw-6">Top Picks</h6>
                                    <p class="text-muted">Bestsellers customers are loving right now.</p>
                                </div>
                                <a href="{{ route('products.index') }}" class="tf-btn btn-fill animate-hover-btn rounded-0 justify-content-center align-self-start">Shop Bestsellers</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
