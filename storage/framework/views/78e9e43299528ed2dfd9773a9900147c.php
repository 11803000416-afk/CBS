<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - CBS</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body class="aurora-bg min-h-screen flex items-center justify-center p-3 sm:p-4 md:p-6">
    <div class="w-full max-w-md relative z-10">
        <!-- Logo/Brand -->
        <div class="text-center mb-6 sm:mb-8">
            <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2">CBS</h1>
            <p class="text-sm sm:text-base text-gray-400">Car Broker System</p>
        </div>

        <!-- Verification Card -->
        <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 sm:p-8 shadow-2xl border border-white/20">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-white mb-2">Check your email</h2>
                <p class="text-gray-300 mb-6">
                    We've sent you a verification link. Please check your email and click the link to verify your account.
                </p>

                <?php if(session('status')): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <?php echo e(session('status')); ?>

                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('verification.send')); ?>" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                        Resend Verification Email
                    </button>
                </form>

                <div class="mt-6">
                    <a href="<?php echo e(route('logout')); ?>" class="text-blue-400 hover:text-blue-300 text-sm">
                        Logout and try different account
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html><?php /**PATH /opt/lampp/htdocs/CBS/resources/views/auth/verify-email.blade.php ENDPATH**/ ?>