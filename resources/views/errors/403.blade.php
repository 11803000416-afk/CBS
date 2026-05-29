<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied - CBS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="modern-bg min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-2xl">
        <div class="dashboard-card text-center">
            <div class="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-full bg-gradient-to-br from-slate-700 to-slate-900 text-white shadow-xl" aria-hidden="true">
                <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 2a10 10 0 100 20 10 10 0 000-20zM8 12h8M12 8v8" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 sm:text-4xl">Access denied</h1>
            <p class="mx-auto mt-3 max-w-lg text-gray-600">You don’t have permission to view this resource. If this looks wrong, contact an administrator.</p>
            @if(isset($reference))
                <div class="mt-6 rounded-lg bg-amber-50 border border-amber-200 p-4">
                    <p class="text-sm text-gray-700">Reference code: <strong class="text-amber-700 font-mono">{{ $reference }}</strong></p>
                    <p class="text-xs text-gray-500 mt-1">Share this with your administrator if access should be granted.</p>
                </div>
            @endif
            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
                <a href="{{ route('dashboard') }}" class="btn-primary inline-flex justify-center">Go to dashboard</a>
                <a href="{{ url()->previous() }}" class="btn-secondary inline-flex justify-center">Go back</a>
            </div>
        </div>
    </div>
</body>
</html>