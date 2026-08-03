@extends('admin.layout.app')

@section('title', 'RiderFlow - Delivery Rider Outsourcing Platform')

@section('content')
<!-- Dashboard Page (Default) -->
<div id="dashboardPage" class="page-section">
    <!-- Welcome Section -->
    <div class="mb-8 animate-fade-up stagger-1">
        <h2 class="font-display text-3xl lg:text-2xl font-bold mb-2">Welcome back, {{auth()->user()->name}} !!!</h2>
        <p class="text-[var(--fg-secondary)] text-md">Here's what's happening with your delivery
            operations today.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
        <div class="card stat-card p-6 animate-fade-up stagger-1">
            <div class="flex items-start justify-between mb-4">
                <div class="p-3 rounded-xl bg-[rgba(0,212,170,0.1)]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[var(--accent)]"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span class="text-sm text-green-400 font-semibold flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                    +12%
                </span>
            </div>
            <p class="text-xl font-bold font-display mb-1">248</p>
            <p class="text-[var(--fg-secondary)] text-sm">Total Riders</p>
        </div>

        <div class="card stat-card p-6 animate-fade-up stagger-2">
            <div class="flex items-start justify-between mb-4">
                <div class="p-3 rounded-xl bg-[rgba(16,185,129,0.1)]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-sm text-green-400 font-semibold flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                    +8%
                </span>
            </div>
            <p class="text-xl font-bold font-display mb-1">215</p>
            <p class="text-[var(--fg-secondary)] text-sm">Active Riders</p>
        </div>

        <div class="card stat-card p-6 animate-fade-up stagger-3">
            <div class="flex items-start justify-between mb-4">
                <div class="p-3 rounded-xl bg-[rgba(245,158,11,0.1)]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-yellow-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <span class="text-sm text-green-400 font-semibold flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                    +3
                </span>
            </div>
            <p class="text-xl font-bold font-display mb-1">42</p>
            <p class="text-[var(--fg-secondary)] text-sm">Partner Businesses</p>
        </div>

        <div class="card stat-card p-6 animate-fade-up stagger-4">
            <div class="flex items-start justify-between mb-4">
                <div class="p-3 rounded-xl bg-[rgba(99,102,241,0.1)]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-indigo-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <span class="text-sm text-green-400 font-semibold flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                    +24%
                </span>
            </div>
            <p class="text-xl font-bold font-display mb-1">12,847</p>
            <p class="text-[var(--fg-secondary)] text-sm">Delivered This Month</p>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 items-start">
        <div class="card p-6 lg:col-span-2 animate-fade-up stagger-5">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="font-display text-lg font-semibold">Delivery Overview</h3>
                    <p class="text-sm text-[var(--fg-secondary)]">Weekly parcel delivery statistics</p>
                </div>
                <div class="flex gap-2">
                    <button class="tab-btn active text-sm" data-chart="weekly">Week</button>
                    <button class="tab-btn text-sm" data-chart="monthly">Month</button>
                </div>
            </div>
            <div class="relative" style="height: 280px;">
                <canvas id="deliveryChart"></canvas>
            </div>
        </div>

        <div class="card p-6 animate-fade-up stagger-6">
            <h3 class="font-display text-lg font-semibold mb-2">Attendance Summary</h3>
            <p class="text-sm text-[var(--fg-secondary)] mb-6">Today's rider status</p>

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-green-400"></div>
                        <span class="text-sm">Present</span>
                    </div>
                    <span class="font-semibold">186</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                        <span class="text-sm">Half Day</span>
                    </div>
                    <span class="font-semibold">18</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-red-400"></div>
                        <span class="text-sm">Absent</span>
                    </div>
                    <span class="font-semibold">8</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-gray-400"></div>
                        <span class="text-sm">On Leave</span>
                    </div>
                    <span class="font-semibold">3</span>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-[var(--border)]">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-[var(--fg-secondary)]">Attendance Rate</span>
                    <span class="font-semibold text-green-400">87.5%</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 87.5%"></div>
                </div>
            </div>

            <div class="relative mt-4" style="height: 140px;">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Top Performers -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
        <div class="card p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-display text-lg font-semibold">Recent Activity</h3>
                <button class="text-sm text-[var(--accent)] hover:underline">View All</button>
            </div>
            <div class="space-y-4" id="recentActivity">
                <!-- Populated by JS -->
            </div>
        </div>

        <div class="card p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-display text-lg font-semibold">Top Performers</h3>
                <button class="text-sm text-[var(--accent)] hover:underline">View All</button>
            </div>
            <div class="space-y-4" id="topPerformers">
                <!-- Populated by JS -->
            </div>
        </div>
    </div>
</div>


<!-- Businesses Page -->
<div id="businessesPage" class="page-section hidden">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="font-display text-3xl font-bold mb-2">Client Businesses</h2>
            <p class="text-[var(--fg-secondary)]">Manage your partner businesses and clients</p>
        </div>
        <button class="btn-primary flex items-center gap-2" onclick="openModal('addBusinessModal')">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add Business
        </button>
    </div>

    <!-- Business Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6" id="businessesGrid">
        <!-- Populated by JS -->
    </div>
</div>

<!-- Assignments Page -->
<div id="assignmentsPage" class="page-section hidden">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="font-display text-3xl font-bold mb-2">Rider Assignments</h2>
            <p class="text-[var(--fg-secondary)]">Assign and manage rider-to-business allocations</p>
        </div>
        <button class="btn-primary flex items-center gap-2" onclick="openModal('newAssignmentModal')">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            New Assignment
        </button>
    </div>

    <!-- Current Assignments -->
    <div class="card p-6 mb-6">
        <h3 class="font-display text-lg font-semibold mb-4">Current Assignments</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Rider</th>
                        <th>Business</th>
                        <th>Start Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="assignmentsTableBody">
                    <!-- Populated by JS -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Assignment History -->
    <div class="card p-6">
        <h3 class="font-display text-lg font-semibold mb-4">Assignment History</h3>
        <div class="space-y-3" id="assignmentHistory">
            <!-- Populated by JS -->
        </div>
    </div>
