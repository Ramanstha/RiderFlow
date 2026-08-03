@extends('rider.layout.app')

@section('title', 'Edit Parcel Count - Rider Portal')

@section('content')
<div class="max-w-2xl">
    <div class="mb-8">
        <h2 class="font-display text-2xl font-bold">Edit Parcel Count</h2>
        <p class="text-[var(--fg-secondary)]">Correct your numbers for {{ $parcelCount->date->format('d M Y') }}</p>
    </div>

    @if ($parcelCount->isClientAssigned())
        <div class="card p-4 mb-6 border-l-4 border-l-[var(--accent)]">
            <p class="text-sm">
                <span class="font-semibold">{{ $parcelCount->assignedByCompany->name ?? 'Your client' }}</span>
                assigned you <span class="font-semibold">{{ number_format($parcelCount->assigned) }}</span> parcels for
                {{ $parcelCount->date->format('d M Y') }}. Report your delivery numbers below — the assigned total and
                date are set by the client and cannot be changed here.
            </p>
        </div>
    @endif

    <div class="card p-6">
        <form action="{{ route('rider.parcel.update', $parcelCount) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium mb-2">Date *</label>
                <input type="date" name="date" value="{{ old('date', $parcelCount->date->toDateString()) }}"
                    max="{{ now()->toDateString() }}" class="input-field @if ($parcelCount->isClientAssigned()) opacity-60 @endif"
                    @disabled($parcelCount->isClientAssigned()) required>
                @error('date') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Assigned *</label>
                    <input type="number" name="assigned" value="{{ old('assigned', $parcelCount->assigned) }}"
                        class="input-field @if ($parcelCount->isClientAssigned()) opacity-60 @endif" min="0"
                        @disabled($parcelCount->isClientAssigned()) required>
                    @if ($parcelCount->isClientAssigned())
                        <p class="text-xs text-[var(--fg-secondary)] mt-1">Set by your client.</p>
                    @endif
                    @error('assigned') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Delivered *</label>
                    <input type="number" name="delivered" value="{{ old('delivered', $parcelCount->delivered) }}"
                        class="input-field" min="0" required>
                    @error('delivered') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Failed *</label>
                    <input type="number" name="failed" value="{{ old('failed', $parcelCount->failed) }}"
                        class="input-field" min="0" required>
                    @error('failed') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Returned *</label>
                    <input type="number" name="returned" value="{{ old('returned', $parcelCount->returned) }}"
                        class="input-field" min="0" required>
                    @error('returned') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex gap-5 pt-4">
                <a href="{{ route('rider.parcel.index') }}" class="btn-secondary text-center btn-sm">Cancel</a>
                <button type="submit" class="btn-primary btn-sm">Update Count</button>
            </div>
        </form>
    </div>
</div>
@endsection
