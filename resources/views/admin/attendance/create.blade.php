@extends('admin.layout.app')

@section('title', 'Record Attendance - RiderFlow')

@section('content')
<div class="max-w-2xl">
    <div class="mb-8">
        <h2 class="font-display text-2xl font-bold">Record Attendance</h2>
        <p class="text-[var(--fg-secondary)]">Log a rider's attendance for a given day</p>
    </div>

    <div class="card p-6">
        <form action="{{ route('attendance.store') }}" method="POST" class="space-y-4">
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
                    <label class="block text-sm font-medium mb-2">Date *</label>
                    <x-nepali-date name="date" :value="old('date', now()->toDateString())" required />
                    @error('date') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Status *</label>
                <select name="status" class="input-field" required>
                    @foreach (\App\Enums\AttendanceStatus::cases() as $status)
                        <option value="{{ $status->value }}" @selected(old('status') === $status->value)>{{ $status->value }}</option>
                    @endforeach
                </select>
                @error('status') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-5 pt-4">
                <a href="{{ route('attendance.index') }}" class="btn-secondary text-center btn-sm">Cancel</a>
                <button type="submit" class="btn-primary btn-sm">Save Attendance</button>
            </div>
        </form>
    </div>
</div>
@endsection
