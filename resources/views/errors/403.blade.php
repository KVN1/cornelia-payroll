@extends('layouts.app')
@section('page-title', 'Access Denied')
@section('content')

<div style="text-align:center;padding:80px 20px">
    <div style="font-size:64px;margin-bottom:20px">🔒</div>
    <div style="font-size:24px;font-weight:700;color:var(--text);margin-bottom:8px">Access Denied</div>
    <div style="font-size:14px;color:var(--text2);margin-bottom:28px;max-width:400px;margin-left:auto;margin-right:auto">
        You don't have permission to view this page. Please contact your administrator if you think this is a mistake.
    </div>
    <a href="{{ route('attendance.index') }}" class="btn btn-primary">← Go to Attendance</a>
</div>

@endsection
