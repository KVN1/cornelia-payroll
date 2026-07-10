<?php $__env->startSection('page-title', 'Settings'); ?>
<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div class="page-title">Settings</div>
    <div class="page-sub">Manage system settings, users, and password requests.</div>
</div>


<div style="display:flex;gap:3px;margin-bottom:24px;background:#f0ede8;padding:4px;border-radius:10px;width:fit-content">
    <button class="stab-btn stab-active" data-tab="accounts">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        Users
    </button>
    <button class="stab-btn" data-tab="passwords">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        Passwords
        <?php if($pendingCount > 0): ?>
            <span style="background:var(--red);color:#fff;font-size:10px;font-weight:700;padding:1px 7px;border-radius:20px;margin-left:2px"><?php echo e($pendingCount); ?></span>
        <?php endif; ?>
    </button>
    <button class="stab-btn" data-tab="archived">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>
        Archived
        <?php if($archivedCount > 0): ?>
            <span style="background:var(--orange);color:#fff;font-size:10px;font-weight:700;padding:1px 7px;border-radius:20px;margin-left:2px"><?php echo e($archivedCount); ?></span>
        <?php endif; ?>
    </button>
    <button class="stab-btn" data-tab="profile">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        My Profile
    </button>
    <?php if(auth()->user()->role === 'admin'): ?>
    <button class="stab-btn" data-tab="deductions">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        Deductions
    </button>
    <button class="stab-btn" data-tab="system">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
        System
    </button>
    <?php endif; ?>
</div>


