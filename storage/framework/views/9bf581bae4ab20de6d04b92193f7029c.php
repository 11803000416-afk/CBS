<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - CBS Car Broker System</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', system-ui, sans-serif; }
        .animate-tilt {
            animation: tilt 3s infinite linear;
        }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
        @keyframes tilt {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(0.5deg); }
            75% { transform: rotate(-0.5deg); }
        }
        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        .gradient-shift {
            background-size: 200% 200%;
            animation: gradientShift 6s ease infinite;
        }
    </style>
</head>
<body class="relative min-h-screen flex items-center justify-center p-4 sm:p-6 overflow-hidden">
    <!-- Professional Gradient Background -->
    <div class="fixed inset-0 bg-gradient-to-br from-slate-900 via-blue-900 to-cyan-900 gradient-shift"></div>
    <div class="fixed inset-0 opacity-10">
        <div class="absolute top-20 left-10 w-72 h-72 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl"></div>
        <div class="absolute top-40 right-10 w-72 h-72 bg-cyan-500 rounded-full mix-blend-multiply filter blur-3xl animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl animation-delay-4000"></div>
    </div>

    <!-- Content -->
    <div class="relative z-10 w-full max-w-md">
        <!-- Logo/Brand Header -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center mb-6">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-2xl blur-lg opacity-75 group-hover:opacity-100 transition duration-1000 animate-tilt" style="width: 80px; height: 80px; margin: auto;"></div>
                <div class="relative w-20 h-20 bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl flex items-center justify-center shadow-2xl border border-cyan-400/30">
                    <svg class="w-12 h-12 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            </div>
            <h1 class="text-4xl sm:text-5xl font-bold text-white mb-2 tracking-tight">Car Broker System</h1>
            <p class="text-cyan-300 text-base font-medium tracking-wider">Professional Vehicle Marketplace Platform</p>
        </div>

        <!-- Form Card -->
        <div class="backdrop-blur-xl bg-white/95 border border-white/20 rounded-3xl p-8 sm:p-10 shadow-2xl relative overflow-hidden">
            <!-- Decorative Top Border -->
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-500 via-cyan-500 to-purple-500 rounded-t-3xl"></div>

            <!-- Form Header -->
            <div class="text-center mb-9">
                <h2 class="text-3xl font-bold bg-gradient-to-r from-slate-900 to-cyan-600 bg-clip-text text-transparent mb-2">Welcome Back</h2>
                <p class="text-slate-600 font-medium text-lg">Sign in to your account</p>
            </div>

            <!-- Success Message -->
            <?php if(session('success')): ?>
                <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="font-bold text-green-900">Welcome!</p>
                            <p class="text-sm text-green-700"><?php echo e(session('success')); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Error Messages -->
            <?php if($errors->any()): ?>
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="font-bold text-red-900">Sign In Failed</p>
                            <p class="text-sm text-red-700 mt-1"><?php echo e($errors->first()); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form method="POST" action="<?php echo e(route('login.store', [], false)); ?>" class="space-y-5">
                <?php echo csrf_field(); ?>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-bold text-slate-900 mb-3">Email Address</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-cyan-500 transition-colors group-focus-within:text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input id="email" name="email" type="email" value="<?php echo e(old('email')); ?>" autocomplete="email" autofocus
                            class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border-2 border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 font-medium transition-all duration-200 focus:border-cyan-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-500/20 hover:border-slate-300" 
                            placeholder="you@example.com" required>
                    </div>
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-red-600 text-sm mt-2 block flex items-center gap-1" role="alert">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                            <?php echo e($message); ?>

                        </span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-bold text-slate-900 mb-3">Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-cyan-500 transition-colors group-focus-within:text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password"
                            class="w-full pl-12 pr-12 py-3.5 bg-slate-50 border-2 border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 font-medium transition-all duration-200 focus:border-cyan-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-500/20 hover:border-slate-300" 
                            placeholder="••••••••" required>
                        <button type="button" class="password-toggle-btn absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-cyan-600 transition-colors" onclick="togglePassword(this)">
                            <svg class="h-5 w-5 hidden eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg class="h-5 w-5 eye-closed" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-2.29m5.159-2.674A9.01 9.01 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.009 10.009 0 01-4.232 5.251m-7.08-10.273a3 3 0 015.364 1.834M9 17a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </button>
                    </div>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-red-600 text-sm mt-2 block flex items-center gap-1" role="alert">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                            <?php echo e($message); ?>

                        </span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between pt-2">
                    <label class="flex items-center gap-3 text-sm text-slate-600 cursor-pointer group">
                        <input type="checkbox" name="remember" class="w-5 h-5 rounded border-2 border-slate-300 bg-white text-cyan-600 focus:ring-2 focus:ring-cyan-500/50 transition-all">
                        <span class="font-medium group-hover:text-slate-900 transition">Remember for 30 days</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                    class="w-full mt-8 py-3.5 px-4 bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 focus:ring-offset-white/80 text-lg">
                    <span class="flex items-center justify-center gap-2">
                        Sign In to CBS
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </span>
                </button>
            </form>

            <!-- Divider -->
            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white/95 text-slate-600 font-medium">New to CBS?</span>
                </div>
            </div>

            <!-- Register Link -->
            <a href="<?php echo e(route('register')); ?>" class="w-full block text-center px-4 py-3 rounded-xl border-2 border-cyan-500 text-cyan-600 hover:bg-cyan-50 font-bold transition-all hover:translate-y-[-2px] text-base">
                Create Account
            </a>
        </div>

        <!-- Footer -->
        <div class="text-center mt-10">
            <p class="text-cyan-200/60 text-sm font-medium">© <?php echo e(date('Y')); ?> CBS - Car Broker System</p>
            <p class="text-cyan-200/40 text-xs mt-2">Secure • Fast • Reliable</p>
        </div>
    </div>

    <script>
        function togglePassword(btn) {
            const input = btn.parentElement.querySelector('input');
            const eyeOpen = btn.querySelector('.eye-open');
            const eyeClosed = btn.querySelector('.eye-closed');
            
            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen?.classList.remove('hidden');
                eyeClosed?.classList.add('hidden');
            } else {
                input.type = 'password';
                eyeOpen?.classList.add('hidden');
                eyeClosed?.classList.remove('hidden');
            }
        }
    </script>
</body>
</html>
<?php /**PATH /opt/lampp/htdocs/CBS/resources/views/auth/login.blade.php ENDPATH**/ ?>