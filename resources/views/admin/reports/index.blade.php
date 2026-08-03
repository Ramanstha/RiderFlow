@extends('admin.layout.app')

@section('title', 'Reports - RiderFlow')

@section('content')
<div id="reportsPage" class="page-section">
    <div class="mb-8">
        <h2 class="font-display text-2xl font-bold mb-1">Reports</h2>
        <p class="text-[var(--fg-secondary)] text-sm">Aggregated insights across attendance, parcels and performance</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('reports.attendance') }}" class="card p-6 hover:border-[var(--accent)] transition-all">
            <h3 class="font-display text-lg font-bold mb-2">Attendance Report</h3>
            <p class="text-sm text-[var(--fg-secondary)]">Present/absent breakdown and attendance rate per rider</p>
        </a>
        <a href="{{ route('reports.parcels') }}" class="card p-6 hover:border-[var(--accent)] transition-all">
            <h3 class="font-display text-lg font-bold mb-2">Parcel Report</h3>
            <p class="text-sm text-[var(--fg-secondary)]">Delivery success rate and volume per rider</p>
        </a>
        <a href="{{ route('reports.performance') }}" class="card p-6 hover:border-[var(--accent)] transition-all">
            <h3 class="font-display text-lg font-bold mb-2">Performance Report</h3>
            <p class="text-sm text-[var(--fg-secondary)]">Average scores and delivery trends per rider</p>
        </a>
    </div>
</div>
@endsection
