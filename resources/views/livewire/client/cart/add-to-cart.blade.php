<div class="space-y-6">
    @if($showVariantSelection)
        <div class="space-y-4">
            <!-- Size Selection -->
            @if($this->availableSizes->isNotEmpty())
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Size</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($this->availableSizes as $size)
                            <button
                                type="button"
                                wire:click="$set('selectedSize', '{{ $size }}')"
                                class="px-3 py-2 border rounded-md text-sm font-medium transition-colors
                                       {{ $selectedSize === $size
                                          ? 'border-blue-500 bg-blue-50 text-blue-700'
                                          : 'border-gray-300 text-gray-700 hover:border-gray-400' }}"
                            >
                                {{ $size }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Color Selection -->
            @if($this->availableColors->isNotEmpty())
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($this->availableColors as $color)
                            <button
                                type="button"
                                wire:click="$set('selectedColor', '{{ $color }}')"
                                class="px-3 py-2 border rounded-md text-sm font-medium transition-colors
                                       {{ $selectedColor === $color
                                          ? 'border-blue-500 bg-blue-50 text-blue-700'
                                          : 'border-gray-300 text-gray-700 hover:border-gray-400' }}"
                            >
                                {{ $color }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Material Selection -->
            @if($this->availableMaterials->isNotEmpty())
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Material</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($this->availableMaterials as $material)
                            <button
                                type="button"
                                wire:click="$set('selectedMaterial', '{{ $material }}')"
                                class="px-3 py-2 border rounded-md text-sm font-medium transition-colors
                                       {{ $selectedMaterial === $material
                                          ? 'border-blue-500 bg-blue-50 text-blue-700'
                                          : 'border-gray-300 text-gray-700 hover:border-gray-400' }}"
                            >
                                {{ $material }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Variant Info -->
            @if($this->currentVariant)
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Selected: {{ $this->currentVariant->display_name }}</span>
                        <span class="text-sm font-medium">
                            Stock: {{ $this->currentVariant->stock_quantity }}
                        </span>
                    </div>
                    @if($this->currentVariant->price)
                        <div class="mt-2">
                            <span class="text-lg font-bold text-gray-900">
                                ${{ number_format($this->currentVariant->effective_price, 2) }}
                            </span>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    @endif

    <!-- Quantity Selection -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
        <div class="flex items-center space-x-3">
            <button
                type="button"
                wire:click="$set('quantity', {{ max(1, $quantity - 1) }})"
                class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-md hover:border-gray-400"
                {{ $quantity <= 1 ? 'disabled' : '' }}
            >
                <x-heroicon-m-minus class="w-4 h-4" />
            </button>

            <input
                type="number"
                wire:model.live="quantity"
                min="1"
                class="w-16 text-center border border-gray-300 rounded-md px-2 py-1"
            >

            <button
                type="button"
                wire:click="$set('quantity', {{ $quantity + 1 }})"
                class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-md hover:border-gray-400"
            >
                <x-heroicon-m-plus class="w-4 h-4" />
            </button>
        </div>
        @error('quantity')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Add to Cart Button -->
    <div class="space-y-3">
        <button
            type="button"
            wire:click="addToCart"
            class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-blue-700 transition-colors
                   {{ (!$product->is_in_stock || ($showVariantSelection && !$selectedVariant)) ? 'opacity-50 cursor-not-allowed' : '' }}"
            {{ (!$product->is_in_stock || ($showVariantSelection && !$selectedVariant)) ? 'disabled' : '' }}
        >
            @if(!$product->is_in_stock)
                Out of Stock
            @else
                Add to Cart - ${{ number_format($this->currentVariant?->effective_price ?? $product->effective_price, 2) }}
            @endif
        </button>

        @error('variant')
        <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Stock Status -->
    @if($product->is_in_stock)
        <div class="text-sm text-green-600 flex items-center">
            <x-heroicon-s-check-circle class="w-4 h-4 mr-1" />
            In Stock
            @if($product->manage_stock)
                ({{ $this->currentVariant?->stock_quantity ?? $product->stock_quantity }} available)
            @endif
        </div>
    @else
        <div class="text-sm text-red-600 flex items-center">
            <x-heroicon-s-x-circle class="w-4 h-4 mr-1" />
            Out of Stock
        </div>
    @endif
</div>
