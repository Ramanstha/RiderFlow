@extends('client.layout.app')

@section('title', 'Attendance Report - Client Portal')

@section('content')
<div class="mb-8 flex items-start justify-between gap-4 flex-wrap">
    <div>
        <h2 class="font-display text-2xl font-bold mb-1">Attendance Report</h2>
        <p class="text-[var(--fg-secondary)] text-sm sm:text-base">Attendance totals per rider on your fleet.</p>
    </div>
    <a href="{{ route('client.reports.index') }}" class="btn-secondary">All reports</a>
</div>

@include('client.reports.partials.filters', ['base' => 'client.reports.attendance'])

<div class="card p-6">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-[var(--fg-secondary)] border-b border-[var(--border)]">
                    <th class="py-3 pr-4 font-medium">Rider</th>
                    <th class="py-3 pr-4 font-medium text-right">Total Days</th>
                    <th class="py-3 pr-4 font-medium text-right">Present</th>
                    <th class="py-3 pr-4 font-medium text-right">Absent</th>
                    <th class="py-3 font-medium text-right">Rate</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($summary as $row)
                    <tr class="border-b border-[var(--border)] last:border-0">
                        <td class="py-3 pr-4">{{ $row['rider_name'] }}</td>
                        <td class="py-3 pr-4 text-right">{{ $row['total_days'] }}</td>
                        <td class="py-3 pr-4 text-right text-green-400">{{ $row['present_days'] }}</td>
                        <td class="py-3 pr-4 text-right text-red-400">{{ $row['absent_days'] }}</td>
                        <td class="py-3 text-right">
                            <span class="badge badge-{{ $row['attendance_rate'] >= 90 ? 'success' : ($row['attendance_rate'] >= 75 ? 'warning' : 'danger') }}">
                                {{ $row['attendance_rate'] }}%
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-[var(--fg-secondary)]">No records for this period.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
