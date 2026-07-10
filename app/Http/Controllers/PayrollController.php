<?php

namespace App\Http\Controllers;

use App\Models\PayrollPeriod;
use App\Models\PayrollRecord;
use App\Models\Employee;
use App\Models\TimeLog;
use App\Models\Holiday;
use App\Models\DeductionSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index()
    {
        $periods = PayrollPeriod::withCount('records')->orderByDesc('period_start')->paginate(10);
        return view('payroll.index', compact('periods'));
    }

    public function create()
    {
        return view('payroll.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'period_start' => 'required|date',
            'period_end'   => 'required|date|after_or_equal:period_start',
            'pay_date'     => 'required|date',
        ]);

        $period = PayrollPeriod::create($data);
        return redirect()->route('payroll.show', $period)->with('success', 'Payroll period created.');
    }

    public function storeSemiMonthly(Request $request)
    {
        $request->validate(['month' => 'required']);

        [$year, $month] = explode('-', $request->month);
        $year  = (int) $year;
        $month = (int) $month;

        $lastDay = Carbon::create($year, $month)->daysInMonth;

        // 1st cutoff: 1–15, pay on 15th
        PayrollPeriod::create([
            'period_start' => Carbon::create($year, $month, 1)->toDateString(),
            'period_end'   => Carbon::create($year, $month, 15)->toDateString(),
            'pay_date'     => Carbon::create($year, $month, 15)->toDateString(),
            'status'       => 'open',
        ]);

        // 2nd cutoff: 16–end, pay on last day
        PayrollPeriod::create([
            'period_start' => Carbon::create($year, $month, 16)->toDateString(),
            'period_end'   => Carbon::create($year, $month, $lastDay)->toDateString(),
            'pay_date'     => Carbon::create($year, $month, $lastDay)->toDateString(),
            'status'       => 'open',
        ]);

        return redirect()->route('payroll.index')->with('success', "Both payroll periods for " . Carbon::create($year, $month)->format('F Y') . " created successfully.");
    }

    public function show(PayrollPeriod $payroll)
    {
        $payroll->load(['records.employee' => function($query) {
            $query->withTrashed();
        }, 'records.employee.position']);
        return view('payroll.show', compact('payroll'));
    }

    public function payslip(\App\Models\PayrollRecord $record)
    {
        $record->load(['employee' => function($query) {
            $query->withTrashed();
        }, 'employee.position.department', 'period']);
        return view('payroll.payslip', compact('record'));
    }

    public function generate(PayrollPeriod $payroll)
    {
        if ($payroll->status !== 'open') {
            return back()->with('error', 'Payroll period is not open.');
        }

        // Load deduction rates from settings once for all employees
        $deductions = DeductionSetting::allActive();

        $payroll->update(['status' => 'processing']);
        $employees = Employee::where('status', 'active')->get();

        foreach ($employees as $employee) {
            $this->generateRecord($payroll, $employee, $deductions);
        }

        $payroll->update(['status' => 'closed']);
        return redirect()->route('payroll.show', $payroll)->with('success', 'Payroll generated successfully.');
    }

    private function generateRecord(PayrollPeriod $period, Employee $employee, array $deductions): void
    {
        $logs = TimeLog::where('employee_id', $employee->id)
            ->whereBetween('log_date', [$period->period_start, $period->period_end])
            ->get();

        $daysWorked    = $logs->whereNotNull('time_in')->whereNotNull('time_out')->count();
        $basicPay      = round($daysWorked * $employee->daily_rate, 2);
        $overtimePay   = $this->computeOvertimePay($logs, $employee->daily_rate);
        $lateDeduction = $this->computeLateDeduction($logs, $employee->daily_rate, $deductions['late_deduction'] ?? 1.0);
        $holidayPay    = $this->computeHolidayPay($period, $employee->daily_rate);
        $grossPay      = $basicPay + $overtimePay + $holidayPay;

        // ── Use rates from DeductionSettings ─────────────
        $sssRate        = ($deductions['sss']        ?? 4.5)  / 100;
        $philhealthRate = ($deductions['philhealth']  ?? 2.0)  / 100;
        $pagibigRate    = ($deductions['pagibig']     ?? 2.0)  / 100;
        $taxRate        = ($deductions['tax']         ?? 0.0)  / 100;

        $sss        = round($grossPay * $sssRate, 2);
        $philhealth = round($grossPay * $philhealthRate, 2);
        $pagibig    = min(100, round($grossPay * $pagibigRate, 2));

        // If tax rate is 0, use BIR withholding table; otherwise use flat rate
        if ($taxRate > 0) {
            $tax = round(($grossPay - $sss - $philhealth - $pagibig) * $taxRate, 2);
        } else {
            $tax = $this->computeWithholdingTax($grossPay - $sss - $philhealth - $pagibig);
        }

        $totalDeductions = $sss + $philhealth + $pagibig + $tax + $lateDeduction;
        $netPay          = $grossPay - $totalDeductions;

        PayrollRecord::updateOrCreate(
            ['payroll_period_id' => $period->id, 'employee_id' => $employee->id],
            [
                'days_worked'      => $daysWorked,
                'basic_pay'        => $basicPay,
                'overtime_pay'     => $overtimePay,
                'holiday_pay'      => $holidayPay,
                'gross_pay'        => $grossPay,
                'sss_contribution' => $sss,
                'philhealth'       => $philhealth,
                'pagibig'          => $pagibig,
                'withholding_tax'  => $tax,
                'late_deduction'   => $lateDeduction,
                'total_deductions' => $totalDeductions,
                'net_pay'          => $netPay,
                'status'           => 'approved',
            ]
        );
    }

    private function computeOvertimePay($logs, float $dailyRate): float
    {
        $hourlyRate = $dailyRate / 8;
        $totalOT    = $logs->sum('overtime_hours');
        return round($totalOT * $hourlyRate * 1.25, 2);
    }

    private function computeLateDeduction($logs, float $dailyRate, float $lateRate = 1.0): float
    {
        $hourlyRate   = $dailyRate / 8;
        $totalMinutes = $logs->sum('late_minutes');
        // lateRate is a percentage multiplier (default 1.0 = 100% of per-minute rate)
        return round(($totalMinutes / 60) * $hourlyRate * ($lateRate / 100), 2);
    }

    private function computeHolidayPay(PayrollPeriod $period, float $dailyRate): float
    {
        $holidays = Holiday::whereBetween('holiday_date', [$period->period_start, $period->period_end])
            ->where('type', 'regular')
            ->count();
        return round($holidays * $dailyRate, 2);
    }

    private function computeWithholdingTax(float $taxableIncome): float
    {
        $monthly = $taxableIncome * 2;
        if ($monthly <= 20833)  return 0;
        if ($monthly <= 33333)  return round(($monthly - 20833)  * 0.20 / 2, 2);
        if ($monthly <= 66667)  return round((2500  + ($monthly - 33333)  * 0.25) / 2, 2);
        if ($monthly <= 166667) return round((10833 + ($monthly - 66667)  * 0.30) / 2, 2);
        if ($monthly <= 666667) return round((40833 + ($monthly - 166667) * 0.32) / 2, 2);
        return round((200833 + ($monthly - 666667) * 0.35) / 2, 2);
    }
}
