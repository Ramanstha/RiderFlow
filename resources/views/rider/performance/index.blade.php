@extends('rider.layout.app')

@section('title', 'My Performance - Rider Portal')

@section('content')
<div class="mb-8">
    <h2 class="font-display text-2xl font-bold mb-1">My Performance</h2>
    <p class="text-[var(--fg-secondary)] text-sm">Scores and delivery outcomes recorded by your operations team</p>
</div>

<!-- Filters -->
<div class="card p-3 mb-6">
    <form method="GET" action="{{ route('rider.performance.index') }}" class="flex flex-wrap gap-4">
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
                    <th>Period</th>
                    <th>Score</th>
                    <th>Completed</th>
                    <th>Failed</th>
                    <th>Attendance Rate</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($performances as $performance)
                    <tr>
                        <td><span class="text-sm">{{ $performance->period->format('M Y') }}</span></td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="progress-bar w-24">
                                    <div class="progress-fill" style="width: {{ $performance->score }}%"></div>
                                </div>
                                <span class="text-sm font-semibold">{{ $performance->score }}</span>
                            </div>
                        </td>
                        <td><span class="text-sm">{{ number_format($performance->deliveries_completed) }}</span></td>
                        <td><span class="text-sm text-red-400">{{ number_format($performance->deliveries_failed) }}</span></td>
                        <td><span class="text-sm">{{ $performance->attendance_rate !== null ? $performance->attendance_rate.'%' : '-' }}</span></td>
                        <td><span class="text-sm text-[var(--fg-secondary)]">{{ $performance->notes ?: '-' }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-[var(--fg-secondary)] py-8">No performance records yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex items-center justify-between p-4 border-t border-[var(--border)]">
        <p class="text-sm text-[var(--fg-secondary)]">
            Showing {{ $performances->firstItem() ?? 0 }}-{{ $performances->lastItem() ?? 0 }} of {{ $performances->total() }} records
        </p>
        <div class="flex gap-2">
            {{ $performances->links() }}
        </div>
    </div>
</div>
@endsection
