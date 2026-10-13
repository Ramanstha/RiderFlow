@extends('admin.layout.app')

@section('title', 'Edit Performance Record - RiderFlow')

@section('content')
<div class="max-w-2xl">
    <div class="mb-8">
        <h2 class="font-display text-3xl font-bold mb-2">Edit Performance Record</h2>
        <p class="text-[var(--fg-secondary)]">Update {{ $performance->rider->name }}'s {{ bs($performance->period, 'F Y') }} performance</p>
    </div>

    <div class="card p-6">
        <form action="{{ route('performance.update', $performance) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Rider *</label>
                    <select name="rider_id" class="input-field" required>
                        @foreach ($riders as $rider)
                            <option value="{{ $rider->id }}" @selected((string) old('rider_id', $performance->rider_id) === (string) $rider->id)>{{ $rider->name }}</option>
                        @endforeach
                    </select>
                    @error('rider_id') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Period (month) *</label>
                    <x-nepali-month name="period" :value="old('period', $performance->period)" required />
                    @error('period') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Score (%) *</label>
                <input type="number" name="score" value="{{ old('score', $performance->score) }}" class="input-field" min="0" max="100" required>
                @error('score') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Deliveries Completed</label>
                    <input type="number" name="deliveries_completed" value="{{ old('deliveries_completed', $performance->deliveries_completed) }}" class="input-field" min="0">
                    @error('deliveries_completed') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Deliveries Failed</label>
                    <input type="number" name="deliveries_failed" value="{{ old('deliveries_failed', $performance->deliveries_failed) }}" class="input-field" min="0">
                    @error('deliveries_failed') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Attendance Rate (%)</label>
                <input type="number" step="0.01" name="attendance_rate" value="{{ old('attendance_rate', $performance->attendance_rate) }}" class="input-field" min="0" max="100">
                @error('attendance_rate') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Notes</label>
                <textarea name="notes" class="input-field" rows="3">{{ old('notes', $performance->notes) }}</textarea>
                @error('notes') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-4">
                <a href="{{ route('performance.index') }}" class="btn-secondary flex-1 text-center">Cancel</a>
                <button type="submit" class="btn-primary flex-1">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
