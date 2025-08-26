@extends('admin.layouts.app')

@section('title', 'General Settings')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">General Settings</h1>
            <p class="text-muted">Configure basic site information and preferences</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Settings
            </a>
        </div>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="group" value="general">

        <div class="row">
            <!-- Main Settings -->
            <div class="col-lg-8">
                <!-- Site Information -->
                <div class="table-admin p-4 mb-4">
                    <h5 class="mb-3">Site Information</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="site_name" class="form-label">Site Name *</label>
                            <input type="text" class="form-control @error('site_name') is-invalid @enderror"
                                   id="site_name" name="site_name"
                                   value="{{ old('site_name', setting('site_name', config('app.name'))) }}" required>
                            @error('site_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">This will appear in the browser title and throughout the site</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="site_tagline" class="form-label">Site Tagline</label>
                            <input type="text" class="form-control @error('site_tagline') is-invalid @enderror"
                                   id="site_tagline" name="site_tagline"
                                   value="{{ old('site_tagline', setting('site_tagline')) }}"
                                   placeholder="Your shopping destination">
                            @error('site_tagline')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">A short description of your site</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="site_description" class="form-label">Site Description</label>
                        <textarea class="form-control @error('site_description') is-invalid @enderror"
                                  id="site_description" name="site_description" rows="3"
                                  placeholder="Describe your business in a few sentences...">{{ old('site_description', setting('site_description')) }}</textarea>
                        @error('site_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Used for SEO meta descriptions and site information</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="site_email" class="form-label">Contact Email *</label>
                            <input type="email" class="form-control @error('site_email') is-invalid @enderror"
                                   id="site_email" name="site_email"
                                   value="{{ old('site_email', setting('site_email')) }}" required>
                            @error('site_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Main contact email for your business</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="site_phone" class="form-label">Contact Phone</label>
                            <input type="tel" class="form-control @error('site_phone') is-invalid @enderror"
                                   id="site_phone" name="site_phone"
                                   value="{{ old('site_phone', setting('site_phone')) }}"
                                   placeholder="+1 (555) 123-4567">
                            @error('site_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Customer service phone number</div>
                        </div>
                    </div>
                </div>

                <!-- Regional Settings -->
                <div class="table-admin p-4 mb-4">
                    <h5 class="mb-3">Regional Settings</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="timezone" class="form-label">Timezone *</label>
                            <select class="form-select @error('timezone') is-invalid @enderror" id="timezone" name="timezone" required>
                                @foreach($timezones as $value => $label)
                                    <option value="{{ $value }}" {{ old('timezone', setting('timezone', 'UTC')) === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('timezone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Used for displaying dates and times</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="currency" class="form-label">Default Currency *</label>
                            <select class="form-select @error('currency') is-invalid @enderror" id="currency" name="currency" required>
                                @foreach($currencies as $code => $name)
                                    <option value="{{ $code }}" {{ old('currency', setting('currency', 'UGX')) === $code ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('currency')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Primary currency for products and orders</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="date_format" class="form-label">Date Format</label>
                            <select class="form-select @error('date_format') is-invalid @enderror" id="date_format" name="date_format">
                                <option value="M j, Y" {{ old('date_format', setting('date_format', 'M j, Y')) === 'M j, Y' ? 'selected' : '' }}>
                                    {{ now()->format('M j, Y') }} (M j, Y)
                                </option>
                                <option value="F j, Y" {{ old('date_format', setting('date_format', 'M j, Y')) === 'F j, Y' ? 'selected' : '' }}>
                                    {{ now()->format('F j, Y') }} (F j, Y)
                                </option>
                                <option value="m/d/Y" {{ old('date_format', setting('date_format', 'M j, Y')) === 'm/d/Y' ? 'selected' : '' }}>
                                    {{ now()->format('m/d/Y') }} (m/d/Y)
                                </option>
                                <option value="d/m/Y" {{ old('date_format', setting('date_format', 'M j, Y')) === 'd/m/Y' ? 'selected' : '' }}>
                                    {{ now()->format('d/m/Y') }} (d/m/Y)
                                </option>
                                <option value="Y-m-d" {{ old('date_format', setting('date_format', 'M j, Y')) === 'Y-m-d' ? 'selected' : '' }}>
                                    {{ now()->format('Y-m-d') }} (Y-m-d)
                                </option>
                            </select>
                            @error('date_format')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="time_format" class="form-label">Time Format</label>
                            <select class="form-select @error('time_format') is-invalid @enderror" id="time_format" name="time_format">
                                <option value="g:i A" {{ old('time_format', setting('time_format', 'g:i A')) === 'g:i A' ? 'selected' : '' }}>
                                    {{ now()->format('g:i A') }} (12-hour)
                                </option>
                                <option value="H:i" {{ old('time_format', setting('time_format', 'g:i A')) === 'H:i' ? 'selected' : '' }}>
                                    {{ now()->format('H:i') }} (24-hour)
                                </option>
                            </select>
                            @error('time_format')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="language" class="form-label">Default Language</label>
                            <select class="form-select @error('language') is-invalid @enderror" id="language" name="language">
                                @foreach($languages as $code => $name)
                                    <option value="{{ $code }}" {{ old('language', setting('language', 'en')) === $code ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('language')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Primary language for the site</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="number_format" class="form-label">Number Format</label>
                            <select class="form-select @error('number_format') is-invalid @enderror" id="number_format" name="number_format">
                                <option value="1,234.56" {{ old('number_format', setting('number_format', '1,234.56')) === '1,234.56' ? 'selected' : '' }}>
                                    1,234.56 (US Format)
                                </option>
                                <option value="1.234,56" {{ old('number_format', setting('number_format', '1,234.56')) === '1.234,56' ? 'selected' : '' }}>
                                    1.234,56 (European)
                                </option>
                                <option value="1 234.56" {{ old('number_format', setting('number_format', '1,234.56')) === '1 234.56' ? 'selected' : '' }}>
                                    1 234.56 (French)
                                </option>
                            </select>
                            @error('number_format')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Site Branding -->
                <div class="table-admin p-4 mb-4">
                    <h5 class="mb-3">Site Branding</h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="site_logo" class="form-label">Site Logo</label>
                            <input type="file" class="form-control @error('site_logo') is-invalid @enderror"
                                   id="site_logo" name="site_logo" accept="image/*">
                            @error('site_logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Recommended: 200x60px, PNG or SVG format</div>

                            @if(setting('site_logo'))
                                <div class="mt-2">
                                    <img src="{{ Storage::url(setting('site_logo')) }}" alt="Current logo" class="img-thumbnail" style="max-height: 60px;">
                                    <div class="mt-1">
                                        <small class="text-muted">Current logo</small>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="site_favicon" class="form-label">Favicon</label>
                            <input type="file" class="form-control @error('site_favicon') is-invalid @enderror"
                                   id="site_favicon" name="site_favicon" accept="image/x-icon,image/png">
                            @error('site_favicon')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Recommended: 32x32px, ICO or PNG format</div>

                            @if(setting('site_favicon'))
                                <div class="mt-2">
                                    <img src="{{ Storage::url(setting('site_favicon')) }}" alt="Current favicon" class="img-thumbnail" style="max-height: 32px;">
                                    <div class="mt-1">
                                        <small class="text-muted">Current favicon</small>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="primary_color" class="form-label">Primary Brand Color</label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color @error('primary_color') is-invalid @enderror"
                                       id="primary_color" name="primary_color"
                                       value="{{ old('primary_color', setting('primary_color', '#007bff')) }}">
                                <input type="text" class="form-control @error('primary_color') is-invalid @enderror"
                                       value="{{ old('primary_color', setting('primary_color', '#007bff')) }}" readonly>
                            </div>
                            @error('primary_color')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Used for buttons, links, and accents</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="secondary_color" class="form-label">Secondary Brand Color</label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color @error('secondary_color') is-invalid @enderror"
                                       id="secondary_color" name="secondary_color"
                                       value="{{ old('secondary_color', setting('secondary_color', '#6c757d')) }}">
                                <input type="text" class="form-control @error('secondary_color') is-invalid @enderror"
                                       value="{{ old('secondary_color', setting('secondary_color', '#6c757d')) }}" readonly>
                            </div>
                            @error('secondary_color')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Used for secondary elements and text</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="table-admin p-4 mb-4">
                    <h6 class="mb-3">Quick Actions</h6>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Save General Settings
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="previewChanges()">
                            <i class="bi bi-eye me-2"></i>Preview Changes
                        </button>
                        <button type="button" class="btn btn-outline-danger" onclick="resetDefaults()">
                            <i class="bi bi-arrow-clockwise me-2"></i>Reset to Defaults
                        </button>
                    </div>
                </div>

                <!-- Site Preview -->
                <div class="table-admin p-4 mb-4">
                    <h6 class="mb-3">Site Preview</h6>
                    <div class="border rounded p-3 bg-light">
                        <div class="d-flex align-items-center mb-2">
                            @if(setting('site_logo'))
                                <img src="{{ Storage::url(setting('site_logo')) }}" alt="Logo" style="height: 30px;" class="me-2">
                            @endif
                            <div>
                                <strong id="preview-name">{{ setting('site_name', config('app.name')) }}</strong>
                                @if(setting('site_tagline'))
                                    <br><small class="text-muted" id="preview-tagline">{{ setting('site_tagline') }}</small>
                                @endif
                            </div>
                        </div>
                        <small class="text-muted d-block" id="preview-description">
                            {{ setting('site_description', 'Your site description will appear here...') }}
                        </small>
                    </div>
                    <div class="form-text mt-2">This is how your site branding will appear</div>
                </div>

                <!-- Current Settings Info -->
                <div class="table-admin p-4">
                    <h6 class="mb-3">Current Settings</h6>
                    <ul class="list-unstyled small">
                        <li><strong>Timezone:</strong> {{ setting('timezone', 'UTC') }}</li>
                        <li><strong>Currency:</strong> {{ setting('currency', 'UGX') }}</li>
                        <li><strong>Language:</strong> {{ setting('language', 'English') }}</li>
                        <li><strong>Date Format:</strong> {{ now()->format(setting('date_format', 'M j, Y')) }}</li>
                        <li><strong>Time Format:</strong> {{ now()->format(setting('time_format', 'g:i A')) }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        // Live preview updates
        document.addEventListener('DOMContentLoaded', function() {
            // Update site name preview
            document.getElementById('site_name').addEventListener('input', function() {
                document.getElementById('preview-name').textContent = this.value || 'Site Name';
            });

            // Update tagline preview
            document.getElementById('site_tagline').addEventListener('input', function() {
                const taglineElement = document.getElementById('preview-tagline');
                if (this.value) {
                    taglineElement.textContent = this.value;
                    taglineElement.style.display = 'inline';
                } else {
                    taglineElement.style.display = 'none';
                }
            });

            // Update description preview
            document.getElementById('site_description').addEventListener('input', function() {
                document.getElementById('preview-description').textContent = this.value || 'Your site description will appear here...';
            });

            // Color picker sync
            document.getElementById('primary_color').addEventListener('input', function() {
                this.nextElementSibling.value = this.value;
            });

            document.getElementById('secondary_color').addEventListener('input', function() {
                this.nextElementSibling.value = this.value;
            });
        });

        // Preview changes function
        function previewChanges() {
            const siteName = document.getElementById('site_name').value;
            window.open('{{ route("home") }}', '_blank');
        }

        // Reset to defaults
        function resetDefaults() {
            if (confirm('Are you sure you want to reset all general settings to their default values? This action cannot be undone.')) {
                // Reset form fields to defaults
                document.getElementById('site_name').value = '{{ config("app.name") }}';
                document.getElementById('site_tagline').value = '';
                document.getElementById('site_description').value = '';
                document.getElementById('timezone').value = 'UTC';
                document.getElementById('currency').value = 'UGX';
                document.getElementById('date_format').value = 'M j, Y';
                document.getElementById('time_format').value = 'g:i A';
                document.getElementById('language').value = 'en';
                document.getElementById('primary_color').value = '#007bff';
                document.getElementById('secondary_color').value = '#6c757d';

                // Update previews
                document.getElementById('preview-name').textContent = '{{ config("app.name") }}';
                document.getElementById('preview-tagline').style.display = 'none';
                document.getElementById('preview-description').textContent = 'Your site description will appear here...';
            }
        }
    </script>
@endpush
