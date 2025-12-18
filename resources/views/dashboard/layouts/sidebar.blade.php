<aside class="sidebar">
    <div class="sidebar-header">
        <a href="{{route('admin.dashboard.index')}}" class="logo">
            <div class="logo-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <span class="logo-text">{{ __('dashboard.dashboard') }}</span>
        </a>
    </div>

    <div class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-title">{{ __('dashboard.main_menu') }}</div>
            <ul class="nav-links">
                <li>
                    <a href="{{route('admin.dashboard.products.index')}}" class="active">
                        <div class="nav-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <span class="nav-text">{{ __('dashboard.nav_products') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.dashboard.users.index')}}">
                        <div class="nav-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <span class="nav-text">{{ __('dashboard.nav_users') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.dashboard.activity-logs.index') }}">
                        <div class="nav-icon">
                            <i class="fas fa-history"></i>
                        </div>
                        <span class="nav-text">{{ __('dashboard.nav_activity_log') }}</span>
                    </a>
                </li>
            </ul>
        </div>

    </div>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="user-details">
                <div class="user-name">{{auth()->user()->name}}</div>
                <div class="user-role">{{ __('dashboard.administrator') }}</div>
            </div>
        </div>
    </div>
</aside>