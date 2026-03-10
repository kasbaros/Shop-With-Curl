<div>
    @if($showModal && $this->compareCount > 0)
        {{-- Backdrop --}}
        <div class="offcanvas-backdrop fade show" wire:click="closeModal" style="z-index: 4999;"></div>

        {{-- Bottom offcanvas bar --}}
        <div class="offcanvas offcanvas-bottom canvas-compare show" style="visibility: visible; z-index: 5000;">
            <div class="canvas-wrapper">
                <header class="canvas-header">
                    <div class="close-popup">
                        <span class="icon-close icon-close-popup" wire:click="closeModal" style="cursor: pointer;" aria-label="Close"></span>
                    </div>
                </header>
                <div class="canvas-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="tf-compare-list">
                                    <div class="tf-compare-head">
                                        <div class="title">Compare Products</div>
                                    </div>
                                    <div class="tf-compare-offcanvas">
                                        @foreach($this->compareProducts as $product)
                                            @php
                                                $galleryPaths = is_array($product->gallery) ? $product->gallery : [];
                                                $primaryPath = $galleryPaths[0] ?? $product->featured_image;
                                                $img = $product->getStorageImageUrl($primaryPath);
                                            @endphp
                                            <div class="tf-compare-item" wire:key="compare-{{ $product->id }}">
                                                <div class="position-relative">
                                                    <div class="icon" wire:click="remove({{ $product->id }})" style="cursor: pointer;">
                                                        <i class="icon-close"></i>
                                                    </div>
                                                    <a href="{{ route('products.show', $product->slug) }}">
                                                        <img class="radius-3" src="{{ $img }}" alt="{{ $product->name }}">
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="tf-compare-buttons">
                                        <div class="tf-compare-buttons-wrap">
                                            <a href="{{ route('compare.index') }}"
                                               class="tf-btn radius-3 btn-fill justify-content-center fw-6 fs-14 flex-grow-1 animate-hover-btn">
                                                Compare
                                            </a>
                                            <div class="tf-compapre-button-clear-all link" wire:click="clear" style="cursor: pointer;">
                                                Clear All
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
