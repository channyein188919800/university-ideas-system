<?php $__env->startSection('title', 'Home - University Ideas System'); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<section class="hero">
    <div class="container text-center">
        <h1><i class="fas fa-lightbulb"></i> Share Your Ideas</h1>
        <p>Help improve our university by submitting your innovative ideas and feedback.</p>
        <?php if(auth()->guard()->check()): ?>
            <?php if(auth()->user()->canSubmitIdea()): ?>
                <a href="<?php echo e(route('ideas.create')); ?>" class="btn btn-accent btn-lg">
                    <i class="fas fa-plus-circle"></i> Submit an Idea
                </a>
            <?php else: ?>
                <div class="alert alert-warning d-inline-block">
                    <i class="fas fa-clock"></i> Idea submission is currently closed.
                </div>
            <?php endif; ?>
        <?php else: ?>
            <a href="<?php echo e(route('login')); ?>" class="btn btn-accent btn-lg">
                <i class="fas fa-sign-in-alt"></i> Login to Submit Ideas
            </a>
        <?php endif; ?>
    </div>
</section>

<div class="container mb-5">
    <!-- Closure Dates Alert -->
    <?php if($ideaClosureDate || $finalClosureDate): ?>
        <div class="alert alert-info mb-4">
            <i class="fas fa-calendar-alt"></i>
            <strong>Important Dates:</strong>
            <?php if($ideaClosureDate): ?>
                Idea submission closes on <strong><?php echo e($ideaClosureDate->format('F d, Y')); ?></strong>
            <?php endif; ?>
            <?php if($finalClosureDate): ?>
                | Final closure (comments) on <strong><?php echo e($finalClosureDate->format('F d, Y')); ?></strong>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Statistics Row -->
    <div class="row mb-5">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon primary">
                    <i class="fas fa-fire"></i>
                </div>
                <div class="stats-number"><?php echo e($popularIdeas->count()); ?></div>
                <div class="stats-label">Popular Ideas</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon success">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="stats-number"><?php echo e($mostViewedIdeas->count()); ?></div>
                <div class="stats-label">Most Viewed</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stats-number"><?php echo e($latestIdeas->count()); ?></div>
                <div class="stats-label">Latest Ideas</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon info">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="stats-number"><?php echo e($latestComments->count()); ?></div>
                <div class="stats-label">Recent Comments</div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Popular Ideas -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-fire"></i> Most Popular Ideas
                </div>
                <div class="card-body">
                    <?php $__empty_1 = true; $__currentLoopData = $popularIdeas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idea): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="idea-card mb-3">
                            <h5 class="idea-title">
                                <a href="<?php echo e(route('ideas.show', $idea)); ?>" class="text-decoration-none">
                                    <?php echo e($idea->title); ?>

                                </a>
                                <?php if($idea->is_anonymous): ?>
                                    <span class="badge badge-anonymous"><i class="fas fa-user-secret"></i> Anonymous</span>
                                <?php endif; ?>
                            </h5>
                            <div class="idea-meta">
                                <i class="fas fa-building"></i> <?php echo e($idea->department->name); ?>

                                <span class="mx-2">|</span>
                                <i class="fas fa-calendar"></i> <?php echo e($idea->created_at->diffForHumans()); ?>

                            </div>
                            <p class="idea-description"><?php echo e(Str::limit($idea->description, 150)); ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="idea-stats">
                                    <span class="idea-stat text-success">
                                        <i class="fas fa-thumbs-up"></i> <?php echo e($idea->thumbs_up_count); ?>

                                    </span>
                                    <span class="idea-stat text-danger">
                                        <i class="fas fa-thumbs-down"></i> <?php echo e($idea->thumbs_down_count); ?>

                                    </span>
                                    <span class="idea-stat">
                                        <i class="fas fa-eye"></i> <?php echo e($idea->views_count); ?>

                                    </span>
                                    <span class="idea-stat">
                                        <i class="fas fa-comment"></i> <?php echo e($idea->comments_count); ?>

                                    </span>
                                </div>
                                <span class="badge bg-primary">
                                    Score: <?php echo e($idea->popularity_score); ?>

                                </span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>No popular ideas yet.</p>
                        </div>
                    <?php endif; ?>
                    <div class="text-center mt-3">
                        <a href="<?php echo e(route('ideas.index', ['sort' => 'popular'])); ?>" class="btn btn-outline-primary btn-sm">
                            View All Popular <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Most Viewed Ideas -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-eye"></i> Most Viewed Ideas
                </div>
                <div class="card-body">
                    <?php $__empty_1 = true; $__currentLoopData = $mostViewedIdeas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idea): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="idea-card mb-3">
                            <h5 class="idea-title">
                                <a href="<?php echo e(route('ideas.show', $idea)); ?>" class="text-decoration-none">
                                    <?php echo e($idea->title); ?>

                                </a>
                                <?php if($idea->is_anonymous): ?>
                                    <span class="badge badge-anonymous"><i class="fas fa-user-secret"></i> Anonymous</span>
                                <?php endif; ?>
                            </h5>
                            <div class="idea-meta">
                                <i class="fas fa-building"></i> <?php echo e($idea->department->name); ?>

                                <span class="mx-2">|</span>
                                <i class="fas fa-calendar"></i> <?php echo e($idea->created_at->diffForHumans()); ?>

                            </div>
                            <p class="idea-description"><?php echo e(Str::limit($idea->description, 150)); ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="idea-stats">
                                    <span class="idea-stat">
                                        <i class="fas fa-eye"></i> <?php echo e($idea->views_count); ?> views
                                    </span>
                                    <span class="idea-stat">
                                        <i class="fas fa-comment"></i> <?php echo e($idea->comments_count); ?>

                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>No viewed ideas yet.</p>
                        </div>
                    <?php endif; ?>
                    <div class="text-center mt-3">
                        <a href="<?php echo e(route('ideas.index', ['sort' => 'views'])); ?>" class="btn btn-outline-primary btn-sm">
                            View All <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Latest Ideas -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-clock"></i> Latest Ideas
                </div>
                <div class="card-body">
                    <?php $__empty_1 = true; $__currentLoopData = $latestIdeas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idea): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="idea-card mb-3">
                            <h5 class="idea-title">
                                <a href="<?php echo e(route('ideas.show', $idea)); ?>" class="text-decoration-none">
                                    <?php echo e($idea->title); ?>

                                </a>
                                <?php if($idea->is_anonymous): ?>
                                    <span class="badge badge-anonymous"><i class="fas fa-user-secret"></i> Anonymous</span>
                                <?php endif; ?>
                            </h5>
                            <div class="idea-meta">
                                <i class="fas fa-building"></i> <?php echo e($idea->department->name); ?>

                                <span class="mx-2">|</span>
                                <i class="fas fa-calendar"></i> <?php echo e($idea->created_at->diffForHumans()); ?>

                            </div>
                            <p class="idea-description"><?php echo e(Str::limit($idea->description, 150)); ?></p>
                            <div class="idea-stats">
                                <span class="idea-stat text-success">
                                    <i class="fas fa-thumbs-up"></i> <?php echo e($idea->thumbs_up_count); ?>

                                </span>
                                <span class="idea-stat text-danger">
                                    <i class="fas fa-thumbs-down"></i> <?php echo e($idea->thumbs_down_count); ?>

                                </span>
                                <span class="idea-stat">
                                    <i class="fas fa-comment"></i> <?php echo e($idea->comments_count); ?>

                                </span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>No ideas submitted yet.</p>
                        </div>
                    <?php endif; ?>
                    <div class="text-center mt-3">
                        <a href="<?php echo e(route('ideas.index')); ?>" class="btn btn-outline-primary btn-sm">
                            View All Ideas <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Comments -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-comments"></i> Latest Comments
                </div>
                <div class="card-body">
                    <?php $__empty_1 = true; $__currentLoopData = $latestComments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="idea-card mb-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="mb-0">
                                    <i class="fas fa-comment text-primary"></i>
                                    On: <a href="<?php echo e(route('ideas.show', $comment->idea)); ?>" class="text-decoration-none"><?php echo e($comment->idea->title); ?></a>
                                </h6>
                                <?php if($comment->is_anonymous): ?>
                                    <span class="badge badge-anonymous"><i class="fas fa-user-secret"></i> Anonymous</span>
                                <?php endif; ?>
                            </div>
                            <p class="idea-description mb-2"><?php echo e(Str::limit($comment->content, 150)); ?></p>
                            <small class="text-muted">
                                <i class="fas fa-clock"></i> <?php echo e($comment->created_at->diffForHumans()); ?>

                            </small>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>No comments yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-tags"></i> Browse by Category
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <a href="<?php echo e(route('ideas.index', ['category' => $category->id])); ?>" class="text-decoration-none">
                                <span class="badge-category">
                                    <?php echo e($category->name); ?>

                                    <span class="badge bg-secondary ms-1"><?php echo e($category->ideas_count); ?></span>
                                </span>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p class="text-muted mb-0">No categories available.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\university-ideas-system\resources\views/home.blade.php ENDPATH**/ ?>