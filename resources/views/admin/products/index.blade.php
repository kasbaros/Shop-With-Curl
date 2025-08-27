@extends('admin.layouts.app')

@section('title', 'Products')
@section('page-title', 'Products Management')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0">Products</h2>
            <p class="text-muted mb-0">Manage your store products</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-admin">
            <i class="bi bi-plus-circle me-1"></i> Add Product
        </a>
    </div>

    <!-- Filters -->
    <div class="stat-card p-3 mb-4">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" class="form-control" name="search" placeholder="Search products..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select class="form-select" name="category">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="status">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="stock">
                    <option value="">All Stock</option>
                    <option value="in_stock" {{ request('stock') === 'in_stock' ? 'selected' : '' }}>In Stock</option>
                    <option value="low_stock" {{ request('stock') === 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                    <option value="out_of_stock" {{ request('stock') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                </select>
            </div>
            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i> Clear
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    <div class="stat-card mb-4" id="bulkActions" style="display: none;">
        <div class="p-3 bg-light">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span id="selectedCount">0</span> products selected
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-success" onclick="bulkAction('activate')">
                        <i class="bi bi-check-circle"></i> Activate
                    </button>
                    <button type="button" class="btn btn-sm btn-warning" onclick="bulkAction('deactivate')">
                        <i class="bi bi-x-circle"></i> Deactivate
                    </button>
                    <button type="button" class="btn btn-sm btn-info" onclick="bulkAction('feature')">
                        <i class="bi bi-star"></i> Feature
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="bulkAction('delete')">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="table-admin">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                <tr>
                    <th width="40">
                        <input type="checkbox" id="selectAll" class="form-check-input">
                    </th>
                    <th width="80">Image</th>
                    <th>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                           class="text-decoration-none text-dark">
                            Product
                            @if(request('sort') === 'name')
                                <i class="bi bi-chevron-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </a>
                    </th>
                    <th>Category</th>
                    <th>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'price', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                           class="text-decoration-none text-dark">
                            Price
                            @if(request('sort') === 'price')
                                <i class="bi bi-chevron-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </a>
                    </th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th width="120">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input product-checkbox" value="{{ $product->id }}">
                        </td>
                        <td>
                            @php
                                $thumb = method_exists($product, 'getMediaStorageUrl')
                                    ? $product->getMediaStorageUrl('images', 'large', 0)
                                    : '';
                                $thumb = $thumb ?: ($product->thumbnail_url ?? $product->image_url ?? asset('images/placeholder-product.jpg'));
                            @endphp
                            <img src="{{ $thumb }}"
                                 alt="{{ $product->name }}"
                                 class="rounded"
                                 style="width: 50px; height: 50px; object-fit: cover;">
                        </td>
                        <td>
                            <div>
                                <h6 class="mb-1">
                                    <a href="{{ route('admin.products.show', $product) }}" class="text-decoration-none">
                                        {{ $product->name }}
                                    </a>
                                    @if($product->is_featured)
                                        <i class="bi bi-star-fill text-warning ms-1" title="Featured"></i>
                                    @endif
                                </h6>
                                <small class="text-muted">SKU: {{ $product->sku }}</small>
                            </div>
                        </td>
                        <td>
                            @if($product->categories->count() > 0)
                                @foreach($product->categories->take(2) as $category)
                                    <span class="badge bg-secondary me-1">{{ $category->name }}</span>
                                @endforeach
                                @if($product->categories->count() > 2)
                                    <span class="badge bg-light text-dark">+{{ $product->categories->count() - 2 }}</span>
                                @endif
                            @else
                                <span class="badge bg-secondary">No Category</span>
                            @endif
                        </td>
                        <td>
                            <div>
                                @if($product->sale_price)
                                    <span class="text-danger fw-bold">${{ number_format($product->sale_price, 2) }}</span>
                                    <small class="text-muted text-decoration-line-through">${{ number_format($product->price, 2) }}</small>
                                @else
                                    <span class="fw-bold">${{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($product->manage_stock)
                                @if($product->stock_quantity <= 0)
                                    <span class="badge bg-danger">Out of Stock</span>
                                @elseif($product->stock_quantity <= ($product->min_stock_level ?? 5))
                                    <span class="badge bg-warning">Low Stock ({{ $product->stock_quantity }})</span>
                                @else
                                    <span class="badge bg-success">In Stock ({{ $product->stock_quantity }})</span>
                                @endif
                            @else
                                <span class="badge bg-info">Not Managed</span>
                            @endif
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                       {{ $product->is_active ? 'checked' : '' }}
                                       onchange="toggleStatus({{ $product->id }}, this)">
                            </div>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('admin.products.show', $product) }}">
                                            <i class="bi bi-eye me-2"></i>View
                                        </a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.products.edit', $product) }}">
                                            <i class="bi bi-pencil me-2"></i>Edit
                                        </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><button class="dropdown-item" onclick="toggleFeatured({{ $product->id }})">
                                            <i class="bi bi-star me-2"></i>{{ $product->is_featured ? 'Unfeature' : 'Feature' }}
                                        </button></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><button class="dropdown-item text-danger" onclick="deleteProduct({{ $product->id }})">
                                            <i class="bi bi-trash me-2"></i>Delete
                                        </button></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-box-seam mb-3" style="font-size: 3rem;"></i>
                                <h5>No products found</h5>
                                <p>Get started by adding your first product.</p>
                                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i> Add Product
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="p-3 border-top">
                {{ $products->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        // Select All functionality
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.product-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });

        // Individual checkbox change
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('product-checkbox')) {
                updateBulkActions();
            }
        });

        function updateBulkActions() {
            const selected = document.querySelectorAll('.product-checkbox:checked');
            const bulkActions = document.getElementById('bulkActions');
            const selectedCount = document.getElementById('selectedCount');

            selectedCount.textContent = selected.length;
            bulkActions.style.display = selected.length > 0 ? 'block' : 'none';
        }

        // Toggle Status
        function toggleStatus(productId, checkbox) {
            fetch(`/admin/products/${productId}/toggle-status`, {
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
                        alert('Error updating product status');
                    }
                })
                .catch(error => {
                    checkbox.checked = !checkbox.checked;
                    alert('Error updating product status');
                });
        }

        // Toggle Featured
        function toggleFeatured(productId) {
            fetch(`/admin/products/${productId}/toggle-featured`, {
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
                        alert('Error updating product');
                    }
                });
        }

        // Bulk Actions
        function bulkAction(action) {
            const selected = Array.from(document.querySelectorAll('.product-checkbox:checked'))
                .map(cb => cb.value);

            if (selected.length === 0) {
                alert('Please select products first');
                return;
            }

            if (action === 'delete' && !confirm('Are you sure you want to delete the selected products?')) {
                return;
            }

            fetch('/admin/products/bulk-action', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    action: action,
                    products: selected
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error performing bulk action');
                    }
                });
        }

        // Delete Product
        function deleteProduct(productId) {
            if (!confirm('Are you sure you want to delete this product?')) {
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/products/${productId}`;
            form.innerHTML = `
        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
        <input type="hidden" name="_method" value="DELETE">
    `;
            document.body.appendChild(form);
            form.submit();
        }
    </script>
@endpush
