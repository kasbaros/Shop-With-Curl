@extends('admin.layouts.app')

@section('title', 'Gallery Management')
@section('page-title', 'Gallery Management')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0">Gallery Management</h2>
            <p class="text-muted mb-0">Manage your website gallery items</p>
        </div>
        <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Add Gallery Item
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="stat-card p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Gallery Items</h5>
                    <div>
                        <a href="{{ route('admin.gallery.create') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Add New
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($galleryItems->isEmpty())
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-images text-muted" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="fw-bold mb-3">No Gallery Items Found</h5>
                        <p class="text-muted mb-4">Start building your gallery by adding your first item.</p>
                        <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary btn-admin">
                            <i class="bi bi-plus-circle me-1"></i> Add Gallery Item
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Image</th>
                                    <th>Caption</th>
                                    <th>Source</th>
                                    <th>Product</th>
                                    <th>Status</th>
                                    <th>Featured</th>
                                    <th>Sort</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($galleryItems as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div style="width: 60px; height: 60px;">
                                                    <img src="{{ $item->image_url }}"
                                                         alt="{{ $item->caption }}"
                                                         class="img-fluid rounded"
                                                         style="width: 100%; height: 100%; object-fit: cover;">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <h6 class="mb-1">
                                                    {{ $item->caption ?? 'No caption' }}
                                                </h6>
                                                @if($item->hashtags)
                                                    <small class="text-muted">
                                                        {{ $item->hashtags_string }}
                                                    </small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $item->source_type === 'upload' ? 'primary' : ($item->source_type === 'instagram' ? 'danger' : 'info') }}">
                                                {{ ucfirst($item->source_type) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($item->product)
                                                <a href="{{ route('admin.products.show', $item->product) }}"
                                                   class="text-decoration-none">
                                                    {{ $item->product->name }}
                                                </a>
                                            @else
                                                <span class="text-muted">No product</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input status-toggle"
                                                       type="checkbox"
                                                       data-url="{{ route('admin.gallery.toggle-status', $item) }}"
                                                    {{ $item->is_active ? 'checked' : '' }}>
                                                <label class="form-check-label">
                                                    {{ $item->is_active ? 'Active' : 'Inactive' }}
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input featured-toggle"
                                                       type="checkbox"
                                                       data-url="{{ route('admin.gallery.toggle-featured', $item) }}"
                                                    {{ $item->is_featured ? 'checked' : '' }}>
                                                <label class="form-check-label">
                                                    {{ $item->is_featured ? 'Featured' : 'Normal' }}
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $item->sort_order ?? 0 }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('admin.gallery.show', $item) }}"
                                                   class="btn btn-sm btn-outline-secondary"
                                                   title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.gallery.edit', $item) }}"
                                                   class="btn btn-sm btn-outline-primary"
                                                   title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button class="btn btn-sm btn-outline-danger delete-gallery-item"
                                                        data-id="{{ $item->id }}"
                                                        data-caption="{{ $item->caption }}"
                                                        title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                                @if($item->link)
                                                    <a href="{{ $item->link }}"
                                                       target="_blank"
                                                       class="btn btn-sm btn-outline-info"
                                                       title="Open Link">
                                                        <i class="bi bi-box-arrow-up-right"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
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
                    <p>Are you sure you want to delete "<span id="deleteItemCaption"></span>"?</p>
                    <p class="text-muted">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
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
        document.addEventListener('DOMContentLoaded', function() {
            // Status toggle functionality
            document.querySelectorAll('.status-toggle').forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const url = this.dataset.url;
                    const isChecked = this.checked;
                    const label = this.nextElementSibling;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                    // Create a form and submit it
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    form.style.display = 'none';

                    // Add CSRF token
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    form.appendChild(csrfInput);

                    // Add to body and submit
                    document.body.appendChild(form);

                    // Update UI optimistically
                    label.textContent = isChecked ? 'Active' : 'Inactive';

                    // Show notification
                    showNotification('Status updated successfully', 'success');

                    // Submit the form
                    form.submit();
                });
            });

            // Featured toggle functionality
            document.querySelectorAll('.featured-toggle').forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const url = this.dataset.url;
                    const isChecked = this.checked;
                    const label = this.nextElementSibling;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                    // Create a form and submit it
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    form.style.display = 'none';

                    // Add CSRF token
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    form.appendChild(csrfInput);

                    // Add to body and submit
                    document.body.appendChild(form);

                    // Update UI optimistically
                    label.textContent = isChecked ? 'Featured' : 'Normal';

                    // Show notification
                    showNotification('Featured status updated successfully', 'success');

                    // Submit the form
                    form.submit();
                });
            });

            // Delete gallery item functionality
            document.querySelectorAll('.delete-gallery-item').forEach(button => {
                button.addEventListener('click', function() {
                    const itemId = this.dataset.id;
                    const caption = this.dataset.caption || 'this item';

                    document.getElementById('deleteItemCaption').textContent = caption;
                    document.getElementById('deleteForm').action = "{{ url('admin/gallery') }}/" + itemId;

                    new bootstrap.Modal(document.getElementById('deleteModal')).show();
                });
            });
        });

        function showNotification(message, type) {
            const iconClass = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';

            const alertHtml = `
                <div class="alert ${alertClass} alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert" style="z-index: 9999;">
                    <i class="${iconClass} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', alertHtml);

            // Auto remove after 3 seconds
            setTimeout(() => {
                const alert = document.querySelector('.alert:last-child');
                if (alert) {
                    alert.remove();
                }
            }, 3000);
        }
    </script>
@endpush
