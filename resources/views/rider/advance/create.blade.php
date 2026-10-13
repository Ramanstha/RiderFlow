@extends('rider.layout.app')

@section('title', 'Request Advance Salary - Rider Portal')

@section('content')
<div class="max-w-2xl">
    <div class="mb-8">
        <h2 class="font-display text-2xl font-bold">Request Advance Salary</h2>
        <p class="text-[var(--fg-secondary)]">Ask for part of your salary ahead of payday</p>
    </div>

    @if ($hasPending)
        <div class="card p-4 mb-6 border-l-4 border-l-yellow-500">
            <p class="text-sm">
                You already have a pending request. You can submit a new one after it is reviewed, or
                <a href="{{ route('rider.advance.index') }}" class="text-[var(--accent)] underline">cancel it</a>.
            </p>
        </div>
    @endif

    <div class="card p-6">
        <form action="{{ route('rider.advance.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Rider</label>
                    <input type="text" value="{{ $rider->name }}" class="input-field" disabled>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Payable ({{ $earningsMonth }})</label>
                    <input type="text" value="{{ $payable !== null ? 'Rs. '.number_format($payable, 2) : 'Not set' }}"
                        class="input-field" disabled>
                    @if ($earnings !== null)
                        <p class="text-xs text-[var(--fg-secondary)] mt-1">Earned Rs. {{ number_format($earnings, 2) }} less approved advances</p>
                    @endif
                </div>
            </div>

            {{-- The smallest advance is Rs. 1, so below that there is nothing left to request. --}}
            @php($nothingPayable = $payable !== null && $payable < 1)
            <div>
                <label class="block text-sm font-medium mb-2">Advance Amount (Rs.) *</label>
                <input type="number" name="amount" value="{{ old('amount') }}" class="input-field"
                    min="1" step="0.01" @if ($payable !== null) max="{{ $payable }}" @endif
                    placeholder="Enter amount" required @disabled($nothingPayable)>
                @if ($nothingPayable)
                    <p class="text-xs text-yellow-500 mt-1">You have no payable amount this month yet, so an advance cannot be requested.</p>
                @elseif ($payable !== null)
                    <p class="text-xs text-[var(--fg-secondary)] mt-1">Up to Rs. {{ number_format($payable, 2) }}</p>
                @endif
                @error('amount') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Needed By</label>
                <x-nepali-date name="needed_by" :value="old('needed_by')" :min="now()" />
                @error('needed_by') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Reason *</label>
                <textarea name="reason" rows="4" maxlength="1000" class="input-field"
                    placeholder="Briefly explain why you need the advance" required>{{ old('reason') }}</textarea>
                @error('reason') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-5 pt-4">
                <a href="{{ route('rider.advance.index') }}" class="btn-secondary text-center btn-sm">Cancel</a>
                <button type="submit" class="btn-primary btn-sm" @disabled($hasPending || $nothingPayable)>Submit Request</button>
            </div>
        </form>
    </div>
</div>
@endsection
