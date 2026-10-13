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
                <option class="capitalize" value="{{ $rider->id }}" @selected(request('rider_id')==$rider->id)>{{ $rider->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-2">From</label>
            <x-nepali-date name="date_from" :value="request('date_from')" class="" />
        </div>
        <div>
            <label class="block text-sm font-medium mb-2">To</label>
            <x-nepali-date name="date_to" :value="request('date_to')" class="" />
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
                    <th class="py-3 pr-4 font-medium">Rider</th>
                    <th class="py-3 pr-4 font-medium">Date</th>
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
                    <td class="capitalize py-3 pr-4">{{ $assignment->rider->name ?? '—' }}</td>
                    <td class="py-3 pr-4">{{ bs($assignment->date, 'd M Y') }}</td>
                    <td class="py-3 pr-4 text-right font-semibold">{{ number_format($assignment->assigned) }}</td>
                    <td class="py-3 pr-4 text-right text-green-400">{{ $assignment->delivered !== null ? number_format($assignment->delivered) : '—' }}</td>
                    <td class="py-3 pr-4 text-right text-yellow-400">{{ number_format($assignment->outstanding) }}</td>
                    <td class="py-3 pr-4">
                        @if ($assignment->hasRiderReport())
                        <span
                            class="badge badge-{{ $assignment->success_rate >= 90 ? 'success' : ($assignment->success_rate >= 70 ? 'warning' : 'danger') }}">
                            {{ $assignment->success_rate }}% delivered
                        </span>
                        @else
                        <span class="badge badge-warning">Not started</span>
                        @endif
                    </td>
                    <td class="py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('client.parcel-assignment.edit', $assignment) }}"
                                class="p-2 rounded-lg hover:bg-[var(--bg-secondary)] transition-all" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route('client.parcel-assignment.destroy', $assignment) }}" method="POST"
                                onsubmit="return confirm('Withdraw this assignment?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="p-2 rounded-lg hover:bg-red-500/10 text-red-400 transition-all"
                                    title="Remove">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
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