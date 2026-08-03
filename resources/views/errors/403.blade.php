<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Access Denied</title>
    <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="pattern-bg min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md text-center">
        <div class="card p-8">
            <div
                class="w-16 h-16 mx-auto mb-4 rounded-xl bg-gradient-to-br from-red-500 to-red-400 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h1 class="font-display text-2xl font-bold mb-2">Access Denied</h1>
            <p class="text-[var(--fg-secondary)] mb-6">You don't have permission to view this page. Contact a Super
                Admin if you believe this is a mistake.</p>
            <a href="{{ route('dashboard') }}" class="btn-primary inline-block">Back to Dashboard</a>
        </div>
    </div>
</body>

</html>
