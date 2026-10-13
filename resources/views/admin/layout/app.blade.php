@php
$unreadNotificationsCount = auth()->user()?->unreadNotifications()->count() ?? 0;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'RiderFlow')</title>
    <link rel="icon" href="{{ asset('logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    @stack('styles')
</head>

<body class="pattern-bg min-h-screen">
    <!-- Mobile Menu Button -->
    <button id="mobileMenuBtn"
        class="lg:hidden absolute top-4 left-4 z-50 p-3 rounded-xl bg-[var(--bg-card)] border border-[var(--border)]">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside id="sidebar"
            class="sidebar fixed lg:sticky inset-y-0 lg:top-0 left-0 z-40 w-60 h-screen transform -translate-x-full lg:translate-x-0 transition-transform duration-300 flex flex-col">
            <!-- Logo -->
            <div class="p-6 border-b border-[var(--border)]">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('logo.png') }}" alt="RiderFlow Logo" class="w-10 h-10 rounded-full">
                    <div>
                        <h1 class="font-display text-xl font-bold">RiderFlow</h1>
                        <p class="text-xs text-[var(--fg-secondary)]">Delivery Solutions</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto scrollbar-thin">
                <a href="{{ route('dashboard') }}"
                    class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" data-page="dashboard">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                @can('users.manage')
                <div class="pt-4 mt-4 border-t border-[var(--border)]">
                    <p class="px-4 py-2 text-xs font-semibold text-[var(--fg-secondary)] uppercase tracking-wider">
                        Admins/Roles</p>

                    <a href="{{ route('role.index') }}"
                        class="nav-item {{ request()->routeIs('role.*') ? 'active' : '' }}" data-page="Roles">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Roles</span>
                    </a>

                    <a href="{{ route('user.index') }}"
                        class="nav-item {{ request()->routeIs('user.*') ? 'active' : '' }}" data-page="users">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Admins</span>
                    </a>
                </div>
                @endcan

                <div class="pt-4 mt-4 border-t border-[var(--border)]">
                    <p class="px-4 py-2 text-xs font-semibold text-[var(--fg-secondary)] uppercase tracking-wider">
                        Managment</p>
                    @can('riders.view')
                    <a href="{{ route('rider.index') }}"
                        class="nav-item {{ request()->routeIs('rider.*') ? 'active' : '' }}" data-page="riders">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Rider Management</span>
                    </a>
                    @endcan

                    @can('companies.view')
                    <a href="{{ route('company.index') }}"
                        class="nav-item {{ request()->routeIs('company.*') ? 'active' : '' }}" data-page="businesses">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span>Client Businesses</span>
                    </a>
                    @endcan

                    @can('assignments.view')
                    <a href="{{ route('assignment.index') }}"
                        class="nav-item {{ request()->routeIs('assignment.*') ? 'active' : '' }}"
                        data-page="assignments">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        <span>Rider Assignments</span>
                    </a>
                    @endcan

                    @can('attendances.view')
                    <a href="{{ route('attendance.index') }}"
                        class="nav-item {{ request()->routeIs('attendance.*') ? 'active' : '' }}"
                        data-page="attendance">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Attendance</span>
                    </a>
                    @endcan

                    @can('parcels.view')
                    <a href="{{ route('parcel.index') }}"
                        class="nav-item {{ request()->routeIs('parcel.*') ? 'active' : '' }}" data-page="parcels">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span>Parcel Tracking</span>
                    </a>
                    @endcan

                    @can('performances.view')
                    <a href="{{ route('performance.index') }}"
                        class="nav-item {{ request()->routeIs('performance.*') ? 'active' : '' }}"
                        data-page="performance">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>Performance</span>
                    </a>
                    @endcan

                    @can('reports.view')
                    <a href="{{ route('reports.index') }}"
                        class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}" data-page="reports">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Reports</span>
                    </a>
                    @endcan
                </div>
                @can('advance.view')
                <div class="pt-4 mt-4 border-t border-[var(--border)]">
                    <p class="px-4 py-2 text-xs font-semibold text-[var(--fg-secondary)] uppercase tracking-wider">
                        Finance</p>
                    <a href="{{ route('advance.index') }}"
                        class="nav-item {{ request()->routeIs('advance.*') ? 'active' : '' }}" data-page="advances">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Advance Salary</span>
                    </a>
                </div>
                @endcan
                
                @can('site.manage')
                <div class="pt-4 mt-4 border-t border-[var(--border)]">
                    <p class="px-4 py-2 text-xs font-semibold text-[var(--fg-secondary)] uppercase tracking-wider">
                        Site Settings</p>

                    
                    <a href="{{ route('banner.index') }}"
                        class="nav-item {{ request()->routeIs('banner.*') ? 'active' : '' }}" data-page="banners">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16">
                            <path fill="currentColor"
                                d="M9 15H7v-2h2zm3-5.618a1 1 0 0 1-.553.894l-3 1.5a1 1 0 0 1-.894 0l-3-1.5A1 1 0 0 1 4 9.382V4h8zM12 1a1 1 0 1 1 0 2H4a1 1 0 0 1 0-2z" />
                        </svg>
                        <span>Banner</span>
                    </a>

                    <a href="{{ route('contact-info.index') }}"
                        class="nav-item {{ request()->routeIs('contact-info.*') ? 'active' : '' }}"
                        data-page="contacts">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span>Contact Us</span>
                    </a>

                    <a href="{{ route('testimonial.index') }}"
                        class="nav-item {{ request()->routeIs('testimonial.*') ? 'active' : '' }}" data-page="contacts">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 48 48">
                            <path fill="currentColor"
                                d="M16 24a8 8 0 1 0 0-16a8 8 0 0 0 0 16m18 0a6 6 0 1 0 0-12a6 6 0 0 0 0 12M6.75 27A3.75 3.75 0 0 0 3 30.75V32s0 9 13 9s13-9 13-9v-1.25A3.75 3.75 0 0 0 25.25 27zm21.924 11.089c1.376.558 3.119.911 5.325.911c10.5 0 10.5-8 10.5-8v-.25A3.75 3.75 0 0 0 40.75 27H29.607a5.73 5.73 0 0 1 1.391 3.75v1.295l-.001.057l-.006.15q-.008.173-.035.43a10 10 0 0 1-.24 1.325a10.7 10.7 0 0 1-2.042 4.082" />
                        </svg>
                        <span>Testimonial</span>
                    </a>

                    <a href="{{ route('contact-message.index') }}"
                        class="nav-item {{ request()->routeIs('contact-message.*') ? 'active' : '' }}"
                        data-page="contacts">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M16.5 0a7.65 7.65 0 0 0-6.16 3a1 1 0 1 0 1.6 1.19A5.67 5.67 0 0 1 16.5 2A5.27 5.27 0 0 1 22 7a5 5 0 0 1-3.36 4.61a1 1 0 0 0-.32.19c-.2.19-2.07 1.78-2.32 2v-.85a1 1 0 0 0-.86-1a5 5 0 0 1-.79-.17a1 1 0 1 0-.55 1.93l.2.05V16a1 1 0 0 0 .62.92a1 1 0 0 0 1.09-.21c.26-.27 3.54-3.06 3.82-3.31A7 7 0 0 0 24 7a7.27 7.27 0 0 0-7.5-7" />
                            <path fill="currentColor"
                                d="M12.12 6.5a1 1 0 1 0 2 0a1 1 0 1 0-2 0m3.25 0a1 1 0 1 0 2 0a1 1 0 1 0-2 0m3.25 0a1 1 0 1 0 2 0a1 1 0 1 0-2 0M7.5 16A7.5 7.5 0 0 0 0 23.5a.5.5 0 0 0 .5.5h14a.5.5 0 0 0 .5-.5A7.5 7.5 0 0 0 7.5 16m-4.75-5.25a4.75 4.75 0 1 0 9.5 0a4.75 4.75 0 1 0-9.5 0" />
                        </svg>
                        <span>Contact Message</span>
                    </a>
                </div>
                @endcan

            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 lg:ml-0 min-h-screen overflow-x-hidden">
            <!-- Header -->
            <header
                class="sticky top-0 z-30 bg-[var(--bg-primary)]/80 backdrop-blur-lg border-b border-[var(--border)] px-4 py-3 ">
                <div class="flex items-center justify-between gap-4">
                    <div class="lg:hidden w-10"></div>
                    <div class="flex-1 max-w-xl">
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-[var(--fg-secondary)]"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" placeholder="Search riders, businesses, parcels..."
                                class="input-field pl-12 pr-4 rounded-xl">
                        </div>
                    </div>
                    <div class="flex items-center gap-3">

                        <button type="button"
                            class="theme-toggle relative p-3 rounded-xl hover:bg-[var(--bg-card)] transition-all"
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

                        <a href="{{ route('notification.index') }}"
                            class="relative p-3 rounded-xl hover:bg-[var(--bg-card)] transition-all {{ request()->routeIs('notification.*') ? 'bg-[var(--bg-card)] text-[var(--accent)]' : '' }}"
                            aria-label="Notifications{{ $unreadNotificationsCount ? " ({$unreadNotificationsCount} unread)" : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @if ($unreadNotificationsCount > 0)
                            <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full"></span>
                            @endif
                        </a>

                        <div class="profile relative">
                            <button type="button" id="profileMenuBtn" aria-haspopup="true" aria-expanded="false"
                                class="flex items-center gap-2 p-1 rounded-xl hover:bg-[var(--bg-card)] transition-all">
                                <img class="avatar-up"
                                    src="{{ auth()->user()->avatar ? asset(auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                                    alt="{{ auth()->user()->name }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[var(--fg-secondary)]"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div id="profileMenu"
                                class="hidden absolute right-0 mt-2 w-56 rounded-xl border border-[var(--border)] bg-[var(--bg-card)] shadow-xl overflow-hidden z-50">
                                <div class="px-4 py-3 border-b border-[var(--border)] text-center">
                                    <img class="avatar-down text-center mx-auto mb-2"
                                        src="{{ auth()->user()->avatar ? asset(auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                                        alt="{{ auth()->user()->name }}">
                                    <p class="font-semibold truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-sm text-[var(--fg-secondary)]">
                                        {{ auth()->user()->getRoleNames()->first() ?? 'No role' }}</p>
                                    <p class="text-sm text-[var(--fg-secondary)] truncate">{{ auth()->user()->email }}
                                    </p>
                                </div>
                                <a href="{{ route('user.edit', auth()->id()) }}"
                                    class="flex items-center gap-3 px-4 py-3 text-sm hover:bg-[rgba(0,212,170,0.1)] hover:text-[var(--accent)] transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>Profile Settings</span>
                                </a>
                                <form action="{{ route('logout') }}" method="POST"
                                    class="border-t border-[var(--border)]">
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
                </div>
            </header>

            <!-- Page Content Container -->
            <div id="pageContent" class="p-4 lg:p-8">
                @yield('content')
            </div>
        </main>
    </div>

    @stack('modals')

    <script src="https://cdn.tailwindcss.com"></script>
    @include('partials.nepali-date-scripts')
    @stack('vendor-scripts')

    <script>
        // ==================== NAVIGATION (in-page sections on the dashboard) ====================
        function initializeNavigation() {
            const navItems = document.querySelectorAll('.nav-item[data-page]');
            navItems.forEach(item => {
                if (item.tagName === 'A') return;
                item.addEventListener('click', () => {
                    const page = item.dataset.page;
                    navigateToPage(page);
                    navItems.forEach(nav => nav.classList.remove('active'));
                    item.classList.add('active');
                    closeMobileMenu();
                });
            });
        }

        function navigateToPage(pageName) {
            document.querySelectorAll('.page-section').forEach(page => {
                page.classList.add('hidden');
            });
            const targetPage = document.getElementById(pageName + 'Page');
            if (targetPage) {
                targetPage.classList.remove('hidden');
            }
        }
        // ==================== THEME ====================
        function initializeTheme() {
            const themeToggles = document.querySelectorAll('.theme-toggle');
            const isLight = localStorage.getItem('theme') === 'light';
            document.body.classList.toggle('light-mode', isLight);
            updateThemeIcons(isLight);
            themeToggles.forEach(toggle => {
                toggle.addEventListener('click', () => {
                    const nowLight = document.body.classList.toggle('light-mode');
                    localStorage.setItem('theme', nowLight ? 'light' : 'dark');
                    updateThemeIcons(nowLight);
                    if (typeof updateChartsColors === 'function') updateChartsColors();
                });
            });
        }

        function updateThemeIcons(isLight) {
            document.querySelectorAll('.dark-icon').forEach(icon => icon.classList.toggle('hidden', isLight));
            document.querySelectorAll('.light-icon').forEach(icon => icon.classList.toggle('hidden', !isLight));
            document.querySelectorAll('.theme-label').forEach(label => {
                label.textContent = isLight ? 'Light Mode' : 'Dark Mode';
            });
            document.querySelectorAll('.theme-toggle').forEach(toggle => {
                toggle.setAttribute('aria-pressed', String(isLight));
            });
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
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth < 1024) {
                sidebar.classList.add('-translate-x-full');
            }
        }
        // ==================== MODALS ====================
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
            document.body.style.overflow = '';
        }
        document.addEventListener('DOMContentLoaded', function() {
            initializeNavigation();
            initializeTheme();
            initializeMobileMenu();
            initializeProfileMenu();
            document.querySelectorAll('.modal-overlay').forEach(modal => {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        closeModal(modal.id);
                    }
                });
            });
        });
    </script>
    <script src="{{ asset('backend/js/number-input.js') }}?v={{ filemtime(public_path('backend/js/number-input.js')) }}"></script>
    @stack('scripts')
</body>

</html>