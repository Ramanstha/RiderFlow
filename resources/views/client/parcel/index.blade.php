@extends('client.layout.app')

@section('title', 'Parcels - Client Portal')

@section('content')
<div class="mb-8">
    <h2 class="font-display text-3xl lg:text-2xl font-bold mb-2">Parcels</h2>
    <p class="text-[var(--fg-secondary)] text-md">Daily parcel counts logged by your riders.</p>
</div>

<div class="card p-6 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium mb-2">Rider</label>
            <select name="rider_id" class="input-field">
                <option value="">All riders</option>
                @foreach ($riders as $rider)
                    <option class="capitalize" value="{{ $rider->id }}" @selected(request('rider_id') == $rider->id)>{{ $rider->name }}</option>
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
            <a href="{{ route('client.parcel.index') }}" class="btn-secondary text-center px-4">Reset</a>
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
                    <th class="py-3 font-medium text-right">Success</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $record)
                    @php($rate = $record->assigned > 0 ? round(($record->delivered / $record->assigned) * 100, 1) : 0)
                    <tr class="border-b border-[var(--border)] last:border-0">
                        <td class="py-3 pr-4 capitalize">{{ $record->rider->name ?? '—' }}</td>
                        <td class="py-3 pr-4">{{ $record->date->format('d M Y') }}</td>
                        <td class="py-3 pr-4 text-right">
                            {{ number_format($record->assigned) }}
                            @if ($record->isClientAssigned())
                                <span class="badge badge-success ml-2" title="Assigned by you">Assigned</span>
                            @endif
                        </td>
                        <td class="py-3 pr-4 text-right text-green-400">{{ number_format($record->delivered) }}</td>
                        <td class="py-3 text-right">
                            <span class="badge badge-{{ $rate >= 90 ? 'success' : ($rate >= 70 ? 'warning' : 'danger') }}">
                                {{ $rate }}%
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-[var(--fg-secondary)]">No parcel records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $records->links() }}</div>
</div>
@endsection
