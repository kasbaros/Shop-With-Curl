@extends('admin.layouts.app')

@section('title', 'Add Promo Banner')
@section('page-title', 'Add Promo Banner')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0">Add New Promo Banner</h2>
            <p class="text-muted mb-0">Create a promotional banner for your homepage</p>
        </div>
        <a href="{{ route('admin.promo-banners.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Promo Banners
        </a>
    </div>

    <form action="{{ route('admin.promo-banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Banner Details</h5>

                    <!-- Heading -->
                    <div class="mb-4">
                        <label for="heading" class="form-label fw-medium">Heading <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('heading') is-invalid @enderror"
                               id="heading" name="heading" value="{{ old('heading') }}"
                               placeholder="Bra Spotlight" required>
                        @error('heading')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Subtitle -->
                    <div class="mb-4">
                        <label for="subtitle" class="form-label fw-medium">Subtitle</label>
                        <input type="text" class="form-control @error('subtitle') is-invalid @enderror"
                               id="subtitle" name="subtitle" value="{{ old('subtitle') }}"
                               placeholder="Discover comfort that embraces your confidence">
                        @error('subtitle')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Features -->
                    <div class="mb-4">
                        <label for="features" class="form-label fw-medium">Features</label>
                        <input type="text" class="form-control @error('features') is-invalid @enderror"
                               id="features" name="features" value="{{ old('features') }}"
                               placeholder="Premium Materials, Perfect Fit, All-Day Comfort, Elegant Design">
                        <div class="form-text">Separate multiple features with commas</div>
                        @error('features')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- CTA Button -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="cta_text" class="form-label fw-medium">CTA Button Text</label>
                                <input type="text" class="form-control @error('cta_text') is-invalid @enderror"
                                       id="cta_text" name="cta_text" value="{{ old('cta_text') }}"
                                       placeholder="Shop Collection">
                                @error('cta_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="cta_link" class="form-label fw-medium">CTA Button Link</label>
                                <input type="url" class="form-control @error('cta_link') is-invalid @enderror"
                                       id="cta_link" name="cta_link" value="{{ old('cta_link') }}"
                                       placeholder="https://example.com/shop">
                                @error('cta_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Price Badge -->
                    <div class="mb-4">
                        <label for="price_badge" class="form-label fw-medium">Price Badge</label>
                        <input type="text" class="form-control @error('price_badge') is-invalid @enderror"
                               id="price_badge" name="price_badge" value="{{ old('price_badge') }}"
                               placeholder="Starting at UGX 35,000">
                        @error('price_badge')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Images -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="image_desktop" class="form-label fw-medium">Desktop Image</label>
                                <input type="file" class="form-control @error('image_desktop') is-invalid @enderror"
                                       id="image_desktop" name="image_desktop" accept="image/*">
                                <div class="form-text">Recommended size: 1200x600px</div>
                                @error('image_desktop')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="image_mobile" class="form-label fw-medium">Mobile Image</label>
                                <input type="file" class="form-control @error('image_mobile') is-invalid @enderror"
                                       id="image_mobile" name="image_mobile" accept="image/*">
                                <div class="form-text">Recommended size: 800x600px</div>
                                @error('image_mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
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
                                   name="active" value="1" {{ old('active', 1) ? 'checked' : '' }}>
                            <label class="form-check-label" for="active">Active</label>
                        </div>
                    </div>

                    <!-- Priority -->
                    <div class="mb-4">
                        <label for="priority" class="form-label fw-medium">Priority</label>
                        <input type="number" class="form-control @error('priority') is-invalid @enderror"
                               id="priority" name="priority" value="{{ old('priority', 10) }}"
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
                               id="starts_at" name="starts_at" value="{{ old('starts_at') }}">
                        @error('starts_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="ends_at" class="form-label fw-medium">End Date</label>
                        <input type="datetime-local" class="form-control @error('ends_at') is-invalid @enderror"
                               id="ends_at" name="ends_at" value="{{ old('ends_at') }}">
                        @error('ends_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-admin">
                        <i class="bi bi-check-circle me-1"></i> Create Promo Banner
                    </button>
                    <a href="{{ route('admin.promo-banners.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
@endsection
