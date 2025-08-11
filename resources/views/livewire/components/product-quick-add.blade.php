<div>
    @if($showModal && $product)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="header border-bottom p-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Quick Add</h6>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="wrap">
                            <div class="tf-product-info-item d-flex gap-3 mb-4">
                                <div class="image">
                                    <img src="{{ $this->imageUrl }}"
                                         alt="{{ $product->name }}"
                                         style="width: 80px; height: 80px; object-fit: cover;"
                                         class="rounded">
                                </div>
                                <div class="content flex-grow-1">
                                    <h6 class="mb-1">
                                        <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none">
                                            {{ $product->name }}
                                        </a>
                                    </h6>
                                    <div class="tf-product-info-price">
                                        <div class="fw-6 text-primary">{{ $this->formattedPrice }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quantity -->
                            <div class="tf-product-info-quantity mb-4">
                                <div class="fw-6 mb-2">Quantity</div>
                                <div class="input-group" style="width: 140px;">
                                    <button class="btn btn-outline-secondary" type="button" wire:click="decrementQuantity">-</button>
                                    <input type="text" class="form-control text-center" readonly value="{{ $quantity }}">
                                    <button class="btn btn-outline-secondary" type="button" wire:click="incrementQuantity">+</button>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="tf-product-info-buy-button">
                                <button type="button"
                                        class="btn btn-primary w-100 fw-6 mb-3"
                                        wire:click="addToCart">
                                    Add to cart - <span class="tf-qty-price">{{ $this->formattedPrice }}</span>
                                </button>

                                <div class="text-center">
                                    <a href="{{ route('products.show', $product->slug) }}"
                                       class="btn btn-link">
                                        View full details
                                        <i class="icon icon-arrow1-top-left ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
