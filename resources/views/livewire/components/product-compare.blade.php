<div>
    @if($showCompare && $compareProducts->count() > 0)
        <div class="offcanvas offcanvas-bottom canvas-compare show d-block" style="visibility: visible; background-color: rgba(0,0,0,0.5);">
            <div class="canvas-wrapper">
                <header class="canvas-header">
                    <div class="close-popup">
                        <span class="icon-close icon-close-popup" wire:click="closeCompare"></span>
                    </div>
                </header>
                <div class="canvas-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="tf-compare-list">
                                    <div class="tf-compare-head d-flex justify-content-between align-items-center mb-4">
                                        <div class="title">Compare Products ({{ $this->compareCount }}/{{ app(App\Services\CompareService::class)->getMaxItems() }})</div>
                                        <button class="btn btn-sm btn-outline-danger" wire:click="$dispatch('compare:clear')">
                                            Clear All
                                        </button>
                                    </div>

                                    <div class="tf-compare-offcanvas d-flex gap-3 mb-4">
                                        @foreach($compareProducts as $product)
                                            <div class="tf-compare-item">
                                                <div class="position-relative">
                                                    <div class="icon"
                                                         wire:click="removeFromCompare({{ $product->id }})"
                                                         style="cursor: pointer;">
                                                        <i class="icon-close"></i>
                                                    </div>
                                                    <a href="{{ route('products.show', $product->slug) }}">
                                                        @php
                                                            $media = $product->media->first();
                                                            $img = method_exists($media, 'getUrl') ? $media->getUrl() : ($media->url ?? null);
                                                            $imageUrl = $img ?: asset('images/placeholder-product.jpg');
                                                        @endphp
                                                        <img class="radius-3"
                                                             src="{{ $imageUrl }}"
                                                             alt="{{ $product->name }}"
                                                             style="width: 100px; height: 100px; object-fit: cover;">
                                                    </a>
                                                    <div class="mt-2 text-center">
                                                        <h6 class="mb-1">{{ Str::limit($product->name, 20) }}</h6>
                                                        @php
                                                            $price = $product->sale_price ?: $product->price;
                                                            $formatted = (is_int($price) || ctype_digit((string)$price))
                                                                ? number_format($price / 100, 2)
                                                                : number_format((float)$price, 2);
                                                        @endphp
                                                        <div class="fw-6 text-primary">{{ config('app.currency_symbol', '$') }}{{ $formatted }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="tf-compare-buttons">
                                        <div class="tf-compare-buttons-wrap d-flex gap-3 justify-content-center">
                                            <a href="{{ route('compare.index') }}"
                                               class="tf-btn radius-3 btn-fill justify-content-center fw-6 fs-14 animate-hover-btn"
                                               wire:click="closeCompare">
                                               Compare Details
                                            </a>
                                            <button class="btn btn-outline-secondary" wire:click="closeCompare">
                                                Continue Shopping
                                            </button>
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