</div>

<!-- Attendance Page -->
<div id="attendancePage" class="page-section hidden">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="font-display text-3xl font-bold mb-2">Attendance Management</h2>
            <p class="text-[var(--fg-secondary)]">Track daily attendance and generate reports</p>
        </div>
        <div class="flex gap-3">
            <button class="btn-secondary flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export PDF
            </button>
            <button class="btn-secondary flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export Excel
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Calendar -->
        <div class="card p-6 xl:col-span-2">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-display text-lg font-semibold">January 2025</h3>
                <div class="flex gap-2">
                    <button class="p-2 rounded-lg hover:bg-[var(--bg-secondary)] transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button class="p-2 rounded-lg hover:bg-[var(--bg-secondary)] transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-7 gap-2 mb-2">
                <div class="text-center text-sm font-semibold text-[var(--fg-secondary)] py-2">Sun</div>
                <div class="text-center text-sm font-semibold text-[var(--fg-secondary)] py-2">Mon</div>
                <div class="text-center text-sm font-semibold text-[var(--fg-secondary)] py-2">Tue</div>
                <div class="text-center text-sm font-semibold text-[var(--fg-secondary)] py-2">Wed</div>
                <div class="text-center text-sm font-semibold text-[var(--fg-secondary)] py-2">Thu</div>
                <div class="text-center text-sm font-semibold text-[var(--fg-secondary)] py-2">Fri</div>
                <div class="text-center text-sm font-semibold text-[var(--fg-secondary)] py-2">Sat</div>
            </div>

            <div class="grid grid-cols-7 gap-2" id="attendanceCalendar">
                <!-- Populated by JS -->
            </div>

            <div class="mt-6 pt-4 border-t border-[var(--border)] flex flex-wrap gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded bg-green-400"></div>
                    <span class="text-sm">Present</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded bg-yellow-400"></div>
                    <span class="text-sm">Half Day</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded bg-red-400"></div>
                    <span class="text-sm">Absent</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded bg-gray-400"></div>
                    <span class="text-sm">Leave</span>
                </div>
            </div>
        </div>

        <!-- Today's Attendance -->
        <div class="card p-6">
            <h3 class="font-display text-lg font-semibold mb-4">Today's Check-ins</h3>
            <div class="space-y-3" id="todayCheckins">
                <!-- Populated by JS -->
            </div>
        </div>
    </div>
</div>

<!-- Parcels Page -->
<div id="parcelsPage" class="page-section hidden">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="font-display text-3xl font-bold mb-2">Parcel Tracking</h2>
            <p class="text-[var(--fg-secondary)]">Assign and track daily parcel deliveries</p>
        </div>
        <button class="btn-primary flex items-center gap-2" onclick="openModal('assignParcelsModal')">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Assign Parcels
        </button>
    </div>

    <!-- Parcel Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="card p-5">
            <p class="text-sm text-[var(--fg-secondary)] mb-1">Today Assigned</p>
            <p class="text-2xl font-bold font-display">1,247</p>
        </div>
        <div class="card p-5">
            <p class="text-sm text-[var(--fg-secondary)] mb-1">Delivered</p>
            <p class="text-2xl font-bold font-display text-green-400">1,089</p>
        </div>
        <div class="card p-5">
            <p class="text-sm text-[var(--fg-secondary)] mb-1">Failed</p>
            <p class="text-2xl font-bold font-display text-red-400">89</p>
        </div>
        <div class="card p-5">
            <p class="text-sm text-[var(--fg-secondary)] mb-1">Returned</p>
            <p class="text-2xl font-bold font-display text-yellow-400">69</p>
        </div>
    </div>

    <!-- Parcels Table -->
    <div class="card overflow-hidden">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Rider</th>
                        <th>Business</th>
                        <th>Assigned</th>
                        <th>Delivered</th>
                        <th>Failed</th>
                        <th>Returned</th>
                        <th>Success Rate</th>
                    </tr>
                </thead>
                <tbody id="parcelsTableBody">
                    <!-- Populated by JS -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Performance Page -->
<div id="performancePage" class="page-section hidden">
    <div class="mb-8">
        <h2 class="font-display text-3xl font-bold mb-2">Performance Analytics</h2>
        <p class="text-[var(--fg-secondary)]">Track rider performance metrics and insights</p>
    </div>

    <!-- Performance Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="card p-6">
            <div class="flex items-center gap-4">
                <div class="p-4 rounded-xl bg-[rgba(0,212,170,0.1)]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-[var(--accent)]"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold font-display">45,892</p>
                    <p class="text-sm text-[var(--fg-secondary)]">Total Assigned</p>
                </div>
            </div>
        </div>
        <div class="card p-6">
            <div class="flex items-center gap-4">
                <div class="p-4 rounded-xl bg-[rgba(16,185,129,0.1)]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-green-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold font-display text-green-400">41,203</p>
                    <p class="text-sm text-[var(--fg-secondary)]">Total Delivered</p>
                </div>
            </div>
        </div>
        <div class="card p-6">
            <div class="flex items-center gap-4">
                <div class="p-4 rounded-xl bg-[rgba(99,102,241,0.1)]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-indigo-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold font-display">89.8%</p>
                    <p class="text-sm text-[var(--fg-secondary)]">Success Rate</p>
                </div>
            </div>
        </div>
        <div class="card p-6">
            <div class="flex items-center gap-4">
                <div class="p-4 rounded-xl bg-[rgba(245,158,11,0.1)]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-yellow-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold font-display">92.3%</p>
                    <p class="text-sm text-[var(--fg-secondary)]">Avg Attendance</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Chart -->
    <div class="card p-6 mb-8">
        <h3 class="font-display text-lg font-semibold mb-6">Monthly Performance Trend</h3>
        <canvas id="performanceChart" height="300"></canvas>
    </div>

    <!-- Top Performers Leaderboard -->
    <div class="card p-6">
        <h3 class="font-display text-lg font-semibold mb-6">Top Performers - This Month</h3>
        <div class="space-y-4" id="performanceLeaderboard">
            <!-- Populated by JS -->
        </div>
    </div>
