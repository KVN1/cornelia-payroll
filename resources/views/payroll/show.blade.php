@extends('layouts.app')
@section('page-title', 'Payroll Records')
@section('content')

{{-- Deductions Modal --}}
<div id="deductions-modal" style="
    display:none;
    position:fixed;inset:0;z-index:1000;
    background:rgba(0,0,0,0.45);
    align-items:center;justify-content:center;
">
    <div style="
        background:var(--surface);border-radius:16px;
        width:100%;max-width:440px;margin:20px;
        box-shadow:0 20px 60px rgba(0,0,0,0.2);
        animation:modalIn 0.2s ease;
        overflow:hidden;
    ">
        {{-- Modal Header --}}
        <div style="background:var(--sidebar);padding:20px 24px;display:flex;align-items:center;justify-content:space-between">
            <div>
                <div style="font-size:15px;font-weight:700;color:#fff">Deductions Breakdown</div>
                <div style="font-size:12px;color:rgba(255,255,255,0.4);margin-top:2px" id="modal-emp-name">—</div>
            </div>
            <button onclick="closeModal()" style="background:rgba(255,255,255,0.1);border:none;color:#fff;width:32px;height:32px;border-radius:8px;cursor:pointer;font-size:16px;display:flex;align-items:center;justify-content:center">✕</button>
        </div>

        {{-- Modal Body --}}
        <div style="padding:20px 24px">
            <div id="modal-rows"></div>

            {{-- Total --}}
            <div style="border-top:2px solid var(--red-bg);margin-top:4px;padding-top:14px;display:flex;align-items:center;justify-content:space-between">
                <span style="font-size:14px;font-weight:700;color:var(--red)">Total Deductions</span>
                <span style="font-size:20px;font-weight:700;color:var(--red)" id="modal-total">₱0.00</span>
            </div>
        </div>
    </div>
</div>

<div class="flex items-center gap-3 mb-4">
    <a href="{{ route('payroll.index') }}" class="btn btn-outline btn-sm">← Back</a>
    <div>
        <div class="page-title">
            {{ $payroll->period_start->format('M d') }} – {{ $payroll->period_end->format('M d, Y') }}
        </div>
        <div class="page-sub">
            Pay Date: {{ $payroll->pay_date->format('F d, Y') }} &nbsp;·&nbsp;
            <span class="badge badge-{{ $payroll->status }}">{{ ucfirst($payroll->status) }}</span>
            &nbsp;·&nbsp;
            @php $daysUntilPay = now()->diffInDays($payroll->pay_date, false); @endphp
            @if($daysUntilPay > 0 && $daysUntilPay <= 3 && $payroll->status === 'open')
                <span style="color:var(--red);font-weight:600;font-size:12px">⚠ Pay date in {{ $daysUntilPay }} day(s)!</span>
            @elseif($daysUntilPay == 0 && $payroll->status === 'open')
                <span style="color:var(--red);font-weight:700;font-size:12px">🔴 Pay date is TODAY!</span>
            @elseif($daysUntilPay < 0)
                <span style="color:var(--text2);font-size:12px">Paid {{ abs($daysUntilPay) }} days ago</span>
            @else
                <span style="color:var(--text2);font-size:12px">{{ $daysUntilPay }} days until pay date</span>
            @endif
        </div>
    </div>
    @if($payroll->status === 'open')
    <form method="POST" action="{{ route('payroll.generate', $payroll) }}" style="margin-left:auto">
        @csrf
        <button class="btn btn-accent" onclick="return confirm('Generate payroll for all active employees?')">⚙ Generate Payroll</button>
    </form>
    @endif
</div>

