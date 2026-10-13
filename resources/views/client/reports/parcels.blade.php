@extends('client.layout.app')

@section('title', 'Parcel Report - Client Portal')

@section('content')
<div class="mb-8 flex items-start justify-between gap-4 flex-wrap">
    <div>
        <h2 class="font-display text-2xl font-bold mb-1">Parcel Report</h2>
        <p class="text-[var(--fg-secondary)] text-sm sm:text-base">Delivery totals per rider on your fleet.</p>
    </div>
    <a href="{{ route('client.reports.index') }}" class="btn-secondary">All reports</a>
</div>

@include('client.reports.partials.filters', ['base' => 'client.reports.parcels'])

<div class="card p-6">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-[var(--fg-secondary)] border-b border-[var(--border)]">
                    <th class="py-3 pr-4 font-medium">Rider</th>
                    <th class="py-3 pr-4 font-medium text-right">Assigned</th>
                    <th class="py-3 pr-4 font-medium text-right">Delivered</th>
                    <th class="py-3 font-medium text-right">Success</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($summary as $row)
                    <tr class="border-b border-[var(--border)] last:border-0">
                        <td class="py-3 pr-4">{{ $row['rider_name'] }}</td>
                        <td class="py-3 pr-4 text-right">{{ number_format($row['assigned']) }}</td>
                        <td class="py-3 pr-4 text-right text-green-400">{{ number_format($row['delivered']) }}</td>
                        <td class="py-3 text-right">
                            <span class="badge badge-{{ $row['success_rate'] >= 90 ? 'success' : ($row['success_rate'] >= 70 ? 'warning' : 'danger') }}">
                                {{ $row['success_rate'] }}%
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-[var(--fg-secondary)]">No records for this period.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
