<?php $__env->startSection('title', 'System Settings - University Ideas System'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2><i class="fas fa-cog"></i> System Settings</h2>
                    <p class="text-muted mb-0">Configure system closure dates and academic year</p>
                </div>
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-calendar-alt"></i> Closure Dates
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>">
                        <?php echo csrf_field(); ?>
                        
                        <div class="mb-3">
                            <label for="academic_year" class="form-label">
                                <i class="fas fa-graduation-cap"></i> Academic Year <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control <?php $__errorArgs = ['academic_year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="academic_year" 
                                   name="academic_year" 
                                   value="<?php echo e(old('academic_year', $settings['academic_year'])); ?>" 
                                   required
                                   placeholder="e.g., 2024-2025">
                            <?php $__errorArgs = ['academic_year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">
                                <i class="fas fa-info-circle"></i> Format: YYYY-YYYY (e.g., 2024-2025)
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="idea_closure_date" class="form-label">
                                <i class="fas fa-clock"></i> Idea Submission Closure Date <span class="text-danger">*</span>
                            </label>
                            <input type="datetime-local" 
                                   class="form-control <?php $__errorArgs = ['idea_closure_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="idea_closure_date" 
                                   name="idea_closure_date" 
                                   value="<?php echo e(old('idea_closure_date', $settings['idea_closure_date']?->format('Y-m-d\TH:i'))); ?>" 
                                   required>
                            <?php $__errorArgs = ['idea_closure_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">
                                <i class="fas fa-info-circle"></i> After this date, staff will no longer be able to submit new ideas.
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="final_closure_date" class="form-label">
                                <i class="fas fa-calendar-times"></i> Final Closure Date <span class="text-danger">*</span>
                            </label>
                            <input type="datetime-local" 
                                   class="form-control <?php $__errorArgs = ['final_closure_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="final_closure_date" 
                                   name="final_closure_date" 
                                   value="<?php echo e(old('final_closure_date', $settings['final_closure_date']?->format('Y-m-d\TH:i'))); ?>" 
                                   required>
                            <?php $__errorArgs = ['final_closure_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">
                                <i class="fas fa-info-circle"></i> After this date, all commenting will be closed. This must be on or after the idea closure date.
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Current Status:</strong>
                            <?php
                                $ideaClosed = $settings['idea_closure_date'] && now()->gte($settings['idea_closure_date']);
                                $finalClosed = $settings['final_closure_date'] && now()->gte($settings['final_closure_date']);
                            ?>
                            <ul class="mb-0 mt-2">
                                <li>Idea Submission: <?php echo e($ideaClosed ? 'Closed' : 'Open'); ?></li>
                                <li>Commenting: <?php echo e($finalClosed ? 'Closed' : 'Open'); ?></li>
                            </ul>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Info Card -->
            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-info-circle"></i> About Closure Dates
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            <strong>Idea Closure Date:</strong> Staff can submit ideas until this date.
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            <strong>Final Closure Date:</strong> Staff can comment on ideas until this date.
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-check text-success"></i>
                            <strong>After Final Closure:</strong> QA Manager can download all data for transfer.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\university-ideas-system\resources\views/admin/settings/index.blade.php ENDPATH**/ ?>