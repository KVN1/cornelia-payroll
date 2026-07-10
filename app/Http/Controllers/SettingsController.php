<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordChangeRequest;
use App\Models\DeductionSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function index()
    {
        $users            = User::with(['employee' => function($query) {
                $query->withTrashed();
            }])->orderBy('name')->get();
        $passwordRequests = PasswordChangeRequest::with('user', 'reviewer')
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->orderByDesc('created_at')
            ->get();
        $pendingCount     = $passwordRequests->where('status', 'pending')->count();
        $myPendingRequest = PasswordChangeRequest::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->exists();
        $archivedEmployees = \App\Models\Employee::onlyTrashed()
            ->with('position.department')
            ->orderByDesc('deleted_at')
            ->get();
        $archivedCount     = $archivedEmployees->count();
        $deductionSettings = DeductionSetting::orderBy('id')->get();

        return view('settings', compact(
            'users', 'passwordRequests', 'pendingCount',
            'myPendingRequest', 'archivedEmployees', 'archivedCount',
            'deductionSettings'
        ));
    }

    // ── Update Deductions ─────────────────────────────────
    public function updateDeductions(Request $request)
    {
        $deductions = $request->input('deductions', []);

        foreach ($deductions as $key => $data) {
            DeductionSetting::where('key', $key)->update([
                'value'     => isset($data['value']) ? (float) $data['value'] : 0,
                'is_active' => isset($data['is_active']) && $data['is_active'] == '1',
            ]);
        }

        return back()->with('success', 'Deduction settings updated successfully.');
    }

    // ── Create User ───────────────────────────────────────
    public function storeUser(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:150',
            'username'    => 'required|string|max:50|unique:users',
            'password'    => 'required|min:6',
            'role'        => 'required|in:admin,hr,manager,viewer',
            'employee_id' => 'nullable|exists:employees,id',
        ]);

        User::create([
            'name'        => $data['name'],
            'username'    => $data['username'],
            'password'    => Hash::make($data['password']),
            'role'        => $data['role'],
            'employee_id' => $data['employee_id'] ?? null,
        ]);

        return back()->with('success', "User {$data['name']} created successfully.");
    }

    // ── Update User ───────────────────────────────────────
    public function updateUser(Request $request, User $user)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:150',
            'username'    => ['required','string','max:50', Rule::unique('users')->ignore($user->id)],
            'role'        => 'required|in:admin,hr,manager,viewer',
            'employee_id' => 'nullable|exists:employees,id',
        ]);

        $user->update([
            'name'        => $data['name'],
            'username'    => $data['username'],
            'role'        => $data['role'],
            'employee_id' => $data['employee_id'] ?? null,
        ]);

        return back()->with('success', "User {$user->name} updated.");
    }

    // ── Delete User ───────────────────────────────────────
    public function deleteUser(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        $name = $user->name;
        $user->delete();
        return back()->with('success', "{$name} deleted.");
    }

    // ── Reset Password (admin direct) ─────────────────────
    public function resetUserPassword(Request $request, User $user)
    {
        $request->validate([
            'new_password'              => 'required|min:6|confirmed',
            'new_password_confirmation' => 'required',
        ]);

        $user->update(['password' => Hash::make($request->new_password)]);
        return back()->with('success', "Password reset for {$user->name}.");
    }

    // ── Update own username ───────────────────────────────
    public function updateUsername(Request $request)
    {
        $request->validate([
            'username' => ['required','string','max:50', Rule::unique('users')->ignore(Auth::id())],
        ]);

        Auth::user()->update(['username' => $request->username]);
        return back()->with('success', 'Username updated successfully.');
    }

    // ── Admin direct password change ──────────────────────
    public function changePasswordDirect(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        Auth::user()->update(['password' => Hash::make($request->new_password)]);
        return back()->with('success', 'Password changed successfully.');
    }
}
