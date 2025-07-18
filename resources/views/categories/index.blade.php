<x-app-layout>
    <x-slot name="title">Categories - ShopWithCarl</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">All Categories</h1>
            <p class="text-gray-600 mt-2">Browse our product categories</p>
        </div>

        <!-- Categories Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($categories as $category)
                <a href="{{ route('category.show', $category->slug) }}" class="group">
                    <div class="bg-white rounded-lg shadow-sm border overflow-hidden hover:shadow-lg transition-shadow">
                        <div class="aspect-video bg-gray-200 relative">
                            @if($category->image)
                                <img src="{{ $category->image }}" alt="{{ $category->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <x-heroicon-o-folder class="w-16 h-16 text-gray-400" />
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $category->name }}</h3>
                            <p class="text-gray-600 mb-4">{{ $category->description }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">{{ $category->products_count }} products</span>
                                <span class="text-blue-600 font-medium group-hover:text-blue-700">
                                    View Products →
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</x-app-layout>
