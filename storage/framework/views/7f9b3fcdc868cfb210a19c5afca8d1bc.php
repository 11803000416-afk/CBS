<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - CBS</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="modern-bg min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-2xl">
        <div class="dashboard-card text-center">
            <div class="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-full bg-gradient-to-br from-blue-600 to-purple-600 text-white shadow-xl">
                <span class="text-4xl font-black">404</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 sm:text-4xl">Page not found</h1>
            <p class="mx-auto mt-3 max-w-lg text-gray-600">The page you’re looking for doesn’t exist or has moved. Use the links below to return to the CBS dashboard.</p>
            <?php if(isset($reference)): ?>
                <div class="mt-6 rounded-lg bg-blue-50 border border-blue-200 p-4">
                    <p class="text-sm text-gray-700">Reference code: <strong class="text-blue-700 font-mono"><?php echo e($reference); ?></strong></p>
                    <p class="text-xs text-gray-500 mt-1">Share this with support for faster assistance.</p>
                </div>
            <?php endif; ?>
            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
                <a href="<?php echo e(route('dashboard')); ?>" class="btn-primary inline-flex justify-center">Go to dashboard</a>
                <a href="<?php echo e(url()->previous()); ?>" class="btn-secondary inline-flex justify-center">Go back</a>
            </div>
        </div>
    </div>
</body>
</html><?php /**PATH /opt/lampp/htdocs/CBS/resources/views/errors/404.blade.php ENDPATH**/ ?>