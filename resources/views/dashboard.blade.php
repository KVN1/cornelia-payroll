@extends('layouts.app')
@section('page-title', 'Dashboard')
@section('content')

{{-- Header --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px">
    <div>
        <div style="font-size:24px;font-weight:700;color:var(--text);letter-spacing:-0.5px">
            Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 18 ? 'afternoon' : 'evening') }}, {{ auth()->user()->name }}
        </div>
        <div style="font-size:14px;color:var(--text2);margin-top:4px">
            {{ now()->format('l, F j Y') }} · Cornelia Street Bistro
        </div>
    </div>
    <div style="font-size:12px;color:var(--green);background:var(--green-bg);border:1px solid rgba(45,106,79,0.2);border-radius:8px;padding:7px 14px;display:flex;align-items:center;gap:7px;font-weight:600">
        <span style="width:7px;height:7px;background:var(--green);border-radius:50%;display:inline-block"></span>
        System Online
    </div>
</div>

{{-- Stat Cards --}}
<div class="stats-grid" style="grid-template-columns:repeat(5,1fr);margin-bottom:20px">

    <div class="stat-card green" onclick="window.location='{{ route('employees.index') }}'" style="cursor:pointer">
        <div class="stat-icon green">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="1.8"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <div class="stat-label">Active Staff</div>
        <div class="stat-value">{{ $totalEmployees }}</div>
        <div class="stat-sub">on payroll</div>
    </div>

    <div class="stat-card blue" onclick="window.location='{{ route('attendance.index') }}'" style="cursor:pointer">
        <div class="stat-icon blue">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--blue)" stroke-width="1.8"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div class="stat-label">Present Today</div>
        <div class="stat-value">{{ $presentToday }}</div>
        <div class="stat-sub">timed in</div>
    </div>

    <div class="stat-card red" onclick="window.location='{{ route('attendance.index') }}'" style="cursor:pointer">
        <div class="stat-icon red">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="1.8"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </div>
        <div class="stat-label">Absent</div>
        <div class="stat-value" style="color:var(--red)">{{ $absentToday }}</div>
        <div class="stat-sub">no log today</div>
    </div>

    <div class="stat-card orange" onclick="window.location='{{ route('leaves.index') }}'" style="cursor:pointer">
        <div class="stat-icon orange">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--orange)" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        </div>
        <div class="stat-label">Pending Leaves</div>
        <div class="stat-value">{{ $pendingLeaves }}</div>
        <div class="stat-sub">awaiting approval</div>
    </div>

    <div class="stat-card" onclick="window.location='{{ route('payroll.index') }}'" style="cursor:pointer">
        <div class="stat-icon gold">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.8"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </div>
        <div class="stat-label">Open Payroll</div>
        <div class="stat-value">{{ $openPayrolls }}</div>
        <div class="stat-sub">period(s)</div>
    </div>

</div>

{{-- Attendance Rate --}}
@if($totalEmployees > 0)
<div class="card" style="padding:18px 22px;margin-bottom:20px">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px">
        <div style="display:flex;align-items:center;gap:8px">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--text2)" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            <span style="font-size:13px;font-weight:600;color:var(--text)">Today's Attendance Rate</span>
        </div>
        <span style="font-size:14px;font-weight:700;color:var(--green)">
            {{ round(($presentToday / $totalEmployees) * 100) }}%
        </span>
    </div>
    <div style="background:#f0ede8;border-radius:20px;height:8px;overflow:hidden">
        <div style="height:100%;width:{{ round(($presentToday / $totalEmployees) * 100) }}%;background:linear-gradient(to right,var(--green),#52b788);border-radius:20px;transition:width 1s ease"></div>
    </div>
    <div style="display:flex;justify-content:space-between;margin-top:8px;font-size:11.5px;color:var(--text2)">
        <span>{{ $presentToday }} present</span>
        <span>{{ $absentToday }} absent</span>
        <span>{{ $totalEmployees }} total</span>
    </div>
</div>
@endif

