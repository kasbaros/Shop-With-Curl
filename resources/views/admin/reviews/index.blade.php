@extends('admin.layouts.app')

@section('title', 'Reviews Management')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Reviews Management</h1>
            <p class="text-muted">Manage customer reviews and ratings</p>
        </div>
        <div>
            <a href="{{ route('admin.reviews.analytics') }}" class="btn btn-outline-info btn-admin me-2">
                <i class="bi bi-graph-up"></i> Analytics
            </a>
            <button class="btn btn-outline-secondary btn-admin" id="bulkActionsToggle" disabled>
                <i class="bi bi-list-check"></i> Bulk Actions
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="table-admin">
                <div class="p-4 text-center">
                    <div class="h2 mb-1 text-primary">{{ $stats['total'] }}</div>
                    <div class="text-muted small">Total Reviews</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="table-admin">
                <div class="p-4 text-center">
                    <div class="h2 mb-1 text-success">{{ $stats['approved'] }}</div>
                    <div class="text-muted small">Approved</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="table-admin">
                <div class="p-4 text-center">
                    <div class="h2 mb-1 text-warning">{{ $stats['pending'] }}</div>
                    <div class="text-muted small">Pending Approval</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="table-admin">
                <div class="p-4 text-center">
                    <div class="h2 mb-1 text-info">{{ number_format($stats['average_rating'], 1) }}</div>
                    <div class="text-muted small">Average Rating</div>
                    <div class="mt-1">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= $stats['average_rating'] ? '-fill' : '' }} text-warning"></i>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="table-admin mb-4">
        <div class="p-3">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search"
                           placeholder="Search reviews, products, customers..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="rating" class="form-label">Rating</label>
                    <select class="form-select" id="rating" name="rating">
                        <option value="">All Ratings</option>
                        @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="product_id" class="form-label">Product</label>
                    <select class="form-select" id="product_id" name="product_id">
                        <option value="">All Products</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions Panel -->
    <div class="table-admin mb-4" id="bulkActionsPanel" style="display: none;">
        <div class="p-3 bg-light">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span id="selectedCount">0</span> reviews selected
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-success" onclick="bulkAction('approve')">
                        <i class="bi bi-check-circle"></i> Approve
                    </button>
                    <button type="button" class="btn btn-sm btn-warning" onclick="bulkAction('reject')">
                        <i class="bi bi-x-circle"></i> Reject
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="bulkAction('delete')">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearSelection()">
                        Clear Selection
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Table -->
    <div class="table-admin">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                <tr>
                    <th width="40">
                        <input type="checkbox" id="selectAll" class="form-check-input">
                    </th>
                    <th>Review</th>
                    <th>Product</th>
                    <th>Customer</th>
                    <th>Rating</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th width="120">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($reviews as $review)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input review-checkbox" value="{{ $review->id }}">
                        </td>
                        <td>
                            <div class="d-flex align-items-start">
                                <div class="flex-grow-1">
                                    @if($review->title)
                                        <div class="fw-medium mb-1">{{ Str::limit($review->title, 50) }}</div>
                                    @endif
                                    <div class="text-muted small">
                                        {{ Str::limit($review->comment, 100) }}
                                    </div>
                                    @if($review->is_verified_purchase)
                                        <span class="badge bg-success badge-sm mt-1">Verified Purchase</span>
                                    @endif
                                    @if($review->replies->count() > 0)
                                        <span class="badge bg-info badge-sm mt-1">{{ $review->replies->count() }} Replies</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($review->product->images->first())
                                    <img src="{{ Storage::url($review->product->images->first()->image_path) }}"
                                         class="rounded me-2" width="40" height="40" style="object-fit: cover;">
                                @else
                                    <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center"
                                         style="width: 40px; height: 40px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                                <div>
                                    <a href="{{ route('admin.products.show', $review->product) }}"
                                       class="text-decoration-none">
                                        {{ Str::limit($review->product->name, 30) }}
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($review->user)
                                <div>
                                    <a href="{{ route('admin.users.show', $review->user) }}"
                                       class="text-decoration-none">
                                        {{ $review->user->name }}
                                    </a>
                                </div>
                                <small class="text-muted">{{ $review->user->email }}</small>
                            @else
                                <span class="text-muted">Anonymous</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="me-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }} text-warning"></i>
                                    @endfor
                                </div>
                                <span class="badge bg-secondary">{{ $review->rating }}</span>
                            </div>
                            @if($review->helpful_count > 0 || $review->unhelpful_count > 0)
                                <small class="text-muted d-block mt-1">
                                    {{ $review->helpful_count }} helpful, {{ $review->unhelpful_count }} unhelpful
                                </small>
                            @endif
                        </td>
                        <td>
                            @if($review->is_approved)
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </td>
                        <td>
                            <div>{{ $review->created_at->format('M j, Y') }}</div>
                            <small class="text-muted">{{ $review->created_at->format('g:i A') }}</small>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                        type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.reviews.show', $review) }}">
                                            <i class="bi bi-eye me-2"></i>View Details
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    @if(!$review->is_approved)
                                        <li>
                                            <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="dropdown-item text-success">
                                                    <i class="bi bi-check-circle me-2"></i>Approve
                                                </button>
                                            </form>
                                        </li>
                                    @else
                                        <li>
                                            <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="dropdown-item text-warning">
                                                    <i class="bi bi-x-circle me-2"></i>Reject
                                                </button>
                                            </form>
                                        </li>
                                    @endif
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                                              class="d-inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-trash me-2"></i>Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-star display-1 d-block mb-3"></i>
                                <h5>No Reviews Found</h5>
                                <p>No reviews match your current filters.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($reviews->hasPages())
            <div class="p-3 border-top">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const reviewCheckboxes = document.querySelectorAll('.review-checkbox');
            const bulkActionsPanel = document.getElementById('bulkActionsPanel');
            const bulkActionsToggle = document.getElementById('bulkActionsToggle');
            const selectedCountSpan = document.getElementById('selectedCount');

            function updateBulkActions() {
                const checkedBoxes = document.querySelectorAll('.review-checkbox:checked');
                const count = checkedBoxes.length;

                selectedCountSpan.textContent = count;
                bulkActionsToggle.disabled = count === 0;

                if (count > 0) {
                    bulkActionsPanel.style.display = 'block';
                } else {
                    bulkActionsPanel.style.display = 'none';
                }

                selectAllCheckbox.indeterminate = count > 0 && count < reviewCheckboxes.length;
                selectAllCheckbox.checked = count === reviewCheckboxes.length;
            }

            selectAllCheckbox.addEventListener('change', function() {
                reviewCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkActions();
            });

            reviewCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateBulkActions);
            });

            bulkActionsToggle.addEventListener('click', function() {
                const checkedBoxes = document.querySelectorAll('.review-checkbox:checked');
                if (checkedBoxes.length > 0) {
                    bulkActionsPanel.style.display = bulkActionsPanel.style.display === 'none' ? 'block' : 'none';
                }
            });

            updateBulkActions();
        });

        function bulkAction(action) {
            const checkedBoxes = document.querySelectorAll('.review-checkbox:checked');
            const reviewIds = Array.from(checkedBoxes).map(cb => cb.value);

            if (reviewIds.length === 0) {
                alert('Please select at least one review.');
                return;
            }

            let message = '';
            switch (action) {
                case 'approve':
                    message = `Are you sure you want to approve ${reviewIds.length} review(s)?`;
                    break;
                case 'reject':
                    message = `Are you sure you want to reject ${reviewIds.length} review(s)?`;
                    break;
                case 'delete':
                    message = `Are you sure you want to delete ${reviewIds.length} review(s)? This action cannot be undone.`;
                    break;
            }

            if (confirm(message)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.reviews.bulk-action") }}';

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = action;
                form.appendChild(actionInput);

                reviewIds.forEach(id => {
                    const idInput = document.createElement('input');
                    idInput.type = 'hidden';
                    idInput.name = 'review_ids[]';
                    idInput.value = id;
                    form.appendChild(idInput);
                });

                document.body.appendChild(form);
                form.submit();
            }
        }

        function clearSelection() {
            document.querySelectorAll('.review-checkbox:checked').forEach(checkbox => {
                checkbox.checked = false;
            });
            document.getElementById('selectAll').checked = false;
            document.getElementById('bulkActionsPanel').style.display = 'none';
            document.getElementById('bulkActionsToggle').disabled = true;
        }
    </script>
@endpush
