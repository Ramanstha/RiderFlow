@extends('client.layout.app')

@section('title', 'Assign Parcels - Client Portal')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-display text-2xl font-bold mb-1">Parcel Assignments</h2>
        <p class="text-[var(--fg-secondary)] text-sm">
            Loads you have assigned to your riders. They report delivery numbers against these.
        </p>
    </div>
    <a href="{{ route('client.parcel-assignment.create') }}" class="btn-primary flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        Assign Parcels
    </a>
</div>

<div class="card p-6 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
            <label class="block text-sm font-medium mb-2">From</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="input-field">
        </div>
        <div>
            <label class="block text-sm font-medium mb-2">To</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="input-field">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="btn-primary flex-1 text-center">Filter</button>
            <a href="{{ route('client.parcel-assignment.index') }}" class="btn-secondary text-center px-4">Reset</a>
        </div>
    </form>
</div>

<div class="card p-6">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-[var(--fg-secondary)] border-b border-[var(--border)]">
                    <th class="py-3 pr-4 font-medium">Date</th>
                    <th class="py-3 pr-4 font-medium">Rider</th>
                    <th class="py-3 pr-4 font-medium text-right">Assigned</th>
                    <th class="py-3 pr-4 font-medium text-right">Delivered</th>
                    <th class="py-3 pr-4 font-medium text-right">Outstanding</th>
                    <th class="py-3 pr-4 font-medium">Progress</th>
                    <th class="py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($assignments as $assignment)
                    <tr class="border-b border-[var(--border)] last:border-0">
                        <td class="py-3 pr-4">{{ $assignment->date->format('d M Y') }}</td>
                        <td class="py-3 pr-4">{{ $assignment->rider->name ?? '—' }}</td>
                        <td class="py-3 pr-4 text-right font-semibold">{{ number_format($assignment->assigned) }}</td>
                        <td class="py-3 pr-4 text-right text-green-400">{{ number_format($assignment->delivered) }}</td>
                        <td class="py-3 pr-4 text-right text-yellow-400">{{ number_format($assignment->outstanding) }}</td>
                        <td class="py-3 pr-4">
                            @if ($assignment->hasRiderReport())
                                <span class="badge badge-{{ $assignment->success_rate >= 90 ? 'success' : ($assignment->success_rate >= 70 ? 'warning' : 'danger') }}">
                                    {{ $assignment->success_rate }}% delivered
                                </span>
                            @else
                                <span class="badge badge-warning">Not started</span>
                            @endif
                        </td>
                        <td class="py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('client.parcel-assignment.edit', $assignment) }}"
                                    class="text-[var(--accent)]">Edit</a>
                                <form action="{{ route('client.parcel-assignment.destroy', $assignment) }}" method="POST"
                                    onsubmit="return confirm('Withdraw this assignment?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400">Withdraw</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-[var(--fg-secondary)]">
                            You have not assigned any parcels yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $assignments->links() }}</div>
</div>
@endsection
