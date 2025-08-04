<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Order History</h1>

        <!-- Status Filter -->
        <div class="flex items-center space-x-2">
            <label class="text-sm font-medium text-gray-700">Filter by status:</label>
            <select
                wire:model.live="selectedStatus"
                class="border border-gray-300 rounded-md px-3 py-2"
            >
                @foreach($this->statusOptions as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    @if($this->orders->isNotEmpty())
        <!-- Orders List -->
        <div class="space-y-4">
            @foreach($this->orders as $order)
                <div class="bg-white rounded-lg shadow-sm border">
                    <!-- Order Header -->
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div>
                                    <h3 class="font-medium text-gray-900">
                                        Order #{{ $order->order_number }}
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        Placed on {{ $order->created_at->format('M d, Y') }}
                                    </p>
                                </div>

                                <span class="px-3 py-1 rounded-full text-sm font-medium
                                             {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' :
                                                ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' :
                                                 'bg-blue-100 text-blue-800') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>

                            <div class="text-right">
                                <p class="text-lg font-bold text-gray-900">
                                    {{ $order->formatted_total }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    {{ $order->total_items }} items
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="px-6 py-4">
                        <div class="space-y-3">
                            @foreach($order->items as $item)
                                <div class="flex items-center space-x-4">
                                    <img
                                        src="{{ $item->product->featured_image ?: '/images/placeholder.png' }}"
                                        alt="{{ $item->product->name }}"
                                        class="w-16 h-16 object-cover rounded-md"
                                    >

                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-medium text-gray-900">
                                            {{ $item->product->name }}
                                        </h4>
                                        @if($item->productVariant)
                                            <p class="text-sm text-gray-600">
                                                {{ $item->productVariant->display_name }}
                                            </p>
                                        @endif
                                        <p class="text-sm text-gray-600">
                                            Quantity: {{ $item->quantity }}
                                        </p>
                                    </div>

                                    <div class="text-right">
                                        <p class="font-medium text-gray-900">
                                            {{ $item->formatted_total }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            {{ $item->formatted_price }} each
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Order Actions -->
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                @if($order->tracking_number)
                                    <span class="text-sm text-gray-600">
                                        Tracking: {{ $order->tracking_number }}
                                    </span>
                                @endif

                                @if($order->shipped_at)
                                    <span class="text-sm text-gray-600">
                                        Shipped: {{ $order->shipped_at->format('M d, Y') }}
                                    </span>
                                @endif

                                @if($order->delivered_at)
                                    <span class="text-sm text-gray-600">
                                        Delivered: {{ $order->delivered_at->format('M d, Y') }}
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center space-x-2">
                                <a
                                    href="{{ route('user.order.show', $order) }}"
                                    class="text-blue-600 hover:text-blue-700 text-sm font-medium"
                                >
                                    View Details
                                </a>

                                @if($order->status === 'delivered')
                                    <button
                                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm"
                                    >
                                        Reorder
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $this->orders->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <x-heroicon-o-shopping-bag class="w-16 h-16 mx-auto text-gray-400 mb-4" />
            <h3 class="text-lg font-medium text-gray-900 mb-2">No orders found</h3>
            <p class="text-gray-500 mb-6">
                @if($selectedStatus !== 'all')
                    No orders found with the selected status.
                @else
                    You haven't placed any orders yet.
                @endif
            </p>
            <a
                href="{{ route('products.index') }}"
                class="inline-flex items-center bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors"
            >
                <x-heroicon-m-shopping-bag class="w-5 h-5 mr-2" />
                Start Shopping
            </a>
        </div>
    @endif
</div>
