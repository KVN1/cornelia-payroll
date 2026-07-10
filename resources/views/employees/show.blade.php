@extends('layouts.app')
@section('page-title', 'Employee Profile')
@section('content')

<div class="flex items-center gap-3 mb-4">
    <a href="{{ route('employees.index') }}" class="btn btn-outline btn-sm">← Back</a>
    <div>
        <div class="section-title">{{ $employee->full_name }}</div>
        <div class="section-sub">{{ $employee->employee_no }} · {{ $employee->position->title }} · {{ $employee->position->department->name }}</div>
    </div>
    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-caramel" style="margin-left:auto">Edit</a>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
    <div class="card">
        <div class="card-header"><span class="card-title">Personal Details</span></div>
        <div class="card-body">
            <table style="width:100%;font-size:13.5px">
                <tr><td style="color:#999;padding:6px 0;width:140px">Full Name</td><td><strong>{{ $employee->full_name }}</strong></td></tr>
                <tr><td style="color:#999;padding:6px 0">Email</td><td>{{ $employee->email ?? '—' }}</td></tr>
                <tr><td style="color:#999;padding:6px 0">Phone</td><td>{{ $employee->phone ?? '—' }}</td></tr>
                <tr><td style="color:#999;padding:6px 0">Type</td><td>{{ str_replace('_',' ', ucfirst($employee->employment_type)) }}</td></tr>
                <tr><td style="color:#999;padding:6px 0">Hire Date</td><td>{{ $employee->hire_date->format('F d, Y') }}</td></tr>
                <tr><td style="color:#999;padding:6px 0">Daily Rate</td><td><strong>₱{{ number_format($employee->daily_rate,2) }}</strong></td></tr>
                <tr><td style="color:#999;padding:6px 0">Status</td><td><span class="badge badge-{{ $employee->status }}">{{ ucfirst($employee->status) }}</span></td></tr>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><span class="card-title">Government IDs</span></div>
        <div class="card-body">
            <table style="width:100%;font-size:13.5px">
                <tr><td style="color:#999;padding:6px 0;width:140px">SSS No.</td><td>{{ $employee->sss_no ?? '—' }}</td></tr>
                <tr><td style="color:#999;padding:6px 0">PhilHealth No.</td><td>{{ $employee->philhealth_no ?? '—' }}</td></tr>
                <tr><td style="color:#999;padding:6px 0">Pag-IBIG No.</td><td>{{ $employee->pagibig_no ?? '—' }}</td></tr>
                <tr><td style="color:#999;padding:6px 0">TIN No.</td><td>{{ $employee->tin_no ?? '—' }}</td></tr>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><span class="card-title">Recent Time Logs</span></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Date</th><th>Time In</th><th>Time Out</th><th>Hours</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse($employee->timeLogs->take(7) as $log)
                    <tr>
                        <td>{{ $log->log_date->format('M d') }}</td>
                        <td>{{ $log->time_in  ? $log->time_in->format('h:i A')  : '—' }}</td>
                        <td>{{ $log->time_out ? $log->time_out->format('h:i A') : '—' }}</td>
                        <td>{{ $log->total_hours_worked > 0 ? $log->total_hours_worked.'h' : '—' }}</td>
                        <td>
                            @if($log->is_late)
                                <span class="badge badge-rejected">Late {{ $log->late_minutes }}m</span>
                            @else
                                <span class="badge badge-active">On time</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-muted" style="text-align:center;padding:20px">No logs yet.</td></tr>
                    @endforelse
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
                    @forelse($employee->leaveRequests->take(7) as $leave)
                    <tr>
                        <td>{{ $leave->leaveType->name }}</td>
                        <td>{{ $leave->date_from->format('M d') }}</td>
                        <td>{{ $leave->date_to->format('M d') }}</td>
                        <td>{{ $leave->total_days }}</td>
                        <td><span class="badge badge-{{ $leave->status }}">{{ ucfirst($leave->status) }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-muted" style="text-align:center;padding:20px">No leave requests.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
