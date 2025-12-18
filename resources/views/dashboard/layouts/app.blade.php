<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @include('dashboard.includes.head')
</head>

@stack('scripts')

<body>
    <!-- Sidebar -->
    @include('dashboard.layouts.sidebar')

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Bar -->
        @include('dashboard.includes.topbar')

        <!-- Content -->
        <div class="content">
            @yield('content')
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Notification button alert
            const notificationBtn = document.querySelector('.notification-btn');
            if (notificationBtn) {
                notificationBtn.addEventListener('click', function () {
                    const messages = {
                        'en': 'You have 3 unread notifications:\n1. New user registration\n2. Product review pending\n3. System update available',
                        'ar': 'لديك 3 إشعارات غير مقروءة:\n1. تسجيل مستخدم جديد\n2. مراجعة منتج معلقة\n3. تحديث النظام متاح'
                    };
                    alert(messages['{{ app()->getLocale() }}'] || messages['en']);
                });
            }

            // Account button alert
            const accountBtn = document.querySelector('.account-btn');
            if (accountBtn) {
                accountBtn.addEventListener('click', function () {
                    const messages = {
                        'en': 'Account options:\n- Profile Settings\n- Security\n- Billing\n- Logout',
                        'ar': 'خيارات الحساب:\n- إعدادات الملف الشخصي\n- الأمان\n- الفواتير\n- تسجيل الخروج'
                    };
                    alert(messages['{{ app()->getLocale() }}'] || messages['en']);
                });
            }

            // Quick actions hover effect
            const actionCards = document.querySelectorAll('.action-card');
            actionCards.forEach(card => {
                card.addEventListener('mouseenter', function () {
                    const icon = this.querySelector('.action-icon i');
                    icon.style.transform = 'scale(1.1)';
                });

                card.addEventListener('mouseleave', function () {
                    const icon = this.querySelector('.action-icon i');
                    icon.style.transform = 'scale(1)';
                });
            });

            // Animate stat cards on load
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 200);
            });

            // Mobile menu toggle (for responsive design)
            const createMobileToggle = () => {
                const mobileToggle = document.createElement('button');
                mobileToggle.className = 'mobile-toggle';
                mobileToggle.innerHTML = '<i class="fas fa-bars"></i>';
                mobileToggle.style.cssText = `
                    background: none;
                    border: none;
                    font-size: 24px;
                    color: var(--dark);
                    cursor: pointer;
                    display: none;
                    margin-right: 20px;
                `;

                document.querySelector('.topbar-left').prepend(mobileToggle);

                mobileToggle.addEventListener('click', function () {
                    document.querySelector('.sidebar').style.transform = 'translateX(0)';
                });

                const checkScreenSize = () => {
                    if (window.innerWidth <= 768) {
                        mobileToggle.style.display = 'block';
                    } else {
                        mobileToggle.style.display = 'none';
                        document.querySelector('.sidebar').style.transform = '';
                    }
                };

                checkScreenSize();
                window.addEventListener('resize', checkScreenSize);
            };

            createMobileToggle();
        });
    </script>
</body>
</html>