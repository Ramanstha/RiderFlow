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

    <!-- Calendar -->
    <div class="card p-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-2">
                @if ($previous)
                <a href="{{ route('attendance.riders.show', [$rider, 'month' => $previous]) }}"
                    class="p-2 rounded-lg border border-[var(--border)] hover:border-[var(--accent)] transition-all"
                    title="Previous month" rel="prev">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                @endif
                <div class="min-w-[190px] text-center">
                    <h3 class="font-display text-lg font-bold leading-tight">{{ $bs['label_ne'] }}</h3>
                    <p class="text-xs text-[var(--fg-secondary)]">{{ $bs['label'] }} &middot; {{ $bs['ad_range'] }}</p>
                </div>
                @if ($next)
                <a href="{{ route('attendance.riders.show', [$rider, 'month' => $next]) }}"
                    class="p-2 rounded-lg border border-[var(--border)] hover:border-[var(--accent)] transition-all"
                    title="Next month" rel="next">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
                @endif
            </div>

            <form method="GET" action="{{ route('attendance.riders.show', $rider) }}" class="flex items-center gap-2"
                onchange="this.month.value = this.querySelector('[data-bs-year]').value + '-' + this.querySelector('[data-bs-month]').value.padStart(2, '0'); this.submit()">
                {{-- The selects are unnamed so only the combined BS month is submitted. --}}
                <input type="hidden" name="month" value="{{ $current }}">
                <select data-bs-year class="input-field w-auto text-sm" aria-label="Year (BS)">
                    @foreach ($years as $year)
                    <option value="{{ $year }}" @selected($year===$bs['year'])>{{ $year }}</option>
                    @endforeach
                </select>
                <select data-bs-month class="input-field w-auto text-sm" aria-label="Month (BS)">
                    @foreach (\App\Support\NepaliDate::MONTHS as $number => $name)
                    <option value="{{ $number }}" @selected($number===$bs['month'])>{{ $name }}</option>
                    @endforeach
                </select>
                <a href="{{ route('attendance.riders.show', $rider) }}" class="btn-secondary text-sm px-4">Today</a>
            </form>
        </div>

        <!-- Legend -->
        <div class="flex flex-wrap items-center gap-4 mb-5 text-xs text-[var(--fg-secondary)]">
            <span class="flex items-center gap-2"><span class="legend-dot legend-present"></span> Present</span>
            <span class="flex items-center gap-2"><span class="legend-dot legend-absent"></span> Absent</span>
        </div>

        <div class="calendar-grid mb-2">
            @foreach (\App\Support\NepaliDate::WEEKDAYS_NE as $index => $weekday)
            <div class="calendar-weekday"
                title="{{ ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'][$index] }}">
                {{ $weekday }}</div>
            @endforeach
        </div>

        @foreach ($weeks as $week)
        <div class="calendar-grid">
            @foreach ($week as $cell)
            @if ($cell === null)
            <div class="calendar-cell calendar-cell-empty"></div>
            @else
            @php
            $status = $cell['status']?->value;
            $stateClass = match ($status) {
            'Present' => 'is-present',
            'Absent' => 'is-absent',
            default => '',
            };
            @endphp
            <div class="calendar-cell {{ $stateClass }} {{ $cell['date']->isToday() ? 'is-today' : '' }}"
                title="{{ \App\Support\NepaliDate::MONTHS[$bs['month']] }} {{ $cell['bs_day'] }}, {{ $bs['year'] }} ({{ $cell['date']->format('D, d M Y') }})@if ($status) — {{ $status }}@endif">
                <span class="flex items-baseline justify-between gap-1">
                    <span class="calendar-date">{{ \App\Support\NepaliDate::digits($cell['bs_day']) }}</span>
                    <span class="calendar-ad-date">{{ $cell['date']->format('M j') }}</span>
                </span>
                @if ($status)
                <span class="calendar-status">{{ $status }}</span>
                @endif
            </div>
            @endif
            @endforeach
        </div>
        @endforeach

        <p class="text-sm text-[var(--fg-secondary)] mt-5">
            {{ $summary['total_days'] }} {{ \Illuminate\Support\Str::plural('day', $summary['total_days']) }} tracked in {{ $bs['label'] }}
        </p>
    </div>
</div>
@endsection