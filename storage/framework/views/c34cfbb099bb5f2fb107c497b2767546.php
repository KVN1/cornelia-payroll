<?php $__env->startSection('page-title', 'Leave Requests'); ?>
<?php $__env->startSection('content'); ?>

<div class="flex items-center justify-between mb-4">
    <div>
        <div class="page-title">Leave Requests</div>
        <div class="page-sub">Manage employee leave applications.</div>
    </div>
    <a href="<?php echo e(route('leaves.create')); ?>" class="btn btn-primary">+ File Leave</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Leave Type</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Days</th>
                    <th>Filed</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="leaves-tbody">
                <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr id="leave-row-<?php echo e($req->id); ?>">
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff;flex-shrink:0">
                                <?php echo e(strtoupper(substr($req->employee->first_name,0,1).substr($req->employee->last_name,0,1))); ?>

                            </div>
                            <strong><?php echo e($req->employee->full_name); ?></strong>
                        </div>
                    </td>
                    <td>
                        <span style="background:var(--orange-bg);color:var(--orange);padding:2px 9px;border-radius:20px;font-size:11.5px;font-weight:600">
                            <?php echo e($req->leaveType->name); ?>

                        </span>
                        <?php if(!$req->leaveType->is_paid): ?>
                            <span style="font-size:11px;color:#aaa;margin-left:4px">(unpaid)</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($req->date_from->format('M d, Y')); ?></td>
                    <td><?php echo e($req->date_to->format('M d, Y')); ?></td>
                    <td><?php echo e($req->total_days); ?> day(s)</td>
                    <td><?php echo e($req->created_at->format('M d')); ?></td>
                    <td><span class="badge badge-<?php echo e($req->status); ?>"><?php echo e(ucfirst($req->status)); ?></span></td>
                    <td>
                        <?php if($req->status === 'pending'): ?>
                        <div class="flex gap-2">
                            <button onclick="handleLeave(<?php echo e($req->id); ?>, 'approve')"
                                    class="btn btn-success btn-xs">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                Approve
                            </button>
                            <button onclick="handleLeave(<?php echo e($req->id); ?>, 'reject')"
                                    class="btn btn-danger btn-xs">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                Reject
                            </button>
                        </div>
                        <?php else: ?>
                        <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" style="text-align:center;padding:40px">
                        <div style="width:40px;height:40px;background:#f0ede8;border-radius:10px;display:flex;align-items:center;justify-content:center;margin:0 auto 10px">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--text2)" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        </div>
                        <div class="text-muted">No leave requests yet.</div>
                        <a href="<?php echo e(route('leaves.create')); ?>" class="btn btn-accent btn-sm" style="margin-top:12px;display:inline-flex">+ File Leave</a>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div style="padding:0 20px"><?php echo e($requests->links()); ?></div>
</div>


<div id="leave-toast" style="position:fixed;top:20px;right:24px;z-index:9999;padding:12px 20px;border-radius:10px;font-size:13.5px;font-weight:500;display:none;align-items:center;gap:10px;box-shadow:0 8px 24px rgba(0,0,0,0.15);animation:toastIn 0.25s ease;max-width:320px">
    <span id="leave-toast-msg"></span>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
@keyframes toastIn { from{opacity:0;transform:translateX(20px)} to{opacity:1;transform:translateX(0)} }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function showToast(msg, type) {
    const t = document.getElementById('leave-toast');
    document.getElementById('leave-toast-msg').textContent = msg;
    t.style.background  = type === 'success' ? '#1a1208' : '#c1121f';
    t.style.color       = '#fff';
    t.style.display     = 'flex';
    t.style.animation   = 'toastIn 0.25s ease';
    clearTimeout(t._t);
    t._t = setTimeout(() => t.style.display = 'none', 3000);
}

async function handleLeave(id, action) {
    const url = action === 'approve'
        ? `/leaves/${id}/approve`
        : `/leaves/${id}/reject`;

    try {
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        });

        if (res.ok) {
            // Update the row badge and remove action buttons
            const row = document.getElementById('leave-row-' + id);
            if (row) {
                const badge = row.querySelector('.badge');
                if (badge) {
                    badge.className = 'badge badge-' + action + 'd';
                    badge.textContent = action === 'approve' ? 'Approved' : 'Rejected';
                }
                const actionCell = row.querySelector('td:last-child');
                if (actionCell) actionCell.innerHTML = '<span class="text-muted">—</span>';
            }
            showToast(action === 'approve' ? 'Leave approved.' : 'Leave rejected.', 'success');
        } else {
            showToast('Something went wrong. Please try again.', 'error');
        }
    } catch(e) {
        showToast('Network error. Please try again.', 'error');
    }
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\Cornelia\resources\views/leaves/index.blade.php ENDPATH**/ ?>