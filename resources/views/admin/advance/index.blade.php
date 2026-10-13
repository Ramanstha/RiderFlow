@extends('admin.layout.app')

@section('title', 'Advance Salary - RiderFlow')

@section('content')
<div class="page-section">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="font-display text-2xl font-bold mb-1">Advance Salary Requests</h2>
            <p class="text-[var(--fg-secondary)] text-sm">Review advance salary requests submitted by riders</p>
        </div>
    </div>

    @if (session('success'))
        <div class="card p-4 mb-6 border-l-4 border-l-[var(--accent)]">
            <p class="text-sm">{{ session('success') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="card p-4 mb-6 border-l-4 border-l-red-500">
            <ul class="text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="text-red-400">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card p-3 mb-6">
        <form method="GET" action="{{ route('advance.index') }}" class="flex flex-wrap gap-4">
            <select name="rider_id" class="input-field w-auto text-sm" onchange="this.form.submit()">
                <option value="">All Riders</option>
                @foreach ($riders as $rider)
                    <option value="{{ $rider->id }}" @selected((string) request('rider_id') === (string) $rider->id)>{{ $rider->name }}</option>
                @endforeach
            </select>
            <select name="status" class="input-field w-auto text-sm" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                @foreach (\App\Enums\SalaryAdvanceStatus::cases() as $status)
                    <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->value }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-secondary text-sm px-5">Filter</button>
        </form>
    </div>

    <div class="card overflow-hidden">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Rider</th>
                        <th>Requested On</th>
                        <th>Amount</th>
                        <th>Needed By</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Review</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($advances as $advance)
                        <tr>
                            <td>
                                <span class="text-sm font-medium">{{ $advance->rider->name ?? '—' }}</span>
                                @if ($advance->rider?->pay !== null)
                                    @php
                                        $earnings = (float) $advance->rider->pay * $advance->rider->present_days;
                                        $monthAdvances = (float) $advance->rider->month_advances;
                                    @endphp
                                    <p class="text-xs text-[var(--fg-secondary)]"
                                        title="Rs. {{ number_format((float) $advance->rider->pay, 2) }} per day × {{ $advance->rider->present_days }} present days = Rs. {{ number_format($earnings, 2) }}, less Rs. {{ number_format($monthAdvances, 2) }} approved advances">
                                        {{ $monthLabel }} Payable: Rs. {{ number_format($earnings - $monthAdvances, 2) }}
                                    </p>
                                @endif
                            </td>
                            <td><span class="text-sm">{{ bs($advance->created_at, 'Y-m-d') }}</span></td>
                            <td><span class="text-sm text-[var(--accent)]">Rs. {{ number_format($advance->amount, 2) }}</span></td>
                            <td><span class="text-sm">{{ bs($advance->needed_by, 'Y-m-d') ?: '—' }}</span></td>
                            <td class="max-w-xs"><span class="text-sm whitespace-normal">{{ $advance->reason }}</span></td>
                            <td><span class="badge badge-{{ $advance->status->badge() }}">{{ $advance->status->value }}</span></td>
                            <td>
                                @if ($advance->isPending())
                                    @can('advance.manage')
                                        <form action="{{ route('advance.update', $advance) }}" method="POST" class="flex flex-col gap-2 min-w-[12rem]">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" name="admin_note" maxlength="1000" placeholder="Note (optional)"
                                                class="input-field text-sm">
                                            <div class="flex gap-2">
                                                <button type="submit" name="status" value="Approved" class="btn-primary btn-sm">Approve</button>
                                                <button type="submit" name="status" value="Rejected" class="btn-secondary btn-sm"
                                                    onclick="return confirm('Reject this advance request?')">Reject</button>
                                            </div>
                                        </form>
                                    @else
                                        <span class="text-sm text-[var(--fg-secondary)]">Awaiting review</span>
                                    @endcan
                                @else
                                    <p class="text-sm">{{ $advance->reviewer->name ?? '—' }}</p>
                                    <p class="text-xs text-[var(--fg-secondary)]">{{ bs($advance->reviewed_at, 'Y-m-d') }}</p>
                                    @if ($advance->admin_note)
                                        <p class="text-xs text-[var(--fg-secondary)] mt-1">{{ $advance->admin_note }}</p>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-[var(--fg-secondary)] py-8">No advance requests found.</td>
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
</div>
@endsection
