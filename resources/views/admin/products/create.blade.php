@extends('admin.layouts.app')

@section('title', 'Add Product')
@section('page-title', 'Add New Product')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0">Add New Product</h2>
            <p class="text-muted mb-0">Create a new product for your store</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Products
        </a>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <!-- Main Product Information -->
            <div class="col-lg-8">
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Basic Information</h5>

                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <input type="text" class="form-control @error('slug') is-invalid @enderror"
                               id="slug" name="slug" value="{{ old('slug') }}"
                               placeholder="Auto-generated from product name">
                        <div class="form-text">Leave empty to auto-generate from product name</div>
                        @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="short_description" class="form-label">Short Description</label>
                        <textarea class="form-control @error('short_description') is-invalid @enderror"
                                  id="short_description" name="short_description" rows="3"
                                  maxlength="500">{{ old('short_description') }}</textarea>
                        <div class="form-text">Brief description for product listings (max 500 characters)</div>
                        @error('short_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Full Description *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="8" required>{{ old('description') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Product Images -->
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Product Images</h5>

                    <div class="mb-3">
                        <label for="images" class="form-label">Upload Images</label>
                        <input type="file" class="form-control @error('images.*') is-invalid @enderror"
                               id="images" name="images[]" multiple accept="image/*">
                        <div class="form-text">
                            Upload multiple images (JPEG, PNG, JPG, GIF, WebP). Max 2MB per image.
                            First image will be used as the main product image.
                        </div>
                        @error('images.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="imagePreview" class="row g-3" style="display: none;">
                        <!-- Image previews will be shown here -->
                    </div>
                </div>

                <!-- SEO Settings -->
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">SEO Settings</h5>

                    <div class="mb-3">
                        <label for="meta_title" class="form-label">Meta Title</label>
                        <input type="text" class="form-control @error('meta_title') is-invalid @enderror"
                               id="meta_title" name="meta_title" value="{{ old('meta_title') }}" maxlength="255">
                        <div class="form-text">Leave empty to use product name as meta title</div>
                        @error('meta_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea class="form-control @error('meta_description') is-invalid @enderror"
                                  id="meta_description" name="meta_description" rows="3"
                                  maxlength="500">{{ old('meta_description') }}</textarea>
                        <div class="form-text">Brief description for search engines (max 500 characters)</div>
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
                               id="sku" name="sku" value="{{ old('sku') }}" required>
                        @error('sku')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category *</label>
                        <select class="form-select @error('category_id') is-invalid @enderror"
                                id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            @foreach($categoryGroups as $parent)
                                @if($parent->children->isNotEmpty())
                                    <optgroup label="{{ $parent->name }}">
                                        @foreach($parent->children as $child)
                                            <option value="{{ $child->id }}" {{ old('category_id') == $child->id ? 'selected' : '' }}>
                                                {{ $child->name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @else
                                    <option value="" disabled style="font-weight: bold;">{{ $parent->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Regular Price *</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control @error('price') is-invalid @enderror"
                                   id="price" name="price" value="{{ old('price') }}"
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
                                   id="sale_price" name="sale_price" value="{{ old('sale_price') }}"
                                   step="0.01" min="0">
                        </div>
                        <div class="form-text">Leave empty if no sale price</div>
                        @error('sale_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Inventory -->
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Inventory</h5>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="manage_stock"
                                   name="manage_stock" value="1" {{ old('manage_stock') ? 'checked' : '' }}>
                            <label class="form-check-label" for="manage_stock">
                                Manage Stock
                            </label>
                        </div>
                        <div class="form-text">Enable stock quantity tracking</div>
                    </div>

                    <div id="stockFields" class="stock-fields" style="{{ old('manage_stock') ? '' : 'display: none;' }}">
                        <div class="mb-3">
                            <label for="stock_quantity" class="form-label">Stock Quantity</label>
                            <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror"
                                   id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', 0) }}"
                                   min="0">
                            @error('stock_quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="min_stock_level" class="form-label">Low Stock Alert Level</label>
                            <input type="number" class="form-control @error('min_stock_level') is-invalid @enderror"
                                   id="min_stock_level" name="min_stock_level" value="{{ old('min_stock_level', 5) }}"
                                   min="0">
                            <div class="form-text">Alert when stock falls below this level</div>
                            @error('min_stock_level')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Shipping -->
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Shipping</h5>

                    <div class="mb-3">
                        <label for="weight" class="form-label">Weight</label>
                        <div class="input-group">
                            <input type="number" class="form-control @error('weight') is-invalid @enderror"
                                   id="weight" name="weight" value="{{ old('weight') }}"
                                   step="0.01" min="0">
                            <span class="input-group-text">lbs</span>
                        </div>
                        @error('weight')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="dimensions" class="form-label">Dimensions</label>
                        <input type="text" class="form-control @error('dimensions') is-invalid @enderror"
                               id="dimensions" name="dimensions" value="{{ old('dimensions') }}"
                               placeholder="L x W x H (inches)">
                        @error('dimensions')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Product Status -->
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Status</h5>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active"
                                   name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
                        <div class="form-text">Product visible in store</div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_featured"
                                   name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                Featured
                            </label>
                        </div>
                        <div class="form-text">Show in featured products section</div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-admin">
                        <i class="bi bi-check-circle me-1"></i> Create Product
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-generate slug from product name
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');

            nameInput.addEventListener('input', function() {
                if (!slugInput.dataset.manual) {
                    slugInput.value = this.value
                        .toLowerCase()
                        .replace(/[^a-z0-9]+/g, '-')
                        .replace(/^-+|-+$/g, '');
                }
            });

            slugInput.addEventListener('input', function() {
                this.dataset.manual = 'true';
            });

            // Toggle stock fields
            const manageStockCheckbox = document.getElementById('manage_stock');
            const stockFields = document.getElementById('stockFields');

            manageStockCheckbox.addEventListener('change', function() {
                stockFields.style.display = this.checked ? 'block' : 'none';
            });

            // Image preview
            const imageInput = document.getElementById('images');
            const imagePreview = document.getElementById('imagePreview');

            imageInput.addEventListener('change', function() {
                imagePreview.innerHTML = '';

                if (this.files.length > 0) {
                    imagePreview.style.display = 'block';

                    Array.from(this.files).forEach((file, index) => {
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const col = document.createElement('div');
                                col.className = 'col-md-3';
                                col.innerHTML = `
                            <div class="position-relative">
                                <img src="${e.target.result}" class="img-fluid rounded" style="height: 120px; width: 100%; object-fit: cover;">
                                ${index === 0 ? '<div class="badge bg-primary position-absolute top-0 start-0 m-1">Main</div>' : ''}
                            </div>
                        `;
                                imagePreview.appendChild(col);
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                } else {
                    imagePreview.style.display = 'none';
                }
            });

            // Price validation
            const priceInput = document.getElementById('price');
            const salePriceInput = document.getElementById('sale_price');

            salePriceInput.addEventListener('input', function() {
                const price = parseFloat(priceInput.value) || 0;
                const salePrice = parseFloat(this.value) || 0;

                if (salePrice > 0 && salePrice >= price) {
                    this.setCustomValidity('Sale price must be less than regular price');
                } else {
                    this.setCustomValidity('');
                }
            });
        });
    </script>
@endpush
