{{--<div>--}}
{{--    @if($showModal)--}}
{{--        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">--}}
{{--            <div class="modal-dialog modal-dialog-centered modal-lg">--}}
{{--                <div class="modal-content">--}}
{{--                    <div class="header border-bottom p-3 d-flex align-items-center justify-content-between">--}}
{{--                        <h6 class="mb-0">Compare</h6>--}}
{{--                        <button type="button" class="btn-close" wire:click="closeModal"></button>--}}
{{--                    </div>--}}
{{--                    <div class="modal-body">--}}
{{--                        @if($message)--}}
{{--                            <div class="alert alert-info py-2 mb-3">{{ $message }}</div>--}}
{{--                        @endif--}}

{{--                        @if(empty($items))--}}
{{--                            <div class="text-center text-muted py-4">--}}
{{--                                No items in compare yet.--}}
{{--                            </div>--}}
{{--                        @else--}}
{{--                            <div class="row g-3">--}}
{{--                                @foreach($items as $item)--}}
{{--                                    <div class="col-md-6">--}}
{{--                                        <div class="d-flex align-items-center p-3 border rounded">--}}
{{--                                            <img src="{{ $item['img'] }}" alt="{{ $item['name'] }}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">--}}
{{--                                            <div class="flex-grow-1">--}}
{{--                                                <div class="d-flex justify-content-between align-items-start">--}}
{{--                                                    <div>--}}
{{--                                                        <a href="{{ route('products.show', $item['slug']) }}" class="text-decoration-none fw-6">{{ $item['name'] }}</a>--}}
{{--                                                        <div class="small text-muted mt-1">UGX {{ number_format((float)$item['price'], 0) }}</div>--}}
{{--                                                    </div>--}}
{{--                                                    <button type="button" class="btn btn-sm btn-outline-danger" wire:click="remove({{ $item['id'] }})">--}}
{{--                                                        <i class="icon icon-delete"></i>--}}
{{--                                                    </button>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                @endforeach--}}
{{--                            </div>--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                    <div class="p-3 border-top d-flex justify-content-between align-items-center">--}}
{{--                        <button type="button" class="btn btn-outline-secondary" wire:click="clear">Clear All</button>--}}
{{--                        <button type="button" class="btn btn-primary" wire:click="closeModal">Close</button>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    @endif--}}
{{--</div>--}}


<div>
    @if($showModal)
        <div class="offcanvas offcanvas-bottom canvas-compare show" id="compare" tabindex="-1" wire:model.debounce.500ms="items" style="visibility: visible;">
            <div class="canvas-wrapper">
                <header class="canvas-header">
                    <div class="close-popup">
                        <span class="icon-close icon-close-popup" wire:click="closeModal" aria-label="Close"></span>
                    </div>
                </header>
                <div class="canvas-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="tf-compare-list">
                                    <div class="tf-compare-head">
                                        <div class="title">Compare Products</div>
                                        @if($message)
                                            <div class="alert alert-info py-2 mb-3">{{ $message }}</div>
                                        @endif
                                    </div>
                                    <div class="tf-compare-offcanvas">
                                        @forelse ($items as $item)
                                            <div class="tf-compare-item">
                                                <div class="position-relative">
                                                    <div class="icon" wire:click="remove({{ $item['id'] }})">
                                                        <i class="icon-close"></i>
                                                    </div>
                                                    <a href="{{ route('products.show', $item['slug']) }}">
                                                        <img class="radius-3" src="{{ $item['image'] }}" alt="{{ $item['name'] }}">
                                                    </a>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center text-muted py-4">
                                                No products to compare
                                            </div>
                                        @endforelse
                                    </div>
                                    <div class="tf-compare-buttons">
                                        <div class="tf-compare-buttons-wrap">
                                            <a href="javascript:void(0);" onclick="window.Livewire.dispatch('compare:show')"
                                               class="tf-btn radius-3 btn-fill justify-content-center fw-6 fs-14 flex-grow-1 animate-hover-btn">Compare</a>
                                            <div class="tf-compapre-button-clear-all link" wire:click="clear">
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
