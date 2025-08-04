@extends('admin.layouts.app')

@section('title', 'Users')
@section('page-title', 'Users Management')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0">Users Management</h2>
            <p class="text-muted mb-0">Manage customers and admin users</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary" onclick="exportUsers()">
                <i class="bi bi-download me-1"></i> Export
            </button>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-admin">
                <i class="bi bi-plus-circle me-1"></i> Add User
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Users</h6>
                        <h4 class="mb-0">{{ number_format($stats['total_users']) }}</h4>
                    </div>
                    <div class="stat-icon bg-primary">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Customers</h6>
                        <h4 class="mb-0 text-success">{{ number_format($stats['customers']) }}</h4>
                    </div>
                    <div class="stat-icon bg-success">
                        <i class="bi bi-person-check"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">New This Week</h6>
                        <h4 class="mb-0 text-info">{{ number_format($stats['new_users_week']) }}</h4>
                    </div>
                    <div class="stat-icon bg-info">
                        <i class="bi bi-person-plus"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Unverified</h6>
                        <h4 class="mb-0 text-warning">{{ number_format($stats['unverified_users']) }}</h4>
                    </div>
                    <div class="stat-icon bg-warning">
                        <i class="bi bi-person-exclamation"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="stat-card p-3 mb-4">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="search" class="form-label">Search</label>
                <input type="text" class="form-control" id="search" name="search"
                       placeholder="Name, email, phone..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role">
                    <option value="">All Roles</option>
                    <option value="customer" {{ request('role') === 'customer' ? 'selected' : '' }}>Customer</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="developer" {{ request('role') === 'developer' ? 'selected' : '' }}>Developer</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="date_from" class="form-label">From Date</label>
                <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label for="date_to" class="form-label">To Date</label>
                <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-1">
                <div class="d-flex gap-1">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-funnel"></i>
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="table-admin">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                <tr>
                    <th>
                        <input type="checkbox" class="form-check-input" id="selectAll">
                    </th>
                    <th>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                           class="text-decoration-none text-dark">
                            User
                            @if(request('sort') === 'name')
                                <i class="bi bi-chevron-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </a>
                    </th>
                    <th>Contact</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'orders_count', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                           class="text-decoration-none text-dark">
                            Orders
                            @if(request('sort') === 'orders_count')
                                <i class="bi bi-chevron-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                           class="text-decoration-none text-dark">
                            Joined
                            @if(request('sort') === 'created_at')
                                <i class="bi bi-chevron-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </a>
                    </th>
                    <th width="120">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input user-checkbox" value="{{ $user->id }}">
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary rounded-circle text-white d-flex align-items-center justify-content-center me-3"
                                     style="width: 40px; height: 40px;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $user->name }}</h6>
                                    <small class="text-muted">ID: {{ $user->id }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $user->email }}</strong>
                                @if($user->phone)
                                    <br><small class="text-muted">{{ $user->phone }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            @switch($user->role)
                                @case('customer')
                                    <span class="badge bg-success">Customer</span>
                                    @break
                                @case('admin')
                                    <span class="badge bg-primary">Admin</span>
                                    @break
                                @case('developer')
                                    <span class="badge bg-dark">Developer</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                            @endswitch
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm border-0 p-0 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    @if($user->email_verified_at)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-warning">Inactive</span>
                                    @endif
                                </button>
                                <ul class="dropdown-menu">
                                    <li><button class="dropdown-item" onclick="toggleStatus({{ $user->id }})">
                                            {{ $user->email_verified_at ? 'Deactivate' : 'Activate' }}
                                        </button></li>
                                </ul>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $user->orders_count ?? 0 }}</span>
                            @if(($user->orders_count ?? 0) > 0)
                                <div class="small text-muted">
                                    Last: {{ $user->orders()->latest()->first()?->created_at?->format('M j') }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <div>{{ $user->created_at->format('M j, Y') }}</div>
                            <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('admin.users.show', $user) }}">
                                            <i class="bi bi-eye me-2"></i>View Profile
                                        </a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.users.edit', $user) }}">
                                            <i class="bi bi-pencil me-2"></i>Edit
                                        </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><button class="dropdown-item" onclick="resetPassword({{ $user->id }}, '{{ $user->name }}')">
                                            <i class="bi bi-key me-2"></i>Reset Password
                                        </button></li>
                                    <li><button class="dropdown-item" onclick="sendEmail({{ $user->id }})">
                                            <i class="bi bi-envelope me-2"></i>Send Email
                                        </button></li>
                                    @if(auth()->user()->isDeveloper() && $user->id !== auth()->id())
                                        <li><button class="dropdown-item" onclick="impersonateUser({{ $user->id }}, '{{ $user->name }}')">
                                                <i class="bi bi-person-circle me-2"></i>Impersonate
                                            </button></li>
                                    @endif
                                    @if($user->orders_count === 0 && $user->id !== auth()->id())
                                        <li><hr class="dropdown-divider"></li>
                                        <li><button class="dropdown-item text-danger" onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')">
                                                <i class="bi bi-trash me-2"></i>Delete
                                            </button></li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-people mb-3" style="font-size: 3rem;"></i>
                                <h5>No users found</h5>
                                <p>No users match your current filters.</p>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-arrow-clockwise me-1"></i> Reset Filters
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-3 border-top">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Bulk Actions -->
    <div class="mt-3" id="bulkActions" style="display: none;">
        <div class="stat-card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <span><span id="selectedCount">0</span> users selected</span>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-success btn-sm" onclick="bulkAction('activate')">
                        <i class="bi bi-check-circle me-1"></i> Activate
                    </button>
                    <button class="btn btn-outline-warning btn-sm" onclick="bulkAction('deactivate')">
                        <i class="bi bi-x-circle me-1"></i> Deactivate
                    </button>
                    <button class="btn btn-outline-danger btn-sm" onclick="bulkAction('delete')">
                        <i class="bi bi-trash me-1"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reset Password Modal -->
    <div class="modal fade" id="resetPasswordModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reset Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="resetPasswordForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="newPassword" name="password" required minlength="8">
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="confirmPassword" name="password_confirmation" required>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="sendEmail" name="send_email" value="1">
                            <label class="form-check-label" for="sendEmail">
                                Send password reset email to user
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let selectedUsers = [];
        let currentUserId = null;

        // Select All functionality
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });

        // Individual checkbox functionality
        document.querySelectorAll('.user-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActions);
        });

        function updateBulkActions() {
            const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
            selectedUsers = Array.from(checkedBoxes).map(cb => parseInt(cb.value));

            const bulkActions = document.getElementById('bulkActions');
            const selectedCount = document.getElementById('selectedCount');

            if (selectedUsers.length > 0) {
                bulkActions.style.display = 'block';
                selectedCount.textContent = selectedUsers.length;
            } else {
                bulkActions.style.display = 'none';
            }
        }

        // Toggle User Status
        function toggleStatus(userId) {
            fetch(`/admin/users/${userId}/toggle-status`, {
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
                        alert('Error updating user status');
                    }
                });
        }

        // Reset Password
        function resetPassword(userId, userName) {
            currentUserId = userId;
            document.querySelector('#resetPasswordModal .modal-title').textContent = `Reset Password for ${userName}`;
            new bootstrap.Modal(document.getElementById('resetPasswordModal')).show();
        }

        document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch(`/admin/users/${currentUserId}/reset-password`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('resetPasswordModal')).hide();
                        alert('Password reset successfully!');
                        this.reset();
                    } else {
                        alert('Error resetting password');
                    }
                });
        });

        // Send Email
        function sendEmail(userId) {
            if (!confirm('Send a custom email to this user?')) {
                return;
            }

            // This would open an email composition modal
            alert('Email composition feature would be implemented here');
        }

        // Impersonate User (Developer only)
        function impersonateUser(userId, userName) {
            if (!confirm(`Impersonate ${userName}? You will be logged in as this user.`)) {
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/users/${userId}/impersonate`;
            form.innerHTML = `
        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
    `;
            document.body.appendChild(form);
            form.submit();
        }

        // Delete User
        function deleteUser(userId, userName) {
            if (!confirm(`Are you sure you want to delete ${userName}?\n\nThis action cannot be undone.`)) {
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/users/${userId}`;
            form.innerHTML = `
        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
        <input type="hidden" name="_method" value="DELETE">
    `;
            document.body.appendChild(form);
            form.submit();
        }

        // Bulk Actions
        function bulkAction(action) {
            if (selectedUsers.length === 0) {
                alert('Please select users first');
                return;
            }

            let message = '';
            switch(action) {
                case 'activate':
                    message = `Activate ${selectedUsers.length} selected users?`;
                    break;
                case 'deactivate':
                    message = `Deactivate ${selectedUsers.length} selected users?`;
                    break;
                case 'delete':
                    message = `Delete ${selectedUsers.length} selected users?\n\nThis action cannot be undone.`;
                    break;
            }

            if (!confirm(message)) {
                return;
            }

            fetch('/admin/users/bulk-action', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    action: action,
                    users: selectedUsers
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

        // Export Users
        function exportUsers() {
            const params = new URLSearchParams(window.location.search);
            params.append('export', 'csv');
            window.location.href = '/admin/users?' + params.toString();
        }
    </script>
@endpush
