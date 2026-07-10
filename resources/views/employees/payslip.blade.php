@extends('layouts.app')
@section('page-title', 'Payslip')
@section('content')

<div class="flex items-center gap-3 mb-4">
    <a href="{{ route('payroll.show', $record->period) }}" class="btn btn-outline btn-sm">← Back</a>
    <div>
        <div class="page-title">Payslip</div>
        <div class="page-sub">{{ $record->employee->full_name }} · {{ $record->period->period_start->format('M d') }} – {{ $record->period->period_end->format('M d, Y') }}</div>
    </div>
    <button onclick="window.print()" class="btn btn-outline btn-sm" style="margin-left:auto">🖨 Print</button>
</div>

<div style="max-width:680px" id="payslip">

    {{-- Header --}}
    <div class="card" style="margin-bottom:16px">
        <div style="background:var(--sidebar);padding:24px 28px;display:flex;align-items:center;justify-content:space-between">
            <div>
                <div style="font-size:18px;font-weight:700;color:#fff;letter-spacing:-0.3px">Cornelia Street Bistro</div>
                <div style="font-size:12px;color:rgba(255,255,255,0.4);margin-top:3px;text-transform:uppercase;letter-spacing:1px">Official Payslip</div>
            </div>
            <div style="text-align:right">
                <div style="font-size:13px;color:var(--gold);font-weight:600">
                    {{ $record->period->period_start->format('M d') }} – {{ $record->period->period_end->format('M d, Y') }}
                </div>
                <div style="font-size:11.5px;color:rgba(255,255,255,0.4);margin-top:3px">
                    Pay Date: {{ $record->period->pay_date->format('F d, Y') }}
                </div>
            </div>
        </div>

        {{-- Employee Info --}}
        <div style="padding:20px 28px;border-bottom:1px solid var(--border);display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px">
            <div>
                <div style="font-size:10.5px;text-transform:uppercase;letter-spacing:0.8px;color:var(--text2);font-weight:600;margin-bottom:4px">Employee</div>
                <div style="font-size:14px;font-weight:700">{{ $record->employee->full_name }}</div>
                <div style="font-size:12px;color:var(--text2)">{{ $record->employee->employee_no }}</div>
            </div>
            <div>
                <div style="font-size:10.5px;text-transform:uppercase;letter-spacing:0.8px;color:var(--text2);font-weight:600;margin-bottom:4px">Position</div>
                <div style="font-size:13px;font-weight:600">{{ $record->employee->position->title }}</div>
                <div style="font-size:12px;color:var(--text2)">{{ $record->employee->position->department->name }}</div>
            </div>
            <div>
                <div style="font-size:10.5px;text-transform:uppercase;letter-spacing:0.8px;color:var(--text2);font-weight:600;margin-bottom:4px">Daily Rate</div>
                <div style="font-size:13px;font-weight:600">₱{{ number_format($record->employee->daily_rate, 2) }}</div>
                <div style="font-size:12px;color:var(--text2)">{{ $record->days_worked }} days worked</div>
            </div>
        </div>
    </div>

    {{-- Earnings & Deductions --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">

        {{-- Earnings --}}
        <div class="card">
            <div class="card-header" style="background:linear-gradient(to bottom,#f0faf4,#fff)">
                <span class="card-title" style="color:var(--green)">Earnings</span>
                <span style="font-size:18px">💵</span>
            </div>
            <div style="padding:16px 20px">
                <table style="width:100%">
                    <tbody>
                        <tr>
                            <td style="padding:8px 0;font-size:13px;color:var(--text2)">Basic Pay</td>
                            <td style="padding:8px 0;font-size:13px;font-weight:600;text-align:right">₱{{ number_format($record->basic_pay, 2) }}</td>
                        </tr>
                        @if($record->overtime_pay > 0)
                        <tr>
                            <td style="padding:8px 0;font-size:13px;color:var(--text2)">Overtime Pay</td>
                            <td style="padding:8px 0;font-size:13px;font-weight:600;text-align:right;color:var(--accent)">₱{{ number_format($record->overtime_pay, 2) }}</td>
                        </tr>
                        @endif
                        @if($record->holiday_pay > 0)
                        <tr>
                            <td style="padding:8px 0;font-size:13px;color:var(--text2)">Holiday Pay</td>
                            <td style="padding:8px 0;font-size:13px;font-weight:600;text-align:right;color:var(--accent)">₱{{ number_format($record->holiday_pay, 2) }}</td>
                        </tr>
                        @endif
                        @if($record->night_diff_pay > 0)
                        <tr>
                            <td style="padding:8px 0;font-size:13px;color:var(--text2)">Night Differential</td>
                            <td style="padding:8px 0;font-size:13px;font-weight:600;text-align:right;color:var(--accent)">₱{{ number_format($record->night_diff_pay, 2) }}</td>
                        </tr>
                        @endif
                        @if($record->allowances > 0)
                        <tr>
                            <td style="padding:8px 0;font-size:13px;color:var(--text2)">Allowances</td>
                            <td style="padding:8px 0;font-size:13px;font-weight:600;text-align:right;color:var(--accent)">₱{{ number_format($record->allowances, 2) }}</td>
                        </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr style="border-top:2px solid var(--green-bg)">
                            <td style="padding:10px 0 4px;font-size:13px;font-weight:700;color:var(--green)">Gross Pay</td>
                            <td style="padding:10px 0 4px;font-size:16px;font-weight:700;text-align:right;color:var(--green)">₱{{ number_format($record->gross_pay, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Deductions --}}
        <div class="card">
            <div class="card-header" style="background:linear-gradient(to bottom,#fff5f5,#fff)">
                <span class="card-title" style="color:var(--red)">Deductions</span>
                <span style="font-size:18px">📋</span>
            </div>
            <div style="padding:16px 20px">
                <table style="width:100%">
                    <tbody>
                        <tr>
                            <td style="padding:8px 0;font-size:13px;color:var(--text2)">SSS Contribution</td>
                            <td style="padding:8px 0;font-size:13px;font-weight:600;text-align:right;color:var(--red)">− ₱{{ number_format($record->sss_contribution, 2) }}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0;font-size:13px;color:var(--text2)">PhilHealth</td>
                            <td style="padding:8px 0;font-size:13px;font-weight:600;text-align:right;color:var(--red)">− ₱{{ number_format($record->philhealth, 2) }}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0;font-size:13px;color:var(--text2)">Pag-IBIG</td>
                            <td style="padding:8px 0;font-size:13px;font-weight:600;text-align:right;color:var(--red)">− ₱{{ number_format($record->pagibig, 2) }}</td>
                        </tr>
                        @if($record->withholding_tax > 0)
                        <tr>
                            <td style="padding:8px 0;font-size:13px;color:var(--text2)">Withholding Tax</td>
                            <td style="padding:8px 0;font-size:13px;font-weight:600;text-align:right;color:var(--red)">− ₱{{ number_format($record->withholding_tax, 2) }}</td>
                        </tr>
                        @endif
                        @if($record->late_deduction > 0)
                        <tr>
                            <td style="padding:8px 0;font-size:13px;color:var(--text2)">Late Deduction</td>
                            <td style="padding:8px 0;font-size:13px;font-weight:600;text-align:right;color:var(--red)">− ₱{{ number_format($record->late_deduction, 2) }}</td>
                        </tr>
                        @endif
                        @if($record->absent_deduction > 0)
                        <tr>
                            <td style="padding:8px 0;font-size:13px;color:var(--text2)">Absent Deduction</td>
                            <td style="padding:8px 0;font-size:13px;font-weight:600;text-align:right;color:var(--red)">− ₱{{ number_format($record->absent_deduction, 2) }}</td>
                        </tr>
                        @endif
                        @if($record->other_deductions > 0)
                        <tr>
                            <td style="padding:8px 0;font-size:13px;color:var(--text2)">Other Deductions</td>
                            <td style="padding:8px 0;font-size:13px;font-weight:600;text-align:right;color:var(--red)">− ₱{{ number_format($record->other_deductions, 2) }}</td>
                        </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr style="border-top:2px solid var(--red-bg)">
                            <td style="padding:10px 0 4px;font-size:13px;font-weight:700;color:var(--red)">Total Deductions</td>
                            <td style="padding:10px 0 4px;font-size:16px;font-weight:700;text-align:right;color:var(--red)">− ₱{{ number_format($record->total_deductions, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- Net Pay --}}
    <div style="background:var(--sidebar);border-radius:var(--radius);padding:24px 28px;display:flex;align-items:center;justify-content:space-between">
        <div>
            <div style="font-size:12px;text-transform:uppercase;letter-spacing:1.5px;color:rgba(255,255,255,0.4);font-weight:600;margin-bottom:6px">Net Pay</div>
            <div style="font-size:13px;color:rgba(255,255,255,0.5)">
                Gross ₱{{ number_format($record->gross_pay,2) }} − Deductions ₱{{ number_format($record->total_deductions,2) }}
            </div>
        </div>
        <div style="text-align:right">
            <div style="font-size:36px;font-weight:700;color:var(--gold);letter-spacing:-1px">
                ₱{{ number_format($record->net_pay, 2) }}
            </div>
            <span class="badge badge-{{ $record->status }}" style="margin-top:4px">{{ ucfirst($record->status) }}</span>
        </div>
    </div>

    {{-- Footer note --}}
    <div style="text-align:center;font-size:11.5px;color:var(--text2);margin-top:16px;padding:0 20px">
        This is an official payslip generated by the Cornelia Street Bistro Payroll System.
    </div>

</div>

@endsection

@push('styles')
<style>
@media print {
    .sidebar, .topbar, .btn, .page-header { display: none !important; }
    .main-wrap { margin-left: 0 !important; }
    .content { padding: 0 !important; }
    #payslip { max-width: 100% !important; }
}
</style>
@endpush
