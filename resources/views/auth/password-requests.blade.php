@extends('layouts.app')
@section('page-title', 'Password Requests')
@section('content')

<div class="flex items-center justify-between mb-4">
    <div>
        <div class="page-title">Password Change Requests</div>
        <div class="page-sub">Review and approve staff password change requests.</div>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Requested</th>
                    <th>Status</th>
                    <th>Reviewed By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                <tr data-req="{{ $req->id }}">
                    <td>
                        <strong>{{ $req->user->name }}</strong><br>
                        <span class="text-muted">{{ $req->user->username }}</span>
                    </td>
                    <td><span style="text-transform:capitalize">{{ $req->user->role }}</span></td>
                    <td>{{ $req->created_at->format('M d, Y h:i A') }}</td>
                    <td><span class="badge badge-{{ $req->status }}">{{ ucfirst($req->status) }}</span></td>
                    <td>
                        @if($req->reviewer)
                            {{ $req->reviewer->name }}<br>
                            <span class="text-muted">{{ $req->reviewed_at->format('M d h:i A') }}</span>
                        @else —
                        @endif
                    </td>
                    <td>
                        @if($req->status === 'pending')
                        <div class="flex gap-2">
                            <button onclick="handlePwRequest('{{ route('auth.approve-password', $req) }}', {{ $req->id }}, 'approve')"
                                    class="btn btn-success btn-sm">✓ Approve</button>
                            <button onclick="handlePwRequest('{{ route('auth.reject-password', $req) }}', {{ $req->id }}, 'reject')"
                                    class="btn btn-danger btn-sm">✗ Reject</button>
                        </div>
                        @else
                        <span class="text-muted">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:40px">
                        <div style="width:40px;height:40px;background:#f0ede8;border-radius:10px;display:flex;align-items:center;justify-content:center;margin:0 auto 10px">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--text2)" stroke-width="1.8"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </div>
                        <div class="text-muted">No password change requests yet.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:0 20px">{{ $requests->links() }}</div>
</div>

{{-- Direct Reset Section --}}
<div class="page-title" style="margin-top:32px;margin-bottom:16px;font-size:18px">Direct Password Reset</div>
<div class="card" style="max-width:480px">
    <div class="card-header">
        <span class="card-title">Reset Any User's Password</span>
        <span style="font-size:11px;color:var(--text2)">Admin only</span>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label class="form-label">Select User</label>
            <select id="reset-user-select" class="form-control" onchange="goToReset(this)">
                <option value="">Select a user…</option>
                @foreach(\App\Models\User::orderBy('name')->get() as $user)
                <option value="{{ route('auth.reset-password-form', $user) }}">
                    {{ $user->name }} ({{ $user->username }}) — {{ $user->role }}
                </option>
                @endforeach
            </select>
        </div>
        <div style="font-size:12px;color:var(--text2)">
            ℹ This immediately resets the password without waiting for a request.
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

async function handlePwRequest(url, id, action) {
    try {
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        });
        if (res.ok) {
            const row = document.querySelector(`tr[data-req="${id}"]`);
            if (row) {
                const badge = row.querySelector('.badge');
                if (badge) {
                    badge.className = 'badge badge-' + (action === 'approve' ? 'approved' : 'rejected');
                    badge.textContent = action === 'approve' ? 'Approved' : 'Rejected';
                }
                row.querySelector('td:last-child').innerHTML = '<span class="text-muted">—</span>';
            }
        }
    } catch(e) {
        alert('Something went wrong. Please try again.');
    }
}

function goToReset(sel) {
    if (sel.value) window.location.href = sel.value;
}
</script>
@endpush
