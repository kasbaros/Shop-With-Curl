@extends('admin.layouts.app')

@section('title', 'Edit Promo Banner')
@section('page-title', 'Edit Promo Banner')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0">Edit Promo Banner</h2>
            <p class="text-muted mb-0">Modify the promotional banner details</p>
        </div>
        <a href="{{ route('admin.promo-banners.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Promo Banners
        </a>
    </div>

    <form action="{{ route('admin.promo-banners.update', $promoBanner) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-8">
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Banner Details</h5>

                    <!-- Heading -->
                    <div class="mb-4">
                        <label for="heading" class="form-label fw-medium">Heading <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('heading') is-invalid @enderror"
                               id="heading" name="heading" value="{{ old('heading', $promoBanner->heading) }}"
                               required>
                        @error('heading')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Subtitle -->
                    <div class="mb-4">
                        <label for="subtitle" class="form-label fw-medium">Subtitle</label>
                        <input type="text" class="form-control @error('subtitle') is-invalid @enderror"
                               id="subtitle" name="subtitle" value="{{ old('subtitle', $promoBanner->subtitle) }}">
                        @error('subtitle')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Features -->
                    <div class="mb-4">
                        <label for="features" class="form-label fw-medium">Features</label>
                        <input type="text" class="form-control @error('features') is-invalid @enderror"
                               id="features" name="features"
                               value="{{ old('features', $promoBanner->features ? implode(', ', $promoBanner->features) : '') }}"
                               placeholder="e.g. Premium Materials, Perfect Fit">
                        <div class="form-text">Separate multiple features with commas</div>
                        @error('features')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- CTA Button -->
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="cta_text" class="form-label fw-medium">CTA Text</label>
                            <input type="text" class="form-control @error('cta_text') is-invalid @enderror"
                                   id="cta_text" name="cta_text" value="{{ old('cta_text', $promoBanner->cta_text) }}">
                            @error('cta_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="cta_link" class="form-label fw-medium">CTA Link</label>
                            <input type="url" class="form-control @error('cta_link') is-invalid @enderror"
                                   id="cta_link" name="cta_link" value="{{ old('cta_link', $promoBanner->cta_link) }}">
                            @error('cta_link')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Images -->
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="image_desktop" class="form-label fw-medium">Desktop Image</label>
                            <input type="file" class="form-control @error('image_desktop') is-invalid @enderror"
                                   id="image_desktop" name="image_desktop" accept="image/*">
                            @if($promoBanner->image_desktop)
                                <img src="{{ $promoBanner->desktopImageUrl }}" alt="Desktop Banner"
                                     class="mt-2 img-fluid rounded">
                            @endif
                            @error('image_desktop')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="image_mobile" class="form-label fw-medium">Mobile Image</label>
                            <input type="file" class="form-control @error('image_mobile') is-invalid @enderror"
                                   id="image_mobile" name="image_mobile" accept="image/*">
                            @if($promoBanner->image_mobile)
                                <img src="{{ $promoBanner->mobileImageUrl }}" alt="Mobile Banner"
                                     class="mt-2 img-fluid rounded">
                            @endif
                            @error('image_mobile')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-lg-4">
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Settings</h5>
                    <!-- Active -->
                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" name="active" id="active"
                               value="1" {{ old('active', $promoBanner->active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="active">Active</label>
                    </div>

                    <!-- Priority -->
                    <div class="mb-4">
                        <label for="priority" class="form-label fw-medium">Priority</label>
                        <input type="number" class="form-control @error('priority') is-invalid @enderror"
                               id="priority" name="priority" value="{{ old('priority', $promoBanner->priority) }}">
                        @error('priority')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Schedule -->
                    <div class="mb-4">
                        <label for="starts_at" class="form-label fw-medium">Start Date</label>
                        <input type="datetime-local" class="form-control @error('starts_at') is-invalid @enderror"
                               id="starts_at" name="starts_at"
                               value="{{ old('starts_at', optional($promoBanner->starts_at)->format('Y-m-d\TH:i')) }}">
                        @error('starts_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="ends_at" class="form-label fw-medium">End Date</label>
                        <input type="datetime-local" class="form-control @error('ends_at') is-invalid @enderror"
                               id="ends_at" name="ends_at"
                               value="{{ old('ends_at', optional($promoBanner->ends_at)->format('Y-m-d\TH:i')) }}">
                        @error('ends_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Submit -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-admin">
                        <i class="bi bi-check-circle me-1"></i> Save Changes
                    </button>
                    <a href="{{ route('admin.promo-banners.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
@endsection
