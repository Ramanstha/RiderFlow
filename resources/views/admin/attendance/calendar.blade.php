@extends('admin.layout.app')

@section('title', $rider->name . ' Attendance - RiderFlow')

@section('content')
<div id="attendanceCalendarPage" class="page-section">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <a href="{{ route('attendance.riders') }}"
                class="inline-flex items-center gap-1 text-xs text-[var(--fg-secondary)] hover:text-[var(--accent)] mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Back to riders
            </a>
            <h2 class="font-display text-2xl font-bold mb-1">Attendance Details</h2>
            <p class="text-[var(--fg-secondary)] text-sm">Monthly attendance history for the selected rider</p>
        </div>
        @can('attendances.manage')
        <a href="{{ route('attendance.create') }}" class="btn-primary flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Record Attendance
        </a>
        @endcan
    </div>

    <!-- Selected rider -->
    <div class="card p-5 mb-6 border-l-4 border-l-[var(--accent)]">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="flex items-center gap-4 flex-1">
                <img src="{{ $rider->photo ? asset('storage/' . $rider->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($rider->name) }}"
                    alt="{{ $rider->name }}" class="avatar w-14 h-14">
                <div class="min-w-0">
                    <p class="text-xs uppercase tracking-wide text-[var(--accent)] font-semibold mb-1">Selected Rider
                    </p>
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

    @include('partials.attendance-calendar', ['route' => 'attendance.riders.show', 'routeParams' => [$rider]])
</div>
@endsection