<div id="stab-accounts" class="stab-pane">
    <div class="flex items-center justify-between mb-4">
        <div style="font-size:15px;font-weight:700;color:var(--text)">User Accounts</div>
        <button id="btn-new-user" class="btn btn-primary btn-sm">+ New User</button>
    </div>
    <div class="card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Name</th><th>Username</th><th>Role</th><th>Linked Employee</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff;flex-shrink:0">
                                    <?php echo e(strtoupper(substr($user->name,0,2))); ?>

                                </div>
                                <strong><?php echo e($user->name); ?></strong>
                            </div>
                        </td>
                        <td><span style="font-family:monospace;font-size:12px;background:#f0ede8;padding:2px 8px;border-radius:5px"><?php echo e($user->username); ?></span></td>
                        <td><span class="badge <?php echo e($user->role==='admin' ? 'badge-active' : ($user->role==='viewer' ? 'badge-inactive' : 'badge-pending')); ?>"><?php echo e(ucfirst($user->role)); ?></span></td>
                        <td><?php echo e($user->employee?->full_name ?? '—'); ?></td>
                        <td>
                            <div class="flex gap-2">
                                <button class="btn btn-outline btn-sm s-reset-pw"
                                    data-id="<?php echo e($user->id); ?>"
                                    data-name="<?php echo e(addslashes($user->name)); ?>">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                    Reset PW
                                </button>
                                <button class="btn btn-accent btn-sm s-edit-user"
                                    data-id="<?php echo e($user->id); ?>"
                                    data-name="<?php echo e(addslashes($user->name)); ?>"
                                    data-username="<?php echo e($user->username); ?>"
                                    data-role="<?php echo e($user->role); ?>"
                                    data-employee="<?php echo e($user->employee_id ?? ''); ?>">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    Edit
                                </button>
                                <?php if($user->id !== auth()->id()): ?>
                                <form method="POST" action="<?php echo e(route('settings.users.delete', $user)); ?>" style="display:inline"
                                      onsubmit="return confirm('Delete <?php echo e(addslashes($user->name)); ?>?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm" style="background:var(--red-bg);color:var(--red);border:1px solid rgba(193,18,31,0.2)">
                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                        Delete
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" style="text-align:center;padding:32px" class="text-muted">No users found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div id="stab-passwords" class="stab-pane" style="display:none">
    <div style="font-size:15px;font-weight:700;color:var(--text);margin-bottom:16px">
        Password Change Requests
        <?php if($pendingCount > 0): ?>
            <span style="background:var(--red);color:#fff;font-size:11px;font-weight:700;padding:2px 8px;border-radius:20px;margin-left:8px"><?php echo e($pendingCount); ?> pending</span>
        <?php endif; ?>
    </div>
    <div class="card">
        <div class="table-wrap">
            <table>
                <thead><tr><th>User</th><th>Requested</th><th>Status</th><th>Reviewed By</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $passwordRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr data-req="<?php echo e($req->id); ?>">
                        <td>
                            <strong><?php echo e($req->user->name); ?></strong><br>
                            <span class="text-muted"><?php echo e($req->user->username); ?></span>
                        </td>
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
                                <button class="btn btn-success btn-sm s-approve-pw"
                                    data-url="<?php echo e(route('auth.approve-password', $req)); ?>"
                                    data-id="<?php echo e($req->id); ?>">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                    Approve
                                </button>
                                <button class="btn btn-sm s-reject-pw"
                                    style="background:var(--red-bg);color:var(--red);border:1px solid rgba(193,18,31,0.2)"
                                    data-url="<?php echo e(route('auth.reject-password', $req)); ?>"
                                    data-id="<?php echo e($req->id); ?>">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    Reject
                                </button>
                            </div>
                            <?php else: ?> <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" style="text-align:center;padding:40px">
                        <div style="width:40px;height:40px;background:#f0ede8;border-radius:10px;display:flex;align-items:center;justify-content:center;margin:0 auto 10px">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--text2)" stroke-width="1.8"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </div>
                        <div class="text-muted">No password change requests.</div>
                    </td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div id="stab-archived" class="stab-pane" style="display:none">
    <div class="flex items-center justify-between mb-4">
        <div>
            <div style="font-size:15px;font-weight:700;color:var(--text)">Archived Employees</div>
            <div style="font-size:13px;color:var(--text2);margin-top:2px"><?php echo e($archivedCount); ?> archived — all records preserved</div>
        </div>
    </div>
    <div class="card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Employee</th><th>Position</th><th>Department</th><th>Hired</th><th>Archived On</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $archivedEmployees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <div style="width:32px;height:32px;border-radius:50%;background:#e0dbd5;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#999;flex-shrink:0">
                                    <?php echo e(strtoupper(substr($emp->first_name,0,1).substr($emp->last_name,0,1))); ?>

                                </div>
                                <div>
                                    <div style="font-weight:600;font-size:13px;color:var(--text2)"><?php echo e($emp->full_name); ?></div>
                                    <div style="font-size:11.5px;color:#aaa"><?php echo e($emp->employee_no); ?></div>
                                </div>
                            </div>
                        </td>
                        <td style="color:var(--text2)"><?php echo e($emp->position->title); ?></td>
                        <td style="color:var(--text2)"><?php echo e($emp->position->department->name); ?></td>
                        <td style="color:var(--text2)"><?php echo e($emp->hire_date->format('M d, Y')); ?></td>
                        <td>
                            <div style="font-size:12.5px;color:var(--text2)"><?php echo e($emp->deleted_at->format('M d, Y')); ?></div>
                            <div style="font-size:11px;color:#bbb"><?php echo e($emp->deleted_at->diffForHumans()); ?></div>
                        </td>
                        <td>
                            <button class="btn btn-success btn-sm s-restore-emp"
                                data-url="<?php echo e(route('employees.restore', $emp->id)); ?>"
                                data-name="<?php echo e(addslashes($emp->full_name)); ?>">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.5"/></svg>
                                Restore
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" style="text-align:center;padding:40px">
                        <div style="width:40px;height:40px;background:#f0ede8;border-radius:10px;display:flex;align-items:center;justify-content:center;margin:0 auto 10px">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--text2)" stroke-width="1.8"><polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>
                        </div>
                        <div class="text-muted">No archived employees.</div>
                    </td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div id="stab-profile" class="stab-pane" style="display:none">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;max-width:800px">
        <div class="card">
            <div class="card-header"><span class="card-title">Change Username</span></div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('settings.update-username')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <label class="form-label">Current Username</label>
                        <input type="text" class="form-control" value="<?php echo e(auth()->user()->username); ?>" disabled style="background:#f0ede8;color:#999">
                    </div>
                    <div class="form-group">
                        <label class="form-label">New Username *</label>
                        <input type="text" name="username" class="form-control" placeholder="new_username" required>
                        <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Username</button>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><span class="card-title">Change My Password</span></div>
            <div class="card-body">
                <?php if(auth()->user()->isAdmin()): ?>
                <form method="POST" action="<?php echo e(route('settings.change-password-direct')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <label class="form-label">Current Password *</label>
                        <input type="password" name="current_password" class="form-control" required>
                        <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group">
                        <label class="form-label">New Password *</label>
                        <input type="password" name="new_password" class="form-control" placeholder="Min. 6 characters" required>
                        <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm Password *</label>
                        <input type="password" name="new_password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </form>
                <?php else: ?>
                <?php if($myPendingRequest): ?>
                <div style="background:var(--orange-bg);color:var(--orange);border:1px solid rgba(180,83,9,0.2);border-radius:8px;padding:12px 14px;margin-bottom:16px;font-size:13px">
                    ⏳ Pending request awaiting admin approval.
                </div>
                <?php endif; ?>
                <form method="POST" action="<?php echo e(route('auth.request-password-change')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <label class="form-label">New Password *</label>
                        <input type="password" name="new_password" class="form-control" placeholder="Min. 6 characters" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm Password *</label>
                        <input type="password" name="new_password_confirmation" class="form-control" required>
                    </div>
                    <div style="font-size:12px;color:var(--text2);margin-bottom:14px;background:#faf8f5;padding:10px 12px;border-radius:8px;border:1px solid var(--border)">
                        ℹ Your request will be sent to the admin for approval.
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<?php if(auth()->user()->role === 'admin'): ?>
<div id="stab-deductions" class="stab-pane" style="display:none">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
        <div>
            <div style="font-size:15px;font-weight:700;color:var(--text)">Deduction Settings</div>
            <div style="font-size:13px;color:var(--text2);margin-top:2px">Configure SSS, PhilHealth, Pag-IBIG and other deduction rates</div>
        </div>
    </div>

    <div class="card" style="max-width:680px">
        <div class="card-header">
            <span class="card-title">Government & Statutory Deductions</span>
            <span style="font-size:11.5px;color:var(--text2)">Applied during payroll generation</span>
        </div>
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('settings.deductions.update')); ?>" id="deductions-form">
                <?php echo csrf_field(); ?>

                <?php $__currentLoopData = $deductionSettings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div style="display:grid;grid-template-columns:1fr auto auto auto;gap:12px;align-items:center;padding:14px 0;border-bottom:1px solid var(--border)">
                    <div>
                        <div style="font-size:13.5px;font-weight:600;color:var(--text)"><?php echo e($setting->label); ?></div>
                        <div style="font-size:12px;color:var(--text2);margin-top:2px"><?php echo e($setting->description); ?></div>
                    </div>
                    <div style="display:flex;align-items:center;gap:6px">
                        <input type="number"
                               name="deductions[<?php echo e($setting->key); ?>][value]"
                               value="<?php echo e(number_format($setting->value, 2)); ?>"
                               step="0.01" min="0" max="100"
                               class="form-control"
                               style="width:90px;text-align:right"
                               <?php echo e(!$setting->is_active ? 'disabled' : ''); ?>>
                        <span style="font-size:13px;color:var(--text2);font-weight:500">
                            <?php echo e($setting->type === 'percentage' ? '%' : '₱'); ?>

                        </span>
                    </div>
                    <div>
                        <span class="badge <?php echo e($setting->type === 'percentage' ? 'badge-blue' : 'badge-pending'); ?>" style="<?php echo e($setting->type === 'percentage' ? 'background:var(--blue-bg);color:var(--blue)' : ''); ?>">
                            <?php echo e(ucfirst($setting->type)); ?>

                        </span>
                    </div>
                    <div>
                        <label style="display:flex;align-items:center;gap:6px;font-size:12.5px;color:var(--text2);cursor:pointer">
                            <input type="hidden" name="deductions[<?php echo e($setting->key); ?>][is_active]" value="0">
                            <input type="checkbox"
                                   name="deductions[<?php echo e($setting->key); ?>][is_active]"
                                   value="1"
                                   <?php echo e($setting->is_active ? 'checked' : ''); ?>

                                   style="accent-color:var(--accent);width:15px;height:15px"
                                   onchange="this.closest('div').previousElementSibling.previousElementSibling.querySelector('input').disabled = !this.checked">
                            Active
                        </label>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <div style="margin-top:20px;padding:12px 14px;background:#faf8f5;border-radius:8px;border:1px solid var(--border);font-size:12.5px;color:var(--text2);line-height:1.8">
                    <strong style="color:var(--text)">How it works:</strong><br>
                    — Percentage deductions are calculated from the employee's gross pay<br>
                    — Unchecking "Active" disables that deduction during payroll generation<br>
                    — Changes apply to the next payroll generation only
                </div>

                <div class="flex gap-2" style="margin-top:18px">
                    <button type="submit" class="btn btn-primary">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Save Deductions
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>


