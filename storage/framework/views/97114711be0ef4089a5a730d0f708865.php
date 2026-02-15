<?php $__env->startSection('title', 'All Ideas - University Ideas System'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fas fa-lightbulb"></i> All Ideas</h2>
            <p class="text-muted mb-0">Browse and discover innovative ideas from across the university</p>
        </div>
        <?php if(auth()->guard()->check()): ?>
            <?php if(auth()->user()->canSubmitIdea()): ?>
                <a href="<?php echo e(route('ideas.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> Submit Idea
                </a>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('ideas.index')); ?>" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label"><i class="fas fa-filter"></i> Category</label>
                    <select name="category" class="form-select" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>" <?php echo e(request('category') == $category->id ? 'selected' : ''); ?>>
                                <?php echo e($category->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label"><i class="fas fa-sort"></i> Sort By</label>
                    <select name="sort" class="form-select" onchange="this.form.submit()">
                        <option value="latest" <?php echo e(request('sort') == 'latest' ? 'selected' : ''); ?>>Latest</option>
                        <option value="popular" <?php echo e(request('sort') == 'popular' ? 'selected' : ''); ?>>Most Popular</option>
                        <option value="views" <?php echo e(request('sort') == 'views' ? 'selected' : ''); ?>>Most Viewed</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <a href="<?php echo e(route('ideas.index')); ?>" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-undo"></i> Reset Filters
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Ideas List -->
    <?php $__empty_1 = true; $__currentLoopData = $ideas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idea): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="idea-card">
            <div class="row">
                <div class="col-md-8">
                    <h4 class="idea-title">
                        <a href="<?php echo e(route('ideas.show', $idea)); ?>" class="text-decoration-none">
                            <?php echo e($idea->title); ?>

                        </a>
                        <?php if($idea->is_anonymous): ?>
                            <span class="badge badge-anonymous"><i class="fas fa-user-secret"></i> Anonymous</span>
                        <?php endif; ?>
                    </h4>
                    <div class="idea-meta">
                        <i class="fas fa-building"></i> <?php echo e($idea->department->name); ?>

                        <span class="mx-2">|</span>
                        <i class="fas fa-user"></i> <?php echo e($idea->author_name); ?>

                        <span class="mx-2">|</span>
                        <i class="fas fa-calendar"></i> <?php echo e($idea->created_at->format('M d, Y')); ?>

                    </div>
                    <p class="idea-description"><?php echo e(Str::limit($idea->description, 250)); ?></p>
                    <div class="mb-2">
                        <?php $__currentLoopData = $idea->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="badge-category"><?php echo e($category->name); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex flex-column align-items-end justify-content-between h-100">
                        <div class="idea-stats mb-3">
                            <span class="idea-stat text-success" title="Thumbs Up">
                                <i class="fas fa-thumbs-up"></i> <?php echo e($idea->thumbs_up_count); ?>

                            </span>
                            <span class="idea-stat text-danger" title="Thumbs Down">
                                <i class="fas fa-thumbs-down"></i> <?php echo e($idea->thumbs_down_count); ?>

                            </span>
                            <span class="idea-stat" title="Views">
                                <i class="fas fa-eye"></i> <?php echo e($idea->views_count); ?>

                            </span>
                            <span class="idea-stat" title="Comments">
                                <i class="fas fa-comment"></i> <?php echo e($idea->comments_count); ?>

                            </span>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary mb-2">
                                Popularity: <?php echo e($idea->popularity_score > 0 ? '+' : ''); ?><?php echo e($idea->popularity_score); ?>

                            </span>
                            <br>
                            <a href="<?php echo e(route('ideas.show', $idea)); ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-arrow-right"></i> View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No ideas found</h4>
                <p class="text-muted">Be the first to submit an idea!</p>
                <?php if(auth()->guard()->check()): ?>
                    <?php if(auth()->user()->canSubmitIdea()): ?>
                        <a href="<?php echo e(route('ideas.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus-circle"></i> Submit Idea
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Pagination -->
    <div class="mt-4">
        <?php echo e($ideas->withQueryString()->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\university-ideas-system\resources\views/ideas/index.blade.php ENDPATH**/ ?>