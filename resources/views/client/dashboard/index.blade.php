@extends('client.layout.app')

@section('title', 'Dashboard - Client Portal')

@section('content')
<div class="mb-8 animate-fade-up stagger-1">
    <h2 class=" capitalize font-display text-2xl lg:text-3xl font-bold mb-2">{{ $company->name }}</h2>
    <p class="text-[var(--fg-secondary)] text-sm sm:text-base">
        Here's how your fleet is performing in {{ bs(now(), 'F Y') }}.
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
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <span class="text-sm text-[var(--accent)] font-semibold">{{ $stats['riders']['active'] }} active</span>
        </div>
        <p class="text-xl font-bold font-display mb-1">{{ number_format($stats['riders']['total']) }}</p>
        <p class="text-[var(--fg-secondary)] text-sm">Riders Assigned</p>
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
            <span class="text-sm text-green-400 font-semibold">{{ $stats['month']['success_rate'] }}%</span>
        </div>
        <p class="text-xl font-bold font-display mb-1">{{ number_format($stats['month']['delivered']) }}</p>
        <p class="text-[var(--fg-secondary)] text-sm">
            Delivered This Month
            <span class="text-xs">/ {{ number_format($stats['month']['assigned']) }} assigned</span>
        </p>
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
            <span class="text-sm text-yellow-400 font-semibold">{{ $stats['attendance']['attendance_rate'] }}%</span>
        </div>
        <p class="text-xl font-bold font-display mb-1">{{ $stats['today']['present'] }}</p>
        <p class="text-[var(--fg-secondary)] text-sm">
            Present Today
            <span class="text-xs">/ {{ $stats['today']['absent'] }} absent</span>
        </p>
    </div>

    <div class="card stat-card p-6 animate-fade-up stagger-4">
        <div class="flex items-start justify-between mb-4">
            <div class="p-3 rounded-xl bg-[rgba(59,130,246,0.1)]">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-400" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
            </div>
        </div>
        <p class="text-xl font-bold font-display mb-1">{{ number_format($stats['active_assignments']) }}</p>
        <p class="text-[var(--fg-secondary)] text-sm">Active Assignments</p>
    </div>
</div>

<!-- Today at a glance -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="card p-6 animate-fade-up stagger-1">
        <h3 class="font-display text-lg font-bold mb-4">Parcels Today</h3>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-[var(--fg-secondary)]">Assigned</span>
                <span class="font-semibold">{{ number_format($stats['today']['parcels']['assigned']) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-[var(--fg-secondary)]">Delivered</span>
                <span class="font-semibold text-green-400">{{ number_format($stats['today']['parcels']['delivered']) }}</span>
            </div>
            <div class="flex justify-between pt-3 border-t border-[var(--border)]">
                <span class="text-[var(--fg-secondary)]">Success Rate</span>
                <span class="font-semibold">{{ $stats['today']['parcels']['success_rate'] }}%</span>
            </div>
        </div>
    </div>

    <div class="card p-6 lg:col-span-2 animate-fade-up stagger-2">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-display text-lg font-bold">Top Riders This Month</h3>
            <a href="{{ route('client.rider.index') }}" class="text-sm text-[var(--accent)]">View all</a>
        </div>

        @forelse ($stats['top_riders'] as $row)
            <div class="flex items-center justify-between gap-3 py-3 border-b border-[var(--border)] last:border-0">
                <a href="{{ route('client.rider.show', $row['rider_id']) }}" class="capitalize font-medium hover:text-[var(--accent)] min-w-0 truncate">
                    {{ $row['rider_name'] }}
                </a>
                <div class="flex items-center gap-3 sm:gap-6 text-sm shrink-0">
                    <span class="text-[var(--fg-secondary)]">{{ number_format($row['delivered']) }} delivered</span>
                    <span class="badge badge-{{ $row['success_rate'] >= 90 ? 'success' : ($row['success_rate'] >= 70 ? 'warning' : 'danger') }}">
                        {{ $row['success_rate'] }}%
                    </span>
                </div>
            </div>
        @empty
            <p class="text-sm text-[var(--fg-secondary)] py-6 text-center">No parcel activity recorded this month.</p>
        @endforelse
    </div>
</div>

<!-- Recent activity -->
<div class="card p-6 animate-fade-up stagger-3">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-display text-lg font-bold">Recent Parcel Activity</h3>
        <a href="{{ route('client.parcel.index') }}" class="text-sm text-[var(--accent)]">View all</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-[var(--fg-secondary)] border-b border-[var(--border)]">
                    <th class="py-3 pr-4 font-medium">Date</th>
                    <th class="py-3 pr-4 font-medium">Rider</th>
                    <th class="py-3 pr-4 font-medium text-right">Assigned</th>
                    <th class="py-3 font-medium text-right">Delivered</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($stats['recent_activity'] as $record)
                    <tr class="border-b border-[var(--border)] last:border-0">
                        <td class="py-3 pr-4">{{ bs($record->date, 'd M Y') }}</td>
                        <td class="py-3 pr-4">{{ $record->rider->name ?? '—' }}</td>
                        <td class="py-3 pr-4 text-right">{{ number_format($record->assigned) }}</td>
                        <td class="py-3 text-right text-green-400">{{ number_format($record->delivered) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-6 text-center text-[var(--fg-secondary)]">No parcel records yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
