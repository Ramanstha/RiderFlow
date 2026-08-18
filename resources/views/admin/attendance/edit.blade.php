@extends('admin.layout.app')

@section('title', 'Edit Attendance - RiderFlow')

@section('content')
<div class="max-w-2xl">
    <div class="mb-8">
        <h2 class="font-display text-3xl font-bold mb-2">Edit Attendance</h2>
        <p class="text-[var(--fg-secondary)]">Update {{ $attendance->rider->name }}'s attendance record</p>
    </div>

    <div class="card p-6">
        <form action="{{ route('attendance.update', $attendance) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Rider *</label>
                    <select name="rider_id" class="input-field" required>
                        @foreach ($riders as $rider)
                            <option value="{{ $rider->id }}" @selected((string) old('rider_id', $attendance->rider_id) === (string) $rider->id)>{{ $rider->name }}</option>
                        @endforeach
                    </select>
                    @error('rider_id') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Date *</label>
                    <input type="date" name="date" value="{{ old('date', $attendance->date->toDateString()) }}" class="input-field" required>
                    @error('date') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Status *</label>
                <select name="status" class="input-field" required>
                    @foreach (\App\Enums\AttendanceStatus::cases() as $status)
                        <option value="{{ $status->value }}" @selected(old('status', $attendance->status->value) === $status->value)>{{ $status->value }}</option>
                    @endforeach
                </select>
                @error('status') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-4">
                <a href="{{ route('attendance.index') }}" class="btn-secondary flex-1 text-center">Cancel</a>
                <button type="submit" class="btn-primary flex-1">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
