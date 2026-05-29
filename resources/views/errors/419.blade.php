<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Expired - CBS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="modern-bg min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-2xl">
        <div class="dashboard-card text-center">
            <div class="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-full bg-gradient-to-br from-amber-500 to-orange-500 text-white shadow-xl" aria-hidden="true">
                <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01M10.29 3.86l-8.24 14.26A2 2 0 003.79 21h16.42a2 2 0 001.74-2.88L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 sm:text-4xl">Session expired</h1>
            <p class="mx-auto mt-3 max-w-lg text-gray-600">Your session timed out for security. Please sign in again to continue using CBS.</p>
            @if(isset($reference))
                <div class="mt-6 rounded-lg bg-amber-50 border border-amber-200 p-4">
                    <p class="text-sm text-gray-700">Reference code: <strong class="text-amber-700 font-mono">{{ $reference }}</strong></p>
                    <p class="text-xs text-gray-500 mt-1">Your session has been invalidated for security.</p>
                </div>
            @endif
            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
                <a href="{{ route('login') }}" class="btn-primary inline-flex justify-center">Sign in again</a>
                <a href="{{ route('dashboard') }}" class="btn-secondary inline-flex justify-center">Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>