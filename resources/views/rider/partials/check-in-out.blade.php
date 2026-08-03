{{-- Self check-in / check-out control. Expects $today (RiderAttendance|null). --}}
<div class="card p-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="font-display text-lg font-semibold mb-1">Today's Shift</h3>
            @if (! $today)
                <p class="text-sm text-[var(--fg-secondary)]">You have not checked in yet today.</p>
            @else
                <p class="text-sm text-[var(--fg-secondary)]">
                    Checked in at <span class="font-medium">{{ $today->check_in ?: '-' }}</span>
                    @if ($today->check_out)
                        &middot; checked out at <span class="font-medium">{{ $today->check_out }}</span>
                    @endif
                </p>
            @endif
        </div>

        <div class="flex items-center gap-3">
            @if ($today)
                <span class="badge badge-{{ $today->status->value === 'Present' ? 'success' : ($today->status->value === 'Absent' ? 'danger' : 'warning') }}">
                    {{ $today->status->value }}
                </span>
            @endif

            @if (! $today)
                <form action="{{ route('rider.attendance.check-in') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-primary btn-sm">Check In</button>
                </form>
            @elseif (! $today->check_out)
                <form action="{{ route('rider.attendance.check-out') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-primary btn-sm">Check Out</button>
                </form>
            @else
                <span class="text-sm text-[var(--fg-secondary)]">Shift complete</span>
            @endif
        </div>
    </div>
</div>
