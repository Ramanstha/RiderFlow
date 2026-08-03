<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Rider Portal - RiderFlow')</title>
    <link rel="icon" href="{{ asset('logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    @stack('styles')
</head>

@php($rider = auth('rider')->user())

<body class="pattern-bg min-h-screen">
    <!-- Mobile Menu Button -->
    <button id="mobileMenuBtn"
        class="lg:hidden fixed top-4 left-4 z-50 p-3 rounded-xl bg-[var(--bg-card)] border border-[var(--border)]">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside id="sidebar"
            class="sidebar fixed lg:sticky inset-y-0 lg:top-0 left-0 z-40 w-72 h-screen transform -translate-x-full lg:translate-x-0 transition-transform duration-300 flex flex-col">
            <!-- Logo -->
            <div class="p-6 border-b border-[var(--border)]">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-[var(--accent)] to-cyan-400 flex items-center justify-center glow-accent">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-900" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="font-display text-xl font-bold">RiderFlow</h1>
                        <p class="text-xs text-[var(--fg-secondary)]">Rider Portal</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto scrollbar-thin">
                <a href="{{ route('rider.dashboard') }}"
                    class="nav-item {{ request()->routeIs('rider.dashboard') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <div class="pt-4 mt-4 border-t border-[var(--border)]">
                    <p class="px-4 py-2 text-xs font-semibold text-[var(--fg-secondary)] uppercase tracking-wider">
                        My Work</p>

                    <a href="{{ route('rider.attendance.index') }}"
                        class="nav-item {{ request()->routeIs('rider.attendance.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>My Attendance</span>
                    </a>

                    <a href="{{ route('rider.assignment.index') }}"
                        class="nav-item {{ request()->routeIs('rider.assignment.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        <span>My Assignments</span>
                    </a>

                    <a href="{{ route('rider.parcel.index') }}"
                        class="nav-item {{ request()->routeIs('rider.parcel.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span>My Parcels</span>
                    </a>

                    <a href="{{ route('rider.performance.index') }}"
                        class="nav-item {{ request()->routeIs('rider.performance.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>My Performance</span>
                    </a>
                </div>

                <div class="pt-4 mt-4 border-t border-[var(--border)]">
                    <p class="px-4 py-2 text-xs font-semibold text-[var(--fg-secondary)] uppercase tracking-wider">
                        Account</p>

                    <a href="{{ route('rider.profile.edit') }}"
                        class="nav-item {{ request()->routeIs('rider.profile.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>My Profile</span>
                    </a>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-item w-full text-left">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>

                    <div class="nav-item" id="themeToggle">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 dark-icon" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 light-icon hidden" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>Dark Mode</span>
                    </div>
                </div>
            </nav>

            <!-- Rider Profile -->
            <div class="p-4 border-t border-[var(--border)]">
                <a href="{{ route('rider.profile.edit') }}"
                    class="flex items-center gap-3 p-3 rounded-xl hover:bg-[rgba(0,212,170,0.05)] cursor-pointer transition-all">
                    <img src="{{ $rider->photo ? asset('storage/'.$rider->photo) : 'https://ui-avatars.com/api/?name='.urlencode($rider->name) }}"
                        alt="{{ $rider->name }}" class="avatar">
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold truncate">{{ $rider->name }}</p>
                        <p class="text-sm text-[var(--fg-secondary)] truncate">{{ $rider->license_number }}</p>
                    </div>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 lg:ml-0 min-h-screen overflow-x-hidden">
            <!-- Header -->
            <header
                class="sticky top-0 z-30 bg-[var(--bg-primary)]/80 backdrop-blur-lg border-b border-[var(--border)] px-4 lg:px-8 py-4">
                <div class="flex items-center justify-between gap-4">
                    <div class="lg:hidden w-10"></div>
                    <div class="flex-1">
                        <p class="text-sm text-[var(--fg-secondary)]">{{ now()->format('l, d M Y') }}</p>
                    </div>
                    <span class="badge badge-{{ $rider->status->value === 'Active' ? 'success' : ($rider->status->value === 'Inactive' ? 'danger' : 'warning') }}">
                        {{ $rider->status->value }}
                    </span>
                </div>
            </header>

            <!-- Page Content Container -->
            <div id="pageContent" class="p-4 lg:p-8">
                @include('rider.partials.flash')
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.tailwindcss.com"></script>
    @stack('vendor-scripts')

    <script>
        // ==================== THEME ====================
        function initializeTheme() {
            const themeToggle = document.getElementById('themeToggle');
            const savedTheme = localStorage.getItem('theme') || 'dark';
            if (savedTheme === 'light') {
                document.body.classList.add('light-mode');
                updateThemeIcons(true);
            }
            themeToggle.addEventListener('click', () => {
                document.body.classList.toggle('light-mode');
                const isLight = document.body.classList.contains('light-mode');
                localStorage.setItem('theme', isLight ? 'light' : 'dark');
                updateThemeIcons(isLight);
                if (typeof updateChartsColors === 'function') updateChartsColors();
            });
        }

        function updateThemeIcons(isLight) {
            const darkIcon = document.querySelector('.dark-icon');
            const lightIcon = document.querySelector('.light-icon');
            if (isLight) {
                darkIcon.classList.add('hidden');
                lightIcon.classList.remove('hidden');
            } else {
                darkIcon.classList.remove('hidden');
                lightIcon.classList.add('hidden');
            }
        }
        // ==================== MOBILE MENU ====================
        function initializeMobileMenu() {
            const mobileBtn = document.getElementById('mobileMenuBtn');
            const sidebar = document.getElementById('sidebar');
            mobileBtn.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
            });
            document.addEventListener('click', (e) => {
                if (!sidebar.contains(e.target) && !mobileBtn.contains(e.target)) {
                    closeMobileMenu();
                }
            });
        }

        function closeMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth < 1024) {
                sidebar.classList.add('-translate-x-full');
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            initializeTheme();
            initializeMobileMenu();
        });
    </script>
    @stack('scripts')
</body>

</html>
