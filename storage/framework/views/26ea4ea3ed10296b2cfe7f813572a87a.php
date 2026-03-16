

<?php $__env->startSection('title', 'Inquiries'); ?>
<?php $__env->startSection('subtitle', 'Manage buyer inquiries and communications'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex justify-between items-center mb-6">
    <p class="text-sm text-slate-600">Total: <span class="font-semibold text-slate-800"><?php echo e($inquiries->total()); ?></span> inquiries</p>
    <a href="<?php echo e(route('inquiries.create')); ?>" class="bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white px-5 py-2.5 rounded-lg shadow-md hover:shadow-lg transition-all flex items-center gap-2 font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Inquiry
    </a>
</div>

<!-- Inquiry Cards -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <?php $__empty_1 = true; $__currentLoopData = $inquiries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inquiry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden hover:shadow-lg transition-all">
            <!-- Header -->
            <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-5 py-3 border-b border-slate-200 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                        <?php echo e(strtoupper(substr($inquiry->buyer->name ?? 'U', 0, 1))); ?>

                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800"><?php echo e($inquiry->buyer->name ?? '-'); ?></h3>
                        <p class="text-xs text-slate-500"><?php echo e($inquiry->created_at->diffForHumans()); ?></p>
                    </div>
                </div>
                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold
                    <?php echo e($inquiry->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : ''); ?>

                    <?php echo e($inquiry->status === 'responded' ? 'bg-blue-100 text-blue-700' : ''); ?>

                    <?php echo e($inquiry->status === 'closed' ? 'bg-slate-100 text-slate-700' : ''); ?>">
                    <?php echo e(ucfirst($inquiry->status)); ?>

                </span>
            </div>

            <!-- Body -->
            <div class="p-5 space-y-4">
                <!-- Vehicle Info -->
                <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg border border-blue-100">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <div>
                        <p class="text-xs text-blue-600 font-medium">Vehicle</p>
                        <p class="font-semibold text-slate-800"><?php echo e($inquiry->vehicle->brand ?? '-'); ?> <?php echo e($inquiry->vehicle->model ?? ''); ?></p>
                    </div>
                </div>

                <!-- Message -->
                <div>
                    <p class="text-sm text-slate-600 leading-relaxed"><?php echo e($inquiry->message); ?></p>
                </div>

                <!-- Meeting Details -->
                <?php if($inquiry->meeting_location || $inquiry->preferred_time || $inquiry->special_requirements): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-3 border-t border-slate-200">
                        <?php if($inquiry->meeting_location): ?>
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-emerald-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <div>
                                    <p class="text-xs text-slate-500">Location</p>
                                    <p class="text-sm font-semibold text-slate-700"><?php echo e($inquiry->meeting_location); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if($inquiry->preferred_time): ?>
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-xs text-slate-500">Preferred Time</p>
                                    <p class="text-sm font-semibold text-slate-700"><?php echo e($inquiry->preferred_time->format('M d, Y - h:i A')); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if($inquiry->special_requirements): ?>
                            <div class="md:col-span-2 flex items-start gap-2">
                                <svg class="w-4 h-4 text-purple-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                </svg>
                                <div>
                                    <p class="text-xs text-slate-500">Special Requirements</p>
                                    <p class="text-sm text-slate-700"><?php echo e($inquiry->special_requirements); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Response -->
                <?php if($inquiry->response): ?>
                    <div class="p-3 bg-emerald-50 rounded-lg border border-emerald-100">
                        <p class="text-xs text-emerald-600 font-semibold mb-1">Response:</p>
                        <p class="text-sm text-slate-700"><?php echo e($inquiry->response); ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Actions -->
            <div class="bg-slate-50 px-5 py-3 border-t border-slate-200 flex items-center justify-end gap-3">
                <a href="<?php echo e(route('inquiries.edit', $inquiry)); ?>" class="text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit
                </a>
                <form method="POST" action="<?php echo e(route('inquiries.destroy', $inquiry)); ?>" onsubmit="return confirm('Are you sure you want to delete this inquiry?')" class="inline">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button class="text-red-600 hover:text-red-800 font-medium flex items-center gap-1 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-span-full">
            <div class="bg-white rounded-xl shadow-md border border-slate-200 p-12 text-center">
                <svg class="w-20 h-20 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
                <p class="text-slate-400 font-medium text-lg">No inquiries found</p>
                <p class="text-slate-400 text-sm mt-1">Start by adding a new inquiry</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="mt-6"><?php echo e($inquiries->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\CBS\resources\views/inquiries/index.blade.php ENDPATH**/ ?>