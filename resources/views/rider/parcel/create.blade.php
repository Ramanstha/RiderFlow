@extends('rider.layout.app')

@section('title', 'Submit Parcel Count - Rider Portal')

@section('content')
<div class="max-w-2xl">
    <div class="mb-8">
        <h2 class="font-display text-2xl font-bold">Submit Parcel Count</h2>
        <p class="text-[var(--fg-secondary)]">Log your delivery numbers for the day</p>
    </div>

    <div class="card p-6">
        <form action="{{ route('rider.parcel.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-2">Date *</label>
                <input type="date" name="date" value="{{ old('date', now()->toDateString()) }}"
                    max="{{ now()->toDateString() }}" class="input-field" required>
                @error('date') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Assigned *</label>
                    <input type="number" name="assigned" value="{{ old('assigned', 0) }}" class="input-field" min="0" required>
                    @error('assigned') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Delivered *</label>
                    <input type="number" name="delivered" value="{{ old('delivered', 0) }}" class="input-field" min="0" required>
                    @error('delivered') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Failed *</label>
                    <input type="number" name="failed" value="{{ old('failed', 0) }}" class="input-field" min="0" required>
                    @error('failed') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Returned *</label>
                    <input type="number" name="returned" value="{{ old('returned', 0) }}" class="input-field" min="0" required>
                    @error('returned') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex gap-5 pt-4">
                <a href="{{ route('rider.parcel.index') }}" class="btn-secondary text-center btn-sm">Cancel</a>
                <button type="submit" class="btn-primary btn-sm">Submit Count</button>
            </div>
        </form>
    </div>
</div>
@endsection
