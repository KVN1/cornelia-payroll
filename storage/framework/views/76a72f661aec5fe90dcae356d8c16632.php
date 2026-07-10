<?php $__env->startSection('page-title', 'File Leave'); ?>
<?php $__env->startSection('content'); ?>

<div class="flex items-center gap-3 mb-4">
    <a href="<?php echo e(route('leaves.index')); ?>" class="btn btn-outline btn-sm">← Back</a>
    <div>
        <div class="page-title">File a Leave Request</div>
        <div class="page-sub">Submit a leave application for an employee.</div>
    </div>
</div>

<div class="card" style="max-width:520px">
    <div class="card-body">
        <form id="leave-form" method="POST" action="<?php echo e(route('leaves.store')); ?>">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label class="form-label">Employee *</label>
                <select name="employee_id" class="form-control" required>
                    <option value="">Select employee…</option>
                    <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($emp->id); ?>" <?php echo e(old('employee_id')==$emp->id?'selected':''); ?>>
                        <?php echo e($emp->full_name); ?> (<?php echo e($emp->employee_no); ?>)
                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['employee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group">
                <label class="form-label">Leave Type *</label>
                <select name="leave_type_id" class="form-control" required>
                    <option value="">Select type…</option>
                    <?php $__currentLoopData = $leaveTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($lt->id); ?>" <?php echo e(old('leave_type_id')==$lt->id?'selected':''); ?>>
                        <?php echo e($lt->name); ?> <?php echo e(!$lt->is_paid ? '(Unpaid)' : ''); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['leave_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Date From *</label>
                    <input type="date" name="date_from" class="form-control" value="<?php echo e(old('date_from')); ?>" required>
                    <?php $__errorArgs = ['date_from'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group">
                    <label class="form-label">Date To *</label>
                    <input type="date" name="date_to" class="form-control" value="<?php echo e(old('date_to')); ?>" required>
                    <?php $__errorArgs = ['date_to'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Reason</label>
                <textarea name="reason" class="form-control" rows="3"><?php echo e(old('reason')); ?></textarea>
            </div>

            
            <div id="days-preview" style="display:none;background:#faf8f5;border-radius:8px;padding:10px 14px;border:1px solid var(--border);margin-bottom:16px;font-size:13px;color:var(--text2)">
                Duration: <strong id="days-count" style="color:var(--accent)">0</strong> working day(s)
            </div>

            <div class="flex gap-2 mt-4">
                <button type="submit" id="submit-btn" class="btn btn-primary">Submit Request</button>
                <a href="<?php echo e(route('leaves.index')); ?>" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Live days preview
const dateFrom = document.querySelector('[name="date_from"]');
const dateTo   = document.querySelector('[name="date_to"]');

function updateDays() {
    if (!dateFrom.value || !dateTo.value) {
        document.getElementById('days-preview').style.display = 'none';
        return;
    }
    const from = new Date(dateFrom.value);
    const to   = new Date(dateTo.value);
    if (to < from) { document.getElementById('days-preview').style.display = 'none'; return; }

    let days = 0;
    const cur = new Date(from);
    while (cur <= to) {
        const dow = cur.getDay();
        if (dow !== 0 && dow !== 6) days++;
        cur.setDate(cur.getDate() + 1);
    }
    document.getElementById('days-count').textContent = days;
    document.getElementById('days-preview').style.display = 'block';
}

dateFrom.addEventListener('change', updateDays);
dateTo.addEventListener('change', updateDays);

// Force full page submit so redirect works properly
document.getElementById('leave-form').addEventListener('submit', function() {
    document.getElementById('submit-btn').disabled = true;
    document.getElementById('submit-btn').textContent = 'Submitting…';
    // Use full page navigation instead of AJAX
    this.submit();
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\Cornelia\resources\views/leaves/create.blade.php ENDPATH**/ ?>