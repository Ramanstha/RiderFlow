@extends('client.layout.app')

@section('title', 'Edit Assignment - Client Portal')

@section('content')
<div class="max-w-2xl">
    <div class="mb-8">
        <h2 class="font-display text-2xl font-bold">Edit Assignment</h2>
        <p class="text-[var(--fg-secondary)]">
            {{ $assignment->rider->name ?? 'Rider' }} &middot; {{ $assignment->date->format('d M Y') }}
        </p>
    </div>

    @if ($assignment->hasRiderReport())
        <div class="card p-4 mb-6 border-l-4 border-l-[var(--accent)]">
            <p class="text-sm">
                This rider has already reported
                <span class="font-semibold">{{ number_format($assignment->delivered) }}</span> delivered.
                The assignment cannot be set below what they have reported.
            </p>
        </div>
    @endif

    <div class="card p-6">
        <form action="{{ route('client.parcel-assignment.update', $assignment) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-2">Rider</label>
                <input type="text" class="input-field opacity-60" value="{{ $assignment->rider->name ?? '—' }}" disabled>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Date</label>
                <input type="text" class="input-field opacity-60" value="{{ $assignment->date->format('d M Y') }}" disabled>
                <p class="text-xs text-[var(--fg-secondary)] mt-1">
                    Withdraw and re-assign to move this load to another rider or day.
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Parcels to Assign *</label>
                <input type="number" name="assigned" value="{{ old('assigned', $assignment->assigned) }}"
                    class="input-field" min="1" required>
                @error('assigned') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-5 pt-4">
                <a href="{{ route('client.parcel-assignment.index') }}" class="btn-secondary text-center btn-sm">Cancel</a>
                <button type="submit" class="btn-primary btn-sm">Save Assignment</button>
            </div>
        </form>
    </div>
</div>
@endsection
