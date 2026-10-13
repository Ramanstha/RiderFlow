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
                <x-nepali-date name="date" :value="old('date', now()->toDateString())" :max="now()" required />
                @error('date') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Assigned *</label>
                    <input type="number" name="assigned" value="{{ old('assigned', 0) }}" class="input-field" min="0" required>
                    @error('assigned') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Delivered</label>
                    <input type="number" name="delivered" value="{{ old('delivered') }}" class="input-field" min="0">
                    @error('delivered') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Start KM *</label>
                    <input type="number" name="start_km" value="{{ old('start_km', 0) }}" class="input-field" min="0" required>
                    @error('start_km') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">End KM</label>
                    <input type="number" name="end_km" value="{{ old('end_km') }}" class="input-field" min="0">
                    @error('end_km') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <p class="text-xs text-[var(--fg-secondary)]">You can leave Delivered and End KM blank now and fill them in at the end of the day.</p>

            <div class="flex gap-5 pt-4">
                <a href="{{ route('rider.parcel.index') }}" class="btn-secondary text-center btn-sm">Cancel</a>
                <button type="submit" class="btn-primary btn-sm">Submit Count</button>
            </div>
        </form>
    </div>
</div>
@endsection
