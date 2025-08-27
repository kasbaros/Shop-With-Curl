@extends('admin.layouts.app')

@section('title', $product->name)
@section('page-title', 'Product Details')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0">{{ $product->name }}</h2>
            <p class="text-muted mb-0">
                SKU: {{ $product->sku }} |
                Categories:
                @if($product->categories->count() > 0)
                    {{ $product->categories->pluck('name')->join(', ') }}
                @else
                    Uncategorized
                @endif
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary btn-admin">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-three-dots"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><button class="dropdown-item" onclick="toggleStatus({{ $product->id }})">
                            <i class="bi bi-{{ $product->is_active ? 'eye-slash' : 'eye' }} me-2"></i>
                            {{ $product->is_active ? 'Deactivate' : 'Activate' }}
                        </button></li>
                    <li><button class="dropdown-item" onclick="toggleFeatured({{ $product->id }})">
                            <i class="bi bi-star me-2"></i>
                            {{ $product->is_featured ? 'Unfeature' : 'Feature' }}
                        </button></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><button class="dropdown-item text-danger" onclick="deleteProduct({{ $product->id }})">
                            <i class="bi bi-trash me-2"></i>Delete
                        </button></li>
                </ul>
            </div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Product Information -->
        <div class="col-lg-8">
            <!-- Basic Info -->
            <div class="stat-card p-4 mb-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h5 class="mb-0">Product Information</h5>
                    <div class="d-flex gap-2">
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

                <div class="row">
                    <div class="col-md-6">
                        <h6>Name</h6>
                        <p>{{ $product->name }}</p>

                        <h6>SKU</h6>
                        <p><code>{{ $product->sku }}</code></p>

                        <h6>Categories</h6>
                        @if($product->categories->count() > 0)
                            <div>
                                @foreach($product->categories as $category)
                                    <span class="badge bg-secondary me-1">{{ $category->name }}</span>
                                @endforeach
                            </div>
                        @else
                            <p>Uncategorized</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h6>Regular Price</h6>
                        <p class="fs-5 fw-bold">${{ number_format($product->price, 2) }}</p>

                        @if($product->sale_price)
                            <h6>Sale Price</h6>
                            <p class="fs-5 fw-bold text-danger">${{ number_format($product->sale_price, 2) }}</p>
                        @endif

                        <h6>Created</h6>
                        <p>{{ $product->created_at->format('M j, Y') }}</p>
                    </div>
                </div>

                @if($product->short_description)
                    <h6>Short Description</h6>
                    <p>{{ $product->short_description }}</p>
                @endif

                <h6>Description</h6>
                <div class="bg-light p-3 rounded">
                    {!! nl2br(e($product->description)) !!}
                </div>
            </div>

