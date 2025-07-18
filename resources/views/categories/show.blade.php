<x-app-layout>
    <x-slot name="title">{{ $category->name }} - ShopWithCarl</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Category Header -->
        <div class="mb-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm">
                    <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">Home</a></li>
                    <li class="flex items-center">
                        <x-heroicon-m-chevron-right class="w-4 h-4 text-gray-400 mx-2" />
                        <a href="{{ route('categories.index') }}" class="text-gray-500 hover:text-gray-700">Categories</a>
                    </li>
                    <li class="flex items-center">
                        <x-heroicon-m-chevron-right class="w-4 h-4 text-gray-400 mx-2" />
                        <span class="text-gray-900">{{ $category->name }}</span>
                    </li>
                </ol>
            </nav>

            <div class="flex items-center space-x-6">
                @if($category->image)
                <img src="{{ $category->image }}" alt="{{ $category->name }}" class="w-24 h-24 object-cover rounded-lg">
                @endif
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $category->name }}</h1>
                    @if($category->description)
                    <p class="text-gray-600 mt-2">{{ $category->description }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Subcategories -->
        @if($category->children->count() > 0)
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Subcategories</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($category->children as $subcategory)
                <a href="{{ route('category.show', $subcategory->slug) }}" class="group">
                    <div class="bg-white rounded-lg shadow-sm border p-4 hover:shadow-md transition-shadow">
                        <div class="text-center">
                            @if($subcategory->image)
                            <img src="{{ $subcategory->image }}" alt="{{ $subcategory->name }}" class="w-12 h-12 object-cover rounded-lg mx-auto mb-2">
                            @else
                            <div class="w-12 h-12 bg-gray-200 rounded-lg mx-auto mb-2 flex items-center justify-center">
                                <x-heroicon-o-folder class="w-6 h-6 text-gray-400" />
                            </div>
                            @endif
                            <h3 class="font-medium text-gray-900 text-sm">{{ $subcategory->name }}</h3>
                            <p class="text-xs text-gray-500">{{ $subcategory->products_count }} products</p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Products -->
        <livewire:products.product-grid :category="$category" />
    </div>
</x-app-layout>
