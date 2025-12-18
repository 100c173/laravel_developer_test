<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name') }}</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#10B981',
                        danger: '#EF4444',
                        warning: '#F59E0B',
                    }
                }
            }
        }
    </script>

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts - Arabic Support -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css">

    <style>
        * {
            font-family: {{ app( )->getLocale() === 'ar' ? "'Cairo', sans-serif" : "'Poppins', sans-serif" }};
        }

        body {
            background-color: #f8fafc;
        }

        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar.rtl {
            left: auto;
            right: 0;
        }

        .main-content {
            {{ app()->getLocale() === 'ar' ? 'margin-right: 280px;' : 'margin-left: 280px;' }}
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
            border-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}: 4px solid transparent;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}-color: white;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }

        .btn-primary {
            background-color: #3B82F6;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary:hover {
            background-color: #2563EB;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .btn-danger {
            background-color: #EF4444;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            background-color: #DC2626;
        }

        .btn-warning {
            background-color: #F59E0B;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-warning:hover {
            background-color: #D97706;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #1f2937;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #3B82F6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background-color: #ECFDF5;
            color: #065F46;
            border: 1px solid #A7F3D0;
        }

        .alert-danger {
            background-color: #FEF2F2;
            color: #7F1D1D;
            border: 1px solid #FECACA;
        }

        .alert-info {
            background-color: #F0F9FF;
            color: #0C2340;
            border: 1px solid #BAE6FD;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                {{ app()->getLocale() === 'ar' ? 'right: -280px;' : 'left: -280px;' }}
            }

            .sidebar.show {
                width: 280px;
            }

            .main-content {
                margin-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}: 0;
            }

            .sidebar-toggle {
                display: block;
            }
        }

        @media (min-width: 769px) {
            .sidebar-toggle {
                display: none;
            }
        }
    </style>

    @yield('extra_css')
</head>
<body>
    <!-- Sidebar Navigation -->
    <div class="sidebar" id="sidebar">
        <!-- Logo -->
        <div class="p-6 border-b border-white border-opacity-20">
            <h1 class="text-white text-2xl font-bold flex items-center gap-2">
                <i class="fas fa-box"></i>
                {{ config('app.name') }}
            </h1>
        </div>

        <!-- Navigation Menu -->
        <nav class="mt-6">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span>{{ app()->getLocale() === 'ar' ? 'لوحة التحكم' : 'Dashboard' }}</span>
            </a>

            <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="fas fa-boxes"></i>
                <span>{{ app()->getLocale() === 'ar' ? 'المنتجات' : 'Products' }}</span>
            </a>

            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>{{ app()->getLocale() === 'ar' ? 'المستخدمون' : 'Users' }}</span>
            </a>

            <a href="{{ route('admin.activity-logs.index') }}" class="nav-link {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">
                <i class="fas fa-history"></i>
                <span>{{ app()->getLocale() === 'ar' ? 'سجل الأنشطة' : 'Activity Logs' }}</span>
            </a>

            <hr class="my-4 border-white border-opacity-20">

            <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>
                <span>{{ app()->getLocale() === 'ar' ? 'تسجيل الخروج' : 'Logout' }}</span>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-50">
            <div class="flex items-center justify-between p-4 md:p-6">
                <!-- Sidebar Toggle Button (Mobile) -->
                <button class="sidebar-toggle text-gray-600 hover:text-gray-900" onclick="toggleSidebar()">
                    <i class="fas fa-bars text-2xl"></i>
                </button>

                <!-- Page Title -->
                <h2 class="text-2xl font-bold text-gray-800">@yield('page_title')</h2>

                <!-- Right Side Actions -->
                <div class="flex items-center gap-4">
                    <!-- Language Switcher -->
                    <select id="language-switcher" class="form-control w-32" onchange="changeLanguage(this.value)">
                        <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>English</option>
                        <option value="ar" {{ app()->getLocale() === 'ar' ? 'selected' : '' }}>العربية</option>
                    </select>

                    <!-- User Profile -->
                    <div class="flex items-center gap-3">
                        <span class="text-gray-700 font-medium">{{ auth()->user()->name ?? 'Admin' }}</span>
                        <img src="https://ui-avatars.com/api/?name={{ auth( )->user()->name ?? 'Admin' }}&background=667eea&color=fff" alt="Avatar" class="w-10 h-10 rounded-full">
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-4 md:p-6">
            <!-- Alerts -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <strong>{{ app()->getLocale() === 'ar' ? 'حدث خطأ!' : 'Error!' }}</strong>
                        <ul class="mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Page Content Yield -->
            @yield('content')
        </main>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>

    <script>
        // Set CSRF token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]' ).attr('content')
            }
        });

        // Language configuration
        const locale = '{{ app()->getLocale() }}';
        const isArabic = locale === 'ar';

        // Toggle Sidebar on Mobile
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        // Close sidebar when clicking on a link (mobile)
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    document.getElementById('sidebar').classList.remove('show');
                }
            });
        });

        // Change Language
        function changeLanguage(lang) {
            window.location.href = `/language/${lang}`;
        }

        // SweetAlert2 Configuration
        const Swal = window.Swal;
        Swal.mixin({
            toast: true,
            position: isArabic ? 'top-start' : 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });

        // Delete Confirmation
        function confirmDelete(url, itemName = '') {
            Swal.fire({
                title: isArabic ? 'تأكيد الحذف' : 'Confirm Delete',
                text: isArabic ? `هل أنت متأكد من حذف ${itemName}؟` : `Are you sure you want to delete ${itemName}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: isArabic ? 'نعم، احذف' : 'Yes, delete',
                cancelButtonText: isArabic ? 'إلغاء' : 'Cancel',
                reverseButtons: isArabic,
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }

        // Show Toast Message
        function showToast(message, type = 'success') {
            Swal.fire({
                icon: type,
                title: message,
            });
        }

        // Auto-hide alerts after 5 seconds
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                alert.style.display = 'none';
            }, 5000);
        });
    </script>

    @yield('extra_js')
</body>
</html>
