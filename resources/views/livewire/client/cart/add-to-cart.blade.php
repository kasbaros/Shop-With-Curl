<div class="space-y-6">
    <style>
        /* Color Swatch Fixes */
        .color-btn {
            width: 32px !important;
            height: 32px !important;
            border: 2px solid #e5e7eb !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            position: relative !important;
            transition: all 0.2s ease !important;
        }

        .color-btn.active {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 2px #3b82f6 !important;
        }

        .color-btn:hover {
            transform: scale(1.1) !important;
        }

        /* Remove old CSS class dependencies */
        .color-btn .btn-checkbox {
            display: none !important;
        }

        /* Size button active state */
        .size-btn.active {
            background-color: #3b82f6 !important;
            color: white !important;
        }

        /* Material buttons consistency */
        .material-selector .size-btn {
            margin: 2px;
        }

        /* Loading state */
        wire\:loading {
            opacity: 0.7;
        }
    </style>

    @if($showVariantSelection)
        <div class="space-y-4">
            <!-- Size Selection -->
            @if($this->availableSizes->isNotEmpty())
                <div class="variant-picker-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="variant-picker-label">
                            Size: <span
                                class="fw-6 variant-picker-label-value">{{ $selectedSize ?? 'Select Size' }}</span>
                        </div>
                    </div>
                    <div class="variant-picker-values">
                        @foreach($this->availableSizes as $size)
                            <input type="radio"
                                   name="size"
                                   id="size-{{ $size }}"
                                   wire:model.live="selectedSize"
                                   value="{{ $size }}"
                                   class="d-none">
                            <label class="style-text size-btn {{ $selectedSize === $size ? 'active' : '' }}"
                                   for="size-{{ $size }}">
                                <p>{{ $size }}</p>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Color Selection -->
            @if($this->availableColors->isNotEmpty())
                <div class="variant-picker-item mt-3">
                    <div class="variant-picker-label">
                        Color: <span
                            class="fw-6 variant-picker-label-value value-currentColor">{{ $selectedColor ?? 'Select Color' }}</span>
                    </div>
                    <div class="variant-picker-values">
                        @foreach($this->availableColors as $colorObj)
                            <input id="color-{{ $colorObj['name'] }}"
                                   type="radio"
                                   name="color"
                                   value="{{ $colorObj['name'] }}"
                                   wire:model.live="selectedColor"
                                   class="d-none"
                                   @if($selectedColor === $colorObj['name']) checked @endif>
                            <label
                                class="hover-tooltip radius-60 color-btn {{ $selectedColor === $colorObj['name'] ? 'active' : '' }}"
                                for="color-{{ $colorObj['name'] }}"
                                data-value="{{ $colorObj['name'] }}"
                                style="background-color: {{ $colorObj['hex_code'] }} !important;"
                                title="{{ $colorObj['name'] }}">
                                <span class="tooltip">{{ $colorObj['name'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Material Selection -->
            @if($this->availableMaterials->isNotEmpty())
                <div class="mt-3">
                    <label class="variant-picker-label">Material: <span
                            class="fw-6">{{ $selectedMaterial ?? 'Select Material' }}</span></label>
                    <div class="variant-picker-values">
                        @foreach($this->availableMaterials as $material)
                            <input type="radio"
                                   name="material"
                                   id="material-{{ $material }}"
                                   wire:model.live="selectedMaterial"
                                   value="{{ $material }}"
                                   class="d-none">
                            <label class="style-text size-btn {{ $selectedMaterial === $material ? 'active' : '' }}"
                                   for="material-{{ $material }}">
                                <p>{{ $material }}</p>
                            </label>
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
                                UGX {{ number_format($this->currentVariant->effective_price, 0) }}
                            </span>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    @endif

    <!-- Quantity Selection -->
    <div class="tf-product-info-quantity mt-2">
        <div class="quantity-title fw-6">Quantity</div>
        <div class="wg-quantity">
            <span class="btn-quantity btn-decrease {{ $quantity <= 1 ? 'disabled opacity-50 cursor-not-allowed' : '' }}"
                  wire:click="$set('quantity', {{ max(1, $quantity - 1) }})">-</span>
            <input type="text"
                   class="quantity-product"
                   wire:model.live="quantity"
                   value="{{ $quantity }}">
            <span class="btn-quantity btn-increase"
                  wire:click="$set('quantity', {{ $quantity + 1 }})">+</span>
        </div>
        @error('quantity')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Add to Cart Button -->
    <div class="tf-product-info-buy-button">
        @php($privileged = (auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isDeveloper())))
        @if($privileged)
            <a href="{{ route('admin.dashboard') }}"
               class="tf-btn btn-outline-secondary justify-content-center fw-6 fs-16 flex-grow-1 btn-add-to-cart opacity-50 cursor-not-allowed">
                <span>Cart disabled for admin/developer</span>
            </a>
        @else
            <a href="#"
               wire:click.prevent="addToCart"
               class="tf-btn btn-fill d-flex justify-content-center fw-6 fs-16 w-100 animate-hover-btn btn-add-to-cart {{ (!$product->is_in_stock || ($showVariantSelection && !$selectedVariant)) ? 'opacity-50 cursor-not-allowed' : '' }}">
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
        @error('variant')
        <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Stock Status -->
    @if($product->is_in_stock)
        <div class="text-sm text-green-600 d-flex align-items-center">
            <x-heroicon-s-check-circle class="me-2 text-success" style="width: 18px; height: 18px;"/>
            <span>In Stock
                @if($product->manage_stock)
                    ({{ $this->currentVariant?->stock_quantity ?? $product->stock_quantity }} available)
                @endif
            </span>
        </div>
    @else
        <div class="text-sm text-red-600 d-flex align-items-center">
            <x-heroicon-s-x-circle class="me-2 text-danger" style="width: 18px; height: 18px;"/>
            <span>Out of Stock</span>
        </div>
    @endif
</div>
