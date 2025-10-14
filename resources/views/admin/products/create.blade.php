{{--@extends('admin.layouts.app')--}}

{{--@section('title', 'Add Product')--}}
{{--@section('page-title', 'Add New Product')--}}

{{--@section('content')--}}
{{--    <div class="d-flex justify-content-between align-items-center mb-4">--}}
{{--        <div>--}}
{{--            <h2 class="h4 mb-0">Add New Product</h2>--}}
{{--            <p class="text-muted mb-0">Create a new product for your store</p>--}}
{{--        </div>--}}
{{--        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">--}}
{{--            <i class="bi bi-arrow-left me-1"></i> Back to Products--}}
{{--        </a>--}}
{{--    </div>--}}

{{--    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">--}}
{{--        @csrf--}}

{{--        <div class="row">--}}
{{--            <!-- Main Product Information -->--}}
{{--            <div class="col-lg-8">--}}
{{--                <div class="stat-card p-4 mb-4">--}}
{{--                    <h5 class="mb-3">Basic Information</h5>--}}

{{--                    <div class="mb-3">--}}
{{--                        <label for="name" class="form-label">Product Name *</label>--}}
{{--                        <input type="text" class="form-control @error('name') is-invalid @enderror"--}}
{{--                               id="name" name="name" value="{{ old('name') }}" required>--}}
{{--                        @error('name')--}}
{{--                        <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}

{{--                    <div class="mb-3">--}}
{{--                        <label for="slug" class="form-label">Slug</label>--}}
{{--                        <input type="text" class="form-control @error('slug') is-invalid @enderror"--}}
{{--                               id="slug" name="slug" value="{{ old('slug') }}"--}}
{{--                               placeholder="Auto-generated from product name">--}}
{{--                        <div class="form-text">Leave empty to auto-generate from product name</div>--}}
{{--                        @error('slug')--}}
{{--                        <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}

{{--                    <div class="mb-3">--}}
{{--                        <label for="short_description" class="form-label">Short Description</label>--}}
{{--                        <textarea class="form-control @error('short_description') is-invalid @enderror"--}}
{{--                                  id="short_description" name="short_description" rows="3"--}}
{{--                                  maxlength="500">{{ old('short_description') }}</textarea>--}}
{{--                        <div class="form-text">Brief description for product listings (max 500 characters)</div>--}}
{{--                        @error('short_description')--}}
{{--                        <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}

{{--                    <div class="mb-3">--}}
{{--                        <label for="description" class="form-label">Full Description *</label>--}}
{{--                        <textarea class="form-control @error('description') is-invalid @enderror"--}}
{{--                                  id="description" name="description" rows="8" required>{{ old('description') }}</textarea>--}}
{{--                        @error('description')--}}
{{--                        <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <!-- Product Images -->--}}
{{--                <div class="stat-card p-4 mb-4">--}}
{{--                    <h5 class="mb-3">Product Images</h5>--}}

{{--                    <div class="mb-3">--}}
{{--                        <label for="images" class="form-label">Upload Images</label>--}}
{{--                        <input type="file" class="form-control @error('images.*') is-invalid @enderror"--}}
{{--                               id="images" name="images[]" multiple accept="image/*">--}}
{{--                        <div class="form-text">--}}
{{--                            Upload multiple images (JPEG, PNG, JPG, GIF, WebP). Max 2MB per image.--}}
{{--                            First image will be used as the main product image.--}}
{{--                        </div>--}}
{{--                        @error('images.*')--}}
{{--                        <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}

{{--                    <div id="imagePreview" class="row g-3" style="display: none;">--}}
{{--                        <!-- Image previews will be shown here -->--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <!-- SEO Settings -->--}}
{{--                <div class="stat-card p-4 mb-4">--}}
{{--                    <h5 class="mb-3">SEO Settings</h5>--}}

{{--                    <div class="mb-3">--}}
{{--                        <label for="meta_title" class="form-label">Meta Title</label>--}}
{{--                        <input type="text" class="form-control @error('meta_title') is-invalid @enderror"--}}
{{--                               id="meta_title" name="meta_title" value="{{ old('meta_title') }}" maxlength="255">--}}
{{--                        <div class="form-text">Leave empty to use product name as meta title</div>--}}
{{--                        @error('meta_title')--}}
{{--                        <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}

{{--                    <div class="mb-3">--}}
{{--                        <label for="meta_description" class="form-label">Meta Description</label>--}}
{{--                        <textarea class="form-control @error('meta_description') is-invalid @enderror"--}}
{{--                                  id="meta_description" name="meta_description" rows="3"--}}
{{--                                  maxlength="500">{{ old('meta_description') }}</textarea>--}}
{{--                        <div class="form-text">Brief description for search engines (max 500 characters)</div>--}}
{{--                        @error('meta_description')--}}
{{--                        <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <!-- Sidebar -->--}}
{{--            <div class="col-lg-4">--}}
{{--                <!-- Product Details -->--}}
{{--                <div class="stat-card p-4 mb-4">--}}
{{--                    <h5 class="mb-3">Product Details</h5>--}}

{{--                    <div class="mb-3">--}}
{{--                        <label for="sku" class="form-label">SKU *</label>--}}
{{--                        <input type="text" class="form-control @error('sku') is-invalid @enderror"--}}
{{--                               id="sku" name="sku" value="{{ old('sku') }}" required>--}}
{{--                        @error('sku')--}}
{{--                        <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}

{{--                    <div class="mb-3">--}}
{{--                        <label for="category_id" class="form-label">Category *</label>--}}
{{--                        <select class="form-select @error('category_id') is-invalid @enderror"--}}
{{--                                id="category_id" name="category_id" required>--}}
{{--                            <option value="">Select Category</option>--}}
{{--                            @foreach($categoryGroups as $parent)--}}
{{--                                @if($parent->children->isNotEmpty())--}}
{{--                                    <optgroup label="{{ $parent->name }}">--}}
{{--                                        @foreach($parent->children as $child)--}}
{{--                                            <option value="{{ $child->id }}" {{ old('category_id') == $child->id ? 'selected' : '' }}>--}}
{{--                                                {{ $child->name }}--}}
{{--                                            </option>--}}
{{--                                        @endforeach--}}
{{--                                    </optgroup>--}}
{{--                                @else--}}
{{--                                    <option value="" disabled style="font-weight: bold;">{{ $parent->name }}</option>--}}
{{--                                @endif--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                        @error('category_id')--}}
{{--                        <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}

{{--                    <div class="mb-3">--}}
{{--                        <label for="price" class="form-label">Regular Price *</label>--}}
{{--                        <div class="input-group">--}}
{{--                            <span class="input-group-text">UGX</span>--}}
{{--                            <input type="number" class="form-control @error('price') is-invalid @enderror"--}}
{{--                                   id="price" name="price" value="{{ old('price') }}"--}}
{{--                                   step="0.01" min="0" required>--}}
{{--                        </div>--}}
{{--                        @error('price')--}}
{{--                        <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}

{{--                    <div class="mb-3">--}}
{{--                        <label for="sale_price" class="form-label">Sale Price</label>--}}
{{--                        <div class="input-group">--}}
{{--                            <span class="input-group-text">UGX</span>--}}
{{--                            <input type="number" class="form-control @error('sale_price') is-invalid @enderror"--}}
{{--                                   id="sale_price" name="sale_price" value="{{ old('sale_price') }}"--}}
{{--                                   step="0.01" min="0">--}}
{{--                        </div>--}}
{{--                        <div class="form-text">Leave empty if no sale price</div>--}}
{{--                        @error('sale_price')--}}
{{--                        <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <!-- Inventory & Variants -->--}}
{{--                <div class="stat-card p-4 mb-4">--}}
{{--                    <h5 class="mb-3">Inventory & Variants</h5>--}}

{{--                    <div class="mb-3">--}}
{{--                        <div class="form-check form-switch">--}}
{{--                            <input class="form-check-input" type="checkbox" id="has_variants"--}}
{{--                                   name="has_variants" value="1" {{ old('has_variants') ? 'checked' : '' }}>--}}
{{--                            <label class="form-check-label" for="has_variants">--}}
{{--                                Product has variants (sizes, colors)--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                        <div class="form-text">Enable if product comes in different sizes, colors, etc.</div>--}}
{{--                    </div>--}}

{{--                    <div id="simpleInventoryFields" class="simple-inventory" style="{{ old('has_variants') ? 'display: none;' : '' }}">--}}
{{--                        <div class="mb-3">--}}
{{--                            <div class="form-check form-switch">--}}
{{--                                <input class="form-check-input" type="checkbox" id="manage_stock"--}}
{{--                                       name="manage_stock" value="1" {{ old('manage_stock') ? 'checked' : '' }}>--}}
{{--                                <label class="form-check-label" for="manage_stock">--}}
{{--                                    Manage Stock--}}
{{--                                </label>--}}
{{--                            </div>--}}
{{--                            <div class="form-text">Enable stock quantity tracking</div>--}}
{{--                        </div>--}}

{{--                        <div id="stockFields" class="stock-fields" style="{{ old('manage_stock') ? '' : 'display: none;' }}">--}}
{{--                            <div class="mb-3">--}}
{{--                                <label for="stock_quantity" class="form-label">Stock Quantity</label>--}}
{{--                                <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror"--}}
{{--                                       id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', 0) }}"--}}
{{--                                       min="0">--}}
{{--                                @error('stock_quantity')--}}
{{--                                <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                                @enderror--}}
{{--                            </div>--}}

{{--                            <div class="mb-3">--}}
{{--                                <label for="min_stock_level" class="form-label">Low Stock Alert Level</label>--}}
{{--                                <input type="number" class="form-control @error('min_stock_level') is-invalid @enderror"--}}
{{--                                       id="min_stock_level" name="min_stock_level" value="{{ old('min_stock_level', 5) }}"--}}
{{--                                       min="0">--}}
{{--                                <div class="form-text">Alert when stock falls below this level</div>--}}
{{--                                @error('min_stock_level')--}}
{{--                                <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <!-- Variants Section -->--}}
{{--                    <div id="variantsSection" class="variants-section" style="{{ old('has_variants') ? '' : 'display: none;' }}">--}}
{{--                        <div class="row mb-3">--}}
{{--                            <div class="col-md-6">--}}
{{--                                <label for="available_sizes" class="form-label">Available Sizes</label>--}}
{{--                                <input type="text" class="form-control" id="available_sizes" name="available_sizes"--}}
{{--                                       value="{{ old('available_sizes', 'XS,S,M,L,XL,XXL') }}"--}}
{{--                                       placeholder="XS,S,M,L,XL,XXL">--}}
{{--                                <div class="form-text">Comma-separated list of sizes</div>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-6">--}}
{{--                                <label for="available_colors" class="form-label">Available Colors</label>--}}
{{--                                <input type="text" class="form-control" id="available_colors" name="available_colors"--}}
{{--                                       value="{{ old('available_colors', 'Black,White,Blue,Red,Green') }}"--}}
{{--                                       placeholder="Black,White,Blue,Red,Green">--}}
{{--                                <div class="form-text">Comma-separated list of colors</div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="mb-3">--}}
{{--                            <button type="button" class="btn btn-primary" id="generateVariants">--}}
{{--                                <i class="bi bi-plus-circle me-1"></i> Generate Variant Combinations--}}
{{--                            </button>--}}
{{--                            <div class="form-text">Click to generate all size/color combinations</div>--}}
{{--                        </div>--}}

{{--                        <div id="variantsContainer" class="variants-container">--}}
{{--                            <!-- Variants will be generated here -->--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <!-- Shipping -->--}}
{{--                <div class="stat-card p-4 mb-4">--}}
{{--                    <h5 class="mb-3">Shipping</h5>--}}

{{--                    <div class="mb-3">--}}
{{--                        <label for="weight" class="form-label">Weight</label>--}}
{{--                        <div class="input-group">--}}
{{--                            <input type="number" class="form-control @error('weight') is-invalid @enderror"--}}
{{--                                   id="weight" name="weight" value="{{ old('weight') }}"--}}
{{--                                   step="0.01" min="0">--}}
{{--                            <span class="input-group-text">Kg</span>--}}
{{--                        </div>--}}
{{--                        @error('weight')--}}
{{--                        <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}

{{--                    <div class="mb-3">--}}
{{--                        <label for="dimensions" class="form-label">Dimensions</label>--}}
{{--                        <input type="text" class="form-control @error('dimensions') is-invalid @enderror"--}}
{{--                               id="dimensions" name="dimensions" value="{{ old('dimensions') }}"--}}
{{--                               placeholder="L x W x H (cm)">--}}
{{--                        @error('dimensions')--}}
{{--                        <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <!-- Product Status -->--}}
{{--                <div class="stat-card p-4 mb-4">--}}
{{--                    <h5 class="mb-3">Status</h5>--}}

{{--                    <div class="mb-3">--}}
{{--                        <div class="form-check form-switch">--}}
{{--                            <input class="form-check-input" type="checkbox" id="is_active"--}}
{{--                                   name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>--}}
{{--                            <label class="form-check-label" for="is_active">--}}
{{--                                Active--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                        <div class="form-text">Product visible in store</div>--}}
{{--                    </div>--}}

{{--                    <div class="mb-3">--}}
{{--                        <div class="form-check form-switch">--}}
{{--                            <input class="form-check-input" type="checkbox" id="is_featured"--}}
{{--                                   name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>--}}
{{--                            <label class="form-check-label" for="is_featured">--}}
{{--                                Featured--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                        <div class="form-text">Show in featured products section</div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <!-- Submit Buttons -->--}}
{{--                <div class="d-grid gap-2">--}}
{{--                    <button type="submit" class="btn btn-primary btn-admin">--}}
{{--                        <i class="bi bi-check-circle me-1"></i> Create Product--}}
{{--                    </button>--}}
{{--                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">--}}
{{--                        Cancel--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </form>--}}
{{--@endsection--}}

{{--@push('scripts')--}}
{{--    <script>--}}
{{--        document.addEventListener('DOMContentLoaded', function() {--}}
{{--            // Auto-generate slug from product name--}}
{{--            const nameInput = document.getElementById('name');--}}
{{--            const slugInput = document.getElementById('slug');--}}

{{--            nameInput.addEventListener('input', function() {--}}
{{--                if (!slugInput.dataset.manual) {--}}
{{--                    slugInput.value = this.value--}}
{{--                        .toLowerCase()--}}
{{--                        .replace(/[^a-z0-9]+/g, '-')--}}
{{--                        .replace(/^-+|-+$/g, '');--}}
{{--                }--}}
{{--            });--}}

{{--            slugInput.addEventListener('input', function() {--}}
{{--                this.dataset.manual = 'true';--}}
{{--            });--}}

{{--            // Toggle stock fields--}}
{{--            const manageStockCheckbox = document.getElementById('manage_stock');--}}
{{--            const stockFields = document.getElementById('stockFields');--}}

{{--            manageStockCheckbox.addEventListener('change', function() {--}}
{{--                stockFields.style.display = this.checked ? 'block' : 'none';--}}
{{--            });--}}

{{--            // Image preview--}}
{{--            const imageInput = document.getElementById('images');--}}
{{--            const imagePreview = document.getElementById('imagePreview');--}}

{{--            imageInput.addEventListener('change', function() {--}}
{{--                imagePreview.innerHTML = '';--}}

{{--                if (this.files.length > 0) {--}}
{{--                    imagePreview.style.display = 'block';--}}

{{--                    Array.from(this.files).forEach((file, index) => {--}}
{{--                        if (file.type.startsWith('image/')) {--}}
{{--                            const reader = new FileReader();--}}
{{--                            reader.onload = function(e) {--}}
{{--                                const col = document.createElement('div');--}}
{{--                                col.className = 'col-md-3';--}}
{{--                                col.innerHTML = `--}}
{{--                            <div class="position-relative">--}}
{{--                                <img src="${e.target.result}" class="img-fluid rounded" style="height: 120px; width: 100%; object-fit: cover;">--}}
{{--                                ${index === 0 ? '<div class="badge bg-primary position-absolute top-0 start-0 m-1">Main</div>' : ''}--}}
{{--                            </div>--}}
{{--                        `;--}}
{{--                                imagePreview.appendChild(col);--}}
{{--                            };--}}
{{--                            reader.readAsDataURL(file);--}}
{{--                        }--}}
{{--                    });--}}
{{--                } else {--}}
{{--                    imagePreview.style.display = 'none';--}}
{{--                }--}}
{{--            });--}}

{{--            // Price validation--}}
{{--            const priceInput = document.getElementById('price');--}}
{{--            const salePriceInput = document.getElementById('sale_price');--}}

{{--            salePriceInput.addEventListener('input', function() {--}}
{{--                const price = parseFloat(priceInput.value) || 0;--}}
{{--                const salePrice = parseFloat(this.value) || 0;--}}

{{--                if (salePrice > 0 && salePrice >= price) {--}}
{{--                    this.setCustomValidity('Sale price must be less than regular price');--}}
{{--                } else {--}}
{{--                    this.setCustomValidity('');--}}
{{--                }--}}
{{--            });--}}

{{--            // Variant management--}}
{{--            const hasVariantsCheckbox = document.getElementById('has_variants');--}}
{{--            const simpleInventoryFields = document.getElementById('simpleInventoryFields');--}}
{{--            const variantsSection = document.getElementById('variantsSection');--}}
{{--            const generateVariantsBtn = document.getElementById('generateVariants');--}}
{{--            const variantsContainer = document.getElementById('variantsContainer');--}}

{{--            // Toggle between simple inventory and variants--}}
{{--            hasVariantsCheckbox.addEventListener('change', function() {--}}
{{--                if (this.checked) {--}}
{{--                    simpleInventoryFields.style.display = 'none';--}}
{{--                    variantsSection.style.display = 'block';--}}
{{--                } else {--}}
{{--                    simpleInventoryFields.style.display = 'block';--}}
{{--                    variantsSection.style.display = 'none';--}}
{{--                    variantsContainer.innerHTML = '';--}}
{{--                }--}}
{{--            });--}}

{{--            // Generate variant combinations--}}
{{--            generateVariantsBtn.addEventListener('click', function() {--}}
{{--                const sizesInput = document.getElementById('available_sizes').value.trim();--}}
{{--                const colorsInput = document.getElementById('available_colors').value.trim();--}}
{{--                const basePrice = parseFloat(priceInput.value) || 0;--}}

{{--                if (!sizesInput || !colorsInput) {--}}
{{--                    alert('Please enter both sizes and colors');--}}
{{--                    return;--}}
{{--                }--}}

{{--                const sizes = sizesInput.split(',').map(s => s.trim()).filter(s => s);--}}
{{--                const colors = colorsInput.split(',').map(c => c.trim()).filter(c => c);--}}

{{--                if (sizes.length === 0 || colors.length === 0) {--}}
{{--                    alert('Please enter valid sizes and colors');--}}
{{--                    return;--}}
{{--                }--}}

{{--                let variantsHtml = '<h6 class="mb-3">Variant Combinations</h6>';--}}
{{--                variantsHtml += '<div class="table-responsive">';--}}
{{--                variantsHtml += '<table class="table table-sm">';--}}
{{--                variantsHtml += '<thead><tr><th>Size</th><th>Color</th><th>Image</th><th>SKU Suffix</th><th>Price (UGX)</th><th>Stock Quantity</th><th>Active</th></tr></thead>';--}}
{{--                variantsHtml += '<tbody>';--}}

{{--                let variantIndex = 0;--}}
{{--                sizes.forEach(size => {--}}
{{--                    colors.forEach(color => {--}}
{{--                        const skuSuffix = `${size}-${color.toUpperCase()}`;--}}
{{--                        variantsHtml += `--}}
{{--                <tr>--}}
{{--                    <td>--}}
{{--                        <input type="hidden" name="variants[${variantIndex}][size]" value="${size}">--}}
{{--                        <span class="badge bg-secondary">${size}</span>--}}
{{--                    </td>--}}
{{--                    <td>--}}
{{--                        <input type="hidden" name="variants[${variantIndex}][color]" value="${color}">--}}
{{--                        <span class="badge bg-info">${color}</span>--}}
{{--                    </td>--}}
{{--                    <td>--}}
{{--                        <input type="file" name="variants[${variantIndex}][image]" class="form-control form-control-sm" accept="image/*" required>--}}
{{--                        <div class="image-preview mt-1" id="image-preview-${variantIndex}" style="display: none;">--}}
{{--                            <img src="" class="img-fluid rounded" style="height: 60px; width: auto;">--}}
{{--                        </div>--}}
{{--                    </td>--}}
{{--                    <td>--}}
{{--                        <input type="text" name="variants[${variantIndex}][sku_suffix]" value="${skuSuffix}" class="form-control form-control-sm" style="width: 120px;" readonly>--}}
{{--                    </td>--}}
{{--                    <td>--}}
{{--                        <input type="number" name="variants[${variantIndex}][price]" value="${basePrice}" class="form-control form-control-sm" step="0.01" min="0" style="width: 100px;">--}}
{{--                    </td>--}}
{{--                    <td>--}}
{{--                        <input type="number" name="variants[${variantIndex}][stock_quantity]" value="0" class="form-control form-control-sm" min="0" style="width: 80px;">--}}
{{--                    </td>--}}
{{--                    <td>--}}
{{--                        <div class="form-check form-switch">--}}
{{--                            <input type="checkbox" name="variants[${variantIndex}][is_active]" value="1" checked class="form-check-input">--}}
{{--                        </div>--}}
{{--                    </td>--}}
{{--                </tr>--}}
{{--            `;--}}
{{--                        variantIndex++;--}}
{{--                    });--}}
{{--                });--}}

{{--                variantsHtml += '</tbody></table></div>';--}}
{{--                variantsHtml += '<div class="mt-3"><small class="text-muted">Tip: Upload an image for each variant. Adjust prices and stock quantities as needed. SKU will be automatically generated as: BASE_SKU-SIZE-COLOR</small></div>';--}}

{{--                variantsContainer.innerHTML = variantsHtml;--}}

{{--                // Add image preview for each variant image input--}}
{{--                document.querySelectorAll('input[type="file"][name*="variants"][name*="image"]').forEach(input => {--}}
{{--                    input.addEventListener('change', function() {--}}
{{--                        const previewId = `image-preview-${this.name.match(/\[(\d+)\]/)[1]}`;--}}
{{--                        const previewDiv = document.getElementById(previewId);--}}
{{--                        const imgElement = previewDiv.querySelector('img');--}}

{{--                        if (this.files.length > 0 && this.files[0].type.startsWith('image/')) {--}}
{{--                            const reader = new FileReader();--}}
{{--                            reader.onload = function(e) {--}}
{{--                                imgElement.src = e.target.result;--}}
{{--                                previewDiv.style.display = 'block';--}}
{{--                            };--}}
{{--                            reader.readAsDataURL(this.files[0]);--}}
{{--                        } else {--}}
{{--                            previewDiv.style.display = 'none';--}}
{{--                        }--}}
{{--                    });--}}
{{--                });--}}
{{--            });--}}

{{--        });--}}
{{--    </script>--}}
{{--@endpush--}}

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

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
        @csrf

        <div class="row">
            <!-- Main Product Information -->
            <div class="col-lg-8">
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Basic Information</h5>

                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
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
                        <div class="form-text">
                            <span id="shortDescCount">0</span>/500 characters
                        </div>
                        @error('short_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Full Description <span class="text-danger">*</span></label>
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
                               id="images" name="images[]" multiple accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                        <div class="form-text">
                            Upload multiple images (JPEG, PNG, JPG, GIF, WebP). Max 2MB per image.
                            First image will be used as the main product image.
                        </div>
                        @error('images.*')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
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
                        <div class="form-text">
                            <span id="metaTitleCount">0</span>/255 characters. Leave empty to use product name.
                        </div>
                        @error('meta_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea class="form-control @error('meta_description') is-invalid @enderror"
                                  id="meta_description" name="meta_description" rows="3"
                                  maxlength="500">{{ old('meta_description') }}</textarea>
                        <div class="form-text">
                            <span id="metaDescCount">0</span>/500 characters. Brief description for search engines.
                        </div>
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
                        <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('sku') is-invalid @enderror"
                               id="sku" name="sku" value="{{ old('sku') }}" required>
                        <div class="form-text">Unique product identifier</div>
                        @error('sku')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
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
                                    <option value="{{ $parent->id }}" {{ old('category_id') == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Regular Price <span class="text-danger">*</span></label>
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
                        <div id="salePriceError" class="text-danger small" style="display: none;">
                            Sale price must be less than regular price
                        </div>
                        @error('sale_price')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Inventory & Variants -->
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Inventory & Variants</h5>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="has_variants"
                                   name="has_variants" value="1" {{ old('has_variants') ? 'checked' : '' }}>
                            <label class="form-check-label" for="has_variants">
                                Product has variants (sizes, colors)
                            </label>
                        </div>
                        <div class="form-text">Enable if product comes in different sizes, colors, etc.</div>
                    </div>

                    <div id="simpleInventoryFields" class="simple-inventory" style="{{ old('has_variants') ? 'display: none;' : '' }}">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="manage_stock"
                                       name="manage_stock" value="1" {{ old('manage_stock', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="manage_stock">
                                    Manage Stock
                                </label>
                            </div>
                            <div class="form-text">Enable stock quantity tracking</div>
                        </div>

                        <div id="stockFields" class="stock-fields" style="{{ old('manage_stock', true) ? '' : 'display: none;' }}">
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

                    <!-- Variants Section -->
                    <div id="variantsSection" class="variants-section" style="{{ old('has_variants') ? '' : 'display: none;' }}">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="available_sizes" class="form-label">Available Sizes</label>
                                <input type="text" class="form-control" id="available_sizes" name="available_sizes"
                                       value="{{ old('available_sizes', 'XS,S,M,L,XL,XXL') }}"
                                       placeholder="XS,S,M,L,XL,XXL">
                                <div class="form-text">Comma-separated</div>
                            </div>
                            <div class="col-md-6">
                                <label for="available_colors" class="form-label">Available Colors</label>
                                <input type="text" class="form-control" id="available_colors" name="available_colors"
                                       value="{{ old('available_colors', 'Black,White,Blue,Red,Green') }}"
                                       placeholder="Black,White,Blue,Red,Green">
                                <div class="form-text">Comma-separated</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button type="button" class="btn btn-primary btn-sm" id="generateVariants">
                                <i class="bi bi-plus-circle me-1"></i> Generate Variant Combinations
                            </button>
                            <div class="form-text mt-2">Click to generate all size/color combinations</div>
                        </div>

                        <div id="variantsContainer" class="variants-container">
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
                                   step="0.01" min="0" placeholder="0.00">
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
            // Character counters
            const charCounters = [
                { input: 'short_description', counter: 'shortDescCount' },
                { input: 'meta_title', counter: 'metaTitleCount' },
                { input: 'meta_description', counter: 'metaDescCount' }
            ];

            charCounters.forEach(item => {
                const input = document.getElementById(item.input);
                const counter = document.getElementById(item.counter);
                if (input && counter) {
                    const updateCounter = () => {
                        counter.textContent = input.value.length;
                    };
                    updateCounter();
                    input.addEventListener('input', updateCounter);
                }
            });

            // Auto-generate slug from product name
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');

            if (nameInput && slugInput) {
                nameInput.addEventListener('input', function() {
                    if (!slugInput.dataset.manual) {
                        slugInput.value = this.value
                            .toLowerCase()
                            .replace(/[^a-z0-9]+/g, '-')
                            .replace(/^-+|-+$/g, '');
                    }
                });

                slugInput.addEventListener('input', function() {
                    if (this.value) {
                        this.dataset.manual = 'true';
                    }
                });
            }

            // Toggle stock fields
            const manageStockCheckbox = document.getElementById('manage_stock');
            const stockFields = document.getElementById('stockFields');

            if (manageStockCheckbox && stockFields) {
                manageStockCheckbox.addEventListener('change', function() {
                    stockFields.style.display = this.checked ? 'block' : 'none';
                });
            }

            // Image preview with file size validation
            const imageInput = document.getElementById('images');
            const imagePreview = document.getElementById('imagePreview');
            const maxFileSize = 2 * 1024 * 1024; // 2MB

            if (imageInput && imagePreview) {
                imageInput.addEventListener('change', function() {
                    imagePreview.innerHTML = '';
                    let hasError = false;

                    if (this.files.length > 0) {
                        imagePreview.style.display = 'block';

                        Array.from(this.files).forEach((file, index) => {
                            if (file.size > maxFileSize) {
                                hasError = true;
                                alert(`File "${file.name}" exceeds 2MB limit`);
                                return;
                            }

                            if (file.type.startsWith('image/')) {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    const col = document.createElement('div');
                                    col.className = 'col-md-3';
                                    col.innerHTML = `
                                        <div class="position-relative">
                                            <img src="${e.target.result}" class="img-fluid rounded"
                                                 style="height: 120px; width: 100%; object-fit: cover;"
                                                 alt="Preview">
                                            ${index === 0 ? '<div class="badge bg-primary position-absolute top-0 start-0 m-1">Main</div>' : ''}
                                        </div>
                                    `;
                                    imagePreview.appendChild(col);
                                };
                                reader.readAsDataURL(file);
                            }
                        });

                        if (hasError) {
                            this.value = '';
                            imagePreview.style.display = 'none';
                        }
                    } else {
                        imagePreview.style.display = 'none';
                    }
                });
            }

            // Price validation
            const priceInput = document.getElementById('price');
            const salePriceInput = document.getElementById('sale_price');
            const salePriceError = document.getElementById('salePriceError');

            if (priceInput && salePriceInput && salePriceError) {
                const validatePrice = () => {
                    const price = parseFloat(priceInput.value) || 0;
                    const salePrice = parseFloat(salePriceInput.value) || 0;

                    if (salePrice > 0 && salePrice >= price) {
                        salePriceError.style.display = 'block';
                        salePriceInput.setCustomValidity('Sale price must be less than regular price');
                    } else {
                        salePriceError.style.display = 'none';
                        salePriceInput.setCustomValidity('');
                    }
                };

                salePriceInput.addEventListener('input', validatePrice);
                priceInput.addEventListener('input', validatePrice);
            }

            // Variant management
            const hasVariantsCheckbox = document.getElementById('has_variants');
            const simpleInventoryFields = document.getElementById('simpleInventoryFields');
            const variantsSection = document.getElementById('variantsSection');
            const generateVariantsBtn = document.getElementById('generateVariants');
            const variantsContainer = document.getElementById('variantsContainer');

            // Toggle between simple inventory and variants
            if (hasVariantsCheckbox && simpleInventoryFields && variantsSection) {
                hasVariantsCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        simpleInventoryFields.style.display = 'none';
                        variantsSection.style.display = 'block';
                    } else {
                        simpleInventoryFields.style.display = 'block';
                        variantsSection.style.display = 'none';
                        if (variantsContainer) {
                            variantsContainer.innerHTML = '';
                        }
                    }
                });
            }

            // Generate variant combinations
            if (generateVariantsBtn && variantsContainer && priceInput) {
                generateVariantsBtn.addEventListener('click', function() {
                    const sizesInput = document.getElementById('available_sizes');
                    const colorsInput = document.getElementById('available_colors');

                    if (!sizesInput || !colorsInput) return;

                    const sizesValue = sizesInput.value.trim();
                    const colorsValue = colorsInput.value.trim();
                    const basePrice = parseFloat(priceInput.value) || 0;

                    if (!sizesValue || !colorsValue) {
                        alert('Please enter both sizes and colors');
                        return;
                    }

                    const sizes = sizesValue.split(',').map(s => s.trim()).filter(s => s);
                    const colors = colorsValue.split(',').map(c => c.trim()).filter(c => c);

                    if (sizes.length === 0 || colors.length === 0) {
                        alert('Please enter valid sizes and colors');
                        return;
                    }

                    let variantsHtml = '<h6 class="mb-3">Variant Combinations</h6>';
                    variantsHtml += '<div class="table-responsive">';
                    variantsHtml += '<table class="table table-sm table-bordered">';
                    variantsHtml += '<thead class="table-light"><tr><th>Size</th><th>Color</th><th>Image</th><th>SKU Suffix</th><th>Price (UGX)</th><th>Stock</th><th>Active</th></tr></thead>';
                    variantsHtml += '<tbody>';

                    let variantIndex = 0;
                    sizes.forEach(size => {
                        colors.forEach(color => {
                            const skuSuffix = `${size}-${color.substring(0, 3).toUpperCase()}`;
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
                                        <input type="file" name="variants[${variantIndex}][image]"
                                               class="form-control form-control-sm variant-image-input"
                                               accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                               data-index="${variantIndex}">
                                        <div class="image-preview mt-1" id="variant-image-preview-${variantIndex}" style="display: none;">
                                            <img src="" class="img-fluid rounded" style="height: 50px; width: auto;" alt="Variant preview">
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" name="variants[${variantIndex}][sku_suffix]"
                                               value="${skuSuffix}"
                                               class="form-control form-control-sm"
                                               style="width: 100px;" readonly>
                                    </td>
                                    <td>
                                        <input type="number" name="variants[${variantIndex}][price]"
                                               value="${basePrice}"
                                               class="form-control form-control-sm"
                                               step="0.01" min="0" style="width: 90px;">
                                    </td>
                                    <td>
                                        <input type="number" name="variants[${variantIndex}][stock_quantity]"
                                               value="0"
                                               class="form-control form-control-sm"
                                               min="0" style="width: 70px;">
                                    </td>
                                    <td class="text-center">
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
                    variantsHtml += '<div class="alert alert-info mt-3"><small><strong>Tip:</strong> Upload an image for each variant. Adjust prices and stock quantities as needed. Final SKU format: BASE_SKU-SIZE-COLOR</small></div>';

                    variantsContainer.innerHTML = variantsHtml;

                    // Add image preview for each variant image input
                    document.querySelectorAll('.variant-image-input').forEach(input => {
                        input.addEventListener('change', function() {
                            const index = this.dataset.index;
                            const previewDiv = document.getElementById(`variant-image-preview-${index}`);

                            if (!previewDiv) return;

                            const imgElement = previewDiv.querySelector('img');

                            if (this.files.length > 0 && this.files[0].type.startsWith('image/')) {
                                const file = this.files[0];

                                // Check file size
                                if (file.size > maxFileSize) {
                                    alert(`File size exceeds 2MB limit`);
                                    this.value = '';
                                    previewDiv.style.display = 'none';
                                    return;
                                }

                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    imgElement.src = e.target.result;
                                    previewDiv.style.display = 'block';
                                };
                                reader.readAsDataURL(file);
                            } else {
                                previewDiv.style.display = 'none';
                            }
                        });
                    });
                });
            }

            // Form submission validation
            const productForm = document.getElementById('productForm');
            if (productForm) {
                productForm.addEventListener('submit', function(e) {
                    const hasVariants = document.getElementById('has_variants');

                    if (hasVariants && hasVariants.checked) {
                        const variantInputs = document.querySelectorAll('input[name*="variants"]');
                        if (variantInputs.length === 0) {
                            e.preventDefault();
                            alert('Please generate variants before submitting');
                            return false;
                        }
                    }
                });
            }
        });
    </script>
@endpush
