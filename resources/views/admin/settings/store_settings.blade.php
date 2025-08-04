@extends('admin.layouts.app')

@section('title', 'Store Settings')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Store Settings</h1>
            <p class="text-muted">Manage your store information and customer features</p>
        </div>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" class="needs-validation" novalidate>
        @csrf
        <input type="hidden" name="group" value="store">

        <div class="row">
            <!-- Store Information -->
            <div class="col-lg-8">
                <div class="table-admin p-4 mb-4">
                    <h5 class="mb-3">
                        <i class="bi bi-shop me-2 text-primary"></i>Store Information
                    </h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="store_name" class="form-label">Store Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="store_name" name="store_name"
                                   value="{{ setting('store_name', config('app.name')) }}" required>
                            <div class="invalid-feedback">Please enter a store name.</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="store_phone" class="form-label">Store Phone</label>
                            <input type="tel" class="form-control" id="store_phone" name="store_phone"
                                   value="{{ setting('store_phone') }}" placeholder="+256 xxx xxx xxx">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="store_address" class="form-label">Store Address <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="store_address" name="store_address" rows="3" required>{{ setting('store_address') }}</textarea>
                        <div class="invalid-feedback">Please enter a store address.</div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="store_city" class="form-label">City <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="store_city" name="store_city"
                                   value="{{ setting('store_city') }}" required>
                            <div class="invalid-feedback">Please enter a city.</div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="store_country" class="form-label">Country <span class="text-danger">*</span></label>
                            <select class="form-select" id="store_country" name="store_country" required>
                                <option value="">Select Country</option>
                                @foreach($countries as $code => $name)
                                    <option value="{{ $code }}" {{ setting('store_country') === $code ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Please select a country.</div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="store_postal_code" class="form-label">Postal Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="store_postal_code" name="store_postal_code"
                                   value="{{ setting('store_postal_code') }}" required>
                            <div class="invalid-feedback">Please enter a postal code.</div>
                        </div>
                    </div>
                </div>

                <!-- Tax Configuration -->
                <div class="table-admin p-4 mb-4">
                    <h5 class="mb-3">
                        <i class="bi bi-calculator me-2 text-success"></i>Tax Configuration
                    </h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tax_rate" class="form-label">Default Tax Rate (%) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="tax_rate" name="tax_rate"
                                       value="{{ setting('tax_rate', '18') }}" step="0.01" min="0" max="100" required>
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="form-text">Uganda VAT rate is typically 18%</div>
                            <div class="invalid-feedback">Please enter a valid tax rate.</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="tax_type" class="form-label">Tax Calculation <span class="text-danger">*</span></label>
                            <select class="form-select" id="tax_type" name="tax_type" required>
                                @foreach($taxTypes as $value => $label)
                                    <option value="{{ $value }}" {{ setting('tax_type', 'exclusive') === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">
                                <strong>Exclusive:</strong> Tax added to product price<br>
                                <strong>Inclusive:</strong> Tax included in product price
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="tax_number" class="form-label">Tax Registration Number</label>
                        <input type="text" class="form-control" id="tax_number" name="tax_number"
                               value="{{ setting('tax_number') }}" placeholder="URA TIN Number">
                        <div class="form-text">Your URA Tax Identification Number (TIN)</div>
                    </div>
                </div>

                <!-- Business Information -->
                <div class="table-admin p-4 mb-4">
                    <h5 class="mb-3">
                        <i class="bi bi-building me-2 text-info"></i>Business Information
                    </h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="business_registration" class="form-label">Business Registration Number</label>
                            <input type="text" class="form-control" id="business_registration" name="business_registration"
                                   value="{{ setting('business_registration') }}" placeholder="Company Registration Number">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="business_license" class="form-label">Trading License Number</label>
                            <input type="text" class="form-control" id="business_license" name="business_license"
                                   value="{{ setting('business_license') }}" placeholder="KCCA/Municipal License">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="store_description" class="form-label">Store Description</label>
                        <textarea class="form-control" id="store_description" name="store_description" rows="4"
                                  placeholder="Brief description of your store and what you sell">{{ setting('store_description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Customer Features -->
            <div class="col-lg-4">
                <div class="table-admin p-4 mb-4">
                    <h5 class="mb-3">
                        <i class="bi bi-people me-2 text-warning"></i>Customer Features
                    </h5>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="enable_reviews" name="enable_reviews" value="1"
                                {{ setting('enable_reviews', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="enable_reviews">
                                <strong>Product Reviews</strong>
                                <div class="small text-muted">Allow customers to leave product reviews</div>
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="enable_wishlist" name="enable_wishlist" value="1"
                                {{ setting('enable_wishlist', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="enable_wishlist">
                                <strong>Wishlist</strong>
                                <div class="small text-muted">Allow customers to save favorite products</div>
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="enable_compare" name="enable_compare" value="1"
                                {{ setting('enable_compare', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="enable_compare">
                                <strong>Product Compare</strong>
                                <div class="small text-muted">Allow customers to compare products</div>
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="guest_checkout" name="guest_checkout" value="1"
                                {{ setting('guest_checkout', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="guest_checkout">
                                <strong>Guest Checkout</strong>
                                <div class="small text-muted">Allow checkout without registration</div>
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="require_phone" name="require_phone" value="1"
                                {{ setting('require_phone', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="require_phone">
                                <strong>Require Phone Number</strong>
                                <div class="small text-muted">Make phone number mandatory for orders</div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Inventory Settings -->
                <div class="table-admin p-4 mb-4">
                    <h5 class="mb-3">
                        <i class="bi bi-box-seam me-2 text-secondary"></i>Inventory Settings
                    </h5>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="track_inventory" name="track_inventory" value="1"
                                {{ setting('track_inventory', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="track_inventory">
                                <strong>Track Inventory</strong>
                                <div class="small text-muted">Monitor stock levels automatically</div>
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="allow_backorders" name="allow_backorders" value="1"
                                {{ setting('allow_backorders', false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="allow_backorders">
                                <strong>Allow Backorders</strong>
                                <div class="small text-muted">Accept orders when out of stock</div>
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="low_stock_threshold" class="form-label">Low Stock Alert</label>
                        <input type="number" class="form-control" id="low_stock_threshold" name="low_stock_threshold"
                               value="{{ setting('low_stock_threshold', '10') }}" min="0">
                        <div class="form-text">Alert when stock falls below this number</div>
                    </div>
                </div>

                <!-- Operating Hours -->
                <div class="table-admin p-4">
                    <h5 class="mb-3">
                        <i class="bi bi-clock me-2 text-primary"></i>Operating Hours
                    </h5>

                    <div class="mb-3">
                        <label for="operating_hours" class="form-label">Business Hours</label>
                        <textarea class="form-control" id="operating_hours" name="operating_hours" rows="4"
                                  placeholder="Monday - Friday: 8:00 AM - 6:00 PM&#10;Saturday: 9:00 AM - 5:00 PM&#10;Sunday: Closed">{{ setting('operating_hours') }}</textarea>
                        <div class="form-text">Display your business hours to customers</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="d-flex justify-content-end gap-2 mt-4">
            <button type="button" class="btn btn-outline-secondary" onclick="window.history.back()">Cancel</button>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg me-1"></i>Save Store Settings
            </button>
        </div>
    </form>

    @push('scripts')
        <script>
            // Form validation
            (() => {
                'use strict';
                window.addEventListener('load', () => {
                    const forms = document.querySelectorAll('.needs-validation');
                    Array.prototype.slice.call(forms).forEach((form) => {
                        form.addEventListener('submit', (event) => {
                            if (!form.checkValidity()) {
                                event.preventDefault();
                                event.stopPropagation();
                            }
                            form.classList.add('was-validated');
                        }, false);
                    });
                });
            })();
        </script>
    @endpush
@endsection
