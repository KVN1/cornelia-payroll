<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\TimeLog;
use App\Models\LeaveRequest;
use App\Models\PayrollPeriod;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today          = Carbon::today()->toDateString();
        $totalEmployees = Employee::where('status', 'active')->count();
        $presentToday   = TimeLog::where('log_date', $today)->whereNotNull('time_in')->count();
        $absentToday    = $totalEmployees - $presentToday;
        $pendingLeaves  = LeaveRequest::where('status', 'pending')->count();
        $openPayrolls   = PayrollPeriod::where('status', 'open')->count();

        $todayLogs = TimeLog::with(['employee' => function($query) {
                $query->withTrashed();
            }, 'employee.position'])
            ->where('log_date', $today)
            ->get()
            ->filter(fn($log) => $log->employee !== null);

        $pendingLeavesList = LeaveRequest::with('employee', 'leaveType')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // Payroll deadline notifications — periods with pay date within 3 days
        $payrollAlerts = PayrollPeriod::where('status', 'open')
            ->whereDate('pay_date', '>=', Carbon::today())
            ->whereDate('pay_date', '<=', Carbon::today()->addDays(3))
            ->get();

        return view('dashboard', compact(
            'totalEmployees', 'presentToday', 'absentToday',
            'pendingLeaves', 'openPayrolls', 'todayLogs',
            'pendingLeavesList', 'payrollAlerts'
        ));
    }
}
