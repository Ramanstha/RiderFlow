@extends('admin.layout.app')

@section('title', 'Add Performance Record - RiderFlow')

@section('content')
<div class="max-w-2xl">
    <div class="mb-8">
        <h2 class="font-display text-2xl font-bold">Add Performance Record</h2>
        <p class="text-[var(--fg-secondary)]">Log a rider's monthly performance snapshot</p>
    </div>

    <div class="card p-6">
        <form action="{{ route('performance.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Rider *</label>
                    <select name="rider_id" class="input-field" required>
                        <option value="">Select a rider</option>
                        @foreach ($riders as $rider)
                            <option value="{{ $rider->id }}" @selected((string) old('rider_id') === (string) $rider->id)>{{ $rider->name }}</option>
                        @endforeach
                    </select>
                    @error('rider_id') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Period (month) *</label>
                    <input type="month" name="period" value="{{ old('period', now()->format('Y-m')) }}" class="input-field" required>
                    @error('period') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Score (%) *</label>
                <input type="number" name="score" value="{{ old('score', 0) }}" class="input-field" min="0" max="100" required>
                @error('score') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Deliveries Completed</label>
                    <input type="number" name="deliveries_completed" value="{{ old('deliveries_completed', 0) }}" class="input-field" min="0">
                    @error('deliveries_completed') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Deliveries Failed</label>
                    <input type="number" name="deliveries_failed" value="{{ old('deliveries_failed', 0) }}" class="input-field" min="0">
                    @error('deliveries_failed') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Attendance Rate (%)</label>
                <input type="number" step="0.01" name="attendance_rate" value="{{ old('attendance_rate') }}" class="input-field" min="0" max="100">
                @error('attendance_rate') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Notes</label>
                <textarea name="notes" class="input-field" rows="3">{{ old('notes') }}</textarea>
                @error('notes') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-5 pt-4">
                <a href="{{ route('performance.index') }}" class="btn-secondary text-center btn-sm">Cancel</a>
                <button type="submit" class="btn-primary btn-sm">Save Record</button>
            </div>
        </form>
    </div>
</div>
@endsection
