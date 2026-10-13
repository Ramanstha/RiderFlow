@extends('client.layout.app')

@section('title', 'Performance - Client Portal')

@section('content')
<div class="mb-8">
    <h2 class="font-display text-3xl lg:text-2xl font-bold mb-2">Performance</h2>
    <p class="text-[var(--fg-secondary)] text-md">Scores and delivery totals for your riders.</p>
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
            <x-nepali-date name="date_from" :value="request('date_from')" class="" />
        </div>
        <div>
            <label class="block text-sm font-medium mb-2">To</label>
            <x-nepali-date name="date_to" :value="request('date_to')" class="" />
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="btn-primary flex-1 text-center">Filter</button>
            <a href="{{ route('client.performance.index') }}" class="btn-secondary text-center px-4">Reset</a>
        </div>
    </form>
</div>

<div class="card p-6">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-[var(--fg-secondary)] border-b border-[var(--border)]">
                    <th class="py-3 pr-4 font-medium">Rider</th>
                    <th class="py-3 pr-4 font-medium text-right">Average Score</th>
                    <th class="py-3 pr-4 font-medium text-right">Completed</th>
                    <th class="py-3 font-medium text-right">Failed</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($summary as $row)
                    <tr class="border-b border-[var(--border)] last:border-0">
                        <td class="py-3 pr-4">
                            <a href="{{ route('client.rider.show', $row['rider_id']) }}" class="hover:text-[var(--accent)]">
                                {{ $row['rider_name'] }}
                            </a>
                        </td>
                        <td class="py-3 pr-4 text-right">
                            <span class="badge badge-{{ $row['average_score'] >= 80 ? 'success' : ($row['average_score'] >= 60 ? 'warning' : 'danger') }}">
                                {{ $row['average_score'] }}%
                            </span>
                        </td>
                        <td class="py-3 pr-4 text-right">{{ number_format($row['total_deliveries_completed']) }}</td>
                        <td class="py-3 text-right">{{ number_format($row['total_deliveries_failed']) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-[var(--fg-secondary)]">No performance records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
