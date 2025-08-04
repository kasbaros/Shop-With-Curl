@extends('admin.layouts.app')

@section('title', 'Create User')
@section('page-title', 'Create New User')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0">Create New User</h2>
            <p class="text-muted mb-0">Add a new customer or admin user</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Users
            </a>
        </div>
    </div>

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

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
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                   id="phone" name="phone" value="{{ old('phone') }}">
                            @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">Role *</label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                <option value="">Select Role</option>
                                <option value="customer" {{ old('role') === 'customer' ? 'selected' : '' }}>Customer</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                @if(auth()->user()->isDeveloper())
                                    <option value="developer" {{ old('role') === 'developer' ? 'selected' : '' }}>Developer</option>
                                @endif
                            </select>
                            @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Password Information -->
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Password Information</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password *</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" required minlength="8">
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Minimum 8 characters</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password *</label>
                            <input type="password" class="form-control"
                                   id="password_confirmation" name="password_confirmation" required minlength="8">
                            <div class="form-text">Must match the password above</div>
                        </div>
                    </div>
                </div>

                <!-- Personal Information (Optional) -->
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Personal Information <small class="text-muted">(Optional)</small></h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                                   id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}"
                                   max="{{ now()->subYears(13)->format('Y-m-d') }}">
                            @error('date_of_birth')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Save Actions -->
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Save User</h5>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Create User
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i> Cancel
                        </a>
                    </div>

                    <hr>

                    <h6 class="mb-2">Account Options</h6>

                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="email_verified" name="email_verified" value="1" checked>
                        <label class="form-check-label" for="email_verified">
                            Mark email as verified
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="send_welcome_email" name="send_welcome_email" value="1" checked>
                        <label class="form-check-label" for="send_welcome_email">
                            Send welcome email
                        </label>
                    </div>
                </div>

                <!-- Role Information -->
                <div class="stat-card p-4 mb-4">
                    <h5 class="mb-3">Role Permissions</h5>

                    <div id="role-info">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Select a role to see permissions
                        </div>
                    </div>
                </div>

                <!-- Tips -->
                <div class="stat-card p-4 bg-light">
                    <h6 class="mb-2">Tips</h6>
                    <ul class="small mb-0">
                        <li><strong>Customer:</strong> Can place orders and manage their account</li>
                        <li><strong>Admin:</strong> Can manage products, orders, and customers</li>
                        <li><strong>Developer:</strong> Full system access including settings</li>
                        <li><strong>Password:</strong> User will be able to change it after first login</li>
                        <li><strong>Verification:</strong> Unverified users cannot login</li>
                    </ul>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        // Role information display
        document.getElementById('role').addEventListener('change', function() {
            const roleInfo = document.getElementById('role-info');
            const role = this.value;

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
        });

        // Password confirmation validation
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmation = this.value;

            if (password && confirmation) {
                if (password === confirmation) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else {
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                }
            } else {
                this.classList.remove('is-valid', 'is-invalid');
            }
        });

        // Generate strong password
        function generatePassword() {
            const length = 12;
            const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
            let password = "";
            for (let i = 0, n = charset.length; i < length; ++i) {
                password += charset.charAt(Math.floor(Math.random() * n));
            }

            document.getElementById('password').value = password;
            document.getElementById('password_confirmation').value = password;

            // Trigger validation
            document.getElementById('password_confirmation').dispatchEvent(new Event('input'));
        }

        // Add generate password button
        document.addEventListener('DOMContentLoaded', function() {
            const passwordField = document.getElementById('password');
            const generateBtn = document.createElement('button');
            generateBtn.type = 'button';
            generateBtn.className = 'btn btn-outline-secondary btn-sm mt-2';
            generateBtn.innerHTML = '<i class="bi bi-shuffle me-1"></i> Generate Strong Password';
            generateBtn.onclick = generatePassword;

            passwordField.parentNode.appendChild(generateBtn);
        });
    </script>
@endpush
