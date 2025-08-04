@extends('admin.layouts.app')

@section('title', 'Coupons Management')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Coupons Management</h1>
            <p class="text-muted">Manage discount codes and promotional campaigns</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary btn-admin">
            <i class="bi bi-plus-circle"></i> Create Coupon
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card p-4">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-primary text-white rounded-circle me-3" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-percent"></i>
                    </div>
                    <div>
                        <div class="stat-value h4 mb-0">{{ $stats['total'] }}</div>
                        <div class="stat-label text-muted small">Total Coupons</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card p-4">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-success text-white rounded-circle me-3" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <div class="stat-value h4 mb-0">{{ $stats['active'] }}</div>
                        <div class="stat-label text-muted small">Active Coupons</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card p-4">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-warning text-white rounded-circle me-3" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div>
                        <div class="stat-value h4 mb-0">{{ $stats['expired'] }}</div>
                        <div class="stat-label text-muted small">Expired Coupons</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card p-4">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-info text-white rounded-circle me-3" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <div>
                        <div class="stat-value h4 mb-0">{{ $stats['total_usage'] }}</div>
                        <div class="stat-label text-muted small">Total Uses</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="table-admin mb-4">
        <div class="p-3 border-bottom">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="search" placeholder="Search coupons..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="percentage" {{ request('type') === 'percentage' ? 'selected' : '' }}>Percentage</option>
                        <option value="fixed_amount" {{ request('type') === 'fixed_amount' ? 'selected' : '' }}>Fixed Amount</option>
                        <option value="free_shipping" {{ request('type') === 'free_shipping' ? 'selected' : '' }}>Free Shipping</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="upcoming" {{ request('status') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-outline-primary btn-admin me-2">Filter</button>
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary btn-admin">Clear</a>
                </div>
                <div class="col-md-2 text-end">
                    <button type="button" class="btn btn-outline-danger btn-admin" id="bulkActionBtn" style="display: none;">
                        Bulk Actions
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Coupons Table -->
    <div class="table-admin">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                <tr>
                    <th width="40">
                        <input type="checkbox" id="selectAll" class="form-check-input">
                    </th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Value</th>
                    <th>Usage</th>
                    <th>Status</th>
                    <th>Expires</th>
                    <th width="120">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($coupons as $coupon)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input coupon-checkbox" value="{{ $coupon->id }}">
                        </td>
                        <td>
                            <div class="fw-bold">{{ $coupon->code }}</div>
                            <small class="text-muted">ID: {{ $coupon->id }}</small>
                        </td>
                        <td>
                            <div>{{ $coupon->name }}</div>
                            @if($coupon->description)
                                <small class="text-muted">{{ Str::limit($coupon->description, 40) }}</small>
                            @endif
                        </td>
                        <td>
                            @switch($coupon->type)
                                @case('percentage')
                                    <span class="badge bg-primary">Percentage</span>
                                    @break
                                @case('fixed_amount')
                                    <span class="badge bg-success">Fixed Amount</span>
                                    @break
                                @case('free_shipping')
                                    <span class="badge bg-info">Free Shipping</span>
                                    @break
                            @endswitch
                        </td>
                        <td>
                            @if($coupon->type === 'percentage')
                                {{ number_format($coupon->value * 100, 1) }}%
                            @elseif($coupon->type === 'fixed_amount')
                                ${{ number_format($coupon->value, 2) }}
                            @else
                                Free Shipping
                            @endif
                        </td>
                        <td>
                            <div>{{ $coupon->used_count }}{{ $coupon->usage_limit ? '/' . $coupon->usage_limit : '' }}</div>
                            @if($coupon->usage_limit)
                                <div class="progress mt-1" style="height: 4px;">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{ ($coupon->used_count / $coupon->usage_limit) * 100 }}%"></div>
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($coupon->is_active)
                                @if($coupon->expires_at && $coupon->expires_at->isPast())
                                    <span class="badge bg-danger">Expired</span>
                                @elseif($coupon->starts_at && $coupon->starts_at->isFuture())
                                    <span class="badge bg-warning">Scheduled</span>
                                @else
                                    <span class="badge bg-success">Active</span>
                                @endif
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            @if($coupon->expires_at)
                                <div>{{ $coupon->expires_at->format('M j, Y') }}</div>
                                <small class="text-muted">{{ $coupon->expires_at->diffForHumans() }}</small>
                            @else
                                <span class="text-muted">Never</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.coupons.show', $coupon) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.coupons.toggle', $coupon) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-{{ $coupon->is_active ? 'warning' : 'success' }}"
                                            title="{{ $coupon->is_active ? 'Deactivate' : 'Activate' }}">
                                        <i class="bi bi-{{ $coupon->is_active ? 'pause' : 'play' }}"></i>
                                    </button>
                                </form>
                                @if($coupon->used_count === 0)
                                    <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this coupon?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-percent" style="font-size: 2rem;"></i>
                                <div class="mt-2">No coupons found.</div>
                                <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary btn-admin mt-2">
                                    Create Your First Coupon
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($coupons->hasPages())
            <div class="p-3 border-top">
                {{ $coupons->withQueryString()->links() }}
            </div>
        @endif
    </div>

    <!-- Bulk Actions Modal -->
    <div class="modal fade" id="bulkActionsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="bulkActionsForm" method="POST" action="{{ route('admin.coupons.bulk-action') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Bulk Actions</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Action</label>
                            <select name="action" class="form-select" required>
                                <option value="">Choose action...</option>
                                <option value="activate">Activate Selected</option>
                                <option value="deactivate">Deactivate Selected</option>
                                <option value="delete">Delete Selected (unused only)</option>
                            </select>
                        </div>
                        <div class="alert alert-info">
                            <span id="selectedCount">0</span> coupon(s) selected
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Apply Action</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.coupon-checkbox');
            const bulkActionBtn = document.getElementById('bulkActionBtn');
            const selectedCount = document.getElementById('selectedCount');

            function updateBulkActions() {
                const checked = document.querySelectorAll('.coupon-checkbox:checked');
                bulkActionBtn.style.display = checked.length > 0 ? 'block' : 'none';
                selectedCount.textContent = checked.length;
            }

            selectAll.addEventListener('change', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = selectAll.checked;
                });
                updateBulkActions();
            });

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateBulkActions);
            });

            bulkActionBtn.addEventListener('click', function() {
                const modal = new bootstrap.Modal(document.getElementById('bulkActionsModal'));
                modal.show();
            });

            document.getElementById('bulkActionsForm').addEventListener('submit', function(e) {
                const checked = document.querySelectorAll('.coupon-checkbox:checked');
                checked.forEach(checkbox => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'coupon_ids[]';
                    input.value = checkbox.value;
                    this.appendChild(input);
                });
            });
        });
    </script>
@endpush
