<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model {
    use SoftDeletes;

    protected $fillable = [
        'employee_no','biometric_pin','first_name','last_name','middle_name',
        'email','phone','position_id','employment_type',
        'hire_date','end_date','status',
        'sss_no','philhealth_no','pagibig_no','tin_no','daily_rate', 'biometric_id',
'webauthn_credential_id',
'webauthn_public_key',
'biometric_enrolled',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'end_date'  => 'date',
    ];

    public function getFullNameAttribute(): string {
        return "{$this->first_name} {$this->last_name}";
    }

    public function position()        { return $this->belongsTo(Position::class); }
    public function timeLogs()        { return $this->hasMany(TimeLog::class); }
    public function shiftAssignments(){ return $this->hasMany(ShiftAssignment::class); }
    public function leaveRequests()   { return $this->hasMany(LeaveRequest::class); }
    public function payrollRecords()  { return $this->hasMany(PayrollRecord::class); }

    public function scopeActive($query) { return $query->where('status', 'active'); }
}
