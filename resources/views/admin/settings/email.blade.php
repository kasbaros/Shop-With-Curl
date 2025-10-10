@extends('admin.layouts.app')

@section('title', 'Email Settings')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Email Settings</h1>
            <p class="text-muted">Configure email server and notification settings</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Settings
            </a>
        </div>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        <input type="hidden" name="group" value="email">

        <div class="row">
            <!-- Main Settings -->
            <div class="col-lg-8">
                <!-- SMTP Configuration -->
                <div class="table-admin p-4 mb-4">
                    <h5 class="mb-3">SMTP Configuration</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="mail_mailer" class="form-label">Mail Driver *</label>
                            <select class="form-select @error('mail_mailer') is-invalid @enderror" id="mail_mailer" name="mail_mailer" required>
                                <option value="smtp" {{ old('mail_mailer', setting('mail_mailer', 'smtp')) === 'smtp' ? 'selected' : '' }}>SMTP</option>
                                <option value="sendmail" {{ old('mail_mailer', setting('mail_mailer', 'smtp')) === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                <option value="mailgun" {{ old('mail_mailer', setting('mail_mailer', 'smtp')) === 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                <option value="ses" {{ old('mail_mailer', setting('mail_mailer', 'smtp')) === 'ses' ? 'selected' : '' }}>Amazon SES</option>
                                <option value="log" {{ old('mail_mailer', setting('mail_mailer', 'smtp')) === 'log' ? 'selected' : '' }}>Log (Testing)</option>
                            </select>
                            @error('mail_mailer')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Choose your email delivery method</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="mail_host" class="form-label">SMTP Host *</label>
                            <input type="text" class="form-control @error('mail_host') is-invalid @enderror"
                                   id="mail_host" name="mail_host"
                                   value="{{ old('mail_host', setting('mail_host', 'localhost')) }}" required>
                            @error('mail_host')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Your SMTP server hostname</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="mail_port" class="form-label">SMTP Port *</label>
                            <input type="number" class="form-control @error('mail_port') is-invalid @enderror"
                                   id="mail_port" name="mail_port"
                                   value="{{ old('mail_port', setting('mail_port', '587')) }}" required>
                            @error('mail_port')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Common ports: 587 (TLS), 465 (SSL), 25 (unsecured)</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="mail_encryption" class="form-label">Encryption</label>
                            <select class="form-select @error('mail_encryption') is-invalid @enderror" id="mail_encryption" name="mail_encryption">
                                <option value="" {{ old('mail_encryption', setting('mail_encryption')) === '' ? 'selected' : '' }}>None</option>
                                <option value="tls" {{ old('mail_encryption', setting('mail_encryption', 'tls')) === 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ old('mail_encryption', setting('mail_encryption', 'tls')) === 'ssl' ? 'selected' : '' }}>SSL</option>
                            </select>
                            @error('mail_encryption')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Recommended: TLS for port 587, SSL for port 465</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="mail_username" class="form-label">SMTP Username</label>
                            <input type="text" class="form-control @error('mail_username') is-invalid @enderror"
                                   id="mail_username" name="mail_username"
                                   value="{{ old('mail_username', setting('mail_username')) }}">
                            @error('mail_username')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Your SMTP authentication username</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="mail_password" class="form-label">SMTP Password</label>
                            <input type="password" class="form-control @error('mail_password') is-invalid @enderror"
                                   id="mail_password" name="mail_password"
                                   value="{{ old('mail_password', setting('mail_password')) }}">
                            @error('mail_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Your SMTP authentication password</div>
                        </div>
                    </div>
                </div>

                <!-- From Address Settings -->
                <div class="table-admin p-4 mb-4">
                    <h5 class="mb-3">From Address Settings</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="mail_from_address" class="form-label">From Email Address *</label>
                            <input type="email" class="form-control @error('mail_from_address') is-invalid @enderror"
                                   id="mail_from_address" name="mail_from_address"
                                   value="{{ old('mail_from_address', setting('mail_from_address', config('mail.from.address'))) }}" required>
                            @error('mail_from_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Email address that emails will be sent from</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="mail_from_name" class="form-label">From Name *</label>
                            <input type="text" class="form-control @error('mail_from_name') is-invalid @enderror"
                                   id="mail_from_name" name="mail_from_name"
                                   value="{{ old('mail_from_name', setting('mail_from_name', config('mail.from.name'))) }}" required>
                            @error('mail_from_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Name that will appear in the "From" field</div>
                        </div>
                    </div>
                </div>

                <!-- Email Notifications -->
                <div class="table-admin p-4 mb-4">
                    <h5 class="mb-3">Email Notifications</h5>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="email_notifications_enabled"
                                       name="email_notifications_enabled" value="1"
                                       {{ old('email_notifications_enabled', setting('email_notifications_enabled', true)) ? 'checked' : '' }}>
                                <label class="form-check-label" for="email_notifications_enabled">
                                    Enable Email Notifications
                                </label>
                                <div class="form-text">Allow the system to send email notifications</div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="notify_new_orders"
                                       name="notify_new_orders" value="1"
                                       {{ old('notify_new_orders', setting('notify_new_orders', true)) ? 'checked' : '' }}>
                                <label class="form-check-label" for="notify_new_orders">
                                    Notify on New Orders
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="notify_low_stock"
                                       name="notify_low_stock" value="1"
                                       {{ old('notify_low_stock', setting('notify_low_stock', true)) ? 'checked' : '' }}>
                                <label class="form-check-label" for="notify_low_stock">
                                    Notify on Low Stock
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="notify_new_customers"
                                       name="notify_new_customers" value="1"
                                       {{ old('notify_new_customers', setting('notify_new_customers', false)) ? 'checked' : '' }}>
                                <label class="form-check-label" for="notify_new_customers">
                                    Notify on New Customer Registration
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="notify_contact_forms"
                                       name="notify_contact_forms" value="1"
                                       {{ old('notify_contact_forms', setting('notify_contact_forms', true)) ? 'checked' : '' }}>
                                <label class="form-check-label" for="notify_contact_forms">
                                    Notify on Contact Form Submissions
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Test Email -->
                <div class="table-admin p-4 mb-4">
                    <h5 class="mb-3">Test Email Configuration</h5>
                    <p class="text-muted small mb-3">Send a test email to verify your configuration is working correctly.</p>

                    <div class="mb-3">
                        <label for="test_email" class="form-label">Test Email Address</label>
                        <input type="email" class="form-control" id="test_email" name="test_email"
                               placeholder="test@example.com">
                    </div>

                    <button type="button" class="btn btn-outline-primary btn-sm w-100" id="send-test-email">
                        <i class="bi bi-send"></i> Send Test Email
                    </button>
                </div>

                <!-- Email Queue Status -->
                <div class="table-admin p-4 mb-4">
                    <h5 class="mb-3">Email Queue</h5>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Queued Emails:</span>
                        <span class="badge bg-info">0</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Failed Emails:</span>
                        <span class="badge bg-danger">0</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Sent Today:</span>
                        <span class="badge bg-success">0</span>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="table-admin p-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-lg"></i> Save Email Settings
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
<script>
document.getElementById('send-test-email').addEventListener('click', function() {
    const testEmail = document.getElementById('test_email').value;
    const button = this;

    if (!testEmail) {
        alert('Please enter a test email address');
        return;
    }

    button.disabled = true;
    button.innerHTML = '<i class="spinner-border spinner-border-sm me-1"></i> Sending...';

    fetch('{{ route('admin.settings.test-email') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            test_email: testEmail
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Test email sent successfully!');
        } else {
            alert('Failed to send test email: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error sending test email: ' + error.message);
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = '<i class="bi bi-send"></i> Send Test Email';
    });
});
</script>
@endsection
