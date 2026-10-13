@extends('admin.layout.app')

@section('title', 'Attendance by Rider - RiderFlow')

@section('content')
<div id="attendanceRidersPage" class="page-section">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="font-display text-2xl font-bold mb-1">Attendance by Rider</h2>
            <p class="text-[var(--fg-secondary)] text-sm">Pick a rider to open their monthly attendance calendar</p>
        </div>
        <a href="{{ route('attendance.riders') }}" class="btn-secondary flex items-center gap-2 text-sm px-5">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
            </svg>
            All Records
        </a>
    </div>

    <!-- Search -->
    <div class="card p-3 mb-6">
        <form method="GET" action="{{ route('attendance.riders') }}" class="flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ $search }}" placeholder="Search riders by name, email or phone..."
                class="input-field flex-1 min-w-[200px]">
            <button type="submit" class="btn-secondary text-sm px-5">Search</button>
            @if ($search)
                <a href="{{ route('attendance.riders') }}" class="btn-secondary text-sm px-5">Clear</a>
            @endif
        </form>
    </div>

    @if ($riders->isEmpty())
        <div class="card p-10 text-center">
            <p class="text-[var(--fg-secondary)] text-sm">
                {{ $search ? 'No rider with attendance records matches "'.$search.'".' : 'No rider has any attendance records yet.' }}
            </p>
        </div>
    @else
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($riders as $rider)
                <a href="{{ route('attendance.riders.show', $rider) }}"
                    class="card p-5 block transition-all hover:border-[var(--accent)]">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="{{ $rider->photo ? asset('storage/' . $rider->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($rider->name) }}"
                            alt="{{ $rider->name }}" class="avatar">
                        <div class="min-w-0">
                            <p class="font-medium truncate">{{ $rider->name }}</p>
                            <p class="text-xs text-[var(--fg-secondary)] truncate">
                                {{ $rider->companies->pluck('name')->join(', ') ?: 'Unassigned' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        <span class="badge badge-success">{{ $rider->present_count }} Present</span>
                        <span class="badge badge-danger">{{ $rider->absent_count }} Absent</span>
                        <span class="badge badge-info">{{ $rider->attendances_count }} {{ \Illuminate\Support\Str::plural('Day', $rider->attendances_count) }}</span>
                    </div>

                    <div class="flex items-center justify-between text-xs text-[var(--fg-secondary)]">
                        <span>
                            Last marked
                            {{ bs($rider->last_marked_on, 'd M Y') ?: '—' }}
                        </span>
                        <span class="flex items-center gap-1 text-[var(--accent)] font-medium">
                            View calendar
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>

        <p class="text-sm text-[var(--fg-secondary)] mt-6">
            Showing {{ $riders->count() }} {{ Str::plural('rider', $riders->count()) }} with attendance records
        </p>
    @endif
</div>
@endsection
