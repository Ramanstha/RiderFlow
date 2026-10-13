@extends('rider.layout.app')

@section('title', 'My Parcels - Rider Portal')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-display text-2xl font-bold mb-1">My Parcel Counts</h2>
        <p class="text-[var(--fg-secondary)] text-sm">Daily numbers you have submitted</p>
    </div>
    <a href="{{ route('rider.parcel.create') }}" class="btn-primary flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        Submit Count
    </a>
</div>

<!-- Filters -->
<div class="card p-3 mb-6">
    <form method="GET" action="{{ route('rider.parcel.index') }}" class="filter-bar flex flex-wrap gap-4">
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
                    <th>Date</th>
                    <th>Assigned</th>
                    <th>Delivered</th>
                    <th>Start KM</th>
                    <th>End KM</th>
                    <th>KM Run</th>
                    <th>Success Rate</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($parcelCounts as $parcelCount)
                    <tr>
                        <td><span class="text-sm">{{ bs($parcelCount->date, 'Y-m-d') }}</span></td>
                        <td>
                            <span class="text-sm">{{ number_format($parcelCount->assigned) }}</span>
                            @if ($parcelCount->isClientAssigned())
                                <span class="badge badge-warning ml-2" title="Assigned by {{ $parcelCount->assignedByCompany->name ?? 'your client' }}">
                                    Client
                                </span>
                            @endif
                        </td>
                        <td><span class="text-sm text-[var(--accent)]">{{ $parcelCount->delivered !== null ? number_format($parcelCount->delivered) : '—' }}</span></td>
                        <td><span class="text-sm">{{ number_format($parcelCount->start_km) }}</span></td>
                        <td><span class="text-sm">{{ $parcelCount->end_km !== null ? number_format($parcelCount->end_km) : '—' }}</span></td>
                        <td><span class="text-sm">{{ $parcelCount->km_run !== null ? number_format($parcelCount->km_run) : '—' }}</span></td>
                        <td>
                            <span class="badge badge-{{ $parcelCount->success_rate >= 90 ? 'success' : ($parcelCount->success_rate >= 70 ? 'warning' : 'danger') }}">
                                {{ $parcelCount->success_rate }}%
                            </span>
                        </td>
                        <td>
                            @if ($parcelCount->isEditableByRider())
                                <a href="{{ route('rider.parcel.edit', $parcelCount) }}"
                                    class="p-2 rounded-lg hover:bg-[var(--bg-secondary)] transition-all inline-flex" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                            @else
                                <span class="text-sm text-[var(--fg-secondary)]">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-[var(--fg-secondary)] py-8">No parcel counts submitted yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-4 border-t border-[var(--border)]">
        <p class="text-sm text-[var(--fg-secondary)]">
            Showing {{ $parcelCounts->firstItem() ?? 0 }}-{{ $parcelCounts->lastItem() ?? 0 }} of {{ $parcelCounts->total() }} records
        </p>
        <div class="flex gap-2">
            {{ $parcelCounts->links() }}
        </div>
    </div>
</div>
@endsection
