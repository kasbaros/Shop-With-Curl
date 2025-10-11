@extends('admin.layouts.app')

@section('title', 'Categories')
@section('page-title', 'Categories Management')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0">Categories</h2>
            <p class="text-muted mb-0">Organize your products with categories</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-admin">
            <i class="bi bi-plus-circle me-1"></i> Add Category
        </a>
    </div>

    <!-- Filters -->
    <div class="stat-card p-3 mb-4">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="search" class="form-label">Search</label>
                <input type="text" class="form-control" id="search" name="search"
                       placeholder="Search categories..." value="{{ request('search') }}">
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
                    <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name</option>
                    <option value="products_count" {{ request('sort') === 'products_count' ? 'selected' : '' }}>Product Count</option>
                    <option value="sort_order" {{ request('sort') === 'sort_order' ? 'selected' : '' }}>Sort Order</option>
                </select>
            </div>
            <div class="col-md-2">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Categories Table -->
    <div class="table-admin">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                <tr>
                    <th width="80">Image</th>
                    <th>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                           class="text-decoration-none text-dark">
                            Category
                            @if(request('sort') === 'name')
                                <i class="bi bi-chevron-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'products_count', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                           class="text-decoration-none text-dark">
                            Products
                            @if(request('sort') === 'products_count')
                                <i class="bi bi-chevron-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </a>
                    </th>
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
                @forelse($categories as $category)
                    <tr>
                        <td>
                            @php
                                $media = $category->getFirstMedia('images');
                                if ($media) {
                                    $thumb = $media->hasGeneratedConversion('thumb') ? $media->getUrl('thumb') : $media->getUrl();
                                } else {
                                    $thumb = $category->image_path ? Storage::url($category->image_path) : null;
                                }
                            @endphp
                            @if($thumb)
                                <img src="{{ $thumb }}"
                                     alt="{{ $category->name }}"
                                     class="rounded"
                                     style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                     style="width: 50px; height: 50px;">
                                    <i class="bi bi-folder text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div>
                                <h6 class="mb-1">
                                    <a href="{{ route('admin.categories.show', $category) }}" class="text-decoration-none">
                                        {{ $category->name }}
                                    </a>
                                </h6>
                                @if($category->description)
                                    <small class="text-muted">{{ Str::limit($category->description, 50) }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $category->products_count }} products</span>
                        </td>
                        <td>
                            <span class="text-muted">{{ $category->sort_order ?? 0 }}</span>
                        </td>
                        <td>
                            <div class="form-check form-switch">
{{--                                <input class="form-check-input" type="checkbox"--}}
{{--                                       {{ $category->is_active ? 'checked' : '' }}--}}
{{--                                       onchange="toggleStatus({{ $category->id }}, this)">--}}
                                <input class="form-check-input" type="checkbox"
                                       {{ $category->is_active ? 'checked' : '' }}
                                       onchange="toggleStatus('{{ $category->slug }}', this)">
                            </div>
                        </td>
                        <td>
                            <small class="text-muted">{{ $category->created_at->format('M j, Y') }}</small>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('admin.categories.show', $category) }}">
                                            <i class="bi bi-eye me-2"></i>View
                                        </a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.categories.edit', $category) }}">
                                            <i class="bi bi-pencil me-2"></i>Edit
                                        </a></li>
                                    <li><hr class="dropdown-divider"></li>
{{--                                    <li>--}}
{{--                                        <button class="dropdown-item text-danger" onclick="deleteCategory({{ $category->id }}, '{{ addslashes($category->name) }}', {{ $category->products_count }})">--}}
{{--                                            <i class="bi bi-trash me-2"></i>Delete--}}
{{--                                        </button>--}}
{{--                                    </li>--}}
                                    <li>
                                        <button class="dropdown-item text-danger" onclick="deleteCategory('{{ $category->slug }}', '{{ $category->name }}', {{ $category->products_count }})">
                                            <i class="bi bi-trash me-2"></i>Delete
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-folder2-open mb-3" style="font-size: 3rem;"></i>
                                <h5>No categories found</h5>
                                <p>Create your first category to organize products.</p>
                                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i> Add Category
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="p-3 border-top">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        // Toggle Status
        // function toggleStatus(categoryId, checkbox) {
        //     fetch(`/admin/categories/${categoryId}/toggle-status`, {
        //         method: 'PATCH',
        //         headers: {
        //             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        //             'Accept': 'application/json',
        //         }
        //     })
        //         .then(response => response.json())
        //         .then(data => {
        //             if (data.success) {
        //                 // Status updated successfully
        //             } else {
        //                 // Revert checkbox on error
        //                 checkbox.checked = !checkbox.checked;
        //                 alert('Error updating category status');
        //             }
        //         })
        //         .catch(error => {
        //             checkbox.checked = !checkbox.checked;
        //             alert('Error updating category status');
        //         });
        // }

        function toggleStatus(categorySlug, checkbox) {
            fetch(`/admin/categories/${categorySlug}/toggle-status`, {
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
                        alert('Error updating category status');
                    }
                })
                .catch(error => {
                    checkbox.checked = !checkbox.checked;
                    alert('Error updating category status');
                });
        }

        // Delete Category
        function deleteCategory(categoryId, categoryName, productCount) {
            let message = `Are you sure you want to delete "${categoryName}"?`;

            if (productCount > 0) {
                message += `\n\nWarning: This category has ${productCount} products. You cannot delete it until all products are moved or deleted.`;
                alert(message);
                return;
            }

            if (!confirm(message)) {
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/categories/${categoryId}`;
            form.innerHTML = `
        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
        <input type="hidden" name="_method" value="DELETE">
    `;
            document.body.appendChild(form);
            form.submit();
        }
    </script>
@endpush
