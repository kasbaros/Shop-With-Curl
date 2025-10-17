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
            <div class="col-lg-6">
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
            <div class="col-lg-6">
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
                            <span class="input-group-text">UGX</span>
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
                            <span class="input-group-text">UGX</span>
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

                <!-- Inventory & Variants -->

{{--                <div class="stat-card p-4 mb-4">--}}
{{--                    <h5 class="mb-3">Inventory & Variants</h5>--}}

{{--                    <!-- Main Toggle Section -->--}}
{{--                    <div class="mb-4 p-3 border rounded bg-light">--}}
{{--                        <div class="form-check form-switch mb-2">--}}
{{--                            <input class="form-check-input" type="checkbox" id="has_variants"--}}
{{--                                   name="has_variants" value="1" {{ old('has_variants') ? 'checked' : '' }}>--}}
{{--                            <label class="form-check-label fw-semibold" for="has_variants">--}}
{{--                                Product has variants (sizes, colors)--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                        <div class="form-text text-muted">Enable if product comes in different sizes, colors, etc.</div>--}}
{{--                    </div>--}}

{{--                    <!-- Simple Inventory - Better Visual Separation -->--}}
{{--                    <div id="simpleInventoryFields" class="simple-inventory border rounded p-3 bg-light" style="{{ old('has_variants') ? 'display: none;' : '' }}">--}}
{{--                        <div class="mb-3">--}}
{{--                            <div class="form-check form-switch mb-2">--}}
{{--                                <input class="form-check-input" type="checkbox" id="manage_stock"--}}
{{--                                       name="manage_stock" value="1" {{ old('manage_stock') ? 'checked' : '' }}>--}}
{{--                                <label class="form-check-label fw-semibold" for="manage_stock">--}}
{{--                                    Manage Stock--}}
{{--                                </label>--}}
{{--                            </div>--}}
{{--                            <div class="form-text text-muted">Enable stock quantity tracking</div>--}}
{{--                        </div>--}}

