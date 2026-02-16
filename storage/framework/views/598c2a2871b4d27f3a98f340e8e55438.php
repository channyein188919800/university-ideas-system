<?php $__env->startSection('title', 'Manage Categories - University Ideas System'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fas fa-tags"></i> Manage Categories</h2>
            <p class="text-muted mb-0">Create and manage idea categories</p>
        </div>
        <a href="<?php echo e(route('qa-manager.categories.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Category
        </a>
    </div>

    <!-- Categories Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Description</th>
                            <th>Ideas Count</th>
                            <th>Status</th>
                            <th>Can Delete</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><strong><?php echo e($category->name); ?></strong></td>
                                <td><code><?php echo e($category->slug); ?></code></td>
                                <td><?php echo e(Str::limit($category->description, 50)); ?></td>
                                <td>
                                    <span class="badge bg-primary"><?php echo e($category->ideas_count); ?></span>
                                </td>
                                <td>
                                    <?php if($category->is_active): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($category->canBeDeleted()): ?>
                                        <span class="badge bg-success"><i class="fas fa-check"></i> Yes</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning"><i class="fas fa-times"></i> No</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo e(route('qa-manager.categories.edit', $category)); ?>" class="btn btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if($category->canBeDeleted()): ?>
                                            <form method="POST" action="<?php echo e(route('qa-manager.categories.destroy', $category)); ?>" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No categories found.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        <?php echo e($categories->links()); ?>

    </div>
    
    <!-- Info Card -->
    <div class="card mt-4">
        <div class="card-header bg-info text-white">
            <i class="fas fa-info-circle"></i> About Categories
        </div>
        <div class="card-body">
            <ul class="list-unstyled mb-0">
                <li class="mb-2">
                    <i class="fas fa-check text-success"></i>
                    Categories help organize ideas by topic or theme.
                </li>
                <li class="mb-2">
                    <i class="fas fa-check text-success"></i>
                    Staff can select multiple categories when submitting ideas.
                </li>
                <li class="mb-0">
                    <i class="fas fa-exclamation-triangle text-warning"></i>
                    Categories that have been used cannot be deleted.
                </li>
            </ul>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\university-ideas-system\resources\views/qa-manager/categories/index.blade.php ENDPATH**/ ?>