@extends('admin.layout.app')

@section('title', 'Edit Assignment - RiderFlow')

@section('content')
<div class="max-w-2xl">
    <div class="mb-8">
        <h2 class="font-display text-3xl font-bold mb-2">Edit Assignment</h2>
        <p class="text-[var(--fg-secondary)]">Update {{ $assignment->rider->name }}'s assignment</p>
    </div>

    <div class="card p-6">
        <form action="{{ route('assignment.update', $assignment) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Rider *</label>
                    <select name="rider_id" class="input-field" required>
                        @foreach ($riders as $rider)
                            <option value="{{ $rider->id }}" @selected((string) old('rider_id', $assignment->rider_id) === (string) $rider->id)>{{ $rider->name }}</option>
                        @endforeach
                    </select>
                    @error('rider_id') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Company *</label>
                    <select name="company_id" class="input-field" required>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}" @selected((string) old('company_id', $assignment->company_id) === (string) $company->id)>{{ $company->name }}</option>
                        @endforeach
                    </select>
                    @error('company_id') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Assignment Type *</label>
                <select name="type" class="input-field" required>
                    @foreach (\App\Enums\AssignmentType::cases() as $type)
                        <option value="{{ $type->value }}" @selected(old('type', $assignment->type->value) === $type->value)>{{ $type->value }}</option>
                    @endforeach
                </select>
                @error('type') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Start Date *</label>
                    <input type="date" name="start_date" value="{{ old('start_date', $assignment->start_date->toDateString()) }}" class="input-field" required>
                    @error('start_date') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">End Date</label>
                    <input type="date" name="end_date" value="{{ old('end_date', $assignment->end_date?->toDateString()) }}" class="input-field">
                    @error('end_date') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex gap-3 pt-4">
                <a href="{{ route('assignment.index') }}" class="btn-secondary flex-1 text-center">Cancel</a>
                <button type="submit" class="btn-primary flex-1">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
