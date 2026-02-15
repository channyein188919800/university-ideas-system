<?php $__env->startSection('title', 'QA Manager Dashboard - University Ideas System'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fas fa-chart-line"></i> QA Manager Dashboard</h2>
            <p class="text-muted mb-0">Quality assurance oversight and reporting</p>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon primary">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <div class="stats-number"><?php echo e($stats['total_ideas']); ?></div>
                <div class="stats-label">Total Ideas</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon success">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="stats-number"><?php echo e($stats['total_comments']); ?></div>
                <div class="stats-label">Total Comments</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon warning">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stats-number"><?php echo e($stats['total_users']); ?></div>
                <div class="stats-label">Total Users</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon info">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stats-number"><?php echo e($stats['total_departments']); ?></div>
                <div class="stats-label">Departments</div>
            </div>
        </div>
    </div>

    <!-- Closure Dates -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-calendar-alt"></i> System Status
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Idea Submission</h6>
                            <p class="mb-0">
                                <?php if($ideaClosureDate): ?>
                                    <?php if(now()->lt($ideaClosureDate)): ?>
                                        <span class="badge bg-success">Open</span>
                                        <small class="text-muted">Closes <?php echo e($ideaClosureDate->diffForHumans()); ?></small>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Closed</span>
                                        <small class="text-muted">Closed on <?php echo e($ideaClosureDate->format('M d, Y')); ?></small>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Not Set</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6>Commenting</h6>
                            <p class="mb-0">
                                <?php if($finalClosureDate): ?>
                                    <?php if(now()->lt($finalClosureDate)): ?>
                                        <span class="badge bg-success">Open</span>
                                        <small class="text-muted">Closes <?php echo e($finalClosureDate->diffForHumans()); ?></small>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Closed</span>
                                        <small class="text-muted">Closed on <?php echo e($finalClosureDate->format('M d, Y')); ?></small>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Not Set</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-bolt"></i> Quick Actions
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3 col-sm-6">
                            <a href="<?php echo e(route('qa-manager.categories.index')); ?>" class="btn btn-outline-primary w-100">
                                <i class="fas fa-tags"></i> Manage Categories
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="<?php echo e(route('qa-manager.reports.statistics')); ?>" class="btn btn-outline-success w-100">
                                <i class="fas fa-chart-bar"></i> Statistics
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="<?php echo e(route('qa-manager.reports.exceptions')); ?>" class="btn btn-outline-warning w-100">
                                <i class="fas fa-exclamation-triangle"></i> Exception Reports
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="<?php echo e(route('ideas.index')); ?>" class="btn btn-outline-info w-100">
                                <i class="fas fa-lightbulb"></i> View All Ideas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Department Statistics -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-building"></i> Department Statistics
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
                                    <th>Progress</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $departmentStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><strong><?php echo e($dept->name); ?></strong></td>
                                        <td><?php echo e($dept->ideas_count); ?></td>
                                        <td><?php echo e($dept->percentage); ?>%</td>
                                        <td><?php echo e($dept->contributors_count); ?></td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar" 
                                                     role="progressbar" 
                                                     style="width: <?php echo e($dept->percentage); ?>%"
                                                     aria-valuenow="<?php echo e($dept->percentage); ?>" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    <?php echo e($dept->percentage); ?>%
                                                </div>
                                            </div>
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
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\university-ideas-system\resources\views/qa-manager/dashboard.blade.php ENDPATH**/ ?>