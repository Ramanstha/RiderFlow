@extends('client.layout.app')

@section('title', $rider->name.' - Client Portal')

@section('content')
<div class="mb-8 flex items-start justify-between gap-4 flex-wrap">
    <div class="flex items-center gap-4">
        <img src="{{ $rider->photo ? asset('storage/'.$rider->photo) : 'https://ui-avatars.com/api/?name='.urlencode($rider->name) }}"
            alt="{{ $rider->name }}" class="avatar w-14 h-14">
        <div>
            <h2 class="font-display text-2xl font-bold">{{ $rider->name }}</h2>
            <p class="text-[var(--fg-secondary)] text-sm">
                {{ $rider->email }} &middot; {{ $rider->phone }} &middot;
                <span class="font-mono text-xs">{{ $rider->license_number }}</span>
            </p>
        </div>
    </div>
    <a href="{{ route('client.rider.index') }}" class="btn-secondary">Back to riders</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="card p-6">
        <h3 class="font-display text-lg font-bold mb-4">Recent Attendance</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[var(--fg-secondary)] border-b border-[var(--border)]">
                        <th class="py-2 pr-4 font-medium">Date</th>
                        <th class="py-2 pr-4 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($attendance as $record)
                        <tr class="border-b border-[var(--border)] last:border-0">
                            <td class="py-2 pr-4">{{ $record->date->format('d M Y') }}</td>
                            <td class="py-2 pr-4">
                                <span class="badge badge-{{ $record->status->value === 'Present' ? 'success' : 'danger' }}">
                                    {{ $record->status->value }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="py-6 text-center text-[var(--fg-secondary)]">No attendance yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card p-6">
        <h3 class="font-display text-lg font-bold mb-4">Recent Parcels</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[var(--fg-secondary)] border-b border-[var(--border)]">
                        <th class="py-2 pr-4 font-medium">Date</th>
                        <th class="py-2 pr-4 font-medium text-right">Assigned</th>
                        <th class="py-2 font-medium text-right">Delivered</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($parcels as $record)
                        <tr class="border-b border-[var(--border)] last:border-0">
                            <td class="py-2 pr-4">{{ $record->date->format('d M Y') }}</td>
                            <td class="py-2 pr-4 text-right">{{ number_format($record->assigned) }}</td>
                            <td class="py-2 text-right text-green-400">{{ number_format($record->delivered) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="py-6 text-center text-[var(--fg-secondary)]">No parcel records yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="card p-6">
        <h3 class="font-display text-lg font-bold mb-4">Assignments</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[var(--fg-secondary)] border-b border-[var(--border)]">
                        <th class="py-2 pr-4 font-medium">Business</th>
                        <th class="py-2 pr-4 font-medium">Type</th>
                        <th class="py-2 pr-4 font-medium">Start</th>
                        <th class="py-2 font-medium">End</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rider->assignments as $assignment)
                        <tr class="border-b border-[var(--border)] last:border-0">
                            <td class="py-2 pr-4">{{ $assignment->company->name ?? '—' }}</td>
                            <td class="py-2 pr-4">{{ $assignment->type->value }}</td>
                            <td class="py-2 pr-4">{{ $assignment->start_date?->format('d M Y') }}</td>
                            <td class="py-2">{{ $assignment->end_date?->format('d M Y') ?? 'Ongoing' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-6 text-center text-[var(--fg-secondary)]">No assignments yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card p-6">
        <h3 class="font-display text-lg font-bold mb-4">Performance</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[var(--fg-secondary)] border-b border-[var(--border)]">
                        <th class="py-2 pr-4 font-medium">Period</th>
                        <th class="py-2 pr-4 font-medium text-right">Score</th>
                        <th class="py-2 pr-4 font-medium text-right">Completed</th>
                        <th class="py-2 font-medium text-right">Failed</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($performance as $record)
                        <tr class="border-b border-[var(--border)] last:border-0">
                            <td class="py-2 pr-4">{{ $record->period->format('M Y') }}</td>
                            <td class="py-2 pr-4 text-right">{{ $record->score }}%</td>
                            <td class="py-2 pr-4 text-right">{{ number_format($record->deliveries_completed) }}</td>
                            <td class="py-2 text-right">{{ number_format($record->deliveries_failed) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-6 text-center text-[var(--fg-secondary)]">No performance records yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
