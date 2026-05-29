<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <!-- Premium Header -->
    <div class="bg-gradient-to-r from-blue-600 via-blue-500 to-purple-600 text-white py-8 px-4 sm:px-6 lg:px-8 shadow-lg">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-4xl font-bold mb-2">System Dashboard</h1>
            <p class="text-blue-100 text-lg">Full system overview and analytics</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Key Stats Section -->
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-5 lg:gap-6 mb-8">
            <!-- Total Vehicles -->
            <div class="stat-card-modern card-blue dashboard-card-hover group">
                <div class="relative z-10 flex items-start justify-between">
                    <div>
                        <p class="text-blue-700 text-sm font-semibold mb-2 uppercase tracking-wide">Total Vehicles</p>
                        <p class="text-5xl font-bold text-blue-900 mb-2"><?php echo e($stats['vehicles']); ?></p>
                        <p class="text-blue-600 text-xs font-medium">All listings</p>
                    </div>
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Available Vehicles -->
            <div class="stat-card-modern card-emerald dashboard-card-hover group">
                <div class="relative z-10 flex items-start justify-between">
                    <div>
                        <p class="text-emerald-700 text-sm font-semibold mb-2 uppercase tracking-wide">Available</p>
                        <p class="text-5xl font-bold text-emerald-900 mb-2"><?php echo e($stats['available_vehicles']); ?></p>
                        <p class="text-emerald-600 text-xs font-medium">Ready to sell</p>
                    </div>
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Revenue (Sales Value) -->
            <div class="stat-card-modern card-purple dashboard-card-hover group">
                <div class="relative z-10 flex items-start justify-between">
                    <div>
                        <p class="text-purple-700 text-sm font-semibold mb-2 uppercase tracking-wide">Total Revenue</p>
                        <p class="text-3xl font-bold text-purple-900 mb-2">Nu.<?php echo e(number_format($stats['total_revenue'], 0)); ?></p>
                        <p class="text-purple-600 text-xs font-medium">Total sales value</p>
                    </div>
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform">
                        <span class="text-xl font-black tracking-tight text-white">Nu</span>
                    </div>
                </div>
            </div>

            <!-- Admin Profit (0.5%) -->
            <div class="stat-card-modern card-cyan dashboard-card-hover group">
                <div class="relative z-10 flex items-start justify-between">
                    <div>
                        <p class="text-cyan-700 text-sm font-semibold mb-2 uppercase tracking-wide">Admin Profit</p>
                        <p class="text-3xl font-bold text-cyan-900 mb-2">Nu.<?php echo e(number_format($stats['admin_revenue'], 2)); ?></p>
                        <p class="text-cyan-600 text-xs font-medium">0.5% of sales</p>
                    </div>
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-cyan-400 to-cyan-600 flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- System Users -->
            <div class="stat-card-modern card-cyan dashboard-card-hover group">
                <div class="relative z-10 flex items-start justify-between">
                    <div>
                        <p class="text-cyan-700 text-sm font-semibold mb-2 uppercase tracking-wide">Users</p>
                        <p class="text-5xl font-bold text-cyan-900 mb-2"><?php echo e($stats['buyers'] + $stats['sellers']); ?></p>
                        <p class="text-cyan-600 text-xs font-medium">Buyers & Sellers</p>
                    </div>
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-cyan-400 to-cyan-600 flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20H1v-2a3 3 0 015.856-1.487M13 16H9"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Secondary Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-red-500">
                <p class="text-gray-600 text-sm font-medium mb-1">Sold Vehicles</p>
                <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['sold_vehicles']); ?></p>
                <p class="text-red-600 text-xs mt-2"><?php echo e(round(($stats['sold_vehicles'] / max($stats['vehicles'], 1)) * 100, 1)); ?>% of total</p>
            </div>

            <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-blue-500">
                <p class="text-gray-600 text-sm font-medium mb-1">Completed Transactions</p>
                <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['completed_transactions']); ?></p>
                <p class="text-blue-600 text-xs mt-2"><?php echo e(round(($stats['completed_transactions'] / max($stats['total_transactions'], 1)) * 100, 1)); ?>% complete rate</p>
            </div>

            <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-amber-500">
                <p class="text-gray-600 text-sm font-medium mb-1">Pending Inquiries</p>
                <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['pending_inquiries']); ?></p>
                <p class="text-amber-600 text-xs mt-2">Awaiting response</p>
            </div>
        </div>

        <!-- Featured Vehicles Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Available Vehicles</h2>
                <a href="<?php echo e(route('vehicles.unified')); ?>" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">View All →</a>
            </div>

            <?php if($featuredVehicles->count() > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <?php $__currentLoopData = $featuredVehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if (isset($component)) { $__componentOriginale56e826e722be54fb6fce6ea617765f3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale56e826e722be54fb6fce6ea617765f3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.vehicle-card','data' => ['vehicle' => $vehicle]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('vehicle-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['vehicle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($vehicle)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale56e826e722be54fb6fce6ea617765f3)): ?>
<?php $attributes = $__attributesOriginale56e826e722be54fb6fce6ea617765f3; ?>
<?php unset($__attributesOriginale56e826e722be54fb6fce6ea617765f3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale56e826e722be54fb6fce6ea617765f3)): ?>
<?php $component = $__componentOriginale56e826e722be54fb6fce6ea617765f3; ?>
<?php unset($__componentOriginale56e826e722be54fb6fce6ea617765f3); ?>
<?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-2xl p-12 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No vehicles available</h3>
                    <p class="text-gray-600">No available vehicles to display</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Vehicles Added by Month -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                    </svg>
                    Vehicles Added (Last 6 Months)
                </h3>
                <canvas id="vehiclesChart"></canvas>
            </div>

            <!-- Revenue by Month -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                    </svg>
                    Revenue by Month (×100K)
                </h3>
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Vehicle Status & Top Sellers -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Vehicle Status Breakdown -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                    </svg>
                    Vehicle Status Distribution
                </h3>
                <canvas id="statusChart"></canvas>
            </div>

            <!-- Top Sellers -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 7H7v3h6V7z"/>
                    </svg>
                    Top Sellers
                </h3>
                <div class="space-y-3">
                    <?php $__empty_1 = true; $__currentLoopData = $topSellers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $seller): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center flex-1">
                                <div class="flex items-center justify-center w-8 h-8 bg-gradient-to-br from-blue-400 to-blue-600 text-white rounded-full font-bold text-sm mr-3">
                                    <?php echo e($index + 1); ?>

                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900"><?php echo e($seller->name); ?></p>
                                    <p class="text-xs text-gray-600"><?php echo e($seller->email); ?></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-blue-600"><?php echo e($seller->vehicles_count); ?></p>
                                <p class="text-xs text-gray-600">vehicles</p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-gray-500 text-center py-6">No sellers yet</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Vehicles Added Chart
    const vehiclesCtx = document.getElementById('vehiclesChart').getContext('2d');
    new Chart(vehiclesCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($vehiclesByMonth['labels']); ?>,
            datasets: [{
                label: 'Vehicles Added',
                data: <?php echo json_encode($vehiclesByMonth['data']); ?>,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false,
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { drawBorder: false },
                }
            }
        }
    });

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($transactionsByMonth['labels']); ?>,
            datasets: [{
                label: 'Revenue (×100K)',
                data: <?php echo json_encode($transactionsByMonth['data']); ?>,
                backgroundColor: 'rgba(139, 92, 246, 0.8)',
                borderColor: '#8b5cf6',
                borderWidth: 2,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false,
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { drawBorder: false },
                }
            }
        }
    });

    // Status Distribution Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($vehicleStatusBreakdown['labels']); ?>,
            datasets: [{
                data: <?php echo json_encode($vehicleStatusBreakdown['data']); ?>,
                backgroundColor: [
                    '#10b981',
                    '#8b5cf6',
                    '#f59e0b',
                ],
                borderColor: '#fff',
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: { size: 12, weight: 'bold' },
                    }
                }
            }
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/CBS/resources/views/dashboard/admin.blade.php ENDPATH**/ ?>