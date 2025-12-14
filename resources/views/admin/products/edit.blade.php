{{--@extends('admin.layouts.app')--}}

{{--@section('title', 'Edit Product')--}}
{{--@section('page-title', 'Edit Product')--}}

{{--@section('content')--}}
{{--    <div class="d-flex justify-content-between align-items-center mb-4">--}}
{{--        <div>--}}
{{--            <h2 class="h4 mb-0">Edit Product</h2>--}}
{{--            <p class="text-muted mb-0">{{ $product->name }}</p>--}}
{{--        </div>--}}
{{--        <div class="d-flex gap-2">--}}
{{--            <a href="{{ route('admin.products.show', $product) }}" class="btn btn-outline-info">--}}
{{--                <i class="bi bi-eye me-1"></i> View--}}
{{--            </a>--}}
{{--            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">--}}
{{--                <i class="bi bi-arrow-left me-1"></i> Back--}}
{{--            </a>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    @if(session('error'))--}}
{{--        <div class="alert alert-danger alert-dismissible fade show" role="alert">--}}
{{--            {{ session('error') }}--}}
{{--            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>--}}
{{--        </div>--}}
{{--    @endif--}}

{{--    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" id="productForm">--}}
{{--        @csrf--}}
{{--        @method('PUT')--}}

{{--        <div class="row">--}}
{{--            <!-- Main Product Information -->--}}
{{--            <div class="col-lg-8">--}}
{{--                <div class="stat-card p-4 mb-4">--}}
{{--                    <h5 class="mb-3">Basic Information</h5>--}}

{{--                    <div class="mb-3">--}}
{{--                        <label for="name" class="form-label">Product Name *</label>--}}
{{--                        <input type="text" class="form-control @error('name') is-invalid @enderror"--}}
{{--                               id="name" name="name" value="{{ old('name', $product->name) }}" required>--}}
{{--                        @error('name')--}}
{{--                        <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}

{{--                    <div class="mb-3">--}}
{{--                        <label for="slug" class="form-label">Slug</label>--}}
{{--                        <input type="text" class="form-control @error('slug') is-invalid @enderror"--}}
{{--                               id="slug" name="slug" value="{{ old('slug', $product->slug) }}">--}}
{{--                        <div class="form-text">Leave empty to auto-generate from product name</div>--}}
{{--                        @error('slug')--}}
{{--                        <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}

{{--                    <div class="mb-3">--}}
{{--                        <label for="short_description" class="form-label">Short Description</label>--}}
{{--                        <textarea class="form-control @error('short_description') is-invalid @enderror"--}}
{{--                                  id="short_description" name="short_description" rows="3"--}}
{{--                                  maxlength="500">{{ old('short_description', $product->short_description) }}</textarea>--}}
{{--                        @error('short_description')--}}
{{--                        <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}

{{--                    <div class="mb-3">--}}
{{--                        <label for="description" class="form-label">Full Description *</label>--}}
{{--                        <textarea class="form-control @error('description') is-invalid @enderror"--}}
{{--                                  id="description" name="description" rows="8" required>{{ old('description', $product->description) }}</textarea>--}}
{{--                        @error('description')--}}
{{--                        <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <!-- Product Images -->--}}
{{--                <div class="stat-card p-4 mb-4">--}}
{{--                    <h5 class="mb-3">Product Images</h5>--}}

{{--                    <!-- Featured Image -->--}}
{{--                    @php--}}
{{--                        $featuredImageUrl = $product->featured_image ? $product->getStorageImageUrl($product->featured_image) : null;--}}
{{--                    @endphp--}}

{{--                    <div class="mb-4">--}}
{{--                        <h6>Featured Image</h6>--}}
{{--                        @if($featuredImageUrl)--}}
{{--                            <div class="row g-3 mb-3" id="currentFeaturedImage">--}}
{{--                                <div class="col-md-4">--}}
{{--                                    <div class="position-relative">--}}
{{--                                        <img src="{{ $featuredImageUrl }}"--}}
{{--                                             class="img-fluid rounded"--}}
{{--                                             alt="{{ $product->name }}"--}}
{{--                                             style="height: 200px; width: 100%; object-fit: cover;">--}}
{{--                                        <div class="badge bg-primary position-absolute top-0 start-0 m-2">Featured</div>--}}
{{--                                    </div>--}}
{{--                                    <div class="form-check mt-2">--}}
{{--                                        <input class="form-check-input" type="checkbox"--}}
{{--                                               name="remove_featured_image" value="1"--}}
{{--                                               id="remove_featured">--}}
{{--                                        <label class="form-check-label text-danger" for="remove_featured">--}}
{{--                                            <small>Remove featured image</small>--}}
{{--                                        </label>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        @else--}}
{{--                            <p class="text-muted" id="noFeaturedImageText">No featured image set</p>--}}
{{--                        @endif--}}

{{--                        <div class="mb-3">--}}
{{--                            <label for="featured_image" class="form-label">--}}
{{--                                {{ $featuredImageUrl ? 'Replace' : 'Upload' }} Featured Image--}}
{{--                            </label>--}}
{{--                            <input type="file" class="form-control @error('featured_image') is-invalid @enderror"--}}
{{--                                   id="featured_image" name="featured_image" accept="image/*">--}}
{{--                            <div class="form-text">Recommended: 800x800px. Max 4MB</div>--}}
{{--                            @error('featured_image')--}}
{{--                            <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                            @enderror--}}
{{--                        </div>--}}

{{--                        <!-- New featured image preview -->--}}
{{--                        <div id="featuredImagePreview" class="row g-3 mb-3" style="display: none;">--}}
{{--                            <div class="col-md-4">--}}
{{--                                <div class="position-relative">--}}
{{--                                    <img src="" id="featuredPreviewImg" class="img-fluid rounded"--}}
{{--                                         style="height: 200px; width: 100%; object-fit: cover;">--}}
{{--                                    <div class="badge bg-success position-absolute top-0 start-0 m-2">New</div>--}}
{{--                                </div>--}}
{{--                                <div class="text-center mt-2">--}}
{{--                                    <small class="text-muted" id="featuredFileName"></small>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <!-- Gallery Images -->--}}
{{--                    @php--}}
{{--                        $galleryImages = $product->gallery ?? [];--}}
{{--                        $hasGalleryImages = count($galleryImages) > 0;--}}
{{--                    @endphp--}}

{{--                    <div class="mb-4">--}}
{{--                        <h6>Gallery Images</h6>--}}
{{--                        @if($hasGalleryImages)--}}
{{--                            <div class="row g-3 mb-3">--}}
{{--                                @foreach($galleryImages as $index => $imagePath)--}}
{{--                                    <div class="col-md-3">--}}
{{--                                        <div class="position-relative">--}}
{{--                                            <img src="{{ $product->getStorageImageUrl($imagePath) }}"--}}
{{--                                                 class="img-fluid rounded"--}}
{{--                                                 alt="{{ $product->name }}"--}}
{{--                                                 style="height: 120px; width: 100%; object-fit: cover;">--}}
{{--                                            <div class="badge bg-info position-absolute top-0 end-0 m-1">{{ $index + 1 }}</div>--}}
{{--                                        </div>--}}
{{--                                        <div class="form-check mt-2">--}}
{{--                                            <input class="form-check-input" type="checkbox"--}}
{{--                                                   name="remove_gallery_images[]" value="{{ $index }}"--}}
{{--                                                   id="remove_gallery_{{ $index }}">--}}
{{--                                            <label class="form-check-label text-danger" for="remove_gallery_{{ $index }}">--}}
{{--                                                <small>Remove</small>--}}
{{--                                            </label>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                @endforeach--}}
{{--                            </div>--}}
{{--                        @endif--}}

{{--                        <!-- Check for legacy Spatie media -->--}}
{{--                        @if($product->getMedia('images')->count() > 0)--}}
{{--                            <div class="alert alert-info mb-3">--}}
{{--                                <small><i class="bi bi-info-circle me-1"></i> Legacy images detected</small>--}}
{{--                            </div>--}}
{{--                            <div class="row g-3 mb-3">--}}
{{--                                @foreach($product->getMedia('images') as $media)--}}
{{--                                    <div class="col-md-3">--}}
{{--                                        <div class="position-relative">--}}
{{--                                            <img src="{{ $media->getUrl() }}"--}}
{{--                                                 class="img-fluid rounded"--}}
{{--                                                 alt="{{ $product->name }}"--}}
{{--                                                 style="height: 120px; width: 100%; object-fit: cover;">--}}
{{--                                            <div class="badge bg-warning position-absolute top-0 start-0 m-1">Legacy</div>--}}
{{--                                        </div>--}}
{{--                                        <div class="form-check mt-2">--}}
{{--                                            <input class="form-check-input" type="checkbox"--}}
{{--                                                   name="remove_media_images[]" value="{{ $media->id }}"--}}
{{--                                                   id="remove_media_{{ $media->id }}">--}}
{{--                                            <label class="form-check-label text-danger" for="remove_media_{{ $media->id }}">--}}
{{--                                                <small>Remove</small>--}}
{{--                                            </label>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                @endforeach--}}
{{--                            </div>--}}
{{--                        @endif--}}

{{--                        <div class="mb-3">--}}
{{--                            <label for="images" class="form-label">Add More Gallery Images</label>--}}
{{--                            <input type="file" class="form-control @error('images.*') is-invalid @enderror"--}}
{{--                                   id="images" name="images[]" multiple accept="image/*">--}}
{{--                            <div class="form-text">Upload multiple images. Max 4MB each</div>--}}
{{--                            @error('images.*')--}}
{{--                            <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                            @enderror--}}
{{--                        </div>--}}

{{--                        <div id="imagePreview" class="row g-3" style="display: none;"></div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <!-- Product Variants -->--}}
{{--                <div class="stat-card p-4 mb-4">--}}
{{--                    <div class="d-flex justify-content-between align-items-center mb-3">--}}
{{--                        <h5 class="mb-0">Product Variants</h5>--}}
{{--                        <div class="form-check form-switch">--}}
{{--                            <input class="form-check-input" type="checkbox" id="has_variants" name="has_variants"--}}
{{--                                   value="1" {{ old('has_variants', $product->variants->count() > 0) ? 'checked' : '' }}>--}}
{{--                            <label class="form-check-label" for="has_variants">--}}
{{--                                Enable Variants--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div id="variantsSection" style="{{ old('has_variants', $product->variants->count() > 0) ? '' : 'display: none;' }}">--}}
{{--                        <div class="alert alert-info">--}}
{{--                            <small><i class="bi bi-info-circle me-1"></i>--}}
{{--                                Variants allow you to sell different versions of the same product (e.g., different sizes or colors).--}}
{{--                                Each variant will have its own SKU created by combining the base product SKU (<strong>{{ $product->sku }}</strong>) with the suffix you provide.--}}
{{--                            </small>--}}
{{--                        </div>--}}

{{--                        <div id="variantsContainer">--}}
{{--                            @forelse(old('variants', $product->variants->toArray()) as $index => $variant)--}}
{{--                                <div class="variant-item border rounded p-3 mb-3" data-variant-index="{{ $index }}">--}}
{{--                                    <div class="d-flex justify-content-between align-items-center mb-3">--}}
{{--                                        <h6 class="mb-0">Variant #{{ $index + 1 }}</h6>--}}
{{--                                        <button type="button" class="btn btn-sm btn-danger remove-variant">--}}
{{--                                            <i class="bi bi-trash"></i> Remove--}}
{{--                                        </button>--}}
{{--                                    </div>--}}

{{--                                    <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant['id'] ?? '' }}">--}}

{{--                                    <div class="row">--}}
{{--                                        <div class="col-md-6 mb-3">--}}
{{--                                            <label class="form-label">Size *</label>--}}
{{--                                            <input type="text" class="form-control" name="variants[{{ $index }}][size]"--}}
{{--                                                   value="{{ $variant['size'] ?? '' }}" required>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-md-6 mb-3">--}}
{{--                                            <label class="form-label">Color *</label>--}}
{{--                                            <input type="text" class="form-control" name="variants[{{ $index }}][color]"--}}
{{--                                                   value="{{ $variant['color'] ?? '' }}" required>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-md-6 mb-3">--}}
{{--                                            <label class="form-label">Color Image</label>--}}
{{--                                            @php $currentVariantImage = $variant['image'] ?? null; @endphp--}}
{{--                                            @if(!empty($currentVariantImage))--}}
{{--                                                <div class="d-flex align-items-center gap-3 mb-2">--}}
{{--                                                    <img src="{{ asset('storage/' . ltrim($currentVariantImage, '/')) }}" alt="Variant Image" style="height: 60px; width: 60px; object-fit: cover;" class="rounded border">--}}
{{--                                                    <small class="text-muted">Current</small>--}}
{{--                                                </div>--}}
{{--                                            @endif--}}
{{--                                            <input type="file" class="form-control" name="variants[{{ $index }}][image]" accept="image/*">--}}
{{--                                            <small class="text-muted">Upload to {{ !empty($currentVariantImage) ? 'replace' : 'set' }} image for this color</small>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-md-4 mb-3">--}}
{{--                                            <label class="form-label">--}}
{{--                                                SKU Suffix *--}}
{{--                                                <i class="bi bi-info-circle" data-bs-toggle="tooltip"--}}
{{--                                                   title="E.g., 'S-RED' will create SKU: {{ $product->sku }}-S-RED"></i>--}}
{{--                                            </label>--}}
{{--                                            @php--}}
{{--                                                // Extract suffix from full SKU by removing base product SKU--}}
{{--                                                $existingSku = $variant['sku'] ?? '';--}}
{{--                                                $baseSku = $product->sku . '-';--}}
{{--                                                $skuSuffix = str_starts_with($existingSku, $baseSku)--}}
{{--                                                    ? substr($existingSku, strlen($baseSku))--}}
{{--                                                    : ($variant['sku_suffix'] ?? '');--}}
{{--                                            @endphp--}}
{{--                                            <input type="text" class="form-control sku-suffix-input" name="variants[{{ $index }}][sku_suffix]"--}}
{{--                                                   value="{{ old('variants.'.$index.'.sku_suffix', $skuSuffix) }}" required--}}
{{--                                                   placeholder="e.g., S-RED"--}}
{{--                                                   data-base-sku="{{ $product->sku }}"--}}
{{--                                                   data-preview-target="sku-preview-{{ $index }}">--}}
{{--                                            <small class="text-muted">Full SKU: <span class="text-primary fw-bold" id="sku-preview-{{ $index }}">{{ $product->sku }}-{{ old('variants.'.$index.'.sku_suffix', $skuSuffix) ?: '[suffix]' }}</span></small>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-md-4 mb-3">--}}
{{--                                            <label class="form-label">Price (UGX) *</label>--}}
{{--                                            <input type="number" class="form-control" name="variants[{{ $index }}][price]"--}}
{{--                                                   value="{{ $variant['price'] ?? '' }}" step="0.01" min="0" required>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-md-4 mb-3">--}}
{{--                                            <label class="form-label">Stock Quantity *</label>--}}
{{--                                            <input type="number" class="form-control" name="variants[{{ $index }}][stock_quantity]"--}}
{{--                                                   value="{{ $variant['stock_quantity'] ?? 0 }}" min="0" required>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-12">--}}
{{--                                            <div class="form-check">--}}
{{--                                                <input class="form-check-input" type="checkbox"--}}
{{--                                                       name="variants[{{ $index }}][is_active]" value="1"--}}
{{--                                                       id="variant_active_{{ $index }}"--}}
{{--                                                    {{ ($variant['is_active'] ?? true) ? 'checked' : '' }}>--}}
{{--                                                <label class="form-check-label" for="variant_active_{{ $index }}">--}}
{{--                                                    Active--}}
{{--                                                </label>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            @empty--}}
{{--                            @endforelse--}}
{{--                        </div>--}}

{{--                        <button type="button" class="btn btn-outline-primary" id="addVariant">--}}
{{--                            <i class="bi bi-plus-circle me-1"></i> Add Variant--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <!-- SEO Settings -->--}}
{{--                <div class="stat-card p-4 mb-4">--}}
{{--                    <h5 class="mb-3">SEO Settings</h5>--}}

{{--                    <div class="mb-3">--}}
{{--                        <label for="meta_title" class="form-label">Meta Title</label>--}}
{{--                        <input type="text" class="form-control @error('meta_title') is-invalid @enderror"--}}
{{--                               id="meta_title" name="meta_title" value="{{ old('meta_title', $product->meta_title) }}" maxlength="255">--}}
{{--                        <div class="form-text">Leave empty to use product name</div>--}}
{{--                        @error('meta_title')--}}
{{--                        <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}

{{--                    <div class="mb-3">--}}
{{--                        <label for="meta_description" class="form-label">Meta Description</label>--}}
{{--                        <textarea class="form-control @error('meta_description') is-invalid @enderror"--}}
{{--                                  id="meta_description" name="meta_description" rows="3"--}}
{{--                                  maxlength="500">{{ old('meta_description', $product->meta_description) }}</textarea>--}}
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
{{--                               id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required>--}}
{{--                        @error('sku')--}}
{{--                        <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}

{{--                    <div class="mb-3">--}}
{{--                        <label for="categories" class="form-label">Categories *</label>--}}
{{--                        <select class="form-select @error('categories') is-invalid @enderror"--}}
{{--                                id="categories" name="categories[]" multiple required>--}}
{{--                            @foreach($categories as $category)--}}
{{--                                <option value="{{ $category->id }}"--}}
{{--                                    {{ in_array($category->id, old('categories', $product->categories->pluck('id')->toArray())) ? 'selected' : '' }}>--}}
{{--                                    {{ $category->name }}--}}
{{--                                </option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                        <div class="form-text">Hold Ctrl/Cmd to select multiple</div>--}}
{{--                        @error('categories')--}}
{{--                        <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}

{{--                    <div class="mb-3">--}}
{{--                        <label for="price" class="form-label">Regular Price *</label>--}}
{{--                        <div class="input-group">--}}
{{--                            <span class="input-group-text">UGX</span>--}}
{{--                            <input type="number" class="form-control @error('price') is-invalid @enderror"--}}
{{--                                   id="price" name="price" value="{{ old('price', $product->price) }}"--}}
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
{{--                                   id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}"--}}
{{--                                   step="0.01" min="0">--}}
{{--                        </div>--}}
{{--                        <div class="form-text">Must be less than regular price</div>--}}
{{--                        @error('sale_price')--}}
{{--                        <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <!-- Stock Management -->--}}
{{--                <div class="stat-card p-4 mb-4">--}}
{{--                    <h5 class="mb-3">Stock Management</h5>--}}

{{--                    <div class="mb-3">--}}
{{--                        <input type="hidden" name="manage_stock" value="0">--}}
{{--                        <div class="form-check">--}}
{{--                            <input class="form-check-input" type="checkbox" id="manage_stock" name="manage_stock"--}}
{{--                                   value="1" {{ old('manage_stock', $product->manage_stock) ? 'checked' : '' }}>--}}
{{--                            <label class="form-check-label" for="manage_stock">--}}
{{--                                Track stock quantity--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                        <small class="text-muted">Disable if variants are managing stock</small>--}}
{{--                    </div>--}}

{{--                    <div id="stockFields" style="{{ old('manage_stock', $product->manage_stock) ? '' : 'display: none;' }}">--}}
{{--                        <div class="mb-3">--}}
{{--                            <label for="stock_quantity" class="form-label">Stock Quantity</label>--}}
{{--                            <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror"--}}
{{--                                   id="stock_quantity" name="stock_quantity"--}}
{{--                                   value="{{ old('stock_quantity', $product->stock_quantity) }}" min="0">--}}
{{--                            @error('stock_quantity')--}}
{{--                            <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                            @enderror--}}
{{--                        </div>--}}

{{--                        <div class="mb-3">--}}
{{--                            <label for="min_stock_level" class="form-label">Low Stock Alert Level</label>--}}
{{--                            <input type="number" class="form-control @error('min_stock_level') is-invalid @enderror"--}}
{{--                                   id="min_stock_level" name="min_stock_level"--}}
{{--                                   value="{{ old('min_stock_level', $product->min_stock_level) }}" min="0">--}}
{{--                            @error('min_stock_level')--}}
{{--                            <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                            @enderror--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <!-- Product Settings -->--}}
{{--                <div class="stat-card p-4 mb-4">--}}
{{--                    <h5 class="mb-3">Product Settings</h5>--}}

{{--                    <div class="mb-3">--}}
{{--                        <label for="status" class="form-label">Status *</label>--}}
{{--                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>--}}
{{--                            @php $currentStatus = old('status', $product->status); @endphp--}}
{{--                            <option value="draft" {{ $currentStatus === 'draft' ? 'selected' : '' }}>Draft</option>--}}
{{--                            <option value="published" {{ $currentStatus === 'published' ? 'selected' : '' }}>Published</option>--}}
{{--                            <option value="inactive" {{ $currentStatus === 'inactive' ? 'selected' : '' }}>Inactive</option>--}}
{{--                        </select>--}}
{{--                        @error('status')--}}
{{--                        <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}

{{--                    <div class="mb-3">--}}
{{--                        <input type="hidden" name="is_active" value="0">--}}
{{--                        <div class="form-check">--}}
{{--                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active"--}}
{{--                                   value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>--}}
{{--                            <label class="form-check-label" for="is_active">--}}
{{--                                Active (visible to customers)--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div class="mb-3">--}}
{{--                        <input type="hidden" name="is_featured" value="0">--}}
{{--                        <div class="form-check">--}}
{{--                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"--}}
{{--                                   value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>--}}
{{--                            <label class="form-check-label" for="is_featured">--}}
{{--                                Featured product--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div class="mb-3">--}}
{{--                        <label for="weight" class="form-label">Weight (Kg)</label>--}}
{{--                        <input type="number" class="form-control @error('weight') is-invalid @enderror"--}}
{{--                               id="weight" name="weight" value="{{ old('weight', $product->weight) }}"--}}
{{--                               step="0.01" min="0">--}}
{{--                        @error('weight')--}}
{{--                        <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}

{{--                    <div class="mb-3">--}}
{{--                        <label for="dimensions" class="form-label">Dimensions (L x W x H)</label>--}}
{{--                        <input type="text" class="form-control @error('dimensions') is-invalid @enderror"--}}
{{--                               id="dimensions" name="dimensions" value="{{ old('dimensions', $product->dimensions) }}"--}}
{{--                               placeholder="e.g., 10 x 5 x 3 cm">--}}
{{--                        @error('dimensions')--}}
{{--                        <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <!-- Actions -->--}}
{{--                <div class="stat-card p-4">--}}
{{--                    <div class="d-grid gap-2">--}}
{{--                        <button type="submit" class="btn btn-primary">--}}
{{--                            <i class="bi bi-check-circle me-1"></i> Update Product--}}
{{--                        </button>--}}
{{--                        <a href="{{ route('admin.products.show', $product) }}" class="btn btn-outline-info">--}}
{{--                            <i class="bi bi-eye me-1"></i> Preview Product--}}
{{--                        </a>--}}
{{--                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">--}}
{{--                            <i class="bi bi-arrow-left me-1"></i> Cancel--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </form>--}}

{{--    <!-- Hidden input to track deleted variants -->--}}
{{--    <input type="hidden" name="delete_variants[]" id="deleteVariantsInput">--}}
{{--@endsection--}}

{{--@push('scripts')--}}
{{--    <script>--}}
{{--        let variantIndex = {{ count(old('variants', $product->variants->toArray())) }};--}}
{{--        const deletedVariants = [];--}}

{{--        // Stock management toggle--}}
{{--        document.getElementById('manage_stock').addEventListener('change', function() {--}}
{{--            const stockFields = document.getElementById('stockFields');--}}
{{--            stockFields.style.display = this.checked ? 'block' : 'none';--}}
{{--        });--}}

{{--        // Variants toggle--}}
{{--        document.getElementById('has_variants').addEventListener('change', function() {--}}
{{--            const variantsSection = document.getElementById('variantsSection');--}}
{{--            variantsSection.style.display = this.checked ? 'block' : 'none';--}}

{{--            if (this.checked && document.querySelectorAll('.variant-item').length === 0) {--}}
{{--                addVariant();--}}
{{--            }--}}
{{--        });--}}

{{--        // Add variant--}}
{{--        document.getElementById('addVariant').addEventListener('click', function() {--}}
{{--            addVariant();--}}
{{--        });--}}

{{--        function addVariant() {--}}
{{--            const container = document.getElementById('variantsContainer');--}}
{{--            const variantHtml = `--}}
{{--                <div class="variant-item border rounded p-3 mb-3" data-variant-index="${variantIndex}">--}}
{{--                    <div class="d-flex justify-content-between align-items-center mb-3">--}}
{{--                        <h6 class="mb-0">Variant #${variantIndex + 1}</h6>--}}
{{--                        <button type="button" class="btn btn-sm btn-danger remove-variant">--}}
{{--                            <i class="bi bi-trash"></i> Remove--}}
{{--                        </button>--}}
{{--                    </div>--}}

{{--                    <input type="hidden" name="variants[${variantIndex}][id]" value="">--}}

{{--                    <div class="row">--}}
{{--                        <div class="col-md-6 mb-3">--}}
{{--                            <label class="form-label">Size *</label>--}}
{{--                            <input type="text" class="form-control" name="variants[${variantIndex}][size]" required>--}}
{{--                        </div>--}}
{{--                        <div class="col-md-6 mb-3">--}}
{{--                            <label class="form-label">Color *</label>--}}
{{--                            <input type="text" class="form-control" name="variants[${variantIndex}][color]" required>--}}
{{--                        </div>--}}
{{--                        <div class="col-md-4 mb-3">--}}
{{--                            <label class="form-label">--}}
{{--                                SKU Suffix *--}}
{{--                                <i class="bi bi-info-circle" data-bs-toggle="tooltip" title="E.g., 'S-RED' will create SKU: {{ $product->sku }}-S-RED"></i>--}}
{{--                            </label>--}}
{{--                            <input type="text" class="form-control sku-suffix-input" name="variants[${variantIndex}][sku_suffix]" required placeholder="e.g., S-RED" data-base-sku="{{ $product->sku }}" data-preview-target="sku-preview-${variantIndex}">--}}
{{--                            <small class="text-muted">Full SKU: <span class="text-primary fw-bold" id="sku-preview-${variantIndex}">{{ $product->sku }}-[suffix]</span></small>--}}
{{--                        </div>--}}
{{--                        <div class="col-md-4 mb-3">--}}
{{--                            <label class="form-label">Price (UGX) *</label>--}}
{{--                            <input type="number" class="form-control" name="variants[${variantIndex}][price]"--}}
{{--                                   step="0.01" min="0" required>--}}
{{--                        </div>--}}
{{--                        <div class="col-md-4 mb-3">--}}
{{--                            <label class="form-label">Stock Quantity *</label>--}}
{{--                            <input type="number" class="form-control" name="variants[${variantIndex}][stock_quantity]"--}}
{{--                                   value="0" min="0" required>--}}
{{--                        </div>--}}
{{--                        <div class="col-12">--}}
{{--                            <div class="form-check">--}}
{{--                                <input class="form-check-input" type="checkbox"--}}
{{--                                       name="variants[${variantIndex}][is_active]" value="1"--}}
{{--                                       id="variant_active_${variantIndex}" checked>--}}
{{--                                <label class="form-check-label" for="variant_active_${variantIndex}">--}}
{{--                                    Active--}}
{{--                                </label>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            `;--}}

{{--            container.insertAdjacentHTML('beforeend', variantHtml);--}}
{{--            variantIndex++;--}}

{{--            // Reattach remove listeners--}}
{{--            attachRemoveListeners();--}}
{{--        }--}}

{{--        // Remove variant--}}
{{--        function attachRemoveListeners() {--}}
{{--            document.querySelectorAll('.remove-variant').forEach(button => {--}}
{{--                button.removeEventListener('click', handleRemoveVariant);--}}
{{--                button.addEventListener('click', handleRemoveVariant);--}}
{{--            });--}}
{{--        }--}}

{{--        function handleRemoveVariant(e) {--}}
{{--            const variantItem = e.target.closest('.variant-item');--}}
{{--            const variantId = variantItem.querySelector('input[name*="[id]"]').value;--}}

{{--            if (variantId) {--}}
{{--                // Track for deletion if it's an existing variant--}}
{{--                deletedVariants.push(variantId);--}}
{{--                updateDeletedVariantsInput();--}}
{{--            }--}}

{{--            variantItem.remove();--}}

{{--            // Renumber remaining variants--}}
{{--            document.querySelectorAll('.variant-item').forEach((item, index) => {--}}
{{--                item.querySelector('h6').textContent = `Variant #${index + 1}`;--}}
{{--            });--}}
{{--        }--}}

{{--        function updateDeletedVariantsInput() {--}}
{{--            // Remove existing delete inputs--}}
{{--            document.querySelectorAll('input[name="delete_variants[]"]').forEach(input => {--}}
{{--                if (input.id !== 'deleteVariantsInput') {--}}
{{--                    input.remove();--}}
{{--                }--}}
{{--            });--}}

{{--            // Add hidden inputs for each deleted variant--}}
{{--            const form = document.getElementById('productForm');--}}
{{--            deletedVariants.forEach(id => {--}}
{{--                const input = document.createElement('input');--}}
{{--                input.type = 'hidden';--}}
{{--                input.name = 'delete_variants[]';--}}
{{--                input.value = id;--}}
{{--                form.appendChild(input);--}}
{{--            });--}}
{{--        }--}}

{{--        // Initialize remove listeners on page load--}}
{{--        attachRemoveListeners();--}}

{{--        // Image preview functionality--}}
{{--        document.getElementById('images').addEventListener('change', function(e) {--}}
{{--            const preview = document.getElementById('imagePreview');--}}
{{--            preview.innerHTML = '';--}}

{{--            if (e.target.files.length > 0) {--}}
{{--                preview.style.display = 'flex';--}}

{{--                Array.from(e.target.files).forEach((file, index) => {--}}
{{--                    if (file.type.startsWith('image/')) {--}}
{{--                        const reader = new FileReader();--}}
{{--                        reader.onload = function(e) {--}}
{{--                            const col = document.createElement('div');--}}
{{--                            col.className = 'col-md-3';--}}
{{--                            col.innerHTML = `--}}
{{--                                <div class="position-relative">--}}
{{--                                    <img src="${e.target.result}" class="img-fluid rounded"--}}
{{--                                         style="height: 120px; width: 100%; object-fit: cover;">--}}
{{--                                    <div class="badge bg-success position-absolute top-0 start-0 m-1">New</div>--}}
{{--                                </div>--}}
{{--                                <div class="text-center mt-2">--}}
{{--                                    <small class="text-muted">${file.name}</small>--}}
{{--                                </div>--}}
{{--                            `;--}}
{{--                            preview.appendChild(col);--}}
{{--                        };--}}
{{--                        reader.readAsDataURL(file);--}}
{{--                    }--}}
{{--                });--}}
{{--            } else {--}}
{{--                preview.style.display = 'none';--}}
{{--            }--}}
{{--        });--}}

{{--        // Featured image preview--}}
{{--        document.getElementById('featured_image').addEventListener('change', function(e) {--}}
{{--            const preview = document.getElementById('featuredImagePreview');--}}
{{--            const previewImg = document.getElementById('featuredPreviewImg');--}}
{{--            const fileName = document.getElementById('featuredFileName');--}}

{{--            if (e.target.files.length > 0) {--}}
{{--                const file = e.target.files[0];--}}
{{--                if (file.type.startsWith('image/')) {--}}
{{--                    const reader = new FileReader();--}}
{{--                    reader.onload = function(event) {--}}
{{--                        previewImg.src = event.target.result;--}}
{{--                        fileName.textContent = file.name;--}}
{{--                        preview.style.display = 'block';--}}
{{--                    };--}}
{{--                    reader.readAsDataURL(file);--}}
{{--                } else {--}}
{{--                    preview.style.display = 'none';--}}
{{--                }--}}
{{--            } else {--}}
{{--                preview.style.display = 'none';--}}
{{--            }--}}
{{--        });--}}

{{--        // Auto-generate slug from name (only if slug is empty)--}}
{{--        const slugInput = document.getElementById('slug');--}}
{{--        const nameInput = document.getElementById('name');--}}

{{--        nameInput.addEventListener('input', function() {--}}
{{--            if (slugInput.value === '') {--}}
{{--                const slug = this.value.toLowerCase()--}}
{{--                    .replace(/[^a-z0-9 -]/g, '')--}}
{{--                    .replace(/\s+/g, '-')--}}
{{--                    .replace(/-+/g, '-')--}}
{{--                    .trim();--}}
{{--                slugInput.value = slug;--}}
{{--            }--}}
{{--        });--}}

{{--        // Form validation--}}
{{--        document.getElementById('productForm').addEventListener('submit', function(e) {--}}
{{--            const hasVariants = document.getElementById('has_variants').checked;--}}
{{--            const variantItems = document.querySelectorAll('.variant-item');--}}

{{--            if (hasVariants && variantItems.length === 0) {--}}
{{--                e.preventDefault();--}}
{{--                alert('Please add at least one variant or disable variants.');--}}
{{--                return false;--}}
{{--            }--}}

{{--            // Validate sale price is less than regular price--}}
{{--            const price = parseFloat(document.getElementById('price').value);--}}
{{--            const salePrice = parseFloat(document.getElementById('sale_price').value);--}}

{{--            if (salePrice && salePrice >= price) {--}}
{{--                e.preventDefault();--}}
{{--                alert('Sale price must be less than regular price.');--}}
{{--                document.getElementById('sale_price').focus();--}}
{{--                return false;--}}
{{--            }--}}

{{--            return true;--}}
{{--        });--}}

{{--        // Warn about unsaved changes--}}
{{--        let formChanged = false;--}}
{{--        const form = document.getElementById('productForm');--}}

{{--        form.addEventListener('change', function() {--}}
{{--            formChanged = true;--}}
{{--        });--}}

{{--        window.addEventListener('beforeunload', function(e) {--}}
{{--            if (formChanged) {--}}
{{--                e.preventDefault();--}}
{{--                e.returnValue = '';--}}
{{--            }--}}
{{--        });--}}

{{--        form.addEventListener('submit', function() {--}}
{{--            formChanged = false;--}}
{{--        });--}}

{{--        // Initialize Bootstrap tooltips--}}
{{--        document.addEventListener('DOMContentLoaded', function() {--}}
{{--            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));--}}
{{--            tooltipTriggerList.map(function (tooltipTriggerEl) {--}}
{{--                return new bootstrap.Tooltip(tooltipTriggerEl);--}}
{{--            });--}}
{{--        });--}}

{{--        // Re-initialize tooltips when new variants are added--}}
{{--        const originalAddVariant = addVariant;--}}
{{--        function addVariant() {--}}
{{--            originalAddVariant();--}}
{{--            // Re-initialize tooltips for new elements--}}
{{--            const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');--}}
{{--            tooltips.forEach(el => {--}}
{{--                if (!el._tooltip) {--}}
{{--                    new bootstrap.Tooltip(el);--}}
{{--                }--}}
{{--            });--}}
{{--            // Attach SKU preview listeners to new inputs--}}
{{--            attachSkuPreviewListeners();--}}
{{--        }--}}

{{--        // SKU suffix live preview--}}
{{--        function attachSkuPreviewListeners() {--}}
{{--            document.querySelectorAll('.sku-suffix-input').forEach(input => {--}}
{{--                if (!input.dataset.listenerAttached) {--}}
{{--                    input.addEventListener('input', function() {--}}
{{--                        const baseSku = this.dataset.baseSku;--}}
{{--                        const previewId = this.dataset.previewTarget;--}}
{{--                        const preview = document.getElementById(previewId);--}}
{{--                        if (preview) {--}}
{{--                            const suffix = this.value.trim();--}}
{{--                            preview.textContent = suffix ? `${baseSku}-${suffix}` : `${baseSku}-[suffix]`;--}}
{{--                        }--}}
{{--                    });--}}
{{--                    input.dataset.listenerAttached = 'true';--}}
{{--                }--}}
{{--            });--}}
{{--        }--}}

{{--        // Initialize on page load--}}
{{--        attachSkuPreviewListeners();--}}
{{--    </script>--}}
{{--@endpush--}}


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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" id="productForm">
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
                        <div class="form-text">Leave empty to auto-generate from product name</div>
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

                    <!-- Featured Image -->
                    @php
                        $featuredImageUrl = $product->featured_image ? $product->getStorageImageUrl($product->featured_image) : null;
                    @endphp

                    <div class="mb-4">
                        <h6>Featured Image</h6>
                        @if($featuredImageUrl)
                            <div class="row g-3 mb-3" id="currentFeaturedImage">
                                <div class="col-md-4">
                                    <div class="position-relative">
                                        <img src="{{ $featuredImageUrl }}"
                                             class="img-fluid rounded"
                                             alt="{{ $product->name }}"
                                             style="height: 200px; width: 100%; object-fit: cover;">
                                        <div class="badge bg-primary position-absolute top-0 start-0 m-2">Featured</div>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox"
                                               name="remove_featured_image" value="1"
                                               id="remove_featured">
                                        <label class="form-check-label text-danger" for="remove_featured">
                                            <small>Remove featured image</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p class="text-muted" id="noFeaturedImageText">No featured image set</p>
                        @endif

                        <div class="mb-3">
                            <label for="featured_image" class="form-label">
                                {{ $featuredImageUrl ? 'Replace' : 'Upload' }} Featured Image
                            </label>
                            <input type="file" class="form-control @error('featured_image') is-invalid @enderror"
                                   id="featured_image" name="featured_image" accept="image/*">
                            <div class="form-text">Recommended: 800x800px. Max 4MB</div>
                            @error('featured_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- New featured image preview -->
                        <div id="featuredImagePreview" class="row g-3 mb-3" style="display: none;">
                            <div class="col-md-4">
                                <div class="position-relative">
                                    <img src="" id="featuredPreviewImg" class="img-fluid rounded"
                                         style="height: 200px; width: 100%; object-fit: cover;">
                                    <div class="badge bg-success position-absolute top-0 start-0 m-2">New</div>
                                </div>
                                <div class="text-center mt-2">
                                    <small class="text-muted" id="featuredFileName"></small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gallery Images -->
                    @php
                        $galleryImages = $product->gallery ?? [];
                        $hasGalleryImages = count($galleryImages) > 0;
                    @endphp

                    <div class="mb-4">
                        <h6>Gallery Images</h6>
                        @if($hasGalleryImages)
                            <div class="row g-3 mb-3">
                                @foreach($galleryImages as $index => $imagePath)
                                    <div class="col-md-3">
                                        <div class="position-relative">
                                            <img src="{{ $product->getStorageImageUrl($imagePath) }}"
                                                 class="img-fluid rounded"
                                                 alt="{{ $product->name }}"
                                                 style="height: 120px; width: 100%; object-fit: cover;">
                                            <div class="badge bg-info position-absolute top-0 end-0 m-1">{{ $index + 1 }}</div>
                                        </div>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox"
                                                   name="remove_gallery_images[]" value="{{ $index }}"
                                                   id="remove_gallery_{{ $index }}">
                                            <label class="form-check-label text-danger" for="remove_gallery_{{ $index }}">
                                                <small>Remove</small>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Check for legacy Spatie media -->
                        @if($product->getMedia('images')->count() > 0)
                            <div class="alert alert-info mb-3">
                                <small><i class="bi bi-info-circle me-1"></i> Legacy images detected</small>
                            </div>
                            <div class="row g-3 mb-3">
                                @foreach($product->getMedia('images') as $media)
                                    <div class="col-md-3">
                                        <div class="position-relative">
                                            <img src="{{ $media->getUrl() }}"
                                                 class="img-fluid rounded"
                                                 alt="{{ $product->name }}"
                                                 style="height: 120px; width: 100%; object-fit: cover;">
                                            <div class="badge bg-warning position-absolute top-0 start-0 m-1">Legacy</div>
                                        </div>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox"
                                                   name="remove_media_images[]" value="{{ $media->id }}"
                                                   id="remove_media_{{ $media->id }}">
                                            <label class="form-check-label text-danger" for="remove_media_{{ $media->id }}">
                                                <small>Remove</small>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="images" class="form-label">Add More Gallery Images</label>
                            <input type="file" class="form-control @error('images.*') is-invalid @enderror"
                                   id="images" name="images[]" multiple accept="image/*">
                            <div class="form-text">Upload multiple images. Max 4MB each</div>
                            @error('images.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="imagePreview" class="row g-3" style="display: none;"></div>
                    </div>
                </div>

                <!-- Product Variants -->
                <div class="stat-card p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Product Variants</h5>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="has_variants" name="has_variants"
                                   value="1" {{ old('has_variants', $product->variants->count() > 0) ? 'checked' : '' }}>
                            <label class="form-check-label" for="has_variants">
                                Enable Variants
                            </label>
                        </div>
                    </div>

                    <div id="variantsSection" style="{{ old('has_variants', $product->variants->count() > 0) ? '' : 'display: none;' }}">
                        <div class="alert alert-info">
                            <small><i class="bi bi-info-circle me-1"></i>
                                Variants allow you to sell different versions of the same product (e.g., different sizes or colors).
                                Each variant will have its own SKU created by combining the base product SKU (<strong>{{ $product->sku }}</strong>) with the suffix you provide.
                            </small>
                        </div>

                        <!-- Quick Variant Generator -->
                        <div class="border rounded p-3 bg-light mb-4">
                            <h6 class="mb-3"><i class="bi bi-magic me-1"></i> Quick Variant Generator</h6>

                            <!-- Sizes Input -->
                            <div class="mb-3">
                                <label for="available_sizes" class="form-label fw-semibold">Sizes (comma-separated)</label>
                                <input type="text" class="form-control" id="available_sizes"
                                       placeholder="XS,S,M,L,XL,XXL">
                                <div class="form-text text-muted">Enter sizes separated by commas</div>
                            </div>

                            <!-- Colors Selection -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Available Colors</label>
                                <div class="mb-2">
                                    <input type="text" class="form-control" id="colorSearch" placeholder="Search colors...">
                                </div>
                                @php
                                    $palette = \App\Support\ColorPalette::all();
                                @endphp
                                <div class="border rounded p-3 bg-white" style="max-height: 180px; overflow-y: auto;">
                                    <div class="row g-2" id="colorOptions">
                                        @foreach($palette as $colorName => $hex)
                                            @php
                                                $id = 'gen_color_' . str_replace([' ', '#'], ['_', ''], strtolower($colorName));
                                            @endphp
                                            <div class="col-6 col-md-4 col-lg-3 color-option" data-color-name="{{ strtolower($colorName) }}">
                                                <div class="form-check">
                                                    <input class="form-check-input gen-color-checkbox" type="checkbox"
                                                           id="{{ $id }}" value="{{ $colorName }}">
                                                    <label class="form-check-label d-flex align-items-center gap-2" for="{{ $id }}">
                                                        <span class="rounded-circle border shadow-sm"
                                                              style="display:inline-block;width:18px;height:18px;background-color: {{ $hex }};"></span>
                                                        <span class="text-capitalize small">{{ $colorName }}</span>
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="button" class="btn btn-primary" id="generateVariants">
                                    <i class="bi bi-plus-circle me-2"></i> Generate Variants
                                </button>
                                <div class="form-text text-muted mt-2">This will add new variants to existing ones</div>
                            </div>
                        </div>

                        <!-- Existing Variants -->
                        <h6 class="mb-3">Current Variants</h6>
                        <div id="variantsContainer">
                            @forelse(old('variants', $product->variants->toArray()) as $index => $variant)
                                <div class="variant-item border rounded p-3 mb-3" data-variant-index="{{ $index }}">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0">Variant #{{ $index + 1 }}</h6>
                                        <button type="button" class="btn btn-sm btn-danger remove-variant">
                                            <i class="bi bi-trash"></i> Remove
                                        </button>
                                    </div>

                                    <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant['id'] ?? '' }}">

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Size *</label>
                                            <input type="text" class="form-control" name="variants[{{ $index }}][size]"
                                                   value="{{ $variant['size'] ?? '' }}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Color *</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="variants[{{ $index }}][color]"
                                                       value="{{ $variant['color'] ?? '' }}" required>
                                                @php
                                                    $variantColor = $variant['color'] ?? '';
                                                    $colorHex = \App\Support\ColorPalette::resolveHex($variantColor);
                                                @endphp
                                                <span class="input-group-text" style="background-color: {{ $colorHex }}; width: 40px;"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Color Image</label>
                                            @php $currentVariantImage = $variant['image'] ?? null; @endphp
                                            @if(!empty($currentVariantImage))
                                                <div class="d-flex align-items-center gap-2 mb-2">
                                                    <img src="{{ asset('storage/' . ltrim($currentVariantImage, '/')) }}" alt="Variant Image" style="height: 40px; width: 40px; object-fit: cover;" class="rounded border">
                                                    <small class="text-muted">Current</small>
                                                </div>
                                            @endif
                                            <input type="file" class="form-control form-control-sm" name="variants[{{ $index }}][image]" accept="image/*">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">
                                                SKU Suffix *
                                                <i class="bi bi-info-circle" data-bs-toggle="tooltip"
                                                   title="E.g., 'S-RED' will create SKU: {{ $product->sku }}-S-RED"></i>
                                            </label>
                                            @php
                                                // Extract suffix from full SKU by removing base product SKU
                                                $existingSku = $variant['sku'] ?? '';
                                                $baseSku = $product->sku . '-';
                                                $skuSuffix = str_starts_with($existingSku, $baseSku)
                                                    ? substr($existingSku, strlen($baseSku))
                                                    : ($variant['sku_suffix'] ?? '');
                                            @endphp
                                            <input type="text" class="form-control sku-suffix-input" name="variants[{{ $index }}][sku_suffix]"
                                                   value="{{ old('variants.'.$index.'.sku_suffix', $skuSuffix) }}" required
                                                   placeholder="e.g., S-RED"
                                                   data-base-sku="{{ $product->sku }}"
                                                   data-preview-target="sku-preview-{{ $index }}">
                                            <small class="text-muted">SKU: <span class="text-primary fw-bold" id="sku-preview-{{ $index }}">{{ $product->sku }}-{{ old('variants.'.$index.'.sku_suffix', $skuSuffix) ?: '[suffix]' }}</span></small>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Price (UGX) *</label>
                                            <input type="number" class="form-control" name="variants[{{ $index }}][price]"
                                                   value="{{ $variant['price'] ?? '' }}" step="0.01" min="0" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Stock *</label>
                                            <input type="number" class="form-control" name="variants[{{ $index }}][stock_quantity]"
                                                   value="{{ $variant['stock_quantity'] ?? 0 }}" min="0" required>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                       name="variants[{{ $index }}][is_active]" value="1"
                                                       id="variant_active_{{ $index }}"
                                                    {{ ($variant['is_active'] ?? true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="variant_active_{{ $index }}">
                                                    Active
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted" id="noVariantsText">No variants yet. Use the generator above or add manually below.</p>
                            @endforelse
                        </div>

                        <button type="button" class="btn btn-outline-primary" id="addVariant">
                            <i class="bi bi-plus-circle me-1"></i> Add Single Variant
                        </button>
                    </div>
                </div>

                <!-- SEO Settings -->
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">SEO Settings</h5>

                    <div class="mb-3">
                        <label for="meta_title" class="form-label">Meta Title</label>
                        <input type="text" class="form-control @error('meta_title') is-invalid @enderror"
                               id="meta_title" name="meta_title" value="{{ old('meta_title', $product->meta_title) }}" maxlength="255">
                        <div class="form-text">Leave empty to use product name</div>
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
                        <div class="form-text">Hold Ctrl/Cmd to select multiple</div>
                        @error('categories')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Regular Price *</label>
                        <div class="input-group">
                            <span class="input-group-text">UGX</span>
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
                            <span class="input-group-text">UGX</span>
                            <input type="number" class="form-control @error('sale_price') is-invalid @enderror"
                                   id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}"
                                   step="0.01" min="0">
                        </div>
                        <div class="form-text">Must be less than regular price</div>
                        @error('sale_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Stock Management -->
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Stock Management</h5>

                    <div class="mb-3">
                        <input type="hidden" name="manage_stock" value="0">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="manage_stock" name="manage_stock"
                                   value="1" {{ old('manage_stock', $product->manage_stock) ? 'checked' : '' }}>
                            <label class="form-check-label" for="manage_stock">
                                Track stock quantity
                            </label>
                        </div>
                        <small class="text-muted">Disable if variants are managing stock</small>
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
                        <label for="status" class="form-label">Status *</label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            @php $currentStatus = old('status', $product->status); @endphp
                            <option value="draft" {{ $currentStatus === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ $currentStatus === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="inactive" {{ $currentStatus === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <input type="hidden" name="is_active" value="0">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                   value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active (visible to customers)
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <input type="hidden" name="is_featured" value="0">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"
                                   value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                Featured product
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="weight" class="form-label">Weight (Kg)</label>
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
                               placeholder="e.g., 10 x 5 x 3 cm">
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

    <!-- Hidden input to track deleted variants -->
    <input type="hidden" name="delete_variants[]" id="deleteVariantsInput">
@endsection

@push('scripts')
    <script>
        let variantIndex = {{ count(old('variants', $product->variants->toArray())) }};
        const deletedVariants = [];

        // Stock management toggle
        document.getElementById('manage_stock').addEventListener('change', function() {
            const stockFields = document.getElementById('stockFields');
            stockFields.style.display = this.checked ? 'block' : 'none';
        });

        // Variants toggle
        document.getElementById('has_variants').addEventListener('change', function() {
            const variantsSection = document.getElementById('variantsSection');
            variantsSection.style.display = this.checked ? 'block' : 'none';

            if (this.checked && document.querySelectorAll('.variant-item').length === 0) {
                addVariant();
            }
        });

        // Add variant
        document.getElementById('addVariant').addEventListener('click', function() {
            addVariant();
        });

        function addVariant(size = '', color = '', price = '') {
            const container = document.getElementById('variantsContainer');
            const noVariantsText = document.getElementById('noVariantsText');
            if (noVariantsText) noVariantsText.remove();

            const basePrice = price || document.getElementById('price').value || 0;
            const skuSuffix = size && color ? `${size}-${color.toUpperCase()}` : '';

            const variantHtml = `
                <div class="variant-item border rounded p-3 mb-3" data-variant-index="${variantIndex}">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Variant #${document.querySelectorAll('.variant-item').length + 1}</h6>
                        <button type="button" class="btn btn-sm btn-danger remove-variant">
                            <i class="bi bi-trash"></i> Remove
                        </button>
                    </div>

                    <input type="hidden" name="variants[${variantIndex}][id]" value="">

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Size *</label>
                            <input type="text" class="form-control" name="variants[${variantIndex}][size]" value="${size}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Color *</label>
                            <input type="text" class="form-control" name="variants[${variantIndex}][color]" value="${color}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" class="form-control form-control-sm" name="variants[${variantIndex}][image]" accept="image/*">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                SKU Suffix *
                                <i class="bi bi-info-circle" data-bs-toggle="tooltip" title="E.g., 'S-RED' will create SKU: {{ $product->sku }}-S-RED"></i>
                            </label>
                            <input type="text" class="form-control sku-suffix-input" name="variants[${variantIndex}][sku_suffix]" value="${skuSuffix}" required placeholder="e.g., S-RED" data-base-sku="{{ $product->sku }}" data-preview-target="sku-preview-${variantIndex}">
                            <small class="text-muted">SKU: <span class="text-primary fw-bold" id="sku-preview-${variantIndex}">{{ $product->sku }}-${skuSuffix || '[suffix]'}</span></small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Price (UGX) *</label>
                            <input type="number" class="form-control" name="variants[${variantIndex}][price]"
                                   value="${basePrice}" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Stock *</label>
                            <input type="number" class="form-control" name="variants[${variantIndex}][stock_quantity]"
                                   value="0" min="0" required>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                       name="variants[${variantIndex}][is_active]" value="1"
                                       id="variant_active_${variantIndex}" checked>
                                <label class="form-check-label" for="variant_active_${variantIndex}">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', variantHtml);
            variantIndex++;

            // Reattach remove listeners
            attachRemoveListeners();
            attachSkuPreviewListeners();
        }

        // Variant Generator
        document.getElementById('generateVariants')?.addEventListener('click', function() {
            const sizesInput = document.getElementById('available_sizes').value.trim();
            const colorCheckboxes = document.querySelectorAll('.gen-color-checkbox:checked');
            const basePrice = document.getElementById('price').value || 0;

            if (!sizesInput || colorCheckboxes.length === 0) {
                alert('Please enter sizes and select at least one color');
                return;
            }

            const sizes = sizesInput.split(',').map(s => s.trim()).filter(s => s);
            const colors = Array.from(colorCheckboxes).map(cb => cb.value);

            if (sizes.length === 0) {
                alert('Please enter valid sizes');
                return;
            }

            // Generate combinations
            let addedCount = 0;
            sizes.forEach(size => {
                colors.forEach(color => {
                    addVariant(size, color, basePrice);
                    addedCount++;
                });
            });

            // Clear inputs after generation
            document.getElementById('available_sizes').value = '';
            colorCheckboxes.forEach(cb => cb.checked = false);

            alert(`Generated ${addedCount} variant combinations!`);
        });

        // Color search in variant generator
        document.getElementById('colorSearch')?.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase().trim();
            document.querySelectorAll('.color-option').forEach(option => {
                const colorName = option.getAttribute('data-color-name');
                option.style.display = colorName.includes(searchTerm) ? 'block' : 'none';
            });
        });

        // Remove variant
        function attachRemoveListeners() {
            document.querySelectorAll('.remove-variant').forEach(button => {
                button.removeEventListener('click', handleRemoveVariant);
                button.addEventListener('click', handleRemoveVariant);
            });
        }

        function handleRemoveVariant(e) {
            const variantItem = e.target.closest('.variant-item');
            const variantId = variantItem.querySelector('input[name*="[id]"]').value;

            if (variantId) {
                // Track for deletion if it's an existing variant
                deletedVariants.push(variantId);
                updateDeletedVariantsInput();
            }

            variantItem.remove();

            // Renumber remaining variants
            document.querySelectorAll('.variant-item').forEach((item, index) => {
                item.querySelector('h6').textContent = `Variant #${index + 1}`;
            });
        }

        function updateDeletedVariantsInput() {
            // Remove existing delete inputs
            document.querySelectorAll('input[name="delete_variants[]"]').forEach(input => {
                if (input.id !== 'deleteVariantsInput') {
                    input.remove();
                }
            });

            // Add hidden inputs for each deleted variant
            const form = document.getElementById('productForm');
            deletedVariants.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete_variants[]';
                input.value = id;
                form.appendChild(input);
            });
        }

        // Initialize remove listeners on page load
        attachRemoveListeners();

        // Image preview functionality
        document.getElementById('images').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';

            if (e.target.files.length > 0) {
                preview.style.display = 'flex';

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
                                    <div class="badge bg-success position-absolute top-0 start-0 m-1">New</div>
                                </div>
                                <div class="text-center mt-2">
                                    <small class="text-muted">${file.name}</small>
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

        // Featured image preview
        document.getElementById('featured_image').addEventListener('change', function(e) {
            const preview = document.getElementById('featuredImagePreview');
            const previewImg = document.getElementById('featuredPreviewImg');
            const fileName = document.getElementById('featuredFileName');

            if (e.target.files.length > 0) {
                const file = e.target.files[0];
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        previewImg.src = event.target.result;
                        fileName.textContent = file.name;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    preview.style.display = 'none';
                }
            } else {
                preview.style.display = 'none';
            }
        });

        // Auto-generate slug from name (only if slug is empty)
        const slugInput = document.getElementById('slug');
        const nameInput = document.getElementById('name');

        nameInput.addEventListener('input', function() {
            if (slugInput.value === '') {
                const slug = this.value.toLowerCase()
                    .replace(/[^a-z0-9 -]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .trim();
                slugInput.value = slug;
            }
        });

        // Form validation
        document.getElementById('productForm').addEventListener('submit', function(e) {
            const hasVariants = document.getElementById('has_variants').checked;
            const variantItems = document.querySelectorAll('.variant-item');

            if (hasVariants && variantItems.length === 0) {
                e.preventDefault();
                alert('Please add at least one variant or disable variants.');
                return false;
            }

            // Validate sale price is less than regular price
            const price = parseFloat(document.getElementById('price').value);
            const salePrice = parseFloat(document.getElementById('sale_price').value);

            if (salePrice && salePrice >= price) {
                e.preventDefault();
                alert('Sale price must be less than regular price.');
                document.getElementById('sale_price').focus();
                return false;
            }

            return true;
        });

        // Warn about unsaved changes
        let formChanged = false;
        const form = document.getElementById('productForm');

        form.addEventListener('change', function() {
            formChanged = true;
        });

        window.addEventListener('beforeunload', function(e) {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        form.addEventListener('submit', function() {
            formChanged = false;
        });

        // Initialize Bootstrap tooltips
        document.addEventListener('DOMContentLoaded', function() {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        // Re-initialize tooltips when new variants are added
        const originalAddVariant = addVariant;
        function addVariant() {
            originalAddVariant();
            // Re-initialize tooltips for new elements
            const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltips.forEach(el => {
                if (!el._tooltip) {
                    new bootstrap.Tooltip(el);
                }
            });
            // Attach SKU preview listeners to new inputs
            attachSkuPreviewListeners();
        }

        // SKU suffix live preview
        function attachSkuPreviewListeners() {
            document.querySelectorAll('.sku-suffix-input').forEach(input => {
                if (!input.dataset.listenerAttached) {
                    input.addEventListener('input', function() {
                        const baseSku = this.dataset.baseSku;
                        const previewId = this.dataset.previewTarget;
                        const preview = document.getElementById(previewId);
                        if (preview) {
                            const suffix = this.value.trim();
                            preview.textContent = suffix ? `${baseSku}-${suffix}` : `${baseSku}-[suffix]`;
                        }
                    });
                    input.dataset.listenerAttached = 'true';
                }
            });
        }

        // Initialize on page load
        attachSkuPreviewListeners();
    </script>
@endpush
