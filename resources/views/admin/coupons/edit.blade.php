@extends('admin.layouts.app')

@section('title', 'Edit Coupon')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Edit Coupon</h1>
            <p class="text-muted">
                Editing: <strong>{{ $coupon->code }}</strong>
                @if($coupon->used_count > 0)
                    <span class="badge bg-warning ms-2">{{ $coupon->used_count }} uses</span>
                @endif
            </p>
        </div>
        <div>
            <a href="{{ route('admin.coupons.show', $coupon) }}" class="btn btn-outline-info btn-admin me-2">
                <i class="bi bi-eye"></i> View Details
            </a>
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary btn-admin">
                <i class="bi bi-arrow-left"></i> Back to Coupons
            </a>
        </div>
    </div>

    @if($coupon->used_count > 0)
        <div class="alert alert-warning mb-4">
            <i class="bi bi-exclamation-triangle"></i>
            <strong>Warning:</strong> This coupon has been used {{ $coupon->used_count }} time(s). Some changes may affect existing orders or future usage.
        </div>
    @endif

    <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST" id="couponForm">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="table-admin mb-4">
                    <div class="p-3 border-bottom">
                        <h5 class="mb-0">Basic Information</h5>
                    </div>
                    <div class="p-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="code" class="form-label">Coupon Code <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('code') is-invalid @enderror"
                                               id="code" name="code" value="{{ old('code', $coupon->code) }}" required
                                               style="text-transform: uppercase;"
                                            {{ $coupon->used_count > 0 ? 'readonly' : '' }}>
                                        @if($coupon->used_count == 0)
                                            <button type="button" class="btn btn-outline-secondary" id="generateCodeBtn">
                                                <i class="bi bi-arrow-clockwise"></i> Generate
                                            </button>
                                        @endif
                                    </div>
                                    @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($coupon->used_count > 0)
                                        <small class="form-text text-muted">Code cannot be changed after use</small>
                                    @else
                                        <small class="form-text text-muted">Use letters and numbers only</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Coupon Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', $coupon->name) }}" required>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3">{{ old('description', $coupon->description) }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Discount Configuration -->
                <div class="table-admin mb-4">
                    <div class="p-3 border-bottom">
                        <h5 class="mb-0">Discount Configuration</h5>
                        @if($coupon->used_count > 0)
                            <small class="text-warning">Changes may affect future usage</small>
                        @endif
                    </div>
                    <div class="p-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Discount Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('type') is-invalid @enderror"
                                            id="type" name="type" required
                                        {{ $coupon->used_count > 0 ? 'disabled' : '' }}>
                                        <option value="percentage" {{ old('type', $coupon->type) === 'percentage' ? 'selected' : '' }}>
                                            Percentage Discount
                                        </option>
                                        <option value="fixed_amount" {{ old('type', $coupon->type) === 'fixed_amount' ? 'selected' : '' }}>
                                            Fixed Amount Discount
                                        </option>
                                        <option value="free_shipping" {{ old('type', $coupon->type) === 'free_shipping' ? 'selected' : '' }}>
                                            Free Shipping
                                        </option>
                                    </select>
                                    @if($coupon->used_count > 0)
                                        <input type="hidden" name="type" value="{{ $coupon->type }}">
                                        <small class="form-text text-muted">Type cannot be changed after use</small>
                                    @endif
                                    @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3" id="valueGroup">
                                    <label for="value" class="form-label">
                                        <span id="valueLabel">Discount Value</span> <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="valuePrefix">$</span>
                                        <input type="number" class="form-control @error('value') is-invalid @enderror"
                                               id="value" name="value"
                                               value="{{ old('value', $coupon->type === 'percentage' ? $coupon->value * 100 : $coupon->value) }}"
                                               step="0.01" min="0" required>
                                        <span class="input-group-text" id="valueSuffix" style="display: none;">%</span>
                                    </div>
                                    @error('value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted" id="valueHelp">Enter the discount amount</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="minimum_amount" class="form-label">Minimum Order Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control @error('minimum_amount') is-invalid @enderror"
                                               id="minimum_amount" name="minimum_amount"
                                               value="{{ old('minimum_amount', $coupon->minimum_amount) }}"
                                               step="0.01" min="0">
                                    </div>
                                    @error('minimum_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3" id="maxDiscountGroup">
                                    <label for="maximum_discount" class="form-label">Maximum Discount Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control @error('maximum_discount') is-invalid @enderror"
                                               id="maximum_discount" name="maximum_discount"
                                               value="{{ old('maximum_discount', $coupon->maximum_discount) }}"
                                               step="0.01" min="0">
                                    </div>
                                    @error('maximum_discount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Usage Restrictions -->
                <div class="table-admin mb-4">
                    <div class="p-3 border-bottom">
                        <h5 class="mb-0">Usage Restrictions</h5>
                    </div>
                    <div class="p-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="usage_limit" class="form-label">Total Usage Limit</label>
                                    <input type="number" class="form-control @error('usage_limit') is-invalid @enderror"
                                           id="usage_limit" name="usage_limit"
                                           value="{{ old('usage_limit', $coupon->usage_limit) }}"
                                           min="{{ $coupon->used_count }}">
                                    @error('usage_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($coupon->used_count > 0)
                                        <small class="form-text text-muted">Minimum: {{ $coupon->used_count }} (already used)</small>
                                    @else
                                        <small class="form-text text-muted">Leave empty for unlimited usage</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="usage_limit_per_user" class="form-label">Usage Limit Per Customer</label>
                                    <input type="number" class="form-control @error('usage_limit_per_user') is-invalid @enderror"
                                           id="usage_limit_per_user" name="usage_limit_per_user"
                                           value="{{ old('usage_limit_per_user', $coupon->usage_limit_per_user) }}"
                                           min="1">
                                    @error('usage_limit_per_user')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Leave empty for unlimited per customer</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Restrictions -->
                <div class="table-admin mb-4">
                    <div class="p-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Product Restrictions</h5>
                            <small class="text-muted">Optional - Leave empty to apply to all products</small>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="applicable_products" class="form-label">Specific Products</label>
                                    <select class="form-select @error('applicable_products') is-invalid @enderror"
                                            id="applicable_products" name="applicable_products[]" multiple>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}"
                                                {{ in_array($product->id, old('applicable_products', $coupon->applicable_products ?? [])) ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('applicable_products')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="applicable_categories" class="form-label">Specific Categories</label>
                                    <select class="form-select @error('applicable_categories') is-invalid @enderror"
                                            id="applicable_categories" name="applicable_categories[]" multiple>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ in_array($category->id, old('applicable_categories', $coupon->applicable_categories ?? [])) ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('applicable_categories')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Current Status -->
                <div class="table-admin mb-4">
                    <div class="p-3 border-bottom">
                        <h5 class="mb-0">Current Status</h5>
                    </div>
                    <div class="p-4">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="text-center">
                                    <div class="h4 mb-0 text-primary">{{ $coupon->used_count }}</div>
                                    <small class="text-muted">Times Used</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    <div class="h4 mb-0 text-{{ $coupon->is_active ? 'success' : 'secondary' }}">
                                        {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                                    </div>
                                    <small class="text-muted">Status</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Schedule -->
                <div class="table-admin mb-4">
                    <div class="p-3 border-bottom">
                        <h5 class="mb-0">Schedule</h5>
                    </div>
                    <div class="p-4">
                        <div class="mb-3">
                            <label for="starts_at" class="form-label">Start Date & Time</label>
                            <input type="datetime-local" class="form-control @error('starts_at') is-invalid @enderror"
                                   id="starts_at" name="starts_at"
                                   value="{{ old('starts_at', $coupon->starts_at?->format('Y-m-d\TH:i')) }}">
                            @error('starts_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="expires_at" class="form-label">Expiry Date & Time</label>
                            <input type="datetime-local" class="form-control @error('expires_at') is-invalid @enderror"
                                   id="expires_at" name="expires_at"
                                   value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d\TH:i')) }}">
                            @error('expires_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="table-admin mb-4">
                    <div class="p-3 border-bottom">
                        <h5 class="mb-0">Status</h5>
                    </div>
                    <div class="p-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
                        <small class="form-text text-muted">Inactive coupons cannot be used by customers</small>
                    </div>
                </div>

                <!-- Preview -->
                <div class="table-admin mb-4">
                    <div class="p-3 border-bottom">
                        <h5 class="mb-0">Preview</h5>
                    </div>
                    <div class="p-4">
                        <div class="coupon-preview border rounded p-3 text-center bg-light">
                            <div class="coupon-code h4 mb-2 font-monospace" id="previewCode">{{ $coupon->code }}</div>
                            <div class="coupon-name text-muted mb-2" id="previewName">{{ $coupon->name }}</div>
                            <div class="coupon-discount h5 text-primary" id="previewDiscount">
                                @if($coupon->type === 'percentage')
                                    {{ number_format($coupon->value * 100, 1) }}% OFF
                                @elseif($coupon->type === 'fixed_amount')
                                    ${{ number_format($coupon->value, 2) }} OFF
                                @else
                                    FREE SHIPPING
                                @endif
                            </div>
                            <div class="coupon-expiry small text-muted" id="previewExpiry">
                                {{ $coupon->expires_at ? 'Valid until: ' . $coupon->expires_at->format('M j, Y') : 'Never expires' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="table-admin">
                    <div class="p-4">
                        <button type="submit" class="btn btn-primary btn-admin w-100 mb-2">
                            <i class="bi bi-check-circle"></i> Update Coupon
                        </button>
                        <a href="{{ route('admin.coupons.show', $coupon) }}" class="btn btn-outline-info btn-admin w-100 mb-2">
                            <i class="bi bi-eye"></i> View Details
                        </a>
                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary btn-admin w-100">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('type');
            const valueInput = document.getElementById('value');
            const valuePrefix = document.getElementById('valuePrefix');
            const valueSuffix = document.getElementById('valueSuffix');
            const valueLabel = document.getElementById('valueLabel');
            const valueHelp = document.getElementById('valueHelp');
            const maxDiscountGroup = document.getElementById('maxDiscountGroup');
            const valueGroup = document.getElementById('valueGroup');

            // Generate coupon code (only if unused)
            const generateBtn = document.getElementById('generateCodeBtn');
            if (generateBtn) {
                generateBtn.addEventListener('click', function() {
                    const button = this;
                    const codeInput = document.getElementById('code');

                    // Disable button and show loading state
                    button.disabled = true;
                    button.innerHTML = '<i class="bi bi-arrow-clockwise spinner-border spinner-border-sm"></i> Generating...';

                    fetch('{{ route("admin.coupons.generate-code") }}')
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            codeInput.value = data.code;
                            updatePreview();
                            // Reset button state
                            button.disabled = false;
                            button.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Generate';
                        })
                        .catch(error => {
                            console.error('Error generating code:', error);
                            alert('Failed to generate code. Please try again.');
                            // Reset button state
                            button.disabled = false;
                            button.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Generate';
                        });
                });
            }

            // Update form based on discount type
            function updateTypeInterface() {
                const type = typeSelect.value;

                switch (type) {
                    case 'percentage':
                        valuePrefix.style.display = 'none';
                        valueSuffix.style.display = 'block';
                        valueLabel.textContent = 'Percentage';
                        valueHelp.textContent = 'Enter percentage (e.g., 10 for 10%)';
                        valueInput.min = '0';
                        valueInput.max = '100';
                        valueInput.step = '0.1';
                        maxDiscountGroup.style.display = 'block';
                        valueGroup.style.display = 'block';
                        break;
                    case 'fixed_amount':
                        valuePrefix.style.display = 'block';
                        valueSuffix.style.display = 'none';
                        valueLabel.textContent = 'Amount';
                        valueHelp.textContent = 'Enter fixed discount amount';
                        valueInput.min = '0';
                        valueInput.max = '';
                        valueInput.step = '0.01';
                        maxDiscountGroup.style.display = 'none';
                        valueGroup.style.display = 'block';
                        break;
                    case 'free_shipping':
                        valueGroup.style.display = 'none';
                        maxDiscountGroup.style.display = 'none';
                        break;
                    default:
                        valueGroup.style.display = 'block';
                        maxDiscountGroup.style.display = 'block';
                }

                updatePreview();
            }

            typeSelect.addEventListener('change', updateTypeInterface);

            // Real-time preview updates
            function updatePreview() {
                const code = document.getElementById('code').value || 'COUPON-CODE';
                const name = document.getElementById('name').value || 'Coupon Name';
                const type = document.getElementById('type').value;
                const value = document.getElementById('value').value;
                const expiresAt = document.getElementById('expires_at').value;

                document.getElementById('previewCode').textContent = code.toUpperCase();
                document.getElementById('previewName').textContent = name;

                let discountText = 'Discount Value';
                if (type && value) {
                    switch (type) {
                        case 'percentage':
                            discountText = value + '% OFF';
                            break;
                        case 'fixed_amount':
                            discountText = 'UGX ' + parseFloat(value).toFixed(0) + ' OFF';
                            break;
                        case 'free_shipping':
                            discountText = 'FREE SHIPPING';
                            break;
                    }
                }
                document.getElementById('previewDiscount').textContent = discountText;

                let expiryText = 'Never expires';
                if (expiresAt) {
                    const date = new Date(expiresAt);
                    expiryText = 'Valid until: ' + date.toLocaleDateString();
                }
                document.getElementById('previewExpiry').textContent = expiryText;
            }

            // Add event listeners for preview updates
            ['code', 'name', 'value', 'expires_at'].forEach(id => {
                document.getElementById(id).addEventListener('input', updatePreview);
            });

            // Convert code to uppercase
            document.getElementById('code').addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });

            // Initialize
            updateTypeInterface();
            updatePreview();
        });
    </script>
@endpush
