@extends('admin.layout.app')

@section('title', 'New Assignment - RiderFlow')

@section('content')
<div class="max-w-2xl">
    <div class="mb-8">
        <h2 class="font-display text-2xl font-bold">New Assignment</h2>
        <p class="text-[var(--fg-secondary)]">Assign a rider to a company for a date range</p>
    </div>

    <div class="card p-6">
        <form action="{{ route('assignment.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Rider *</label>
                    <select name="rider_id" class="input-field" required @disabled($riders->isEmpty())>
                        <option value="">Select a rider</option>
                        @foreach ($riders as $rider)
                            <option value="{{ $rider->id }}" @selected((string) old('rider_id') === (string) $rider->id)>{{ $rider->name }}</option>
                        @endforeach
                    </select>
                    @if ($riders->isEmpty())
                        <p class="text-sm text-[var(--fg-secondary)] mt-1">No active riders are free — every one is either inactive or already assigned.</p>
                    @endif
                    @error('rider_id') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Company *</label>
                    <select name="company_id" class="input-field" required>
                        <option value="">Select a company</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}" @selected((string) old('company_id') === (string) $company->id)>{{ $company->name }}</option>
                        @endforeach
                    </select>
                    @error('company_id') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Assignment Type *</label>
                <select name="type" class="input-field" required>
                    <option value="">Select a type</option>
                    @foreach (\App\Enums\AssignmentType::cases() as $type)
                        <option value="{{ $type->value }}" @selected(old('type') === $type->value)>{{ $type->value }}</option>
                    @endforeach
                </select>
                @error('type') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Start Date *</label>
                    <input type="date" name="start_date" value="{{ old('start_date', now()->toDateString()) }}" class="input-field" required>
                    @error('start_date') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">End Date</label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}" class="input-field">
                    @error('end_date') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex gap-5 pt-4">
                <a href="{{ route('assignment.index') }}" class="btn-secondary text-center btn-sm">Cancel</a>
                <button type="submit" class="btn-primary btn-sm">Create Assignment</button>
            </div>
        </form>
    </div>
</div>
@endsection
