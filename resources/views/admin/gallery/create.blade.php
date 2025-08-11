@extends('admin.layouts.app')

@section('title', 'Add Gallery Item')
@section('page-title', 'Add Gallery Item')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0">Add New Gallery Item</h2>
            <p class="text-muted mb-0">Create a new gallery item for your website</p>
        </div>
        <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Gallery
        </a>
    </div>

    <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data" id="galleryForm">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Gallery Item Details</h5>

                    <!-- Source Type Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-medium">Image Source <span class="text-danger">*</span></label>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="source_type"
                                           id="sourceUpload" value="upload" checked>
                                    <label class="form-check-label" for="sourceUpload">
                                        <i class="bi bi-upload me-1"></i> Upload Image
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="source_type"
                                           id="sourceInstagram" value="instagram">
                                    <label class="form-check-label" for="sourceInstagram">
                                        <i class="bi bi-instagram me-1"></i> Instagram URL
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="source_type"
                                           id="sourceCustomer" value="customer">
                                    <label class="form-check-label" for="sourceCustomer">
                                        <i class="bi bi-person-heart me-1"></i> Customer Photo
                                    </label>
                                </div>
                            </div>
                        </div>
                        @error('source_type')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Image Upload -->
                    <div class="mb-4" id="imageUploadDiv">
                        <label for="image" class="form-label fw-medium">Upload Image <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                               id="image" name="image" accept="image/*">
                        <div class="form-text">
                            Upload a high-quality image (recommended size: 1200x800px)<br>
                            Supported formats: JPEG, PNG, JPG, GIF, WebP. Max size: 2MB
                        </div>
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <!-- Image Preview -->
                        <div id="imagePreview" class="mt-3" style="display: none;">
                            <img id="previewImg" src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                        </div>
                    </div>

                    <!-- Image URL -->
                    <div class="mb-4" id="imageUrlDiv" style="display: none;">
                        <label for="image_url" class="form-label fw-medium">Image URL <span class="text-danger">*</span></label>
                        <input type="url" class="form-control @error('image_url') is-invalid @enderror"
                               id="image_url" name="image_url" placeholder="https://example.com/image.jpg">
                        <div class="form-text">Enter the full URL to the image (must be publicly accessible)</div>
                        @error('image_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Caption -->
                    <div class="mb-4">
                        <label for="caption" class="form-label fw-medium">Caption</label>
                        <input type="text" class="form-control @error('caption') is-invalid @enderror"
                               id="caption" name="caption" value="{{ old('caption') }}"
                               placeholder="Add a caption for this image">
                        <div class="form-text">A short description that will appear with the gallery item</div>
                        @error('caption')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Hashtags -->
                    <div class="mb-4">
                        <label for="hashtags" class="form-label fw-medium">Hashtags</label>
                        <input type="text" class="form-control @error('hashtags') is-invalid @enderror"
                               id="hashtags" name="hashtags" value="{{ old('hashtags') }}"
                               placeholder="shopwithcarl, activewear, fitness">
                        <div class="form-text">Separate multiple hashtags with commas. Don't include the # symbol.</div>
                        @error('hashtags')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- External Link -->
                    <div class="mb-4">
                        <label for="link" class="form-label fw-medium">External Link</label>
                        <input type="url" class="form-control @error('link') is-invalid @enderror"
                               id="link" name="link" value="{{ old('link') }}"
                               placeholder="https://example.com">
                        <div class="form-text">Optional link that opens when users click the gallery item</div>
                        @error('link')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Product Association -->
                    <div class="mb-4">
                        <label for="product_id" class="form-label fw-medium">Associated Product</label>
                        <select class="form-select @error('product_id') is-invalid @enderror"
                                id="product_id" name="product_id">
                            <option value="">No associated product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Link this gallery item to a specific product in your catalog</div>
                        @error('product_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                            <input class="form-check-input" type="checkbox" id="is_active"
                                   name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
                        <div class="form-text">Only active items will be displayed on the website</div>
                    </div>

                    <!-- Featured -->
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_featured"
                                   name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                Featured
                            </label>
                        </div>
                        <div class="form-text">Featured items may be displayed in special sections</div>
                    </div>

                    <!-- Sort Order -->
                    <div class="mb-4">
                        <label for="sort_order" class="form-label fw-medium">Sort Order</label>
                        <input type="number" class="form-control @error('sort_order') is-invalid @enderror"
                               id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}"
                               min="0" step="1">
                        <div class="form-text">Items with lower numbers appear first</div>
                        @error('sort_order')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-admin">
                        <i class="bi bi-check-circle me-1"></i> Create Gallery Item
                    </button>
                    <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary">
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
            // Source type selection
            const sourceRadios = document.querySelectorAll('input[name="source_type"]');
            const imageUploadDiv = document.getElementById('imageUploadDiv');
            const imageUrlDiv = document.getElementById('imageUrlDiv');
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');

            // Handle source type change
            sourceRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'upload') {
                        imageUploadDiv.style.display = 'block';
                        imageUrlDiv.style.display = 'none';
                        imageInput.required = true;
                        document.getElementById('image_url').required = false;
                    } else {
                        imageUploadDiv.style.display = 'none';
                        imageUrlDiv.style.display = 'block';
                        imageInput.required = false;
                        document.getElementById('image_url').required = true;
                    }
                });
            });

            // Image preview
            imageInput.addEventListener('change', function() {
                const file = this.files[0];

                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        imagePreview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.style.display = 'none';
                }
            });

            // Hashtags input enhancement
            const hashtagsInput = document.getElementById('hashtags');
            hashtagsInput.addEventListener('input', function() {
                // Remove # symbols that users might add
                this.value = this.value.replace(/#/g, '');
            });

            // Form validation
            document.getElementById('galleryForm').addEventListener('submit', function(e) {
                const sourceType = document.querySelector('input[name="source_type"]:checked').value;

                if (sourceType === 'upload') {
                    const image = document.getElementById('image').files[0];

                    if (!image) {
                        e.preventDefault();
                        alert('Please select an image to upload');
                        document.getElementById('image').focus();
                        return false;
                    }

                    // Check file size (2MB = 2097152 bytes)
                    if (image.size > 2097152) {
                        e.preventDefault();
                        alert('Image size must be less than 2MB');
                        return false;
                    }
                } else {
                    const imageUrl = document.getElementById('image_url').value.trim();

                    if (!imageUrl) {
                        e.preventDefault();
                        alert('Please enter an image URL');
                        document.getElementById('image_url').focus();
                        return false;
                    }
                }
            });
        });
    </script>
@endpush
