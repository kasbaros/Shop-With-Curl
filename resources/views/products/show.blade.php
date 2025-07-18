<x-app-layout>
    <x-slot name="title">{{ $product->name }} - ShopWithCarl</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <livewire:products.product-detail :product="$product" />
    </div>
</x-app-layout>
