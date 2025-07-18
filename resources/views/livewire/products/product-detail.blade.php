<div class="max-w-6xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Product Images -->
        <div class="space-y-4">
            <!-- Main Image -->
            <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                @if($this->selectedImage)
                    <img
                        src="{{ $this->selectedImage['large'] }}"
                        alt="{{ $product->name }}"
                        class="w-full h-full object-cover"
                    >
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <x-heroicon-o-photo class="w-16 h-16 text-gray-400" />
                    </div>
                @endif
            </div>

            <!-- Thumbnail Images -->
            @if(count($this->images) > 1)
                <div class="flex space-x-2 overflow-x-auto">
                    @foreach($this->images as $index => $image)
                        <button
                            wire:click="selectImage({{ $index }})"
                            class="flex-shrink-0 w-20 h-20 rounded-md overflow-hidden border-2 transition-colors
                                   {{ $selectedImageIndex === $index ? 'border-blue-500' : 'border-gray-200' }}"
                        >
                            <img
                                src="{{ $image['thumb'] }}"
                                alt="{{ $product->name }}"
                                class="w-full h-full object-cover"
                            >
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Product Info -->
        <div class="space-y-6">
            <!-- Breadcrumb -->
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm">
                    <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">Home</a></li>
                    @foreach($product->categories as $category)
                        <li class="flex items-center">
                            <x-heroicon-m-chevron-right class="w-4 h-4 text-gray-400 mx-2" />
                            <a href="{{ route('category.show', $category->slug) }}" class="text-gray-500 hover:text-gray-700">
                                {{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                    <li class="flex items-center">
                        <x-heroicon-m-chevron-right class="w-4 h-4 text-gray-400 mx-2" />
                        <span class="text-gray-900">{{ $product->name }}</span>
                    </li>
                </ol>
            </nav>

            <!-- Product Title -->
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $product->name }}</h1>
                <p class="mt-2 text-gray-600">SKU: {{ $product->sku }}</p>
            </div>

            <!-- Price -->
            <div class="flex items-center space-x-3">
                <span class="text-3xl font-bold text-gray-900">
                    ${{ number_format($product->effective_price, 2) }}
                </span>
                @if($product->sale_price)
                    <span class="text-lg text-gray-500 line-through">
                        ${{ number_format($product->price, 2) }}
                    </span>
                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-sm font-medium">
                        {{ $product->discount_percentage }}% OFF
                    </span>
                @endif
            </div>

            <!-- Rating -->
            @if($product->reviews_count > 0)
                <div class="flex items-center space-x-2">
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                            <x-heroicon-s-star class="w-5 h-5 {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" />
                        @endfor
                    </div>
                    <span class="text-sm text-gray-600">
                        {{ number_format($product->average_rating, 1) }} ({{ $product->reviews_count }} reviews)
                    </span>
                </div>
            @endif

            <!-- Short Description -->
            @if($product->short_description)
                <div class="text-gray-600">
                    {{ $product->short_description }}
                </div>
            @endif

            <!-- Add to Cart Component -->
            <livewire:cart.add-to-cart :product="$product" />

            <!-- Wishlist Button -->
            @auth
                <livewire:user.wishlist-toggle :product="$product" />
            @endauth

            <!-- Product Details -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-900">Product Details</h3>

                @if($product->description)
                    <div class="prose prose-sm max-w-none text-gray-600">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                @endif

                <!-- Product Specifications -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    @if($product->weight)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Weight:</span>
                            <span class="font-medium">{{ $product->weight }} lbs</span>
                        </div>
                    @endif

                    @if($product->dimensions)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Dimensions:</span>
                            <span class="font-medium">{{ $product->dimensions }}</span>
                        </div>
                    @endif
                </div>

                <!-- Size Chart -->
                @if($product->categories->whereNotNull('size_charts')->isNotEmpty())
                    <button
                        wire:click="toggleSizeChart"
                        class="text-blue-600 hover:text-blue-700 underline text-sm"
                    >
                        View Size Chart
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Product Reviews -->
    <div class="mt-12">
        <livewire:products.product-reviews :product="$product" />
    </div>

    <!-- Related Products -->
    @if($this->relatedProducts->isNotEmpty())
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Products</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($this->relatedProducts as $relatedProduct)
                    <x-product-card :product="$relatedProduct" />
                @endforeach
            </div>
        </div>
    @endif

    <!-- Size Chart Modal -->
    @if($showSizeChart)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">Size Chart</h3>
                        <button
                            wire:click="toggleSizeChart"
                            class="text-gray-400 hover:text-gray-600"
                        >
                            <x-heroicon-m-x-mark class="w-6 h-6" />
                        </button>
                    </div>

                    <div class="space-y-4">
                        @foreach($product->categories as $category)
                            @foreach($category->sizeCharts as $sizeChart)
                                <div>
                                    <h4 class="font-medium mb-2">{{ $sizeChart->name }}</h4>
                                    @if($sizeChart->description)
                                        <p class="text-sm text-gray-600 mb-3">{{ $sizeChart->description }}</p>
                                    @endif

                                    <!-- Size Chart Table -->
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full border border-gray-200">
                                            <!-- Table content would be rendered from $sizeChart->measurements -->
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
