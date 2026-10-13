@extends('admin.layout.app')

@section('title', 'Users - RiderFlow')

@section('content')
<div id="usersPage" class="page-section">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="font-display text-2xl font-bold mb-1">Users</h2>
            <p class="text-[var(--fg-secondary)] text-sm">Manage admin panel access and roles</p>
        </div>
        <a href="{{ route('user.create') }}" class="btn-primary flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add New User
        </a>
    </div>

    @if (session('success'))
        <div class="card p-4 mb-6 border-l-4 border-l-[var(--accent)]">
            <p class="text-sm">{{ session('success') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="card p-4 mb-6 border-l-4 border-l-red-500">
            <p class="text-sm text-red-400">{{ $errors->first() }}</p>
        </div>
    @endif

    <div class="card p-3 mb-6">
        <form method="GET" action="{{ route('user.index') }}" class="filter-bar flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..."
                class="input-field flex-1 min-w-[200px]">
            <button type="submit" class="btn-secondary text-sm px-5">Filter</button>
        </form>
    </div>

    <div class="card overflow-hidden">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td><p class="font-medium">{{ $user->name }}</p></td>
                            <td><span class="text-sm">{{ $user->email }}</span></td>
                            <td><span class="badge badge-success">{{ $user->roles->pluck('name')->first() ?? '-' }}</span></td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('user.edit', $user) }}"
                                        class="p-2 rounded-lg hover:bg-[var(--bg-secondary)] transition-all" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('user.destroy', $user) }}" method="POST"
                                        onsubmit="return confirm('Remove this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg hover:bg-red-500/10 text-red-400 transition-all" title="Remove">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-[var(--fg-secondary)] py-8">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-4 border-t border-[var(--border)]">
            <p class="text-sm text-[var(--fg-secondary)]">
                Showing {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
            </p>
            <div class="flex gap-2">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
