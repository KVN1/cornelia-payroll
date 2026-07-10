<?php

namespace App\Http\Controllers;

use App\Models\TimeLog;
use App\Models\Employee;
use App\Models\ShiftAssignment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TimeLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = TimeLog::with(['employee' => function($query) {
                $query->withTrashed();
            }])
            ->when($request->date,        fn($q, $d) => $q->where('log_date', $d))
            ->when($request->employee_id, fn($q, $e) => $q->where('employee_id', $e))
            ->orderByDesc('log_date')
            ->paginate(20);

        $employees = Employee::where('status', 'active')->orderBy('last_name')->get();
        return view('attendance.index', compact('logs', 'employees'));
    }

    public function timeIn(Request $request)
    {
        $request->validate(['employee_id' => 'required|exists:employees,id']);

        $today = Carbon::today()->toDateString();
        $log   = TimeLog::firstOrCreate(
            ['employee_id' => $request->employee_id, 'log_date' => $today]
        );

        if ($log->time_in) {
            return response()->json(['success' => false, 'message' => 'Already timed in today.']);
        }

        $now          = Carbon::now();
        $log->time_in = $now;

        $assignment = ShiftAssignment::with('shift')
            ->where('employee_id', $request->employee_id)
            ->where('work_date', $today)
            ->first();

        if ($assignment) {
            $shiftStart  = Carbon::parse($today . ' ' . $assignment->shift->start_time);
            $lateMinutes = max(0, $shiftStart->diffInMinutes($now, false));
            $log->is_late      = $lateMinutes > 0;
            $log->late_minutes = (int) $lateMinutes;
        }

        $log->save();
        return response()->json(['success' => true, 'message' => 'Time In recorded at ' . $now->format('h:i A')]);
    }

    public function breakOut(Request $request)
    {
        $request->validate(['employee_id' => 'required|exists:employees,id']);
        $log = $this->getTodayLog($request->employee_id);

        if (!$log->time_in)
            return response()->json(['success' => false, 'message' => 'Please Time In first.']);
        if ($log->break_out && !$log->break_in)
            return response()->json(['success' => false, 'message' => 'Already on break.']);

        $now = Carbon::now();
        if ($log->break_out && $log->break_in) {
            $log->break2_out = $now;
        } else {
            $log->break_out = $now;
        }

        $log->save();
        return response()->json(['success' => true, 'message' => 'Break started at ' . $now->format('h:i A')]);
    }

    public function breakIn(Request $request)
    {
        $request->validate(['employee_id' => 'required|exists:employees,id']);
        $log = $this->getTodayLog($request->employee_id);

        $now = Carbon::now();
        if ($log->break2_out && !$log->break2_in) {
            $log->break2_in = $now;
        } elseif ($log->break_out && !$log->break_in) {
            $log->break_in = $now;
        } else {
            return response()->json(['success' => false, 'message' => 'No active break to end.']);
        }

        $log->save();
        return response()->json(['success' => true, 'message' => 'Break ended at ' . $now->format('h:i A')]);
    }

    public function timeOut(Request $request)
    {
        $request->validate(['employee_id' => 'required|exists:employees,id']);
        $log = $this->getTodayLog($request->employee_id);

        if (!$log->time_in)
            return response()->json(['success' => false, 'message' => 'Please Time In first.']);
        if ($log->time_out)
            return response()->json(['success' => false, 'message' => 'Already timed out today.']);

        $now           = Carbon::now();
        $log->time_out = $now;

        $assignment = ShiftAssignment::with('shift')
            ->where('employee_id', $request->employee_id)
            ->where('work_date', Carbon::today()->toDateString())
            ->first();

        $shiftStart = $assignment
            ? Carbon::parse(Carbon::today()->toDateString() . ' ' . $assignment->shift->start_time)->format('H:i')
            : '08:00';

        $log->computeHours($shiftStart);
        $log->save();

        return response()->json(['success' => true, 'message' => 'Time Out recorded. Hours worked: ' . $log->total_hours_worked]);
    }

    // ── PIN Clock ─────────────────────────────────────────
    public function pinClock(Request $request)
    {
        $request->validate([
            'pin'    => 'required|string|max:6',
            'action' => 'nullable|in:time-in,break-out,break-in,time-out',
        ]);

        $employee = Employee::where('biometric_pin', $request->pin)
            ->where('status', 'active')
            ->first();

        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Invalid PIN. Please try again.']);
        }

        $today = Carbon::today()->toDateString();
        $log   = TimeLog::firstOrCreate([
            'employee_id' => $employee->id,
            'log_date'    => $today,
        ]);

        $now    = Carbon::now();
        $action = $request->action;

        // If no specific action given, auto-detect
        if (!$action) {
            if (!$log->time_in)                          $action = 'time-in';
            elseif (!$log->break_out)                    $action = 'break-out';
            elseif ($log->break_out && !$log->break_in)  $action = 'break-in';
            elseif (!$log->time_out)                     $action = 'time-out';
            else return response()->json(['success' => false, 'message' => "{$employee->full_name} has already completed today's shift."]);
        }

        // Validate action against current state
        switch ($action) {
            case 'time-in':
                if ($log->time_in) return response()->json(['success' => false, 'message' => "{$employee->full_name} already timed in today."]);
                $log->time_in = $now;
                $assignment = ShiftAssignment::with('shift')->where('employee_id', $employee->id)->where('work_date', $today)->first();
                if ($assignment) {
                    $shiftStart = Carbon::parse($today . ' ' . $assignment->shift->start_time);
                    $late = max(0, $shiftStart->diffInMinutes($now, false));
                    $log->is_late = $late > 0;
                    $log->late_minutes = (int) $late;
                }
                $label = 'AM Time In';
                break;

            case 'break-out':
                if (!$log->time_in) return response()->json(['success' => false, 'message' => 'Please Time In first.']);
                if ($log->break_out && !$log->break_in) return response()->json(['success' => false, 'message' => 'Already on break.']);
                $log->break_out = $now;
                $label = 'Break / Lunch';
                break;

            case 'break-in':
                if (!$log->break_out) return response()->json(['success' => false, 'message' => 'No active break to end.']);
                if ($log->break_in) return response()->json(['success' => false, 'message' => 'Already returned from break.']);
                $log->break_in = $now;
                $label = 'PM Time In';
                break;

            case 'time-out':
                if (!$log->time_in) return response()->json(['success' => false, 'message' => 'Please Time In first.']);
                if ($log->time_out) return response()->json(['success' => false, 'message' => 'Already timed out today.']);
                $log->time_out = $now;
                $assignment = ShiftAssignment::with('shift')->where('employee_id', $employee->id)->where('work_date', $today)->first();
                $shiftStart = $assignment ? Carbon::parse($today . ' ' . $assignment->shift->start_time)->format('H:i') : '08:00';
                $log->computeHours($shiftStart);
                $label = 'PM Time Out';
                break;

            default:
                return response()->json(['success' => false, 'message' => 'Invalid action.']);
        }

        $log->save();

        return response()->json([
            'success'  => true,
            'action'   => $label,
            'message'  => "{$label} recorded for {$employee->full_name} at " . $now->format('h:i A'),
            'employee' => $employee->full_name,
            'time'     => $now->format('h:i A'),
        ]);
    }

    private function getTodayLog(int $employeeId): TimeLog
    {
        return TimeLog::firstOrCreate([
            'employee_id' => $employeeId,
            'log_date'    => Carbon::today()->toDateString(),
        ]);
    }
}