</div>

<!-- Reports Page -->
<div id="reportsPage" class="page-section hidden">
    <div class="mb-8">
        <h2 class="font-display text-3xl font-bold mb-2">Reports Center</h2>
        <p class="text-[var(--fg-secondary)]">Generate and export detailed reports</p>
    </div>

    <!-- Report Types -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
        <div class="card p-6 cursor-pointer hover:border-[var(--accent)] transition-all group">
            <div
                class="p-4 rounded-xl bg-[rgba(0,212,170,0.1)] w-fit mb-4 group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-[var(--accent)]" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <h3 class="font-display text-lg font-semibold mb-2">Attendance Report</h3>
            <p class="text-sm text-[var(--fg-secondary)] mb-4">Daily, weekly, and monthly attendance
                summaries with detailed breakdowns.</p>
            <button class="btn-primary w-full">Generate Report</button>
        </div>

        <div class="card p-6 cursor-pointer hover:border-[var(--accent)] transition-all group">
            <div
                class="p-4 rounded-xl bg-[rgba(99,102,241,0.1)] w-fit mb-4 group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-indigo-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <h3 class="font-display text-lg font-semibold mb-2">Parcel Delivery Report</h3>
            <p class="text-sm text-[var(--fg-secondary)] mb-4">Complete parcel tracking with success
                rates and failure analysis.</p>
            <button class="btn-primary w-full">Generate Report</button>
        </div>

        <div class="card p-6 cursor-pointer hover:border-[var(--accent)] transition-all group">
            <div
                class="p-4 rounded-xl bg-[rgba(245,158,11,0.1)] w-fit mb-4 group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-yellow-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <h3 class="font-display text-lg font-semibold mb-2">Performance Report</h3>
            <p class="text-sm text-[var(--fg-secondary)] mb-4">Individual and team performance analytics
                with rankings.</p>
            <button class="btn-primary w-full">Generate Report</button>
        </div>

        <div class="card p-6 cursor-pointer hover:border-[var(--accent)] transition-all group">
            <div
                class="p-4 rounded-xl bg-[rgba(16,185,129,0.1)] w-fit mb-4 group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-green-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <h3 class="font-display text-lg font-semibold mb-2">Client-wise Report</h3>
            <p class="text-sm text-[var(--fg-secondary)] mb-4">Business-specific reports showing
                assigned riders and deliveries.</p>
            <button class="btn-primary w-full">Generate Report</button>
        </div>

        <div class="card p-6 cursor-pointer hover:border-[var(--accent)] transition-all group">
            <div
                class="p-4 rounded-xl bg-[rgba(239,68,68,0.1)] w-fit mb-4 group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-red-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="font-display text-lg font-semibold mb-2">Monthly Summary</h3>
            <p class="text-sm text-[var(--fg-secondary)] mb-4">Comprehensive monthly overview of all
                operations.</p>
            <button class="btn-primary w-full">Generate Report</button>
        </div>

        <div class="card p-6 cursor-pointer hover:border-[var(--accent)] transition-all group">
            <div
                class="p-4 rounded-xl bg-[rgba(148,163,184,0.1)] w-fit mb-4 group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="font-display text-lg font-semibold mb-2">Billing Report</h3>
            <p class="text-sm text-[var(--fg-secondary)] mb-4">Invoice generation based on rider
                assignments and deliveries.</p>
            <button class="btn-primary w-full">Generate Report</button>
        </div>
    </div>

    <!-- Recent Reports -->
    <div class="card p-6">
        <h3 class="font-display text-lg font-semibold mb-4">Recently Generated Reports</h3>
        <div class="space-y-3" id="recentReports">
            <!-- Populated by JS -->
        </div>
    </div>
</div>

<!-- Notifications Page -->
<div id="notificationsPage" class="page-section hidden">
    <div class="mb-8">
        <h2 class="font-display text-3xl font-bold mb-2">Notifications</h2>
        <p class="text-[var(--fg-secondary)]">Stay updated with important alerts and reminders</p>
    </div>

    <div class="space-y-4" id="notificationsList">
        <!-- Populated by JS -->
    </div>
</div>
@endsection

@push('modals')
<!-- New Assignment Modal -->
<div id="newAssignmentModal" class="modal-overlay">
    <div class="modal-content p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-display text-xl font-bold">New Rider Assignment</h3>
            <button onclick="closeModal('newAssignmentModal')"
                class="p-2 rounded-lg hover:bg-[var(--bg-secondary)] transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-2">Select Rider *</label>
                <select class="input-field" required>
                    <option value="">Choose a rider</option>
                    <option>Marcus Johnson</option>
                    <option>Elena Rodriguez</option>
                    <option>David Kim</option>
                    <option>Sarah Chen</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Assign to Business *</label>
                <select class="input-field" required>
                    <option value="">Choose a business</option>
                    <option>QuickDeliver Co.</option>
                    <option>FreshMart Grocery</option>
                    <option>MediExpress Pharmacy</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Start Date *</label>
                <input type="date" class="input-field" required>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Notes</label>
                <textarea class="input-field" rows="3" placeholder="Any additional notes..."></textarea>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeModal('newAssignmentModal')"
                    class="btn-secondary flex-1">Cancel</button>
                <button type="submit" class="btn-primary flex-1">Create Assignment</button>
            </div>
        </form>
    </div>
