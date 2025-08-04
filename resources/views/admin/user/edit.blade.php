@extends('admin.layouts.app')

@section('title', 'Edit ' . $user->name)
@section('page-title', 'Edit User')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0">Edit User: {{ $user->name }}</h2>
            <p class="text-muted mb-0">{{ ucfirst($user->role) }} • ID: {{ $user->id }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-secondary">
                <i class="bi bi-eye me-1"></i> View Profile
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Users
            </a>
        </div>
    </div>

    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Main Form -->
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Basic Information</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                   id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">Role *</label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                <option value="">Select Role</option>
                                <option value="customer" {{ old('role', $user->role) === 'customer' ? 'selected' : '' }}>Customer</option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                @if(auth()->user()->isDeveloper())
                                    <option value="developer" {{ old('role', $user->role) === 'developer' ? 'selected' : '' }}>Developer</option>
                                @endif
                            </select>
                            @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Personal Information</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                                   id="date_of_birth" name="date_of_birth"
                                   value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}"
                                   max="{{ now()->subYears(13)->format('Y-m-d') }}">
                            @error('date_of_birth')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Account Status -->
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Account Status</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="email_verified" name="email_verified"
                                       value="1" {{ old('email_verified', $user->email_verified_at ? '1' : '0') === '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="email_verified">
                                    Email Verified
                                </label>
                            </div>
                            <div class="form-text">Verified users can login to their account</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                       value="1" {{ old('is_active', $user->email_verified_at ? '1' : '0') === '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Account Active
                                </label>
                            </div>
                            <div class="form-text">Active accounts can access the system</div>
                        </div>
                    </div>

                    @if($user->email_verified_at)
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>
                            Email verified on {{ $user->email_verified_at->format('M j, Y \a\t g:i A') }}
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Email not verified. User cannot login until verified.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Save Actions -->
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Save Changes</h5>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Update User
                        </button>
                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i> Cancel
                        </a>
                    </div>

                    <hr>

                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-warning btn-sm" onclick="resetPassword()">
                            <i class="bi bi-key me-1"></i> Reset Password
                        </button>
                        @if(auth()->user()->isDeveloper() && $user->id !== auth()->id())
                            <button type="button" class="btn btn-outline-info btn-sm" onclick="impersonateUser()">
                                <i class="bi bi-person-circle me-1"></i> Impersonate
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Current User Info -->
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Current Status</h5>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Status</span>
                        @if($user->email_verified_at)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-warning">Inactive</span>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Role</span>
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

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Member Since</span>
                        <span>{{ $user->created_at->format('M Y') }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Last Login</span>
                        <span>
                        @if($user->last_login_at)
                                {{ $user->last_login_at->diffForHumans() }}
                            @else
                                Never
                            @endif
                    </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <span>Total Orders</span>
                        <span class="badge bg-info">{{ $user->orders()->count() }}</span>
                    </div>
                </div>

                <!-- Role Information -->
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Role Permissions</h5>

                    <div id="role-info">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </div>

                <!-- Danger Zone -->
                @if($user->orders()->count() === 0 && $user->id !== auth()->id())
                    <div class="stat-card p-4 border-danger">
                        <h5 class="mb-3 text-danger">Danger Zone</h5>
                        <p class="small text-muted mb-3">
                            This user has no orders and can be safely deleted.
                        </p>
                        <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="deleteUser()">
                            <i class="bi bi-trash me-1"></i> Delete User Account
                        </button>
                    </div>
                @elseif($user->orders()->count() > 0)
                    <div class="stat-card p-4 bg-light">
                        <h6 class="mb-2">Cannot Delete</h6>
                        <p class="small mb-0">
                            This user has {{ $user->orders()->count() }} order(s) and cannot be deleted for data integrity.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </form>

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
        // Role information display
        function updateRoleInfo() {
            const roleInfo = document.getElementById('role-info');
            const role = document.getElementById('role').value;

            let content = '';

            switch(role) {
                case 'customer':
                    content = `
                <div class="alert alert-success">
                    <h6 class="mb-2"><i class="bi bi-person me-2"></i>Customer Role</h6>
                    <ul class="mb-0 small">
                        <li>Browse and purchase products</li>
                        <li>Manage personal profile</li>
                        <li>View order history</li>
                        <li>Write product reviews</li>
                        <li>Manage addresses and wishlist</li>
                    </ul>
                </div>
            `;
                    break;
                case 'admin':
                    content = `
                <div class="alert alert-primary">
                    <h6 class="mb-2"><i class="bi bi-shield-check me-2"></i>Admin Role</h6>
                    <ul class="mb-0 small">
                        <li>Manage products and categories</li>
                        <li>Process and manage orders</li>
                        <li>Manage customers</li>
                        <li>Handle reviews and coupons</li>
                        <li>View analytics and reports</li>
                    </ul>
                </div>
            `;
                    break;
                case 'developer':
                    content = `
                <div class="alert alert-dark">
                    <h6 class="mb-2"><i class="bi bi-code-slash me-2"></i>Developer Role</h6>
                    <ul class="mb-0 small">
                        <li>Full admin access</li>
                        <li>System configuration</li>
                        <li>User impersonation</li>
                        <li>Advanced settings</li>
                        <li>Database management</li>
                    </ul>
                </div>
            `;
                    break;
                default:
                    content = `
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Select a role to see permissions
                </div>
            `;
            }

            roleInfo.innerHTML = content;
        }

        // Initialize role info and add event listener
        document.addEventListener('DOMContentLoaded', function() {
            updateRoleInfo();
            document.getElementById('role').addEventListener('change', updateRoleInfo);
        });

        // Reset Password
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

        // Impersonate User
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

        // Delete User
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

        // Sync email verified with is_active
        document.getElementById('email_verified').addEventListener('change', function() {
            const isActiveCheckbox = document.getElementById('is_active');
            if (!this.checked) {
                isActiveCheckbox.checked = false;
            }
        });

        document.getElementById('is_active').addEventListener('change', function() {
            const emailVerifiedCheckbox = document.getElementById('email_verified');
            if (this.checked && !emailVerifiedCheckbox.checked) {
                emailVerifiedCheckbox.checked = true;
            }
        });
    </script>
@endpush
