@extends('admin.layouts.app')

@section('title', 'Promo Banners Management')
@section('page-title', 'Promo Banners Management')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0">Promo Banners Management</h2>
            <p class="text-muted mb-0">Manage promotional banners displayed on your homepage</p>
        </div>
        <a href="{{ route('admin.promo-banners.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Add Promo Banner
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="stat-card p-4 mb-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($promoBanners->isEmpty())
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-megaphone text-muted" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="fw-bold mb-3">No Promo Banners Found</h5>
                        <p class="text-muted mb-4">Create your first promotional banner to engage visitors.</p>
                        <a href="{{ route('admin.promo-banners.create') }}" class="btn btn-primary btn-admin">
                            <i class="bi bi-plus-circle me-1"></i> Add Promo Banner
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-light">
                            <tr>
                                <th>Heading</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Schedule</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($promoBanners as $banner)
                                <tr>
                                    <td>
                                        <div>
                                            <h6 class="mb-1">{{ $banner->heading }}</h6>
                                            @if($banner->subtitle)
                                                <small class="text-muted">{{ $banner->subtitle }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input status-toggle"
                                                   type="checkbox"
                                                   data-url="{{ route('admin.promo-banners.toggle-status', $banner) }}"
                                                {{ $banner->active ? 'checked' : '' }}>
                                            <label class="form-check-label">
                                                {{ $banner->active ? 'Active' : 'Inactive' }}
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $banner->priority ?? 10 }}</span>
                                    </td>
                                    <td>
                                        @if($banner->starts_at || $banner->ends_at)
                                            <small>
                                                @if($banner->starts_at)
                                                    From: {{ $banner->starts_at->format('M d, Y') }}<br>
                                                @endif
                                                @if($banner->ends_at)
                                                    To: {{ $banner->ends_at->format('M d, Y') }}
                                                @endif
                                            </small>
                                        @else
                                            <span class="text-muted">Always active</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('admin.promo-banners.show', $banner) }}"
                                               class="btn btn-sm btn-outline-secondary" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.promo-banners.edit', $banner) }}"
                                               class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-danger delete-banner"
                                                    data-id="{{ $banner->id }}"
                                                    data-heading="{{ $banner->heading }}" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
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
                    <h5 class="modal-title">Delete Promo Banner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="bi bi-exclamation-triangle text-warning" style="font-size: 2rem;"></i>
                    </div>
                    <p>Are you sure you want to delete "<span id="deleteBannerHeading"></span>"?</p>
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
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        }
                    }).then(() => location.reload());
                });
            });

            // Delete banner functionality
            document.querySelectorAll('.delete-banner').forEach(button => {
                button.addEventListener('click', function() {
                    const bannerId = this.dataset.id;
                    const heading = this.dataset.heading || 'this banner';

                    document.getElementById('deleteBannerHeading').textContent = heading;
                    document.getElementById('deleteForm').action = "{{ url('admin/promo-banners') }}/" + bannerId;

                    new bootstrap.Modal(document.getElementById('deleteModal')).show();
                });
            });
        });
    </script>
@endpush
