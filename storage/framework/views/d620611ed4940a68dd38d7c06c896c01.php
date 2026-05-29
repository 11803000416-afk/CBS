<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['vehicle', 'showViewButton' => true, 'showBrowseButton' => true]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['vehicle', 'showViewButton' => true, 'showBrowseButton' => true]); ?>
<?php foreach (array_filter((['vehicle', 'showViewButton' => true, 'showBrowseButton' => true]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div class="vehicle-card group">
    <!-- Premium Image Container with Overlay -->
    <div class="vehica-card-image-container">
        <?php if($vehicle->images && count($vehicle->images) > 0): ?>
            <img src="<?php echo e(asset('storage/' . $vehicle->images[0])); ?>" 
                alt="<?php echo e($vehicle->brand); ?> <?php echo e($vehicle->model); ?>"
                class="vehica-card-image"
                loading="lazy"
                decoding="async">
        <?php else: ?>
            <div class="vehica-card-image-placeholder">
                <svg class="w-16 h-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 16l2.879-2.879m0 0a3 3 0 104.243-4.243m-4.243 4.243L21 21m-9-8a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        <?php endif; ?>
        <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-slate-900/70 via-slate-900/20 to-transparent"></div>

        <!-- Price Badge -->
        <div class="vehica-price-badge">
            <?php echo e($vehicle->currency ?? 'Nu.'); ?><?php echo e(number_format($vehicle->price ?? 0, 0)); ?>

        </div>

        <div class="absolute left-4 top-4 flex items-center gap-2">
            <span class="premium-badge bg-white/90 text-slate-800 shadow-md backdrop-blur"><?php echo e($vehicle->year); ?></span>
        </div>
    </div>

    <!-- Vehicle Details Section -->
    <div class="vehica-card-content">
        <!-- Header: Brand and Model -->
        <div class="vehica-card-header">
            <div>
                <h3 class="vehica-card-title">
                    <?php echo e($vehicle->brand); ?> <span class="text-cyan-600"><?php echo e($vehicle->model); ?></span>
                </h3>
                <p class="vehica-card-year"><?php echo e(ucfirst($vehicle->status ?? 'available')); ?> • Listed <?php echo e(optional($vehicle->created_at)->format('M d, Y')); ?></p>
            </div>
        </div>

        <!-- Specs Row -->
        <div class="vehica-specs-row gap-3 border-0 pb-0">
            <div class="vehica-spec vehicle-card-meta-item">
                <p class="vehica-spec-label">Mileage</p>
                <p class="vehica-spec-value"><?php echo e(number_format($vehicle->mileage ?? 0)); ?> km</p>
            </div>
            <div class="vehica-spec vehicle-card-meta-item">
                <p class="vehica-spec-label">Condition</p>
                <p class="vehica-spec-value"><?php echo e($vehicle->condition ?? 'Verified'); ?></p>
            </div>
        </div>

        <!-- Action Buttons -->
        <?php if($showViewButton || $showBrowseButton): ?>
            <div class="vehica-button-group">
                <?php if($showViewButton): ?>
                    <a href="<?php echo e(route('vehicles.show', $vehicle)); ?>" class="vehica-btn vehica-btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        View Details
                    </a>
                <?php endif; ?>
                <?php if($showBrowseButton): ?>
                    <a href="<?php echo e(route('vehicles.unified')); ?>" class="vehica-btn vehica-btn-secondary">
                        <span>Browse More</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php /**PATH /opt/lampp/htdocs/CBS/resources/views/components/vehicle-card.blade.php ENDPATH**/ ?>