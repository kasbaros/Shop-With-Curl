<div>
    @if($showModal && $product)
    <div class="modal fade modalDemo popup-quickadd show d-block" id="quick_add"
         x-data="{ color: '{{ $selectedColor ?? '' }}', size: '{{ $selectedSize ?? '' }}' }"
         wire:model.debounce.500ms="selectedColor, selectedSize, quantity"
         style="z-index: 1060; display: block; background-color: rgba(0,0,0,0.5);"
         aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="header">
                    <span class="icon-close icon-close-popup" wire:click="closeModal"></span>
                </div>
                <div class="wrap">
                    <div class="tf-product-info-item">
                        <div class="image" style="position: relative;">
                            @if($this->currentVariant && $this->currentVariant->image_url)
                                <img src="{{ $this->imageUrl }}" alt="{{ $product->name }}">
                            @elseif($product->images && count($product->images) > 1)
                                <div class="image-gallery" style="position: relative; overflow: hidden;">
                                    <div class="image-slides" style="display: flex; transition: transform 0.3s ease;">
                                        @foreach($product->images as $index => $image)
                                            <img src="{{ $image['thumb'] ?? $image['original'] }}"
                                                 alt="{{ $product->name }}"
                                                 style="flex-shrink: 0; width: 100%; {{ $index > 0 ? 'display: none;' : '' }}"
                                                 data-slide="{{ $index }}">
                                        @endforeach
                                    </div>
                                    @if(count($product->images) > 1)
                                        <div class="image-nav" style="position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%); display: flex; gap: 5px;">
                                            @foreach($product->images as $index => $image)
                                                <button type="button"
                                                        class="image-dot"
                                                        data-slide="{{ $index }}"
                                                        style="width: 8px; height: 8px; border: none; border-radius: 50%; background: {{ $index === 0 ? '#000' : 'rgba(0,0,0,0.3)' }}; cursor: pointer;"
                                                        onclick="showSlide({{ $index }})"></button>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @else
                                <img src="{{ $this->imageUrl }}" alt="{{ $product->name }}">
                            @endif
                        </div>
                        <div class="content">
                            <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                            <div class="tf-product-info-price">
                                <div class="price">{{ $this->formattedPrice }}</div>
                            </div>
                        </div>
                    </div>
                    @if($product->variants()->exists())
                    <div class="tf-product-info-variant-picker mb_15">
                        <!-- Color Selection -->
                        @if($this->availableColors->isNotEmpty())
                        <div class="variant-picker-item">
                            <div class="variant-picker-label">
                                Color: <span class="fw-6 variant-picker-label-value">{{ $selectedColor ?? 'Please select' }}</span>
                            </div>
                            <div class="variant-picker-values">
                                @foreach ($this->availableColors as $colorObj)
                                    <input id="values-{{ strtolower(str_replace(' ', '-', $colorObj['name'])) }}" type="radio" name="color"
                                           value="{{ $colorObj['name'] }}" wire:model.live="selectedColor"
                                           @if($selectedColor === $colorObj['name']) checked @endif>
                                    <label class="hover-tooltip radius-60" for="values-{{ strtolower(str_replace(' ', '-', $colorObj['name'])) }}"
                                           data-value="{{ $colorObj['name'] }}">
                                        <span class="btn-checkbox" style="background-color: {{ $colorObj['hex_code'] }} !important;"></span>
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
                                Size: <span class="fw-6 variant-picker-label-value">{{ $selectedSize ?? 'Please select' }}</span>
                            </div>
                            <div class="variant-picker-values">
                                @foreach ($this->availableSizes as $size)
                                    <input type="radio" name="size" id="size-{{ strtolower(str_replace(' ', '-', $size)) }}"
                                           value="{{ $size }}" wire:model.live="selectedSize"
                                           @if($selectedSize === $size) checked @endif>
                                    <label class="style-text" for="size-{{ strtolower(str_replace(' ', '-', $size)) }}"
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
                                Material: <span class="fw-6 variant-picker-label-value">{{ $selectedMaterial ?? 'Please select' }}</span>
                            </div>
                            <div class="variant-picker-values">
                                @foreach ($this->availableMaterials as $material)
                                    <input type="radio" name="material" id="material-{{ strtolower(str_replace(' ', '-', $material)) }}"
                                           value="{{ $material }}" wire:model.live="selectedMaterial"
                                           @if($selectedMaterial === $material) checked @endif>
                                    <label class="style-text" for="material-{{ strtolower(str_replace(' ', '-', $material)) }}"
                                           data-value="{{ $material }}">
                                        <p>{{ $material }}</p>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Variant Info -->
                        @if($this->currentVariant)
                        <div class="bg-light p-3 rounded mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">Selected: {{ $this->currentVariant->display_name }}</small>
                                <small class="fw-bold">Stock: {{ $this->currentVariant->stock_quantity }}</small>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                    <div class="tf-product-info-quantity mb_15">
                        <div class="quantity-title fw-6">Quantity</div>
                        <div class="wg-quantity">
                            <span class="btn-quantity minus-btn" wire:click="decrementQuantity">-</span>
                            <input type="text" name="number" value="{{ $quantity }}" readonly>
                            <span class="btn-quantity plus-btn" wire:click="incrementQuantity">+</span>
                        </div>
                    </div>
                    <div class="tf-product-info-buy-button">
                        <form>
                            <a href="javascript:void(0);"
                               class="tf-btn btn-fill justify-content-center fw-6 fs-16 flex-grow-1 animate-hover-btn btn-add-to-cart"
                               wire:click="addToCart">
                                <span>Add to cart -&nbsp;</span>
                                <span class="tf-qty-price">{{ $this->formattedPrice }}</span>
                            </a>
                            <a href="javascript:void(0);"
                               class="tf-product-btn-wishlist box-icon bg_white btn-icon-action"
                               wire:click="toggleWishlist">
                                <span class="icon icon-heart"></span>
                                <span class="tooltip">Add to Wishlist</span>
                                <span class="icon icon-delete"></span>
                            </a>
                            <a href="javascript:void(0);"
                               class="tf-product-btn-wishlist box-icon bg_white compare btn-icon-action"
                               wire:click="toggleCompare">
                                <span class="icon icon-compare"></span>
                                <span class="tooltip">Add to Compare</span>
                                <span class="icon icon-check"></span>
                            </a>
{{--                            <div class="w-100">--}}
{{--                                <a href="#" class="btns-full">Buy with <img--}}
{{--                                        src="{{ asset('images/payments/paypal.png') }}" alt=""></a>--}}
{{--                                <a href="#" class="payment-more-option">More payment options</a>--}}
{{--                            </div>--}}
                        </form>
                        <div class="mt-3">
                            <a href="{{ route('products.show', $product->slug) }}" class="tf-btn fw-6 btn-line">View
                                full details<i class="icon icon-arrow1-top-left"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<script>
