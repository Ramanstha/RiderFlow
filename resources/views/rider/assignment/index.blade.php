@extends('rider.layout.app')

@section('title', 'My Assignments - Rider Portal')

@section('content')
<div class="mb-8">
    <h2 class="font-display text-2xl font-bold mb-1">My Assignments</h2>
    <p class="text-[var(--fg-secondary)] text-sm">Businesses you have been assigned to</p>
</div>

<div class="card overflow-hidden">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Business</th>
                    <th>Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($assignments as $assignment)
                    <tr>
                        <td><p class="font-medium">{{ $assignment->company->name }}</p></td>
                        <td><span class="badge badge-info">{{ $assignment->type->value }}</span></td>
                        <td><span class="text-sm">{{ bs($assignment->start_date, 'Y-m-d') }}</span></td>
                        <td><span class="text-sm">{{ bs($assignment->end_date, 'Y-m-d') ?: '-' }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-[var(--fg-secondary)] py-8">No assignments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-4 border-t border-[var(--border)]">
        <p class="text-sm text-[var(--fg-secondary)]">
            Showing {{ $assignments->firstItem() ?? 0 }}-{{ $assignments->lastItem() ?? 0 }} of {{ $assignments->total() }} records
        </p>
        <div class="flex gap-2">
            {{ $assignments->links() }}
        </div>
    </div>
</div>
@endsection
