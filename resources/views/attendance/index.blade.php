@extends('layouts.app')
@section('page-title', 'Attendance')

@section('content')
<style>
* { box-sizing: border-box; }

.att-wrap {
    display: flex;
    flex-direction: column;
    gap: 16px;
    height: calc(100vh - 100px);
}

/* ── Top row: clock + 4 tiles + input ── */
.att-top {
    display: grid;
    grid-template-columns: 200px 1fr 260px;
    gap: 14px;
    flex-shrink: 0;
}

/* Clock */
.att-clock {
    background: var(--sidebar);
    border-radius: var(--radius);
    padding: 18px 14px;
    text-align: center;
    color: #fff;
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 6px;
}
.att-clock-label { font-size: 9px; text-transform: uppercase; letter-spacing: 2px; color: rgba(255,255,255,0.3); }
.att-clock-time  { font-size: 36px; font-weight: 800; color: var(--gold); letter-spacing: 1px; font-variant-numeric: tabular-nums; line-height: 1; }
.att-clock-date  { font-size: 11px; color: rgba(255,255,255,0.35); }

/* Confirm banner inside clock */
.att-confirm {
    margin-top: 10px;
    padding: 10px 12px;
    border-radius: 8px;
    display: none;
    align-items: center;
    gap: 10px;
    text-align: left;
    animation: confirmIn 0.3s ease;
}
.att-confirm.ok  { background: rgba(45,106,79,0.25); border: 1px solid rgba(45,106,79,0.4); }
.att-confirm.err { background: rgba(193,18,31,0.25); border: 1px solid rgba(193,18,31,0.4); }
.att-confirm-name   { font-size: 12px; font-weight: 700; }
.att-confirm-detail { font-size: 10.5px; opacity: 0.7; margin-top: 1px; }
@keyframes confirmIn { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:translateY(0)} }