{{-- Summary Cards --}}
@if($payroll->records->count())
<div class="stats-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:20px">
    <div class="stat-card">
        <div class="stat-icon blue">👥</div>
        <div class="stat-label">Employees</div>
        <div class="stat-value">{{ $payroll->records->count() }}</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon green">💵</div>
        <div class="stat-label">Total Gross</div>
        <div class="stat-value" style="font-size:18px">₱{{ number_format($payroll->records->sum('gross_pay'),2) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red">📋</div>
        <div class="stat-label">Total Deductions</div>
        <div class="stat-value" style="font-size:18px;color:var(--red)">₱{{ number_format($payroll->records->sum('total_deductions'),2) }}</div>
    </div>
    <div class="stat-card orange">
        <div class="stat-icon gold">💰</div>
        <div class="stat-label">Total Net Pay</div>
        <div class="stat-value" style="font-size:18px;color:var(--accent)">₱{{ number_format($payroll->records->sum('net_pay'),2) }}</div>
    </div>
</div>
@endif

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Days</th>
                    <th>Basic Pay</th>
                    <th>OT Pay</th>
                    <th>Gross Pay</th>
                    <th>Deductions</th>
                    <th>Net Pay</th>
                    <th>Status</th>
                    <th>Payslip</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payroll->records as $rec)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff;flex-shrink:0">
                                {{ strtoupper(substr($rec->employee->first_name,0,1).substr($rec->employee->last_name,0,1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:13px">{{ $rec->employee->full_name }}</div>
                                <div style="font-size:11.5px;color:var(--text2)">{{ $rec->employee->position->title }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $rec->days_worked }}</td>
                    <td>₱{{ number_format($rec->basic_pay,2) }}</td>
                    <td>
                        @if($rec->overtime_pay > 0)
                            <span style="color:var(--accent);font-weight:600">₱{{ number_format($rec->overtime_pay,2) }}</span>
                        @else —
                        @endif
                    </td>
                    <td><strong>₱{{ number_format($rec->gross_pay,2) }}</strong></td>

                    {{-- Clickable Deductions --}}
                    <td>
                        @php
                            $dedData = json_encode([
                                'name'       => $rec->employee->full_name,
                                'sss'        => number_format($rec->sss_contribution,2),
                                'philhealth' => number_format($rec->philhealth,2),
                                'pagibig'    => number_format($rec->pagibig,2),
                                'tax'        => number_format($rec->withholding_tax,2),
                                'late'       => number_format($rec->late_deduction,2),
                                'absent'     => number_format($rec->absent_deduction,2),
                                'other'      => number_format($rec->other_deductions,2),
                                'total'      => number_format($rec->total_deductions,2),
                            ]);
                        @endphp
                        <button onclick="showDeductions({{ $dedData }})"
                        style="background:none;border:none;cursor:pointer;text-align:left;padding:0">
                            <span style="color:var(--red);font-weight:600;text-decoration:underline;text-decoration-style:dotted;text-underline-offset:3px">
                                − ₱{{ number_format($rec->total_deductions,2) }}
                            </span>
                            <div style="font-size:11px;color:#bbb;margin-top:2px">
                                click to view breakdown
                            </div>
                        </button>
                    </td>

                    <td><strong style="color:var(--green);font-size:15px">₱{{ number_format($rec->net_pay,2) }}</strong></td>
                    <td><span class="badge badge-{{ $rec->status }}">{{ ucfirst($rec->status) }}</span></td>
                    <td>
                        <a href="{{ route('payroll.payslip', $rec) }}" class="btn btn-outline btn-sm">📄 View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center;padding:40px 24px">
                        <div style="font-size:28px;margin-bottom:8px">📊</div>
                        <div style="font-size:13px;color:var(--text2);margin-bottom:12px">No records yet. Click Generate Payroll to compute.</div>
                        @if($payroll->status === 'open')
                        <form method="POST" action="{{ route('payroll.generate', $payroll) }}" style="display:inline">
                            @csrf
                            <button class="btn btn-accent btn-sm">⚙ Generate Now</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('styles')
<style>
@keyframes modalIn {
    from { opacity:0; transform:scale(0.95) translateY(10px); }
    to   { opacity:1; transform:scale(1) translateY(0); }
}
</style>
@endpush

@push('scripts')
<script>
const modal   = document.getElementById('deductions-modal');
const empName = document.getElementById('modal-emp-name');
const rows    = document.getElementById('modal-rows');
const total   = document.getElementById('modal-total');

const labels = {
    sss:        { label: 'SSS Contribution'  },
    philhealth: { label: 'PhilHealth'         },
    pagibig:    { label: 'Pag-IBIG'           },
    tax:        { label: 'Withholding Tax'    },
    late:       { label: 'Late Deduction'     },
    absent:     { label: 'Absent Deduction'   },
    other:      { label: 'Other Deductions'   },
};

function showDeductions(data) {
    empName.textContent = data.name;
    total.textContent   = '− ₱' + data.total;
    rows.innerHTML      = '';

    let hasAny = false;

    Object.entries(labels).forEach(([key, cfg]) => {
        const val = parseFloat(data[key].replace(/,/g,''));
        if (val <= 0) return;
        hasAny = true;

        const row = document.createElement('div');
        row.style.cssText = 'display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f3ede5';
        row.innerHTML = `
            <span style="font-size:13.5px;color:var(--text)">${cfg.label}</span>
            <span style="font-size:14px;font-weight:600;color:var(--red)">− ₱${data[key]}</span>
        `;
        rows.appendChild(row);
    });

    if (!hasAny) {
        rows.innerHTML = '<div style="text-align:center;padding:20px;color:var(--text2);font-size:13px">No deductions found.</div>';
    }

    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    modal.style.display = 'none';
    document.body.style.overflow = '';
}

// Close on backdrop click
modal.addEventListener('click', e => {
    if (e.target === modal) closeModal();
});

// Close on Escape key
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeModal();
});
</script>
@endpush
