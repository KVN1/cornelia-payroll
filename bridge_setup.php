<?php

// ══════════════════════════════════════════════════════════════
// ADD THESE ROUTES to your routes/api.php
// ══════════════════════════════════════════════════════════════

use App\Http\Controllers\BridgeController;

Route::middleware('bridge.token')->prefix('bridge')->group(function () {
    Route::get('employees',    [BridgeController::class, 'employees']);
    Route::post('enroll',      [BridgeController::class, 'enroll']);
    Route::post('clock',       [BridgeController::class, 'clock']);
});


// ══════════════════════════════════════════════════════════════
// CREATE THIS FILE: app/Http/Controllers/BridgeController.php
// ══════════════════════════════════════════════════════════════

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
            'biometric_id' => 'required|string|max:50',
            'finger_data'  => 'required|string',
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        $employee->update([
            'biometric_id'          => $request->biometric_id,
            'webauthn_credential_id' => $request->finger_data,
            'biometric_enrolled'    => true,
        ]);

        return response()->json([
            'success'  => true,
            'message'  => "Fingerprint enrolled for {$employee->full_name}",
            'employee' => $employee->full_name,
        ]);
    }

    // POST /api/bridge/clock
    public function clock(Request $request)
    {
        $request->validate([
            'finger_data' => 'required|string',
            'timestamp'   => 'nullable|string',
        ]);

        // Find employee by stored finger_data (webauthn_credential_id)
        $employee = Employee::where('webauthn_credential_id', 'LIKE',
            '%' . substr($request->finger_data, 0, 20) . '%')
            ->where('status', 'active')
            ->first();

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'No matching fingerprint found. Please enroll first.',
            ], 404);
        }

        // Auto-detect action (same logic as PIN clock)
        $today = Carbon::today()->toDateString();
        $log   = TimeLog::firstOrCreate([
            'employee_id' => $employee->id,
            'log_date'    => $today,
        ]);

        $now    = Carbon::now();
        $action = null;

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


// ══════════════════════════════════════════════════════════════
// CREATE THIS FILE: app/Http/Middleware/BridgeTokenMiddleware.php
// ══════════════════════════════════════════════════════════════

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BridgeTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('X-Bridge-Token');
        if ($token !== config('app.bridge_token', 'cornelia-bridge-2026')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $next($request);
    }
}


// ══════════════════════════════════════════════════════════════
// ADD TO: bootstrap/app.php (Laravel 12) — inside withMiddleware()
// ══════════════════════════════════════════════════════════════
//
//    ->withMiddleware(function (Middleware $middleware) {
//        $middleware->alias([
//            'bridge.token' => \App\Http\Middleware\BridgeTokenMiddleware::class,
//        ]);
//    })
