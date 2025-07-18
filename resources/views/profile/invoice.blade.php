<x-app-layout>
    <x-slot name="title">Order #{{ $order->id }} - ShopWithCarl</x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Order #{{ $order->id }}</h1>
                <p class="text-gray-600">Placed on {{ $order->created_at->format('F j, Y') }}</p>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' :
                       ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' :
                       ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="p-6 border-b">
                        <h2 class="text-lg font-semibold text-gray-900">Order Items</h2>
                    </div>
                    <div class="divide-y">
                        @foreach($order->items as $item)
                            <div class="p-6 flex items-center space-x-4">
                                <img src="{{ $item->product->featured_image ?? '/images/placeholder.png' }}"
                                     alt="{{ $item->product->name }}"
                                     class="w-16 h-16 object-cover rounded-lg">
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900">{{ $item->product->name }}</h3>
                                    @if($item->productVariant)
                                        <p class="text-sm text-gray-600">{{ $item->productVariant->display_name }}</p>
                                    @endif
                                    <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</p>
                                    <p class="text-sm text-gray-600">Price: ${{ number_format($item->price, 2) }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium text-gray-900">${{ number_format($item->total, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Order Summary & Details -->
            <div class="space-y-6">
                <!-- Order Summary -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium">${{ number_format($order->subtotal, 2) }}</span>
                        </div>

                        @if($order->discount_amount > 0)
                            <div class="flex justify-between text-green-600">
                                <span>Discount</span>
                                <span>-${{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span class="font-medium">${{ number_format($order->shipping_amount, 2) }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax</span>
                            <span class="font-medium">${{ number_format($order->tax_amount, 2) }}</span>
                        </div>

                        <div class="border-t pt-3">
                            <div class="flex justify-between text-lg font-semibold">
                                <span>Total</span>
                                <span>${{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Payment Method</span>
                            <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Payment Status</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                {{ $order->payment_status === 'completed' ? 'bg-green-100 text-green-800' :
                                   ($order->payment_status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Shipping Address</h3>
                    <div class="text-gray-600">
                        {!! nl2br(e($order->shipping_address)) !!}
                    </div>
                </div>

                <!-- Billing Address -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Billing Address</h3>
                    <div class="text-gray-600">
                        {!! nl2br(e($order->billing_address)) !!}
                    </div>
                </div>

                @if($order->notes)
                    <!-- Order Notes -->
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Notes</h3>
                        <p class="text-gray-600">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-8 flex space-x-4">
            <a href="{{ route('user.orders') }}"
               class="bg-gray-200 text-gray-800 px-6 py-3 rounded-lg font-medium hover:bg-gray-300 transition-colors">
                Back to Orders
            </a>

            @if($order->status === 'pending')
                <button class="bg-red-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-red-700 transition-colors">
                    Cancel Order
                </button>
            @endif
        </div>
    </div>
</x-app-layout>
