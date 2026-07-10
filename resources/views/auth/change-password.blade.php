@extends('layouts.app')
@section('page-title', 'Change Password')
@section('content')

<div class="flex items-center gap-3 mb-4">
    <a href="{{ route('attendance.index') }}" class="btn btn-outline btn-sm">← Back</a>
    <div>
        <div class="page-title">Change Password</div>
        <div class="page-sub">Submit a request — your admin will approve it.</div>
    </div>
</div>

<div style="max-width:480px">
    @if($pending)
    <div style="background:#fff8e6;color:var(--orange);border:1px solid #f5d47a;border-radius:10px;padding:14px 18px;margin-bottom:20px;display:flex;align-items:center;gap:10px">
        <span style="font-size:18px">⏳</span>
        <div>
            <div style="font-weight:600;font-size:13.5px">Request pending</div>
            <div style="font-size:12.5px;margin-top:2px">Your password change request is waiting for admin approval.</div>
        </div>
    </div>
    @endif

    <div class="card">
        <div class="card-header">
            <span class="card-title">Request Password Change</span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('auth.request-password-change') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">New Password *</label>
                    <input type="password" name="new_password" class="form-control" placeholder="Min. 6 characters" required>
                    @error('new_password')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm New Password *</label>
                    <input type="password" name="new_password_confirmation" class="form-control" placeholder="Re-enter new password" required>
                </div>
                <div style="background:#faf8f5;border-radius:8px;padding:12px 14px;margin-bottom:18px;font-size:12.5px;color:var(--text2);border:1px solid var(--border)">
                    ℹ Your request will be sent to the admin for approval. Your current password stays active until approved.
                </div>
                <button type="submit" class="btn btn-primary">Submit Request</button>
            </form>
        </div>
    </div>
</div>
@endsection
