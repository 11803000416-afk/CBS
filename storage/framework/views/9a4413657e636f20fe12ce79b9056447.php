

<?php $__env->startSection('title', 'Transactions'); ?>
<?php $__env->startSection('subtitle', 'View and manage vehicle sales transactions'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex justify-between items-center mb-6">
    <p class="text-sm text-slate-600">Total: <span class="font-semibold text-slate-800"><?php echo e($transactions->total()); ?></span> transactions</p>
    <a href="<?php echo e(route('transactions.create')); ?>" class="bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white px-5 py-2.5 rounded-lg shadow-md hover:shadow-lg transition-all flex items-center gap-2 font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Transaction
    </a>
</div>

<div class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-4 text-left font-semibold text-slate-700">Vehicle</th>
                    <th class="px-4 py-4 text-left font-semibold text-slate-700">Buyer</th>
                    <th class="px-4 py-4 text-left font-semibold text-slate-700">Seller</th>
                    <th class="px-4 py-4 text-left font-semibold text-slate-700">Sale Price</th>
                    <th class="px-4 py-4 text-left font-semibold text-slate-700">Commission</th>
                    <th class="px-4 py-4 text-left font-semibold text-slate-700">Status</th>
                    <th class="px-4 py-4 text-left font-semibold text-slate-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-4 font-semibold text-slate-800"><?php echo e($transaction->vehicle->brand ?? '-'); ?> <?php echo e($transaction->vehicle->model ?? ''); ?></td>
                        <td class="px-4 py-4 text-slate-600"><?php echo e($transaction->buyer->name ?? '-'); ?></td>
                        <td class="px-4 py-4 text-slate-600"><?php echo e($transaction->seller->name ?? '-'); ?></td>
                        <td class="px-4 py-4 font-semibold text-emerald-600">Nu. <?php echo e(number_format($transaction->sale_price, 2)); ?></td>
                        <td class="px-4 py-4 font-semibold text-blue-600">Nu. <?php echo e(number_format($transaction->broker_commission, 2)); ?></td>
                        <td class="px-4 py-4">
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold <?php echo e($transaction->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700'); ?>">
                                <?php echo e(ucfirst($transaction->status)); ?>

                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <a href="<?php echo e(route('transactions.edit', $transaction)); ?>" class="text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </a>
                                <form method="POST" action="<?php echo e(route('transactions.destroy', $transaction)); ?>" onsubmit="return confirm('Are you sure?')" class="inline">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="text-red-600 hover:text-red-800 font-medium flex items-center gap-1 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" class="px-4 py-12 text-center text-slate-400">No transaction records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="mt-6"><?php echo e($transactions->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\CBS\resources\views/transactions/index.blade.php ENDPATH**/ ?>