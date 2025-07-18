<x-app-layout>
    <x-slot name="title">Products - ShopWithCarl</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">All Products</h1>
            <p class="text-gray-600 mt-2">Discover our complete collection of amazing products</p>
        </div>

        <!-- Products Grid Component -->
        <livewire:products.product-grid />
    </div>
</x-app-layout>
