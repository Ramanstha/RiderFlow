@extends('client.layout.app')

@section('title', 'My Riders - Client Portal')

@section('content')
<div class="mb-8">
    <h2 class="font-display text-3xl lg:text-2xl font-bold mb-2">My Riders</h2>
    <p class="text-[var(--fg-secondary)] text-md">The riders currently assigned to your company.</p>
</div>

<div class="card p-6 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-2">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" class="input-field"
                placeholder="Name, email or phone">
        </div>
        <div>
            <label class="block text-sm font-medium mb-2">Status</label>
            <select name="status" class="input-field">
                <option value="">All</option>
                @foreach (\App\Enums\RiderStatus::cases() as $status)
                    <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->value }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="btn-primary flex-1 text-center">Filter</button>
            <a href="{{ route('client.rider.index') }}" class="btn-secondary text-center px-4">Reset</a>
        </div>
    </form>
</div>

<div class="card p-6">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-[var(--fg-secondary)] border-b border-[var(--border)]">
                    <th class="py-3 pr-4 font-medium">Rider</th>
                    <th class="py-3 pr-4 font-medium">Contact</th>
                    <th class="py-3 pr-4 font-medium">Licence</th>
                    <th class="py-3 pr-4 font-medium">Status</th>
                    <th class="py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($riders as $rider)
                    <tr class="border-b border-[var(--border)] last:border-0">
                        <td class="py-3 pr-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $rider->photo ? asset('storage/'.$rider->photo) : 'https://ui-avatars.com/api/?name='.urlencode($rider->name) }}"
                                    alt="{{ $rider->name }}" class="avatar w-9 h-9">
                                <span class="font-medium">{{ $rider->name }}</span>
                            </div>
                        </td>
                        <td class="py-3 pr-4">
                            <p>{{ $rider->email }}</p>
                            <p class="text-[var(--fg-secondary)]">{{ $rider->phone }}</p>
                        </td>
                        <td class="py-3 pr-4 font-mono text-xs">{{ $rider->license_number }}</td>
                        <td class="py-3 pr-4">
                            <span class="badge badge-{{ $rider->status->value === 'Active' ? 'success' : ($rider->status->value === 'Inactive' ? 'danger' : 'warning') }}">
                                {{ $rider->status->value }}
                            </span>
                        </td>
                        <td class="py-3 text-right">
                            <a href="{{ route('client.rider.show', $rider) }}" class="text-[var(--accent)]">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-[var(--fg-secondary)]">
                            No riders match your filters.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $riders->links() }}</div>
</div>
@endsection
