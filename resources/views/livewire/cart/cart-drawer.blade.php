<div>
    <!-- Overlay -->
    <div
        class="fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity {{ $isOpen ? 'opacity-100' : 'opacity-0 pointer-events-none' }}"
        wire:click="toggleDrawer"
    ></div>

    <!-- Drawer -->
    <div
        class="fixed right-0 top-0 h-full w-96 bg-white shadow-xl transform transition-transform z-50 {{ $isOpen ? 'translate-x-0' : 'translate-x-full' }}"
    >
        <div class="flex flex-col h-full">
            <!-- Header -->
            <div class="flex items-center justify-between p-4 border-b">
                <h2 class="text-lg font-semibold">Shopping Cart</h2>
                <button
                    wire:click="toggleDrawer"
                    class="p-2 hover:bg-gray-100 rounded-md"
                >
                    <x-heroicon-m-x-mark class="w-5 h-5"/>
                </button>
            </div>

            <!-- Cart Items -->
            <div class="flex-1 overflow-y-auto p-4">
                @if(empty($cart))
                    <div class="text-center py-8">
                        <x-heroicon-o-shopping-bag class="w-16 h-16 mx-auto text-gray-400 mb-4"/>
                        <p class="text-gray-500">Your cart is empty</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($cart as $item)
                            <div class="flex items-center space-x-4 p-3 border rounded-lg">
                                <img
                                    src="{{ $item['image'] }}"
                                    alt="{{ $item['name'] }}"
                                    class="w-16 h-16 object-cover rounded-md"
                                >
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-medium text-gray-900 truncate">
                                        {{ $item['name'] }}
                                    </h3>
                                    <p class="text-sm text-gray-500">
                                        ${{ number_format($item['price'], 2) }}
                                    </p>
                                    <div class="flex items-center space-x-2 mt-2">
                                        <button
                                            wire:click="updateQuantity('{{ $item['key'] }}', {{ $item['quantity'] - 1 }})"
                                            class="w-6 h-6 flex items-center justify-center border border-gray-300 rounded text-xs hover:border-gray-400"
                                        >
                                            <x-heroicon-m-minus class="w-3 h-3"/>
                                        </button>
                                        <span class="text-sm font-medium">{{ $item['quantity'] }}</span>
                                        <button
                                            wire:click="updateQuantity('{{ $item['key'] }}', {{ $item['quantity'] + 1 }})"
                                            class="w-6 h-6 flex items-center justify-center border border-gray-300 rounded text-xs hover:border-gray-400"
                                        >
                                            <x-heroicon-m-plus class="w-3 h-3"/>
                                        </button>
                                    </div>
                                </div>
                                <button
                                    wire:click="removeItem('{{ $item['key'] }}')"
                                    class="p-1 text-red-600 hover:text-red-700"
                                >
                                    <x-heroicon-m-trash class="w-4 h-4"/>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Footer -->
            @if(!empty($cart))
                <div class="border-t p-4 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-medium">Subtotal:</span>
                        <span class="text-lg font-bold">${{ number_format($total, 2) }}</span>
                    </div>
                    <div class="space-y-2">
                        <a
                            href="{{ route('cart.index') }}"
                            class="w-full bg-gray-200 text-gray-800 py-2 px-4 rounded-lg font-medium text-center block hover:bg-gray-300 transition-colors"
                        >
                            View Cart
                        </a>
                        <a
                            href="{{ route('checkout.index') }}"
                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg font-medium text-center block hover:bg-blue-700 transition-colors"
                        >
                            Checkout
                        </a>
                    </div>
                    <button
                        wire:click="clearCart"
                        class="w-full text-red-600 py-2 text-sm hover:text-red-700 transition-colors"
                    >
                        Clear Cart
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