/* Action tiles */
.att-tiles {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}
.att-tile {
    border: 2px solid var(--border);
    background: var(--surface);
    border-radius: 12px;
    padding: 14px 10px;
    cursor: pointer;
    font-family: inherit;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    transition: all 0.15s;
    position: relative;
    overflow: hidden;
}
.att-tile::after {
    content: ''; position: absolute; bottom: 0; left: 0; right: 0;
    height: 3px; transform: scaleX(0); transition: transform 0.2s;
    transform-origin: left;
}
.att-tile.green::after  { background: var(--green); }
.att-tile.orange::after { background: var(--orange); }
.att-tile.blue::after   { background: var(--blue); }
.att-tile.red::after    { background: var(--red); }
.att-tile:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
.att-tile:hover::after { transform: scaleX(1); }
.att-tile.sel { box-shadow: var(--shadow-md); }
.att-tile.sel.green  { border-color: var(--green);  background: #f0fdf4; }
.att-tile.sel.orange { border-color: var(--orange); background: #fff8f0; }
.att-tile.sel.blue   { border-color: var(--blue);   background: #f0f5ff; }
.att-tile.sel.red    { border-color: var(--red);    background: #fff0f0; }
.att-tile.sel::after { transform: scaleX(1); }
.att-tile.tile-locked { opacity: 0.45; cursor: not-allowed; }
.att-tile.tile-locked:hover { transform: none; box-shadow: none; }
.att-tile.tile-locked::after { display: none; }
.att-tile-icon  { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
.att-tile-label { font-size: 12px; font-weight: 700; color: var(--text); }
.att-tile-sub   { font-size: 10.5px; color: var(--text2); }
.att-tile-check {
    position: absolute; top: 7px; right: 7px;
    width: 18px; height: 18px; border-radius: 50%;
    display: none; align-items: center; justify-content: center;
}
.att-tile.sel .att-tile-check { display: flex; }
.att-tile.sel.green  .att-tile-check { background: var(--green); }
.att-tile.sel.orange .att-tile-check { background: var(--orange); }
.att-tile.sel.blue   .att-tile-check { background: var(--blue); }
.att-tile.sel.red    .att-tile-check { background: var(--red); }

/* Input panel */
.att-input {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
.att-input-header {
    padding: 10px 14px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #faf8f5;
    flex-shrink: 0;
}
.att-input-title { font-size: 12px; font-weight: 700; color: var(--text); }
.att-input-body  { padding: 12px 14px; flex: 1; display: flex; flex-direction: column; gap: 10px; }

/* Fingerprint area */
.fp-area {
    background: #faf8f5;
    border: 1.5px dashed var(--border);
    border-radius: 10px;
    padding: 12px;
    text-align: center;
    transition: all 0.2s;
    flex-shrink: 0;
}
.fp-area.fp-ready   { border-color: var(--accent); }
.fp-area.fp-scanning { border-color: var(--accent); background: rgba(200,132,74,0.04); }
.fp-area.fp-ok      { border-color: var(--green);  background: #f0fdf4; border-style: solid; }
.fp-area.fp-err     { border-color: var(--red);    background: #fff0f0; border-style: solid; }

.fp-icon { font-size: 1.6rem; margin-bottom: 4px; }
.fp-label { font-size: 12px; font-weight: 600; color: var(--text); margin-bottom: 2px; }
.fp-sub   { font-size: 10.5px; color: var(--text2); margin-bottom: 8px; }
.fp-btn {
    padding: 7px 18px;
    background: var(--text); color: #fff;
    border: none; border-radius: 7px;
    font-size: 12px; font-weight: 600;
    cursor: pointer; font-family: inherit;
    transition: background 0.15s;
}
.fp-btn:hover { background: var(--accent); }

@keyframes fpPulse { 0%,100%{opacity:1} 50%{opacity:0.3} }

/* PIN pad */
.pin-divider {
    display: flex; align-items: center; gap: 8px;
    flex-shrink: 0;
}
.pin-divider::before, .pin-divider::after {
    content: ''; flex: 1; height: 1px; background: var(--border);
}
.pin-divider span { font-size: 10px; color: var(--text2); text-transform: uppercase; letter-spacing: 1px; font-weight: 600; white-space: nowrap; }

.pin-wrap { flex-shrink: 0; }
.pin-display {
    background: var(--sidebar);
    border-radius: 8px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-bottom: 8px;
}
.pin-dot {
    width: 10px; height: 10px; border-radius: 50%;
    background: rgba(255,255,255,0.15);
    transition: background 0.1s;
}
.pin-dot.filled { background: var(--gold); }
.pin-empty-msg { font-size: 10px; color: rgba(255,255,255,0.2); }

.pin-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 5px;
}
.pin-key {
    padding: 10px 6px;
    border-radius: 7px;
    border: 1px solid var(--border);
    background: #faf8f5;
    color: var(--text);
    font-size: 15px;
    font-weight: 700;
    font-family: inherit;
    cursor: pointer;
    transition: all 0.1s;
    display: flex; align-items: center; justify-content: center;
}
.pin-key:hover  { background: var(--muted); border-color: var(--accent); }
.pin-key:active { transform: scale(0.93); }
.pin-key-del { background: #fff0f0; border-color: rgba(193,18,31,0.15); color: var(--red); }
.pin-key-ok  { background: #f0fdf4; border-color: rgba(45,106,79,0.2); color: var(--green); }
.pin-status  { font-size: 10.5px; text-align: center; margin-top: 5px; min-height: 15px; }

/* ── Bottom: Table ── */
.att-table-wrap {
    flex: 1;
    min-height: 0;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
.att-table-header {
    padding: 10px 16px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-shrink: 0;
    background: #faf8f5;
}
.att-table-scroll {
    flex: 1;
    overflow-y: auto;
}
</style>

<div class="att-wrap">

    {{-- ── Scan Toast ── --}}
    <div id="fp-toast" style="display:none;position:fixed;bottom:28px;left:50%;transform:translateX(-50%);z-index:9999;padding:13px 24px;border-radius:12px;font-size:14px;font-weight:600;color:#fff;box-shadow:0 8px 32px rgba(0,0,0,0.2);transition:all 0.3s;white-space:nowrap;pointer-events:none;animation:toastIn 0.3s ease">
        <span id="fp-toast-msg"></span>
    </div>

    {{-- ── Live Scanner Banner (only shows on error/unrecognized) ── --}}
    <div id="live-banner" style="display:none;background:var(--sidebar);border-radius:var(--radius);padding:12px 20px;align-items:center;gap:16px;flex-shrink:0">
        <div id="live-fp-icon" style="font-size:1.8rem;transition:all 0.2s;flex-shrink:0">🖐</div>
        <div style="flex:1">
            <div style="font-size:13px;font-weight:700;color:#fff;margin-bottom:2px" id="live-status-title"></div>
            <div style="font-size:11.5px;color:rgba(255,255,255,0.4)" id="live-status-sub">
                <span id="live-dot" style="display:inline-block;width:7px;height:7px;border-radius:50%;background:#ccc;margin-right:5px;vertical-align:middle"></span>
                <span id="live-dot-text"></span>
            </div>
        </div>
        <div id="live-result-badge" style="display:none;padding:8px 16px;border-radius:8px;text-align:right">
            <div id="live-result-name" style="font-size:13px;font-weight:700"></div>
            <div id="live-result-detail" style="font-size:11px;opacity:0.8;margin-top:2px"></div>
        </div>
    </div>

    {{-- ── TOP ROW ── --}}
    <div class="att-top">

        {{-- Clock --}}
        <div class="att-clock">
            <div class="att-clock-label">Current Time</div>
            <div class="att-clock-time" id="live-clock">--:--:--</div>
            <div class="att-clock-date">{{ now()->format('l, F j Y') }}</div>
            <div id="att-confirm" class="att-confirm">
                <div id="att-confirm-icon" style="font-size:1.2rem;flex-shrink:0"></div>
                <div>
                    <div id="att-confirm-name"   class="att-confirm-name"></div>
                    <div id="att-confirm-detail" class="att-confirm-detail"></div>
                </div>
            </div>
        </div>

        {{-- 4 Tiles --}}
        <div class="att-tiles" id="att-tiles">
            <button class="att-tile green" data-action="time-in"
                    data-from="05:00" data-to="11:59"
                    data-label="AM Time In" data-window="5:00 AM – 11:59 AM">
                <div class="att-tile-check"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></div>
                <div class="att-tile-icon" style="background:var(--green-bg)">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div class="att-tile-label">AM Time In</div>
                <div class="att-tile-sub" id="sub-time-in">Start of shift</div>
                <div class="att-tile-lock" style="display:none;position:absolute;inset:0;background:rgba(255,255,255,0.7);border-radius:10px;display:none;flex-direction:column;align-items:center;justify-content:center;gap:4px" id="lock-time-in">
                    <span style="font-size:1.2rem">🔒</span>
                    <span style="font-size:10px;color:var(--text2);font-weight:600;text-align:center;padding:0 8px" id="lock-msg-time-in"></span>
                </div>
            </button>

            <button class="att-tile orange" data-action="break-out"
                    data-from="12:00" data-to="12:59"
                    data-label="Break / Lunch" data-window="12:00 PM – 12:59 PM">
                <div class="att-tile-check"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></div>
                <div class="att-tile-icon" style="background:var(--orange-bg)">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--orange)" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/></svg>
                </div>
                <div class="att-tile-label">Break / Lunch</div>
                <div class="att-tile-sub" id="sub-break-out">End of morning</div>
                <div class="att-tile-lock" style="display:none;position:absolute;inset:0;background:rgba(255,255,255,0.7);border-radius:10px;display:none;flex-direction:column;align-items:center;justify-content:center;gap:4px" id="lock-break-out">
                    <span style="font-size:1.2rem">🔒</span>
                    <span style="font-size:10px;color:var(--text2);font-weight:600;text-align:center;padding:0 8px" id="lock-msg-break-out"></span>
                </div>
            </button>

            <button class="att-tile blue" data-action="break-in"
                    data-from="13:00" data-to="16:59"
                    data-label="PM Time In" data-window="1:00 PM – 4:59 PM">
                <div class="att-tile-check"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></div>
                <div class="att-tile-icon" style="background:var(--blue-bg)">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--blue)" stroke-width="2"><polyline points="9 14 4 9 9 4"/><path d="M20 20v-7a4 4 0 0 0-4-4H4"/></svg>
                </div>
                <div class="att-tile-label">PM Time In</div>
                <div class="att-tile-sub" id="sub-break-in">Back from break</div>
                <div class="att-tile-lock" style="display:none;position:absolute;inset:0;background:rgba(255,255,255,0.7);border-radius:10px;display:none;flex-direction:column;align-items:center;justify-content:center;gap:4px" id="lock-break-in">
                    <span style="font-size:1.2rem">🔒</span>
                    <span style="font-size:10px;color:var(--text2);font-weight:600;text-align:center;padding:0 8px" id="lock-msg-break-in"></span>
                </div>
            </button>

            <button class="att-tile red" data-action="time-out"
                    data-from="13:00" data-to="23:59"
                    data-label="PM Time Out" data-window="1:00 PM onwards">
                <div class="att-tile-check"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></div>
                <div class="att-tile-icon" style="background:var(--red-bg)">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                </div>
                <div class="att-tile-label">PM Time Out</div>
                <div class="att-tile-sub" id="sub-time-out">End of shift</div>
                <div class="att-tile-lock" style="display:none;position:absolute;inset:0;background:rgba(255,255,255,0.7);border-radius:10px;display:none;flex-direction:column;align-items:center;justify-content:center;gap:4px" id="lock-time-out">
                    <span style="font-size:1.2rem">🔒</span>
                    <span style="font-size:10px;color:var(--text2);font-weight:600;text-align:center;padding:0 8px" id="lock-msg-time-out"></span>
                </div>
            </button>
        </div>

        {{-- Input Panel --}}
        <div class="att-input">
            <div class="att-input-header">
                <span class="att-input-title" id="input-title">Select an action →</span>
                <button id="btn-cancel-action" onclick="deselectAction()" style="display:none;background:transparent;border:none;color:var(--text2);cursor:pointer;font-size:11px;font-family:inherit">✕ Cancel</button>
            </div>
            <div class="att-input-body" id="input-body">

                {{-- Placeholder --}}
                <div id="input-placeholder" style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;opacity:0.4">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--text2)" stroke-width="1.5"><path d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3"/></svg>
                    <div style="font-size:12px;color:var(--text2);text-align:center">Click a tile to start</div>
                </div>

                {{-- Fingerprint + PIN (hidden until tile selected) --}}
                <div id="input-active" style="display:none;flex-direction:column;gap:8px;flex:1">

                    {{-- Fingerprint status indicator --}}
                    <div id="fp-area" style="display:flex;align-items:center;gap:10px;background:#faf8f5;border:1.5px solid var(--border);border-radius:10px;padding:10px 12px">
                        <div style="font-size:1.4rem;flex-shrink:0" id="fp-area-icon">🖐</div>
                        <div>
                            <div style="font-size:12px;font-weight:600;color:var(--text)" id="fp-area-title">Fingerprint Active</div>
                            <div style="font-size:10.5px;color:var(--text2)" id="fp-area-sub">Place finger on scanner anytime</div>
                        </div>
                    </div>

                    {{-- Divider --}}
                    <div class="pin-divider"><span>or use PIN</span></div>

                    {{-- PIN pad --}}
                    <div class="pin-wrap">
                        <div class="pin-display" id="pin-display">
                            <span class="pin-empty-msg" id="pin-empty-msg">Enter PIN</span>
                        </div>
                        <div class="pin-grid">
                            @foreach([1,2,3,4,5,6,7,8,9] as $n)
                            <button class="pin-key" data-val="{{ $n }}">{{ $n }}</button>
                            @endforeach
                            <button class="pin-key pin-key-del" id="pin-del">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"/><line x1="18" y1="9" x2="12" y2="15"/><line x1="12" y1="9" x2="18" y2="15"/></svg>
                            </button>
                            <button class="pin-key" data-val="0">0</button>
                            <button class="pin-key pin-key-ok" id="pin-ok">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            </button>
                        </div>
                        <div class="pin-status" id="pin-status"></div>
                    </div>

                </div>

            </div>
        </div>

    </div>

    {{-- ── BOTTOM: Table ── --}}
    <div class="att-table-wrap">
        <div class="att-table-header">
            <span style="font-size:13px;font-weight:600;color:var(--text)">Today's Logs — {{ now()->format('F j, Y') }}</span>
            <form method="GET" style="display:flex;gap:8px;align-items:center">
                <input type="date" name="date" value="{{ request('date', today()->toDateString()) }}"
                       class="form-control" style="width:150px;padding:6px 10px;font-size:12.5px">
                <button class="btn btn-accent btn-sm">Filter</button>
                <a href="{{ route('attendance.index') }}" class="btn btn-outline btn-sm">Today</a>
            </form>
        </div>
        <div class="att-table-scroll">
            <table style="width:100%;border-collapse:collapse;font-size:13px">
                <thead>
                    <tr>
                        <th style="padding:9px 14px;text-align:left;font-size:10.5px;text-transform:uppercase;letter-spacing:0.7px;color:var(--text2);font-weight:600;background:#faf8f5;border-bottom:1px solid var(--border);position:sticky;top:0">Employee</th>
                        <th style="padding:9px 14px;text-align:left;font-size:10.5px;text-transform:uppercase;letter-spacing:0.7px;color:var(--text2);font-weight:600;background:#faf8f5;border-bottom:1px solid var(--border);position:sticky;top:0">AM In</th>
                        <th style="padding:9px 14px;text-align:left;font-size:10.5px;text-transform:uppercase;letter-spacing:0.7px;color:var(--text2);font-weight:600;background:#faf8f5;border-bottom:1px solid var(--border);position:sticky;top:0">Break</th>
                        <th style="padding:9px 14px;text-align:left;font-size:10.5px;text-transform:uppercase;letter-spacing:0.7px;color:var(--text2);font-weight:600;background:#faf8f5;border-bottom:1px solid var(--border);position:sticky;top:0">PM In</th>
                        <th style="padding:9px 14px;text-align:left;font-size:10.5px;text-transform:uppercase;letter-spacing:0.7px;color:var(--text2);font-weight:600;background:#faf8f5;border-bottom:1px solid var(--border);position:sticky;top:0">PM Out</th>
                        <th style="padding:9px 14px;text-align:left;font-size:10.5px;text-transform:uppercase;letter-spacing:0.7px;color:var(--text2);font-weight:600;background:#faf8f5;border-bottom:1px solid var(--border);position:sticky;top:0">Hours</th>
                        <th style="padding:9px 14px;text-align:left;font-size:10.5px;text-transform:uppercase;letter-spacing:0.7px;color:var(--text2);font-weight:600;background:#faf8f5;border-bottom:1px solid var(--border);position:sticky;top:0">OT</th>
                        <th style="padding:9px 14px;text-align:left;font-size:10.5px;text-transform:uppercase;letter-spacing:0.7px;color:var(--text2);font-weight:600;background:#faf8f5;border-bottom:1px solid var(--border);position:sticky;top:0">Status</th>
                    </tr>
                </thead>
                <tbody id="logs-body">
                    @forelse($logs as $log)
                    <tr style="border-bottom:1px solid rgba(26,18,8,0.05)">
                        <td style="padding:10px 14px">
                            <div style="display:flex;align-items:center;gap:8px">
                                <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:#fff;flex-shrink:0">
                                    {{ strtoupper(substr($log->employee->first_name,0,1).substr($log->employee->last_name,0,1)) }}
                                </div>
                                <strong style="font-size:13px">{{ $log->employee->full_name }}</strong>
                            </div>
                        </td>
                        <td style="padding:10px 14px;font-weight:500">{{ $log->time_in   ? $log->time_in->format('h:i A')   : '—' }}</td>
                        <td style="padding:10px 14px">{{ $log->break_out ? $log->break_out->format('h:i A') : '—' }}</td>
                        <td style="padding:10px 14px">{{ $log->break_in  ? $log->break_in->format('h:i A')  : '—' }}</td>
                        <td style="padding:10px 14px;font-weight:500">{{ $log->time_out  ? $log->time_out->format('h:i A')  : '—' }}</td>
                        <td style="padding:10px 14px">{{ $log->total_hours_worked > 0 ? $log->total_hours_worked.'h' : '—' }}</td>
                        <td style="padding:10px 14px">
                            @if($log->overtime_hours > 0)
                                <span style="color:var(--accent);font-weight:600">+{{ $log->overtime_hours }}h</span>
                            @else —
                            @endif
                        </td>
                        <td style="padding:10px 14px">
                            @if($log->is_late)
                                <span class="badge badge-rejected">{{ $log->late_minutes }}m late</span>
                            @else
                                <span class="badge badge-active">On time</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align:center;padding:40px;color:var(--text2);font-size:13px">
                            No logs for this date.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
const CSRF      = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const ROUTES    = {
    'time-in':   '{{ route("attendance.time-in") }}',
    'break-out': '{{ route("attendance.break-out") }}',
    'break-in':  '{{ route("attendance.break-in") }}',
    'time-out':  '{{ route("attendance.time-out") }}',
    'pin-clock': '{{ route("attendance.pin-clock") }}',
};
const ATT_URL   = '{{ url("/attendance") }}';
const FP_SERVER = 'http://127.0.0.1:7788';

let currentAction  = null;
let pin            = '';
let fpWatchAbort   = null;   // AbortController for current /watch request

// ── Clock + tile lock checker ─────────────────────────────
function getNowMinutes() {
    const n = new Date();
    return n.getHours() * 60 + n.getMinutes();
}

function timeToMinutes(t) {
    const [h, m] = t.split(':').map(Number);
    return h * 60 + m;
}

function updateTileLocks() {
    const now = getNowMinutes();
    document.querySelectorAll('.att-tile').forEach(tile => {
        const from   = timeToMinutes(tile.dataset.from);
        const to     = timeToMinutes(tile.dataset.to);
        const action = tile.dataset.action;
        const lock   = document.getElementById('lock-' + action);
        const lockMsg = document.getElementById('lock-msg-' + action);
        const sub    = document.getElementById('sub-' + action);

        if (now < from || now > to) {
            // Outside time window
            tile.classList.add('tile-locked');
            tile.disabled = true;
            if (lock) lock.style.display = 'flex';
            if (lockMsg) lockMsg.textContent = now < from
                ? 'Opens at ' + tile.dataset.window.split('–')[0].trim()
                : 'Closed — ' + tile.dataset.window;
        } else {
            // Within time window
            tile.classList.remove('tile-locked');
            tile.disabled = false;
            if (lock) lock.style.display = 'none';
            if (sub) sub.textContent = tile.dataset.window;
        }
    });
}

setInterval(() => {
    const el = document.getElementById('live-clock');
    if (el) el.textContent = new Date().toLocaleTimeString('en-PH',{hour:'2-digit',minute:'2-digit',second:'2-digit'});
    updateTileLocks();
}, 1000);
document.getElementById('live-clock').textContent =
    new Date().toLocaleTimeString('en-PH',{hour:'2-digit',minute:'2-digit',second:'2-digit'});
updateTileLocks();

// ── Confirm in clock widget ────────────────────────────────
function showConfirm(name, detail, ok) {
    const box    = document.getElementById('att-confirm');
    const icon   = document.getElementById('att-confirm-icon');
    const nameEl = document.getElementById('att-confirm-name');
    const detEl  = document.getElementById('att-confirm-detail');
    box.className      = 'att-confirm ' + (ok ? 'ok' : 'err');
    icon.textContent   = ok ? '✅' : '❌';
    nameEl.textContent = name;
    detEl.textContent  = detail;
    nameEl.style.color = ok ? '#a7f3d0' : '#fca5a5';
    box.style.display  = 'flex';
    clearTimeout(box._t);
    box._t = setTimeout(() => box.style.display = 'none', 5000);
}

// ── Banner (only shown on error) ───────────────────────────
function showBanner(msg, ok) {
    const b = document.getElementById('live-banner');
    document.getElementById('live-fp-icon').textContent      = ok ? '✅' : '❌';
    document.getElementById('live-fp-icon').style.animation  = 'none';
    document.getElementById('live-status-title').textContent = msg;
    document.getElementById('live-dot').style.background     = ok ? 'var(--green)' : 'var(--red)';
    document.getElementById('live-dot-text').textContent     = ok ? '' : 'Use PIN below';
    b.style.display = 'flex';
    clearTimeout(b._t);
    b._t = setTimeout(() => b.style.display = 'none', 3000);
}
function hideBanner() {
    clearTimeout(document.getElementById('live-banner')._t);
    document.getElementById('live-banner').style.display = 'none';
}

// ── Tile selection ─────────────────────────────────────────
const tileTitles = {
    'time-in':'AM Time In','break-out':'Break / Lunch',
    'break-in':'PM Time In','time-out':'PM Time Out'
};

document.querySelectorAll('.att-tile').forEach(tile => {
    tile.addEventListener('click', function() {
        if (this.disabled) return; // locked tile
        document.querySelectorAll('.att-tile').forEach(t => t.classList.remove('sel'));
        this.classList.add('sel');
        currentAction = this.dataset.action;

        document.getElementById('input-title').textContent        = '② ' + tileTitles[currentAction];
        document.getElementById('btn-cancel-action').style.display = 'inline';
        document.getElementById('input-placeholder').style.display = 'none';
        document.getElementById('input-active').style.display      = 'flex';

        pinReset();
        document.getElementById('pin-status').textContent = '';

        // Start watching for fingerprint
        startFpWatch();
    });
});

function deselectAction() {
    document.querySelectorAll('.att-tile').forEach(t => t.classList.remove('sel'));
    currentAction = null;
    document.getElementById('input-title').textContent         = 'Select an action →';
    document.getElementById('btn-cancel-action').style.display = 'none';
    document.getElementById('input-placeholder').style.display = 'flex';
    document.getElementById('input-active').style.display      = 'none';
    pinReset();
    stopFpWatch();
}

// ── Fingerprint Watch ──────────────────────────────────────
// Starts a blocking /watch request when a tile is selected.
// Server holds the connection until finger is placed.
function setFpStatus(icon, title, sub) {
    document.getElementById('fp-area-icon').textContent  = icon;
    document.getElementById('fp-area-title').textContent = title;
    document.getElementById('fp-area-sub').textContent   = sub;
}

function stopFpWatch() {
    if (fpWatchAbort) { fpWatchAbort.abort(); fpWatchAbort = null; }
}

function showFpToast(msg, type) {
    const toast = document.getElementById('fp-toast');
    const colors = { ok: '#2d6a4f', err: '#c1121f', warn: '#b45309' };
    toast.style.background  = colors[type] || '#1a1208';
    toast.style.display     = 'block';
    toast.style.animation   = 'toastIn 0.3s ease';
    document.getElementById('fp-toast-msg').textContent = msg;
    clearTimeout(toast._t);
    toast._t = setTimeout(() => { toast.style.display = 'none'; }, 3500);
}

async function startFpWatch() {
    if (!currentAction) return;
    stopFpWatch();

    fpWatchAbort = new AbortController();
    const action = currentAction;
    setFpStatus('🖐', 'Ready to scan', 'Place finger flat on the scanner');

    try {
        const res  = await fetch(FP_SERVER + '/watch', {
            method:  'POST',
            headers: {'Content-Type': 'application/json'},
            body:    JSON.stringify({ action: action }),
            signal:  fpWatchAbort.signal,
        });
        const data = await res.json();

        if (!currentAction) return;

        if (data.message === 'busy') {
            setFpStatus('⏳', 'Scanner busy', 'Please wait a moment...');
            setTimeout(startFpWatch, 1000);
            return;
        }

        if (data.success) {
            // ✅ Success
            setFpStatus('✅', data.employee, data.action + ' recorded at ' + data.time);
            showFpToast('✅ ' + data.employee + ' — ' + data.action + ' at ' + data.time, 'ok');
            showConfirm(data.employee, data.action + ' at ' + data.time, true);
            hideBanner();
            deselectAction();
            refreshTable();
            setTimeout(() => refreshTable(), 800);

        } else if (data.message && data.message.includes('Too')) {
            // ⏰ Outside time window
            setFpStatus('⏰', 'Wrong time', data.message);
            showFpToast('⏰ ' + data.message, 'warn');
            setTimeout(() => {
                if (currentAction) {
                    setFpStatus('🖐', 'Ready to scan', 'Place finger flat on the scanner');
                    startFpWatch();
                }
            }, 3000);

        } else if (data.message && data.message.includes('already')) {
            // ✅ Already done today
            setFpStatus('✅', 'Already recorded', data.message);
            showFpToast('✅ ' + data.message, 'ok');
            setTimeout(() => {
                if (currentAction) {
                    setFpStatus('🖐', 'Ready to scan', 'Place finger flat on the scanner');
                    startFpWatch();
                }
            }, 3000);

        } else if (data.message === 'Fingerprint not recognized. Please enroll first.') {
            // ❌ Not enrolled
            setFpStatus('❌', 'Not Recognized', 'Use PIN below or enroll fingerprint first');
            showFpToast('❌ Fingerprint not recognized — use PIN', 'err');
            showBanner('Fingerprint not recognized — use PIN', false);
            setTimeout(() => {
                if (currentAction) {
                    hideBanner();
                    setFpStatus('🖐', 'Ready to scan', 'Place finger flat on the scanner');
                    startFpWatch();
                }
            }, 3000);

        } else {
            // ⚠️ Low quality / bad scan — ask to try again
            setFpStatus('⚠️', 'Scan unclear', 'Please try again — press finger firmly');
            showFpToast('⚠️ Scan not clear — press finger firmly and try again', 'warn');
            setTimeout(() => {
                if (currentAction) {
                    setFpStatus('🖐', 'Ready to scan', 'Place finger flat on the scanner');
                    startFpWatch();
                }
            }, 2500);
        }

    } catch(e) {
        if (e.name === 'AbortError') return;
        setFpStatus('⚠️', 'Scanner offline', 'Use PIN below');
        showFpToast('⚠️ Scanner offline — use PIN instead', 'warn');
    }
}

// ── PIN ────────────────────────────────────────────────────
function pinReset() {
    pin = '';
    updatePinDisplay();
}

function updatePinDisplay() {
    const disp  = document.getElementById('pin-display');
    const empty = document.getElementById('pin-empty-msg');
    disp.innerHTML = '';
    if (!pin.length) {
        empty.style.display = '';
        disp.appendChild(empty);
    } else {
        empty.style.display = 'none';
        disp.appendChild(empty);
        for (let i = 0; i < pin.length; i++) {
            const d = document.createElement('span');
            d.className = 'pin-dot filled';
            disp.appendChild(d);
        }
        for (let i = pin.length; i < 6; i++) {
            const d = document.createElement('span');
            d.className = 'pin-dot';
            disp.appendChild(d);
        }
    }
}

document.querySelectorAll('.pin-key[data-val]').forEach(btn => {
    btn.addEventListener('click', function() {
        if (pin.length >= 6) return;
        pin += this.dataset.val;
        updatePinDisplay();
    });
});
document.getElementById('pin-del').addEventListener('click', () => { pin = pin.slice(0,-1); updatePinDisplay(); });
document.getElementById('pin-ok').addEventListener('click', submitPin);

document.addEventListener('keydown', e => {
    if (!currentAction) return;
    if (e.key >= '0' && e.key <= '9' && pin.length < 6) { pin += e.key; updatePinDisplay(); }
    else if (e.key === 'Backspace') { pin = pin.slice(0,-1); updatePinDisplay(); }
    else if (e.key === 'Enter') submitPin();
    else if (e.key === 'Escape') deselectAction();
});

async function submitPin() {
    if (!pin || !currentAction) return;
    const st = document.getElementById('pin-status');
    st.textContent = 'Verifying...'; st.style.color = 'var(--text2)';
    try {
        const res  = await fetch(ROUTES['pin-clock'], {
            method:  'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
            body:    JSON.stringify({pin, action: currentAction}),
        });
        const data = await res.json();
        if (data.success) {
            st.textContent = '✓ ' + data.action + ' — ' + data.employee;
            st.style.color = 'var(--green)';
            showConfirm(data.employee, data.action + ' at ' + data.time, true);
            deselectAction();
            refreshTable();
            setTimeout(() => refreshTable(), 800);
        } else {
            st.textContent = '✗ ' + (data.message || 'Invalid PIN');
            st.style.color = 'var(--red)';
            const disp = document.getElementById('pin-display');
            disp.style.transform = 'translateX(-8px)';
            setTimeout(() => disp.style.transform = 'translateX(8px)', 100);
            setTimeout(() => disp.style.transform = '', 200);
            pin = ''; updatePinDisplay();
        }
    } catch(e) {
        st.textContent = 'Error. Try again.'; st.style.color = 'var(--red)';
        pin = ''; updatePinDisplay();
    }
}

// ── Refresh table (AJAX, no page reload) ──────────────────
async function refreshTable() {
    try {
        const date = new URLSearchParams(window.location.search).get('date') || new Date().toISOString().split('T')[0];
        const res  = await fetch(`${ATT_URL}?date=${date}`, {
            headers: {'X-Requested-With':'XMLHttpRequest','X-AJAX-Nav':'1','Accept':'text/html'}
        });
        if (res.ok) {
            const html = await res.text();
            const doc  = new DOMParser().parseFromString(html, 'text/html');
            const nb   = doc.getElementById('logs-body');
            if (nb) {
                document.getElementById('logs-body').innerHTML = nb.innerHTML;
            }
        }
    } catch(e) {}
}
</script>

<script>
// ── LIVE FINGERPRINT WATCHER ─────────────────────────────────
// Single blocking /watch request — server holds open until finger placed.
// Banner hidden normally, only shows on error/unrecognized.
let liveEnabled = false;

function showBanner(icon, title, sub, dotColor) {
    const b = document.getElementById('live-banner');
    document.getElementById('live-fp-icon').textContent      = icon;
    document.getElementById('live-fp-icon').style.animation  = 'none';
    document.getElementById('live-status-title').textContent = title;
    document.getElementById('live-dot').style.background     = dotColor;
    document.getElementById('live-dot-text').textContent     = sub;
    b.style.display = 'flex';
    clearTimeout(b._t);
    if (dotColor !== '#ccc') {
        b._t = setTimeout(() => b.style.display = 'none', 4000);
    }
}

function hideBanner() {
    clearTimeout(document.getElementById('live-banner')._t);
    document.getElementById('live-banner').style.display = 'none';
}

async function liveWatch() {
    if (!liveEnabled) return;
    try {
        const res  = await fetch(FP_SERVER + '/watch', {
            method:  'POST',
            headers: {'Content-Type': 'application/json'},
            body:    JSON.stringify({}),
        });
        const data = await res.json();

        if (data.message === 'busy') {
            setTimeout(liveWatch, 1000);
            return;
        }

        if (data.success) {
            hideBanner();
            showConfirm(data.employee, data.action + ' at ' + data.time, true);
            showFpToast('✅ ' + data.employee + ' — ' + data.action + ' at ' + data.time, 'ok');
            setFpStatus('✅', data.employee, data.action + ' at ' + data.time);
            refreshTable();
            setTimeout(() => {
                setFpStatus('🖐', 'Fingerprint Active', 'Place finger on scanner anytime');
                refreshTable();
                liveWatch();
            }, 3000);

        } else {
            showBanner('❌', 'Fingerprint Not Recognized', 'Use PIN below', 'var(--red)');
            showFpToast('❌ Fingerprint not recognized — use PIN', 'err');
            setFpStatus('❌', 'Not Recognized', 'Use PIN instead');
            setTimeout(() => {
                hideBanner();
                setFpStatus('🖐', 'Fingerprint Active', 'Place finger on scanner anytime');
                liveWatch();
            }, 3000);
        }

    } catch(e) {
        liveEnabled = false;
        showBanner('⚠️', 'Scanner Offline — Use PIN', 'fingerprint_server.py not running', '#ccc');
        setFpStatus('⚠️', 'Scanner Offline', 'Use PIN below');

        const retry = setInterval(async () => {
            try {
                const r = await fetch(FP_SERVER + '/status', {signal: AbortSignal.timeout(2000)});
                const d = await r.json();
                if (d.status === 'ready') {
                    clearInterval(retry);
                    liveEnabled = true;
                    hideBanner();
                    setFpStatus('🖐', 'Fingerprint Active', 'Place finger on scanner anytime');
                    liveWatch();
                }
            } catch(e2) {}
        }, 5000);
    }
}

// Start on page load
(async function() {
    try {
        const r = await fetch(FP_SERVER + '/status', {signal: AbortSignal.timeout(2000)});
        const d = await r.json();
        if (d.status === 'ready') {
            liveEnabled = true;
            setFpStatus('🖐', 'Fingerprint Active', 'Place finger on scanner anytime');
            liveWatch();
        } else {
            setFpStatus('⚠️', 'Scanner Offline', 'Use PIN below');
        }
    } catch(e) {
        setFpStatus('⚠️', 'Scanner Offline', 'Use PIN below');
    }
})();
</script>

@endsection