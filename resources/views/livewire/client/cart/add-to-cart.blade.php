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

    <!-- Quantity Selection (Redesigned) -->
    <div class="tf-product-info-quantity">
        <div class="quantity-title fw-6">Quantity</div>
        <div class="wg-quantity">
            <span class="btn-quantity btn-decrease {{ $quantity <= 1 ? 'disabled opacity-50 cursor-not-allowed' : '' }}"
                  role="button"
                  aria-label="Decrease quantity"
                  aria-disabled="{{ $quantity <= 1 ? 'true' : 'false' }}"
                  wire:click="$set('quantity', {{ max(1, $quantity - 1) }})">-</span>

            <input type="text"
                   class="quantity-product"
                   name="number"
                   inputmode="numeric"
                   pattern="[0-9]*"
                   wire:model.live="quantity"
                   aria-label="Quantity"
            >

            <span class="btn-quantity btn-increase"
                  role="button"
                  aria-label="Increase quantity"
                  wire:click="$set('quantity', {{ $quantity + 1 }})">+</span>
        </div>
        @error('quantity')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Add to Cart Button (Redesigned) -->
    <div class="tf-product-info-buy-button">
        @php($privileged = (auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isDeveloper())))
        <form class="">
            @if($privileged)
                <a href="{{ route('admin.dashboard') }}"
                   class="tf-btn btn-outline-secondary justify-content-center fw-6 fs-16 flex-grow-1 btn-add-to-cart opacity-50 cursor-not-allowed"
                   aria-disabled="true"
                   title="Admins and developers cannot add items to cart">
                    <span>Cart disabled for admin/developer</span>
                </a>
            @else
                <a href="#"
                   wire:click.prevent="addToCart"
                   class="tf-btn btn-fill justify-content-center fw-6 fs-16 flex-grow-1 animate-hover-btn btn-add-to-cart {{ (!$product->is_in_stock || ($showVariantSelection && !$selectedVariant)) ? 'opacity-50 cursor-not-allowed' : '' }}"
                    {{ (!$product->is_in_stock || ($showVariantSelection && !$selectedVariant)) ? 'aria-disabled=true' : '' }}>
                    <span>
                        @if(!$product->is_in_stock)
                            Out of Stock -&nbsp;
                        @else
                            Add to cart -&nbsp;
                        @endif
                    </span>
                    <span class="tf-qty-price total-price">
                        {{ money_format_ugx((float)($this->currentVariant?->effective_price ?? $product->effective_price)) }}
                    </span>
                </a>
            @endif

            @if($privileged)
                <a href="{{ route('admin.dashboard') }}" class="tf-product-btn-wishlist hover-tooltip box-icon bg_white wishlist btn-icon-action opacity-50 cursor-not-allowed" aria-disabled="true" title="Wishlist disabled for admin/developer">
                    <span class="icon icon-heart"></span>
                    <span class="tooltip">Wishlist disabled</span>
                    <span class="icon icon-delete"></span>
                </a>
            @else
                <a href="#" class="tf-product-btn-wishlist hover-tooltip box-icon bg_white wishlist btn-icon-action">
                    <span class="icon icon-heart"></span>
                    <span class="tooltip">Add to Wishlist</span>
                    <span class="icon icon-delete"></span>
                </a>
            @endif

            <a href="#compare" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft"
               class="tf-product-btn-wishlist hover-tooltip box-icon bg_white compare btn-icon-action {{ $privileged ? 'opacity-50 cursor-not-allowed' : '' }}"
               @if($privileged) aria-disabled="true" title="Compare disabled for admin/developer" onclick="return false;" @endif>
                <span class="icon icon-compare"></span>
                <span class="tooltip">Add to Compare</span>
                <span class="icon icon-check"></span>
            </a>
            <div class="w-100">
                <a href="#" class="btns-full">Buy with <img src="images/payments/paypal.png" alt=""></a>
                <a href="#" class="payment-more-option">More payment options</a>
            </div>

            @error('variant')
            <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </form>
    </div>

    <!-- Stock Status -->
    @if($product->is_in_stock)
        <div class="text-sm text-green-600 d-flex align-items-center">
            <x-heroicon-s-check-circle class="me-2 text-success" style="width: 18px; height: 18px; flex-shrink: 0;"/>
            <span>In Stock
            @if($product->manage_stock)
                    ({{ $this->currentVariant?->stock_quantity ?? $product->stock_quantity }} available)
                @endif</span>
        </div>
    @else
        <div class="text-sm text-red-600 d-flex align-items-center">
            <x-heroicon-s-x-circle class="me-2 text-danger" style="width: 18px; height: 18px; flex-shrink: 0;"/>
            <span>Out of Stock</span>
        </div>
    @endif
</div>
