@extends('admin.layouts.app')

@section('title', 'Settings')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Settings</h1>
            <p class="text-muted">Manage your store configuration and preferences</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.settings.export') }}" class="btn btn-outline-primary">
                <i class="bi bi-download"></i> Export Settings
            </a>
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="bi bi-upload"></i> Import Settings
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="table-admin p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-gear-fill text-primary fs-2"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="fw-bold fs-4">{{ $stats['total_settings'] }}</div>
                        <div class="text-muted small">Total Settings</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="table-admin p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-eye-fill text-success fs-2"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="fw-bold fs-4">{{ $stats['public_settings'] }}</div>
                        <div class="text-muted small">Public Settings</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="table-admin p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-shield-fill text-warning fs-2"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="fw-bold fs-4">{{ $stats['system_settings'] }}</div>
                        <div class="text-muted small">System Settings</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="table-admin p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-collection-fill text-info fs-2"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="fw-bold fs-4">{{ $stats['groups'] }}</div>
                        <div class="text-muted small">Setting Groups</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Categories -->
    <div class="row">
        <!-- General Settings -->
        <div class="col-lg-6 mb-4">
            <div class="table-admin p-4 h-100">
                <div class="d-flex align-items-start">
                    <div class="flex-shrink-0">
                        <i class="bi bi-gear text-primary fs-1"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="mb-2">General Settings</h5>
                        <p class="text-muted mb-3">Configure basic site information, branding, and regional preferences</p>
                        <ul class="list-unstyled small text-muted mb-3">
                            <li>• Site name, logo, and branding</li>
                            <li>• Timezone and currency settings</li>
                            <li>• Date and time formats</li>
                            <li>• Language preferences</li>
                        </ul>
                        <a href="{{ route('admin.settings.general') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-arrow-right me-1"></i> Configure
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Store Settings -->
        <div class="col-lg-6 mb-4">
            <div class="table-admin p-4 h-100">
                <div class="d-flex align-items-start">
                    <div class="flex-shrink-0">
                        <i class="bi bi-shop text-success fs-1"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="mb-2">Store Settings</h5>
                        <p class="text-muted mb-3">Manage store information, tax rates, and customer features</p>
                        <ul class="list-unstyled small text-muted mb-3">
                            <li>• Store address and contact info</li>
                            <li>• Tax rates and calculation</li>
                            <li>• Customer features (reviews, wishlist)</li>
                            <li>• Store policies</li>
                        </ul>
                        <a href="{{ route('admin.settings.store') }}" class="btn btn-success btn-sm">
                            <i class="bi bi-arrow-right me-1"></i> Configure
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Settings -->
        <div class="col-lg-6 mb-4">
            <div class="table-admin p-4 h-100">
                <div class="d-flex align-items-start">
                    <div class="flex-shrink-0">
                        <i class="bi bi-credit-card text-info fs-1"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="mb-2">Payment Settings</h5>
                        <p class="text-muted mb-3">Configure payment gateways and transaction settings</p>
                        <ul class="list-unstyled small text-muted mb-3">
                            <li>• Payment gateway configuration</li>
                            <li>• Stripe, PayPal integration</li>
                            <li>• Transaction fees and limits</li>
                            <li>• Refund policies</li>
                        </ul>
                        <a href="{{ route('admin.settings.payment') }}" class="btn btn-info btn-sm">
                            <i class="bi bi-arrow-right me-1"></i> Configure
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Settings -->
        <div class="col-lg-6 mb-4">
            <div class="table-admin p-4 h-100">
                <div class="d-flex align-items-start">
                    <div class="flex-shrink-0">
                        <i class="bi bi-truck text-warning fs-1"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="mb-2">Shipping Settings</h5>
                        <p class="text-muted mb-3">Set up shipping zones, rates, and delivery options</p>
                        <ul class="list-unstyled small text-muted mb-3">
                            <li>• Shipping zones and rates</li>
                            <li>• Free shipping thresholds</li>
                            <li>• Delivery time estimates</li>
                            <li>• Shipping carriers integration</li>
                        </ul>
                        <a href="{{ route('admin.settings.shipping') }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-arrow-right me-1"></i> Configure
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Email Settings -->
        <div class="col-lg-6 mb-4">
            <div class="table-admin p-4 h-100">
                <div class="d-flex align-items-start">
                    <div class="flex-shrink-0">
                        <i class="bi bi-envelope text-danger fs-1"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="mb-2">Email Settings</h5>
                        <p class="text-muted mb-3">Configure SMTP settings and email templates</p>
                        <ul class="list-unstyled small text-muted mb-3">
                            <li>• SMTP server configuration</li>
                            <li>• Email templates customization</li>
                            <li>• Notification preferences</li>
                            <li>• Email testing tools</li>
                        </ul>
                        <a href="{{ route('admin.settings.email') }}" class="btn btn-danger btn-sm">
                            <i class="bi bi-arrow-right me-1"></i> Configure
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEO Settings -->
        <div class="col-lg-6 mb-4">
            <div class="table-admin p-4 h-100">
                <div class="d-flex align-items-start">
                    <div class="flex-shrink-0">
                        <i class="bi bi-search text-primary fs-1"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="mb-2">SEO Settings</h5>
                        <p class="text-muted mb-3">Optimize your store for search engines</p>
                        <ul class="list-unstyled small text-muted mb-3">
                            <li>• Meta tags and descriptions</li>
                            <li>• Sitemap configuration</li>
                            <li>• Social media integration</li>
                            <li>• Analytics tracking codes</li>
                        </ul>
                        <a href="{{ route('admin.settings.seo') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-arrow-right me-1"></i> Configure
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Settings Row -->
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="table-admin p-4 h-100">
                <div class="text-center">
                    <i class="bi bi-shield-lock text-warning fs-1 mb-3"></i>
                    <h6>Security Settings</h6>
                    <p class="text-muted small mb-3">Configure security features and access controls</p>
                    <a href="{{ route('admin.settings.security') }}" class="btn btn-outline-warning btn-sm">Configure</a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="table-admin p-4 h-100">
                <div class="text-center">
                    <i class="bi bi-code-square text-info fs-1 mb-3"></i>
                    <h6>API Settings</h6>
                    <p class="text-muted small mb-3">Manage API keys and integrations</p>
                    <a href="{{ route('admin.settings.api') }}" class="btn btn-outline-info btn-sm">Configure</a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="table-admin p-4 h-100">
                <div class="text-center">
                    <i class="bi bi-tools text-secondary fs-1 mb-3"></i>
                    <h6>Maintenance</h6>
                    <p class="text-muted small mb-3">System maintenance and backup tools</p>
                    <a href="{{ route('admin.settings.maintenance') }}" class="btn btn-outline-secondary btn-sm">Configure</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Updates -->
    <div class="table-admin p-4">
        <h6 class="mb-3">Recent Setting Updates</h6>
        @if($recentUpdates->count() > 0)
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                    <tr>
                        <th>Setting</th>
                        <th>Group</th>
                        <th>Updated</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($recentUpdates as $setting)
                        <tr>
                            <td>
                                <strong>{{ $setting->key }}</strong>
                                <br><small class="text-muted">{{ $setting->description }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ ucfirst($setting->group) }}</span>
                            </td>
                            <td>
                                <small>{{ $setting->updated_at->diffForHumans() }}</small>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted">No recent updates</p>
        @endif
    </div>

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Settings</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.settings.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="settings_file" class="form-label">Settings File</label>
                            <input type="file" class="form-control" id="settings_file" name="settings_file" accept=".json" required>
                            <div class="form-text">Select a JSON file exported from this system</div>
                        </div>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Warning:</strong> Importing settings will overwrite existing configurations. Make sure to export your current settings first as a backup.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Import Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
