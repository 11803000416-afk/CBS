<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CBS</title>
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

        <!-- Login Card -->
        <div class="glass-card rounded-2xl p-6 sm:p-8 shadow-2xl">
            <!-- Welcome Text -->
            <div class="text-center mb-6 sm:mb-8">
                <h2 class="text-xl sm:text-2xl font-bold text-white mb-1 sm:mb-2">Welcome Back!</h2>
                <p class="text-sm sm:text-base text-gray-400">Sign in to access your dashboard</p>
            </div>

            <!-- Error Messages -->
            <?php if($errors->any()): ?>
                <div class="mb-6 p-3 sm:p-4 rounded-lg bg-red-500/20 border border-red-500/30 backdrop-blur-sm animate-pulse">
                    <div class="flex items-start gap-2 sm:gap-3">
                        <div class="flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-red-300 mb-1 text-sm sm:text-base">Login Failed</p>
                            <p class="text-xs sm:text-sm text-red-200"><?php echo e($errors->first()); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form method="POST" action="<?php echo e(route('login.store')); ?>" class="space-y-4 sm:space-y-5">
                <?php echo csrf_field(); ?>
                
                <!-- Email Field -->
                <div>
                    <label class="label-primary text-xs sm:text-sm">
                        Email Address
                    </label>
                    <input name="email" type="email" value="<?php echo e(old('email')); ?>" 
                        class="input-field text-sm sm:text-base" 
                        placeholder="your@email.com" required>
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-red-400 text-xs sm:text-sm mt-1 block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Password Field with Toggle -->
                <?php echo $__env->make('components.password-input-dark', [
                    'name' => 'password',
                    'label' => 'Password',
                    'placeholder' => 'Enter your password'
                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <!-- Remember Me -->
                <div class="flex items-center justify-between pt-2">
                    <label class="flex items-center gap-2 text-xs sm:text-sm text-gray-300 cursor-pointer hover:text-white">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-white/20 bg-white/10 text-blue-600 focus:ring-2 focus:ring-blue-500">
                        <span>Remember me</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                    class="btn-primary-lg text-sm sm:text-base mt-6 sm:mt-8 hover:shadow-xl">
                    Sign In
                </button>
            </form>

            <!-- Register Link -->
            <div class="mt-4 sm:mt-6 text-center text-xs sm:text-sm">
                <p class="text-gray-400">
                    Don't have an account? 
                    <a href="<?php echo e(route('register')); ?>" class="font-semibold text-blue-400 hover:text-blue-300 transition">
                        Create Account
                    </a>
                </p>
            </div>

            <!-- Demo Credentials -->
            <div class="mt-4 sm:mt-6 p-3 sm:p-4 bg-white/5 border border-white/10 rounded-lg backdrop-blur-sm">
                <p class="font-semibold text-white mb-2 text-xs sm:text-sm">🔑 Demo Accounts:</p>
                <div class="space-y-1 text-xs text-gray-300">
                    <p><strong class="text-gray-200">Admin:</strong> <code class="bg-white/10 px-2 py-1 rounded border border-white/20 text-xs">admin@cbs.bt</code></p>
                    <p><strong class="text-gray-200">Agent:</strong> <code class="bg-white/10 px-2 py-1 rounded border border-white/20 text-xs">agent@cbs.bt</code></p>
                    <p><strong class="text-gray-200">Buyer:</strong> <code class="bg-white/10 px-2 py-1 rounded border border-white/20 text-xs">buyer@cbs.bt</code></p>
                    <p class="pt-1"><strong class="text-gray-200">Password:</strong> <code class="bg-white/10 px-2 py-1 rounded border border-white/20 text-xs">password</code></p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 sm:mt-8 text-xs text-gray-400">
            <p>© <?php echo e(date('Y')); ?> CBS - Car Broker System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\CBS\resources\views/auth/login.blade.php ENDPATH**/ ?>