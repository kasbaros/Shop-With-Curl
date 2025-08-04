{{--<div class="container mx-auto px-4 py-8">--}}
{{--    <div class="mb-8">--}}
{{--        <h1 class="text-3xl font-bold text-gray-900 mb-4">Shop by Category</h1>--}}
{{--        <p class="text-gray-600 mb-6">Discover our wide range of products organized by category</p>--}}

{{--        <!-- Search Categories -->--}}
{{--        <div class="max-w-md">--}}
{{--            <div class="relative">--}}
{{--                <input--}}
{{--                    type="text"--}}
{{--                    wire:model.live="search"--}}
{{--                    placeholder="Search categories..."--}}
{{--                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"--}}
{{--                >--}}
{{--                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">--}}
{{--                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">--}}
{{--                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>--}}
{{--                    </svg>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <div wire:loading class="text-center py-4">--}}
{{--        <div class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-blue-600">--}}
{{--            <svg class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">--}}
{{--                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>--}}
{{--                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>--}}
{{--            </svg>--}}
{{--            Loading categories...--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    @if($categories->count() > 0)--}}
{{--        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" wire:loading.remove>--}}
{{--            @foreach($categories as $category)--}}
{{--                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105">--}}
{{--                    <a href="{{ route('categories.show', $category->slug) }}" wire:navigate class="block">--}}
{{--                        @if($category->image_url)--}}
{{--                            <div class="aspect-w-16 aspect-h-12 mb-4">--}}
{{--                                <img src="{{ $category->image_url }}"--}}
{{--                                     alt="{{ $category->name }}"--}}
{{--                                     class="w-full h-48 object-cover rounded-t-lg"--}}
{{--                                     loading="lazy">--}}
{{--                            </div>--}}
{{--                        @endif--}}

{{--                        <div class="p-4">--}}
{{--                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $category->name }}</h3>--}}

{{--                            @if($category->description)--}}
{{--                                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $category->description }}</p>--}}
{{--                            @endif--}}

{{--                            <div class="flex items-center justify-between">--}}
{{--                                <span class="text-sm text-gray-500">--}}
{{--                                    {{ $category->products_count }} {{ Str::plural('product', $category->products_count) }}--}}
{{--                                </span>--}}
{{--                                <span class="text-blue-600 hover:text-blue-800 text-sm font-medium">--}}
{{--                                    View Category →--}}
{{--                                </span>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--            @endforeach--}}
{{--        </div>--}}
{{--    @else--}}
{{--        <div class="text-center py-12" wire:loading.remove>--}}
{{--            <div class="max-w-md mx-auto">--}}
{{--                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">--}}
{{--                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>--}}
{{--                </svg>--}}
{{--                <h3 class="mt-4 text-lg font-medium text-gray-900">--}}
{{--                    @if($search)--}}
{{--                        No categories found matching "{{ $search }}"--}}
{{--                    @else--}}
{{--                        No categories available--}}
{{--                    @endif--}}
{{--                </h3>--}}
{{--                <p class="mt-2 text-gray-500">--}}
{{--                    @if($search)--}}
{{--                        Try adjusting your search terms.--}}
{{--                    @else--}}
{{--                        Categories will appear here once they are added by administrators.--}}
{{--                    @endif--}}
{{--                </p>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    @endif--}}
{{--</div>--}}

<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumbs -->
    <nav class="mb-8">
        <ol class="flex items-center space-x-2 text-sm text-gray-500">
            <li><a href="{{ route('home') }}" wire:navigate class="hover:text-blue-600">Home</a></li>
            <li>/</li>
            <li><a href="{{ route('categories.index') }}" wire:navigate class="hover:text-blue-600">Categories</a></li>
            @foreach($category->breadcrumbs as $breadcrumb)
                <li>/</li>
                <li class="{{ $loop->last ? 'text-gray-900 font-medium' : '' }}">
                    @if(!$loop->last)
                        <a href="{{ $breadcrumb['url'] }}" wire:navigate class="hover:text-blue-600">{{ $breadcrumb['name'] }}</a>
                    @else
                        {{ $breadcrumb['name'] }}
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>

    <!-- Category Header -->
    <div class="mb-8">
        @if($category->image_url)
            <div class="aspect-w-16 aspect-h-6 mb-6">
                <img src="{{ $category->image_url }}"
                     alt="{{ $category->name }}"
                     class="w-full h-64 object-cover rounded-lg">
            </div>
        @endif

        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $category->name }}</h1>

        @if($category->description)
            <p class="text-gray-600 text-lg">{{ $category->description }}</p>
        @endif
    </div>

    <!-- Subcategories -->
    @if($category->children->count() > 0)
        <div class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-semibold text-gray-900">Subcategories</h2>
                <button
                    wire:click="toggleSubcategories"
                    class="text-blue-600 hover:text-blue-800 font-medium"
                >
                    {{ $showSubcategories ? 'Hide' : 'Show' }} Subcategories
                </button>
            </div>

            @if($showSubcategories)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" wire:transition>
                    @foreach($category->children as $child)
                        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
                            <a href="{{ route('categories.show', $child->slug) }}" wire:navigate class="block p-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $child->name }}</h3>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">
                                        {{ $child->products_count }} {{ Str::plural('product', $child->products_count) }}
                                    </span>
                                    <span class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        View →
                                    </span>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    <!-- Products in this category -->
    <div>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-semibold text-gray-900">Products</h2>
            <a href="{{ route('shop.index', ['category' => $category->slug]) }}"
               wire:navigate
               class="text-blue-600 hover:text-blue-800 font-medium">
                View all products in {{ $category->name }} →
            </a>
        </div>

        @if($products->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                        <a href="{{ route('products.show', $product->slug) }}" wire:navigate class="block">
                            @if($product->image_url)
                                <div class="aspect-w-1 aspect-h-1 mb-4">
                                    <img src="{{ $product->image_url }}"
                                         alt="{{ $product->name }}"
                                         class="w-full h-48 object-cover rounded-t-lg"
                                         loading="lazy">
                                </div>
                            @endif

                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $product->name }}</h3>
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $product->short_description }}</p>

                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-bold text-gray-900">
                                        ${{ number_format($product->price, 2) }}
                                    </span>
                                    @if($product->compare_price && $product->compare_price > $product->price)
                                        <span class="text-sm text-gray-500 line-through">
                                            ${{ number_format($product->compare_price, 2) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            @if($hasMoreProducts)
                <div class="text-center mt-8">
                    <button
                        wire:click="loadMoreProducts"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors duration-200"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove wire:target="loadMoreProducts">Load More Products</span>
                        <span wire:loading wire:target="loadMoreProducts">Loading...</span>
                    </button>
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <div class="max-w-md mx-auto">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No products in this category</h3>
                    <p class="mt-2 text-gray-500">Products will appear here once they are added to this category.</p>
                </div>
            </div>
        @endif
    </div>
</div>

