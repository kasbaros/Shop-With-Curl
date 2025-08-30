@extends('admin.layouts.app')

@section('title', 'Create Coupon')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Create New Coupon</h1>
            <p class="text-muted">Create discount codes and promotional campaigns</p>
        </div>
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary btn-admin">
            <i class="bi bi-arrow-left"></i> Back to Coupons
        </a>
    </div>

    <form action="{{ route('admin.coupons.store') }}" method="POST" id="couponForm">
        @csrf
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
                                               id="code" name="code" value="{{ old('code') }}" required
                                               placeholder="Enter coupon code" style="text-transform: uppercase;">
                                        <button type="button" class="btn btn-outline-secondary" id="generateCodeBtn">
                                            <i class="bi bi-arrow-clockwise"></i> Generate
                                        </button>
                                    </div>
                                    @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Use letters and numbers only. Will be converted to uppercase.</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Coupon Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name') }}" required
                                           placeholder="e.g., Summer Sale 2024">
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3"
                                      placeholder="Brief description of this coupon...">{{ old('description') }}</textarea>
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
                    </div>
                    <div class="p-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Discount Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('type') is-invalid @enderror"
                                            id="type" name="type" required>
                                        <option value="">Select discount type...</option>
                                        <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>
                                            Percentage Discount
                                        </option>
                                        <option value="fixed_amount" {{ old('type') === 'fixed_amount' ? 'selected' : '' }}>
                                            Fixed Amount Discount
                                        </option>
                                        <option value="free_shipping" {{ old('type') === 'free_shipping' ? 'selected' : '' }}>
                                            Free Shipping
                                        </option>
                                    </select>
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
                                               id="value" name="value" value="{{ old('value') }}"
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
                                               id="minimum_amount" name="minimum_amount" value="{{ old('minimum_amount') }}"
                                               step="0.01" min="0" placeholder="0.00">
                                    </div>
                                    @error('minimum_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Minimum cart total required to use this coupon</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3" id="maxDiscountGroup">
                                    <label for="maximum_discount" class="form-label">Maximum Discount Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control @error('maximum_discount') is-invalid @enderror"
                                               id="maximum_discount" name="maximum_discount" value="{{ old('maximum_discount') }}"
                                               step="0.01" min="0" placeholder="0.00">
                                    </div>
                                    @error('maximum_discount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Cap the maximum discount (for percentage coupons)</small>
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
                                           id="usage_limit" name="usage_limit" value="{{ old('usage_limit') }}"
                                           min="1" placeholder="Unlimited">
                                    @error('usage_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Maximum number of times this coupon can be used</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="usage_limit_per_user" class="form-label">Usage Limit Per Customer</label>
                                    <input type="number" class="form-control @error('usage_limit_per_user') is-invalid @enderror"
                                           id="usage_limit_per_user" name="usage_limit_per_user" value="{{ old('usage_limit_per_user') }}"
                                           min="1" placeholder="Unlimited">
                                    @error('usage_limit_per_user')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Maximum uses per customer</small>
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
                                                {{ in_array($product->id, old('applicable_products', [])) ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('applicable_products')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple products</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="applicable_categories" class="form-label">Specific Categories</label>
                                    <select class="form-select @error('applicable_categories') is-invalid @enderror"
                                            id="applicable_categories" name="applicable_categories[]" multiple>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ in_array($category->id, old('applicable_categories', [])) ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('applicable_categories')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple categories</small>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            <strong>Note:</strong> If both products and categories are selected, the coupon will apply to products that match either condition.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Schedule -->
                <div class="table-admin mb-4">
                    <div class="p-3 border-bottom">
                        <h5 class="mb-0">Schedule</h5>
                    </div>
                    <div class="p-4">
                        <div class="mb-3">
                            <label for="starts_at" class="form-label">Start Date & Time</label>
                            <input type="datetime-local" class="form-control @error('starts_at') is-invalid @enderror"
                                   id="starts_at" name="starts_at" value="{{ old('starts_at') }}">
                            @error('starts_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Leave empty to start immediately</small>
                        </div>

                        <div class="mb-3">
                            <label for="expires_at" class="form-label">Expiry Date & Time</label>
                            <input type="datetime-local" class="form-control @error('expires_at') is-invalid @enderror"
                                   id="expires_at" name="expires_at" value="{{ old('expires_at') }}">
                            @error('expires_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Leave empty for no expiry</small>
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
                                {{ old('is_active', true) ? 'checked' : '' }}>
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
                            <div class="coupon-code h4 mb-2 font-monospace" id="previewCode">COUPON-CODE</div>
                            <div class="coupon-name text-muted mb-2" id="previewName">Coupon Name</div>
                            <div class="coupon-discount h5 text-primary" id="previewDiscount">Discount Value</div>
                            <div class="coupon-expiry small text-muted" id="previewExpiry">Valid until: Never expires</div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="table-admin">
                    <div class="p-4">
                        <button type="submit" class="btn btn-primary btn-admin w-100 mb-2">
                            <i class="bi bi-check-circle"></i> Create Coupon
                        </button>
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

            // Generate coupon code
            document.getElementById('generateCodeBtn').addEventListener('click', function() {
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

            // Update form based on discount type
            typeSelect.addEventListener('change', function() {
                const type = this.value;

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
                        valueInput.value = '0';
                        break;
                    default:
                        valueGroup.style.display = 'block';
                        maxDiscountGroup.style.display = 'block';
                }

                updatePreview();
            });

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
            if (typeSelect.value) {
                typeSelect.dispatchEvent(new Event('change'));
            }
            updatePreview();
        });
    </script>
@endpush
