<?php $__env->startSection('page-title', 'Employees'); ?>
<?php $__env->startSection('content'); ?>

<div class="flex items-center justify-between mb-4">
    <div>
        <div class="page-title">Employees</div>
        <div class="page-sub">All staff at Cornelia Street Bistro.</div>
    </div>
    <a href="<?php echo e(route('employees.create')); ?>" class="btn btn-primary">+ Add Employee</a>
</div>

<div class="card mb-4" style="padding:16px 20px">
    <form method="GET" class="flex gap-2">
        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search name or employee no…" class="form-control" style="max-width:280px">
        <select name="status" class="form-control" style="max-width:150px">
            <option value="">All Status</option>
            <option value="active"     <?php echo e(request('status')=='active'     ?'selected':''); ?>>Active</option>
            <option value="inactive"   <?php echo e(request('status')=='inactive'   ?'selected':''); ?>>Inactive</option>
            <option value="terminated" <?php echo e(request('status')=='terminated' ?'selected':''); ?>>Terminated</option>
        </select>
        <button class="btn btn-accent">Filter</button>
        <a href="<?php echo e(route('employees.index')); ?>" class="btn btn-outline">Reset</a>
    </form>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Emp No.</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Department</th>
                    <th>Type</th>
                    <th>Daily Rate</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><span style="font-family:monospace;font-size:12px;color:#888"><?php echo e($emp->employee_no); ?></span></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff;flex-shrink:0">
                                <?php echo e(strtoupper(substr($emp->first_name,0,1).substr($emp->last_name,0,1))); ?>

                            </div>
                            <div>
                                <strong><?php echo e($emp->full_name); ?></strong><br>
                                <span class="text-muted"><?php echo e($emp->position->title); ?></span>
                            </div>
                        </div>
                    </td>
                    <td><?php echo e($emp->position->title); ?></td>
                    <td><?php echo e($emp->position->department->name); ?></td>
                    <td><?php echo e(str_replace('_',' ', ucfirst($emp->employment_type))); ?></td>
                    <td>₱<?php echo e(number_format($emp->daily_rate,2)); ?></td>
                    <td><span class="badge badge-<?php echo e($emp->status); ?>"><?php echo e(ucfirst($emp->status)); ?></span></td>
                    <td>
                        <div class="flex gap-2">
                            <a href="<?php echo e(route('employees.show', $emp)); ?>" class="btn btn-outline btn-sm">View</a>
                            <a href="<?php echo e(route('employees.edit', $emp)); ?>" class="btn btn-accent btn-sm">Edit</a>
                            <button onclick="confirmArchive(<?php echo e($emp->id); ?>, '<?php echo e(addslashes($emp->full_name)); ?>', '<?php echo e(route('employees.archive', $emp)); ?>')"
                                    class="btn btn-sm"
                                    style="background:var(--red-bg);color:var(--red);border:1px solid rgba(193,18,31,0.2)">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>
                                Archive
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" style="text-align:center;padding:40px">
                        <div style="font-size:28px;margin-bottom:8px">👥</div>
                        <div class="text-muted">No employees found.</div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div style="padding:0 20px"><?php echo e($employees->withQueryString()->links()); ?></div>
</div>


<div id="archive-modal" style="display:none;position:fixed;inset:0;z-index:1000;background:rgba(0,0,0,0.45);align-items:center;justify-content:center">
    <div style="background:var(--surface);border-radius:16px;width:100%;max-width:400px;margin:20px;box-shadow:0 20px 60px rgba(0,0,0,0.2);overflow:hidden;animation:modalIn 0.2s ease">
        <div style="background:var(--sidebar);padding:16px 22px;display:flex;align-items:center;justify-content:space-between">
            <div style="font-size:14px;font-weight:700;color:#fff;display:flex;align-items:center;gap:8px">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2"><polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>
                Archive Employee
            </div>
            <button onclick="closeArchiveModal()" style="background:rgba(255,255,255,0.1);border:none;color:#fff;width:28px;height:28px;border-radius:7px;cursor:pointer;display:flex;align-items:center;justify-content:center">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div style="padding:22px 24px">
            <div style="font-size:13.5px;color:var(--text2);margin-bottom:14px">
                You are about to archive <strong id="archive-name" style="color:var(--text)">this employee</strong>.
            </div>
            <div style="font-size:12.5px;color:var(--text2);background:#faf8f5;border-radius:8px;padding:10px 14px;border:1px solid var(--border);margin-bottom:20px;line-height:1.7">
                Their records, payslips, and attendance history will be preserved.<br>
                Restore anytime from <strong>Settings → Archived</strong>.
            </div>
            <div class="flex gap-2">
                <button id="archive-confirm-btn" class="btn btn-danger btn-sm" style="flex:1;justify-content:center">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>
                    Archive
                </button>
                <button onclick="closeArchiveModal()" class="btn btn-outline btn-sm" style="flex:1;justify-content:center">Cancel</button>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes modalIn {
    from { opacity:0; transform:scale(0.95) translateY(10px); }
    to   { opacity:1; transform:scale(1) translateY(0); }
}
</style>

<script>
(function() {
    const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let archiveUrl = null;

    window.confirmArchive = function(id, name, url) {
        archiveUrl = url;
        document.getElementById('archive-name').textContent = name;
        document.getElementById('archive-modal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    };

    document.getElementById('archive-confirm-btn').addEventListener('click', async function() {
        if (!archiveUrl) return;
        this.disabled = true;
        this.textContent = 'Archiving…';
        try {
            const res = await fetch(archiveUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                }
            });
            if (res.ok || res.redirected) {
                closeArchiveModal();
                window.location.href = '<?php echo e(route("employees.index")); ?>';
            } else {
                alert('Failed to archive. Please try again.');
                this.disabled = false;
                this.textContent = 'Archive';
            }
        } catch(e) {
            closeArchiveModal();
            window.location.href = '<?php echo e(route("employees.index")); ?>';
        }
    });

    function closeArchiveModal() {
        document.getElementById('archive-modal').style.display = 'none';
        document.body.style.overflow = '';
    }

    window.closeArchiveModal = closeArchiveModal;

    document.getElementById('archive-modal').addEventListener('click', e => {
        if (e.target.id === 'archive-modal') closeArchiveModal();
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeArchiveModal();
    });
})();
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\Cornelia\resources\views/employees/index.blade.php ENDPATH**/ ?>