{{--            <!-- Product Images -->--}}
{{--            @if($product->getMedia('images')->count() > 0)--}}
{{--                <div class="stat-card p-4 mb-4">--}}
{{--                    <h5 class="mb-3">Product Images ({{ $product->getMedia('images')->count() }})</h5>--}}
{{--                    <div class="row g-3">--}}
{{--                        @foreach($product->getMedia('images') as $media)--}}
{{--                            <div class="col-md-3">--}}
{{--                                <div class="position-relative">--}}
{{--                                    @php--}}
{{--                                        $imgUrl = method_exists($product, 'getMediaStorageUrl')--}}
{{--                                            ? $product->getMediaStorageUrl('images', 'large', $loop->index)--}}
{{--                                            : $media->getUrl();--}}
{{--                                    @endphp--}}
{{--                                    <img src="{{ $imgUrl }}"--}}
{{--                                         class="img-fluid rounded cursor-pointer"--}}
{{--                                         style="height: 150px; width: 100%; object-fit: cover;"--}}
{{--                                         onclick="showImageModal('{{ $imgUrl }}', '{{ $product->name }}')">--}}
{{--                                    @if($loop->first)--}}
{{--                                        <div class="badge bg-primary position-absolute top-0 start-0 m-2">Main</div>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        @endforeach--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            @endif--}}

            <!-- Product Images -->
            @if($product->gallery || $product->getMedia('images')->count() > 0)
                <div class="stat-card p-4 mb-4">
                    @php
                        $images = $product->getImagesAttribute();
                        $imageCount = count($images);
                        $placeholderImage = asset('images/placeholder-product.jpg');
                    @endphp
                    <h5 class="mb-3">Product Images ({{ $imageCount }})</h5>
                    <div class="row g-3">
                        @foreach($images as $index => $image)
                            <div class="col-md-3">
                                <div class="position-relative">
                                    <img src="{{ $image['large'] ?? $image['original'] ?? $placeholderImage }}"
                                         class="img-fluid rounded cursor-pointer"
                                         style="height: 150px; width: 100%; object-fit: cover;"
                                         onclick="showImageModal('{{ $image['original'] ?? $placeholderImage }}', '{{ $product->name }}')"
                                         onerror="this.src='{{ $placeholderImage }}'">
                                    @if($index === 0)
                                        <div class="badge bg-primary position-absolute top-0 start-0 m-2">Main</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- SEO Information -->
            @if($product->meta_title || $product->meta_description)
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">SEO Information</h5>

                    @if($product->meta_title)
                        <h6>Meta Title</h6>
                        <p>{{ $product->meta_title }}</p>
                    @endif

                    @if($product->meta_description)
                        <h6>Meta Description</h6>
                        <p>{{ $product->meta_description }}</p>
                    @endif
                </div>
            @endif

            <!-- Product Reviews -->
            @if($product->reviews->count() > 0)
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Recent Reviews ({{ $product->reviews->count() }})</h5>

                    @foreach($product->reviews->take(5) as $review)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">{{ $review->user->name ?? 'Anonymous' }}</h6>
                                    <div class="text-warning mb-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                        @endfor
                                        <small class="text-muted ms-1">({{ $review->rating }}/5)</small>
                                    </div>
                                    <p class="mb-1">{{ $review->comment }}</p>
                                    <small class="text-muted">{{ $review->created_at->format('M j, Y') }}</small>
                                </div>
                                <div>
                                    @if($review->is_approved)
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if($product->reviews->count() > 5)
                        <a href="#" class="btn btn-outline-primary btn-sm">View All Reviews</a>
                    @endif
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="stat-card p-4 mb-4">
                <h5 class="mb-3">Quick Stats</h5>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted">Views</span>
                    <span class="fw-bold">{{ number_format($product->views ?? 0) }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted">Orders</span>
                    <span class="fw-bold">{{ number_format($product->orders_count ?? 0) }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted">Revenue</span>
                    <span class="fw-bold">${{ number_format($product->total_revenue ?? 0, 2) }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">Reviews</span>
                    <span class="fw-bold">{{ $product->reviews->count() }}</span>
                </div>
            </div>

            <!-- Inventory -->
            <div class="stat-card p-4 mb-4">
                <h5 class="mb-3">Inventory</h5>

                @if($product->manage_stock)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Stock Quantity</span>
                        <span class="fw-bold {{ $product->stock_quantity <= 0 ? 'text-danger' : ($product->stock_quantity <= ($product->min_stock_level ?? 5) ? 'text-warning' : 'text-success') }}">
                        {{ $product->stock_quantity }}
                    </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Low Stock Level</span>
                        <span>{{ $product->min_stock_level ?? 5 }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Status</span>
                        @if($product->stock_quantity <= 0)
                            <span class="badge bg-danger">Out of Stock</span>
                        @elseif($product->stock_quantity <= ($product->min_stock_level ?? 5))
                            <span class="badge bg-warning">Low Stock</span>
                        @else
                            <span class="badge bg-success">In Stock</span>
                        @endif
                    </div>
                @else
                    <p class="text-muted">Stock management disabled</p>
                @endif
            </div>

            <!-- Shipping -->
            @if($product->weight || $product->dimensions)
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Shipping</h5>

                    @if($product->weight)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Weight</span>
                            <span>{{ $product->weight }} lbs</span>
                        </div>
                    @endif

                    @if($product->dimensions)
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Dimensions</span>
                            <span>{{ $product->dimensions }}</span>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Actions -->
            <div class="stat-card p-4">
                <h5 class="mb-3">Actions</h5>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i> Edit Product
                    </a>
                    <button class="btn btn-outline-info" onclick="duplicateProduct({{ $product->id }})">
                        <i class="bi bi-files me-1"></i> Duplicate
                    </button>
                    <button class="btn btn-outline-{{ $product->is_active ? 'warning' : 'success' }}"
                            onclick="toggleStatus({{ $product->id }})">
                        <i class="bi bi-{{ $product->is_active ? 'eye-slash' : 'eye' }} me-1"></i>
                        {{ $product->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                    <button class="btn btn-outline-danger" onclick="deleteProduct({{ $product->id }})">
                        <i class="bi bi-trash me-1"></i> Delete Product
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
                    <h5 class="modal-title" id="imageModalTitle">Product Image</h5>
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

        function toggleStatus(productId) {
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
                        location.reload();
                    } else {
                        alert('Error updating product status');
                    }
                });
        }

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

        function deleteProduct(productId) {
            if (!confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
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

        function duplicateProduct(productId) {
            alert('Duplicate product feature coming soon!');
        }
    </script>
@endpush
