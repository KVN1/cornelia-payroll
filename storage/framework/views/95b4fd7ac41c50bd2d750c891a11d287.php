<?php $__env->startSection('page-title', 'Password Requests'); ?>
<?php $__env->startSection('content'); ?>

<div class="flex items-center justify-between mb-4">
    <div>
        <div class="page-title">Password Change Requests</div>
        <div class="page-sub">Review and approve staff password change requests.</div>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Requested</th>
                    <th>Status</th>
                    <th>Reviewed By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr data-req="<?php echo e($req->id); ?>">
                    <td>
                        <strong><?php echo e($req->user->name); ?></strong><br>
                        <span class="text-muted"><?php echo e($req->user->username); ?></span>
                    </td>
                    <td><span style="text-transform:capitalize"><?php echo e($req->user->role); ?></span></td>
                    <td><?php echo e($req->created_at->format('M d, Y h:i A')); ?></td>
                    <td><span class="badge badge-<?php echo e($req->status); ?>"><?php echo e(ucfirst($req->status)); ?></span></td>
                    <td>
                        <?php if($req->reviewer): ?>
                            <?php echo e($req->reviewer->name); ?><br>
                            <span class="text-muted"><?php echo e($req->reviewed_at->format('M d h:i A')); ?></span>
                        <?php else: ?> —
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($req->status === 'pending'): ?>
                        <div class="flex gap-2">
                            <button onclick="handlePwRequest('<?php echo e(route('auth.approve-password', $req)); ?>', <?php echo e($req->id); ?>, 'approve')"
                                    class="btn btn-success btn-sm">✓ Approve</button>
                            <button onclick="handlePwRequest('<?php echo e(route('auth.reject-password', $req)); ?>', <?php echo e($req->id); ?>, 'reject')"
                                    class="btn btn-danger btn-sm">✗ Reject</button>
                        </div>
                        <?php else: ?>
                        <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" style="text-align:center;padding:40px">
                        <div style="width:40px;height:40px;background:#f0ede8;border-radius:10px;display:flex;align-items:center;justify-content:center;margin:0 auto 10px">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--text2)" stroke-width="1.8"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </div>
                        <div class="text-muted">No password change requests yet.</div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div style="padding:0 20px"><?php echo e($requests->links()); ?></div>
</div>


<div class="page-title" style="margin-top:32px;margin-bottom:16px;font-size:18px">Direct Password Reset</div>
<div class="card" style="max-width:480px">
    <div class="card-header">
        <span class="card-title">Reset Any User's Password</span>
        <span style="font-size:11px;color:var(--text2)">Admin only</span>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label class="form-label">Select User</label>
            <select id="reset-user-select" class="form-control" onchange="goToReset(this)">
                <option value="">Select a user…</option>
                <?php $__currentLoopData = \App\Models\User::orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e(route('auth.reset-password-form', $user)); ?>">
                    <?php echo e($user->name); ?> (<?php echo e($user->username); ?>) — <?php echo e($user->role); ?>

                </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div style="font-size:12px;color:var(--text2)">
            ℹ This immediately resets the password without waiting for a request.
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

async function handlePwRequest(url, id, action) {
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
            const row = document.querySelector(`tr[data-req="${id}"]`);
            if (row) {
                const badge = row.querySelector('.badge');
                if (badge) {
                    badge.className = 'badge badge-' + (action === 'approve' ? 'approved' : 'rejected');
                    badge.textContent = action === 'approve' ? 'Approved' : 'Rejected';
                }
                row.querySelector('td:last-child').innerHTML = '<span class="text-muted">—</span>';
            }
        }
    } catch(e) {
        alert('Something went wrong. Please try again.');
    }
}

function goToReset(sel) {
    if (sel.value) window.location.href = sel.value;
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\Cornelia\resources\views/auth/password-requests.blade.php ENDPATH**/ ?>