{{--                        <div id="stockFields" class="stock-fields mt-3 p-3 border rounded bg-white" style="{{ old('manage_stock') ? '' : 'display: none;' }}">--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-md-6 mb-3">--}}
{{--                                    <label for="stock_quantity" class="form-label fw-semibold">Stock Quantity</label>--}}
{{--                                    <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror"--}}
{{--                                           id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', 0) }}"--}}
{{--                                           min="0">--}}
{{--                                    @error('stock_quantity')--}}
{{--                                    <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}

{{--                                <div class="col-md-6 mb-3">--}}
{{--                                    <label for="min_stock_level" class="form-label fw-semibold">Low Stock Alert Level</label>--}}
{{--                                    <input type="number" class="form-control @error('min_stock_level') is-invalid @enderror"--}}
{{--                                           id="min_stock_level" name="min_stock_level" value="{{ old('min_stock_level', 5) }}"--}}
{{--                                           min="0">--}}
{{--                                    <div class="form-text text-muted">Alert when stock falls below this level</div>--}}
{{--                                    @error('min_stock_level')--}}
{{--                                    <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <!-- Variants Section - Improved Layout -->--}}
{{--                    <div id="variantsSection" class="variants-section mt-3" style="{{ old('has_variants') ? '' : 'display: none;' }}">--}}
{{--                        <div class="border rounded p-3 bg-light">--}}
{{--                            <!-- Sizes Input -->--}}
{{--                            <div class="mb-4">--}}
{{--                                <label for="available_sizes" class="form-label fw-semibold">Available Sizes</label>--}}
{{--                                <input type="text" class="form-control" id="available_sizes" name="available_sizes"--}}
{{--                                       value="{{ old('available_sizes', 'XS,S,M,L,XL,XXL') }}"--}}
{{--                                       placeholder="XS,S,M,L,XL,XXL">--}}
{{--                                <div class="form-text text-muted">Comma-separated list of sizes</div>--}}
{{--                            </div>--}}

{{--                            <!-- Colors Selection - Improved Visual Layout -->--}}
{{--                            <div class="mb-4">--}}
{{--                                <label class="form-label fw-semibold">Available Colors</label>--}}
{{--                                @php--}}
{{--                                    $palette = \App\Support\ColorPalette::all();--}}
{{--                                    $oldSelectedColors = collect(old('available_colors', []))--}}
{{--                                        ->map(fn($c) => strtolower(trim(is_string($c) ? $c : strval($c))))--}}
{{--                                        ->toArray();--}}
{{--                                @endphp--}}
{{--                                <div class="border rounded p-3 bg-white" style="max-height: 220px; overflow-y: auto;">--}}
{{--                                    <div class="row g-3">--}}
{{--                                        @foreach($palette as $colorName => $hex)--}}
{{--                                            @php--}}
{{--                                                $id = 'color_' . str_replace([' ', '#'], ['_', ''], strtolower($colorName));--}}
{{--                                                $checked = in_array(strtolower($colorName), $oldSelectedColors);--}}
{{--                                            @endphp--}}
{{--                                            <div class="col-6 col-md-4 col-lg-3">--}}
{{--                                                <div class="form-check">--}}
{{--                                                    <input class="form-check-input" type="checkbox" name="available_colors[]"--}}
{{--                                                           id="{{ $id }}" value="{{ $colorName }}" {{ $checked ? 'checked' : '' }}>--}}
{{--                                                    <label class="form-check-label d-flex align-items-center gap-2 w-100" for="{{ $id }}">--}}
{{--                                        <span class="rounded-circle border shadow-sm"--}}
{{--                                              style="display:inline-block;width:20px;height:20px;background-color: {{ $hex }};"></span>--}}
{{--                                                        <span class="text-capitalize small">{{ $colorName }}</span>--}}
{{--                                                    </label>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        @endforeach--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="form-text text-muted mt-2">Select one or more colors for variant generation</div>--}}
{{--                            </div>--}}

{{--                            <!-- Generate Button -->--}}
{{--                            <div class="text-center border-top pt-3">--}}
{{--                                <button type="button" class="btn btn-primary" id="generateVariants">--}}
{{--                                    <i class="bi bi-plus-circle me-2"></i> Generate Variant Combinations--}}
{{--                                </button>--}}
{{--                                <div class="form-text text-muted mt-2">Click to generate all size/color combinations</div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <!-- Variants Container -->--}}
{{--                        <div id="variantsContainer" class="variants-container mt-3">--}}
{{--                            <!-- Variants will be generated here -->--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Inventory & Variants</h5>

                    <!-- Main Toggle Section -->
                    <div class="mb-4 p-3 border rounded bg-light">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="has_variants"
                                   name="has_variants" value="1" {{ old('has_variants') ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="has_variants">
                                Product has variants (sizes, colors)
                            </label>
                        </div>
                        <div class="form-text text-muted">Enable if product comes in different sizes, colors, etc.</div>
                    </div>

                    <!-- Simple Inventory - Better Visual Separation -->
                    <div id="simpleInventoryFields" class="simple-inventory border rounded p-3 bg-light" style="{{ old('has_variants') ? 'display: none;' : '' }}">
                        <div class="mb-3">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="manage_stock"
                                       name="manage_stock" value="1" {{ old('manage_stock') ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="manage_stock">
                                    Manage Stock
                                </label>
                            </div>
                            <div class="form-text text-muted">Enable stock quantity tracking</div>
                        </div>

                        <div id="stockFields" class="stock-fields mt-3 p-3 border rounded bg-white" style="{{ old('manage_stock') ? '' : 'display: none;' }}">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="stock_quantity" class="form-label fw-semibold">Stock Quantity</label>
                                    <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror"
                                           id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', 0) }}"
                                           min="0">
                                    @error('stock_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="min_stock_level" class="form-label fw-semibold">Low Stock Alert Level</label>
                                    <input type="number" class="form-control @error('min_stock_level') is-invalid @enderror"
                                           id="min_stock_level" name="min_stock_level" value="{{ old('min_stock_level', 5) }}"
                                           min="0">
                                    <div class="form-text text-muted">Alert when stock falls below this level</div>
                                    @error('min_stock_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Variants Section - Improved Layout -->
                    <div id="variantsSection" class="variants-section mt-3" style="{{ old('has_variants') ? '' : 'display: none;' }}">
                        <div class="border rounded p-3 bg-light">
                            <!-- Sizes Input -->
                            <div class="mb-4">
                                <label for="available_sizes" class="form-label fw-semibold">Available Sizes</label>
                                <input type="text" class="form-control" id="available_sizes" name="available_sizes"
                                       value="{{ old('available_sizes', 'XS,S,M,L,XL,XXL') }}"
                                       placeholder="XS,S,M,L,XL,XXL">
                                <div class="form-text text-muted">Comma-separated list of sizes</div>
                            </div>

                            <!-- Colors Selection - Improved Visual Layout with Search -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Available Colors</label>

                                <!-- Search Input -->
                                <div class="mb-3">
                                    <input type="text" class="form-control" id="colorSearch" placeholder="Search colors...">
                                    <div class="form-text text-muted">Type to filter colors by name</div>
                                </div>

                                @php
                                    $palette = \App\Support\ColorPalette::all();
                                    $oldSelectedColors = collect(old('available_colors', []))
                                        ->map(fn($c) => strtolower(trim(is_string($c) ? $c : strval($c))))
                                        ->toArray();
                                @endphp
                                <div class="border rounded p-3 bg-white" style="max-height: 220px; overflow-y: auto;">
                                    <div class="row g-3" id="colorOptions">
                                        @foreach($palette as $colorName => $hex)
                                            @php
                                                $id = 'color_' . str_replace([' ', '#'], ['_', ''], strtolower($colorName));
                                                $checked = in_array(strtolower($colorName), $oldSelectedColors);
                                            @endphp
                                            <div class="col-6 col-md-4 col-lg-3 color-option" data-color-name="{{ strtolower($colorName) }}">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="available_colors[]"
                                                           id="{{ $id }}" value="{{ $colorName }}" {{ $checked ? 'checked' : '' }}>
                                                    <label class="form-check-label d-flex align-items-center gap-2 w-100" for="{{ $id }}">
                                        <span class="rounded-circle border shadow-sm"
                                              style="display:inline-block;width:20px;height:20px;background-color: {{ $hex }};"></span>
                                                        <span class="text-capitalize small">{{ $colorName }}</span>
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-text text-muted mt-2">Select one or more colors for variant generation</div>
                            </div>

                            <!-- Generate Button -->
                            <div class="text-center border-top pt-3">
                                <button type="button" class="btn btn-primary" id="generateVariants">
                                    <i class="bi bi-plus-circle me-2"></i> Generate Variant Combinations
                                </button>
                                <div class="form-text text-muted mt-2">Click to generate all size/color combinations</div>
                            </div>
                        </div>

                        <!-- Variants Container -->
                        <div id="variantsContainer" class="variants-container mt-3">
                            <!-- Variants will be generated here -->
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
                            <span class="input-group-text">Kg</span>
                        </div>
                        @error('weight')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="dimensions" class="form-label">Dimensions</label>
                        <input type="text" class="form-control @error('dimensions') is-invalid @enderror"
                               id="dimensions" name="dimensions" value="{{ old('dimensions') }}"
                               placeholder="L x W x H (cm)">
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

            // Variant management
            const hasVariantsCheckbox = document.getElementById('has_variants');
            const simpleInventoryFields = document.getElementById('simpleInventoryFields');
            const variantsSection = document.getElementById('variantsSection');
            const generateVariantsBtn = document.getElementById('generateVariants');
            const variantsContainer = document.getElementById('variantsContainer');

            // Toggle between simple inventory and variants
            hasVariantsCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    simpleInventoryFields.style.display = 'none';
                    variantsSection.style.display = 'block';
                } else {
                    simpleInventoryFields.style.display = 'block';
                    variantsSection.style.display = 'none';
                    variantsContainer.innerHTML = '';
                }
            });

            // Generate variant combinations
            generateVariantsBtn.addEventListener('click', function() {
                const sizesInput = document.getElementById('available_sizes').value.trim();
                const basePrice = parseFloat(priceInput.value) || 0;

                // Collect selected colors from checkboxes
                const colorCheckboxes = document.querySelectorAll('input[name="available_colors[]"]:checked');
                const colors = Array.from(colorCheckboxes).map(cb => cb.value.trim()).filter(Boolean);

                if (!sizesInput || colors.length === 0) {
                    alert('Please enter sizes and select at least one color');
                    return;
                }

                const sizes = sizesInput.split(',').map(s => s.trim()).filter(s => s);

                if (sizes.length === 0) {
                    alert('Please enter valid sizes');
                    return;
                }

                let variantsHtml = '<h6 class="mb-3">Variant Combinations</h6>';
                variantsHtml += '<div class="table-responsive">';
                variantsHtml += '<table class="table table-sm">';
                variantsHtml += '<thead><tr><th>Size</th><th>Color</th><th>Color Image</th><th>SKU Suffix</th><th>Price (UGX)</th><th>Stock Quantity</th><th>Active</th></tr></thead>';
                variantsHtml += '<tbody>';

                let variantIndex = 0;
                sizes.forEach(size => {
                    colors.forEach(color => {
                        const skuSuffix = `${size}-${color.toUpperCase()}`;
                        variantsHtml += `
                            <tr>
                                <td>
                                    <input type="hidden" name="variants[${variantIndex}][size]" value="${size}">
                                    <span class="badge bg-secondary">${size}</span>
                                </td>
                                <td>
                                    <input type="hidden" name="variants[${variantIndex}][color]" value="${color}">
                                    <span class="badge bg-info">${color}</span>
                                </td>
                                <td>
                                    <input type="file" name="variants[${variantIndex}][image]" accept="image/*" class="form-control form-control-sm" style="width: 160px;" />
                                </td>
                                <td>
                                    <input type="text" name="variants[${variantIndex}][sku_suffix]"
                                           value="${skuSuffix}" class="form-control form-control-sm"
                                           style="width: 120px;" readonly>
                                </td>
                                <td>
                                    <input type="number" name="variants[${variantIndex}][price]"
                                           value="${basePrice}" class="form-control form-control-sm"
                                           step="0.01" min="0" style="width: 100px;">
                                </td>
                                <td>
                                    <input type="number" name="variants[${variantIndex}][stock_quantity]"
                                           value="0" class="form-control form-control-sm"
                                           min="0" style="width: 80px;">
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" name="variants[${variantIndex}][is_active]"
                                               value="1" checked class="form-check-input">
                                    </div>
                                </td>
                            </tr>
                        `;
                        variantIndex++;
                    });
                });

                variantsHtml += '</tbody></table></div>';
                variantsHtml += '<div class="mt-3"><small class="text-muted">Tip: Adjust prices and stock quantities for each variant as needed. SKU will be automatically generated as: BASE_SKU-SIZE-COLOR</small></div>';

                variantsContainer.innerHTML = variantsHtml;
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const colorSearch = document.getElementById('colorSearch');
            const colorOptions = document.querySelectorAll('.color-option');

            if (colorSearch) {
                colorSearch.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase().trim();

                    colorOptions.forEach(option => {
                        const colorName = option.getAttribute('data-color-name');

                        if (colorName.includes(searchTerm)) {
                            option.style.display = 'block';
                        } else {
                            option.style.display = 'none';
                        }
                    });
                });

                // Clear search on escape key
                colorSearch.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        colorSearch.value = '';
                        colorOptions.forEach(option => {
                            option.style.display = 'block';
                        });
                    }
                });
            }
        });
    </script>
@endpush
