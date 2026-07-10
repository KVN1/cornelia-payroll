@extends('layouts.app')
@section('page-title', 'File Leave')
@section('content')

<div class="flex items-center gap-3 mb-4">
    <a href="{{ route('leaves.index') }}" class="btn btn-outline btn-sm">← Back</a>
    <div>
        <div class="page-title">File a Leave Request</div>
        <div class="page-sub">Submit a leave application for an employee.</div>
    </div>
</div>

<div class="card" style="max-width:520px">
    <div class="card-body">
        <form id="leave-form" method="POST" action="{{ route('leaves.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Employee *</label>
                <select name="employee_id" class="form-control" required>
                    <option value="">Select employee…</option>
                    @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" {{ old('employee_id')==$emp->id?'selected':'' }}>
                        {{ $emp->full_name }} ({{ $emp->employee_no }})
                    </option>
                    @endforeach
                </select>
                @error('employee_id')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Leave Type *</label>
                <select name="leave_type_id" class="form-control" required>
                    <option value="">Select type…</option>
                    @foreach($leaveTypes as $lt)
                    <option value="{{ $lt->id }}" {{ old('leave_type_id')==$lt->id?'selected':'' }}>
                        {{ $lt->name }} {{ !$lt->is_paid ? '(Unpaid)' : '' }}
                    </option>
                    @endforeach
                </select>
                @error('leave_type_id')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Date From *</label>
                    <input type="date" name="date_from" class="form-control" value="{{ old('date_from') }}" required>
                    @error('date_from')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Date To *</label>
                    <input type="date" name="date_to" class="form-control" value="{{ old('date_to') }}" required>
                    @error('date_to')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Reason</label>
                <textarea name="reason" class="form-control" rows="3">{{ old('reason') }}</textarea>
            </div>

            {{-- Days preview --}}
            <div id="days-preview" style="display:none;background:#faf8f5;border-radius:8px;padding:10px 14px;border:1px solid var(--border);margin-bottom:16px;font-size:13px;color:var(--text2)">
                Duration: <strong id="days-count" style="color:var(--accent)">0</strong> working day(s)
            </div>

            <div class="flex gap-2 mt-4">
                <button type="submit" id="submit-btn" class="btn btn-primary">Submit Request</button>
                <a href="{{ route('leaves.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
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
@endpush
