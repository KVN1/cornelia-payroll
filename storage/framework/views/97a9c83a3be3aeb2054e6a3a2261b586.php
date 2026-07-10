<?php $__env->startSection('page-title', 'Payroll'); ?>
<?php $__env->startSection('content'); ?>

<div class="flex items-center justify-between mb-4">
    <div>
        <div class="section-title">Payroll Periods</div>
        <div class="section-sub">Semi-monthly payroll for all active employees.</div>
    </div>
    <a href="<?php echo e(route('payroll.create')); ?>" class="btn btn-primary">+ New Period</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Period</th><th>Pay Date</th><th>Status</th><th>Records</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $periods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><strong><?php echo e($period->period_start->format('M d')); ?> – <?php echo e($period->period_end->format('M d, Y')); ?></strong></td>
                    <td><?php echo e($period->pay_date->format('M d, Y')); ?></td>
                    <td><span class="badge badge-<?php echo e($period->status); ?>"><?php echo e(ucfirst($period->status)); ?></span></td>
                    <td><?php echo e($period->records_count); ?> employees</td>
                    <td class="flex gap-2">
                        <a href="<?php echo e(route('payroll.show', $period)); ?>" class="btn btn-outline btn-sm">View</a>
                        <?php if($period->status === 'open'): ?>
                        <form method="POST" action="<?php echo e(route('payroll.generate', $period)); ?>">
                            <?php echo csrf_field(); ?>
                            <button class="btn btn-caramel btn-sm" onclick="return confirm('Generate payroll for all active employees?')">⚙ Generate</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" class="text-muted" style="text-align:center;padding:32px">No payroll periods yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div style="padding:0 20px"><?php echo e($periods->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\Cornelia\resources\views/payroll/index.blade.php ENDPATH**/ ?>