@extends('admin.layout.app')

@section('title', 'Parcel Tracking - RiderFlow')

@section('content')
<div id="parcelsPage" class="page-section">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="font-display text-2xl font-bold mb-1">Parcel Tracking</h2>
            <p class="text-[var(--fg-secondary)] text-sm">Daily parcel counts per rider</p>
        </div>
        @can('parcels.manage')
        <a href="{{ route('parcel.create') }}" class="btn-primary flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Record Parcel Count
        </a>
        @endcan
    </div>

    @if (session('success'))
        <div class="card p-4 mb-6 border-l-4 border-l-[var(--accent)]">
            <p class="text-sm">{{ session('success') }}</p>
        </div>
    @endif

    <div class="card p-3 mb-6">
        <form method="GET" action="{{ route('parcel.index') }}" class="flex flex-wrap gap-4">
            <select name="rider_id" class="input-field w-auto text-sm" onchange="this.form.submit()">
                <option value="">All Riders</option>
                @foreach ($riders as $rider)
                    <option value="{{ $rider->id }}" @selected((string) request('rider_id') === (string) $rider->id)>{{ $rider->name }}</option>
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
                        <th>Date</th>
                        <th>Assigned</th>
                        <th>Delivered</th>
                        <th>Failed</th>
                        <th>Returned</th>
                        <th>Success Rate</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($parcelCounts as $parcelCount)
                        <tr>
                            <td><p class="font-medium">{{ $parcelCount->rider->name }}</p></td>
                            <td><span class="text-sm">{{ $parcelCount->date->format('Y-m-d') }}</span></td>
                            <td>
                                <span class="text-sm">{{ $parcelCount->assigned }}</span>
                                @if ($parcelCount->isClientAssigned())
                                    <span class="badge badge-warning ml-2"
                                        title="Assigned by {{ $parcelCount->assignedByCompany->name ?? 'the client' }}">Client</span>
                                @endif
                            </td>
                            <td><span class="text-sm">{{ $parcelCount->delivered }}</span></td>
                            <td><span class="text-sm">{{ $parcelCount->failed }}</span></td>
                            <td><span class="text-sm">{{ $parcelCount->returned }}</span></td>
                            <td>
                                <span class="badge badge-{{ $parcelCount->success_rate >= 80 ? 'success' : ($parcelCount->success_rate >= 50 ? 'warning' : 'danger') }}">{{ $parcelCount->success_rate }}%</span>
                            </td>
                            <td>
                                @can('parcels.manage')
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('parcel.edit', $parcelCount) }}"
                                        class="p-2 rounded-lg hover:bg-[var(--bg-secondary)] transition-all" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('parcel.destroy', $parcelCount) }}" method="POST"
                                        onsubmit="return confirm('Remove this parcel count?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg hover:bg-red-500/10 text-red-400 transition-all" title="Remove">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-[var(--fg-secondary)] py-8">No parcel counts found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between p-4 border-t border-[var(--border)]">
            <p class="text-sm text-[var(--fg-secondary)]">
                Showing {{ $parcelCounts->firstItem() ?? 0 }}-{{ $parcelCounts->lastItem() ?? 0 }} of {{ $parcelCounts->total() }} records
            </p>
            <div class="flex gap-2">
                {{ $parcelCounts->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
