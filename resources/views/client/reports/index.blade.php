@extends('client.layout.app')

@section('title', 'Reports - Client Portal')

@section('content')
<div class="mb-8">
    <h2 class="font-display text-3xl lg:text-2xl font-bold mb-2">Reports &amp; Exports</h2>
    <p class="text-[var(--fg-secondary)] text-md">
        Summaries for your own fleet, downloadable as CSV, Excel or PDF.
    </p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @php($cards = [
        ['route' => 'client.reports.attendance', 'title' => 'Attendance Report', 'body' => 'Present, absent and leave days per rider, with attendance rate.'],
        ['route' => 'client.reports.parcels', 'title' => 'Parcel Report', 'body' => 'Assigned and delivered totals with success rate.'],
        ['route' => 'client.reports.performance', 'title' => 'Performance Report', 'body' => 'Average scores and delivery totals over any period.'],
    ])

    @foreach ($cards as $card)
        <a href="{{ route($card['route']) }}" class="card p-6 hover:border-[var(--accent)] transition-all">
            <div class="p-3 rounded-xl bg-[rgba(0,212,170,0.1)] w-fit mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[var(--accent)]" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="font-display text-lg font-bold mb-2">{{ $card['title'] }}</h3>
            <p class="text-sm text-[var(--fg-secondary)]">{{ $card['body'] }}</p>
        </a>
    @endforeach
</div>
@endsection
