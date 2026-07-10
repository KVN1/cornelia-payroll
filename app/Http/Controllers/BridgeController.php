<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\TimeLog;
use App\Models\ShiftAssignment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BridgeController extends Controller
{
    // GET /api/bridge/employees
    public function employees()
    {
        $employees = Employee::where('status', 'active')
            ->orderBy('last_name')
            ->get(['id', 'employee_no', 'first_name', 'last_name', 'biometric_id']);

        return response()->json([
            'employees' => $employees->map(fn($e) => [
                'id'           => $e->id,
                'employee_no'  => $e->employee_no,
                'full_name'    => $e->full_name,
                'biometric_id' => $e->biometric_id,
            ])
        ]);
    }

    // POST /api/bridge/enroll
    public function enroll(Request $request)
    {
        $request->validate([
            'employee_id'  => 'required|exists:employees,id',
            'biometric_id' => 'nullable|string|max:50',
            'finger_data'  => 'required|string',
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        $employee->update([
            'biometric_id'           => $request->biometric_id ?: $employee->employee_no,
            'webauthn_credential_id' => $request->finger_data,
            'biometric_enrolled'     => true,
        ]);

        return response()->json([
            'success'  => true,
            'message'  => "Fingerprint enrolled for {$employee->full_name}",
            'employee' => $employee->full_name,
        ]);
    }

    // POST /api/bridge/identify
    // Called by the server to match a scanned fingerprint against enrolled ones
    public function identify(Request $request)
    {
        $request->validate(['finger_hex' => 'required|string']);

        // Get all enrolled employees
        $employees = Employee::where('status', 'active')
            ->whereNotNull('webauthn_credential_id')
            ->where('biometric_enrolled', true)
            ->get(['id', 'first_name', 'last_name', 'webauthn_credential_id']);

        if ($employees->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No enrolled fingerprints found.',
            ], 404);
        }

        // Find exact match by stored hex template
        $matched = $employees->first(function ($emp) use ($request) {
            return $emp->webauthn_credential_id === $request->finger_hex;
        });

        if (!$matched) {
            return response()->json([
                'success' => false,
                'message' => 'Fingerprint not recognized. Please enroll first.',
            ], 404);
        }

        // Log attendance with specific action if provided
        return $this->logAttendance($matched->id, $request->action);
    }

    // POST /api/bridge/clock (used by PIN fallback)
    public function clock(Request $request)
    {
        $request->validate([
            'finger_data' => 'required|string',
            'timestamp'   => 'nullable|string',
        ]);

        $employee = Employee::where('webauthn_credential_id', $request->finger_data)
            ->where('status', 'active')
            ->first();

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Fingerprint not recognized. Please enroll first.',
            ], 404);
        }

        return $this->logAttendance($employee->id);
    }

    // GET /api/bridge/templates — return all enrolled fingerprint templates
    public function templates()
    {
        $employees = Employee::where('status', 'active')
            ->where('biometric_enrolled', true)
            ->whereNotNull('webauthn_credential_id')
            ->get(['id', 'first_name', 'last_name', 'webauthn_credential_id']);

        return response()->json([
            'templates' => $employees->map(fn($e) => [
                'employee_id' => $e->id,
                'name'        => $e->full_name,
                'template'    => $e->webauthn_credential_id,
            ])
        ]);
    }

    // POST /api/bridge/clock-by-id — log attendance by employee ID (from local match)
    public function clockById(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'action'      => 'nullable|string',
        ]);

        return $this->logAttendance($request->employee_id, $request->action);
    }

    // Time window validation
    private function validateTimeWindow($action): ?string
    {
        $hour   = (int) now()->format('H');
        $minute = (int) now()->format('i');
        $time   = $hour * 60 + $minute;

        $windows = [
            'time-in'   => ['from' =>  5 * 60, 'to' => 11 * 60 + 59, 'label' => '5:00 AM – 11:59 AM'],
            'break-out' => ['from' => 12 * 60, 'to' => 12 * 60 + 59, 'label' => '12:00 PM – 12:59 PM'],
            'break-in'  => ['from' => 13 * 60, 'to' => 16 * 60 + 59, 'label' => '1:00 PM – 4:59 PM'],
            'time-out'  => ['from' => 13 * 60, 'to' => 23 * 60 + 59, 'label' => '1:00 PM onwards'],
        ];

        if (!isset($windows[$action])) return null;

        $w = $windows[$action];
        if ($time < $w['from']) {
            return "Too early — {$w['label']} only.";
        }
        if ($time > $w['to']) {
            return "Too late — {$w['label']} only.";
        }

        return null; // valid
    }

    // Shared attendance logging logic
    private function logAttendance($employeeId, $requestedAction = null)
    {
        $employee = Employee::findOrFail($employeeId);
        $today    = Carbon::today()->toDateString();
        $log      = TimeLog::firstOrCreate([
            'employee_id' => $employee->id,
            'log_date'    => $today,
        ]);

        $now    = Carbon::now();
        $action = null;

        // Map requested action to field
        $actionMap = [
            'time-in'   => 'time_in',
            'break-out' => 'break_out',
            'break-in'  => 'break_in',
            'time-out'  => 'time_out',
        ];

        if ($requestedAction && isset($actionMap[$requestedAction])) {
            // Use the specific action requested
            $field = $actionMap[$requestedAction];

            if ($log->$field) {
                return response()->json([
                    'success' => false,
                    'message' => ucwords(str_replace('-', ' ', $requestedAction)) . ' already recorded today.',
                ]);
            }

            // Validate time window
            $timeError = $this->validateTimeWindow($requestedAction);
            if ($timeError) {
                return response()->json(['success' => false, 'message' => $timeError]);
            }

            switch ($requestedAction) {
                case 'time-in':
                    $log->time_in = $now;
                    $assignment   = ShiftAssignment::with('shift')
                        ->where('employee_id', $employee->id)
                        ->where('work_date', $today)->first();
                    if ($assignment) {
                        $shiftStart        = Carbon::parse($today . ' ' . $assignment->shift->start_time);
                        $lateMinutes       = max(0, $shiftStart->diffInMinutes($now, false));
                        $log->is_late      = $lateMinutes > 0;
                        $log->late_minutes = (int) $lateMinutes;
                    }
                    $action = 'Time In';
                    break;
                case 'break-out':
                    $log->break_out = $now;
                    $action = 'Break Out';
                    break;
                case 'break-in':
                    $log->break_in = $now;
                    $action = 'Break In';
                    break;
                case 'time-out':
                    $log->time_out = $now;
                    $assignment    = ShiftAssignment::with('shift')
                        ->where('employee_id', $employee->id)
                        ->where('work_date', $today)->first();
                    $shiftStart = $assignment
                        ? Carbon::parse($today . ' ' . $assignment->shift->start_time)->format('H:i')
                        : '08:00';
                    $log->computeHours($shiftStart);
                    $action = 'Time Out';
                    break;
            }

        } else {
            // Auto-detect action (fallback for PIN clock)
            if (!$log->time_in) {
                $log->time_in = $now;
                $assignment   = ShiftAssignment::with('shift')
                    ->where('employee_id', $employee->id)
                    ->where('work_date', $today)->first();
                if ($assignment) {
                    $shiftStart        = Carbon::parse($today . ' ' . $assignment->shift->start_time);
                    $lateMinutes       = max(0, $shiftStart->diffInMinutes($now, false));
                    $log->is_late      = $lateMinutes > 0;
                    $log->late_minutes = (int) $lateMinutes;
                }
                $action = 'Time In';
            } elseif (!$log->break_out) {
                $log->break_out = $now;
                $action = 'Break Out';
            } elseif (!$log->break_in) {
                $log->break_in = $now;
                $action = 'Break In';
            } elseif (!$log->time_out) {
                $log->time_out = $now;
                $assignment    = ShiftAssignment::with('shift')
                    ->where('employee_id', $employee->id)
                    ->where('work_date', $today)->first();
                $shiftStart = $assignment
                    ? Carbon::parse($today . ' ' . $assignment->shift->start_time)->format('H:i')
                    : '08:00';
                $log->computeHours($shiftStart);
                $action = 'Time Out';
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'All actions already completed for today.',
                ]);
            }
        }

        $log->save();

        return response()->json([
            'success'  => true,
            'action'   => $action,
            'employee' => $employee->full_name,
            'time'     => $now->format('h:i A'),
            'message'  => "{$action} recorded for {$employee->full_name}",
        ]);
    }
}