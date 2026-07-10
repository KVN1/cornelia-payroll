@extends('layouts.app')
@section('page-title', 'Edit Employee')
@section('content')

<div class="flex items-center gap-3 mb-4">
    <a href="{{ route('employees.show', $employee) }}" class="btn btn-outline btn-sm">← Back</a>
    <div>
        <div class="section-title">Edit Employee</div>
        <div class="section-sub">{{ $employee->full_name }} — {{ $employee->employee_no }}</div>
    </div>
</div>

<div class="card" style="max-width:760px">
    <div class="card-body">
        <form method="POST" action="{{ route('employees.update', $employee) }}">
            @csrf
            @method('PUT')

            <div style="font-family:'Playfair Display',serif;font-size:13px;font-weight:600;color:var(--mocha);margin-bottom:14px;padding-bottom:8px;border-bottom:1px solid var(--foam)">
                Personal Information
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Employment Type *</label>
                    <select name="employment_type" class="form-control" required>
                        <option value="full_time"   {{ $employee->employment_type=='full_time'   ?'selected':'' }}>Full Time</option>
                        <option value="part_time"   {{ $employee->employment_type=='part_time'   ?'selected':'' }}>Part Time</option>
                        <option value="contractual" {{ $employee->employment_type=='contractual' ?'selected':'' }}>Contractual</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-control" required>
                        <option value="active"     {{ $employee->status=='active'     ?'selected':'' }}>Active</option>
                        <option value="inactive"   {{ $employee->status=='inactive'   ?'selected':'' }}>Inactive</option>
                        <option value="terminated" {{ $employee->status=='terminated' ?'selected':'' }}>Terminated</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">First Name *</label>
                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $employee->first_name) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Last Name *</label>
                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $employee->last_name) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Middle Name</label>
                    <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name', $employee->middle_name) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Position *</label>
                    <select name="position_id" class="form-control" required>
                        @foreach($positions as $pos)
                        <option value="{{ $pos->id }}" {{ $employee->position_id==$pos->id?'selected':'' }}>
                            {{ $pos->department->name }} — {{ $pos->title }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $employee->email) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $employee->phone) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Daily Rate (₱) *</label>
                    <input type="number" step="0.01" name="daily_rate" class="form-control" value="{{ old('daily_rate', $employee->daily_rate) }}" required>
                </div>
            </div>

            <div style="font-family:'Playfair Display',serif;font-size:13px;font-weight:600;color:var(--mocha);margin:20px 0 14px;padding-bottom:8px;border-bottom:1px solid var(--foam)">
                Government IDs
            </div>
            <div class="form-grid-3">
                <div class="form-group">
                    <label class="form-label">SSS No.</label>
                    <input type="text" name="sss_no" class="form-control" value="{{ old('sss_no', $employee->sss_no) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">PhilHealth No.</label>
                    <input type="text" name="philhealth_no" class="form-control" value="{{ old('philhealth_no', $employee->philhealth_no) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Pag-IBIG No.</label>
                    <input type="text" name="pagibig_no" class="form-control" value="{{ old('pagibig_no', $employee->pagibig_no) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">TIN No.</label>
                    <input type="text" name="tin_no" class="form-control" value="{{ old('tin_no', $employee->tin_no) }}">
                </div>
            </div>

            <div class="flex gap-2 mt-6">
                <button type="submit" class="btn btn-primary">Update Employee</button>
                <a href="{{ route('employees.show', $employee) }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
