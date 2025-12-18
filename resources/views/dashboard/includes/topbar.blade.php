<header class="topbar">
    <div class="topbar-left">
        <h1 class="page-title">{{ __('dashboard.page_title') }}</h1>
    </div>

    <div class="topbar-right flex items-center gap-4">
        <!-- Notifications -->
        <button class="relative text-gray-600 hover:text-gray-800">
            <i class="fas fa-bell text-lg"></i>
            <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full px-1">
                3
            </span>
        </button>

        <!-- User Dropdown -->
        <div class="hidden sm:flex sm:items-center sm:ms-4">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button
                        class="inline-flex items-center gap-2 px-3 py-2 rounded-md bg-white dark:bg-gray-800 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-gray-800 focus:outline-none transition">
                        <i class="fas fa-user-circle text-xl"></i>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <!-- Profile -->
                    <x-dropdown-link :href="route('profile.edit')">
                        <i class="fas fa-user mr-2"></i> {{ Auth::user()->full_name }}
                    </x-dropdown-link>

                    <!-- Language -->
                    <div class="px-4 py-2 text-xs text-gray-400">
                        {{ __('dashboard.language') }}
                    </div>

                    <x-dropdown-link :href="route('language.switch', 'ar')">
                        🇸🇦 AR
                    </x-dropdown-link>

                    <x-dropdown-link :href="route('language.switch', 'en')">
                        🇺🇸 EN
                    </x-dropdown-link>

                    <div class="border-t my-1"></div>

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="fas fa-sign-out-alt mr-2"></i> {{ __('dashboard.log_out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </div>
</header>