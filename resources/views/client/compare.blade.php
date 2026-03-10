<x-app-layout :isAuthPage="false">
    <x-slot name="title">Compare Products - ShopWithCarl</x-slot>

    <div class="tf-page-title">
        <div class="container-full">
            <div class="heading text-center">Compare Products</div>
        </div>
    </div>

    <section class="flat-spacing-12">
        <div class="container">
            @if($products->isEmpty())
                <div class="text-center py-5">
                    <h5 class="mb-3">No products to compare</h5>
                    <p class="text-muted mb-4">Add products to comparison from the shop page.</p>
                    <a href="{{ route('shop.index') }}" class="tf-btn radius-3 btn-fill animate-hover-btn">
                        Continue Shopping
                    </a>
                </div>
            @else
                <div class="tf-compare-table">
                    {{-- Product Grid Row --}}
                    <div class="tf-compare-row tf-compare-grid">
                        <div class="tf-compare-col d-md-block d-none"></div>
                        @foreach($products as $product)
                            @php
                                $galleryPaths = is_array($product->gallery) ? $product->gallery : [];
                                $primaryPath = $galleryPaths[0] ?? $product->featured_image;
                                $img = $product->getStorageImageUrl($primaryPath);
                                $price = (float) ($product->price ?? 0);
                                $sale = (float) ($product->sale_price ?? 0);
                                $isOnSale = $sale > 0 && $sale < $price;
                            @endphp
                            <div class="tf-compare-col">
                                <div class="tf-compare-item">
                                    <form action="{{ route('compare.remove', $product->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="tf-compare-remove link">Remove</button>
                                    </form>
                                    <a class="tf-compare-image" href="{{ route('products.show', $product->slug) }}">
                                        <img src="{{ $img }}" alt="{{ $product->name }}">
                                    </a>
                                    <a class="tf-compare-title" href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                                    <div class="price">
                                        @if($isOnSale)
                                            <span class="text-danger fw-6">{{ money_format_ugx($sale) }}</span>
                                            <span class="text-muted text-decoration-line-through ms-1">{{ money_format_ugx($price) }}</span>
                                        @else
                                            {{ money_format_ugx($price) }}
                                        @endif
                                    </div>
                                    <div class="tf-compare-group-btns d-flex gap-10">
                                        <a href="javascript:void(0);"
                                           onclick="if(window.Livewire) Livewire.dispatch('product:quickView', {productId: {{ $product->id }}})"
                                           class="tf-btn btn-outline-dark radius-3">
                                            <i class="icon icon-view"></i><span>Quick View</span>
                                        </a>
                                        <a href="javascript:void(0);"
                                           onclick="if(window.Livewire) Livewire.dispatch('product:quickAdd', {productId: {{ $product->id }}})"
                                           class="tf-btn btn-outline-dark radius-3">
                                            <i class="icon icon-bag"></i><span>Quick Add</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Availability Row --}}
                    <div class="tf-compare-row">
                        <div class="tf-compare-col tf-compare-field d-md-block d-none">
                            <h6>Availability</h6>
                        </div>
                        @foreach($products as $product)
                            <div class="tf-compare-col tf-compare-field tf-compare-stock">
                                @if($product->is_active && $product->status === 'published')
                                    <div class="icon"><i class="icon-check"></i></div>
                                    <span class="fw-5">In Stock</span>
                                @else
                                    <span class="text-muted">Out of Stock</span>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- Category Row --}}
                    <div class="tf-compare-row">
                        <div class="tf-compare-col tf-compare-field d-md-block d-none">
                            <h6>Category</h6>
                        </div>
                        @foreach($products as $product)
                            <div class="tf-compare-col tf-compare-value text-center">
                                {{ $product->categories->pluck('name')->join(', ') ?: '—' }}
                            </div>
                        @endforeach
                    </div>

                    {{-- Description Row --}}
                    <div class="tf-compare-row">
                        <div class="tf-compare-col tf-compare-field d-md-block d-none">
                            <h6>Description</h6>
                        </div>
                        @foreach($products as $product)
                            <div class="tf-compare-col tf-compare-value text-center">
                                {{ Str::limit(strip_tags($product->description), 120) ?: '—' }}
                            </div>
                        @endforeach
                    </div>

                    @php
                        // Collect variant attributes across all compared products
                        $hasColors = $products->contains(fn($p) => $p->variants->whereNotNull('color')->isNotEmpty());
                        $hasSizes = $products->contains(fn($p) => $p->variants->whereNotNull('size')->isNotEmpty());
                        $hasMaterials = $products->contains(fn($p) => $p->variants->whereNotNull('material')->isNotEmpty());
                    @endphp

                    {{-- Color Row --}}
                    @if($hasColors)
                        <div class="tf-compare-row">
                            <div class="tf-compare-col tf-compare-field d-md-block d-none">
                                <h6>Color</h6>
                            </div>
                            @foreach($products as $product)
                                <div class="tf-compare-col tf-compare-value text-center">
                                    {{ $product->variants->whereNotNull('color')->pluck('color')->unique()->join(', ') ?: '—' }}
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Size Row --}}
                    @if($hasSizes)
                        <div class="tf-compare-row">
                            <div class="tf-compare-col tf-compare-field d-md-block d-none">
                                <h6>Size</h6>
                            </div>
                            @foreach($products as $product)
                                <div class="tf-compare-col tf-compare-value text-center">
                                    {{ $product->variants->whereNotNull('size')->pluck('size')->unique()->join(', ') ?: '—' }}
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Material Row --}}
                    @if($hasMaterials)
                        <div class="tf-compare-row">
                            <div class="tf-compare-col tf-compare-field d-md-block d-none">
                                <h6>Material</h6>
                            </div>
                            @foreach($products as $product)
                                <div class="tf-compare-col tf-compare-value text-center">
                                    {{ $product->variants->whereNotNull('material')->pluck('material')->unique()->join(', ') ?: '—' }}
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </section>
</x-app-layout>
