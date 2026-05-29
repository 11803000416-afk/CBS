<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Unavailable - CBS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="modern-bg min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-2xl">
        <div class="dashboard-card text-center">
            <div class="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-full bg-gradient-to-br from-amber-500 to-orange-500 text-white shadow-xl">
                <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 sm:text-4xl">Database temporarily unavailable</h1>
            <p class="mx-auto mt-3 max-w-lg text-gray-600">
                {{ $errorMessage ?? 'CBS cannot reach the database right now. Please check the database service and try again.' }}
            </p>
            @if(isset($reference))
                <div class="mt-6 rounded-lg bg-amber-50 border border-amber-200 p-4">
                    <p class="text-sm text-gray-700">Reference code: <strong class="text-amber-700 font-mono text-lg">{{ $reference }}</strong></p>
                    <p class="text-xs text-gray-500 mt-1">Share this code with support when reporting the issue.</p>
                </div>
            @endif
            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
                <a href="{{ url('/') }}" class="btn-primary inline-flex justify-center">Return home</a>
                <a href="{{ url()->current() }}" class="btn-secondary inline-flex justify-center">Retry</a>
            </div>
        </div>
    </div>
</body>
</html>
