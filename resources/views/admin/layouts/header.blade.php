<header class="admin-header d-flex justify-content-between align-items-center p-3 mb-4">
    <div class="d-flex align-items-center">
        <button class="btn btn-outline-primary me-3 sidebar-toggle d-md-none" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>
        <h1 class="h4 mb-0">@yield('page-title', 'Dashboard')</h1>
    </div>

    <div class="d-flex align-items-center">
        <!-- Quick Actions -->
        <div class="me-3">
            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm" target="_blank">
                <i class="bi bi-eye me-1"></i> View Site
            </a>
        </div>

        <!-- User Menu -->
        <div class="dropdown">
            <button class="btn btn-outline-primary d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                <span class="me-2">{{ auth()->user()->name ?? 'Admin' }}</span>
                <i class="bi bi-person-circle"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><h6 class="dropdown-header">{{ auth()->user()->email ?? '' }}</h6></li>
                <li><span class="dropdown-item-text">
                    <small class="badge bg-{{ (auth()->user()->role ?? '') === 'developer' ? 'success' : 'primary' }}">
                        {{ ucfirst(auth()->user()->role ?? 'admin') }}
                    </small>
                </span></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-house me-2"></i>User Dashboard
                    </a></li>
                @if(Route::has('settings.profile'))
                    <li><a class="dropdown-item" href="{{ route('admin.settings.index') }}">
                            <i class="bi bi-person me-2"></i>Profile
                        </a></li>
                @endif
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
