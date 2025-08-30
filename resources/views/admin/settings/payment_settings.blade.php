@extends('admin.layouts.app')

@section('title', 'Payment Settings')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Payment Settings</h1>
            <p class="text-muted">Configure payment methods for Uganda market</p>
        </div>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" class="needs-validation" novalidate>
        @csrf
        <input type="hidden" name="group" value="payment">

        <div class="row">
            <!-- Mobile Money (Primary for Uganda) -->
            <div class="col-lg-6">
                <div class="table-admin p-4 mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-phone me-2 text-success fs-4"></i>
                        <h5 class="mb-0">Mobile Money (Recommended)</h5>
                    </div>
                    <p class="text-muted small mb-3">Mobile Money is the most popular payment method in Uganda</p>

                    <!-- MTN Mobile Money -->
                    <div class="border rounded p-3 mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="form-check form-switch me-3">
                                <input class="form-check-input" type="checkbox" id="mtn_enabled" name="mtn_enabled" value="1"
                                    {{ setting('mtn_enabled', true) ? 'checked' : '' }}>
                            </div>
                            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 40 40'%3E%3Crect width='40' height='40' fill='%23FFCB05'/%3E%3Ctext x='50%25' y='50%25' text-anchor='middle' dy='.3em' font-family='Arial' font-size='12' fill='%23000'%3EMTN%3C/text%3E%3C/svg%3E" alt="MTN" class="me-2">
                            <strong>MTN Mobile Money</strong>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label for="mtn_shortcode" class="form-label small">Business Short Code</label>
                                <input type="text" class="form-control form-control-sm" id="mtn_shortcode" name="mtn_shortcode"
                                       value="{{ setting('mtn_shortcode') }}" placeholder="e.g., 123456">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="mtn_api_key" class="form-label small">API Key</label>
                                <input type="password" class="form-control form-control-sm" id="mtn_api_key" name="mtn_api_key"
                                       value="{{ setting('mtn_api_key') }}" placeholder="Your MTN API Key">
                            </div>
                        </div>
                        <div class="form-text">
                            <a href="https://momodeveloper.mtn.com/" target="_blank" class="text-decoration-none">
                                <i class="bi bi-box-arrow-up-right me-1"></i>Get MTN MoMo API credentials
                            </a>
                        </div>
                    </div>

                    <!-- Airtel Money -->
                    <div class="border rounded p-3 mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="form-check form-switch me-3">
                                <input class="form-check-input" type="checkbox" id="airtel_enabled" name="airtel_enabled" value="1"
                                    {{ setting('airtel_enabled', true) ? 'checked' : '' }}>
                            </div>
                            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 40 40'%3E%3Crect width='40' height='40' fill='%23E60012'/%3E%3Ctext x='50%25' y='50%25' text-anchor='middle' dy='.3em' font-family='Arial' font-size='10' fill='white'%3EAIRTEL%3C/text%3E%3C/svg%3E" alt="Airtel" class="me-2">
                            <strong>Airtel Money</strong>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label for="airtel_shortcode" class="form-label small">Business Short Code</label>
                                <input type="text" class="form-control form-control-sm" id="airtel_shortcode" name="airtel_shortcode"
                                       value="{{ setting('airtel_shortcode') }}" placeholder="e.g., 789012">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="airtel_api_key" class="form-label small">API Key</label>
                                <input type="password" class="form-control form-control-sm" id="airtel_api_key" name="airtel_api_key"
                                       value="{{ setting('airtel_api_key') }}" placeholder="Your Airtel API Key">
                            </div>
                        </div>
                    </div>

                    <!-- Manual Mobile Money -->
                    <div class="border rounded p-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="form-check form-switch me-3">
                                <input class="form-check-input" type="checkbox" id="manual_momo_enabled" name="manual_momo_enabled" value="1"
                                    {{ setting('manual_momo_enabled', true) ? 'checked' : '' }}>
                            </div>
                            <i class="bi bi-cash me-2 text-primary"></i>
                            <strong>Manual Mobile Money</strong>
                        </div>
                        <p class="text-muted small mb-2">Customers send money to your number and provide transaction reference</p>
                        <div class="mb-2">
                            <label for="momo_business_number" class="form-label small">Business Mobile Money Number</label>
                            <input type="tel" class="form-control form-control-sm" id="momo_business_number" name="momo_business_number"
                                   value="{{ setting('momo_business_number') }}" placeholder="+256 7XX XXX XXX">
                        </div>
                        <div class="mb-2">
                            <label for="momo_business_name" class="form-label small">Registered Business Name</label>
                            <input type="text" class="form-control form-control-sm" id="momo_business_name" name="momo_business_name"
                                   value="{{ setting('momo_business_name') }}" placeholder="Name on Mobile Money account">
                        </div>
                    </div>
                </div>

                <!-- Bank Transfer -->
                <div class="table-admin p-4 mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-bank me-2 text-info fs-4"></i>
                        <h5 class="mb-0">Bank Transfer</h5>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="bank_transfer_enabled" name="bank_transfer_enabled" value="1"
                            {{ setting('bank_transfer_enabled', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="bank_transfer_enabled">
                            <strong>Enable Bank Transfer</strong>
                        </label>
                    </div>

                    <div id="bankTransferFields" style="{{ setting('bank_transfer_enabled', true) ? '' : 'display:none;' }}">
                        <div class="mb-3">
                            <label for="bank_name" class="form-label">Bank Name</label>
                            <select class="form-select" id="bank_name" name="bank_name">
                                <option value="">Select Bank</option>
                                <option value="Stanbic Bank" {{ setting('bank_name') === 'Stanbic Bank' ? 'selected' : '' }}>Stanbic Bank</option>
                                <option value="Centenary Bank" {{ setting('bank_name') === 'Centenary Bank' ? 'selected' : '' }}>Centenary Bank</option>
                                <option value="DFCU Bank" {{ setting('bank_name') === 'DFCU Bank' ? 'selected' : '' }}>DFCU Bank</option>
                                <option value="Equity Bank" {{ setting('bank_name') === 'Equity Bank' ? 'selected' : '' }}>Equity Bank</option>
                                <option value="Absa Bank" {{ setting('bank_name') === 'Absa Bank' ? 'selected' : '' }}>Absa Bank</option>
                                <option value="Standard Chartered" {{ setting('bank_name') === 'Standard Chartered' ? 'selected' : '' }}>Standard Chartered</option>
                                <option value="Bank of Africa" {{ setting('bank_name') === 'Bank of Africa' ? 'selected' : '' }}>Bank of Africa</option>
                                <option value="Housing Finance Bank" {{ setting('bank_name') === 'Housing Finance Bank' ? 'selected' : '' }}>Housing Finance Bank</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="bank_account_name" class="form-label">Account Name</label>
                                <input type="text" class="form-control" id="bank_account_name" name="bank_account_name"
                                       value="{{ setting('bank_account_name') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="bank_account_number" class="form-label">Account Number</label>
                                <input type="text" class="form-control" id="bank_account_number" name="bank_account_number"
                                       value="{{ setting('bank_account_number') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="bank_branch" class="form-label">Branch</label>
                            <input type="text" class="form-control" id="bank_branch" name="bank_branch"
                                   value="{{ setting('bank_branch') }}" placeholder="e.g., Kampala Main Branch">
                        </div>
                    </div>
                </div>
            </div>

            <!-- International Payments -->
            <div class="col-lg-6">
                <!-- PayPal -->
                <div class="table-admin p-4 mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' fill='%23003087'/%3E%3Ctext x='50%25' y='50%25' text-anchor='middle' dy='.3em' font-family='Arial' font-size='10' fill='white'%3EPP%3C/text%3E%3C/svg%3E" alt="PayPal" class="me-2">
                        <h5 class="mb-0">PayPal</h5>
                        <span class="badge bg-success ms-2">International</span>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="paypal_enabled" name="paypal_enabled" value="1"
                            {{ setting('paypal_enabled', false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="paypal_enabled">
                            <strong>Enable PayPal</strong>
                        </label>
                    </div>

                    <div id="paypalFields" style="{{ setting('paypal_enabled', false) ? '' : 'display:none;' }}">
                        <div class="mb-3">
                            <label for="paypal_mode" class="form-label">Mode</label>
                            <select class="form-select" id="paypal_mode" name="paypal_mode">
                                <option value="sandbox" {{ setting('paypal_mode', 'sandbox') === 'sandbox' ? 'selected' : '' }}>Sandbox (Test)</option>
                                <option value="live" {{ setting('paypal_mode') === 'live' ? 'selected' : '' }}>Live (Production)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="paypal_client_id" class="form-label">Client ID</label>
                            <input type="text" class="form-control" id="paypal_client_id" name="paypal_client_id"
                                   value="{{ setting('paypal_client_id') }}">
                        </div>

                        <div class="mb-3">
                            <label for="paypal_client_secret" class="form-label">Client Secret</label>
                            <input type="password" class="form-control" id="paypal_client_secret" name="paypal_client_secret"
                                   value="{{ setting('paypal_client_secret') }}">
                        </div>

                        <div class="form-text">
                            <a href="https://developer.paypal.com/" target="_blank" class="text-decoration-none">
                                <i class="bi bi-box-arrow-up-right me-1"></i>Get PayPal API credentials
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Stripe -->
                <div class="table-admin p-4 mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' fill='%23635BFF'/%3E%3Ctext x='50%25' y='50%25' text-anchor='middle' dy='.3em' font-family='Arial' font-size='12' fill='white'%3E$%3C/text%3E%3C/svg%3E" alt="Stripe" class="me-2">
                        <h5 class="mb-0">Stripe</h5>
                        <span class="badge bg-info ms-2">Cards</span>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="stripe_enabled" name="stripe_enabled" value="1"
                            {{ setting('stripe_enabled', false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="stripe_enabled">
                            <strong>Enable Stripe</strong>
                        </label>
                    </div>

                    <div id="stripeFields" style="{{ setting('stripe_enabled', false) ? '' : 'display:none;' }}">
                        <div class="mb-3">
                            <label for="stripe_publishable_key" class="form-label">Publishable Key</label>
                            <input type="text" class="form-control" id="stripe_publishable_key" name="stripe_publishable_key"
                                   value="{{ setting('stripe_publishable_key') }}" placeholder="pk_test_...">
                        </div>

                        <div class="mb-3">
                            <label for="stripe_secret_key" class="form-label">Secret Key</label>
                            <input type="password" class="form-control" id="stripe_secret_key" name="stripe_secret_key"
                                   value="{{ setting('stripe_secret_key') }}" placeholder="sk_test_...">
                        </div>

                        <div class="alert alert-info small">
                            <i class="bi bi-info-circle me-1"></i>
                            <strong>Note:</strong> Stripe works well for international customers but may have limited local card acceptance in Uganda.
                        </div>
                    </div>
                </div>

                <!-- Cash on Delivery -->
                <div class="table-admin p-4 mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-cash-coin me-2 text-warning fs-4"></i>
                        <h5 class="mb-0">Cash on Delivery (COD)</h5>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="cod_enabled" name="cod_enabled" value="1"
                            {{ setting('cod_enabled', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="cod_enabled">
                            <strong>Enable Cash on Delivery</strong>
                        </label>
                    </div>

                    <div id="codFields" style="{{ setting('cod_enabled', true) ? '' : 'display:none;' }}">
                        <div class="mb-3">
                            <label for="cod_fee" class="form-label">COD Fee (UGX)</label>
                            <input type="number" class="form-control" id="cod_fee" name="cod_fee"
                                   value="{{ setting('cod_fee', '5000') }}" min="0" step="1000">
                            <div class="form-text">Additional fee for cash on delivery service</div>
                        </div>

                        <div class="mb-3">
                            <label for="cod_instructions" class="form-label">COD Instructions</label>
                            <textarea class="form-control" id="cod_instructions" name="cod_instructions" rows="3"
                                      placeholder="Instructions for customers choosing cash on delivery">{{ setting('cod_instructions', 'Please have exact amount ready. Our delivery agent will collect payment upon delivery.') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment Configuration -->
                <div class="table-admin p-4">
                    <h5 class="mb-3">
                        <i class="bi bi-gear me-2 text-secondary"></i>Payment Configuration
                    </h5>

                    <div class="mb-3">
                        <label for="default_currency" class="form-label">Default Currency</label>
                        <select class="form-select" id="default_currency" name="default_currency">
                            <option value="UGX" {{ setting('default_currency', 'UGX') === 'UGX' ? 'selected' : '' }}>Ugandan Shilling (UGX)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="payment_timeout" class="form-label">Payment Timeout (minutes)</label>
                        <input type="number" class="form-control" id="payment_timeout" name="payment_timeout"
                               value="{{ setting('payment_timeout', '30') }}" min="5" max="60">
                        <div class="form-text">How long to wait for payment confirmation</div>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="auto_approve_payments" name="auto_approve_payments" value="1"
                            {{ setting('auto_approve_payments', false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="auto_approve_payments">
                            <strong>Auto-approve successful payments</strong>
                            <div class="small text-muted">Automatically approve orders when payment is confirmed</div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="d-flex justify-content-end gap-2 mt-4">
            <button type="button" class="btn btn-outline-secondary" onclick="window.history.back()">Cancel</button>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg me-1"></i>Save Payment Settings
            </button>
        </div>
    </form>

    @push('scripts')
        <script>
            // Toggle field visibility based on checkbox state
            document.addEventListener('DOMContentLoaded', function() {
                // Bank Transfer toggle
                const bankTransferCheckbox = document.getElementById('bank_transfer_enabled');
                const bankTransferFields = document.getElementById('bankTransferFields');

                bankTransferCheckbox.addEventListener('change', function() {
                    bankTransferFields.style.display = this.checked ? 'block' : 'none';
                });

                // PayPal toggle
                const paypalCheckbox = document.getElementById('paypal_enabled');
                const paypalFields = document.getElementById('paypalFields');

                paypalCheckbox.addEventListener('change', function() {
                    paypalFields.style.display = this.checked ? 'block' : 'none';
                });

                // Stripe toggle
                const stripeCheckbox = document.getElementById('stripe_enabled');
                const stripeFields = document.getElementById('stripeFields');

                stripeCheckbox.addEventListener('change', function() {
                    stripeFields.style.display = this.checked ? 'block' : 'none';
                });

                // COD toggle
                const codCheckbox = document.getElementById('cod_enabled');
                const codFields = document.getElementById('codFields');

                codCheckbox.addEventListener('change', function() {
                    codFields.style.display = this.checked ? 'block' : 'none';
                });
            });
        </script>
    @endpush
@endsection
