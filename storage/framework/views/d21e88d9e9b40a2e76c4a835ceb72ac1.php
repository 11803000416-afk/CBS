<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CBS - Car Broker System</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-50 min-h-screen font-sans">
    <div class="min-h-screen flex flex-col lg:flex-row">
        <!-- Left Panel - Hidden on Mobile -->
        <div class="hidden lg:flex lg:w-1/2 xl:w-2/5 bg-gradient-to-br from-emerald-600 via-green-700 to-teal-800 relative overflow-hidden">
            <!-- Decorative elements -->
            <div class="absolute top-10 sm:top-20 right-10 sm:right-20 w-48 sm:w-64 h-48 sm:h-64 bg-green-400/20 rounded-full blur-3xl opacity-50"></div>
            <div class="absolute -bottom-10 -left-10 w-64 sm:w-80 h-64 sm:h-80 bg-teal-400/20 rounded-full blur-3xl opacity-50"></div>
            
            <div class="relative z-10 flex flex-col items-center justify-center w-full p-6 sm:p-12 text-white">
                <!-- CBS Logo -->
                <div class="mb-6 sm:mb-8">
                    <div class="w-24 sm:w-32 h-24 sm:h-32 bg-gradient-to-br from-amber-400 to-orange-500 rounded-3xl flex items-center justify-center shadow-2xl">
                        <svg class="w-12 sm:w-20 h-12 sm:h-20 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/>
                        </svg>
                    </div>
                </div>

                <h1 class="text-3xl sm:text-5xl font-extrabold text-white mb-3 sm:mb-4 text-center tracking-tight">Join CBS</h1>
                <p class="text-xl sm:text-2xl font-medium text-green-100 mb-2 sm:mb-3 text-center">Car Broker System</p>
                <p class="text-sm sm:text-lg text-green-100 text-center max-w-md mb-8 sm:mb-12">
                    Create your account and start exploring quality vehicles today
                </p>

                <!-- Benefits -->
                <div class="w-full max-w-md space-y-3 sm:space-y-4">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 sm:p-5 border border-white/20 hover:bg-white/15 transition">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="w-12 sm:w-14 h-12 sm:h-14 bg-gradient-to-br from-yellow-400 to-amber-500 rounded-lg flex items-center justify-center flex-shrink-0 shadow-lg">
                                <svg class="w-6 sm:w-8 h-6 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <p class="font-bold text-white text-sm sm:text-lg">Browse Vehicles</p>
                                <p class="text-xs sm:text-sm text-green-100">Access our complete inventory</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 sm:p-5 border border-white/20 hover:bg-white/15 transition">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="w-12 sm:w-14 h-12 sm:h-14 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-lg flex items-center justify-center flex-shrink-0 shadow-lg">
                                <svg class="w-6 sm:w-8 h-6 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                            </div>
                            <div>
                                <p class="font-bold text-white text-sm sm:text-lg">Send Inquiries</p>
                                <p class="text-xs sm:text-sm text-green-100">Connect with sellers directly</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 sm:p-5 border border-white/20 hover:bg-white/15 transition">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="w-12 sm:w-14 h-12 sm:h-14 bg-gradient-to-br from-pink-400 to-rose-500 rounded-lg flex items-center justify-center flex-shrink-0 shadow-lg">
                                <svg class="w-6 sm:w-8 h-6 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            </div>
                            <div>
                                <p class="font-bold text-white text-sm sm:text-lg">Sell Your Vehicle</p>
                                <p class="text-xs sm:text-sm text-green-100">List and manage your cars</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Registration Form -->
        <div class="flex-1 flex items-center justify-center p-4 sm:p-6 md:p-8 bg-white">
            <div class="w-full max-w-2xl">
                <!-- Mobile Logo -->
                <div class="flex lg:hidden justify-center mb-4 sm:mb-6">
                    <div class="w-16 sm:w-20 h-16 sm:h-20 bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl flex items-center justify-center shadow-xl">
                        <svg class="w-8 sm:w-12 h-8 sm:h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/>
                        </svg>
                    </div>
                </div>

                <!-- Welcome Text -->
                <div class="text-center mb-6 sm:mb-8 animation-fadeIn">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mb-1 sm:mb-2">Create Your Account</h2>
                    <p id="register-help" class="text-sm sm:text-base text-slate-600">Join CBS and start browsing vehicles today</p>
                </div>

                <!-- Registration Form -->
                <form method="POST" action="<?php echo e(route('register.store', [], false)); ?>" class="space-y-4 sm:space-y-5 animation-slideDown">
                    <?php echo csrf_field(); ?>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                        <!-- Full Name -->
                        <div class="md:col-span-2">
                            <label class="label-light text-xs sm:text-sm">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Full Name *
                                </span>
                            </label>
                            <input name="name" type="text" value="<?php echo e(old('name')); ?>" 
                                class="input-field-light text-sm sm:text-base" 
                                placeholder="Your full name" required>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-red-500 text-xs sm:text-sm mt-1 block"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="label-light text-xs sm:text-sm">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    Email Address *
                                </span>
                            </label>
                            <input name="email" type="email" value="<?php echo e(old('email')); ?>" 
                                class="input-field-light text-sm sm:text-base" 
                                placeholder="your@email.com" required>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-red-500 text-xs sm:text-sm mt-1 block"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="label-light text-xs sm:text-sm">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    Phone Number
                                </span>
                            </label>
                            <input name="phone" type="text" value="<?php echo e(old('phone')); ?>" 
                                class="input-field-light text-sm sm:text-base" 
                                placeholder="+975 17 123 456">
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label class="label-light text-xs sm:text-sm">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Address
                                </span>
                            </label>
                            <input name="address" type="text" value="<?php echo e(old('address')); ?>" 
                                class="input-field-light text-sm sm:text-base" 
                                placeholder="City, Country">
                        </div>

                        <!-- Password with Toggle -->
                        <div>
                            <?php echo $__env->make('components.password-input-light', [
                                'name' => 'password',
                                'label' => 'Password',
                                'placeholder' => 'Create a strong password',
                                'required' => true
                            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>

                        <!-- Confirm Password with Toggle -->
                        <div>
                            <?php echo $__env->make('components.password-input-light', [
                                'name' => 'password_confirmation',
                                'label' => 'Confirm Password',
                                'placeholder' => 'Confirm your password',
                                'required' => true
                            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                        class="w-full bg-gradient-to-r from-emerald-600 via-green-600 to-teal-600 hover:from-emerald-700 hover:via-green-700 hover:to-teal-700 text-white py-3 sm:py-4 rounded-xl sm:rounded-2xl shadow-professional-lg hover:shadow-professional-lg card-hover transition-all font-bold text-sm sm:text-base mt-6 sm:mt-8 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        Create Account
                    </button>
                </form>

                <!-- Login Link -->
                <div class="mt-4 sm:mt-6 text-center text-xs sm:text-sm">
                    <p class="text-slate-600">
                        Already have an account? 
                        <a href="<?php echo e(route('login')); ?>" class="font-bold text-emerald-600 hover:text-emerald-700 hover:underline">Sign In</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<?php /**PATH /opt/lampp/htdocs/CBS/resources/views/auth/register.blade.php ENDPATH**/ ?>