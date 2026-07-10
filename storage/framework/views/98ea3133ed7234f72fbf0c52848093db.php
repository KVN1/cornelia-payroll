<?php $__env->startSection('page-title', 'Edit Employee'); ?>
<?php $__env->startSection('content'); ?>

<div class="flex items-center gap-3 mb-4">
    <a href="<?php echo e(route('employees.show', $employee)); ?>" class="btn btn-outline btn-sm">← Back</a>
    <div>
        <div class="section-title">Edit Employee</div>
        <div class="section-sub"><?php echo e($employee->full_name); ?> — <?php echo e($employee->employee_no); ?></div>
    </div>
</div>

<div class="card" style="max-width:760px">
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('employees.update', $employee)); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div style="font-family:'Playfair Display',serif;font-size:13px;font-weight:600;color:var(--mocha);margin-bottom:14px;padding-bottom:8px;border-bottom:1px solid var(--foam)">
                Personal Information
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Employment Type *</label>
                    <select name="employment_type" class="form-control" required>
                        <option value="full_time"   <?php echo e($employee->employment_type=='full_time'   ?'selected':''); ?>>Full Time</option>
                        <option value="part_time"   <?php echo e($employee->employment_type=='part_time'   ?'selected':''); ?>>Part Time</option>
                        <option value="contractual" <?php echo e($employee->employment_type=='contractual' ?'selected':''); ?>>Contractual</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-control" required>
                        <option value="active"     <?php echo e($employee->status=='active'     ?'selected':''); ?>>Active</option>
                        <option value="inactive"   <?php echo e($employee->status=='inactive'   ?'selected':''); ?>>Inactive</option>
                        <option value="terminated" <?php echo e($employee->status=='terminated' ?'selected':''); ?>>Terminated</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">First Name *</label>
                    <input type="text" name="first_name" class="form-control" value="<?php echo e(old('first_name', $employee->first_name)); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Last Name *</label>
                    <input type="text" name="last_name" class="form-control" value="<?php echo e(old('last_name', $employee->last_name)); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Middle Name</label>
                    <input type="text" name="middle_name" class="form-control" value="<?php echo e(old('middle_name', $employee->middle_name)); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Position *</label>
                    <select name="position_id" class="form-control" required>
                        <?php $__currentLoopData = $positions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($pos->id); ?>" <?php echo e($employee->position_id==$pos->id?'selected':''); ?>>
                            <?php echo e($pos->department->name); ?> — <?php echo e($pos->title); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo e(old('email', $employee->email)); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo e(old('phone', $employee->phone)); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Daily Rate (₱) *</label>
                    <input type="number" step="0.01" name="daily_rate" class="form-control" value="<?php echo e(old('daily_rate', $employee->daily_rate)); ?>" required>
                </div>
            </div>

            <div style="font-family:'Playfair Display',serif;font-size:13px;font-weight:600;color:var(--mocha);margin:20px 0 14px;padding-bottom:8px;border-bottom:1px solid var(--foam)">
                Government IDs
            </div>
            <div class="form-grid-3">
                <div class="form-group">
                    <label class="form-label">SSS No.</label>
                    <input type="text" name="sss_no" class="form-control" value="<?php echo e(old('sss_no', $employee->sss_no)); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">PhilHealth No.</label>
                    <input type="text" name="philhealth_no" class="form-control" value="<?php echo e(old('philhealth_no', $employee->philhealth_no)); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Pag-IBIG No.</label>
                    <input type="text" name="pagibig_no" class="form-control" value="<?php echo e(old('pagibig_no', $employee->pagibig_no)); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">TIN No.</label>
                    <input type="text" name="tin_no" class="form-control" value="<?php echo e(old('tin_no', $employee->tin_no)); ?>">
                </div>
            </div>

            <div class="flex gap-2 mt-6">
                <button type="submit" class="btn btn-primary">Update Employee</button>
                <a href="<?php echo e(route('employees.show', $employee)); ?>" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\Cornelia\resources\views/employees/edit.blade.php ENDPATH**/ ?>