@extends('rider.layout.app')

@section('title', 'My Assignments - Rider Portal')

@section('content')
<div class="mb-8">
    <h2 class="font-display text-2xl font-bold mb-1">My Assignments</h2>
    <p class="text-[var(--fg-secondary)] text-sm">Businesses, zones and shifts you have been assigned to</p>
</div>

<!-- Filters -->
<div class="card p-3 mb-6">
    <form method="GET" action="{{ route('rider.assignment.index') }}" class="flex flex-wrap gap-4">
        <select name="status" class="input-field w-auto text-sm" onchange="this.form.submit()">
            <option value="">All Status</option>
            @foreach (\App\Enums\AssignmentStatus::cases() as $status)
                <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->value }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-secondary text-sm px-5">Filter</button>
    </form>
</div>

<div class="card overflow-hidden">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Business</th>
                    <th>Zone</th>
                    <th>Route</th>
                    <th>Shift</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($assignments as $assignment)
                    <tr>
                        <td><p class="font-medium">{{ $assignment->company->name }}</p></td>
                        <td><span class="text-sm">{{ $assignment->zone ?: '-' }}</span></td>
                        <td><span class="text-sm">{{ $assignment->route ?: '-' }}</span></td>
                        <td><span class="text-sm">{{ $assignment->shift?->value ?: '-' }}</span></td>
                        <td><span class="text-sm">{{ $assignment->start_date->format('Y-m-d') }}</span></td>
                        <td><span class="text-sm">{{ $assignment->end_date?->format('Y-m-d') ?: '-' }}</span></td>
                        <td>
                            <span class="badge badge-{{ $assignment->status->value === 'Assigned' ? 'info' : ($assignment->status->value === 'Completed' ? 'success' : 'danger') }}">
                                {{ $assignment->status->value }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-[var(--fg-secondary)] py-8">No assignments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex items-center justify-between p-4 border-t border-[var(--border)]">
        <p class="text-sm text-[var(--fg-secondary)]">
            Showing {{ $assignments->firstItem() ?? 0 }}-{{ $assignments->lastItem() ?? 0 }} of {{ $assignments->total() }} records
        </p>
        <div class="flex gap-2">
            {{ $assignments->links() }}
        </div>
    </div>
</div>
@endsection
