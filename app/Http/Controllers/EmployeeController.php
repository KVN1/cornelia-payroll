<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::with('position.department')
            ->when($request->search, fn($q, $s) =>
                $q->where('first_name', 'like', "%$s%")
                  ->orWhere('last_name',   'like', "%$s%")
                  ->orWhere('employee_no', 'like', "%$s%")
            )
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->orderBy('last_name')
            ->paginate(15);

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $positions = Position::with('department')->get();
        return view('employees.create', compact('positions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            // Core
            'employee_no'     => 'required|unique:employees',
            'first_name'      => 'required|string|max:80',
            'last_name'       => 'required|string|max:80',
            'middle_name'     => 'nullable|string|max:80',
            'suffix'          => 'nullable|string|max:10',
            'date_of_birth'   => 'nullable|date',
            'gender'          => 'nullable|in:male,female',
            'civil_status'    => 'nullable|in:single,married,widowed,separated',
            'email'           => 'nullable|email|unique:employees',
            'phone'           => 'nullable|string|max:20',
            'address'         => 'nullable|string|max:255',
            'emergency_contact_name'   => 'nullable|string|max:100',
            'emergency_contact_number' => 'nullable|string|max:20',
            // Employment
            'position_id'     => 'required|exists:positions,id',
            'employment_type' => 'required|in:full_time,part_time,contractual',
            'hire_date'       => 'required|date',
            'daily_rate'      => 'required|numeric|min:0',
            // Government IDs
            'sss_no'          => 'nullable|string|max:20',
            'philhealth_no'   => 'nullable|string|max:20',
            'pagibig_no'      => 'nullable|string|max:20',
            'tin_no'          => 'nullable|string|max:20',
            // Biometrics
            'biometric_id'           => 'nullable|string|max:50',
            'biometric_pin'          => 'nullable|string|max:6',
            'webauthn_credential_id' => 'nullable|string',
            'webauthn_public_key'    => 'nullable|string',
            'biometric_enrolled'     => 'nullable|boolean',
            // User account
            'username'        => 'required|string|max:50|unique:users,username',
            'password'        => 'required|string|min:6',
        ]);

        // Separate user fields from employee fields
        $username = $data['username'];
        $password = $data['password'];
        unset($data['username'], $data['password']);

        // Create employee
        $employee = Employee::create($data);

        // Create user account linked to employee
        User::create([
            'name'        => $employee->full_name,
            'username'    => $username,
            'email'       => $employee->email ?? $username . '@cornelia.local',
            'password'    => Hash::make($password),
            'role'        => 'employee',
            'employee_id' => $employee->id,
        ]);

        return redirect()->route('employees.index')
            ->with('success', "Employee {$employee->full_name} added and account created successfully.");
    }

    public function show(Employee $employee)
    {
        $employee->load('position.department', 'timeLogs', 'leaveRequests.leaveType');
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $positions = Position::with('department')->get();
        return view('employees.edit', compact('employee', 'positions'));
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'first_name'      => 'required|string|max:80',
            'last_name'       => 'required|string|max:80',
            'middle_name'     => 'nullable|string|max:80',
            'suffix'          => 'nullable|string|max:10',
            'date_of_birth'   => 'nullable|date',
            'gender'          => 'nullable|in:male,female',
            'civil_status'    => 'nullable|in:single,married,widowed,separated',
            'email'           => ['nullable', 'email', Rule::unique('employees')->ignore($employee->id)],
            'phone'           => 'nullable|string|max:20',
            'address'         => 'nullable|string|max:255',
            'emergency_contact_name'   => 'nullable|string|max:100',
            'emergency_contact_number' => 'nullable|string|max:20',
            'position_id'     => 'required|exists:positions,id',
            'employment_type' => 'required|in:full_time,part_time,contractual',
            'daily_rate'      => 'required|numeric|min:0',
            'status'          => 'required|in:active,inactive,terminated',
            'sss_no'          => 'nullable|string|max:20',
            'philhealth_no'   => 'nullable|string|max:20',
            'pagibig_no'      => 'nullable|string|max:20',
            'tin_no'          => 'nullable|string|max:20',
        ]);

        $employee->update($data);

        // Update linked user name if exists
        $user = User::where('employee_id', $employee->id)->first();
        if ($user) {
            $user->update(['name' => $employee->full_name]);
        }

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Employee updated.');
    }

    public function archive(Employee $employee)
    {
        $employee->delete();

        // Deactivate user account
        User::where('employee_id', $employee->id)->update(['role' => 'inactive']);

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => "{$employee->full_name} has been archived."]);
        }
        return redirect()->route('employees.index')
            ->with('success', "{$employee->full_name} has been archived.");
    }

    public function restore($id)
    {
        $employee = Employee::withTrashed()->findOrFail($id);
        $employee->restore();

        // Reactivate user account
        User::where('employee_id', $employee->id)->update(['role' => 'employee']);

        return redirect()->route('settings', ['#tab-archived'])
            ->with('success', "{$employee->full_name} has been restored.");
    }
}