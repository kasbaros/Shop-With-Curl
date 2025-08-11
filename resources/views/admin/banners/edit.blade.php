@extends('admin.layouts.app')

@section('title', 'Edit Banner')
@section('page-title', 'Edit Banner')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0">Edit Banner</h2>
            <p class="text-muted mb-0">{{ $banner->title }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.banners.show', $banner) }}" class="btn btn-outline-info">
                <i class="bi bi-eye me-1"></i> View
            </a>
            <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data" id="bannerForm">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Main Information -->
            <div class="col-lg-8">
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Banner Content</h5>

                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('title') is-invalid @enderror"
                               id="title"
                               name="title"
                               value="{{ old('title', $banner->title) }}"
                               placeholder="Enter banner title"
                               required>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Subtitle -->
                    <div class="mb-3">
                        <label for="subtitle" class="form-label">Subtitle</label>
                        <input type="text"
                               class="form-control @error('subtitle') is-invalid @enderror"
                               id="subtitle"
                               name="subtitle"
                               value="{{ old('subtitle', $banner->subtitle) }}"
                               placeholder="Enter banner subtitle">
                        @error('subtitle')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description"
                                  name="description"
                                  rows="3"
                                  placeholder="Enter banner description">{{ old('description', $banner->description) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Button Settings -->
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Button Settings</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="button_text" class="form-label">Primary Button Text</label>
                                <input type="text"
                                       class="form-control @error('button_text') is-invalid @enderror"
                                       id="button_text"
                                       name="button_text"
                                       value="{{ old('button_text', $banner->button_text) }}"
                                       placeholder="e.g., Shop Now">
                                @error('button_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="button_link" class="form-label">Primary Button Link</label>
                                <input type="url"
                                       class="form-control @error('button_link') is-invalid @enderror"
                                       id="button_link"
                                       name="button_link"
                                       value="{{ old('button_link', $banner->button_link) }}"
                                       placeholder="https://example.com">
                                @error('button_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="secondary_button_text" class="form-label">Secondary Button Text</label>
                                <input type="text"
                                       class="form-control @error('secondary_button_text') is-invalid @enderror"
                                       id="secondary_button_text"
                                       name="secondary_button_text"
                                       value="{{ old('secondary_button_text', $banner->secondary_button_text) }}"
                                       placeholder="e.g., Learn More">
                                @error('secondary_button_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="secondary_button_link" class="form-label">Secondary Button Link</label>
                                <input type="url"
                                       class="form-control @error('secondary_button_link') is-invalid @enderror"
                                       id="secondary_button_link"
                                       name="secondary_button_link"
                                       value="{{ old('secondary_button_link', $banner->secondary_button_link) }}"
                                       placeholder="https://example.com">
                                @error('secondary_button_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Banner Image -->
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Banner Image</h5>

                    <!-- Current Image -->
                    <div class="mb-3">
                        <label class="form-label">Current Image</label>
                        <div class="position-relative d-inline-block">
                            <img src="{{ $banner->image_url }}"
                                 alt="{{ $banner->title }}"
                                 class="img-fluid rounded"
                                 style="max-height: 200px;">
                        </div>
                    </div>

                    <!-- New Image -->
                    <div class="mb-3">
                        <label for="image" class="form-label">Replace Image</label>
                        <input type="file"
                               class="form-control @error('image') is-invalid @enderror"
                               id="image"
                               name="image"
                               accept="image/*">
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Leave empty to keep current image<br>
                            Max file size: 2MB. Supported formats: JPEG, PNG, GIF, WebP
                        </div>
                    </div>

                    <div id="imagePreview" style="display: none;">
                        <label class="form-label">New Image Preview</label>
                        <img id="previewImg" src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                    </div>
                </div>

                <!-- Settings -->
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Settings</h5>

                    <div class="mb-3">
                        <label for="sort_order" class="form-label">Sort Order</label>
                        <input type="number"
                               class="form-control @error('sort_order') is-invalid @enderror"
                               id="sort_order"
                               name="sort_order"
                               value="{{ old('sort_order', $banner->sort_order) }}"
                               min="0">
                        @error('sort_order')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Banners with lower numbers appear first</div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input"
                                   type="checkbox"
                                   id="is_active"
                                   name="is_active"
                                   value="1"
                                {{ old('is_active', $banner->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
                        <div class="form-text">Only active banners will be displayed on the website</div>
                    </div>
                </div>

                <!-- Metadata -->
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Metadata</h5>
                    <small class="text-muted">
                        <strong>Created:</strong> {{ $banner->created_at->format('M j, Y g:i A') }}<br>
                        <strong>Updated:</strong> {{ $banner->updated_at->format('M j, Y g:i A') }}<br>
                        <strong>ID:</strong> {{ $banner->id }}
                    </small>
                </div>

                <!-- Submit Buttons -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-admin">
                        <i class="bi bi-check-circle me-1"></i> Update Banner
                    </button>
                    <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Image preview
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');

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

            // Form validation
            document.getElementById('bannerForm').addEventListener('submit', function(e) {
                const title = document.getElementById('title').value.trim();

                if (!title) {
                    e.preventDefault();
                    alert('Please enter a banner title');
                    document.getElementById('title').focus();
                    return false;
                }

                // Check file size if new image is selected
                const image = document.getElementById('image').files[0];
                if (image && image.size > 2097152) {
                    e.preventDefault();
                    alert('Image size must be less than 2MB');
                    return false;
                }
            });
        });
    </script>
@endpush
@endsection
