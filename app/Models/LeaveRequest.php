<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model {
    protected $fillable = [
        'employee_id','leave_type_id','date_from','date_to',
        'total_days','reason','status','approved_by','approved_at',
    ];
    protected $casts = [
        'date_from'   => 'date',
        'date_to'     => 'date',
        'approved_at' => 'datetime',
    ];

    public function employee()  { return $this->belongsTo(Employee::class); }
    public function leaveType() { return $this->belongsTo(LeaveType::class); }
    public function approver()  { return $this->belongsTo(Employee::class, 'approved_by'); }
}
