<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - RiderFlow</title>
    <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="pattern-bg min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="flex flex-col items-center gap-3 mb-8">
            <div
                class="w-14 h-14 rounded-xl bg-gradient-to-br from-[var(--accent)] to-cyan-400 flex items-center justify-center glow-accent">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-900" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <div class="text-center">
                <h1 class="font-display text-2xl font-bold">RiderFlow</h1>
                <p class="text-sm text-[var(--fg-secondary)]">Sign in to your account</p>
            </div>
        </div>

        <div class="card p-6">
            @if (session('status'))
                <div class="mb-4 text-sm text-[var(--accent)]">{{ session('status') }}</div>
            @endif

            <form action="{{ route('login.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="input-field"
                        placeholder="you@company.com" required autofocus>
                    @error('email') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Password</label>
                    <input type="password" name="password" class="input-field" placeholder="••••••••" required>
                    @error('password') <p class="text-sm text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <label class="flex items-center gap-2 text-sm text-[var(--fg-secondary)]">
                    <input type="checkbox" name="remember">
                    Remember me
                </label>

                <button type="submit" class="btn-primary w-full text-center">Sign In</button>
            </form>
        </div>

        <p class="text-center text-sm text-[var(--fg-secondary)] mt-6">
            Staff, riders and client companies all sign in here.<br>
            You will be taken to your own dashboard automatically.
        </p>
    </div>
</body>

</html>
