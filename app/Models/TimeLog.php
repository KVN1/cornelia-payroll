<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TimeLog extends Model {
    protected $fillable = [
        'employee_id','log_date',
        'time_in','break_out','break_in','time_out',
        'break2_out','break2_in',
        'total_hours_worked','overtime_hours',
        'is_late','late_minutes','remarks',
    ];
    protected $casts = [
        'log_date'   => 'date',
        'time_in'    => 'datetime',
        'break_out'  => 'datetime',
        'break_in'   => 'datetime',
        'time_out'   => 'datetime',
        'break2_out' => 'datetime',
        'break2_in'  => 'datetime',
        'is_late'    => 'boolean',
    ];

    public function employee() { return $this->belongsTo(Employee::class); }

    public function computeHours(string $shiftStartTime = '08:00'): void
    {
        if (!$this->time_in || !$this->time_out) return;

        $worked = $this->time_in->diffInMinutes($this->time_out);

        if ($this->break_out && $this->break_in) {
            $worked -= $this->break_out->diffInMinutes($this->break_in);
        }
        if ($this->break2_out && $this->break2_in) {
            $worked -= $this->break2_out->diffInMinutes($this->break2_in);
        }

        $hoursWorked              = round($worked / 60, 2);
        $this->total_hours_worked = $hoursWorked;
        $this->overtime_hours     = max(0, round($hoursWorked - 8, 2));

        $shiftStart  = Carbon::parse($this->log_date->format('Y-m-d') . ' ' . $shiftStartTime);
        $lateMinutes = max(0, $shiftStart->diffInMinutes($this->time_in, false));
        $this->is_late      = $lateMinutes > 0;
        $this->late_minutes = (int) $lateMinutes;
    }
}