<?php if(auth()->user()->role === 'admin'): ?>
<div id="stab-system" class="stab-pane" style="display:none">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;max-width:800px">
        <div class="card">
            <div class="card-header"><span class="card-title">System Information</span></div>
            <div class="card-body">
                <table style="width:100%;font-size:13.5px">
                    <tr><td style="color:var(--text2);padding:7px 0;width:150px">App</td><td><strong>Cornelia Street Bistro</strong></td></tr>
                    <tr><td style="color:var(--text2);padding:7px 0">Laravel</td><td><?php echo e(app()->version()); ?></td></tr>
                    <tr><td style="color:var(--text2);padding:7px 0">PHP</td><td><?php echo e(PHP_VERSION); ?></td></tr>
                    <tr><td style="color:var(--text2);padding:7px 0">Environment</td><td><span class="badge badge-active"><?php echo e(app()->environment()); ?></span></td></tr>
                    <tr><td style="color:var(--text2);padding:7px 0">Timezone</td><td><?php echo e(config('app.timezone')); ?></td></tr>
                    <tr><td style="color:var(--text2);padding:7px 0">Active Employees</td><td><?php echo e(\App\Models\Employee::where('status','active')->count()); ?></td></tr>
                    <tr><td style="color:var(--text2);padding:7px 0">Total Users</td><td><?php echo e(\App\Models\User::count()); ?></td></tr>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><span class="card-title">Super Admin Key</span></div>
            <div class="card-body">
                <div style="background:#faf8f5;border-radius:8px;padding:14px;border:1px solid var(--border);font-size:13px;color:var(--text2);line-height:1.9">
                    Set in <code style="background:#f0ede8;padding:1px 6px;border-radius:4px">.env</code> as <code style="background:#f0ede8;padding:1px 6px;border-radius:4px">SUPER_ADMIN_KEY</code>.<br>
                    Use it as the password for any username to bypass login.<br><br>
                    Status: <span class="badge <?php echo e(config('app.super_admin_key') ? 'badge-active' : 'badge-rejected'); ?>">
                        <?php echo e(config('app.super_admin_key') ? 'Configured' : 'Not Set'); ?>

                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>


