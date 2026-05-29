<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Error - CBS</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="modern-bg min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-2xl">
        <div class="dashboard-card text-center">
            <div class="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-full bg-gradient-to-br from-red-600 to-rose-600 text-white shadow-xl">
                <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 sm:text-4xl">Something went wrong</h1>
            <p class="mx-auto mt-3 max-w-lg text-gray-600">The system encountered an unexpected error. The issue has been logged and the team should review it shortly.</p>
            <?php if(isset($reference)): ?>
                <div class="mt-6 rounded-lg bg-red-50 border border-red-200 p-4">
                    <p class="text-sm text-gray-700">Reference code: <strong class="text-red-700 font-mono text-lg"><?php echo e($reference); ?></strong></p>
                    <p class="text-xs text-gray-500 mt-1">Share this code with the support team for faster troubleshooting.</p>
                </div>
            <?php endif; ?>
            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
                <a href="<?php echo e(route('dashboard')); ?>" class="btn-primary inline-flex justify-center">Return to dashboard</a>
                <a href="<?php echo e(url()->previous()); ?>" class="btn-secondary inline-flex justify-center">Try again</a>
            </div>
        </div>
    </div>
</body>
</html><?php /**PATH /opt/lampp/htdocs/CBS/resources/views/errors/500.blade.php ENDPATH**/ ?>