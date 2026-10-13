@extends('rider.layout.app')

@section('title', 'Advance Salary - Rider Portal')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="font-display text-2xl font-bold mb-1">Advance Salary</h2>
        <p class="text-[var(--fg-secondary)] text-sm">Your advance salary requests and their status</p>
    </div>
    <a href="{{ route('rider.advance.create') }}" class="btn-primary flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        Request Advance
    </a>
</div>

<div class="card overflow-hidden">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Requested On</th>
                    <th>Amount</th>
                    <th>Needed By</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Remarks</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($advances as $advance)
                    <tr>
                        <td><span class="text-sm">{{ bs($advance->created_at, 'Y-m-d') }}</span></td>
                        <td><span class="text-sm text-[var(--accent)]">Rs. {{ number_format($advance->amount, 2) }}</span></td>
                        <td><span class="text-sm">{{ bs($advance->needed_by, 'Y-m-d') ?: '—' }}</span></td>
                        <td><span class="text-sm" title="{{ $advance->reason }}">{{ \Illuminate\Support\Str::limit($advance->reason, 40) }}</span></td>
                        <td><span class="badge badge-{{ $advance->status->badge() }}">{{ $advance->status->value }}</span></td>
                        <td><span class="text-sm text-[var(--fg-secondary)]" title="{{ $advance->admin_note }}">{{ $advance->admin_note ?: '—' }}</span></td>
                        <td>
                            @if ($advance->isPending())
                                <form action="{{ route('rider.advance.destroy', $advance) }}" method="POST"
                                    onsubmit="return confirm('Cancel this advance request?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg hover:bg-[var(--bg-secondary)] transition-all inline-flex text-red-400" title="Cancel request">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </form>
                            @else
                                <span class="text-sm text-[var(--fg-secondary)]">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-[var(--fg-secondary)] py-8">No advance requests yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex items-center justify-between p-4 border-t border-[var(--border)]">
        <p class="text-sm text-[var(--fg-secondary)]">
            Showing {{ $advances->firstItem() ?? 0 }}-{{ $advances->lastItem() ?? 0 }} of {{ $advances->total() }} records
        </p>
        <div class="flex gap-2">
            {{ $advances->links() }}
        </div>
    </div>
</div>
@endsection
