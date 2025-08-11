@extends('admin.layouts.app')

@section('title', $banner->title)
@section('page-title', 'Banner Details')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0">{{ $banner->title }}</h2>
            <p class="text-muted mb-0">Banner Details</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-primary btn-admin">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-three-dots"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><button class="dropdown-item" onclick="toggleStatus({{ $banner->id }})">
                            <i class="bi bi-{{ $banner->is_active ? 'eye-slash' : 'eye' }} me-2"></i>
                            {{ $banner->is_active ? 'Deactivate' : 'Activate' }}
                        </button></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><button class="dropdown-item text-danger" onclick="deleteBanner({{ $banner->id }}, '{{ $banner->title }}')">
                            <i class="bi bi-trash me-2"></i>Delete
                        </button></li>
                </ul>
            </div>
            <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Banner Information -->
        <div class="col-lg-8">
            <!-- Banner Preview -->
            <div class="stat-card p-0 mb-4">
                <div class="position-relative" style="height: 400px; background-image: url('{{ $banner->image_url }}'); background-size: cover; background-position: center;">
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.4);">
                        <div class="text-center text-white px-4">
                            @if($banner->subtitle)
                                <p class="h6 mb-2 text-light">{{ $banner->subtitle }}</p>
                            @endif
                            <h1 class="display-4 fw-bold mb-3">{{ $banner->title }}</h1>
                            @if($banner->description)
                                <p class="lead mb-4">{{ $banner->description }}</p>
                            @endif
                            <div class="d-flex justify-content-center gap-2 flex-wrap">
                                @if($banner->button_text && $banner->button_link)
                                    <a href="{{ $banner->button_link }}"
                                       class="btn btn-primary btn-lg"
                                       target="_blank">
                                        {{ $banner->button_text }}
                                    </a>
                                @endif
                                @if($banner->secondary_button_text && $banner->secondary_button_link)
                                    <a href="{{ $banner->secondary_button_link }}"
                                       class="btn btn-outline-light btn-lg"
                                       target="_blank">
                                        {{ $banner->secondary_button_text }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-3 border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">This is how your banner appears on the website</span>
                        <div>
                            <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil me-1"></i> Edit Banner
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Banner Content Details -->
            <div class="stat-card p-4 mb-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h5 class="mb-0">Banner Information</h5>
                    <div class="d-flex gap-2">
                        @if($banner->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h6>Title</h6>
                        <p>{{ $banner->title }}</p>

                        @if($banner->subtitle)
                            <h6>Subtitle</h6>
                            <p>{{ $banner->subtitle }}</p>
                        @endif

                        <h6>Sort Order</h6>
                        <p>{{ $banner->sort_order }}</p>
                    </div>
                    <div class="col-md-6">
                        @if($banner->button_text)
                            <h6>Primary Button</h6>
                            <p>{{ $banner->button_text }}</p>
                        @endif

                        @if($banner->secondary_button_text)
                            <h6>Secondary Button</h6>
                            <p>{{ $banner->secondary_button_text }}</p>
                        @endif

                        <h6>Created</h6>
                        <p>{{ $banner->created_at->format('M j, Y \a\t g:i A') }}</p>

                        <h6>Last Updated</h6>
                        <p>{{ $banner->updated_at->format('M j, Y \a\t g:i A') }}</p>
                    </div>
                </div>

                @if($banner->description)
                    <h6>Description</h6>
                    <div class="bg-light p-3 rounded">
                        {!! nl2br(e($banner->description)) !!}
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Banner Links -->
            <div class="stat-card p-4 mb-4">
                <h5 class="mb-3">Banner Links</h5>

                @if($banner->button_link)
                    <h6>Primary Button Link</h6>
                    <p>
                        <a href="{{ $banner->button_link }}" target="_blank" class="text-primary">
                            {{ Str::limit($banner->button_link, 40) }}
                            <i class="bi bi-box-arrow-up-right ms-1"></i>
                        </a>
                    </p>
                @endif

                @if($banner->secondary_button_link)
                    <h6>Secondary Button Link</h6>
                    <p>
                        <a href="{{ $banner->secondary_button_link }}" target="_blank" class="text-primary">
                            {{ Str::limit($banner->secondary_button_link, 40) }}
                            <i class="bi bi-box-arrow-up-right ms-1"></i>
                        </a>
                    </p>
                @endif

                @if(!$banner->button_link && !$banner->secondary_button_link)
                    <p class="text-muted">No links configured for this banner.</p>
                @endif
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

            <!-- Actions -->
            <div class="stat-card p-4">
                <h5 class="mb-3">Actions</h5>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i> Edit Banner
                    </a>
                    <button class="btn btn-outline-{{ $banner->is_active ? 'warning' : 'success' }}"
                            onclick="toggleStatus({{ $banner->id }})">
                        <i class="bi bi-{{ $banner->is_active ? 'eye-slash' : 'eye' }} me-1"></i>
                        {{ $banner->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                    <button class="btn btn-outline-danger" onclick="deleteBanner({{ $banner->id }}, '{{ $banner->title }}')">
                        <i class="bi bi-trash me-1"></i> Delete Banner
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalTitle">Banner Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="{{ $banner->image_url }}" class="img-fluid rounded" alt="{{ $banner->title }}">
                </div>
            </div>
        </div>
    </div>

@push('scripts')
    <script>
        function showImageModal() {
            new bootstrap.Modal(document.getElementById('imageModal')).show();
        }

        function toggleStatus(bannerId) {
            fetch(`/admin/banners/${bannerId}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error updating banner status');
                    }
                });
        }

        function deleteBanner(bannerId, bannerTitle) {
            let message = `Are you sure you want to delete "${bannerTitle}"?`;

            if (!confirm(message)) {
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/banners/${bannerId}`;
            form.innerHTML = `
        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
        <input type="hidden" name="_method" value="DELETE">
    `;
            document.body.appendChild(form);
            form.submit();
        }
    </script>
@endpush
@endsection
