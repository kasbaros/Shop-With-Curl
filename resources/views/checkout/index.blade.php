<x-app-layout>
    <x-slot name="title">Checkout - ShopWithCarl</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

        <div class="lg:grid lg:grid-cols-12 lg:gap-x-12 xl:gap-x-16">
            <!-- Checkout Form -->
            <div class="lg:col-span-7">
                <form action="{{ route('checkout.store') }}" method="POST" class="space-y-8">
                    @csrf

                    <!-- Shipping Address -->
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Shipping Address</h2>

                        @if($addresses->where('type', 'shipping')->count() > 0 || $addresses->where('type', 'both')->count() > 0)
                            <div class="space-y-3">
                                @foreach($addresses->whereIn('type', ['shipping', 'both']) as $address)
                                    <label class="flex items-start space-x-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                        <input type="radio" name="shipping_address_id" value="{{ $address->id }}"
                                               {{ $address->is_default ? 'checked' : '' }}
                                               class="mt-1 text-blue-600 focus:ring-blue-500">
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-900">{{ $address->name }}</div>
                                            <div class="text-sm text-gray-600">
                                                {{ $address->address_line_1 }}
                                                @if($address->address_line_2), {{ $address->address_line_2 }}@endif
                                            </div>
                                            <div class="text-sm text-gray-600">
                                                {{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}
                                            </div>
                                            <div class="text-sm text-gray-600">{{ $address->country }}</div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-600 mb-4">No shipping addresses found.</p>
                        @endif

                        <div class="mt-4">
                            <a href="{{ route('user.addresses') }}" class="text-blue-600 hover:text-blue-700 text-sm">
                                Manage Addresses
                            </a>
                        </div>

                        @error('shipping_address_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Billing Address -->
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Billing Address</h2>

                        @if($addresses->where('type', 'billing')->count() > 0 || $addresses->where('type', 'both')->count() > 0)
                            <div class="space-y-3">
                                @foreach($addresses->whereIn('type', ['billing', 'both']) as $address)
                                    <label class="flex items-start space-x-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                        <input type="radio" name="billing_address_id" value="{{ $address->id }}"
                                               {{ $address->is_default ? 'checked' : '' }}
                                               class="mt-1 text-blue-600 focus:ring-blue-500">
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-900">{{ $address->name }}</div>
                                            <div class="text-sm text-gray-600">
                                                {{ $address->address_line_1 }}
                                                @if($address->address_line_2), {{ $address->address_line_2 }}@endif
                                            </div>
                                            <div class="text-sm text-gray-600">
                                                {{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}
                                            </div>
                                            <div class="text-sm text-gray-600">{{ $address->country }}</div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-600 mb-4">No billing addresses found.</p>
                        @endif

                        @error('billing_address_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Payment Method</h2>

                        <div class="space-y-3">
                            <label class="flex items-center space-x-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="credit_card" checked
                                       class="text-blue-600 focus:ring-blue-500">
                                <div class="flex-1">
                                    <div class="font-medium text-gray-900">Credit Card</div>
                                    <div class="text-sm text-gray-600">Pay with your credit or debit card</div>
                                </div>
                            </label>

                            <label class="flex items-center space-x-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="paypal"
                                       class="text-blue-600 focus:ring-blue-500">
                                <div class="flex-1">
                                    <div class="font-medium text-gray-900">PayPal</div>
                                    <div class="text-sm text-gray-600">Pay with your PayPal account</div>
                                </div>
                            </label>

                            <label class="flex items-center space-x-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="bank_transfer"
                                       class="text-blue-600 focus:ring-blue-500">
                                <div class="flex-1">
                                    <div class="font-medium text-gray-900">Bank Transfer</div>
                                    <div class="text-sm text-gray-600">Transfer money directly from your bank</div>
                                </div>
                            </label>
                        </div>

                        @error('payment_method')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Order Notes -->
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Order Notes (Optional)</h2>
                        <textarea name="notes" rows="3" placeholder="Any special instructions for your order..."
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex space-x-4">
                        <a href="{{ route('cart.index') }}" class="flex-1 bg-gray-200 text-gray-800 py-3 px-6 rounded-lg font-medium text-center hover:bg-gray-300 transition-colors">
                            Back to Cart
                        </a>
                        <button type="submit" class="flex-1 bg-blue-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                            Place Order
                        </button>
                    </div>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-5 mt-8 lg:mt-0">
                <div class="bg-white rounded-lg shadow-sm border p-6 sticky top-4">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h2>

                    <!-- Cart Items -->
                    <div class="space-y-4 mb-6">
                        @foreach($cart as $item)
                            <div class="flex items-center space-x-3">
                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-12 h-12 object-cover rounded-md">
                                <div class="flex-1">
                                    <h3 class="text-sm font-medium text-gray-900">{{ $item['name'] }}</h3>
                                    <p class="text-sm text-gray-600">Qty: {{ $item['quantity'] }}</p>
                                </div>
                                <span class="text-sm font-medium text-gray-900">
                                    ${{ number_format($item['total'], 2) }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pricing Details -->
                    <div class="space-y-3 border-t pt-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium">${{ number_format($subtotal, 2) }}</span>
                        </div>

                        @if($discount > 0)
                            <div class="flex justify-between text-green-600">
                                <span>Discount ({{ $appliedCoupon['code'] }})</span>
                                <span>-${{ number_format($discount, 2) }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span class="font-medium">
                                @if($shipping > 0)
                                    ${{ number_format($shipping, 2) }}
                                @else
                                    Free
                                @endif
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax</span>
                            <span class="font-medium">${{ number_format($tax, 2) }}</span>
                        </div>

                        <div class="border-t pt-3">
                            <div class="flex justify-between text-lg font-medium">
                                <span>Total</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
