@extends('admin.layouts.app')

@section('title', 'Gallery Item Details')
@section('page-title', 'Gallery Item Details')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0">Gallery Item Details</h2>
            <p class="text-muted mb-0">View details for this gallery item</p>
        </div>
        <div>
            <a href="{{ route('admin.gallery.edit', $galleryItem) }}" class="btn btn-primary me-2">
                <i class="bi bi-pencil me-1"></i> Edit Item
            </a>
            <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Gallery
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="stat-card p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Gallery Item Information</h5>
                    <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="bi bi-trash me-1"></i> Delete
                    </button>
                </div>

                <!-- Image Display -->
                <div class="text-center mb-4">
                    <img src="{{ $galleryItem->image_url }}"
                         alt="{{ $galleryItem->caption }}"
                         class="img-fluid rounded shadow-sm"
                         style="max-height: 400px;">
                </div>

                <!-- Details Table -->
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tbody>
                        <tr>
                            <th scope="row" style="width: 200px;" class="fw-medium">Caption:</th>
                            <td>{{ $galleryItem->caption ?? 'No caption' }}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="fw-medium">Source Type:</th>
                            <td>
                                    <span
                                        class="badge bg-{{ $galleryItem->source_type === 'upload' ? 'primary' : ($galleryItem->source_type === 'instagram' ? 'danger' : 'info') }}">
                                        {{ ucfirst($galleryItem->source_type) }}
                                    </span>
                            </td>
                        </tr>
                        @if($galleryItem->hashtags)
                            <tr>
                                <th scope="row" class="fw-medium">Hashtags:</th>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($galleryItem->hashtags as $tag)
                                            <span class="badge bg-secondary">#{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endif
                        @if($galleryItem->link)
                            <tr>
                                <th scope="row" class="fw-medium">External Link:</th>
                                <td>
                                    <a href="{{ $galleryItem->link }}" target="_blank" class="text-decoration-none">
                                        {{ $galleryItem->link }}
                                        <i class="bi bi-box-arrow-up-right ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @endif
                        @if($galleryItem->product)
                            <tr>
                                <th scope="row" class="fw-medium">Associated Product:</th>
                                <td>
                                    <a href="{{ route('admin.products.show', $galleryItem->product) }}"
                                       class="text-decoration-none">
                                        {{ $galleryItem->product->name }}
                                    </a>
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <th scope="row" class="fw-medium">Status:</th>
                            <td>
                                    <span class="badge bg-{{ $galleryItem->is_active ? 'success' : 'secondary' }}">
                                        {{ $galleryItem->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row" class="fw-medium">Featured:</th>
                            <td>
                                    <span class="badge bg-{{ $galleryItem->is_featured ? 'warning' : 'secondary' }}">
                                        {{ $galleryItem->is_featured ? 'Featured' : 'Normal' }}
                                    </span>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row" class="fw-medium">Sort Order:</th>
                            <td>{{ $galleryItem->sort_order ?? 0 }}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="fw-medium">Created:</th>
                            <td>{{ $galleryItem->created_at->format('M d, Y \a\t g:i A') }}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="fw-medium">Last Updated:</th>
                            <td>{{ $galleryItem->updated_at->format('M d, Y \a\t g:i A') }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
            <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="stat-card p-4 mb-4">
            <h5 class="mb-3">Quick Actions</h5>
            <div class="d-grid gap-2">
                <a href="{{ route('admin.gallery.edit', $galleryItem) }}" class="btn btn-primary btn-admin">
                    <i class="bi bi-pencil me-1"></i> Edit Gallery Item
                </a>

                @if($galleryItem->link)
                    <a href="{{ $galleryItem->link }}" target="_blank" class="btn btn-info">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Open External Link
                    </a>
                @endif

                @if($galleryItem->product)
                    <a href="{{ route('admin.products.show', $galleryItem->product) }}" class="btn btn-success">
                        <i class="bi bi-box me-1"></i> View Product
                    </a>
                @endif

                <button class="btn btn-outline-primary toggle-status"
                        data-url="{{ route('admin.gallery.toggle-status', $galleryItem) }}">
                    <i class="bi bi-toggle-on me-1"></i>
                    {{ $galleryItem->is_active ? 'Deactivate' : 'Activate' }}
                </button>

                <button class="btn btn-outline-warning toggle-featured"
                        data-url="{{ route('admin.gallery.toggle-featured', $galleryItem) }}">
                    <i class="bi bi-star me-1"></i>
                    {{ $galleryItem->is_featured ? 'Unfeature' : 'Feature' }}
                </button>

                <hr>

                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="bi bi-trash me-1"></i> Delete Gallery Item
                </button>

                <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back to Gallery
                </a>
            </div>
        </div>

        <!-- Image Information -->
        <div class="stat-card p-4 mb-4">
            <h5 class="mb-3">Image Information</h5>
            <div class="table-responsive">
                <table class="table table-sm table-borderless">
                    <tbody>
                    <tr>
                        <th class="fw-medium">Image URL:</th>
                        <td>
                            <a href="{{ $galleryItem->image_url }}" target="_blank" class="text-break">
                                {{ Str::limit($galleryItem->image_url, 30) }}
                                <i class="bi bi-box-arrow-up-right ms-1"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th class="fw-medium">Type:</th>
                        <td>
                            @if(filter_var($galleryItem->image, FILTER_VALIDATE_URL))
                                External URL
                            @else
                                Uploaded File
                            @endif
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    </div>

    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Gallery Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="bi bi-exclamation-triangle text-warning" style="font-size: 2rem;"></i>
                    </div>
                    <p class="text-center">Are you sure you want to delete this gallery item?</p>
                    <p class="text-muted text-center">This action cannot be undone.</p>
                    @if($galleryItem->caption)
                        <div class="alert alert-secondary">
                            <strong>Caption:</strong> {{ $galleryItem->caption }}
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.gallery.destroy', $galleryItem) }}" method="POST"
                          style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Status toggle
            const statusToggle = document.querySelector('.toggle-status');
            if (statusToggle) {
                statusToggle.addEventListener('click', function () {
                    const url = this.dataset.url;
                    const button = this;

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert('Failed to update status');
                            }
                        })
                        .catch(error => {
                            alert('Error updating status');
                        });
                });
            }

            // Featured toggle
            const featuredToggle = document.querySelector('.toggle-featured');
            if (featuredToggle) {
                featuredToggle.addEventListener('click', function () {
                    const url = this.dataset.url;
                    const button = this;

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert('Failed to update featured status');
                            }
                        })
                        .catch(error => {
                            alert('Error updating featured status');
                        });
                });
            }
        });
    </script>
@endpush
