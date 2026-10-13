@extends('admin.layout.app')

@section('title', 'Edit Parcel Count - RiderFlow')

@section('content')
<div class="max-w-2xl">
    <div class="mb-8">
        <h2 class="font-display text-3xl font-bold mb-2">Edit Parcel Count</h2>
        <p class="text-[var(--fg-secondary)]">Update {{ $parcelCount->rider->name }}'s parcel count for {{ bs($parcelCount->date, 'Y-m-d') }}</p>
    </div>

    <div class="card p-6">
        <form action="{{ route('parcel.update', $parcelCount) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Rider *</label>
                    <select name="rider_id" class="input-field" required>
                        @foreach ($riders as $rider)
                            <option value="{{ $rider->id }}" @selected((string) old('rider_id', $parcelCount->rider_id) === (string) $rider->id)>{{ $rider->name }}</option>
                        @endforeach
                    </select>
                    @error('rider_id') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Date *</label>
                    <x-nepali-date name="date" :value="old('date', $parcelCount->date->toDateString())" required />
                    @error('date') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Assigned *</label>
                    <input type="number" name="assigned" value="{{ old('assigned', $parcelCount->assigned) }}" class="input-field" min="0" required>
                    @error('assigned') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Delivered</label>
                    <input type="number" name="delivered" value="{{ old('delivered', $parcelCount->delivered) }}" class="input-field" min="0">
                    @error('delivered') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Start KM *</label>
                    <input type="number" name="start_km" value="{{ old('start_km', $parcelCount->start_km) }}" class="input-field" min="0" required>
                    @error('start_km') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">End KM</label>
                    <input type="number" name="end_km" value="{{ old('end_km', $parcelCount->end_km) }}" class="input-field" min="0">
                    @error('end_km') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex gap-3 pt-4">
                <a href="{{ route('parcel.index') }}" class="btn-secondary flex-1 text-center">Cancel</a>
                <button type="submit" class="btn-primary flex-1">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
