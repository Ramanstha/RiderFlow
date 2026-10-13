@extends('rider.layout.app')

@section('title', 'My Attendance - Rider Portal')

@section('content')
<div class="mb-8">
    <h2 class="font-display text-2xl font-bold mb-1">My Attendance</h2>
    <p class="text-[var(--fg-secondary)] text-sm">Your attendance record</p>
</div>

<!-- Filters -->
<div class="card p-3 mb-6">
    <form method="GET" action="{{ route('rider.attendance.index') }}" class="flex flex-wrap gap-4">
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

<div class="card overflow-hidden">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($attendances as $attendance)
                    <tr>
                        <td><span class="text-sm">{{ bs($attendance->date, 'Y-m-d') }}</span></td>
                        <td>
                            <span class="badge badge-{{ $attendance->status->value === 'Present' ? 'success' : 'danger' }}">
                                {{ $attendance->status->value }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center text-[var(--fg-secondary)] py-8">No attendance records found.</td>
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
@endsection
