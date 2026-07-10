@extends('layouts.app')
@section('page-title', 'Reset Password')
@section('content')

<div class="flex items-center gap-3 mb-4">
    <a href="{{ route('auth.password-requests') }}" class="btn btn-outline btn-sm">← Back</a>
    <div>
        <div class="page-title">Reset Password</div>
        <div class="page-sub">Directly reset password for {{ $user->name }} (@{{ $user->username }})</div>
    </div>
</div>

<div class="card" style="max-width:440px">
    <div class="card-body">
        <div style="display:flex;align-items:center;gap:12px;padding:14px 16px;background:#faf8f5;border-radius:10px;border:1px solid var(--border);margin-bottom:22px">
            <div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:#fff;flex-shrink:0">
                {{ strtoupper(substr($user->name,0,2)) }}
            </div>
            <div>
                <div style="font-weight:700;font-size:14px">{{ $user->name }}</div>
                <div style="font-size:12px;color:var(--text2)">@{{ $user->username }} · {{ ucfirst($user->role) }}</div>
            </div>
        </div>

        <form method="POST" action="{{ route('auth.reset-password', $user) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">New Password *</label>
                <input type="password" name="new_password" class="form-control" placeholder="Min. 6 characters" required>
                @error('new_password')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Confirm Password *</label>
                <input type="password" name="new_password_confirmation" class="form-control" placeholder="Re-enter password" required>
            </div>
            <div class="flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary"
                        onclick="return confirm('Reset password for {{ $user->name }}?')">
                    Reset Password
                </button>
                <a href="{{ route('auth.password-requests') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
