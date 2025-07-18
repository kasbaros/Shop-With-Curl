<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white p-6 rounded-lg shadow-sm border">
        <div class="flex flex-wrap items-center gap-4">
            <!-- Category Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select wire:model.live="selectedCategory" class="border border-gray-300 rounded-md px-3 py-2">
                    <option value="">All Categories</option>
                    @foreach($this->categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }} ({{ $category->products_count }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Price Range -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Price Range</label>
                <div class="flex items-center space-x-2">
                    <input
                        type="number"
                        wire:model.live="minPrice"
                        placeholder="Min"
                        class="w-20 border border-gray-300 rounded-md px-2 py-2"
                    >
                    <span class="text-gray-500">-</span>
                    <input
                        type="number"
                        wire:model.live="maxPrice"
                        placeholder="Max"
                        class="w-20 border border-gray-300 rounded-md px-2 py-2"
                    >
                </div>
            </div>

            <!-- Filter Checkboxes -->
            <div class="flex flex-wrap gap-4">
                <label class="flex items-center">
                    <input type="checkbox" wire:model.live="inStockOnly" class="mr-2">
                    <span class="text-sm">In Stock Only</span>
                </label>

                <label class="flex items-center">
                    <input type="checkbox" wire:model.live="onSaleOnly" class="mr-2">
                    <span class="text-sm">On Sale</span>
                </label>

                <label class="flex items-center">
                    <input type="checkbox" wire:model.live="featuredOnly" class="mr-2">
                    <span class="text-sm">Featured</span>
                </label>
            </div>

            <!-- Clear Filters -->
            <button
                wire:click="clearFilters"
                class="text-sm text-blue-600 hover:text-blue-700 underline"
            >
                Clear Filters
            </button>
        </div>
    </div>

    <!-- Sort Options -->
    <div class="flex justify-between items-center">
        <p class="text-sm text-gray-600">
            Showing {{ $this->products->firstItem() ?? 0 }} to {{ $this->products->lastItem() ?? 0 }}
            of {{ $this->products->total() }} products
        </p>

        <div class="flex items-center space-x-2">
            <label class="text-sm font-medium text-gray-700">Sort by:</label>
            <select
                wire:change="setSortBy($event.target.value.split('|')[0], $event.target.value.split('|')[1])"
                class="border border-gray-300 rounded-md px-3 py-2"
            >
                <option value="name|asc" {{ $sortBy === 'name' && $sortOrder === 'asc' ? 'selected' : '' }}>
                    Name (A-Z)
                </option>
                <option value="name|desc" {{ $sortBy === 'name' && $sortOrder === 'desc' ? 'selected' : '' }}>
                    Name (Z-A)
                </option>
                <option value="price|asc" {{ $sortBy === 'price' && $sortOrder === 'asc' ? 'selected' : '' }}>
                    Price (Low to High)
                </option>
                <option value="price|desc" {{ $sortBy === 'price' && $sortOrder === 'desc' ? 'selected' : '' }}>
                    Price (High to Low)
                </option>
                <option value="created_at|desc" {{ $sortBy === 'created_at' && $sortOrder === 'desc' ? 'selected' : '' }}>
                    Newest First
                </option>
            </select>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($this->products as $product)
            <x-product-card :product="$product" />
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $this->products->links() }}
    </div>

    <!-- No Products Message -->
    @if($this->products->isEmpty())
        <div class="text-center py-12">
            <x-heroicon-o-cube class="w-16 h-16 mx-auto text-gray-400 mb-4" />
            <h3 class="text-lg font-medium text-gray-900 mb-2">No products found</h3>
            <p class="text-gray-500">Try adjusting your filters or search terms.</p>
        </div>
    @endif
</div>
