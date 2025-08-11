<div class="p-3">
    <!-- Logo -->
    <div class="text-center mb-4">
        <h4 class="text-white mb-0">{{ config('app.name') }}</h4>
        <small class="text-white-50">Admin Panel</small>
    </div>

    <!-- Navigation -->
    <nav class="nav flex-column">
        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <!-- Banners -->
        <a class="nav-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}" href="{{ route('admin.banners.index') }}">
            <i class="bi bi-images"></i> Banners
        </a>

        <!-- Promo Banners -->
        <a class="nav-link {{ request()->routeIs('admin.promo-banners.*') ? 'active' : '' }}" href="{{ route('admin.promo-banners.index') }}">
            <i class="bi bi-megaphone"></i> Promo Banners
        </a>

        <!-- Lookbooks -->
        <a class="nav-link {{ request()->routeIs('admin.lookbooks.*') ? 'active' : '' }}" href="{{ route('admin.lookbooks.index') }}">
            <i class="bi bi-collection"></i> Lookbooks
        </a>

        <a class="nav-link {{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}" href="{{ route('admin.gallery.index') }}">
            <i class="bi bi-camera"></i> Gallery
        </a>

        <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
            <i class="bi bi-box-seam"></i> Products
            @if(isset($pendingProducts) && $pendingProducts > 0)
                <span class="badge bg-warning ms-auto">{{ $pendingProducts }}</span>
            @endif
        </a>

        <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
            <i class="bi bi-grid-3x3-gap"></i> Categories
        </a>

        <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
            <i class="bi bi-cart-check"></i> Orders
            @if(isset($pendingOrders) && $pendingOrders > 0)
                <span class="badge bg-danger ms-auto">{{ $pendingOrders }}</span>
            @endif
        </a>

        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
            <i class="bi bi-people"></i> Users
            @if(isset($unverifiedUsers) && $unverifiedUsers > 0)
                <span class="badge bg-info ms-auto">{{ $unverifiedUsers }}</span>
            @endif
        </a>

        <a class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}" href="{{ route('admin.coupons.index') }}">
            <i class="bi bi-percent"></i> Coupons
            @if(isset($activeCoupons) && $activeCoupons > 0)
                <span class="badge bg-success ms-auto">{{ $activeCoupons }}</span>
            @endif
        </a>

        <a class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}" href="{{ route('admin.reviews.index') }}">
            <i class="bi bi-star"></i> Reviews
            @if(isset($pendingReviews) && $pendingReviews > 0)
                <span class="badge bg-warning ms-auto">{{ $pendingReviews }}</span>
            @endif
        </a>

        <!-- Quick Actions Dropdown -->
        <div class="nav-item dropdown mt-2">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-lightning"></i> Quick Actions
            </a>
            <ul class="dropdown-menu dropdown-menu-dark">
                <li><a class="dropdown-item" href="{{ route('admin.banners.create') }}">
                        <i class="bi bi-plus-circle me-2"></i> Add Banner
                    </a></li>
                <li><a class="dropdown-item" href="{{ route('admin.promo-banners.create') }}">
                        <i class="bi bi-plus-circle me-2"></i> Add Promo Banner
                    </a></li>
                <li><a class="dropdown-item" href="{{ route('admin.lookbooks.create') }}">
                        <i class="bi bi-plus-circle me-2"></i> Add Lookbook
                    </a></li>
                <li><a class="dropdown-item" href="{{ route('admin.products.create') }}">
                        <i class="bi bi-plus-circle me-2"></i> Add Product
                    </a></li>
                <li><a class="dropdown-item" href="{{ route('admin.categories.create') }}">
                        <i class="bi bi-plus-circle me-2"></i> Add Category
                    </a></li>
                <li><a class="dropdown-item" href="{{ route('admin.users.create') }}">
                        <i class="bi bi-person-plus me-2"></i> Add User
                    </a></li>
                <li><a class="dropdown-item" href="{{ route('admin.coupons.create') }}">
                        <i class="bi bi-plus-circle me-2"></i> Add Coupon
                    </a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{{ route('home') }}" target="_blank">
                        <i class="bi bi-box-arrow-up-right me-2"></i> View Store
                    </a></li>
            </ul>
        </div>

        @if(($isDeveloper ?? false) || (auth()->user() && auth()->user()->role === 'developer'))
            <hr class="my-3" style="border-color: rgba(255,255,255,0.2);">
            <small class="text-white-50 px-3">Developer Tools</small>

            <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                <i class="bi bi-gear"></i> Settings
            </a>

            <a class="nav-link" href="#" onclick="showSystemInfo()">
                <i class="bi bi-info-circle"></i> System Info
            </a>

            <a class="nav-link" href="#" onclick="clearCache()">
                <i class="bi bi-arrow-clockwise"></i> Clear Cache
            </a>
        @endif

        <!-- Impersonation Notice -->
        @if(session()->has('impersonate_original_user'))
            <div class="alert alert-warning mt-3 py-2 px-3" style="font-size: 0.8rem;">
                <i class="bi bi-person-circle me-1"></i>
                <strong>Impersonating User</strong>
                <form action="{{ route('admin.stop-impersonating') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-warning mt-1 w-100">
                        <i class="bi bi-arrow-left me-1"></i> Return to Admin
                    </button>
                </form>
            </div>
        @endif
    </nav>

    <!-- User Info at Bottom -->
    <div class="mt-auto pt-3" style="position: absolute; bottom: 20px; left: 20px; right: 20px;">
        <div class="d-flex align-items-center">
            <div class="bg-primary rounded-circle text-white d-flex align-items-center justify-content-center me-2"
                 style="width: 32px; height: 32px; font-size: 0.8rem;">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
            <div class="flex-grow-1">
                <div class="text-white small fw-bold">{{ auth()->user()->name ?? 'Admin' }}</div>
                <div class="text-white-50" style="font-size: 0.7rem;">
                    {{ ucfirst(auth()->user()->role ?? 'Admin') }}
                </div>
            </div>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-light dropdown-toggle border-0" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
                    <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">
                            <i class="bi bi-person me-2"></i> My Profile
                        </a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.settings.index') }}">
                            <i class="bi bi-gear me-2"></i> Account Settings
                        </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline w-100">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Developer Tools Scripts -->
@if(($isDeveloper ?? false) || (auth()->user() && auth()->user()->role === 'developer'))
    <script>
        function showSystemInfo() {
            const info = `
System Information:
• Laravel Version: {{ app()->version() }}
            • PHP Version: {{ PHP_VERSION }}
            • Environment: {{ app()->environment() }}
            • Database: {{ config('database.default') }}
            • Cache Driver: {{ config('cache.default') }}
            • Queue Driver: {{ config('queue.default') }}
            • Mail Driver: {{ config('mail.default') }}
            • Storage Driver: {{ config('filesystems.default') }}
            • Session Driver: {{ config('session.driver') }}
            • Debug Mode: {{ config('app.debug') ? 'ON' : 'OFF' }}
            `;
            alert(info);
        }

        function clearCache() {
            if (confirm('Clear application cache? This will clear all cached data.')) {
                fetch('/admin/clear-cache', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Cache cleared successfully!');
                        } else {
                            alert('Error clearing cache');
                        }
                    })
                    .catch(() => {
                        alert('Feature coming soon!');
                    });
            }
        }
    </script>
@endif
