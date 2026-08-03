@extends('admin.layout.app')

@section('title', 'Parcel Report - RiderFlow')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-display text-2xl font-bold mb-1">Parcel Report</h2>
        <p class="text-[var(--fg-secondary)] text-sm">Delivery volume and success rate per rider</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('reports.parcels.csv', request()->query()) }}" class="btn-secondary flex items-center gap-2">
            Export CSV
        </a>
        <a href="{{ route('reports.parcels.xlsx', request()->query()) }}" class="btn-secondary flex items-center gap-2">
            Export Excel
        </a>
        <a href="{{ route('reports.parcels.pdf', request()->query()) }}" class="btn-secondary flex items-center gap-2">
            Export PDF
        </a>
    </div>
</div>

<div class="card p-3 mb-6">
    <form method="GET" action="{{ route('reports.parcels') }}" class="flex flex-wrap gap-4">
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
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="input-field w-auto text-sm">
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="input-field w-auto text-sm">
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
                    <th>Assigned</th>
                    <th>Delivered</th>
                    <th>Failed</th>
                    <th>Returned</th>
                    <th>Success Rate</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($summary as $row)
                    <tr>
                        <td><p class="font-medium">{{ $row['rider_name'] }}</p></td>
                        <td><span class="text-sm">{{ $row['company_name'] }}</span></td>
                        <td><span class="text-sm">{{ $row['assigned'] }}</span></td>
                        <td><span class="text-sm">{{ $row['delivered'] }}</span></td>
                        <td><span class="text-sm">{{ $row['failed'] }}</span></td>
                        <td><span class="text-sm">{{ $row['returned'] }}</span></td>
                        <td>
                            <span class="badge badge-{{ $row['success_rate'] >= 80 ? 'success' : ($row['success_rate'] >= 50 ? 'warning' : 'danger') }}">{{ $row['success_rate'] }}%</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-[var(--fg-secondary)] py-8">No parcel data for the selected filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
