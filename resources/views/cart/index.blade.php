<x-app-layout>
    <x-slot name="title">Shopping Cart - ShopWithCarl</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <flux:heading size="2xl" class="mb-8">Shopping Cart</flux:heading>

        @if(empty($cart))
            <!-- Empty Cart -->
            <flux:card class="text-center py-12">
                <flux:icon.shopping-bag class="w-16 h-16 mx-auto text-gray-400 mb-4" />
                <flux:heading size="lg" class="mb-2">Your cart is empty</flux:heading>
                <flux:text class="text-gray-600 mb-6">Add some products to get started</flux:text>
                <flux:button href="{{ route('products.index') }}" variant="primary" size="lg">
                    Continue Shopping
                </flux:button>
            </flux:card>
        @else
            <div class="lg:grid lg:grid-cols-12 lg:gap-x-12 xl:gap-x-16">
                <!-- Cart Items -->
                <div class="lg:col-span-7">
                    <div class="space-y-6">
                        @foreach($cart as $key => $item)
                            <flux:card class="flex items-center space-x-4 p-6">
                                <!-- Product Image -->
                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-20 h-20 object-cover rounded-lg">

                                <!-- Product Details -->
                                <div class="flex-1">
                                    <flux:heading size="lg" class="mb-1">{{ $item['name'] }}</flux:heading>
                                    <flux:text class="text-gray-600">${{ number_format($item['price'], 2) }} each</flux:text>

                                    <!-- Quantity Update -->
                                    <form action="{{ route('cart.update', $key) }}" method="POST" class="mt-2">
                                        @csrf
                                        @method('PATCH')
                                        <div class="flex items-center space-x-2">
                                            <flux:text size="sm" class="text-gray-600">Quantity:</flux:text>
                                            <flux:input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-16" />
                                            <flux:button type="submit" variant="outline" size="sm">
                                                Update
                                            </flux:button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Item Total -->
                                <div class="text-right">
                                    <flux:text size="lg" class="font-medium">
                                        ${{ number_format($item['total'], 2) }}
                                    </flux:text>
                                    <form action="{{ route('cart.remove', $key) }}" method="POST" class="mt-2">
                                        @csrf
                                        @method('DELETE')
                                        <flux:button type="submit" variant="ghost" size="sm" class="text-red-600 hover:text-red-700">
                                            Remove
                                        </flux:button>
                                    </form>
                                </div>
                            </flux:card>
                        @endforeach
                    </div>

                    <!-- Clear Cart -->
                    <div class="mt-6">
                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <flux:button type="submit" variant="ghost" class="text-red-600 hover:text-red-700" onclick="return confirm('Are you sure you want to clear your cart?')">
                                Clear Cart
                            </flux:button>
                        </form>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-5 mt-8 lg:mt-0">
                    <flux:card class="p-6">
                        <flux:heading size="lg" class="mb-4">Order Summary</flux:heading>

                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <flux:text class="text-gray-600">Subtotal</flux:text>
                                <flux:text class="font-medium">${{ number_format($subtotal, 2) }}</flux:text>
                            </div>

                            @php
                                $shipping = $subtotal >= 100 ? 0 : 9.99;
                                $tax = round($subtotal * 0.0825, 2);
                                $total = $subtotal + $shipping + $tax;
                            @endphp

                            <div class="flex justify-between">
                                <flux:text class="text-gray-600">Shipping</flux:text>
                                <flux:text class="font-medium">
                                    @if($shipping > 0)
                                        ${{ number_format($shipping, 2) }}
                                    @else
                                        Free
                                    @endif
                                </flux:text>
                            </div>

                            <div class="flex justify-between">
                                <flux:text class="text-gray-600">Tax</flux:text>
                                <flux:text class="font-medium">${{ number_format($tax, 2) }}</flux:text>
                            </div>

                            <flux:separator />

                            <div class="flex justify-between">
                                <flux:text size="lg" class="font-medium">Total</flux:text>
                                <flux:text size="lg" class="font-medium">${{ number_format($total, 2) }}</flux:text>
                            </div>
                        </div>

                        <!-- Coupon Code -->
                        <div class="mt-6">
                            <form action="{{ route('cart.coupon.apply') }}" method="POST">
                                @csrf
                                <div class="flex space-x-2">
                                    <flux:input type="text" name="coupon_code" placeholder="Coupon code" class="flex-1" />
                                    <flux:button type="submit" variant="outline">
                                        Apply
                                    </flux:button>
                                </div>
                            </form>
                        </div>

                        <!-- Checkout Button -->
                        <div class="mt-6">
                            @auth
                                <flux:button href="{{ route('checkout.index') }}" variant="primary" size="lg" class="w-full">
                                    Proceed to Checkout
                                </flux:button>
                            @else
                                <flux:button href="{{ route('login') }}" variant="primary" size="lg" class="w-full">
                                    Login to Checkout
                                </flux:button>
                            @endauth
                        </div>
                    </flux:card>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
