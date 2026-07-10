<?php $__env->startSection('page-title', 'New Payroll Period'); ?>
<?php $__env->startSection('content'); ?>

<div class="flex items-center gap-3 mb-4">
    <a href="<?php echo e(route('payroll.index')); ?>" class="btn btn-outline btn-sm">← Back</a>
    <div>
        <div class="page-title">New Payroll Period</div>
        <div class="page-sub">Create a single period or auto-generate both semi-monthly cutoffs.</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;max-width:860px">

    
    <div class="card" style="border:2px solid var(--accent);position:relative">
        <div style="position:absolute;top:-11px;left:16px;background:var(--accent);color:#fff;font-size:11px;font-weight:700;padding:2px 12px;border-radius:20px;letter-spacing:0.5px">
            RECOMMENDED
        </div>
        <div class="card-header" style="background:linear-gradient(to bottom,#fff8f2,#fff)">
            <div>
                <div class="card-title">Semi-Monthly Auto-Generate</div>
                <div style="font-size:12px;color:var(--text2);margin-top:2px">Creates both 1–15 and 16–30 periods at once</div>
            </div>
            <span style="font-size:22px">⚡</span>
        </div>
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('payroll.store-semi-monthly')); ?>">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label class="form-label">Month *</label>
                    <input type="month" name="month" class="form-control"
                           value="<?php echo e(old('month', now()->format('Y-m'))); ?>" required>
                    <div class="form-hint">Will generate two periods for this month</div>
                </div>

                
                <div id="preview" style="background:#faf8f5;border-radius:8px;padding:14px;margin-bottom:16px;border:1px solid var(--border)">
                    <div style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--text2);font-weight:600;margin-bottom:10px">Preview</div>
                    <div style="display:flex;flex-direction:column;gap:8px">
                        <div style="display:flex;align-items:center;justify-content:space-between;background:#fff;border-radius:7px;padding:10px 14px;border:1px solid var(--border)">
                            <div>
                                <div style="font-size:13px;font-weight:600" id="p1-label">Mar 1 – Mar 15, 2026</div>
                                <div style="font-size:11.5px;color:var(--text2)">Pay date: <span id="p1-pay">Mar 15, 2026</span></div>
                            </div>
                            <span style="background:var(--green-bg);color:var(--green);font-size:11px;font-weight:600;padding:3px 9px;border-radius:20px">1st cutoff</span>
                        </div>
                        <div style="display:flex;align-items:center;justify-content:space-between;background:#fff;border-radius:7px;padding:10px 14px;border:1px solid var(--border)">
                            <div>
                                <div style="font-size:13px;font-weight:600" id="p2-label">Mar 16 – Mar 31, 2026</div>
                                <div style="font-size:11.5px;color:var(--text2)">Pay date: <span id="p2-pay">Mar 31, 2026</span></div>
                            </div>
                            <span style="background:var(--blue-bg);color:var(--blue);font-size:11px;font-weight:600;padding:3px 9px;border-radius:20px">2nd cutoff</span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-accent w-full" style="justify-content:center">
                    ⚡ Generate Both Periods
                </button>
            </form>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Manual Period</div>
                <div style="font-size:12px;color:var(--text2);margin-top:2px">Set custom start, end, and pay dates</div>
            </div>
            <span style="font-size:22px">📋</span>
        </div>
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('payroll.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label class="form-label">Period Start *</label>
                    <input type="date" name="period_start" class="form-control" value="<?php echo e(old('period_start')); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Period End *</label>
                    <input type="date" name="period_end" class="form-control" value="<?php echo e(old('period_end')); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Pay Date *</label>
                    <input type="date" name="pay_date" class="form-control" value="<?php echo e(old('pay_date')); ?>" required>
                    <div class="form-hint">The actual date employees receive their pay</div>
                </div>
                <div class="flex gap-2 mt-6">
                    <button type="submit" class="btn btn-primary">Create Period</button>
                    <a href="<?php echo e(route('payroll.index')); ?>" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
const monthInput = document.querySelector('input[type="month"]');

function updatePreview() {
    const val = monthInput.value;
    if (!val) return;

    const [year, month] = val.split('-').map(Number);
    const lastDay = new Date(year, month, 0).getDate();

    const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    const m = months[month - 1];

    document.getElementById('p1-label').textContent = `${m} 1 – ${m} 15, ${year}`;
    document.getElementById('p1-pay').textContent   = `${m} 15, ${year}`;
    document.getElementById('p2-label').textContent = `${m} 16 – ${m} ${lastDay}, ${year}`;
    document.getElementById('p2-pay').textContent   = `${m} ${lastDay}, ${year}`;
}

monthInput.addEventListener('input', updatePreview);
updatePreview();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\Cornelia\resources\views/payroll/create.blade.php ENDPATH**/ ?>