@extends('admin.layout.app')

@section('title', 'Performance Report - RiderFlow')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-display text-2xl font-bold mb-1">Performance Report</h2>
        <p class="text-[var(--fg-secondary)] text-sm">Average scores and delivery totals per rider</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('reports.performance.csv', request()->query()) }}" class="btn-secondary flex items-center gap-2">
            Export CSV
        </a>
        <a href="{{ route('reports.performance.xlsx', request()->query()) }}" class="btn-secondary flex items-center gap-2">
            Export Excel
        </a>
        <a href="{{ route('reports.performance.pdf', request()->query()) }}" class="btn-secondary flex items-center gap-2">
            Export PDF
        </a>
    </div>
</div>

<div class="card p-3 mb-6">
    <form method="GET" action="{{ route('reports.performance') }}" class="flex flex-wrap gap-4">
        <select name="rider_id" class="input-field w-auto text-sm">
            <option value="">All Riders</option>
            @foreach ($filters['riders'] as $rider)
                <option value="{{ $rider->id }}" @selected((string) request('rider_id') === (string) $rider->id)>{{ $rider->name }}</option>
            @endforeach
        </select>
        <select name="company_id" class="input-field w-auto text-sm">
            <option value="">All Companies</option>
            @foreach ($filters['companies'] as $company)
                <option value="{{ $company->id }}" @selected((string) request('company_id') === (string) $company->id)>{{ $company->name }}</option>
            @endforeach
        </select>
        <x-nepali-date name="date_from" :value="request('date_from')" class="w-auto text-sm" />
        <x-nepali-date name="date_to" :value="request('date_to')" class="w-auto text-sm" />
        <button type="submit" class="btn-secondary text-sm px-5">Filter</button>
    </form>
</div>

<div class="card overflow-hidden">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Rider</th>
                    <th>Company</th>
                    <th>Average Score</th>
                    <th>Deliveries Completed</th>
                    <th>Deliveries Failed</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($summary as $row)
                    <tr>
                        <td><p class="font-medium">{{ $row['rider_name'] }}</p></td>
                        <td><span class="text-sm">{{ $row['company_name'] }}</span></td>
                        <td>
                            <div class="w-24">
                                <div class="flex items-center justify-between text-xs mb-1">
                                    <span class="text-[var(--fg-secondary)]">Score</span>
                                    <span class="font-semibold">{{ $row['average_score'] }}%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ $row['average_score'] }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td><span class="text-sm">{{ $row['total_deliveries_completed'] }}</span></td>
                        <td><span class="text-sm">{{ $row['total_deliveries_failed'] }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-[var(--fg-secondary)] py-8">No performance data for the selected filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
