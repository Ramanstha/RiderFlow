@if (session('success'))
    <div class="card p-4 mb-6 border-l-4 border-l-[var(--accent)]">
        <p class="text-sm">{{ session('success') }}</p>
    </div>
@endif

@if ($errors->any())
    <div class="card p-4 mb-6 border-l-4 border-l-red-500">
        <ul class="text-sm space-y-1">
            @foreach ($errors->all() as $error)
                <li class="text-red-400">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
