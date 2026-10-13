@extends('client.layout.app')

@section('title', 'Attendance - Client Portal')

@section('content')
<div class="mb-8">
    <h2 class="font-display text-3xl lg:text-2xl font-bold mb-2">Attendance</h2>
    <p class="text-[var(--fg-secondary)] text-md">Daily attendance for the riders on your fleet.</p>
</div>

<div class="card p-6 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label class="block text-sm font-medium mb-2">Rider</label>
            <select name="rider_id" class="input-field">
                <option value="">All riders</option>
                @foreach ($riders as $rider)
                    <option class="capitalize" value="{{ $rider->id }}" @selected(request('rider_id') == $rider->id)>{{ $rider->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-2">Status</label>
            <select name="status" class="input-field">
                <option value="">All</option>
                @foreach (\App\Enums\AttendanceStatus::cases() as $status)
                    <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->value }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-2">From</label>
            <x-nepali-date name="date_from" :value="request('date_from')" class="" />
        </div>
        <div>
            <label class="block text-sm font-medium mb-2">To</label>
            <x-nepali-date name="date_to" :value="request('date_to')" class="" />
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="btn-primary flex-1 text-center">Filter</button>
            <a href="{{ route('client.attendance.index') }}" class="btn-secondary text-center px-4">Reset</a>
        </div>
    </form>
</div>

<div class="card p-6">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-[var(--fg-secondary)] border-b border-[var(--border)]">
                    <th class="py-3 pr-4 font-medium">Rider</th>
                    <th class="py-3 pr-4 font-medium">Date</th>
                    <th class="py-3 pr-4 font-medium">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $record)
                    <tr class="border-b border-[var(--border)] last:border-0">
                        <td class="py-3 pr-4 capitalize">{{ $record->rider->name ?? '—' }}</td>
                        <td class="py-3 pr-4">{{ bs($record->date, 'd M Y') }}</td>
                        <td class="py-3 pr-4">
                            <span class="badge badge-{{ $record->status->value === 'Present' ? 'success' : 'danger' }}">
                                {{ $record->status->value }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-8 text-center text-[var(--fg-secondary)]">No attendance records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $records->links() }}</div>
</div>
@endsection