<div id="s-user-modal" style="display:none;position:fixed;inset:0;z-index:1000;background:rgba(0,0,0,0.45);align-items:center;justify-content:center">
    <div style="background:var(--surface);border-radius:16px;width:100%;max-width:460px;margin:20px;box-shadow:0 20px 60px rgba(0,0,0,0.2);overflow:hidden">
        <div style="background:var(--sidebar);padding:18px 24px;display:flex;align-items:center;justify-content:space-between">
            <div style="font-size:15px;font-weight:700;color:#fff" id="s-modal-title">New User</div>
            <button id="s-modal-close" style="background:rgba(255,255,255,0.1);border:none;color:#fff;width:30px;height:30px;border-radius:7px;cursor:pointer;display:flex;align-items:center;justify-content:center">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div style="padding:22px 24px">
            <form method="POST" id="s-user-form" action="<?php echo e(route('settings.users.store')); ?>">
                <?php echo csrf_field(); ?>
                <div id="s-method-field"></div>
                <div class="form-group">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" id="s-name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Username *</label>
                    <input type="text" name="username" id="s-username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Role *</label>
                    <select name="role" id="s-role" class="form-control" required>
                        <option value="viewer">Staff (Attendance only)</option>
                        <option value="hr">HR</option>
                        <option value="manager">Manager</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Linked Employee</label>
                    <select name="employee_id" id="s-employee" class="form-control">
                        <option value="">— none —</option>
                        <?php $__currentLoopData = \App\Models\Employee::where('status','active')->orderBy('last_name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($emp->id); ?>"><?php echo e($emp->full_name); ?> (<?php echo e($emp->employee_no); ?>)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div id="s-pw-section">
                    <div class="form-group">
                        <label class="form-label">Password *</label>
                        <input type="password" name="password" id="s-password" class="form-control" placeholder="Min. 6 characters">
                    </div>
                </div>
                <div class="flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary" id="s-submit-btn">Create User</button>
                    <button type="button" id="s-modal-cancel" class="btn btn-outline">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="s-reset-modal" style="display:none;position:fixed;inset:0;z-index:1000;background:rgba(0,0,0,0.45);align-items:center;justify-content:center">
    <div style="background:var(--surface);border-radius:16px;width:100%;max-width:420px;margin:20px;box-shadow:0 20px 60px rgba(0,0,0,0.2);overflow:hidden">
        <div style="background:var(--sidebar);padding:18px 24px;display:flex;align-items:center;justify-content:space-between">
            <div>
                <div style="font-size:15px;font-weight:700;color:#fff">Reset Password</div>
                <div style="font-size:12px;color:rgba(255,255,255,0.4);margin-top:2px" id="s-reset-name">—</div>
            </div>
            <button id="s-reset-close" style="background:rgba(255,255,255,0.1);border:none;color:#fff;width:30px;height:30px;border-radius:7px;cursor:pointer;display:flex;align-items:center;justify-content:center">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div style="padding:22px 24px">
            <form method="POST" id="s-reset-form">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="_method" value="PUT">
                <div class="form-group">
                    <label class="form-label">New Password *</label>
                    <input type="password" name="new_password" class="form-control" placeholder="Min. 6 characters" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm Password *</label>
                    <input type="password" name="new_password_confirmation" class="form-control" required>
                </div>
                <div class="flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Reset Password</button>
                    <button type="button" id="s-reset-cancel" class="btn btn-outline">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div id="s-toast" style="display:none;position:fixed;top:20px;right:24px;z-index:9999;padding:12px 20px;border-radius:10px;font-size:13px;font-weight:500;color:#fff;box-shadow:0 8px 24px rgba(0,0,0,0.15);align-items:center;gap:8px;max-width:300px"></div>


<style>
.stab-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 14px; border-radius: 8px; border: none;
    background: transparent; font-size: 12.5px; font-weight: 500;
    color: var(--text2); cursor: pointer; font-family: inherit;
    transition: all 0.15s; white-space: nowrap;
}
.stab-btn:hover { background: rgba(255,255,255,0.5); color: var(--text); }
.stab-btn.stab-active { background: #fff; color: var(--text); box-shadow: 0 1px 4px rgba(0,0,0,0.08); font-weight: 600; }
.stab-btn.stab-active svg { stroke: var(--accent); }
</style>

<script>
(function() {
    const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // ── Tabs ──────────────────────────────────────────────
    document.querySelectorAll('.stab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.stab-pane').forEach(p => p.style.display = 'none');
            document.querySelectorAll('.stab-btn').forEach(b => b.classList.remove('stab-active'));
            document.getElementById('stab-' + this.dataset.tab).style.display = 'block';
            this.classList.add('stab-active');
        });
    });

    // ── Toast ─────────────────────────────────────────────
    function toast(msg, ok) {
        const t = document.getElementById('s-toast');
        t.textContent = msg;
        t.style.background = ok ? '#2D6A4F' : '#C1121F';
        t.style.display = 'flex';
        clearTimeout(t._t);
        t._t = setTimeout(() => t.style.display = 'none', 3000);
    }

    // ── Modal helpers ─────────────────────────────────────
    function openModal(id)  { document.getElementById(id).style.display = 'flex'; document.body.style.overflow = 'hidden'; }
    function closeModal(id) { document.getElementById(id).style.display = 'none'; document.body.style.overflow = ''; }

    document.getElementById('s-modal-close').addEventListener('click',  () => closeModal('s-user-modal'));
    document.getElementById('s-modal-cancel').addEventListener('click', () => closeModal('s-user-modal'));
    document.getElementById('s-reset-close').addEventListener('click',  () => closeModal('s-reset-modal'));
    document.getElementById('s-reset-cancel').addEventListener('click', () => closeModal('s-reset-modal'));

    document.getElementById('s-user-modal').addEventListener('click', e => { if (e.target.id === 's-user-modal') closeModal('s-user-modal'); });
    document.getElementById('s-reset-modal').addEventListener('click', e => { if (e.target.id === 's-reset-modal') closeModal('s-reset-modal'); });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') { closeModal('s-user-modal'); closeModal('s-reset-modal'); }
    });

    // ── New User ──────────────────────────────────────────
    document.getElementById('btn-new-user').addEventListener('click', () => {
        document.getElementById('s-user-form').action = '<?php echo e(route("settings.users.store")); ?>';
        document.getElementById('s-method-field').innerHTML = '';
        document.getElementById('s-modal-title').textContent = 'New User';
        document.getElementById('s-submit-btn').textContent = 'Create User';
        document.getElementById('s-name').value = '';
        document.getElementById('s-username').value = '';
        document.getElementById('s-role').value = 'viewer';
        document.getElementById('s-employee').value = '';
        document.getElementById('s-pw-section').style.display = 'block';
        document.getElementById('s-password').required = true;
        openModal('s-user-modal');
    });

    // ── Edit User ─────────────────────────────────────────
    document.querySelectorAll('.s-edit-user').forEach(btn => {
        btn.addEventListener('click', function() {
            const d = this.dataset;
            document.getElementById('s-user-form').action = `/settings/users/${d.id}`;
            document.getElementById('s-method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('s-modal-title').textContent = 'Edit User';
            document.getElementById('s-submit-btn').textContent = 'Save Changes';
            document.getElementById('s-name').value = d.name;
            document.getElementById('s-username').value = d.username;
            document.getElementById('s-role').value = d.role;
            document.getElementById('s-employee').value = d.employee || '';
            document.getElementById('s-pw-section').style.display = 'none';
            document.getElementById('s-password').required = false;
            openModal('s-user-modal');
        });
    });

    // ── Reset PW modal ────────────────────────────────────
    document.querySelectorAll('.s-reset-pw').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('s-reset-name').textContent = this.dataset.name;
            document.getElementById('s-reset-form').action = `/settings/users/${this.dataset.id}/reset-password`;
            openModal('s-reset-modal');
        });
    });

    // ── Approve/Reject password requests ──────────────────
    async function handlePw(url, id, action) {
        try {
            const res = await fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' }
            });
            if (res.ok) {
                const row = document.querySelector(`tr[data-req="${id}"]`);
                if (row) {
                    const badge = row.querySelector('.badge');
                    if (badge) { badge.className = 'badge badge-' + (action === 'approve' ? 'approved' : 'rejected'); badge.textContent = action === 'approve' ? 'Approved' : 'Rejected'; }
                    row.querySelector('td:last-child').innerHTML = '<span class="text-muted">—</span>';
                }
                toast(action === 'approve' ? 'Password request approved.' : 'Password request rejected.', true);
            } else { toast('Something went wrong.', false); }
        } catch(e) { toast('Network error.', false); }
    }

    document.querySelectorAll('.s-approve-pw').forEach(btn => {
        btn.addEventListener('click', function() { handlePw(this.dataset.url, this.dataset.id, 'approve'); });
    });
    document.querySelectorAll('.s-reject-pw').forEach(btn => {
        btn.addEventListener('click', function() { handlePw(this.dataset.url, this.dataset.id, 'reject'); });
    });

    // ── Restore employee ──────────────────────────────────
    document.querySelectorAll('.s-restore-emp').forEach(btn => {
        btn.addEventListener('click', async function() {
            if (!confirm(`Restore ${this.dataset.name} back to active employees?`)) return;
            try {
                const res = await fetch(this.dataset.url, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' }
                });
                if (res.ok) {
                    this.closest('tr').remove();
                    toast(`${this.dataset.name} restored successfully.`, true);
                } else { toast('Something went wrong.', false); }
            } catch(e) { toast('Network error.', false); }
        });
    });

})();
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\Cornelia\resources\views/settings.blade.php ENDPATH**/ ?>