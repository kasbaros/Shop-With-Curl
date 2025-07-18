<x-app-layout>
    <x-slot name="title">Order Successful - ShopWithCarl</x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center py-12">
            <!-- Success Icon -->
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <!-- Success Message -->
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Order Placed Successfully!</h1>
            <p class="text-lg text-gray-600 mb-8">
                Thank you for your order. We've received your payment and will process your order shortly.
            </p>

            <!-- Order Details -->
            <div class="bg-white rounded-lg shadow-sm border p-6 text-left mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Details</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-sm text-gray-600">Order Number</p>
                        <p class="font-medium text-gray-900">#{{ $order->id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Order Date</p>
                        <p class="font-medium text-gray-900">{{ $order->created_at->format('M j, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Payment Status</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Amount</p>
                        <p class="font-medium text-gray-900">${{ number_format($order->total_amount, 2) }}</p>
                    </div>
                </div>

                <!-- Items -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">Items Ordered</h3>
                    @foreach($order->items as $item)
                        <div class="flex items-center space-x-4 p-4 border rounded-lg">
                            <img src="{{ $item->product->featured_image ?? '/images/placeholder.png' }}"
                                 alt="{{ $item->product->name }}"
                                 class="w-16 h-16 object-cover rounded-lg">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $item->product->name }}</h4>
                                @if($item->productVariant)
                                    <p class="text-sm text-gray-600">{{ $item->productVariant->display_name }}</p>
                                @endif
                                <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-900">${{ number_format($item->total, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Shipping Address -->
                <div class="mt-6 pt-6 border-t">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Shipping Address</h3>
                    <div class="text-gray-600">
                        {!! nl2br(e($order->shipping_address)) !!}
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('user.order.show', $order) }}"
                   class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                    View Order Details
                </a>
                <a href="{{ route('products.index') }}"
                   class="bg-gray-200 text-gray-800 px-6 py-3 rounded-lg font-medium hover:bg-gray-300 transition-colors">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
