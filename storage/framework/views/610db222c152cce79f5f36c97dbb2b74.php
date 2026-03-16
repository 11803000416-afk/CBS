<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Carle'); ?> - Car Trading Platform</title>
    <link rel="icon" type="image/svg+xml" href="<?php echo e(asset('favicon.svg')); ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
        
        /* Aurora Background Effect */
        .aurora-bg {
            background: linear-gradient(180deg, #1a0f3e 0%, #2d1b69 50%, #1a0f3e 100%);
            position: relative;
            overflow: hidden;
        }
        
        .aurora-bg::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 30% 20%, rgba(147, 51, 234, 0.3) 0%, transparent 50%),
                        radial-gradient(circle at 70% 40%, rgba(59, 130, 246, 0.3) 0%, transparent 50%),
                        radial-gradient(circle at 50% 60%, rgba(168, 85, 247, 0.2) 0%, transparent 50%);
            animation: aurora-move 20s ease-in-out infinite;
        }
        
        @keyframes aurora-move {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(5%, -5%) rotate(120deg); }
            66% { transform: translate(-5%, 5%) rotate(240deg); }
        }
        
        /* Gradient text effect */
        .gradient-text {
            background: linear-gradient(90deg, #60a5fa 0%, #a78bfa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Glass morphism */
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Smooth transitions */
        * {
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="aurora-bg min-h-screen text-white">
    <div class="min-h-screen">
        <!-- Top Navigation -->
        <nav class="glass-card sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-6 py-4">
                <div class="flex items-center justify-between">
                    <!-- Logo/Brand -->
                    <div class="flex items-center gap-8">
                        <a href="<?php echo e(route('dashboard')); ?>" class="text-2xl font-bold text-white hover:text-blue-400 transition">
                            Carle
                        </a>
                        <!-- Main Navigation Links -->
                        <div class="hidden lg:flex items-center gap-6">
                            <a href="<?php echo e(route('dashboard')); ?>" class="text-sm font-medium <?php echo e(request()->routeIs('dashboard') ? 'text-blue-400' : 'text-gray-300 hover:text-white'); ?>">
                                Home
                            </a>
                            <?php if(auth()->user()->hasRole(['admin','agent'])): ?>
                                <a href="<?php echo e(route('vehicles.index')); ?>" class="text-sm font-medium <?php echo e(request()->routeIs('vehicles.*') ? 'text-blue-400' : 'text-gray-300 hover:text-white'); ?>">
                                    Vehicles
                                </a>
                                <a href="<?php echo e(route('buyers.index')); ?>" class="text-sm font-medium <?php echo e(request()->routeIs('buyers.*') ? 'text-blue-400' : 'text-gray-300 hover:text-white'); ?>">
                                    Buyers
                                </a>
                                <a href="<?php echo e(route('sellers.index')); ?>" class="text-sm font-medium <?php echo e(request()->routeIs('sellers.*') ? 'text-blue-400' : 'text-gray-300 hover:text-white'); ?>">
                                    Sellers
                                </a>
                                <a href="<?php echo e(route('transactions.index')); ?>" class="text-sm font-medium <?php echo e(request()->routeIs('transactions.*') ? 'text-blue-400' : 'text-gray-300 hover:text-white'); ?>">
                                    Transactions
                                </a>
                                <a href="<?php echo e(route('reports.index')); ?>" class="text-sm font-medium <?php echo e(request()->routeIs('reports.*') ? 'text-blue-400' : 'text-gray-300 hover:text-white'); ?>">
                                    Reports
                                </a>
                            <?php else: ?>
                                <a href="<?php echo e(route('my-vehicles.index')); ?>" class="text-sm font-medium <?php echo e(request()->routeIs('my-vehicles.*') ? 'text-blue-400' : 'text-gray-300 hover:text-white'); ?>">
                                    My Listings
                                </a>
                            <?php endif; ?>
                            <a href="<?php echo e(route('inquiries.index')); ?>" class="text-sm font-medium <?php echo e(request()->routeIs('inquiries.*') ? 'text-blue-400' : 'text-gray-300 hover:text-white'); ?>">
                                Inquiries
                            </a>
                        </div>
                    </div>
                    
                    <!-- Right Side: Date/Time & User -->
                    <div class="flex items-center gap-6">
                        <!-- Live Date/Time -->
                        <div class="hidden md:block text-right">
                            <p class="text-xs text-gray-400" id="currentDate">Loading...</p>
                            <p class="text-sm font-semibold text-white" id="currentTime">--:--:--</p>
                        </div>
                        
                        <!-- User Info -->
                        <div class="flex items-center gap-4">
                            <div class="hidden xl:block text-right">
                                <p class="text-sm font-semibold text-white"><?php echo e(auth()->user()->name); ?></p>
                                <p class="text-xs text-gray-400 capitalize"><?php echo e(auth()->user()->role); ?></p>
                            </div>
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                                <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                            </div>
                            <form action="<?php echo e(route('logout')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button class="px-4 py-2 rounded-lg border border-white/20 hover:bg-white/10 text-white text-sm font-medium transition">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-6 py-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white mb-2"><?php echo $__env->yieldContent('title', 'Dashboard'); ?></h1>
                <p class="text-gray-400"><?php echo $__env->yieldContent('subtitle', 'Manage your car trading operations'); ?></p>
            </div>
            
            <!-- Flash Messages -->
            <?php if(session('success')): ?>
                <div class="mb-6 p-4 rounded-lg bg-green-500/20 border border-green-500/30 text-green-300 flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="font-medium"><?php echo e(session('success')); ?></span>
                </div>
            <?php endif; ?>
            <?php if($errors->any()): ?>
                <div class="mb-6 p-4 rounded-lg bg-red-500/20 border border-red-500/30 text-red-300">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <ul class="space-y-1">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="font-medium"><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
    
    <!-- Live Date/Time Script -->
    <script>
        function updateDateTime() {
            const now = new Date();
            
            // Format date: Wednesday, September 28, 2022
            const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const dateStr = now.toLocaleDateString('en-US', dateOptions);
            
            // Format time: 3:50:36 PM
            const timeOptions = { hour: 'numeric', minute: '2-digit', second: '2-digit', hour12: true };
            const timeStr = now.toLocaleTimeString('en-US', timeOptions);
            
            const dateEl = document.getElementById('currentDate');
            const timeEl = document.getElementById('currentTime');
            
            if (dateEl) dateEl.textContent = dateStr;
            if (timeEl) timeEl.textContent = timeStr;
        }
        
        // Update immediately and then every second
        updateDateTime();
        setInterval(updateDateTime, 1000);
    </script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\CBS\resources\views/layouts/app.blade.php ENDPATH**/ ?>