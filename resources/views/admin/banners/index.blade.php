@extends('admin.layouts.app')

@section('title', 'Banners')
@section('page-title', 'Banners Management')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0">Banners</h2>
            <p class="text-muted mb-0">Manage your website banners and hero images</p>
        </div>
        <a href="{{ route('admin.banners.create') }}" class="btn btn-primary btn-admin">
            <i class="bi bi-plus-circle me-1"></i> Add Banner
        </a>
    </div>

    <!-- Filters -->
    <div class="stat-card p-3 mb-4">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="search" class="form-label">Search</label>
                <input type="text" class="form-control" id="search" name="search"
                       placeholder="Search banners..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="sort" class="form-label">Sort By</label>
                <select class="form-select" id="sort" name="sort">
                    <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date Created</option>
                    <option value="title" {{ request('sort') === 'title' ? 'selected' : '' }}>Title</option>
                    <option value="sort_order" {{ request('sort') === 'sort_order' ? 'selected' : '' }}>Sort Order</option>
                </select>
            </div>
            <div class="col-md-2">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Banners Table -->
    <div class="table-admin">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                <tr>
                    <th width="80">Image</th>
                    <th>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'title', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                           class="text-decoration-none text-dark">
                            Title
                            @if(request('sort') === 'title')
                                <i class="bi bi-chevron-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </a>
                    </th>
                    <th>Subtitle</th>
                    <th>Sort Order</th>
                    <th>Status</th>
                    <th>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                           class="text-decoration-none text-dark">
                            Created
                            @if(request('sort') === 'created_at')
                                <i class="bi bi-chevron-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </a>
                    </th>
                    <th width="120">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($banners as $banner)
                    <tr>
                        <td>
                            <img src="{{ $banner->image_url }}"
                                 alt="{{ $banner->title }}"
                                 class="rounded"
                                 style="width: 50px; height: 50px; object-fit: cover;">
                        </td>
                        <td>
                            <div>
                                <h6 class="mb-1">
                                    <a href="{{ route('admin.banners.show', $banner) }}" class="text-decoration-none">
                                        {{ $banner->title }}
                                    </a>
                                </h6>
                            </div>
                        </td>
                        <td>
                            <span class="text-muted">{{ Str::limit($banner->subtitle ?? 'No subtitle', 25) }}</span>
                        </td>
                        <td>
                            <span class="text-muted">{{ $banner->sort_order }}</span>
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                       {{ $banner->is_active ? 'checked' : '' }}
                                       onchange="toggleStatus({{ $banner->id }}, this)">
                            </div>
                        </td>
                        <td>
                            <small class="text-muted">{{ $banner->created_at->format('M j, Y') }}</small>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('admin.banners.show', $banner) }}">
                                            <i class="bi bi-eye me-2"></i>View
                                        </a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.banners.edit', $banner) }}">
                                            <i class="bi bi-pencil me-2"></i>Edit
                                        </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><button class="dropdown-item text-danger" onclick="deleteBanner({{ $banner->id }}, '{{ $banner->title }}')">
                                            <i class="bi bi-trash me-2"></i>Delete
                                        </button></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-image mb-3" style="font-size: 3rem;"></i>
                                <h5>No banners found</h5>
                                <p>Create your first banner to enhance your website.</p>
                                <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i> Add Banner
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($banners) && method_exists($banners, 'hasPages') && $banners->hasPages())
            <div class="p-3 border-top">
                {{ $banners->links() }}
            </div>
        @endif
    </div>

@push('scripts')
    <script>
        // Toggle Status
        function toggleStatus(bannerId, checkbox) {
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
                        // Status updated successfully
                    } else {
                        // Revert checkbox on error
                        checkbox.checked = !checkbox.checked;
                        alert('Error updating banner status');
                    }
                })
                .catch(error => {
                    checkbox.checked = !checkbox.checked;
                    alert('Error updating banner status');
                });
        }

        // Delete Banner
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
