<?php $__env->startSection('title', 'Admin Dashboard - University Ideas System'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fas fa-cog"></i> Admin Dashboard</h2>
            <p class="text-muted mb-0">System administration and management</p>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stats-number"><?php echo e($stats['total_users']); ?></div>
                <div class="stats-label">Total Users</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon success">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <div class="stats-number"><?php echo e($stats['total_ideas']); ?></div>
                <div class="stats-label">Total Ideas</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon warning">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="stats-number"><?php echo e($stats['total_comments']); ?></div>
                <div class="stats-label">Total Comments</div>
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
                    <i class="fas fa-calendar-alt"></i> System Settings
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Idea Closure Date</h6>
                            <p class="mb-0">
                                <?php if($ideaClosureDate): ?>
                                    <span class="badge <?php echo e(now()->lt($ideaClosureDate) ? 'bg-success' : 'bg-danger'); ?>">
                                        <?php echo e($ideaClosureDate->format('F d, Y H:i')); ?>

                                    </span>
                                    <?php if(now()->lt($ideaClosureDate)): ?>
                                        <small class="text-muted">(Open for <?php echo e(now()->diffForHumans($ideaClosureDate, false)); ?>)</small>
                                    <?php else: ?>
                                        <small class="text-muted">(Closed)</small>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Not Set</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6>Final Closure Date</h6>
                            <p class="mb-0">
                                <?php if($finalClosureDate): ?>
                                    <span class="badge <?php echo e(now()->lt($finalClosureDate) ? 'bg-success' : 'bg-danger'); ?>">
                                        <?php echo e($finalClosureDate->format('F d, Y H:i')); ?>

                                    </span>
                                    <?php if(now()->lt($finalClosureDate)): ?>
                                        <small class="text-muted">(Comments open for <?php echo e(now()->diffForHumans($finalClosureDate, false)); ?>)</small>
                                    <?php else: ?>
                                        <small class="text-muted">(Closed)</small>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Not Set</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="<?php echo e(route('admin.settings.index')); ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit Settings
                        </a>
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
                            <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-outline-primary w-100">
                                <i class="fas fa-users"></i> Manage Users
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="<?php echo e(route('admin.departments.index')); ?>" class="btn btn-outline-success w-100">
                                <i class="fas fa-building"></i> Manage Departments
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="<?php echo e(route('admin.settings.index')); ?>" class="btn btn-outline-warning w-100">
                                <i class="fas fa-cog"></i> System Settings
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

    <div class="row">
        <!-- Recent Ideas -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-lightbulb"></i> Recent Ideas
                </div>
                <div class="card-body">
                    <?php $__empty_1 = true; $__currentLoopData = $recentIdeas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idea): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 <?php echo e(!$loop->last ? 'border-bottom' : ''); ?>">
                            <div>
                                <h6 class="mb-1">
                                    <a href="<?php echo e(route('ideas.show', $idea)); ?>" class="text-decoration-none">
                                        <?php echo e(Str::limit($idea->title, 50)); ?>

                                    </a>
                                </h6>
                                <small class="text-muted">
                                    <i class="fas fa-building"></i> <?php echo e($idea->department->name); ?>

                                    <span class="mx-1">|</span>
                                    <i class="fas fa-clock"></i> <?php echo e($idea->created_at->diffForHumans()); ?>

                                </small>
                            </div>
                            <span class="badge bg-primary">
                                <?php echo e($idea->popularity_score > 0 ? '+' : ''); ?><?php echo e($idea->popularity_score); ?>

                            </span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted mb-0">No ideas submitted yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Comments -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-comments"></i> Recent Comments
                </div>
                <div class="card-body">
                    <?php $__empty_1 = true; $__currentLoopData = $recentComments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="mb-3 pb-3 <?php echo e(!$loop->last ? 'border-bottom' : ''); ?>">
                            <h6 class="mb-1">
                                <i class="fas fa-comment text-primary"></i>
                                On: <a href="<?php echo e(route('ideas.show', $comment->idea)); ?>" class="text-decoration-none">
                                    <?php echo e(Str::limit($comment->idea->title, 40)); ?>

                                </a>
                            </h6>
                            <p class="mb-1 small"><?php echo e(Str::limit($comment->content, 80)); ?></p>
                            <small class="text-muted">
                                <i class="fas fa-clock"></i> <?php echo e($comment->created_at->diffForHumans()); ?>

                            </small>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted mb-0">No comments yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\university-ideas-system\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>