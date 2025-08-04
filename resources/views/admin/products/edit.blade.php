@extends('admin.layouts.app')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0">Edit Product</h2>
            <p class="text-muted mb-0">{{ $product->name }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.show', $product) }}" class="btn btn-outline-info">
                <i class="bi bi-eye me-1"></i> View
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Main Product Information -->
            <div class="col-lg-8">
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Basic Information</h5>

                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <input type="text" class="form-control @error('slug') is-invalid @enderror"
                               id="slug" name="slug" value="{{ old('slug', $product->slug) }}">
                        @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="short_description" class="form-label">Short Description</label>
                        <textarea class="form-control @error('short_description') is-invalid @enderror"
                                  id="short_description" name="short_description" rows="3"
                                  maxlength="500">{{ old('short_description', $product->short_description) }}</textarea>
                        @error('short_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Full Description *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="8" required>{{ old('description', $product->description) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Product Images -->
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Product Images</h5>

                    <!-- Existing Images -->
                    @if($product->getMedia('images')->count() > 0)
                        <div class="mb-4">
                            <h6>Current Images</h6>
                            <div class="row g-3">
                                @foreach($product->getMedia('images') as $media)
                                    <div class="col-md-3">
                                        <div class="position-relative">
                                            <img src="{{ $media->getUrl() }}"
                                                 class="img-fluid rounded"
                                                 style="height: 120px; width: 100%; object-fit: cover;">
                                            @if($loop->first)
                                                <div class="badge bg-primary position-absolute top-0 start-0 m-1">Main</div>
                                            @endif
                                        </div>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox"
                                                   name="remove_images[]" value="{{ $media->id }}"
                                                   id="remove_check_{{ $media->id }}">
                                            <label class="form-check-label text-danger" for="remove_check_{{ $media->id }}">
                                                <small>Remove this image</small>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Upload New Images -->
                    <div class="mb-3">
                        <label for="images" class="form-label">Upload Additional Images</label>
                        <input type="file" class="form-control @error('images.*') is-invalid @enderror"
                               id="images" name="images[]" multiple accept="image/*">
                        <div class="form-text">Upload multiple images (JPEG, PNG, JPG, GIF, WebP). Max 2MB per image.</div>
                        @error('images.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="imagePreview" class="row g-3" style="display: none;">
                        <!-- New image previews will be shown here -->
                    </div>
                </div>

                <!-- SEO Settings -->
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">SEO Settings</h5>

                    <div class="mb-3">
                        <label for="meta_title" class="form-label">Meta Title</label>
                        <input type="text" class="form-control @error('meta_title') is-invalid @enderror"
                               id="meta_title" name="meta_title" value="{{ old('meta_title', $product->meta_title) }}" maxlength="255">
                        @error('meta_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea class="form-control @error('meta_description') is-invalid @enderror"
                                  id="meta_description" name="meta_description" rows="3"
                                  maxlength="500">{{ old('meta_description', $product->meta_description) }}</textarea>
                        @error('meta_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Product Details -->
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Product Details</h5>

                    <div class="mb-3">
                        <label for="sku" class="form-label">SKU *</label>
                        <input type="text" class="form-control @error('sku') is-invalid @enderror"
                               id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required>
                        @error('sku')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="categories" class="form-label">Categories *</label>
                        <select class="form-select @error('categories') is-invalid @enderror"
                                id="categories" name="categories[]" multiple required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ in_array($category->id, old('categories', $product->categories->pluck('id')->toArray())) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Hold Ctrl/Cmd to select multiple categories</div>
                        @error('categories')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Regular Price *</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control @error('price') is-invalid @enderror"
                                   id="price" name="price" value="{{ old('price', $product->price) }}"
                                   step="0.01" min="0" required>
                        </div>
                        @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="sale_price" class="form-label">Sale Price</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control @error('sale_price') is-invalid @enderror"
                                   id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}"
                                   step="0.01" min="0">
                        </div>
                        @error('sale_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Stock Management -->
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Stock Management</h5>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="manage_stock" name="manage_stock"
                                   value="1" {{ old('manage_stock', $product->manage_stock) ? 'checked' : '' }}>
                            <label class="form-check-label" for="manage_stock">
                                Track stock quantity
                            </label>
                        </div>
                    </div>

                    <div id="stockFields" style="{{ old('manage_stock', $product->manage_stock) ? '' : 'display: none;' }}">
                        <div class="mb-3">
                            <label for="stock_quantity" class="form-label">Stock Quantity</label>
                            <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror"
                                   id="stock_quantity" name="stock_quantity"
                                   value="{{ old('stock_quantity', $product->stock_quantity) }}" min="0">
                            @error('stock_quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="min_stock_level" class="form-label">Low Stock Alert Level</label>
                            <input type="number" class="form-control @error('min_stock_level') is-invalid @enderror"
                                   id="min_stock_level" name="min_stock_level"
                                   value="{{ old('min_stock_level', $product->min_stock_level) }}" min="0">
                            @error('min_stock_level')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Product Settings -->
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Product Settings</h5>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                   value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active (visible to customers)
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"
                                   value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                Featured product
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="weight" class="form-label">Weight (lbs)</label>
                        <input type="number" class="form-control @error('weight') is-invalid @enderror"
                               id="weight" name="weight" value="{{ old('weight', $product->weight) }}"
                               step="0.01" min="0">
                        @error('weight')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="dimensions" class="form-label">Dimensions (L x W x H)</label>
                        <input type="text" class="form-control @error('dimensions') is-invalid @enderror"
                               id="dimensions" name="dimensions" value="{{ old('dimensions', $product->dimensions) }}"
                               placeholder="e.g., 10 x 5 x 3 inches">
                        @error('dimensions')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="stat-card p-4">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Update Product
                        </button>
                        <a href="{{ route('admin.products.show', $product) }}" class="btn btn-outline-info">
                            <i class="bi bi-eye me-1"></i> Preview Product
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        // Stock management toggle
        document.getElementById('manage_stock').addEventListener('change', function() {
            const stockFields = document.getElementById('stockFields');
            if (this.checked) {
                stockFields.style.display = 'block';
            } else {
                stockFields.style.display = 'none';
            }
        });

        // Image preview functionality
        document.getElementById('images').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';

            if (e.target.files.length > 0) {
                preview.style.display = 'block';

                Array.from(e.target.files).forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const col = document.createElement('div');
                            col.className = 'col-md-3';
                            col.innerHTML = `
                                <div class="position-relative">
                                    <img src="${e.target.result}" class="img-fluid rounded"
                                         style="height: 120px; width: 100%; object-fit: cover;">
                                    ${index === 0 ? '<div class="badge bg-success position-absolute top-0 start-0 m-1">New Main</div>' : ''}
                                </div>
                            `;
                            preview.appendChild(col);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            } else {
                preview.style.display = 'none';
            }
        });

        // Auto-generate slug from name
        document.getElementById('name').addEventListener('input', function() {
            const slug = this.value.toLowerCase()
                .replace(/[^a-z0-9 -]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            document.getElementById('slug').value = slug;
        });
    </script>
@endpush
