<?php $__env->startSection('title', 'Staff Dashboard - University Ideas System'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fas fa-tachometer-alt"></i> My Dashboard</h2>
            <p class="text-muted mb-0">Welcome back, <?php echo e(auth()->user()->name); ?>!</p>
        </div>
        <?php if($canSubmitIdea): ?>
            <a href="<?php echo e(route('ideas.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Submit Idea
            </a>
        <?php endif; ?>
    </div>

    <!-- Closure Dates Alert -->
    <?php if($ideaClosureDate || $finalClosureDate): ?>
        <div class="alert alert-info mb-4">
            <i class="fas fa-calendar-alt"></i>
            <strong>Important Dates:</strong>
            <?php if($ideaClosureDate): ?>
                Idea submission <?php echo e(now()->lt($ideaClosureDate) ? 'closes' : 'closed'); ?> on <strong><?php echo e($ideaClosureDate->format('F d, Y')); ?></strong>
            <?php endif; ?>
            <?php if($finalClosureDate): ?>
                | Commenting <?php echo e(now()->lt($finalClosureDate) ? 'closes' : 'closed'); ?> on <strong><?php echo e($finalClosureDate->format('F d, Y')); ?></strong>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- My Stats -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon primary">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <div class="stats-number"><?php echo e($stats['my_ideas']); ?></div>
                <div class="stats-label">My Ideas</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon success">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="stats-number"><?php echo e($stats['my_comments']); ?></div>
                <div class="stats-label">My Comments</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon warning">
                    <i class="fas fa-vote-yea"></i>
                </div>
                <div class="stats-number"><?php echo e($stats['my_votes']); ?></div>
                <div class="stats-label">My Votes</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon info">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="stats-number"><?php echo e($stats['total_views']); ?></div>
                <div class="stats-label">Total Views</div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- My Ideas -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-lightbulb"></i> My Recent Ideas</span>
                    <a href="<?php echo e(route('ideas.index')); ?>" class="btn btn-sm btn-outline-light">View All</a>
                </div>
                <div class="card-body">
                    <?php $__empty_1 = true; $__currentLoopData = $myIdeas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idea): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="mb-3 pb-3 <?php echo e(!$loop->last ? 'border-bottom' : ''); ?>">
                            <h6 class="mb-1">
                                <a href="<?php echo e(route('ideas.show', $idea)); ?>" class="text-decoration-none">
                                    <?php echo e(Str::limit($idea->title, 50)); ?>

                                </a>
                                <?php if($idea->is_anonymous): ?>
                                    <span class="badge badge-anonymous"><i class="fas fa-user-secret"></i></span>
                                <?php endif; ?>
                            </h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-clock"></i> <?php echo e($idea->created_at->diffForHumans()); ?>

                                </small>
                                <div>
                                    <span class="badge bg-success me-1"><i class="fas fa-thumbs-up"></i> <?php echo e($idea->thumbs_up_count); ?></span>
                                    <span class="badge bg-primary"><i class="fas fa-comment"></i> <?php echo e($idea->comments_count); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-lightbulb fa-3x mb-3"></i>
                            <p>You haven't submitted any ideas yet.</p>
                            <?php if($canSubmitIdea): ?>
                                <a href="<?php echo e(route('ideas.create')); ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus-circle"></i> Submit Your First Idea
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- My Comments -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-comments"></i> My Recent Comments
                </div>
                <div class="card-body">
                    <?php $__empty_1 = true; $__currentLoopData = $myComments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
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
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-comments fa-3x mb-3"></i>
                            <p>You haven't commented on any ideas yet.</p>
                            <a href="<?php echo e(route('ideas.index')); ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-search"></i> Browse Ideas
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-bolt"></i> Quick Actions
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3 col-sm-6">
                    <a href="<?php echo e(route('ideas.index')); ?>" class="btn btn-outline-primary w-100">
                        <i class="fas fa-search"></i> Browse Ideas
                    </a>
                </div>
                <?php if($canSubmitIdea): ?>
                    <div class="col-md-3 col-sm-6">
                        <a href="<?php echo e(route('ideas.create')); ?>" class="btn btn-outline-success w-100">
                            <i class="fas fa-plus-circle"></i> Submit Idea
                        </a>
                    </div>
                <?php endif; ?>
                <div class="col-md-3 col-sm-6">
                    <a href="<?php echo e(route('home')); ?>" class="btn btn-outline-info w-100">
                        <i class="fas fa-home"></i> Home
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="<?php echo e(route('ideas.index', ['sort' => 'popular'])); ?>" class="btn btn-outline-warning w-100">
                        <i class="fas fa-fire"></i> Popular Ideas
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\university-ideas-system\resources\views/staff/dashboard.blade.php ENDPATH**/ ?>