@extends('client.layout.app')

@section('title', 'Assign Parcels - Client Portal')

@section('content')
<div class="max-w-2xl">
    <div class="mb-8">
        <h2 class="font-display text-2xl font-bold">Assign Parcels</h2>
        <p class="text-[var(--fg-secondary)]">Give one of your riders a parcel load for a given day.</p>
    </div>

    <div class="card p-6">
        <form action="{{ route('client.parcel-assignment.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-2">Rider *</label>
                <select name="rider_id" class="input-field" required>
                    <option value="">Select a rider</option>
                    @foreach ($riders as $rider)
                        <option value="{{ $rider->id }}" @selected(old('rider_id') == $rider->id)>
                            {{ $rider->name }}@if ($rider->status->value !== 'Active') ({{ $rider->status->value }}) @endif
                        </option>
                    @endforeach
                </select>
                @error('rider_id') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Date *</label>
                <input type="date" name="date" value="{{ old('date', now()->toDateString()) }}" class="input-field" required>
                <p class="text-xs text-[var(--fg-secondary)] mt-1">You can assign ahead for an upcoming day.</p>
                @error('date') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Parcels to Assign *</label>
                <input type="number" name="assigned" value="{{ old('assigned') }}" class="input-field" min="1" required>
                <p class="text-xs text-[var(--fg-secondary)] mt-1">
                    If this rider already has a load for that day, this replaces the assigned figure.
                </p>
                @error('assigned') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-5 pt-4">
                <a href="{{ route('client.parcel-assignment.index') }}" class="btn-secondary text-center btn-sm">Cancel</a>
                <button type="submit" class="btn-primary btn-sm">Assign Parcels</button>
            </div>
        </form>
    </div>
</div>
@endsection
