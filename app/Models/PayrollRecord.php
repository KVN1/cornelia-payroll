<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PayrollRecord extends Model {
    protected $fillable = [
        'payroll_period_id','employee_id',
        'days_worked','basic_pay','overtime_pay','holiday_pay',
        'night_diff_pay','allowances','gross_pay',
        'sss_contribution','philhealth','pagibig','withholding_tax',
        'late_deduction','absent_deduction','other_deductions',
        'total_deductions','net_pay','status','notes',
    ];

    public function period()   { return $this->belongsTo(PayrollPeriod::class, 'payroll_period_id'); }
    public function employee() { return $this->belongsTo(Employee::class); }
}
