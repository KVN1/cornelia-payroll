@extends('layouts.app')
@section('page-title', 'Add Employee')
@section('content')

<div class="flex items-center gap-3 mb-4">
    <a href="{{ route('employees.index') }}" class="btn btn-outline btn-sm">← Back</a>
    <div>
        <div class="page-title">Add New Employee</div>
        <div class="page-sub">Fill in the details below to register a new employee.</div>
    </div>
</div>

<form method="POST" action="{{ route('employees.store') }}" id="employeeForm">
@csrf

<div style="display:grid;grid-template-columns:1fr 400px;gap:20px;align-items:start">

    {{-- ── LEFT COLUMN ── --}}
    <div style="display:flex;flex-direction:column;gap:16px">

        {{-- Personal Information --}}
        <div class="card">
            <div class="card-header"><span class="card-title">👤 Personal Information</span></div>
            <div class="card-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Last Name *</label>
                        <input type="text" name="last_name" id="last_name" class="form-control"
                               value="{{ old('last_name') }}" required placeholder="e.g. Dela Cruz">
                        @error('last_name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">First Name *</label>
                        <input type="text" name="first_name" id="first_name" class="form-control"
                               value="{{ old('first_name') }}" required placeholder="e.g. Juan">
                        @error('first_name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="middle_name" class="form-control"
                               value="{{ old('middle_name') }}" placeholder="Optional">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Suffix</label>
                        <select name="suffix" class="form-control">
                            <option value="">None</option>
                            <option value="Jr."  {{ old('suffix')=='Jr.'  ?'selected':'' }}>Jr.</option>
                            <option value="Sr."  {{ old('suffix')=='Sr.'  ?'selected':'' }}>Sr.</option>
                            <option value="II"   {{ old('suffix')=='II'   ?'selected':'' }}>II</option>
                            <option value="III"  {{ old('suffix')=='III'  ?'selected':'' }}>III</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="date_of_birth" class="form-control"
                               value="{{ old('date_of_birth') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-control">
                            <option value="">Select…</option>
                            <option value="male"   {{ old('gender')=='male'   ?'selected':'' }}>Male</option>
                            <option value="female" {{ old('gender')=='female' ?'selected':'' }}>Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Civil Status</label>
                        <select name="civil_status" class="form-control">
                            <option value="">Select…</option>
                            <option value="single"   {{ old('civil_status')=='single'   ?'selected':'' }}>Single</option>
                            <option value="married"  {{ old('civil_status')=='married'  ?'selected':'' }}>Married</option>
                            <option value="widowed"  {{ old('civil_status')=='widowed'  ?'selected':'' }}>Widowed</option>
                            <option value="separated"{{ old('civil_status')=='separated'?'selected':'' }}>Separated</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mobile No.</label>
                        <div style="display:flex;align-items:center">
                            <span style="padding:9px 10px;background:#f0ede8;border:1px solid var(--border);border-right:none;border-radius:var(--radius-sm) 0 0 var(--radius-sm);font-size:13px;color:var(--text2);white-space:nowrap">🇵🇭 +63</span>
                            <input type="text" name="phone" class="form-control"
                                   value="{{ old('phone') }}" placeholder="9XX XXX XXXX"
                                   maxlength="10" oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                                   style="border-radius:0 var(--radius-sm) var(--radius-sm) 0;border-left:none">
                        </div>
                        <div class="form-hint">Digits only, starts with 9</div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Home Address</label>
                    <input type="text" name="address" class="form-control"
                           value="{{ old('address') }}" placeholder="Street, Barangay, City, Province">
                </div>
                <div class="form-group">
                    <label class="form-label">Emergency Contact Name</label>
                    <input type="text" name="emergency_contact_name" class="form-control"
                           value="{{ old('emergency_contact_name') }}" placeholder="Full name">
                </div>
                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label">Emergency Contact Number</label>
                    <div style="display:flex;align-items:center">
                        <span style="padding:9px 10px;background:#f0ede8;border:1px solid var(--border);border-right:none;border-radius:var(--radius-sm) 0 0 var(--radius-sm);font-size:13px;color:var(--text2);white-space:nowrap">🇵🇭 +63</span>
                        <input type="text" name="emergency_contact_number" class="form-control"
                               value="{{ old('emergency_contact_number') }}" placeholder="9XX XXX XXXX"
                               maxlength="10" oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                               style="border-radius:0 var(--radius-sm) var(--radius-sm) 0;border-left:none">
                    </div>
                </div>
            </div>
        </div>

        {{-- Employment Details --}}
        <div class="card">
            <div class="card-header"><span class="card-title">💼 Employment Details</span></div>
            <div class="card-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Employee No. *</label>
                        <input type="text" name="employee_no" id="employee_no" class="form-control"
                               value="{{ old('employee_no','CSB-') }}" required>
                        @error('employee_no')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Employment Type *</label>
                        <select name="employment_type" class="form-control" required>
                            <option value="full_time"   {{ old('employment_type')=='full_time'   ?'selected':'' }}>Full Time</option>
                            <option value="part_time"   {{ old('employment_type')=='part_time'   ?'selected':'' }}>Part Time</option>
                            <option value="contractual" {{ old('employment_type')=='contractual' ?'selected':'' }}>Contractual</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Position *</label>
                        <select name="position_id" class="form-control" required>
                            <option value="">Select position…</option>
                            @foreach($positions as $pos)
                            <option value="{{ $pos->id }}" {{ old('position_id')==$pos->id?'selected':'' }}>
                                {{ $pos->department->name }} — {{ $pos->title }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hire Date *</label>
                        <input type="date" name="hire_date" class="form-control"
                               value="{{ old('hire_date') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pay Type *</label>
                        <select id="pay_type" name="pay_type" class="form-control">
                            <option value="monthly"      {{ old('pay_type','monthly')=='monthly'     ?'selected':'' }}>Monthly Pay</option>
                            <option value="semi_monthly" {{ old('pay_type')=='semi_monthly'          ?'selected':'' }}>Semi-Monthly</option>
                            <option value="daily"        {{ old('pay_type')=='daily'                 ?'selected':'' }}>Daily Rate</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" id="pay-label">Monthly Pay (₱) *</label>
                        <input type="number" step="0.01" id="pay_amount" name="pay_amount"
                               class="form-control" value="{{ old('pay_amount') }}"
                               placeholder="e.g. 16000" required>
                        <div class="form-hint" id="pay-hint">÷ 26 working days = daily rate</div>
                    </div>
                </div>
                <div style="background:#faf8f5;border-radius:8px;padding:14px;border:1px solid var(--border);margin-top:4px">
                    <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.8px;color:var(--text2);font-weight:600;margin-bottom:4px">Computed Daily Rate</div>
                    <div style="font-size:28px;font-weight:700;color:var(--accent);line-height:1" id="daily-display">₱0.00</div>
                    <div style="font-size:11.5px;color:var(--text2);margin-top:3px">per working day</div>
                    <input type="hidden" name="daily_rate" id="daily_rate" value="{{ old('daily_rate','0') }}">
                </div>
            </div>
        </div>

        {{-- Government IDs --}}
        <div class="card">
            <div class="card-header"><span class="card-title">🏛 Government IDs</span></div>
            <div class="card-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">SSS No.</label>
                        <input type="text" name="sss_no" class="form-control"
                               value="{{ old('sss_no') }}" placeholder="XX-XXXXXXX-X"
                               oninput="formatGovId(this,'sss')" maxlength="12">
                        <div class="form-hint">Format: XX-XXXXXXX-X</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">PhilHealth No.</label>
                        <input type="text" name="philhealth_no" class="form-control"
                               value="{{ old('philhealth_no') }}" placeholder="XXXX-XXXXXXX-X"
                               oninput="formatGovId(this,'philhealth')" maxlength="14">
                        <div class="form-hint">Format: XXXX-XXXXXXX-X</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pag-IBIG No. (HDMF)</label>
                        <input type="text" name="pagibig_no" class="form-control"
                               value="{{ old('pagibig_no') }}" placeholder="XXXX-XXXX-XXXX"
                               oninput="formatGovId(this,'pagibig')" maxlength="14">
                        <div class="form-hint">Format: XXXX-XXXX-XXXX</div>
                    </div>
                    <div class="form-group" style="margin-bottom:0">
                        <label class="form-label">TIN No.</label>
                        <input type="text" name="tin_no" class="form-control"
                               value="{{ old('tin_no') }}" placeholder="XXX-XXX-XXX"
                               oninput="formatGovId(this,'tin')" maxlength="15">
                        <div class="form-hint">Format: XXX-XXX-XXX</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Login Account --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">🔐 Login Account</span>
                <span style="font-size:11px;color:var(--text2)">Employee uses this to view their DTR</span>
            </div>
            <div class="card-body">
                <div style="background:var(--blue-bg);border-radius:8px;padding:10px 14px;margin-bottom:14px;font-size:12px;color:var(--blue)">
                    ℹ️ The employee logs in with these credentials from any device on the same network to view their DTR.
                </div>
                <div class="form-grid">
                    <div class="form-group" style="margin-bottom:0">
                        <label class="form-label">Username *</label>
                        <input type="text" name="username" class="form-control"
                               value="{{ old('username') }}" placeholder="e.g. jdelacruz"
                               oninput="this.value=this.value.replace(/[^a-z0-9._]/g,'').toLowerCase()"
                               required>
                        <div class="form-hint">Lowercase, numbers, dots, underscores only</div>
                        @error('username')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group" style="margin-bottom:0">
                        <label class="form-label">Temporary Password *</label>
                        <div style="display:flex;gap:8px">
                            <input type="text" name="password" id="emp_password" class="form-control"
                                   value="{{ old('password') }}" placeholder="Min. 6 characters" required>
                            <button type="button" onclick="generateEmpPassword()"
                                    class="btn btn-outline btn-sm" style="white-space:nowrap">Generate</button>
                        </div>
                        <div class="form-hint">Employee should change this after first login</div>
                        @error('password')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ── RIGHT COLUMN ── --}}
    <div style="display:flex;flex-direction:column;gap:16px;position:sticky;top:72px">

        {{-- Biometrics --}}
        <div class="card">
            <div class="card-header"><span class="card-title">🖐 Biometrics</span></div>
            <div class="card-body" style="padding:14px">

                <div class="form-group">
                    <label class="form-label">Attendance PIN</label>
                    <div style="display:flex;gap:8px">
                        <input type="text" name="biometric_pin" id="biometric_pin" class="form-control"
                               value="{{ old('biometric_pin') }}" placeholder="4–6 digits" maxlength="6"
                               oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                               style="letter-spacing:6px;font-weight:700;font-size:16px">
                        <button type="button" onclick="generatePin()"
                                class="btn btn-outline btn-sm" style="white-space:nowrap">Generate</button>
                    </div>
                    <div class="form-hint">PIN fallback for attendance</div>
                </div>
                <div class="form-group" style="margin-bottom:12px">
                    <label class="form-label">Biometric ID</label>
                    <input type="text" name="biometric_id" id="biometric_id" class="form-control"
                           value="{{ old('biometric_id') }}" placeholder="Auto-filled on scan">
                </div>

                <div id="bio-area" style="border:2px dashed var(--border);border-radius:10px;padding:16px;text-align:center;transition:all 0.25s">

                    {{-- IDLE --}}
                    <div id="bio-idle">
                        <div style="font-size:1.8rem;margin-bottom:6px">🖐</div>
                        <div style="font-weight:700;font-size:13px;color:var(--text);margin-bottom:3px">Fingerprint Enrollment</div>
                        <div style="font-size:11px;margin-bottom:10px" id="bio-server-status">Checking scanner...</div>
                        <div style="font-size:11px;color:var(--blue);background:var(--blue-bg);border-radius:6px;padding:7px 10px;margin-bottom:12px;text-align:left">
                            📋 Place finger multiple times — builds a complete fingerprint like your phone does.
                        </div>
                        <button type="button" onclick="startProgressiveEnroll()"
                                class="btn btn-primary" style="width:100%;justify-content:center">
                            🔍 Start Enrollment
                        </button>
                        <div style="margin-top:8px;font-size:11px;color:var(--text2)">
                            or <a href="#" onclick="skipBio(event)" style="color:var(--accent);text-decoration:none">skip for now</a>
                        </div>
                    </div>

                    {{-- ENROLLING --}}
                    <div id="bio-enrolling" style="display:none">
                        <div style="display:flex;gap:10px;justify-content:center;margin-bottom:10px">
                            <div>
                                <div style="font-size:10px;font-weight:600;color:var(--text2);text-transform:uppercase;letter-spacing:0.6px;margin-bottom:4px">Live</div>
                                <div style="border:2px solid var(--border);border-radius:8px;overflow:hidden;background:#111;width:120px;height:150px;position:relative">
                                    <canvas id="fp-live-canvas" width="120" height="150"
                                            style="display:block;width:120px;height:150px;image-rendering:pixelated"></canvas>
                                    <div style="position:absolute;bottom:4px;left:0;right:0;text-align:center">
                                        <span id="fp-live-msg" style="background:rgba(0,0,0,0.65);color:#fff;font-size:9px;font-weight:600;padding:2px 7px;border-radius:4px"></span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div style="font-size:10px;font-weight:600;color:var(--text2);text-transform:uppercase;letter-spacing:0.6px;margin-bottom:4px">Building</div>
                                <div style="border:2px solid var(--border);border-radius:8px;overflow:hidden;background:#111;width:120px;height:150px;position:relative">
                                    <canvas id="fp-composite-canvas" width="120" height="150"
                                            style="display:block;width:120px;height:150px;image-rendering:pixelated"></canvas>
                                    <svg width="120" height="150" style="position:absolute;inset:0;pointer-events:none;opacity:0.2">
                                        <ellipse cx="60" cy="75" rx="42" ry="58" fill="none" stroke="#fff" stroke-width="1.5" stroke-dasharray="4 3"/>
                                    </svg>
                                    <div style="position:absolute;bottom:4px;left:0;right:0;text-align:center">
                                        <span id="coverage-badge" style="background:rgba(200,132,74,0.85);color:#fff;font-size:9px;font-weight:700;padding:2px 7px;border-radius:4px">0%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="margin-bottom:8px">
                            <div style="display:flex;justify-content:space-between;margin-bottom:4px">
                                <span style="font-size:11px;color:var(--text)" id="enroll-status-text">Place finger…</span>
                                <span style="font-size:11px;color:var(--text2)" id="enroll-scan-count">0/5</span>
                            </div>
                            <div style="background:var(--muted);border-radius:20px;height:6px;overflow:hidden">
                                <div id="enroll-progress-bar" style="height:100%;background:linear-gradient(90deg,var(--accent),var(--green));border-radius:20px;width:0%;transition:width 0.4s ease"></div>
                            </div>
                        </div>
                        <div style="display:flex;justify-content:center;gap:6px;margin-bottom:10px">
                            @for($i=0;$i<5;$i++)
                            <div class="scan-dot" style="width:24px;height:24px;border-radius:50%;background:var(--muted);border:2px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:10px;transition:all 0.3s"></div>
                            @endfor
                        </div>
                        <button type="button" onclick="cancelProgressiveEnroll()"
                                class="btn btn-outline btn-sm" style="width:100%;justify-content:center">Cancel</button>
                    </div>

                    {{-- SUCCESS --}}
                    <div id="bio-success" style="display:none">
                        <div style="display:flex;gap:10px;justify-content:center;margin-bottom:10px">
                            <div>
                                <div style="font-size:10px;font-weight:600;color:var(--green);text-transform:uppercase;letter-spacing:0.6px;margin-bottom:4px;text-align:center">Enrolled ✓</div>
                                <div style="border:2px solid var(--green);border-radius:8px;overflow:hidden;background:#111;width:120px;height:150px">
                                    <canvas id="fp-final-canvas" width="120" height="150"
                                            style="display:block;width:120px;height:150px;image-rendering:pixelated"></canvas>
                                </div>
                            </div>
                            <div style="text-align:left;display:flex;flex-direction:column;justify-content:center;gap:4px">
                                <div style="font-size:1.4rem">✅</div>
                                <div style="font-weight:700;color:var(--green);font-size:13px">Enrolled!</div>
                                <div style="font-size:11px;color:var(--text2)" id="bio-success-msg"></div>
                                <div style="font-size:11px;font-weight:600;color:var(--green)" id="coverage-final"></div>
                            </div>
                        </div>
                        <button type="button" onclick="reEnroll()"
                                class="btn btn-outline btn-sm" style="width:100%;justify-content:center">🔄 Re-enroll</button>
                    </div>

                    {{-- ERROR --}}
                    <div id="bio-error" style="display:none">
                        <div style="font-size:1.6rem;margin-bottom:6px">⚠️</div>
                        <div style="font-weight:600;color:var(--red);font-size:13px;margin-bottom:3px" id="bio-error-title">Failed</div>
                        <div style="font-size:11.5px;color:var(--text2);margin-bottom:12px" id="bio-error-msg"></div>
                        <div style="display:flex;gap:8px">
                            <button type="button" onclick="startProgressiveEnroll()"
                                    class="btn btn-primary btn-sm" style="flex:1;justify-content:center">Try Again</button>
                            <button type="button" onclick="skipBio(event)"
                                    class="btn btn-outline btn-sm" style="flex:1;justify-content:center">Skip</button>
                        </div>
                    </div>

                    {{-- SKIPPED --}}
                    <div id="bio-skipped" style="display:none">
                        <div style="font-size:1.6rem;margin-bottom:6px">⏭</div>
                        <div style="font-weight:600;color:var(--text2);font-size:13px;margin-bottom:3px">Skipped</div>
                        <div style="font-size:11px;color:var(--text2);margin-bottom:10px">Enroll fingerprint later from employee profile.</div>
                        <button type="button" onclick="reEnroll()"
                                class="btn btn-outline btn-sm" style="width:100%;justify-content:center">Add Biometrics Instead</button>
                    </div>

                </div>

                <input type="hidden" name="webauthn_credential_id" id="webauthn_credential_id" value="{{ old('webauthn_credential_id') }}">
                <input type="hidden" name="biometric_enrolled"     id="biometric_enrolled"     value="{{ old('biometric_enrolled','0') }}">
            </div>
        </div>

        {{-- Save Button --}}
        <div class="card">
            <div class="card-body" style="padding:14px">
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:12px;font-size:14px">
                    💾 Save Employee
                </button>
                <a href="{{ route('employees.index') }}" class="btn btn-outline"
                   style="width:100%;justify-content:center;margin-top:8px">Cancel</a>
            </div>
        </div>

    </div>
</div>

</form>

<style>
@keyframes bioSpin {
    0%   { stroke-dashoffset: 176; }
    50%  { stroke-dashoffset: 0; }
    100% { stroke-dashoffset: -176; }
}
#bio-area.enrolling { border-color: var(--accent); border-style: solid; background: rgba(200,132,74,0.02); }
#bio-area.success   { border-color: var(--green);  border-style: solid; background: #f0fdf4; }
#bio-area.error     { border-color: var(--red);    border-style: solid; background: #fff0f0; }
.scan-dot.done   { background: var(--green)  !important; border-color: var(--green)  !important; color: #fff; }
.scan-dot.active { background: var(--accent) !important; border-color: var(--accent) !important; animation: dotPulse 0.6s ease infinite; }
@keyframes dotPulse { 0%,100%{transform:scale(1)} 50%{transform:scale(1.15)} }
@media (max-width: 900px) {
    form > div[style*="grid-template-columns"] { grid-template-columns: 1fr !important; }
    form > div > div[style*="position:sticky"] { position: static !important; }
}
</style>

<script>
const FP_SERVER = 'http://127.0.0.1:7788';

// ── Pay rate calculator ───────────────────────────────────────
(function() {
    const payType    = document.getElementById('pay_type');
    const payAmount  = document.getElementById('pay_amount');
    const payLabel   = document.getElementById('pay-label');
    const payHint    = document.getElementById('pay-hint');
    const dailyDisp  = document.getElementById('daily-display');
    const dailyInput = document.getElementById('daily_rate');
    const config = {
        monthly:      { label: 'Monthly Pay (₱) *',      hint: '÷ 26 working days = daily rate', divisor: 26 },
        semi_monthly: { label: 'Semi-Monthly Pay (₱) *', hint: '÷ 13 working days = daily rate', divisor: 13 },
        daily:        { label: 'Daily Rate (₱) *',       hint: 'This is the direct daily rate',   divisor: 1  },
    };
    function compute() {
        const t = payType.value, a = parseFloat(payAmount.value)||0, c = config[t], d = a/c.divisor;
        payLabel.textContent  = c.label;
        payHint.textContent   = c.hint;
        dailyDisp.textContent = '₱' + d.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        dailyInput.value      = d.toFixed(2);
    }
    payType.addEventListener('change', compute);
    payAmount.addEventListener('input', compute);
    compute();
})();

// ── PH Gov ID formatters ──────────────────────────────────────
function formatGovId(el, type) {
    let v = el.value.replace(/[^0-9]/g, '');
    if (type === 'sss') {
        if (v.length > 2)  v = v.slice(0,2)  + '-' + v.slice(2);
        if (v.length > 10) v = v.slice(0,10) + '-' + v.slice(10,11);
    } else if (type === 'philhealth') {
        if (v.length > 4)  v = v.slice(0,4)  + '-' + v.slice(4);
        if (v.length > 12) v = v.slice(0,12) + '-' + v.slice(12,13);
    } else if (type === 'pagibig') {
        if (v.length > 4)  v = v.slice(0,4)  + '-' + v.slice(4);
        if (v.length > 9)  v = v.slice(0,9)  + '-' + v.slice(9);
    } else if (type === 'tin') {
        if (v.length > 3)  v = v.slice(0,3)  + '-' + v.slice(3);
        if (v.length > 7)  v = v.slice(0,7)  + '-' + v.slice(7);
        if (v.length > 11) v = v.slice(0,11) + '-' + v.slice(11);
    }
    el.value = v;
}

// ── Password generators ───────────────────────────────────────
function generateEmpPassword() {
    const chars = 'abcdefghijkmnpqrstuvwxyz23456789@#!';
    let pwd = '';
    for (let i = 0; i < 8; i++) pwd += chars[Math.floor(Math.random() * chars.length)];
    const f = document.getElementById('emp_password');
    f.value = pwd;
    f.style.borderColor = 'var(--accent)';
    f.style.boxShadow   = '0 0 0 3px rgba(200,132,74,0.12)';
    setTimeout(() => { f.style.borderColor=''; f.style.boxShadow=''; }, 800);
}

function generatePin() {
    const pin = Math.floor(1000 + Math.random() * 9000).toString();
    const f   = document.getElementById('biometric_pin');
    f.value   = pin;
    f.style.borderColor = 'var(--accent)';
    f.style.boxShadow   = '0 0 0 3px rgba(200,132,74,0.12)';
    setTimeout(() => { f.style.borderColor=''; f.style.boxShadow=''; }, 800);
}

// ── Progressive Enrollment ────────────────────────────────────
const REQUIRED_SCANS  = 5;
const TARGET_COVERAGE = 70;
let enrolling = false, scanCount = 0, templates = [];

function setBioState(state) {
    ['idle','enrolling','success','error','skipped'].forEach(s => {
        const el = document.getElementById('bio-' + s);
        if (el) el.style.display = s === state ? '' : 'none';
    });
    const area = document.getElementById('bio-area');
    area.className = state === 'enrolling' ? 'enrolling'
                   : state === 'success'   ? 'success'
                   : state === 'error'     ? 'error' : '';
}

function setBioError(title, msg) {
    document.getElementById('bio-error-title').textContent = title;
    document.getElementById('bio-error-msg').textContent   = msg;
    setBioState('error');
}

function skipBio(e) {
    e.preventDefault();
    enrolling = false;
    document.getElementById('biometric_enrolled').value = '0';
    setBioState('skipped');
}

function reEnroll() {
    enrolling = false; scanCount = 0; templates = [];
    document.getElementById('biometric_enrolled').value     = '0';
    document.getElementById('webauthn_credential_id').value = '';
    ['fp-live-canvas','fp-composite-canvas'].forEach(id => {
        const c = document.getElementById(id);
        if (c) c.getContext('2d').clearRect(0, 0, c.width, c.height);
    });
    setBioState('idle');
}

function cancelProgressiveEnroll() { reEnroll(); }

function updateScanDots(count, active) {
    document.querySelectorAll('.scan-dot').forEach((d, i) => {
        d.className = 'scan-dot'; d.textContent = '';
        if (i < count)                  { d.classList.add('done');   d.textContent = '✓'; }
        else if (i === count && active) { d.classList.add('active'); d.textContent = '•'; }
    });
}

function drawOnLive(b64) {
    const c = document.getElementById('fp-live-canvas');
    if (!c || !b64) return;
    const img = new Image();
    img.onload = () => c.getContext('2d').drawImage(img, 0, 0, c.width, c.height);
    img.src = 'data:image/bmp;base64,' + b64;
}

function addToComposite(b64) {
    const c = document.getElementById('fp-composite-canvas');
    if (!c || !b64) return;
    const ctx = c.getContext('2d');
    ctx.globalCompositeOperation = 'lighter';
    ctx.globalAlpha = 0.55;
    const img = new Image();
    img.onload = () => {
        ctx.drawImage(img, 0, 0, c.width, c.height);
        ctx.globalCompositeOperation = 'source-over';
        ctx.globalAlpha = 1.0;
        updateCoverage();
    };
    img.src = 'data:image/bmp;base64,' + b64;
}

function updateCoverage() {
    const c = document.getElementById('fp-composite-canvas');
    if (!c) return 0;
    const d = c.getContext('2d').getImageData(0, 0, c.width, c.height).data;
    let bright = 0;
    for (let i = 0; i < d.length; i += 4) {
        if (d[i] > 20 || d[i+1] > 20 || d[i+2] > 20) bright++;
    }
    const pct = Math.min(100, Math.round((bright / (c.width * c.height)) * 100 * 1.8));
    document.getElementById('coverage-badge').textContent = pct + '%';
    const bar = document.getElementById('enroll-progress-bar');
    bar.style.width = Math.max(Math.round((scanCount / REQUIRED_SCANS) * 100), pct) + '%';
    if (pct >= TARGET_COVERAGE) {
        document.getElementById('enroll-status-text').textContent = '✅ Great coverage!';
        document.getElementById('coverage-badge').style.background = 'rgba(45,106,79,0.85)';
    }
    return pct;
}

async function startProgressiveEnroll() {
    enrolling = true; scanCount = 0; templates = [];
    ['fp-live-canvas','fp-composite-canvas'].forEach(id => {
        const c = document.getElementById(id);
        if (c) c.getContext('2d').clearRect(0, 0, c.width, c.height);
    });
    document.getElementById('enroll-progress-bar').style.width = '0%';
    document.getElementById('enroll-scan-count').textContent   = '0/5';
    document.getElementById('coverage-badge').textContent      = '0%';
    document.getElementById('coverage-badge').style.background = 'rgba(200,132,74,0.85)';
    document.getElementById('enroll-status-text').textContent  = 'Place finger on scanner';
    document.getElementById('fp-live-msg').textContent         = 'Waiting...';
    updateScanDots(0, true);
    setBioState('enrolling');
    await doProgressiveScan();
}

async function doProgressiveScan() {
    if (!enrolling) return;
    document.getElementById('fp-live-msg').textContent = 'Place finger...';
    updateScanDots(scanCount, true);
    try {
        const res  = await fetch(FP_SERVER + '/scan-live', {
            method: 'POST', headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({}), signal: AbortSignal.timeout(15000),
        });
        const data = await res.json();
        if (!data.success) {
            document.getElementById('fp-live-msg').textContent = '⚠ Try again';
            setTimeout(doProgressiveScan, 1500);
            return;
        }
        drawOnLive(data.image_b64);
        addToComposite(data.image_b64);
        document.getElementById('fp-live-msg').textContent = '✓ Got it';
        templates.push(data.finger_data);
        scanCount++;
        document.getElementById('enroll-scan-count').textContent = `${scanCount}/${REQUIRED_SCANS}`;
        updateScanDots(scanCount, false);
        setTimeout(() => {
            const coverage = updateCoverage();
            if ((coverage >= TARGET_COVERAGE && scanCount >= 3) || scanCount >= REQUIRED_SCANS) {
                finishEnrollment(coverage);
            } else {
                document.getElementById('enroll-status-text').textContent = 'Lift finger, then place again';
                document.getElementById('fp-live-msg').textContent        = 'Lift finger...';
                setTimeout(doProgressiveScan, 1200);
            }
        }, 300);
    } catch(e) {
        if (e.name === 'AbortError' || e.name === 'TimeoutError') {
            if (enrolling) {
                document.getElementById('fp-live-msg').textContent = 'No finger — try again';
                setTimeout(doProgressiveScan, 500);
            }
        } else {
            enrolling = false;
            setBioError('Scanner Offline', 'Make sure fingerprint_server.py is running. ' + e.message);
        }
    }
}

function finishEnrollment(coverage) {
    enrolling = false;
    const bestTemplate = templates[templates.length - 1];
    const composite = document.getElementById('fp-composite-canvas');
    const final     = document.getElementById('fp-final-canvas');
    if (composite && final) final.getContext('2d').drawImage(composite, 0, 0, final.width, final.height);
    const empNo    = document.getElementById('employee_no').value.trim() || 'NEW';
    const firstName = document.getElementById('first_name').value.trim() || '';
    const lastName  = document.getElementById('last_name').value.trim()  || '';
    const fullName  = (firstName + ' ' + lastName).trim() || 'Employee';
    document.getElementById('webauthn_credential_id').value = bestTemplate;
    document.getElementById('biometric_enrolled').value     = '1';
    const bioId = document.getElementById('biometric_id');
    if (!bioId.value) bioId.value = empNo.replace('CSB-','') + '-FP';
    document.getElementById('bio-success-msg').textContent = `${fullName} — ${scanCount} scans`;
    document.getElementById('coverage-final').textContent  = `${coverage}% coverage · Ready`;
    updateScanDots(REQUIRED_SCANS, false);
    setBioState('success');
}

// ── Check server on load ──────────────────────────────────────
(async function() {
    const statusEl = document.getElementById('bio-server-status');
    try {
        const r = await fetch(FP_SERVER + '/status', {signal: AbortSignal.timeout(2000)});
        const d = await r.json();
        if (d.status === 'ready') {
            statusEl.textContent = '✅ Scanner ready';
            statusEl.style.color = 'var(--green)';
        } else {
            statusEl.textContent = '⚠️ Scanner not detected — PIN only';
            statusEl.style.color = 'var(--orange)';
        }
    } catch(e) {
        statusEl.textContent = '⚠️ Scanner offline — PIN only';
        statusEl.style.color = 'var(--text2)';
    }
})();
</script>

@endsection