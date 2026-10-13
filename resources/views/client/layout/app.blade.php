<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Client Portal - RiderFlow')</title>
    <link rel="icon" href="{{ asset('logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    @stack('styles')
</head>

@php($client = auth('client')->user())

<body class="pattern-bg min-h-screen">
    <div id="sidebarBackdrop" class="sidebar-backdrop" aria-hidden="true"></div>

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside id="sidebar"
            class="sidebar fixed lg:sticky inset-y-0 lg:top-0 left-0 z-40 w-72 max-w-[85vw] h-screen h-[100dvh] lg:h-screen shrink-0 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 flex flex-col">
            <!-- Logo -->
            <div class="p-6 border-b border-[var(--border)]">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-[var(--accent)] to-cyan-400 flex items-center justify-center glow-accent">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-900" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="font-display text-xl font-bold">RiderFlow</h1>
                        <p class="text-xs text-[var(--fg-secondary)]">Client Portal</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto scrollbar-thin">
                <a href="{{ route('client.dashboard') }}"
                    class="nav-item {{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <div class="pt-4 mt-4 border-t border-[var(--border)]">
                    <p class="px-4 py-2 text-xs font-semibold text-[var(--fg-secondary)] uppercase tracking-wider">
                        My Fleet</p>

                    <a href="{{ route('client.rider.index') }}"
                        class="nav-item {{ request()->routeIs('client.rider.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>My Riders</span>
                    </a>

                    <a href="{{ route('client.assignment.index') }}"
                        class="nav-item {{ request()->routeIs('client.assignment.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        <span>Assignments</span>
                    </a>

                    <a href="{{ route('client.attendance.index') }}"
                        class="nav-item {{ request()->routeIs('client.attendance.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Attendance</span>
                    </a>

                    <a href="{{ route('client.parcel-assignment.index') }}"
                        class="nav-item {{ request()->routeIs('client.parcel-assignment.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Assign Parcels</span>
                    </a>

                    <a href="{{ route('client.parcel.index') }}"
                        class="nav-item {{ request()->routeIs('client.parcel.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span>Parcels</span>
                    </a>

                    <a href="{{ route('client.performance.index') }}"
                        class="nav-item {{ request()->routeIs('client.performance.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>Performance</span>
                    </a>
                </div>

                <div class="pt-4 mt-4 border-t border-[var(--border)]">
                    <p class="px-4 py-2 text-xs font-semibold text-[var(--fg-secondary)] uppercase tracking-wider">
                        Reports</p>

                    <a href="{{ route('client.reports.index') }}"
                        class="nav-item {{ request()->routeIs('client.reports.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Reports &amp; Exports</span>
                    </a>
                </div>

                <!-- <div class="pt-4 mt-4 border-t border-[var(--border)]">
                    <p class="px-4 py-2 text-xs font-semibold text-[var(--fg-secondary)] uppercase tracking-wider">
                        Account</p>

                    <a href="{{ route('client.profile.edit') }}"
                        class="nav-item {{ request()->routeIs('client.profile.*') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Company Profile</span>
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
                </div> -->
            </nav>

            <!-- Company Profile -->
            <!-- <div class="p-4 border-t border-[var(--border)]">
                <a href="{{ route('client.profile.edit') }}"
                    class="flex items-center gap-3 p-3 rounded-xl hover:bg-[rgba(0,212,170,0.05)] cursor-pointer transition-all">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($client->name) }}" alt="{{ $client->name }}"
                        class="avatar">
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold truncate">{{ $client->name }}</p>
                        <p class="text-sm text-[var(--fg-secondary)] truncate">{{ $client->contact_person }}</p>
                    </div>
                </a>
            </div> -->
        </aside>

        <!-- Main Content -->
        {{-- min-w-0 lets wide content shrink inside the flex row; overflow-x-clip (not
             hidden) avoids creating a scroll container, which would break the sticky header. --}}
        <main class="flex-1 min-w-0 min-h-screen overflow-x-clip">
            <!-- Header -->
            <header
                class="sticky top-0 z-30 bg-[var(--bg-primary)]/80 backdrop-blur-lg border-b border-[var(--border)] px-3 sm:px-4 lg:px-8 py-3 lg:py-4">
                <div class="flex items-center justify-between gap-2 sm:gap-4">
                    <button type="button" id="mobileMenuBtn" aria-controls="sidebar" aria-expanded="false"
                        aria-label="Open navigation"
                        class="lg:hidden p-2.5 rounded-xl bg-[var(--bg-card)] border border-[var(--border)] shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs sm:text-sm text-[var(--fg-secondary)] truncate">{{ bs(now(), 'l, d M Y') }}</p>
                    </div>
                    <button type="button"
                        class="theme-toggle relative p-2.5 sm:p-3 rounded-xl hover:bg-[var(--bg-card)] transition-all shrink-0"
                        aria-label="Toggle theme" aria-pressed="false">
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
                    </button>

                    <span class="badge badge-{{ $client->status->value === 'Active' ? 'success' : 'danger' }} hidden sm:inline-flex">
                        {{ $client->status->value }}
                    </span>
                    <div class="profile relative shrink-0">
                        <button type="button" id="profileMenuBtn" aria-haspopup="true" aria-expanded="false"
                            class="flex items-center gap-2 p-1 rounded-xl hover:bg-[var(--bg-card)] transition-all">
                            <img class="avatar-up" src="https://ui-avatars.com/api/?name={{ urlencode($client->name) }}"
                                alt="{{ $client->name }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[var(--fg-secondary)]"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div id="profileMenu"
                            class="hidden absolute right-0 mt-2 w-56 max-w-[calc(100vw-1.5rem)] rounded-xl border border-[var(--border)] bg-[var(--bg-card)] shadow-xl overflow-hidden z-50">
                            <div class="px-4 py-3 border-b border-[var(--border)] text-center">
                                <img class="avatar-down text-center mx-auto mb-2"
                                    src="https://ui-avatars.com/api/?name={{ urlencode($client->name) }}"
                                    alt="{{ $client->name }}">
                                <p class="capitalize font-semibold truncate">{{ $client->name }}</p>
                                <p class="capitalize text-sm text-[var(--fg-secondary)] truncate">{{ $client->contact_person }}</p>
                                <p class="text-sm text-[var(--fg-secondary)] truncate">{{ $client->email }}</p>
                                <span class="badge badge-{{ $client->status->value === 'Active' ? 'success' : 'danger' }} mt-2 sm:hidden">
                                    {{ $client->status->value }}
                                </span>
                            </div>
                            <a href="{{ route('client.profile.edit') }}"
                                class="flex items-center gap-3 px-4 py-3 text-sm hover:bg-[rgba(0,212,170,0.1)] hover:text-[var(--accent)] transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>Profile Settings</span>
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="border-t border-[var(--border)]">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-3 text-sm text-left text-red-500 hover:bg-red-500/10 transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content Container -->
            <div id="pageContent" class="px-4 py-5 sm:p-6 lg:p-8">
                @include('client.partials.flash')
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.tailwindcss.com"></script>
    @include('partials.nepali-date-scripts')
    @stack('vendor-scripts')

    <script>
        // ==================== THEME ====================
        function initializeTheme() {
            // Both the sidebar nav item and the header button toggle the theme.
            const toggles = document.querySelectorAll('#themeToggle, .theme-toggle');
            const savedTheme = localStorage.getItem('theme') || 'dark';
            if (savedTheme === 'light') {
                document.body.classList.add('light-mode');
            }
            updateThemeIcons(savedTheme === 'light');
            toggles.forEach((toggle) => {
                toggle.addEventListener('click', () => {
                    document.body.classList.toggle('light-mode');
                    const isLight = document.body.classList.contains('light-mode');
                    localStorage.setItem('theme', isLight ? 'light' : 'dark');
                    if (typeof updateChartsColors === 'function') updateChartsColors();
                    updateThemeIcons(isLight);
                });
            });
        }

        function updateThemeIcons(isLight) {
            // Every toggle carries its own icon pair, so update them all.
            document.querySelectorAll('.dark-icon').forEach((icon) => icon.classList.toggle('hidden', isLight));
            document.querySelectorAll('.light-icon').forEach((icon) => icon.classList.toggle('hidden', !isLight));
            document.querySelectorAll('.theme-toggle').forEach((btn) => btn.setAttribute('aria-pressed', String(isLight)));
        }
        // ==================== MOBILE MENU ====================
        function setMobileMenu(open) {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const mobileBtn = document.getElementById('mobileMenuBtn');
            sidebar.classList.toggle('-translate-x-full', !open);
            backdrop?.classList.toggle('is-open', open);
            mobileBtn?.setAttribute('aria-expanded', String(open));
            document.body.style.overflow = open ? 'hidden' : '';
        }

        function initializeMobileMenu() {
            const mobileBtn = document.getElementById('mobileMenuBtn');
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            mobileBtn.addEventListener('click', () => {
                setMobileMenu(sidebar.classList.contains('-translate-x-full'));
            });
            backdrop?.addEventListener('click', closeMobileMenu);
            sidebar.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeMobileMenu));
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeMobileMenu();
            });
            // Rotating a tablet to desktop width must not leave the page scroll-locked.
            window.matchMedia('(min-width: 1024px)').addEventListener('change', (e) => {
                if (e.matches) {
                    backdrop?.classList.remove('is-open');
                    document.body.style.overflow = '';
                }
            });
        }
        // ==================== PROFILE DROPDOWN ====================
        function initializeProfileMenu() {
            const btn = document.getElementById('profileMenuBtn');
            const menu = document.getElementById('profileMenu');
            if (!btn || !menu) return;
            const closeProfileMenu = () => {
                menu.classList.add('hidden');
                btn.setAttribute('aria-expanded', 'false');
            };
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isOpen = !menu.classList.contains('hidden');
                menu.classList.toggle('hidden', isOpen);
                btn.setAttribute('aria-expanded', String(!isOpen));
            });
            document.addEventListener('click', (e) => {
                if (!menu.contains(e.target) && !btn.contains(e.target)) {
                    closeProfileMenu();
                }
            });
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeProfileMenu();
            });
        }

        function closeMobileMenu() {
            if (window.innerWidth < 1024) {
                setMobileMenu(false);
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            initializeTheme();
            initializeMobileMenu();
            initializeProfileMenu();
        });
    </script>
    <script src="{{ asset('backend/js/number-input.js') }}?v={{ filemtime(public_path('backend/js/number-input.js')) }}"></script>
    @stack('scripts')
</body>

</html>