{{-- Main Grid --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">

    {{-- Today's Attendance --}}
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Today's Attendance</div>
                <div style="font-size:12px;color:var(--text2);margin-top:2px">{{ now()->format('M d, Y') }}</div>
            </div>
            <a href="{{ route('attendance.index') }}" class="btn btn-outline btn-sm">View All</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Employee</th><th>Time In</th><th>Break</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse($todayLogs as $log)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <div style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff;flex-shrink:0">
                                    {{ strtoupper(substr($log->employee->first_name,0,1).substr($log->employee->last_name,0,1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600;font-size:13px">{{ $log->employee->full_name }}</div>
                                    <div style="font-size:11.5px;color:var(--text2)">{{ $log->employee->position->title }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:13px;font-weight:500">{{ $log->time_in ? $log->time_in->format('h:i A') : '—' }}</td>
                        <td>
                            @if($log->break_out && !$log->break_in)
                                <span class="badge badge-pending">On Break</span>
                            @elseif($log->break_in)
                                <span style="font-size:12px;color:var(--text2)">Done</span>
                            @else
                                <span style="color:#ccc">—</span>
                            @endif
                        </td>
                        <td>
                            @if($log->time_out)
                                <span class="badge badge-inactive">Out</span>
                            @elseif($log->time_in)
                                <span class="badge badge-active">On Duty</span>
                            @else
                                <span class="badge badge-rejected">Absent</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center;padding:36px 24px">
                            <div style="width:40px;height:40px;background:#f0ede8;border-radius:10px;display:flex;align-items:center;justify-content:center;margin:0 auto 10px">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--text2)" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                            <div style="font-size:13px;color:var(--text2)">No time logs yet today.</div>
                            <a href="{{ route('attendance.index') }}" class="btn btn-accent btn-sm" style="margin-top:12px;display:inline-flex">Go to Attendance</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pending Leave Requests --}}
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Pending Leave Requests</div>
                <div style="font-size:12px;color:var(--text2);margin-top:2px">{{ $pendingLeaves }} awaiting approval</div>
            </div>
            <a href="{{ route('leaves.index') }}" class="btn btn-outline btn-sm">View All</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Employee</th><th>Type</th><th>Dates</th><th>Action</th></tr>
                </thead>
                <tbody>
                    @forelse($pendingLeavesList as $leave)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <div style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff;flex-shrink:0">
                                    {{ strtoupper(substr($leave->employee->first_name,0,1).substr($leave->employee->last_name,0,1)) }}
                                </div>
                                <strong style="font-size:13px">{{ $leave->employee->full_name }}</strong>
                            </div>
                        </td>
                        <td>
                            <span style="background:var(--orange-bg);color:var(--orange);padding:3px 9px;border-radius:20px;font-size:11.5px;font-weight:600">
                                {{ $leave->leaveType->name }}
                            </span>
                        </td>
                        <td style="font-size:12.5px;color:var(--text2)">
                            {{ $leave->date_from->format('M d') }} – {{ $leave->date_to->format('M d') }}
                        </td>
                        <td>
                            <div style="display:flex;gap:5px">
                                <form method="POST" action="{{ route('leaves.approve', $leave) }}" style="display:inline">
                                    @csrf
                                    <button class="btn btn-success btn-xs" title="Approve">
                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('leaves.reject', $leave) }}" style="display:inline">
                                    @csrf
                                    <button class="btn btn-danger btn-xs" title="Reject">
                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center;padding:36px 24px">
                            <div style="width:40px;height:40px;background:#f0ede8;border-radius:10px;display:flex;align-items:center;justify-content:center;margin:0 auto 10px">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--text2)" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            </div>
                            <div style="font-size:13px;color:var(--text2)">No pending leave requests.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Quick Actions --}}
<div style="margin-top:20px;background:var(--sidebar);border-radius:var(--radius);padding:20px 24px;display:flex;align-items:center;justify-content:space-between;gap:16px">
    <div>
        <div style="font-size:13px;font-weight:600;color:#fff">Quick Actions</div>
        <div style="font-size:11.5px;color:rgba(255,255,255,0.35);margin-top:2px">Jump to common tasks</div>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap">
        <a href="{{ route('employees.create') }}" class="btn btn-sm" style="background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.8);border:1px solid rgba(255,255,255,0.1);gap:7px">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
            Add Employee
        </a>
        <a href="{{ route('attendance.index') }}" class="btn btn-sm" style="background:rgba(200,132,74,0.2);color:var(--gold);border:1px solid rgba(200,132,74,0.3);gap:7px">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Attendance
        </a>
        <a href="{{ route('leaves.create') }}" class="btn btn-sm" style="background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.8);border:1px solid rgba(255,255,255,0.1);gap:7px">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            File Leave
        </a>
        <a href="{{ route('payroll.create') }}" class="btn btn-sm" style="background:rgba(45,106,79,0.25);color:#6ee7b7;border:1px solid rgba(45,106,79,0.4);gap:7px">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            New Payroll
        </a>
    </div>
</div>

@endsection
