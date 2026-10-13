@extends('rider.layout.app')

@section('title', 'Dashboard - Rider Portal')

@section('content')
<div class="mb-8 animate-fade-up stagger-1">
    <h2 class="font-display text-3xl lg:text-2xl font-bold mb-2">Welcome back,<span class="capitalize"> {{ $rider->name }} !!!</h2>
    <p class="text-[var(--fg-secondary)] text-md">
        Here's your delivery activity for {{ bs(now(), 'F Y') }}.
    </p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
    <div class="card stat-card p-6 animate-fade-up stagger-1">
        <div class="flex items-start justify-between mb-4">
            <div class="p-3 rounded-xl bg-[rgba(0,212,170,0.1)]">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[var(--accent)]" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
        </div>
        <p class="text-xl font-bold font-display mb-1">{{ $stats['today_parcels']->delivered ?? 0 }}</p>
        <p class="text-[var(--fg-secondary)] text-sm">
            Delivered Today
            @if ($stats['today_parcels'])
                <span class="text-xs">/ {{ $stats['today_parcels']->assigned }} assigned</span>
            @endif
        </p>
    </div>

    <div class="card stat-card p-6 animate-fade-up stagger-2">
        <div class="flex items-start justify-between mb-4">
            <div class="p-3 rounded-xl bg-[rgba(16,185,129,0.1)]">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-400" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span class="text-sm text-green-400 font-semibold">{{ $stats['parcels']['success_rate'] }}%</span>
        </div>
        <p class="text-xl font-bold font-display mb-1">{{ number_format($stats['parcels']['delivered']) }}</p>
        <p class="text-[var(--fg-secondary)] text-sm">Delivered This Month</p>
    </div>

    <div class="card stat-card p-6 animate-fade-up stagger-3">
        <div class="flex items-start justify-between mb-4">
            <div class="p-3 rounded-xl bg-[rgba(245,158,11,0.1)]">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-yellow-400" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <span class="text-sm text-[var(--fg-secondary)] font-semibold">{{ $stats['attendance']['attendance_rate'] }}%</span>
        </div>
        <p class="text-xl font-bold font-display mb-1">
            {{ $stats['attendance']['present_days'] }}/{{ $stats['attendance']['total_days'] }}
        </p>
        <p class="text-[var(--fg-secondary)] text-sm">Days Present This Month</p>
    </div>

    <div class="card stat-card p-6 animate-fade-up stagger-4">
        <div class="flex items-start justify-between mb-4">
            <div class="p-3 rounded-xl bg-[rgba(99,102,241,0.1)]">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-indigo-400" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
        </div>
        <p class="text-xl font-bold font-display mb-1">
            {{ $stats['latest_performance']->score ?? '-' }}
        </p>
        <p class="text-[var(--fg-secondary)] text-sm">
            Latest Performance Score
            @if ($stats['latest_performance'])
                <span class="text-xs">({{ bs($stats['latest_performance']->period, 'M Y') }})</span>
            @endif
        </p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
    <!-- Weekly Chart -->
    <div class="card p-6 lg:col-span-2 animate-fade-up stagger-5">
        <div class="mb-6">
            <h3 class="font-display text-lg font-semibold">Last 7 Days</h3>
            <p class="text-sm text-[var(--fg-secondary)]">Parcels assigned vs. delivered</p>
        </div>
        <div class="relative" style="height: 280px;">
            <canvas id="riderParcelChart"></canvas>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Current Assignment -->
        <div class="card p-6 animate-fade-up stagger-6">
            <h3 class="font-display text-lg font-semibold mb-4">Current Assignment</h3>
            @if ($assignment = $stats['active_assignment'])
                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-[var(--fg-secondary)]">Business</span>
                        <span class="capitalize font-medium">{{ $assignment->company->name }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[var(--fg-secondary)]">Started</span>
                        <span class="font-medium">{{ bs($assignment->start_date, 'Y-m-d') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[var(--fg-secondary)]">Ends</span>
                        <span class="font-medium">{{ bs($assignment->end_date, 'Y-m-d') ?: 'Ongoing' }}</span>
                    </div>
                </div>
                <a href="{{ route('rider.assignment.index') }}"
                    class="btn-secondary btn-sm w-full text-center mt-5">View all assignments</a>
            @else
                <p class="text-sm text-[var(--fg-secondary)]">You have no active assignment right now.</p>
            @endif
        </div>

        <!-- Month Breakdown -->
        <div class="card p-6 animate-fade-up stagger-6">
            <h3 class="font-display text-lg font-semibold mb-4">This Month</h3>
            <div class="space-y-3 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-[var(--fg-secondary)]">Assigned</span>
                    <span class="font-medium">{{ number_format($stats['parcels']['assigned']) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-[var(--fg-secondary)]">Delivered</span>
                    <span class="font-medium text-green-400">{{ number_format($stats['parcels']['delivered']) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-[var(--fg-secondary)]">Absences</span>
                    <span class="font-medium">{{ $stats['attendance']['absent_days'] }}</span>
                </div>
            </div>
            <a href="{{ route('rider.parcel.create') }}" class="btn-primary btn-sm w-full text-center mt-5 block">
                Log today's parcels
            </a>
        </div>
    </div>
</div>
@endsection

@push('vendor-scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@push('scripts')
<script>
    (function () {
        const trend = @json($stats['recent_parcels']);
        const ctx = document.getElementById('riderParcelChart');
        if (!ctx || typeof Chart === 'undefined') return;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: trend.map(d => d.label),
                datasets: [
                    {
                        label: 'Assigned',
                        data: trend.map(d => d.assigned),
                        backgroundColor: 'rgba(148, 163, 184, 0.5)',
                        borderRadius: 6,
                    },
                    {
                        label: 'Delivered',
                        data: trend.map(d => d.delivered),
                        backgroundColor: 'rgba(0, 212, 170, 0.7)',
                        borderRadius: 6,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { labels: { color: '#94a3b8' } },
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#94a3b8' } },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(148, 163, 184, 0.1)' },
                        ticks: { color: '#94a3b8', precision: 0 },
                    },
                },
            },
        });
    })();
</script>
@endpush
