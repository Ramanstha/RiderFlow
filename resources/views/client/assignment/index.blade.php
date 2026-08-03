@extends('client.layout.app')

@section('title', 'Assignments - Client Portal')

@section('content')
<div class="mb-8">
    <h2 class="font-display text-3xl lg:text-2xl font-bold mb-2">Assignments</h2>
    <p class="text-[var(--fg-secondary)] text-md">Riders assigned to your zones and routes.</p>
</div>

<div class="card p-6 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium mb-2">Rider</label>
            <select name="rider_id" class="input-field">
                <option value="">All riders</option>
                @foreach ($riders as $rider)
                    <option value="{{ $rider->id }}" @selected(request('rider_id') == $rider->id)>{{ $rider->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-2">Status</label>
            <select name="status" class="input-field">
                <option value="">All</option>
                @foreach (\App\Enums\AssignmentStatus::cases() as $status)
                    <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->value }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="btn-primary flex-1 text-center">Filter</button>
            <a href="{{ route('client.assignment.index') }}" class="btn-secondary text-center px-4">Reset</a>
        </div>
    </form>
</div>

<div class="card p-6">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-[var(--fg-secondary)] border-b border-[var(--border)]">
                    <th class="py-3 pr-4 font-medium">Rider</th>
                    <th class="py-3 pr-4 font-medium">Zone</th>
                    <th class="py-3 pr-4 font-medium">Route</th>
                    <th class="py-3 pr-4 font-medium">Shift</th>
                    <th class="py-3 pr-4 font-medium">Start</th>
                    <th class="py-3 pr-4 font-medium">End</th>
                    <th class="py-3 font-medium">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($assignments as $assignment)
                    <tr class="border-b border-[var(--border)] last:border-0">
                        <td class="py-3 pr-4">{{ $assignment->rider->name ?? '—' }}</td>
                        <td class="py-3 pr-4">{{ $assignment->zone }}</td>
                        <td class="py-3 pr-4">{{ $assignment->route }}</td>
                        <td class="py-3 pr-4">{{ $assignment->shift->value ?? $assignment->shift }}</td>
                        <td class="py-3 pr-4">{{ $assignment->start_date?->format('d M Y') ?? '—' }}</td>
                        <td class="py-3 pr-4">{{ $assignment->end_date?->format('d M Y') ?? '—' }}</td>
                        <td class="py-3">
                            <span class="badge badge-{{ $assignment->status->value === 'Completed' ? 'success' : ($assignment->status->value === 'Cancelled' ? 'danger' : 'warning') }}">
                                {{ $assignment->status->value }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-[var(--fg-secondary)]">No assignments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $assignments->links() }}</div>
</div>
@endsection
