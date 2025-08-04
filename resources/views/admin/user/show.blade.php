@extends('admin.layouts.app')

@section('title', $user->name . ' - User Profile')
@section('page-title', 'User Profile')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0">{{ $user->name }}</h2>
            <p class="text-muted mb-0">{{ ucfirst($user->role) }} • Member since {{ $user->created_at->format('M Y') }}</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary" onclick="resetPassword()">
                <i class="bi bi-key me-1"></i> Reset Password
            </button>
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary btn-admin">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            @if(auth()->user()->isDeveloper() && $user->id !== auth()->id())
                <button class="btn btn-outline-warning" onclick="impersonateUser()">
                    <i class="bi bi-person-circle me-1"></i> Impersonate
                </button>
            @endif
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- User Information -->
            <div class="stat-card p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">User Information</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm dropdown-toggle border-0" type="button" data-bs-toggle="dropdown">
                            @if($user->email_verified_at)
                                <span class="badge bg-success fs-6">Active</span>
                            @else
                                <span class="badge bg-warning fs-6">Inactive</span>
                            @endif
                        </button>
                        <ul class="dropdown-menu">
                            <li><button class="dropdown-item" onclick="toggleStatus()">
                                    {{ $user->email_verified_at ? 'Deactivate Account' : 'Activate Account' }}
                                </button></li>
                        </ul>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Full Name</label>
                            <div class="fw-bold">{{ $user->name }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted">Email Address</label>
                            <div class="fw-bold">
                                <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                @if($user->email_verified_at)
                                    <i class="bi bi-patch-check-fill text-success ms-1" title="Verified"></i>
                                @else
                                    <i class="bi bi-exclamation-triangle-fill text-warning ms-1" title="Unverified"></i>
                                @endif
                            </div>
                        </div>

                        @if($user->phone)
                            <div class="mb-3">
                                <label class="form-label text-muted">Phone Number</label>
                                <div class="fw-bold">
                                    <a href="tel:{{ $user->phone }}">{{ $user->phone }}</a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Role</label>
                            <div>
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
                            </div>
                        </div>

                        @if($user->date_of_birth)
                            <div class="mb-3">
                                <label class="form-label text-muted">Date of Birth</label>
                                <div class="fw-bold">{{ $user->date_of_birth->format('M j, Y') }} ({{ $user->date_of_birth->age }} years old)</div>
                            </div>
                        @endif

                        @if($user->gender)
                            <div class="mb-3">
                                <label class="form-label text-muted">Gender</label>
                                <div class="fw-bold">{{ ucfirst($user->gender) }}</div>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label text-muted">Last Login</label>
                            <div class="fw-bold">
                                @if($user->last_login_at)
                                    {{ $user->last_login_at->format('M j, Y g:i A') }}
                                    <small class="text-muted d-block">{{ $user->last_login_at->diffForHumans() }}</small>
                                @else
                                    <span class="text-muted">Never</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="stat-card p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Recent Orders</h5>
                    @if($user->orders->count() > 0)
                        <a href="{{ route('admin.orders.index', ['search' => $user->email]) }}" class="btn btn-outline-primary btn-sm">
                            View All Orders
                        </a>
                    @endif
                </div>

                @if($user->orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($user->orders as $order)
                                <tr>
                                    <td><strong>{{ $order->order_number }}</strong></td>
                                    <td>{{ $order->created_at->format('M j, Y') }}</td>
                                    <td>{{ $order->items->count() }} items</td>
                                    <td><strong>${{ number_format($order->total_amount, 2) }}</strong></td>
                                    <td>
                                        @switch($order->status)
                                            @case('pending')
                                                <span class="badge bg-warning">Pending</span>
                                                @break
                                            @case('processing')
                                                <span class="badge bg-info">Processing</span>
                                                @break
                                            @case('shipped')
                                                <span class="badge bg-primary">Shipped</span>
                                                @break
                                            @case('delivered')
                                                <span class="badge bg-success">Delivered</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-danger">Cancelled</span>
                                                @break
                                            @case('refunded')
                                                <span class="badge bg-secondary">Refunded</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-primary btn-sm">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-bag text-muted mb-3" style="font-size: 3rem;"></i>
                        <h6 class="text-muted">No orders yet</h6>
                        <p class="text-muted mb-0">This user hasn't placed any orders.</p>
                    </div>
                @endif
            </div>

            <!-- User Addresses -->
            @if($user->addresses->count() > 0)
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Addresses ({{ $user->addresses->count() }})</h5>

                    <div class="row">
                        @foreach($user->addresses as $address)
                            <div class="col-md-6 mb-3">
                                <div class="border rounded p-3 {{ $address->is_default ? 'border-primary bg-light' : '' }}">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="mb-0">
                                            {{ ucfirst($address->type) }} Address
                                            @if($address->is_default)
                                                <span class="badge bg-primary ms-1">Default</span>
                                            @endif
                                        </h6>
                                    </div>
                                    <address class="mb-0 small">
                                        <strong>{{ $address->first_name }} {{ $address->last_name }}</strong><br>
                                        @if($address->company)
                                            {{ $address->company }}<br>
                                        @endif
                                        {{ $address->address_line_1 }}<br>
                                        @if($address->address_line_2)
                                            {{ $address->address_line_2 }}<br>
                                        @endif
                                        {{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}<br>
                                        {{ $address->country }}
                                        @if($address->phone)
                                            <br><strong>Phone:</strong> {{ $address->phone }}
                                        @endif
                                    </address>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Recent Reviews -->
            @if($user->reviews->count() > 0)
                <div class="stat-card p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Recent Reviews ({{ $user->reviews->count() }})</h5>
                        <a href="{{ route('admin.reviews.index', ['search' => $user->email]) }}" class="btn btn-outline-primary btn-sm">
                            View All Reviews
                        </a>
                    </div>

                    @foreach($user->reviews as $review)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="mb-1">
                                        <a href="{{ route('admin.products.show', $review->product) }}">{{ $review->product->name }}</a>
                                    </h6>
                                    <div class="text-warning mb-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <i class="bi bi-star-fill"></i>
                                            @else
                                                <i class="bi bi-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <div class="text-end">
                                    @if($review->is_approved)
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                    <div class="small text-muted">{{ $review->created_at->format('M j, Y') }}</div>
                                </div>
                            </div>
                            <p class="mb-0 small">{{ Str::limit($review->review, 200) }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- User Statistics -->
            <div class="stat-card p-4 mb-4">
                <h5 class="mb-3">Statistics</h5>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Total Orders</span>
                    <strong class="text-primary">{{ number_format($userStats['total_orders']) }}</strong>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Total Spent</span>
                    <strong class="text-success">${{ number_format($userStats['total_spent'], 2) }}</strong>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Average Order</span>
                    <strong class="text-info">${{ number_format($userStats['average_order'], 2) }}</strong>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Cancelled Orders</span>
                    <strong class="text-danger">{{ number_format($userStats['cancelled_orders']) }}</strong>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Total Reviews</span>
                    <strong class="text-warning">{{ number_format($userStats['total_reviews']) }}</strong>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <span>Approved Reviews</span>
                    <strong class="text-success">{{ number_format($userStats['approved_reviews']) }}</strong>
                </div>
            </div>

            <!-- Account Details -->
            <div class="stat-card p-4 mb-4">
                <h5 class="mb-3">Account Details</h5>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>User ID</span>
                    <strong>{{ $user->id }}</strong>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Joined</span>
                    <span>{{ $user->created_at->format('M j, Y') }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Last Updated</span>
                    <span>{{ $user->updated_at->format('M j, Y') }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Email Verified</span>
                    @if($user->email_verified_at)
                        <span class="text-success">
                        <i class="bi bi-check-circle"></i> Yes
                    </span>
                    @else
                        <span class="text-warning">
                        <i class="bi bi-x-circle"></i> No
                    </span>
                    @endif
                </div>

                @if($user->last_login_ip)
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Last IP</span>
                        <span class="font-monospace">{{ $user->last_login_ip }}</span>
                    </div>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="stat-card p-4">
                <h5 class="mb-3">Quick Actions</h5>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil me-1"></i> Edit Profile
                    </a>
                    <button class="btn btn-outline-secondary btn-sm" onclick="resetPassword()">
                        <i class="bi bi-key me-1"></i> Reset Password
                    </button>
                    <button class="btn btn-outline-info btn-sm" onclick="sendEmail()">
                        <i class="bi bi-envelope me-1"></i> Send Email
                    </button>
                    <button class="btn btn-outline-{{ $user->email_verified_at ? 'warning' : 'success' }} btn-sm" onclick="toggleStatus()">
                        <i class="bi bi-{{ $user->email_verified_at ? 'x-circle' : 'check-circle' }} me-1"></i>
                        {{ $user->email_verified_at ? 'Deactivate' : 'Activate' }}
                    </button>
                    @if(auth()->user()->isDeveloper() && $user->id !== auth()->id())
                        <button class="btn btn-outline-warning btn-sm" onclick="impersonateUser()">
                            <i class="bi bi-person-circle me-1"></i> Impersonate
                        </button>
                    @endif
                    @if($userStats['total_orders'] === 0 && $user->id !== auth()->id())
                        <hr>
                        <button class="btn btn-outline-danger btn-sm" onclick="deleteUser()">
                            <i class="bi bi-trash me-1"></i> Delete Account
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Reset Password Modal -->
    <div class="modal fade" id="resetPasswordModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reset Password for {{ $user->name }}</h5>
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
                            <input class="form-check-input" type="checkbox" id="sendEmail" name="send_email" value="1" checked>
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
        function toggleStatus() {
            if (!confirm('Are you sure you want to {{ $user->email_verified_at ? "deactivate" : "activate" }} this user?')) {
                return;
            }

            fetch(`/admin/users/{{ $user->id }}/toggle-status`, {
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

        function resetPassword() {
            new bootstrap.Modal(document.getElementById('resetPasswordModal')).show();
        }

        document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch(`/admin/users/{{ $user->id }}/reset-password`, {
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

        function sendEmail() {
            alert('Email composition feature would be implemented here');
        }

        function impersonateUser() {
            if (!confirm(`Impersonate {{ $user->name }}? You will be logged in as this user.`)) {
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/users/{{ $user->id }}/impersonate`;
            form.innerHTML = `
        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
    `;
            document.body.appendChild(form);
            form.submit();
        }

        function deleteUser() {
            if (!confirm(`Are you sure you want to delete {{ $user->name }}?\n\nThis action cannot be undone.`)) {
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/users/{{ $user->id }}`;
            form.innerHTML = `
        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
        <input type="hidden" name="_method" value="DELETE">
    `;
            document.body.appendChild(form);
            form.submit();
        }
    </script>
@endpush
