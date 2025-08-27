@extends('admin.layouts.app')

@section('title', 'Edit Lookbook')
@section('page-title', 'Edit Lookbook')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0">Edit Lookbook</h2>
            <p class="text-muted mb-0">Update a product collection or bundle offer</p>
        </div>
        <a href="{{ route('admin.lookbooks.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Lookbooks
        </a>
    </div>

    <form action="{{ route('admin.lookbooks.update', $lookbook) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-8">
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Lookbook Details</h5>

                    <!-- Title -->
                    <div class="mb-4">
                        <label for="title" class="form-label fw-medium">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                               id="title" name="title" value="{{ old('title', $lookbook->title) }}"
                               placeholder="Bundle & Save" required>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Label -->
                    <div class="mb-4">
                        <label for="label" class="form-label fw-medium">Label</label>
                        <input type="text" class="form-control @error('label') is-invalid @enderror"
                               id="label" name="label" value="{{ old('label', $lookbook->label) }}"
                               placeholder="SHOP THIS LOOK">
                        @error('label')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Main Image -->
                    <div class="mb-4">
                        <label for="image" class="form-label fw-medium">Main Image</label>
                        @if($lookbook->image)
                            <div class="mb-2">
                                <img src="{{ $lookbook->image }}" alt="{{ $lookbook->title }}" class="img-fluid rounded" style="max-height: 200px;">
                            </div>
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                               id="image" name="image" accept="image/*">
                        <div class="form-text">This image will be displayed on the right side of the lookbook section. Leave empty to keep current.</div>
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Product Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-medium">Select Products</label>
                        <div class="product-selection-container" style="max-height: 400px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 0.375rem; padding: 1rem;">
                            @php
                                $selectedProductIds = $lookbook->items->pluck('product_id')->toArray();
                            @endphp
                            @foreach($products as $product)
                                <div class="form-check mb-2 p-2 border-bottom">
                                    <input class="form-check-input" type="checkbox" name="product_ids[]"
                                           value="{{ $product->id }}" id="product_{{ $product->id }}"
                                        {{ in_array($product->id, old('product_ids', $selectedProductIds)) ? 'checked' : '' }}>
                                    <label class="form-check-label d-flex justify-content-between align-items-center w-100" for="product_{{ $product->id }}">
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                <img src="{{ $product->thumbnail_url ?? asset('images/placeholder-product.jpg') }}"
                                                     alt="{{ $product->name }}" class="rounded"
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            </div>
                                            <div>
                                                <strong>{{ $product->name }}</strong>
                                                <div class="text-muted small">
                                                    @if($product->sale_price && $product->sale_price < $product->price)
                                                        <span class="text-danger">UGX {{ number_format($product->sale_price, 0) }}</span>
                                                        <span class="text-decoration-line-through">UGX {{ number_format($product->price, 0) }}</span>
                                                    @else
                                                        UGX {{ number_format($product->price, 0) }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="form-text">Select products to include in this lookbook</div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Settings -->
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Settings</h5>

                    <!-- Status -->
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="active"
                                   name="active" value="1" {{ old('active', $lookbook->active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="active">Active</label>
                        </div>
                    </div>

                    <!-- Priority -->
                    <div class="mb-4">
                        <label for="priority" class="form-label fw-medium">Priority</label>
                        <input type="number" class="form-control @error('priority') is-invalid @enderror"
                               id="priority" name="priority" value="{{ old('priority', $lookbook->priority) }}"
                               min="0" step="1">
                        <div class="form-text">Lower numbers appear first</div>
                        @error('priority')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Schedule -->
                    <div class="mb-4">
                        <label for="starts_at" class="form-label fw-medium">Start Date</label>
                        <input type="datetime-local" class="form-control @error('starts_at') is-invalid @enderror"
                               id="starts_at" name="starts_at" value="{{ old('starts_at', optional($lookbook->starts_at)->format('Y-m-d\TH:i')) }}">
                        @error('starts_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="ends_at" class="form-label fw-medium">End Date</label>
                        <input type="datetime-local" class="form-control @error('ends_at') is-invalid @enderror"
                               id="ends_at" name="ends_at" value="{{ old('ends_at', optional($lookbook->ends_at)->format('Y-m-d\TH:i')) }}">
                        @error('ends_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-admin">
                        <i class="bi bi-check-circle me-1"></i> Update Lookbook
                    </button>
                    <a href="{{ route('admin.lookbooks.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add select all/none functionality
            const container = document.querySelector('.product-selection-container');
            if (container) {
                const selectAllBtn = document.createElement('button');
                selectAllBtn.type = 'button';
                selectAllBtn.className = 'btn btn-sm btn-outline-primary me-2 mb-3';
                selectAllBtn.textContent = 'Select All';

                const selectNoneBtn = document.createElement('button');
                selectNoneBtn.type = 'button';
                selectNoneBtn.className = 'btn btn-sm btn-outline-secondary mb-3';
                selectNoneBtn.textContent = 'Select None';

                const buttonsDiv = document.createElement('div');
                buttonsDiv.appendChild(selectAllBtn);
                buttonsDiv.appendChild(selectNoneBtn);

                container.parentNode.insertBefore(buttonsDiv, container);

                selectAllBtn.addEventListener('click', function() {
                    container.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = true);
                });

                selectNoneBtn.addEventListener('click', function() {
                    container.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
                });
            }
        });
    </script>
@endpush
