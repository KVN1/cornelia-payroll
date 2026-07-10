<?php $__env->startSection('page-title', 'Employee Profile'); ?>
<?php $__env->startSection('content'); ?>

<div class="flex items-center gap-3 mb-4">
    <a href="<?php echo e(route('employees.index')); ?>" class="btn btn-outline btn-sm">← Back</a>
    <div>
        <div class="section-title"><?php echo e($employee->full_name); ?></div>
        <div class="section-sub"><?php echo e($employee->employee_no); ?> · <?php echo e($employee->position->title); ?> · <?php echo e($employee->position->department->name); ?></div>
    </div>
    <a href="<?php echo e(route('employees.edit', $employee)); ?>" class="btn btn-caramel" style="margin-left:auto">Edit</a>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
    <div class="card">
        <div class="card-header"><span class="card-title">Personal Details</span></div>
        <div class="card-body">
            <table style="width:100%;font-size:13.5px">
                <tr><td style="color:#999;padding:6px 0;width:140px">Full Name</td><td><strong><?php echo e($employee->full_name); ?></strong></td></tr>
                <tr><td style="color:#999;padding:6px 0">Email</td><td><?php echo e($employee->email ?? '—'); ?></td></tr>
                <tr><td style="color:#999;padding:6px 0">Phone</td><td><?php echo e($employee->phone ?? '—'); ?></td></tr>
                <tr><td style="color:#999;padding:6px 0">Type</td><td><?php echo e(str_replace('_',' ', ucfirst($employee->employment_type))); ?></td></tr>
                <tr><td style="color:#999;padding:6px 0">Hire Date</td><td><?php echo e($employee->hire_date->format('F d, Y')); ?></td></tr>
                <tr><td style="color:#999;padding:6px 0">Daily Rate</td><td><strong>₱<?php echo e(number_format($employee->daily_rate,2)); ?></strong></td></tr>
                <tr><td style="color:#999;padding:6px 0">Status</td><td><span class="badge badge-<?php echo e($employee->status); ?>"><?php echo e(ucfirst($employee->status)); ?></span></td></tr>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><span class="card-title">Government IDs</span></div>
        <div class="card-body">
            <table style="width:100%;font-size:13.5px">
                <tr><td style="color:#999;padding:6px 0;width:140px">SSS No.</td><td><?php echo e($employee->sss_no ?? '—'); ?></td></tr>
                <tr><td style="color:#999;padding:6px 0">PhilHealth No.</td><td><?php echo e($employee->philhealth_no ?? '—'); ?></td></tr>
                <tr><td style="color:#999;padding:6px 0">Pag-IBIG No.</td><td><?php echo e($employee->pagibig_no ?? '—'); ?></td></tr>
                <tr><td style="color:#999;padding:6px 0">TIN No.</td><td><?php echo e($employee->tin_no ?? '—'); ?></td></tr>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><span class="card-title">Recent Time Logs</span></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Date</th><th>Time In</th><th>Time Out</th><th>Hours</th><th>Status</th></tr></thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $employee->timeLogs->take(7); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($log->log_date->format('M d')); ?></td>
                        <td><?php echo e($log->time_in  ? $log->time_in->format('h:i A')  : '—'); ?></td>
                        <td><?php echo e($log->time_out ? $log->time_out->format('h:i A') : '—'); ?></td>
                        <td><?php echo e($log->total_hours_worked > 0 ? $log->total_hours_worked.'h' : '—'); ?></td>
                        <td>
                            <?php if($log->is_late): ?>
                                <span class="badge badge-rejected">Late <?php echo e($log->late_minutes); ?>m</span>
                            <?php else: ?>
                                <span class="badge badge-active">On time</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" class="text-muted" style="text-align:center;padding:20px">No logs yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><span class="card-title">Leave History</span></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Type</th><th>From</th><th>To</th><th>Days</th><th>Status</th></tr></thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $employee->leaveRequests->take(7); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($leave->leaveType->name); ?></td>
                        <td><?php echo e($leave->date_from->format('M d')); ?></td>
                        <td><?php echo e($leave->date_to->format('M d')); ?></td>
                        <td><?php echo e($leave->total_days); ?></td>
                        <td><span class="badge badge-<?php echo e($leave->status); ?>"><?php echo e(ucfirst($leave->status)); ?></span></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" class="text-muted" style="text-align:center;padding:20px">No leave requests.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\Cornelia\resources\views/employees/show.blade.php ENDPATH**/ ?>