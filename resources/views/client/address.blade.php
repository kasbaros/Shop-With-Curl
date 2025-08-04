<x-app-layout>
    <x-slot name="title">My Addresses - ShopWithCarl</x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Addresses</h1>
            <button onclick="document.getElementById('add-address-modal').classList.remove('hidden')"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                Add New Address
            </button>
        </div>

        @if($addresses->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($addresses as $address)
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $address->name }}</h3>
                                @if($address->company)
                                    <p class="text-sm text-gray-600">{{ $address->company }}</p>
                                @endif
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($address->is_default)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Default
                                    </span>
                                @endif
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ ucfirst($address->type) }}
                                </span>
                            </div>
                        </div>

                        <div class="text-gray-600 mb-4">
                            <p>{{ $address->address_line_1 }}</p>
                            @if($address->address_line_2)
                                <p>{{ $address->address_line_2 }}</p>
                            @endif
                            <p>{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                            <p>{{ $address->country }}</p>
                        </div>

                        <div class="flex space-x-2">
                            <button onclick="editAddress({{ $address->id }})"
                                    class="text-blue-600 hover:text-blue-700 text-sm">
                                Edit
                            </button>
                            <form action="{{ route('user.addresses.delete', $address) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Are you sure you want to delete this address?')"
                                        class="text-red-600 hover:text-red-700 text-sm">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-medium text-gray-900 mb-2">No addresses saved</h2>
                <p class="text-gray-600 mb-4">Add your first address for faster checkout</p>
                <button onclick="document.getElementById('add-address-modal').classList.remove('hidden')"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Add Address
                </button>
            </div>
        @endif
    </div>

    <!-- Add Address Modal -->
    <div id="add-address-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-lg p-6 w-full max-w-md">
                <h2 class="text-lg font-semibold mb-4">Add New Address</h2>

                <form action="{{ route('user.addresses.store') }}" method="POST">
                    @csrf

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select name="type" class="w-full border border-gray-300 rounded-md px-3 py-2">
                                <option value="shipping">Shipping</option>
                                <option value="billing">Billing</option>
                                <option value="both">Both</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input type="text" name="name" class="w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Company (Optional)</label>
                            <input type="text" name="company" class="w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 1</label>
                            <input type="text" name="address_line_1" class="w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 2 (Optional)</label>
                            <input type="text" name="address_line_2" class="w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                <input type="text" name="city" class="w-full border border-gray-300 rounded-md px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">State</label>
                                <input type="text" name="state" class="w-full border border-gray-300 rounded-md px-3 py-2">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
                                <input type="text" name="postal_code" class="w-full border border-gray-300 rounded-md px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                                <input type="text" name="country" value="United States" class="w-full border border-gray-300 rounded-md px-3 py-2">
                            </div>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="is_default" value="1" class="h-4 w-4 text-blue-600 rounded">
                            <label class="ml-2 text-sm text-gray-700">Set as default address</label>
                        </div>
                    </div>

                    <div class="flex space-x-4 mt-6">
                        <button type="button"
                                onclick="document.getElementById('add-address-modal').classList.add('hidden')"
                                class="flex-1 bg-gray-200 text-gray-800 py-2 rounded-lg hover:bg-gray-300 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Add Address
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