</div>

<!-- Assign Parcels Modal -->
<div id="assignParcelsModal" class="modal-overlay">
    <div class="modal-content p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-display text-xl font-bold">Assign Daily Parcels</h3>
            <button onclick="closeModal('assignParcelsModal')"
                class="p-2 rounded-lg hover:bg-[var(--bg-secondary)] transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-2">Date *</label>
                <input type="date" class="input-field" required>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Select Rider *</label>
                <select class="input-field" required>
                    <option value="">Choose a rider</option>
                    <option>Marcus Johnson - QuickDeliver Co.</option>
                    <option>Elena Rodriguez - FreshMart</option>
                    <option>David Kim - MediExpress</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Number of Parcels *</label>
                <input type="number" class="input-field" placeholder="Enter parcel count" min="1" required>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeModal('assignParcelsModal')"
                    class="btn-secondary flex-1">Cancel</button>
                <button type="submit" class="btn-primary flex-1">Assign Parcels</button>
            </div>
        </form>
    </div>
</div>
@endpush

@push('vendor-scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@push('scripts')
<script>
    // ==================== DATA ====================
    const topPerformersData = [{
            rank: 1,
            name: "Lisa Anderson",
            delivered: 342,
            rate: 97.2,
            avatar: "https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=100&h=100&fit=crop&crop=face"
        },
        {
            rank: 2,
            name: "Sarah Chen",
            delivered: 328,
            rate: 96.1,
            avatar: "https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&h=100&fit=crop&crop=face"
        },
        {
            rank: 3,
            name: "Marcus Johnson",
            delivered: 315,
            rate: 94.3,
            avatar: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop&crop=face"
        },
        {
            rank: 4,
            name: "Priya Patel",
            delivered: 298,
            rate: 92.4,
            avatar: "https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&h=100&fit=crop&crop=face"
        },
        {
            rank: 5,
            name: "Elena Rodriguez",
            delivered: 287,
            rate: 91.1,
            avatar: "https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop&crop=face"
        }
    ];
    const notificationsData = [{
            id: 1,
            title: "Low Attendance Alert",
            message: "3 riders marked absent today without prior notice",
            type: "warning",
            time: "10 mins ago",
            read: false
        },
        {
            id: 2,
            title: "New Assignment Request",
            message: "FoodDash Express requested 5 additional riders",
            type: "info",
            time: "1 hour ago",
            read: false
        },
        {
            id: 3,
            title: "Parcel Assignment Due",
            message: "Daily parcel assignment pending for 12 riders",
            type: "info",
            time: "2 hours ago",
            read: false
        },
        {
            id: 4,
            title: "Contract Renewal",
            message: "Speedy Courier contract expires in 7 days",
            type: "warning",
            time: "Yesterday",
            read: true
        },
        {
            id: 5,
            title: "Performance Milestone",
            message: "Lisa Anderson achieved 97% delivery rate!",
            type: "success",
            time: "Yesterday",
            read: true
        },
        {
            id: 6,
            title: "System Update",
            message: "Platform maintenance scheduled for Sunday 2AM",
            type: "info",
            time: "2 days ago",
            read: true
        }
    ];
    // ==================== INITIALIZATION ====================
    let deliveryChart = null;
    let attendanceChart = null;
    let performanceChart = null;
    document.addEventListener('DOMContentLoaded', function() {
        populateDashboard();
        populateBusinessesGrid();
        populateAssignments();
        populateAttendanceCalendar();
        populateTodayCheckins();
        populateParcelsTable();
        populatePerformanceLeaderboard();
        populateReports();
        populateNotifications();
        initializeCharts();
    });

    // Dashboard-specific override: lazily (re)initialize charts the first time
    // their section becomes visible, since Chart.js can't size a canvas that's
    // still `display: none` at creation time.
    function navigateToPage(pageName) {
        document.querySelectorAll('.page-section').forEach(page => {
            page.classList.add('hidden');
        });
        const targetPage = document.getElementById(pageName + 'Page');
        if (targetPage) {
            targetPage.classList.remove('hidden');
            if (pageName === 'dashboard' && !deliveryChart) {
                setTimeout(initializeCharts, 100);
            } else if (pageName === 'performance' && !performanceChart) {
                setTimeout(() => initPerformanceChart(), 100);
            }
        }
    }

    function populateDashboard() {
        // Recent Activity
        const recentActivityData = [{
                type: "rider_added",
                message: "New rider Alex Turner was added",
                time: "5 minutes ago",
                icon: "user-plus"
            },
            {
                type: "parcel_delivered",
                message: "Marcus completed 28 deliveries today",
                time: "12 minutes ago",
                icon: "check-circle"
            },
            {
                type: "assignment",
                message: "Elena transferred to FreshMart",
                time: "1 hour ago",
                icon: "arrow-right"
            },
            {
                type: "attendance",
                message: "Morning check-in: 186 present",
                time: "2 hours ago",
                icon: "clock"
            },
            {
                type: "business_new",
                message: "TechParts Hub registered as new client",
                time: "3 hours ago",
                icon: "building"
            }
        ];
        const activityContainer = document.getElementById('recentActivity');
        activityContainer.innerHTML = recentActivityData.map(activity => `
            <div class="flex items-start gap-4 p-3 rounded-xl hover:bg-[var(--bg-secondary)] transition-all cursor-pointer">
                <div class="p-2 rounded-lg ${getActivityBgClass(activity.type)}">
                    ${getActivityIcon(activity.icon)}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium">${activity.message}</p>
                    <p class="text-xs text-[var(--fg-secondary)] mt-1">${activity.time}</p>
                </div>
            </div>
        `).join('');
        // Top Performers
        const performersContainer = document.getElementById('topPerformers');
        performersContainer.innerHTML = topPerformersData.map((performer, index) => `
            <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-[var(--bg-secondary)] transition-all cursor-pointer">
                <div class="relative">
                    <img src="${performer.avatar}" alt="${performer.name}" class="avatar">
                    <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-[var(--accent)] text-[10px] font-bold flex items-center justify-center text-gray-900">${performer.rank}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-sm">${performer.name}</p>
                    <p class="text-xs text-[var(--fg-secondary)]">${performer.delivered} deliveries</p>
                </div>
                <span class="text-sm font-semibold text-green-400">${performer.rate}%</span>
            </div>
        `).join('');
    }

    function getActivityIcon(iconType) {
        const icons = {
            'user-plus': '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[var(--accent)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>',
            'check-circle': '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            'arrow-right': '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m8 0l4 4m-4-4l4-4"/></svg>',
            'clock': '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            'building': '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>'
        };
        return icons[iconType] || '';
    }

    function getActivityBgClass(type) {
        const classes = {
            'rider_added': 'bg-[rgba(0,212,170,0.1)]',
            'parcel_delivered': 'bg-[rgba(16,185,129,0.1)]',
            'assignment': 'bg-[rgba(99,102,241,0.1)]',
            'attendance': 'bg-[rgba(245,158,11,0.1)]',
            'business_new': 'bg-[rgba(6,182,212,0.1)]'
        };
        return classes[type] || 'bg-[var(--bg-secondary)]';
    }

    function populateBusinessesGrid() {
        const businessesData = [{
                id: 1,
                name: "QuickDeliver Co.",
                contact: "John Smith",
                phone: "+1 (555) 111-2222",
                email: "info@quickdeliver.com",
                type: "E-commerce",
                riders: 45,
                status: "Active"
            },
            {
                id: 2,
                name: "FreshMart Grocery",
                contact: "Emily Davis",
                phone: "+1 (555) 222-3333",
                email: "partners@freshmart.com",
                type: "Grocery",
                riders: 32,
                status: "Active"
            },
            {
                id: 3,
                name: "MediExpress Pharmacy",
                contact: "Dr. Robert Lee",
                phone: "+1 (555) 333-4444",
                email: "logistics@mediexpress.com",
                type: "Pharmacy",
                riders: 28,
                status: "Active"
            },
            {
                id: 4,
                name: "Speedy Courier",
                contact: "Anna Martinez",
                phone: "+1 (555) 444-5555",
                email: "ops@speedycourier.com",
                type: "Courier",
                riders: 52,
                status: "Active"
            },
            {
                id: 5,
                name: "FoodDash Express",
                contact: "Tom Wilson",
                phone: "+1 (555) 555-6666",
                email: "business@fooddash.com",
                type: "Food",
                riders: 38,
                status: "Active"
            },
            {
                id: 6,
                name: "TechParts Hub",
                contact: "Chris Taylor",
                phone: "+1 (555) 666-7777",
                email: "shipping@techparts.com",
                type: "Electronics",
                riders: 15,
                status: "Pending"
            }
        ];
        const grid = document.getElementById('businessesGrid');
        grid.innerHTML = businessesData.map(business => `
            <div class="card p-6 hover:border-[var(--accent)] transition-all">
                <div class="flex items-start justify-between mb-4">
                    <div class="p-3 rounded-xl bg-[rgba(0,212,170,0.1)]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[var(--accent)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <span class="badge badge-${business.status === 'Active' ? 'success' : 'warning'}">${business.status}</span>
                </div>
                <h3 class="font-display text-lg font-semibold mb-1">${business.name}</h3>
                <p class="text-sm text-[var(--fg-secondary)] mb-4">${business.type} Services</p>

                <div class="space-y-2 text-sm mb-4">
                    <div class="flex items-center gap-2 text-[var(--fg-secondary)]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        ${business.contact}
                    </div>
                    <div class="flex items-center gap-2 text-[var(--fg-secondary)]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        ${business.phone}
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-[var(--border)]">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[var(--accent)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-sm font-medium">${business.riders} Riders</span>
                    </div>
                    <button class="text-sm text-[var(--accent)] hover:underline font-medium">View Details</button>
                </div>
            </div>
        `).join('');
    }

    function populateAssignments() {
        const tbody = document.getElementById('assignmentsTableBody');
        const assignments = [{
                rider: "Marcus Johnson",
                avatar: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop&crop=face",
                business: "QuickDeliver Co.",
                start: "Jan 15, 2025",
                status: "Active"
            },
            {
                rider: "Elena Rodriguez",
                avatar: "https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop&crop=face",
                business: "FreshMart",
                start: "Jan 10, 2025",
                status: "Active"
            },
            {
                rider: "David Kim",
                avatar: "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&h=100&fit=crop&crop=face",
                business: "MediExpress",
                start: "Dec 01, 2024",
                status: "Active"
            },
            {
                rider: "Priya Patel",
                avatar: "https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&h=100&fit=crop&crop=face",
                business: "QuickDeliver Co.",
                start: "Jan 20, 2025",
                status: "Active"
            }
        ];
        tbody.innerHTML = assignments.map(a => `
            <tr>
                <td>
                    <div class="flex items-center gap-3">
                        <img src="${a.avatar}" alt="${a.rider}" class="avatar">
                        <span class="font-medium">${a.rider}</span>
                    </div>
                </td>
                <td>${a.business}</td>
                <td>${a.start}</td>
                <td><span class="badge badge-success">${a.status}</span></td>
                <td>
                    <div class="flex gap-2">
                        <button class="btn-secondary px-3 py-1 text-xs">Transfer</button>
                        <button class="btn-secondary px-3 py-1 text-xs text-red-400 border-red-400/30 hover:bg-red-400/10">End</button>
                    </div>
                </td>
            </tr>
        `).join('');
        // Assignment History
        const historyContainer = document.getElementById('assignmentHistory');
        const history = [{
                rider: "James Wilson",
                from: "Speedy Courier",
                to: "On Leave",
                date: "Jan 18, 2025",
                type: "leave"
            },
            {
                rider: "Lisa Anderson",
                from: "FreshMart",
                to: "QuickDeliver Co.",
                date: "Jan 12, 2025",
                type: "transfer"
            },
            {
                rider: "Michael Brown",
                from: "MediExpress",
                to: "Inactive",
                date: "Jan 05, 2025",
                type: "ended"
            }
        ];
        historyContainer.innerHTML = history.map(h => `
            <div class="flex items-center gap-4 p-4 rounded-xl bg-[var(--bg-secondary)]">
                <div class="p-2 rounded-lg ${h.type === 'transfer' ? 'bg-indigo-500/10' : h.type === 'leave' ? 'bg-yellow-500/10' : 'bg-red-500/10'}">
                    ${h.type === 'transfer' ? '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m8 0l4 4m-4-4l4-4"/></svg>' :
                      h.type === 'leave' ? '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>' :
                      '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>'}
                </div>
                <div class="flex-1">
                    <p class="font-medium">${h.rider}</p>
                    <p class="text-sm text-[var(--fg-secondary)]">${h.from} → ${h.to}</p>
                </div>
                <span class="text-sm text-[var(--fg-secondary)]">${h.date}</span>
            </div>
        `).join('');
    }

    function populateAttendanceCalendar() {
        const calendar = document.getElementById('attendanceCalendar');
        const daysInMonth = 31;
        const startDay = 3; // Wednesday
        const today = 22;
        let html = '';
        // Empty cells before first day
        for (let i = 0; i < startDay; i++) {
            html += '<div class="calendar-day text-[var(--fg-secondary)]/30"></div>';
        }
        // Days of month
        for (let day = 1; day <= daysInMonth; day++) {
            let statusClass = '';
            const random = Math.random();
            if (day > today) {
                statusClass = '';
            } else if (day === today) {
                statusClass = 'present ring-2 ring-[var(--accent)]';
            } else if (random > 0.9) {
                statusClass = 'absent';
            } else if (random > 0.82) {
                statusClass = 'half-day';
            } else if (random > 0.78) {
                statusClass = 'leave';
            } else {
                statusClass = 'present';
            }
            html += `<div class="calendar-day ${statusClass}">${day}</div>`;
        }
        calendar.innerHTML = html;
    }

    function populateTodayCheckins() {
        const container = document.getElementById('todayCheckins');
        const checkins = [{
                name: "Marcus Johnson",
                avatar: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop&crop=face",
                time: "07:32 AM",
                status: "checked-in"
            },
            {
                name: "Elena Rodriguez",
                avatar: "https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop&crop=face",
                time: "07:45 AM",
                status: "checked-in"
            },
            {
                name: "David Kim",
                avatar: "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&h=100&fit=crop&crop=face",
                time: "08:01 AM",
                status: "checked-in"
            },
            {
                name: "Sarah Chen",
                avatar: "https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&h=100&fit=crop&crop=face",
                time: "08:15 AM",
                status: "late"
            },
            {
                name: "Priya Patel",
                avatar: "https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&h=100&fit=crop&crop=face",
                time: "07:28 AM",
                status: "checked-in"
            }
        ];
        container.innerHTML = checkins.map(c => `
            <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-[var(--bg-secondary)] transition-all">
                <img src="${c.avatar}" alt="${c.name}" class="avatar">
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-sm">${c.name}</p>
                    <p class="text-xs text-[var(--fg-secondary)]">${c.time}</p>
                </div>
                <span class="badge badge-${c.status === 'late' ? 'warning' : 'success'} text-xs">${c.status === 'late' ? 'Late' : 'Present'}</span>
            </div>
        `).join('');
    }

    function populateParcelsTable() {
        const tbody = document.getElementById('parcelsTableBody');
        const parcels = [{
                date: "Jan 22, 2025",
                rider: "Marcus Johnson",
                business: "QuickDeliver Co.",
                assigned: 42,
                delivered: 39,
                failed: 2,
                returned: 1
            },
            {
                date: "Jan 22, 2025",
                rider: "Elena Rodriguez",
                business: "FreshMart",
                assigned: 35,
                delivered: 33,
                failed: 1,
                returned: 1
            },
            {
                date: "Jan 22, 2025",
                rider: "David Kim",
                business: "MediExpress",
                assigned: 28,
                delivered: 27,
                failed: 1,
                returned: 0
            },
            {
                date: "Jan 21, 2025",
                rider: "Sarah Chen",
                business: "Speedy Courier",
                assigned: 52,
                delivered: 50,
                failed: 1,
                returned: 1
            },
            {
                date: "Jan 21, 2025",
                rider: "Priya Patel",
                business: "QuickDeliver Co.",
                assigned: 38,
                delivered: 36,
                failed: 1,
                returned: 1
            },
            {
                date: "Jan 21, 2025",
                rider: "Lisa Anderson",
                business: "FreshMart",
                assigned: 45,
                delivered: 44,
                failed: 0,
                returned: 1
            }
        ];
        tbody.innerHTML = parcels.map(p => {
            const rate = Math.round((p.delivered / p.assigned) * 100);
            return `
                <tr>
                    <td class="whitespace-nowrap">${p.date}</td>
                    <td class="font-medium">${p.rider}</td>
                    <td>${p.business}</td>
                    <td class="font-semibold">${p.assigned}</td>
                    <td class="text-green-400 font-semibold">${p.delivered}</td>
                    <td class="text-red-400">${p.failed}</td>
                    <td class="text-yellow-400">${p.returned}</td>
                    <td>
                        <div class="flex items-center gap-2">
                            <div class="progress-bar w-16">
                                <div class="progress-fill" style="width: ${rate}%"></div>
                            </div>
                            <span class="text-sm font-semibold">${rate}%</span>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    }

    function populatePerformanceLeaderboard() {
        const container = document.getElementById('performanceLeaderboard');
        container.innerHTML = topPerformersData.map((p, index) => `
            <div class="flex items-center gap-4 p-4 rounded-xl ${index === 0 ? 'bg-[rgba(0,212,170,0.05)] border border-[var(--accent)]/20' : 'hover:bg-[var(--bg-secondary)]'} transition-all">
                <div class="w-8 h-8 rounded-full ${index === 0 ? 'bg-[var(--accent)]' : index === 1 ? 'bg-gray-400' : index === 2 ? 'bg-amber-600' : 'bg-[var(--bg-secondary)]'} flex items-center justify-center font-bold text-sm ${index === 0 ? 'text-gray-900' : ''}">
                    ${p.rank}
                </div>
                <img src="${p.avatar}" alt="${p.name}" class="avatar">
                <div class="flex-1">
                    <p class="font-medium">${p.name}</p>
                    <p class="text-sm text-[var(--fg-secondary)]">${p.delivered} deliveries this month</p>
                </div>
                <div class="text-right">
                    <p class="text-xl font-bold font-display text-[var(--accent)]">${p.rate}%</p>
                    <p class="text-xs text-[var(--fg-secondary)]">Success Rate</p>
                </div>
            </div>
        `).join('');
    }

    function populateReports() {
        const container = document.getElementById('recentReports');
        const reports = [{
                name: "Monthly Attendance Report - January 2025",
                type: "PDF",
                date: "Jan 20, 2025",
                size: "2.4 MB"
            },
            {
                name: "Q4 2024 Performance Summary",
                type: "Excel",
                date: "Jan 15, 2025",
                size: "1.8 MB"
            },
            {
                name: "Client-wise Delivery Report - December 2024",
                type: "PDF",
                date: "Jan 10, 2025",
                size: "3.1 MB"
            },
            {
                name: "Weekly Parcel Analysis - Week 3",
                type: "Excel",
                date: "Jan 22, 2025",
                size: "856 KB"
            }
        ];
        container.innerHTML = reports.map(r => `
            <div class="flex items-center gap-4 p-4 rounded-xl hover:bg-[var(--bg-secondary)] transition-all cursor-pointer">
                <div class="p-3 rounded-xl ${r.type === 'PDF' ? 'bg-red-500/10' : 'bg-green-500/10'}">
                    ${r.type === 'PDF' ?
                        '<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>' :
                        '<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>'
                    }
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-medium truncate">${r.name}</p>
                    <p class="text-sm text-[var(--fg-secondary)]">${r.date} • ${r.size}</p>
                </div>
                <button class="p-2 rounded-lg hover:bg-[var(--bg-card)] transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                </button>
            </div>
        `).join('');
    }

    function populateNotifications() {
        const container = document.getElementById('notificationsList');
        container.innerHTML = notificationsData.map(n => `
            <div class="card p-5 ${!n.read ? 'border-l-4 border-l-[var(--accent)]' : ''} hover:border-[var(--accent)] transition-all cursor-pointer">
                <div class="flex items-start gap-4">
                    <div class="p-3 rounded-xl ${getNotificationIconBg(n.type)}">
                        ${getNotificationIcon(n.type)}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h4 class="font-semibold ${!n.read ? 'text-[var(--fg-primary)]' : 'text-[var(--fg-secondary)]'}">${n.title}</h4>
                                <p class="text-sm text-[var(--fg-secondary)] mt-1">${n.message}</p>
                            </div>
                            <span class="text-xs text-[var(--fg-secondary)] whitespace-nowrap">${n.time}</span>
                        </div>
                        ${!n.read ? '<div class="mt-3 flex gap-2"><button class="text-xs btn-primary py-1 px-3">Mark as Read</button><button class="text-xs btn-secondary py-1 px-3">View Details</button></div>' : ''}
                    </div>
                </div>
            </div>
        `).join('');
    }

    function getNotificationIconBg(type) {
        const classes = {
            'warning': 'bg-yellow-500/10',
            'info': 'bg-cyan-500/10',
            'success': 'bg-green-500/10'
        };
        return classes[type] || 'bg-[var(--bg-secondary)]';
    }

    function getNotificationIcon(type) {
        const icons = {
            'warning': '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
            'info': '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            'success': '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
        };
        return icons[type] || '';
    }

    // ==================== CHARTS ====================
    function initializeCharts() {
        initDeliveryChart();
        initAttendanceChart();

        document.querySelectorAll('[data-chart]').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('[data-chart]').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                updateDeliveryChart(btn.dataset.chart);
            });
        });
    }

    function initDeliveryChart() {
        const ctx = document.getElementById('deliveryChart').getContext('2d');
        const isLight = document.body.classList.contains('light-mode');
        deliveryChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Delivered',
                    data: [1450, 1680, 1520, 1890, 1750, 2100, 1950],
                    borderColor: '#00d4aa',
                    backgroundColor: 'rgba(0, 212, 170, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#00d4aa',
                    pointBorderColor: isLight ? '#ffffff' : '#0a0f1a',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }, {
                    label: 'Failed',
                    data: [85, 92, 78, 105, 88, 120, 95],
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#ef4444',
                    pointBorderColor: isLight ? '#ffffff' : '#0a0f1a',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            color: isLight ? '#64748b' : '#94a3b8',
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                family: 'Inter',
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: isLight ? '#1a2332' : '#1a2332',
                        titleColor: '#f1f5f9',
                        bodyColor: '#94a3b8',
                        borderColor: '#2d3a4f',
                        borderWidth: 1,
                        cornerRadius: 10,
                        padding: 12,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: isLight ? 'rgba(0,0,0,0.05)' : 'rgba(255,255,255,0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            color: isLight ? '#64748b' : '#94a3b8',
                            font: {
                                family: 'Inter',
                                size: 12
                            }
                        }
                    },
                    y: {
                        grid: {
                            color: isLight ? 'rgba(0,0,0,0.05)' : 'rgba(255,255,255,0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            color: isLight ? '#64748b' : '#94a3b8',
                            font: {
                                family: 'Inter',
                                size: 12
                            },
                            callback: value => value.toLocaleString()
                        }
                    }
                }
            }
        });
    }

    function initAttendanceChart() {
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        const isLight = document.body.classList.contains('light-mode');
        attendanceChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Present', 'Half Day', 'Absent', 'Leave'],
                datasets: [{
                    data: [186, 18, 8, 3],
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#94a3b8'],
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: isLight ? '#1a2332' : '#1a2332',
                        titleColor: '#f1f5f9',
                        bodyColor: '#94a3b8',
                        borderColor: '#2d3a4f',
                        borderWidth: 1,
                        cornerRadius: 10,
                        padding: 12
                    }
                }
            }
        });
    }

    function initPerformanceChart() {
        const ctx = document.getElementById('performanceChart').getContext('2d');
        const isLight = document.body.classList.contains('light-mode');
        performanceChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan'],
                datasets: [{
                    label: 'Assigned',
                    data: [6200, 6800, 7200, 7500, 8000, 8500],
                    backgroundColor: 'rgba(0, 212, 170, 0.6)',
                    borderRadius: 6,
                    barPercentage: 0.7
                }, {
                    label: 'Delivered',
                    data: [5500, 6100, 6500, 6900, 7300, 7800],
                    backgroundColor: 'rgba(16, 185, 129, 0.8)',
                    borderRadius: 6,
                    barPercentage: 0.7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            color: isLight ? '#64748b' : '#94a3b8',
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                family: 'Inter',
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: isLight ? '#1a2332' : '#1a2332',
                        titleColor: '#f1f5f9',
                        bodyColor: '#94a3b8',
                        borderColor: '#2d3a4f',
                        borderWidth: 1,
                        cornerRadius: 10,
                        padding: 12
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: isLight ? '#64748b' : '#94a3b8',
                            font: {
                                family: 'Inter',
                                size: 12
                            }
                        }
                    },
                    y: {
                        grid: {
                            color: isLight ? 'rgba(0,0,0,0.05)' : 'rgba(255,255,255,0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            color: isLight ? '#64748b' : '#94a3b8',
                            font: {
                                family: 'Inter',
                                size: 12
                            },
                            callback: value => (value / 1000) + 'k'
                        }
                    }
                }
            }
        });
    }

    function updateDeliveryChart(period) {
        if (!deliveryChart) return;
        if (period === 'monthly') {
            deliveryChart.data.labels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
            deliveryChart.data.datasets[0].data = [8200, 8900, 9100, 9500];
            deliveryChart.data.datasets[1].data = [450, 520, 480, 420];
        } else {
            deliveryChart.data.labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            deliveryChart.data.datasets[0].data = [1450, 1680, 1520, 1890, 1750, 2100, 1950];
            deliveryChart.data.datasets[1].data = [85, 92, 78, 105, 88, 120, 95];
        }
        deliveryChart.update();
    }

    function updateChartsColors() {
        const isLight = document.body.classList.contains('light-mode');
        if (deliveryChart) {
            deliveryChart.options.plugins.legend.labels.color = isLight ? '#64748b' : '#94a3b8';
            deliveryChart.options.scales.x.grid.color = isLight ? 'rgba(0,0,0,0.05)' : 'rgba(255,255,255,0.05)';
            deliveryChart.options.scales.y.grid.color = isLight ? 'rgba(0,0,0,0.05)' : 'rgba(255,255,255,0.05)';
            deliveryChart.options.scales.x.ticks.color = isLight ? '#64748b' : '#94a3b8';
            deliveryChart.options.scales.y.ticks.color = isLight ? '#64748b' : '#94a3b8';
            deliveryChart.update();
        }
        if (attendanceChart) {
            attendanceChart.options.plugins.tooltip.backgroundColor = isLight ? '#1a2332' : '#1a2332';
            attendanceChart.update();
        }
        if (performanceChart) {
            performanceChart.options.plugins.legend.labels.color = isLight ? '#64748b' : '#94a3b8';
            performanceChart.options.scales.x.ticks.color = isLight ? '#64748b' : '#94a3b8';
            performanceChart.options.scales.y.grid.color = isLight ? 'rgba(0,0,0,0.05)' : 'rgba(255,255,255,0.05)';
            performanceChart.options.scales.y.ticks.color = isLight ? '#64748b' : '#94a3b8';
            performanceChart.update();
        }
    }
</script>
@endpush