function showSlide(slideIndex) {
    const slides = document.querySelectorAll('[data-slide]');
    const dots = document.querySelectorAll('.image-dot');

    // Hide all slides and show selected one
    slides.forEach((slide, index) => {
        if (slide.tagName === 'IMG') {
            slide.style.display = index === slideIndex ? 'block' : 'none';
        }
    });

    // Update dots
    dots.forEach((dot, index) => {
        dot.style.background = index === slideIndex ? '#000' : 'rgba(0,0,0,0.3)';
    });
}

// Auto-rotate images every 3 seconds if multiple images exist
document.addEventListener('DOMContentLoaded', function() {
    let currentSlide = 0;
    const autoRotateImages = () => {
        const slides = document.querySelectorAll('[data-slide]');
        const imageSlides = Array.from(slides).filter(slide => slide.tagName === 'IMG');

        if (imageSlides.length > 1) {
            currentSlide = (currentSlide + 1) % imageSlides.length;
            showSlide(currentSlide);
        }
    };

    // Start auto-rotation if modal is visible and has multiple images
    setInterval(() => {
        if (document.getElementById('quick_add') && document.getElementById('quick_add').style.display !== 'none') {
            const imageSlides = document.querySelectorAll('[data-slide]');
            const imgSlides = Array.from(imageSlides).filter(slide => slide.tagName === 'IMG');
            if (imgSlides.length > 1) {
                autoRotateImages();
            }
        }
    }, 3000);
});
</script>

</div>
