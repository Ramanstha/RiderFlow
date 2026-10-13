@extends('admin.layout.app')

@section('title', 'Attendance - RiderFlow')

@section('content')
<div id="attendancePage" class="page-section">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="font-display text-2xl font-bold mb-1">Rider Attendance</h2>
            <p class="text-[var(--fg-secondary)] text-sm">Track daily present and absent status</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
        <a href="{{ route('attendance.riders') }}" class="btn-secondary flex items-center gap-2 text-sm px-5">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Attendance by Rider
        </a>
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
    </div>

    @if (session('success'))
        <div class="card p-4 mb-6 border-l-4 border-l-[var(--accent)]">
            <p class="text-sm">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Filters -->
    <div class="card p-3 mb-6">
        <form method="GET" action="{{ route('attendance.index') }}" class="flex flex-wrap gap-4">
            <select name="rider_id" class="input-field w-auto text-sm" onchange="this.form.submit()">
                <option value="">All Riders</option>
                @foreach ($riders as $rider)
                    <option value="{{ $rider->id }}" @selected((string) request('rider_id') === (string) $rider->id)>{{ $rider->name }}</option>
                @endforeach
            </select>
            <select name="status" class="input-field w-auto text-sm" onchange="this.form.submit()">
                <option value="">All Status</option>
                @foreach (\App\Enums\AttendanceStatus::cases() as $status)
                    <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->value }}</option>
                @endforeach
            </select>
            <x-nepali-date name="date_from" :value="request('date_from')" class="w-auto text-sm" />
            <x-nepali-date name="date_to" :value="request('date_to')" class="w-auto text-sm" />
            <button type="submit" class="btn-secondary text-sm px-5">Filter</button>
        </form>
    </div>

    <!-- Attendance Table -->
    <div class="card overflow-hidden">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Rider</th>
                        <th>Company</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($attendances as $attendance)
                        <tr>
                            <td>
                                <a href="{{ route('attendance.riders.show', [$attendance->rider, 'month' => \App\Support\NepaliDate::monthKey($attendance->date)]) }}"
                                    class="font-medium hover:text-[var(--accent)] transition-all">{{ $attendance->rider->name }}</a>
                            </td>
                            <td><span class="text-sm">{{ $attendance->rider->companies->pluck('name')->join(', ') ?: '—' }}</span></td>
                            <td><span class="text-sm">{{ bs($attendance->date, 'Y-m-d') }}</span></td>
                            <td>
                                <span class="badge badge-{{ $attendance->status->value === 'Present' ? 'success' : 'danger' }}">{{ $attendance->status->value }}</span>
                            </td>
                            <td>
                                @can('attendances.manage')
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('attendance.edit', $attendance) }}"
                                        class="p-2 rounded-lg hover:bg-[var(--bg-secondary)] transition-all" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('attendance.destroy', $attendance) }}" method="POST"
                                        onsubmit="return confirm('Remove this attendance record?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg hover:bg-red-500/10 text-red-400 transition-all" title="Remove">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-[var(--fg-secondary)] py-8">No attendance records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between p-4 border-t border-[var(--border)]">
            <p class="text-sm text-[var(--fg-secondary)]">
                Showing {{ $attendances->firstItem() ?? 0 }}-{{ $attendances->lastItem() ?? 0 }} of {{ $attendances->total() }} records
            </p>
            <div class="flex gap-2">
                {{ $attendances->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
