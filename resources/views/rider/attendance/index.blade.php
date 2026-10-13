@extends('rider.layout.app')

@section('title', 'My Attendance - Rider Portal')

@section('content')
<div class="mb-8">
    <h2 class="font-display text-2xl font-bold mb-1">My Attendance</h2>
    <p class="text-[var(--fg-secondary)] text-sm">Your monthly attendance history</p>
</div>

<!-- Summary -->
<div class="card p-5 mb-6 border-l-4 border-l-[var(--accent)]">
    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
        <div class="flex items-center gap-4 flex-1">
            <img src="{{ $rider->photo ? asset('storage/' . $rider->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($rider->name) }}"
                alt="{{ $rider->name }}" class="avatar w-14 h-14">
            <div class="min-w-0">
                <h3 class="font-display text-xl font-bold truncate">{{ $rider->name }}</h3>
                <p class="text-xs text-[var(--fg-secondary)] truncate">
                    {{ $rider->email }} &middot; {{ $rider->companies->pluck('name')->join(', ') ?: 'Unassigned' }}
                </p>
            </div>
        </div>
        <div class="flex flex-col gap-2 sm:items-end">
            <div class="flex flex-wrap gap-2">
                <span class="badge badge-success">{{ $summary['present_days'] }} Present</span>
                <span class="badge badge-danger">{{ $summary['absent_days'] }} Absent</span>
                <span class="badge badge-info">{{ $summary['attendance_rate'] }}% Rate</span>
            </div>
            <div class="flex flex-wrap gap-2">
                <span class="badge badge-success"
                    title="{{ $rider->pay !== null ? 'Rs. ' . number_format((float) $rider->pay, 2) . ' per day × ' . $summary['present_days'] . ' present days' : 'No pay rate on file' }}">
                    Earnings: {{ $payroll['earnings'] !== null ? 'Rs. ' . number_format($payroll['earnings'], 2) : '—' }}
                </span>
                <span class="badge badge-danger" title="Approved advances this month">
                    Advances: Rs. {{ number_format($payroll['advances'], 2) }}
                </span>
                <span class="badge badge-info">
                    Payable: {{ $payroll['payable'] !== null ? 'Rs. ' . number_format($payroll['payable'], 2) : '—' }}
                </span>
            </div>
        </div>
    </div>
</div>

@include('partials.attendance-calendar', ['route' => 'rider.attendance.index', 'routeParams' => []])
@endsection
