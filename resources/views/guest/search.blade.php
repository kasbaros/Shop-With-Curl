<x-app-layout>
    <x-slot name="title">Search Results for "{{ $query }}" - ShopWithCarl</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Search Results</h1>
            <p class="text-gray-600 mt-2">Results for "{{ $query }}"</p>
        </div>

        <!-- Search Results -->
        <livewire:products.product-grid :search-query="$query" />
    </div>
</x-app-layout>
