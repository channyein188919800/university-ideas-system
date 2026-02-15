<?php $__env->startSection('title', 'Statistics Report - University Ideas System'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fas fa-chart-bar"></i> Statistics Report</h2>
            <p class="text-muted mb-0">Department-wise idea statistics</p>
        </div>
        <a href="<?php echo e(route('qa-manager.dashboard')); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-primary"><?php echo e($totalIdeas); ?></h3>
                    <p class="text-muted mb-0">Total Ideas</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-success"><?php echo e($departmentStats->sum('contributors_count')); ?></h3>
                    <p class="text-muted mb-0">Total Contributors</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-info"><?php echo e($departmentStats->count()); ?></h3>
                    <p class="text-muted mb-0">Departments</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Department Statistics Table -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-building"></i> Department Breakdown
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Department</th>
                            <th>Ideas</th>
                            <th>Percentage</th>
                            <th>Contributors</th>
                            <th>Avg Ideas/Contributor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $departmentStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><strong><?php echo e($dept['name']); ?></strong></td>
                                <td><?php echo e($dept['ideas_count']); ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 20px;">
                                            <div class="progress-bar" 
                                                 role="progressbar" 
                                                 style="width: <?php echo e($dept['percentage']); ?>%"
                                                 aria-valuenow="<?php echo e($dept['percentage']); ?>" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
                                        <span><?php echo e($dept['percentage']); ?>%</span>
                                    </div>
                                </td>
                                <td><?php echo e($dept['contributors_count']); ?></td>
                                <td>
                                    <?php echo e($dept['contributors_count'] > 0 ? round($dept['ideas_count'] / $dept['contributors_count'], 2) : 0); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No department data available.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot class="table-group-divider">
                        <tr class="table-primary">
                            <td><strong>Total</strong></td>
                            <td><strong><?php echo e($totalIdeas); ?></strong></td>
                            <td><strong>100%</strong></td>
                            <td><strong><?php echo e($departmentStats->sum('contributors_count')); ?></strong></td>
                            <td>-</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Download Section -->
    <div class="card mt-4">
        <div class="card-header">
            <i class="fas fa-download"></i> Data Export
        </div>
        <div class="card-body">
            <p class="text-muted">Download all data after the final closure date.</p>
            <div class="d-flex gap-2">
                <a href="<?php echo e(route('qa-manager.reports.download-csv')); ?>" class="btn btn-primary">
                    <i class="fas fa-file-csv"></i> Download CSV
                </a>
                <a href="<?php echo e(route('qa-manager.reports.download-documents')); ?>" class="btn btn-success">
                    <i class="fas fa-file-archive"></i> Download Documents (ZIP)
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\university-ideas-system\resources\views/qa-manager/reports/statistics.blade.php ENDPATH**/ ?>