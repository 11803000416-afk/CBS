<?php $__env->startSection('title', 'Dealer License Approval'); ?>
<?php $__env->startSection('subtitle', 'Submit your dealer license for admin approval'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto space-y-6">
    <?php if(session('success')): ?>
        <div class="rounded-xl border border-green-200 bg-green-50 p-4" role="status" aria-live="polite">
            <p class="text-sm font-semibold text-green-800"><?php echo e(session('success')); ?></p>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="rounded-xl border border-red-200 bg-red-50 p-4" role="alert" aria-live="assertive">
            <p class="text-sm font-semibold text-red-800"><?php echo e(session('error')); ?></p>
        </div>
    <?php endif; ?>

    <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <header class="border-b border-slate-200 bg-gradient-to-r from-cyan-50 to-blue-50 px-6 py-4">
            <h2 class="text-lg font-bold text-slate-900">Broker License Status</h2>
        </header>

        <div class="space-y-5 p-6">
            <div class="flex flex-wrap items-center gap-3">
                <span class="text-sm font-medium text-slate-600">Current status:</span>
                <span class="badge <?php echo e($user->dealer_license_status === 'approved' ? 'badge-success' : ($user->dealer_license_status === 'pending' ? 'badge-warning' : ($user->dealer_license_status === 'rejected' ? 'badge-danger' : 'badge-primary'))); ?>">
                    <?php echo e(ucfirst(str_replace('_', ' ', $user->dealer_license_status ?? 'not_submitted'))); ?>

                </span>
            </div>

            <?php if($user->dealer_license_admin_notes): ?>
                <div class="rounded-lg border border-amber-200 bg-amber-50 p-4" role="note">
                    <p class="text-xs font-bold uppercase tracking-wide text-amber-700">Admin Notes</p>
                    <p class="mt-1 text-sm text-amber-900"><?php echo e($user->dealer_license_admin_notes); ?></p>
                </div>
            <?php endif; ?>

            <p class="text-sm text-slate-600" id="broker-license-help">
                Until your dealer license is approved, broker deal actions for vehicles and transactions remain restricted. Buyers and sellers can still interact with listings and inquiries.
            </p>

            <form action="<?php echo e(route('broker.license.submit')); ?>" method="POST" enctype="multipart/form-data" class="space-y-4" aria-describedby="broker-license-help">
                <?php echo csrf_field(); ?>
                <div>
                    <label for="dealer_license_number" class="label-light">Dealer License Number <span class="text-red-600">*</span></label>
                    <input
                        id="dealer_license_number"
                        name="dealer_license_number"
                        type="text"
                        value="<?php echo e(old('dealer_license_number', $user->dealer_license_number)); ?>"
                        class="input-field-light"
                        required
                        aria-required="true"
                        maxlength="100"
                    >
                    <?php $__errorArgs = ['dealer_license_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600" role="alert"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="dealer_license_document" class="label-light">Dealer License Document (PDF/JPG/PNG)</label>
                    <input
                        id="dealer_license_document"
                        name="dealer_license_document"
                        type="file"
                        class="input-field-light"
                        accept=".pdf,.jpg,.jpeg,.png"
                    >
                    <?php if($user->dealer_license_document_path): ?>
                        <p class="mt-2 text-xs text-slate-500">Current document uploaded and stored.</p>
                    <?php endif; ?>
                    <?php $__errorArgs = ['dealer_license_document'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600" role="alert"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <button type="submit" class="btn btn-primary">
                        Submit For Approval
                    </button>
                    <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-secondary">Back to Dashboard</a>
                </div>
            </form>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/CBS/resources/views/broker/license.blade.php ENDPATH**/ ?>