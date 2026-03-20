<div>
    @if($showVariantSelection)
        <div class="tf-product-info-variant-picker">
            <!-- Color Selection -->
            @if($this->availableColors->isNotEmpty())
                <div class="variant-picker-item">
                    <div class="variant-picker-label">
                        Color: <span class="fw-6 variant-picker-label-value value-currentColor">{{ $selectedColor ?? 'Select Color' }}</span>
                    </div>
                    <div class="variant-picker-values">
                        @foreach($this->availableColors as $colorObj)
                            <input id="color-{{ Str::slug($colorObj['name']) }}"
                                   type="radio"
                                   name="color"
                                   value="{{ $colorObj['name'] }}"
                                   wire:model.live="selectedColor"
                                   @if($selectedColor === $colorObj['name']) checked @endif>
                            <label class="hover-tooltip radius-60 color-btn"
                                   for="color-{{ Str::slug($colorObj['name']) }}"
                                   data-value="{{ $colorObj['name'] }}">
                                <span class="btn-checkbox" style="background-color: {{ $colorObj['hex_code'] }}"></span>
                                <span class="tooltip">{{ $colorObj['name'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Size Selection -->
            @if($this->availableSizes->isNotEmpty())
                <div class="variant-picker-item">
                    <div class="variant-picker-label">
                        Size: <span class="fw-6 variant-picker-label-value">{{ $selectedSize ?? 'Select Size' }}</span>
                    </div>
                    <div class="variant-picker-values">
                        @foreach($this->availableSizes as $size)
                            <input type="radio"
                                   name="size"
                                   id="size-{{ Str::slug($size) }}"
                                   wire:model.live="selectedSize"
                                   value="{{ $size }}"
                                   @if($selectedSize === $size) checked @endif>
                            <label class="style-text size-btn"
                                   for="size-{{ Str::slug($size) }}"
                                   data-value="{{ $size }}">
                                <p>{{ $size }}</p>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Material Selection -->
            @if($this->availableMaterials->isNotEmpty())
                <div class="variant-picker-item">
                    <div class="variant-picker-label">
                        Material: <span class="fw-6 variant-picker-label-value">{{ $selectedMaterial ?? 'Select Material' }}</span>
                    </div>
                    <div class="variant-picker-values">
                        @foreach($this->availableMaterials as $material)
                            <input type="radio"
                                   name="material"
                                   id="material-{{ Str::slug($material) }}"
                                   wire:model.live="selectedMaterial"
                                   value="{{ $material }}"
                                   @if($selectedMaterial === $material) checked @endif>
                            <label class="style-text size-btn"
                                   for="material-{{ Str::slug($material) }}"
                                   data-value="{{ $material }}">
                                <p>{{ $material }}</p>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Variant Info -->
        @if($this->currentVariant)
            <div class="tf-product-info-variant-status mt_8">
                <span class="text_black-2 fs-14">Selected: {{ $this->currentVariant->display_name }}</span>
                @if($this->currentVariant->stock_quantity > 0)
                    <span class="text_black-2 fs-14 ms-2">— {{ $this->currentVariant->stock_quantity }} in stock</span>
                @endif
            </div>
        @endif
    @endif

    <!-- Quantity -->
    <div class="tf-product-info-quantity mt_16">
        <div class="quantity-title fw-6">Quantity</div>
        <div class="wg-quantity">
            <span class="btn-quantity btn-decrease"
                  wire:click="$set('quantity', {{ max(1, $quantity - 1) }})">-</span>
            <input type="text"
                   class="quantity-product"
                   wire:model.live="quantity"
                   name="number"
                   value="{{ $quantity }}">
            <span class="btn-quantity btn-increase"
                  wire:click="$set('quantity', {{ $quantity + 1 }})">+</span>
        </div>
    </div>

    <!-- Add to Cart + Wishlist + Compare (same row) -->
    <div class="tf-product-info-buy-button">
        <form class="">
            @php($privileged = (auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isDeveloper())))
            @if($privileged)
                <a href="{{ route('admin.dashboard') }}"
                   class="tf-btn btn-outline-secondary justify-content-center fw-6 fs-16 flex-grow-1 btn-add-to-cart opacity-50">
                    <span>Cart disabled for admin/developer</span>
                </a>
            @else
                <a href="javascript:void(0)"
                   wire:click.prevent="addToCart"
                   class="tf-btn btn-fill justify-content-center fw-6 fs-16 flex-grow-1 animate-hover-btn btn-add-to-cart {{ (!$product->is_in_stock || ($showVariantSelection && !$selectedVariant)) ? 'opacity-50' : '' }}">
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
            @auth
                <livewire:client.wish-list-toggle :product="$product"/>
            @else
                <a href="{{ route('login') }}"
                   class="tf-product-btn-wishlist hover-tooltip box-icon bg_white wishlist btn-icon-action">
                    <span class="icon icon-heart"></span>
                    <span class="tooltip">Add to Wishlist</span>
                </a>
            @endauth
            <a href="#compare" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft"
               class="tf-product-btn-wishlist hover-tooltip box-icon bg_white compare btn-icon-action"
               onclick="if(window.Livewire?.dispatch){window.Livewire.dispatch('compare:toggle',{id: {{ (int)$product->id }} });}">
                <span class="icon icon-compare"></span>
                <span class="tooltip">Add to Compare</span>
                <span class="icon icon-check"></span>
            </a>
        </form>
        @error('variant')
            <p class="text-sm text-danger mt_4">{{ $message }}</p>
        @enderror
    </div>

    <!-- Stock Status -->
    @if($product->is_in_stock)
        <div class="d-flex align-items-center gap-6 mt_8 text-success fs-14">
            <i class="icon-check"></i>
            <span>In Stock
                @if($product->manage_stock)
                    ({{ $this->currentVariant?->stock_quantity ?? $product->stock_quantity }} available)
                @endif
            </span>
        </div>
    @else
        <div class="d-flex align-items-center gap-6 mt_8 text-danger fs-14">
            <i class="icon-close"></i>
            <span>Out of Stock</span>
        </div>
    @endif
</div>
