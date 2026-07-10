<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index()
    {
        $requests = LeaveRequest::with(['employee' => function($query) {
                $query->withTrashed();
            }, 'leaveType'])
            ->orderByDesc('created_at')
            ->paginate(15);
        return view('leaves.index', compact('requests'));
    }

    public function create()
    {
        $employees  = Employee::where('status', 'active')->orderBy('last_name')->get();
        $leaveTypes = LeaveType::all();
        return view('leaves.create', compact('employees', 'leaveTypes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id'   => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'date_from'     => 'required|date',
            'date_to'       => 'required|date|after_or_equal:date_from',
            'reason'        => 'nullable|string',
        ]);

        $from = Carbon::parse($data['date_from']);
        $to   = Carbon::parse($data['date_to']);
        $data['total_days'] = $from->diffInWeekdays($to) + 1;

        LeaveRequest::create($data);
        return redirect()->route('leaves.index')->with('success', 'Leave request submitted.');
    }

    public function approve(LeaveRequest $leave)
    {
        $leave->update([
            'status'      => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Leave approved.']);
        }
        return back()->with('success', 'Leave approved.');
    }

    public function reject(LeaveRequest $leave)
    {
        $leave->update(['status' => 'rejected']);
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Leave rejected.']);
        }
        return back()->with('success', 'Leave rejected.');
    }
}
