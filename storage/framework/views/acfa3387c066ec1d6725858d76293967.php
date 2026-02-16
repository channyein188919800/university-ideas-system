<?php $__env->startSection('title', 'Exception Reports - University Ideas System'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fas fa-exclamation-triangle"></i> Exception Reports</h2>
            <p class="text-muted mb-0">Identify ideas and comments that need attention</p>
        </div>
        <a href="<?php echo e(route('qa-manager.dashboard')); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- Ideas Without Comments -->
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">
            <i class="fas fa-comment-slash"></i> Ideas Without Comments
            <span class="badge bg-dark ms-2"><?php echo e($ideasWithoutComments->count()); ?></span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Department</th>
                            <th>Author</th>
                            <th>Submitted</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $ideasWithoutComments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idea): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e(Str::limit($idea->title, 50)); ?></td>
                                <td><?php echo e($idea->department->name); ?></td>
                                <td>
                                    <?php if($idea->is_anonymous): ?>
                                        <span class="badge badge-anonymous"><i class="fas fa-user-secret"></i> Anonymous</span>
                                    <?php else: ?>
                                        <?php echo e($idea->user->name); ?>

                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($idea->created_at->diffForHumans()); ?></td>
                                <td>
                                    <a href="<?php echo e(route('ideas.show', $idea)); ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                    <p class="text-muted mb-0">All ideas have comments!</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Anonymous Ideas -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <i class="fas fa-user-secret"></i> Anonymous Ideas
            <span class="badge bg-dark ms-2"><?php echo e($anonymousIdeas->count()); ?></span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Department</th>
                            <th>Submitted</th>
                            <th>Comments</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $anonymousIdeas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idea): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e(Str::limit($idea->title, 50)); ?></td>
                                <td><?php echo e($idea->department->name); ?></td>
                                <td><?php echo e($idea->created_at->diffForHumans()); ?></td>
                                <td><?php echo e($idea->comments_count); ?></td>
                                <td>
                                    <a href="<?php echo e(route('ideas.show', $idea)); ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No anonymous ideas.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Anonymous Comments -->
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <i class="fas fa-user-secret"></i> Anonymous Comments
            <span class="badge bg-dark ms-2"><?php echo e($anonymousComments->count()); ?></span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Idea</th>
                            <th>Comment</th>
                            <th>Submitted</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $anonymousComments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e(Str::limit($comment->idea->title, 40)); ?></td>
                                <td><?php echo e(Str::limit($comment->content, 60)); ?></td>
                                <td><?php echo e($comment->created_at->diffForHumans()); ?></td>
                                <td>
                                    <a href="<?php echo e(route('ideas.show', $comment->idea)); ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No anonymous comments.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\university-ideas-system\resources\views/qa-manager/reports/exceptions.blade.php ENDPATH**/ ?>