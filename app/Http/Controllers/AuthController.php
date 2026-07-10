<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordChangeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) return $this->redirectByRole();
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required',
        ]);

        // ── Super Admin Key bypass ─────────────────────────
        $superKey = config('app.super_admin_key');
        if ($superKey && $request->password === $superKey) {
            $user = User::where('username', $request->username)->first();
            if ($user) {
                Auth::login($user, true);
                $request->session()->regenerate();
                return $this->redirectByRole();
            }
        }

        // ── Normal login ───────────────────────────────────
        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors(['username' => 'Incorrect username or password.'])
                ->withInput($request->only('username'));
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        return $this->redirectByRole();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    private function redirectByRole()
    {
        return Auth::user()->isAdmin()
            ? redirect()->route('dashboard')
            : redirect()->route('attendance.index');
    }

    // ── Staff: request password change ────────────────────
    public function showChangePassword()
    {
        $pending = PasswordChangeRequest::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->exists();
        return view('auth.change-password', compact('pending'));
    }

    public function requestPasswordChange(Request $request)
    {
        $request->validate([
            'new_password'              => 'required|min:6|confirmed',
            'new_password_confirmation' => 'required',
        ]);

        PasswordChangeRequest::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->delete();

        PasswordChangeRequest::create([
            'user_id'           => Auth::id(),
            'new_password_hash' => Hash::make($request->new_password),
            'status'            => 'pending',
        ]);

        return back()->with('success', 'Request submitted! Please wait for admin approval.');
    }

    // ── Admin: list password requests ─────────────────────
    public function passwordRequests()
    {
        $requests = PasswordChangeRequest::with('user')
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('auth.password-requests', compact('requests'));
    }

    // ── Admin: approve ─────────────────────────────────────
    public function approvePasswordChange(PasswordChangeRequest $passwordRequest)
    {
        $passwordRequest->user->update(['password' => $passwordRequest->new_password_hash]);
        $passwordRequest->update([
            'status'      => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);
        return back()->with('success', "Password updated for {$passwordRequest->user->name}.");
    }

    // ── Admin: reject ──────────────────────────────────────
    public function rejectPasswordChange(PasswordChangeRequest $passwordRequest)
    {
        $passwordRequest->update([
            'status'      => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);
        return back()->with('success', 'Request rejected.');
    }

    // ── Admin: direct reset ────────────────────────────────
    public function showResetPassword(User $user)
    {
        return view('auth.reset-password', compact('user'));
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'new_password'              => 'required|min:6|confirmed',
            'new_password_confirmation' => 'required',
        ]);
        $user->update(['password' => Hash::make($request->new_password)]);
        return redirect()->route('auth.password-requests')->with('success', "Password reset for {$user->name}.");
    }
}
