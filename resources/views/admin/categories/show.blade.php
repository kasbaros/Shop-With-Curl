@extends('admin.layouts.app')

@section('title', $category->name)
@section('page-title', 'Category Details')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0">{{ $category->name }}</h2>
            <p class="text-muted mb-0">Category Details & Products</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary btn-admin">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-three-dots"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><button class="dropdown-item" onclick="toggleStatus({{ $category->id }})">
                            <i class="bi bi-{{ $category->is_active ? 'eye-slash' : 'eye' }} me-2"></i>
                            {{ $category->is_active ? 'Deactivate' : 'Activate' }}
                        </button></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><button class="dropdown-item text-danger" onclick="deleteCategory({{ $category->id }}, '{{ $category->name }}', {{ $category->products_count }})">
                            <i class="bi bi-trash me-2"></i>Delete
                        </button></li>
                </ul>
            </div>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Category Information -->
        <div class="col-lg-8">
            <!-- Basic Info -->
            <div class="stat-card p-4 mb-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h5 class="mb-0">Category Information</h5>
                    <div class="d-flex gap-2">
                        @if($category->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h6>Name</h6>
                        <p>{{ $category->name }}</p>

                        <h6>Slug</h6>
                        <p><code>{{ $category->slug }}</code></p>

                        <h6>Sort Order</h6>
                        <p>{{ $category->sort_order ?? 0 }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Products Count</h6>
                        <p class="fs-5 fw-bold text-primary">{{ $category->products_count }}</p>

                        <h6>Created</h6>
                        <p>{{ $category->created_at->format('M j, Y \a\t g:i A') }}</p>

                        <h6>Last Updated</h6>
                        <p>{{ $category->updated_at->format('M j, Y \a\t g:i A') }}</p>
                    </div>
                </div>

                @if($category->description)
                    <h6>Description</h6>
                    <div class="bg-light p-3 rounded">
                        {!! nl2br(e($category->description)) !!}
                    </div>
                @endif
            </div>

            <!-- SEO Information -->
            @if($category->meta_title || $category->meta_description)
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">SEO Information</h5>

                    @if($category->meta_title)
                        <h6>Meta Title</h6>
                        <p>{{ $category->meta_title }}</p>
                    @endif

                    @if($category->meta_description)
                        <h6>Meta Description</h6>
                        <p>{{ $category->meta_description }}</p>
                    @endif
                </div>
            @endif

            <!-- Recent Products -->
            @if($recentProducts->count() > 0)
                <div class="stat-card p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Recent Products ({{ $recentProducts->count() }})</h5>
                        <a href="{{ route('admin.products.index', ['category' => $category->id]) }}" class="btn btn-outline-primary btn-sm">
                            View All Products
                        </a>
                    </div>

                    <div class="row g-3">
                        @foreach($recentProducts as $product)
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 border rounded">
                                    @if($product->images->first())
                                        <img src="{{ Storage::url($product->images->first()->image_path) }}"
                                             alt="{{ $product->name }}"
                                             class="rounded me-3"
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                             style="width: 60px; height: 60px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    @endif

                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
                                            <a href="{{ route('admin.products.show', $product) }}" class="text-decoration-none">
                                                {{ Str::limit($product->name, 30) }}
                                            </a>
                                        </h6>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-success fw-bold">${{ number_format($product->price, 2) }}</span>
                                            <div class="d-flex gap-1">
                                                @if($product->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif

                                                @if($product->is_featured)
                                                    <span class="badge bg-warning">Featured</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Category Image -->
            @if($category->image_path)
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Category Image</h5>
                    <img src="{{ Storage::url($category->image_path) }}"
                         alt="{{ $category->name }}"
                         class="img-fluid rounded cursor-pointer"
                         onclick="showImageModal('{{ Storage::url($category->image_path) }}', '{{ $category->name }}')"
                         style="width: 100%; max-height: 300px; object-fit: cover;">
                </div>
            @endif

            <!-- Quick Stats -->
            <div class="stat-card p-4 mb-4">
                <h5 class="mb-3">Statistics</h5>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Total Products</span>
                    <span class="fw-bold fs-5 text-primary">{{ $category->products_count }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Active Products</span>
                    <span class="fw-bold">{{ $category->products()->where('is_active', true)->count() }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Featured Products</span>
                    <span class="fw-bold">{{ $category->products()->where('is_featured', true)->count() }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">Avg. Product Price</span>
                    <span class="fw-bold">
                    @php
                        $avgPrice = $category->products()->avg('price');
                    @endphp
                    ${{ $avgPrice ? number_format($avgPrice, 2) : '0.00' }}
                </span>
                </div>
            </div>

            <!-- Actions -->
            <div class="stat-card p-4">
                <h5 class="mb-3">Actions</h5>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i> Edit Category
                    </a>
                    <a href="{{ route('admin.products.create', ['category' => $category->id]) }}" class="btn btn-outline-success">
                        <i class="bi bi-plus-circle me-1"></i> Add Product
                    </a>
                    <a href="{{ route('admin.products.index', ['category' => $category->id]) }}" class="btn btn-outline-info">
                        <i class="bi bi-eye me-1"></i> View All Products
                    </a>
                    <button class="btn btn-outline-{{ $category->is_active ? 'warning' : 'success' }}"
                            onclick="toggleStatus({{ $category->id }})">
                        <i class="bi bi-{{ $category->is_active ? 'eye-slash' : 'eye' }} me-1"></i>
                        {{ $category->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                    @if($category->products_count == 0)
                        <button class="btn btn-outline-danger" onclick="deleteCategory({{ $category->id }}, '{{ $category->name }}', {{ $category->products_count }})">
                            <i class="bi bi-trash me-1"></i> Delete Category
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalTitle">Category Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" class="img-fluid rounded" alt="">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function showImageModal(imageSrc, title) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('imageModalTitle').textContent = title;
            new bootstrap.Modal(document.getElementById('imageModal')).show();
        }

        function toggleStatus(categoryId) {
            fetch(`/admin/categories/${categoryId}/toggle-status`, {
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
                        alert('Error updating category status');
                    }
                });
        }

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
