<div>
    @if($showModal && $product)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="header border-bottom p-3">
                        <button type="button" class="btn-close ms-auto" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="wrap">
                            <div class="row g-0">
                                <!-- Product Image -->
                                <div class="col-md-6">
                                    <div class="tf-product-media-wrap p-4">
                                        <div class="product-image">
                                            <img src="{{ $this->imageUrl }}"
                                                 alt="{{ $product->name }}"
                                                 class="img-fluid rounded">
                                        </div>
                                    </div>
                                </div>

                                <!-- Product Info -->
                                <div class="col-md-6">
                                    <div class="tf-product-info-wrap p-4">
                                        <div class="tf-product-info-list">
                                            <div class="tf-product-info-title mb-3">
                                                <h5>
                                                    <a class="link text-decoration-none" href="{{ route('products.show', $product->slug) }}">
                                                        {{ $product->name }}
                                                    </a>
                                                </h5>
                                            </div>

                                            @if($product->is_featured || $product->sale_price)
                                                <div class="tf-product-info-badges mb-2">
                                                    @if($product->is_featured)
                                                        <div class="badge bg-primary text-uppercase">Featured</div>
                                                    @endif
                                                    @if($product->sale_price)
                                                        <div class="badge bg-danger text-uppercase">On Sale</div>
                                                    @endif
                                                </div>
                                            @endif

                                            <div class="tf-product-info-price mb-3">
                                                @if($product->sale_price)
                                                    <div class="text-muted text-decoration-line-through">{{ $this->originalPrice }}</div>
                                                    <div class="h5 text-danger mb-0">{{ $this->formattedPrice }}</div>
                                                @else
                                                    <div class="h5 mb-0">{{ $this->formattedPrice }}</div>
                                                @endif
                                            </div>

                                            @if($product->short_description)
                                                <div class="tf-product-description mb-4">
                                                    <p class="mb-0">{{ $product->short_description }}</p>
                                                </div>
                                            @endif

                                            <!-- Quantity -->
                                            <div class="tf-product-info-quantity mb-4">
                                                <div class="fw-6 mb-2">Quantity</div>
                                                <div class="input-group" style="width: 140px;">
                                                    <button class="btn btn-outline-secondary" type="button" wire:click="decrementQuantity">-</button>
                                                    <input type="text" class="form-control text-center" readonly value="{{ $quantity }}">
                                                    <button class="btn btn-outline-secondary" type="button" wire:click="incrementQuantity">+</button>
                                                </div>
                                            </div>

                                            <!-- Action Buttons -->
                                            <div class="tf-product-info-buy-button">
                                                <div class="row g-2 mb-3">
                                                    <div class="col-12">
                                                        <button type="button"
                                                                class="btn btn-primary w-100 fw-6"
                                                                wire:click="addToCart">
                                                            Add to cart - {{ $this->formattedPrice }}
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="row g-2 mb-3">
                                                    <div class="col">
                                                        <button type="button"
                                                                class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2"
                                                                wire:click="toggleWishlist">
                                                            <i class="icon icon-heart"></i>
                                                            Wishlist
                                                        </button>
                                                    </div>
                                                    <div class="col">
                                                        <button type="button"
                                                                class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2"
                                                                wire:click="toggleCompare">
                                                            <i class="icon icon-compare"></i>
                                                            Compare
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="text-center">
                                                    <a href="{{ route('products.show', $product->slug) }}"
                                                       class="btn btn-link fw-6">
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
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
