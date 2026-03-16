

<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('subtitle', 'Car Broker System Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="glass-card rounded-xl p-6 hover:bg-white/10 transition">
        <div class="text-3xl font-bold text-white mb-1"><?php echo e($stats['vehicles'] ?? 0); ?></div>
        <div class="text-sm text-gray-400">Total Vehicles</div>
    </div>
    <div class="glass-card rounded-xl p-6 hover:bg-white/10 transition">
        <div class="text-3xl font-bold text-white mb-1"><?php echo e($stats['available_vehicles'] ?? 0); ?></div>
        <div class="text-sm text-gray-400">Available Vehicles</div>
    </div>
    <div class="glass-card rounded-xl p-6 hover:bg-white/10 transition">
        <div class="text-3xl font-bold text-white mb-1"><?php echo e($stats['sold_vehicles'] ?? 0); ?></div>
        <div class="text-sm text-gray-400">Sold Vehicles</div>
    </div>
    <div class="glass-card rounded-xl p-6 hover:bg-white/10 transition">
        <div class="text-3xl font-bold text-white mb-1"><?php echo e($stats['buyers'] ?? 0); ?></div>
        <div class="text-sm text-gray-400">Total Buyers</div>
    </div>
</div>

<!-- Quick Actions -->
<div class="glass-card rounded-xl p-6 mb-8">
    <h3 class="text-lg font-semibold text-white mb-4">Quick Actions</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <?php if(auth()->user()->hasRole(['admin','agent'])): ?>
            <a href="<?php echo e(route('vehicles.create')); ?>" class="flex flex-col items-center gap-2 p-4 rounded-lg bg-blue-500/10 hover:bg-blue-500/20 border border-blue-500/20 transition">
                <span class="text-sm font-medium text-white">Add Vehicle</span>
            </a>
        <?php else: ?>
            <a href="<?php echo e(route('my-vehicles.create')); ?>" class="flex flex-col items-center gap-2 p-4 rounded-lg bg-blue-500/10 hover:bg-blue-500/20 border border-blue-500/20 transition">
                <span class="text-sm font-medium text-white">List My Car</span>
            </a>
        <?php endif; ?>
        <a href="<?php echo e(route('inquiries.index')); ?>" class="flex flex-col items-center gap-2 p-4 rounded-lg bg-purple-500/10 hover:bg-purple-500/20 border border-purple-500/20 transition">
            <span class="text-sm font-medium text-white">Inquiries</span>
        </a>
        <?php if(auth()->user()->hasRole(['admin','agent'])): ?>
            <a href="<?php echo e(route('transactions.index')); ?>" class="flex flex-col items-center gap-2 p-4 rounded-lg bg-green-500/10 hover:bg-green-500/20 border border-green-500/20 transition">
                <span class="text-sm font-medium text-white">Transactions</span>
            </a>
            <a href="<?php echo e(route('reports.index')); ?>" class="flex flex-col items-center gap-2 p-4 rounded-lg bg-orange-500/10 hover:bg-orange-500/20 border border-orange-500/20 transition">
                <span class="text-sm font-medium text-white">Reports</span>
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- Welcome Section -->
<div class="glass-card rounded-xl p-8 text-center">
    <h2 class="text-3xl font-bold text-white mb-2">Welcome, <?php echo e(auth()->user()->name); ?>!</h2>
    <p class="text-gray-400">You are logged in as a <span class="capitalize text-blue-400 font-semibold"><?php echo e(auth()->user()->role); ?></span>.</p>
    <div class="mt-6 grid grid-cols-3 gap-4">
        <div>
            <p class="text-2xl font-bold text-white"><?php echo e($stats['vehicles'] ?? 0); ?></p>
            <p class="text-gray-400 text-sm">Vehicles</p>
        </div>
        <div>
            <p class="text-2xl font-bold text-white"><?php echo e($stats['pending_inquiries'] ?? 0); ?></p>
            <p class="text-gray-400 text-sm">Pending Inquiries</p>
        </div>
        <div>
            <p class="text-2xl font-bold text-white"><?php echo e($stats['transactions'] ?? 0); ?></p>
            <p class="text-gray-400 text-sm">Transactions</p>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\CBS\resources\views/dashboard/index.blade.php ENDPATH**/ ?>