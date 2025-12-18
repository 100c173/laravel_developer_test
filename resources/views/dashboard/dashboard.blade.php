@extends('layouts.app')

@section('content')
@vite(['resources/css/app.css', 'resources/css/dashboard.css'])

<div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('dashboard') }}" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <span class="logo-text">Dashboard</span>
            </a>
        </div>
        
        <div class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-title">Main Menu</div>
                <ul class="nav-links">
                    <li>
                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <div class="nav-icon">
                                <i class="fas fa-tachometer-alt"></i>
                            </div>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('products') }}" class="{{ request()->routeIs('products') ? 'active' : '' }}">
                            <div class="nav-icon">
                                <i class="fas fa-box"></i>
                            </div>
                            <span class="nav-text">Products</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('users') }}" class="{{ request()->routeIs('users') ? 'active' : '' }}">
                            <div class="nav-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <span class="nav-text">Users</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('gallery') }}" class="{{ request()->routeIs('gallery') ? 'active' : '' }}">
                            <div class="nav-icon">
                                <i class="fas fa-images"></i>
                            </div>
                            <span class="nav-text">Gallery</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('activity') }}" class="{{ request()->routeIs('activity') ? 'active' : '' }}">
                            <div class="nav-icon">
                                <i class="fas fa-history"></i>
                            </div>
                            <span class="nav-text">Activity Log</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="nav-section">
                <div class="nav-title">Management</div>
                <ul class="nav-links">
                    <li>
                        <a href="#">
                            <div class="nav-icon">
                                <i class="fas fa-cog"></i>
                            </div>
                            <span class="nav-text">Settings</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <div class="nav-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <span class="nav-text">Analytics</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <div class="nav-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <span class="nav-text">Reports</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">
                    @if(Auth::user()->profile_photo_path)
                        <img src="{{ Auth::user()->profile_photo_path }}" alt="{{ Auth::user()->name }}">
                    @else
                        <i class="fas fa-user"></i>
                    @endif
                </div>
                <div class="user-details">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-role">
                        @if(Auth::user()->is_admin)
                            Administrator
                        @else
                            User
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Bar -->
        <header class="topbar">
            <div class="topbar-left">
                <h1 class="page-title">
                    @yield('page-title', 'Dashboard Overview')
                </h1>
            </div>
            <div class="topbar-right">
                <button class="notification-btn">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </button>
                
                <!-- Dropdown Menu للمستخدم -->
                <div class="user-dropdown">
                    <button class="account-btn">
                        <i class="fas fa-user-circle"></i>
                        <span class="user-name-mobile">{{ Str::limit(Auth::user()->name, 10) }}</span>
                    </button>
                    <div class="dropdown-menu">
                        <a href="{{ route('profile.edit') }}" class="dropdown-item">
                            <i class="fas fa-user-cog"></i> Profile Settings
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item logout-btn">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="content">
            @yield('dashboard-content')
        </div>
    </main>
</div>

@yield('scripts')
@endsection