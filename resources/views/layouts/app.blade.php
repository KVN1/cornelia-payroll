<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Cornelia Street Bistro') — Payroll</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">
    <style>
        :root {
            --bg:       #F7F5F2;
            --surface:  #FFFFFF;
            --sidebar:  #1A1208;
            --accent:   #C8844A;
            --accent2:  #8B4513;
            --gold:     #D4A97A;
            --muted:    #EDE3D5;
            --text:     #1A1208;
            --text2:    #6B5744;
            --green:    #2D6A4F;
            --green-bg: #D8F3DC;
            --red:      #C1121F;
            --red-bg:   #FFE5E7;
            --blue:     #2563EB;
            --blue-bg:  #DBEAFE;
            --orange-bg:#FFF3E0;
            --orange:   #B45309;
            --border:   rgba(26,18,8,0.10);
            --shadow:   0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md:0 4px 12px rgba(0,0,0,0.08), 0 2px 4px rgba(0,0,0,0.04);
            --radius:   12px;
            --radius-sm:8px;
            --sidebar-w:256px;
            --transition: 0.18s cubic-bezier(0.4,0,0.2,1);
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        html { scroll-behavior: smooth; }

        body {
            font-family: system-ui, -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            font-size: 14px;
            line-height: 1.5;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--sidebar);
            height: 100vh;
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
            z-index: 200;
            border-right: 1px solid rgba(255,255,255,0.04);
            overflow: hidden;
        }

        .sidebar-brand {
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            flex-shrink: 0;
        }
        .brand-logo {
            width: 100%;
            max-width: 160px;
            border-radius: 10px;
            display: block;
            margin-bottom: 10px;
            object-fit: cover;
        }
        .brand-logo-fallback {
            width: 36px; height: 36px;
            background: var(--accent);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 10px;
        }
        .brand-logo-fallback svg { width:18px; height:18px; color:#fff; }
        .brand-sub {
            font-size: 10px;
            color: rgba(255,255,255,0.3);
            text-transform: uppercase;
            letter-spacing: 1.8px;
        }

        .sidebar-nav {
            padding: 10px 0;
            flex: 1;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.08) transparent;
        }
        .sidebar-nav::-webkit-scrollbar { width: 3px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.08); border-radius: 3px; }
        .sidebar-nav::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.15); }

        .nav-group-label {
            font-size: 9.5px;
            text-transform: uppercase;
            letter-spacing: 1.8px;
            color: rgba(255,255,255,0.18);
            padding: 14px 20px 4px;
            font-weight: 600;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 9px 20px;
            color: rgba(255,255,255,0.45);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all var(--transition);
            position: relative;
        }
        .nav-link:hover {
            color: rgba(255,255,255,0.85);
            background: rgba(255,255,255,0.05);
        }
        .nav-link.active {
            color: #fff;
            background: rgba(200,132,74,0.18);
        }
        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0; top: 5px; bottom: 5px;
            width: 3px;
            background: var(--accent);
            border-radius: 0 3px 3px 0;
        }
        .nav-icon {
            width: 30px; height: 30px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            background: rgba(255,255,255,0.04);
            transition: background var(--transition);
        }
        .nav-icon svg { width: 15px; height: 15px; }
        .nav-link:hover .nav-icon { background: rgba(255,255,255,0.08); }
        .nav-link.active .nav-icon {
            background: rgba(200,132,74,0.25);
        }
        .nav-link.active .nav-icon svg { color: var(--gold); }
        .nav-link:not(.active) .nav-icon svg { color: rgba(255,255,255,0.4); }

        .sidebar-footer {
            padding: 14px 20px;
            border-top: 1px solid rgba(255,255,255,0.06);
            font-size: 11px;
            color: rgba(255,255,255,0.22);
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }
        .sidebar-footer svg { width:13px; height:13px; color: rgba(255,255,255,0.22); }

        /* ── Main ── */
        .main-wrap {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ── Topbar ── */
        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 28px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(8px);
        }
        .topbar-left { display: flex; align-items: center; gap: 12px; }
        .topbar-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text);
            letter-spacing: -0.2px;
        }
        .topbar-right { display: flex; align-items: center; gap: 10px; }
        .topbar-avatar {
            width: 32px; height: 32px;
            background: var(--accent);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px;
            font-weight: 600;
            color: #fff;
        }
        .topbar-name { font-size: 13px; font-weight: 500; color: var(--text2); }

        /* ── Content ── */
        .content {
            padding: 28px 32px;
            flex: 1;
            animation: fadeIn 0.25s ease;
        }
        @keyframes fadeIn { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:translateY(0); } }

        /* ── Page header ── */
        .page-header { margin-bottom: 24px; }
        .page-title {
            font-size: 22px;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.4px;
            line-height: 1.2;
        }
        .page-sub {
            font-size: 13.5px;
            color: var(--text2);
            margin-top: 4px;
        }

        /* ── Cards ── */
        .card {
            background: var(--surface);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: box-shadow var(--transition);
        }
        .card:hover { box-shadow: var(--shadow-md); }
        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(to bottom, #fdfbf8, var(--surface));
        }
        .card-title { font-size: 14px; font-weight: 600; color: var(--text); }
        .card-body { padding: 20px; }

        /* ── Stat Cards ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 14px;
            margin-bottom: 24px;
        }
        .stat-card {
            background: var(--surface);
            border-radius: var(--radius);
            padding: 18px 20px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            transition: all var(--transition);
            cursor: default;
            position: relative;
            overflow: hidden;
        }
        .stat-card::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 2px;
            background: var(--accent);
            transform: scaleX(0);
            transition: transform var(--transition);
            transform-origin: left;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .stat-card:hover::after { transform: scaleX(1); }
        .stat-card.green::after  { background: var(--green); }
        .stat-card.red::after    { background: var(--red); }
        .stat-card.blue::after   { background: var(--blue); }
        .stat-card.orange::after { background: var(--orange); }

        .stat-icon {
            width: 36px; height: 36px;
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 17px;
            margin-bottom: 12px;
        }
        .stat-icon.green  { background: var(--green-bg); }
        .stat-icon.red    { background: var(--red-bg); }
        .stat-icon.blue   { background: var(--blue-bg); }
        .stat-icon.orange { background: var(--orange-bg); }
        .stat-icon.gold   { background: #FFF3E0; }

        .stat-label { font-size: 11.5px; color: var(--text2); font-weight: 500; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 4px; }
        .stat-value { font-size: 26px; font-weight: 700; color: var(--text); letter-spacing: -0.5px; line-height: 1; }
        .stat-sub   { font-size: 12px; color: var(--text2); margin-top: 5px; }

        /* ── Tables ── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        thead th {
            padding: 11px 16px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--text2);
            font-weight: 600;
            background: #faf8f5;
            border-bottom: 1px solid var(--border);
        }
        tbody td {
            padding: 12px 16px;
            border-bottom: 1px solid rgba(26,18,8,0.05);
            color: var(--text);
            vertical-align: middle;
        }
        tbody tr { transition: background var(--transition); }
        tbody tr:hover { background: #faf8f5; }
        tbody tr:last-child td { border-bottom: none; }

        /* ── Buttons ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 500;
            font-family: inherit;
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
            transition: all var(--transition);
            white-space: nowrap;
        }
        .btn:active { transform: scale(0.97); }
        .btn-primary { background: var(--text); color: #fff; border-color: var(--text); }
        .btn-primary:hover { background: var(--accent2); border-color: var(--accent2); }
        .btn-accent { background: var(--accent); color: #fff; border-color: var(--accent); }
        .btn-accent:hover { filter: brightness(1.08); }
        .btn-success { background: var(--green); color: #fff; border-color: var(--green); }
        .btn-success:hover { filter: brightness(1.1); }
        .btn-danger  { background: var(--red); color: #fff; border-color: var(--red); }
        .btn-danger:hover { filter: brightness(1.1); }
        .btn-outline {
            background: transparent;
            border-color: var(--border);
            color: var(--text2);
        }
        .btn-outline:hover { background: var(--muted); border-color: var(--accent); color: var(--text); }
        .btn-sm { padding: 5px 11px; font-size: 12px; border-radius: 6px; }
        .btn-xs { padding: 3px 9px; font-size: 11.5px; border-radius: 5px; }

        /* ── Badges ── */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 600;
            letter-spacing: 0.2px;
        }
        .badge::before { content: ''; width: 5px; height: 5px; border-radius: 50%; flex-shrink: 0; }
        .badge-active     { background: var(--green-bg);  color: var(--green);  }
        .badge-active::before { background: var(--green); }
        .badge-inactive   { background: #f0ede8; color: #888; }
        .badge-inactive::before { background: #bbb; }
        .badge-terminated { background: var(--red-bg);    color: var(--red);    }
        .badge-terminated::before { background: var(--red); }
        .badge-pending    { background: var(--orange-bg); color: var(--orange); }
        .badge-pending::before { background: var(--orange); }
        .badge-approved   { background: var(--green-bg);  color: var(--green);  }
        .badge-approved::before { background: var(--green); }
        .badge-rejected   { background: var(--red-bg);    color: var(--red);    }
        .badge-rejected::before { background: var(--red); }
        .badge-open       { background: var(--blue-bg);   color: var(--blue);   }
        .badge-open::before { background: var(--blue); }
        .badge-closed     { background: #f0ede8; color: #888; }
        .badge-closed::before { background: #bbb; }
        .badge-draft      { background: var(--orange-bg); color: var(--orange); }
        .badge-draft::before { background: var(--orange); }
        .badge-released   { background: var(--green-bg);  color: var(--green);  }
        .badge-released::before { background: var(--green); }
        .badge-processing { background: var(--blue-bg);   color: var(--blue);   }
        .badge-processing::before { background: var(--blue); }

        /* ── Forms ── */
        .form-group { margin-bottom: 16px; }
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            color: var(--text2);
            margin-bottom: 6px;
        }
        .form-control {
            width: 100%;
            padding: 9px 13px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 13.5px;
            font-family: inherit;
            color: var(--text);
            background: var(--surface);
            transition: all var(--transition);
            outline: none;
        }
        .form-control:hover { border-color: rgba(200,132,74,0.4); }
        .form-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(200,132,74,0.12); }
        .form-grid   { display: grid; grid-template-columns: 1fr 1fr; gap: 0 18px; }
        .form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0 18px; }
        .form-hint { font-size: 12px; color: var(--text2); margin-top: 4px; }
        .form-error { font-size: 12px; color: var(--red); margin-top: 4px; }

        /* ── Alerts ── */
        .alert {
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            font-size: 13.5px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.25s ease;
        }
        @keyframes slideIn { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:translateY(0); } }
        .alert-success { background: var(--green-bg); color: var(--green); border: 1px solid rgba(45,106,79,0.2); }
        .alert-error   { background: var(--red-bg);   color: var(--red);   border: 1px solid rgba(193,18,31,0.2); }

        /* ── Clock widget ── */
        .clock-widget {
            background: var(--sidebar);
            border-radius: var(--radius);
            padding: 24px;
            color: #fff;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.06);
            box-shadow: var(--shadow-md);
        }
        .clock-label { font-size: 10px; text-transform: uppercase; letter-spacing: 2px; color: rgba(255,255,255,0.3); margin-bottom: 8px; }
        .clock-time  { font-size: 42px; font-weight: 700; color: var(--gold); letter-spacing: 1px; font-variant-numeric: tabular-nums; line-height: 1; }
        .clock-date  { font-size: 12.5px; color: rgba(255,255,255,0.4); margin-top: 6px; }
        .clock-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 20px; }
        .clock-btn {
            padding: 10px 8px;
            border-radius: var(--radius-sm);
            border: 1px solid rgba(255,255,255,0.1);
            cursor: pointer;
            font-family: inherit;
            font-size: 12.5px;
            font-weight: 600;
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.8);
            transition: all var(--transition);
            display: flex; align-items: center; justify-content: center; gap: 6px;
        }
        .clock-btn:hover { background: rgba(255,255,255,0.12); color: #fff; transform: translateY(-1px); }
        .clock-btn:active { transform: scale(0.96); }
        .clock-btn.green  { background: rgba(45,106,79,0.4);   border-color: rgba(45,106,79,0.6); color: #a7f3d0; }
        .clock-btn.orange { background: rgba(200,132,74,0.4);  border-color: rgba(200,132,74,0.6); color: var(--gold); }
        .clock-btn.red    { background: rgba(193,18,31,0.35);  border-color: rgba(193,18,31,0.5); color: #fca5a5; }

        /* ── Section divider ── */
        .section-divider {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: var(--text2);
            font-weight: 600;
            padding: 14px 0 8px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 14px;
        }

        /* ── Pagination ── */
        .pagination { display: flex; gap: 4px; padding: 16px 20px; align-items: center; }
        .pagination a, .pagination span {
            padding: 5px 11px;
            border-radius: 6px;
            font-size: 13px;
            border: 1px solid var(--border);
            color: var(--text2);
            text-decoration: none;
            transition: all var(--transition);
        }
        .pagination a:hover { background: var(--muted); color: var(--text); }
        .pagination .active span { background: var(--text); color: #fff; border-color: var(--text); }

        /* ── Utilities ── */
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-2 { gap: 8px; }
        .gap-3 { gap: 12px; }
        .mt-4 { margin-top: 16px; }
        .mt-6 { margin-top: 24px; }
        .mb-4 { margin-bottom: 16px; }
        .mb-6 { margin-bottom: 24px; }
        .text-muted { color: var(--text2); font-size: 13px; }
        .text-right { text-align: right; }
        .font-bold  { font-weight: 700; }
        .w-full { width: 100%; }
        .truncate { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        /* ── Employee avatar ── */
        .emp-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            display: flex; align-items: center; justify-content: center;
            font-size: 12px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        /* ── Search bar ── */
        .search-bar {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 14px 20px;
            margin-bottom: 16px;
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        /* ── Loading bar ── */
        .loading-bar {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: var(--accent);
            z-index: 9999;
            animation: loadingBar 0.6s ease forwards;
            transform-origin: left;
        }
        @keyframes loadingBar { from { transform: scaleX(0); } to { transform: scaleX(1); } }
    </style>
    @stack('styles')
</head>
<body>

{{-- Loading bar on navigation --}}
<div id="loading-bar" class="loading-bar" style="display:none"></div>

{{-- Sidebar --}}
<aside class="sidebar">
    <div class="sidebar-brand">
        <img src="{{ asset('images/logo.jpg') }}"
             class="brand-logo"
             onerror="this.style.display='none';document.getElementById('brand-fb').style.display='flex'"
             alt="Cornelia Street Bistro">
        <div class="brand-logo-fallback" id="brand-fb" style="display:none">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
        </div>
        <div class="brand-sub">Payroll System</div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-group-label">Overview</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                </svg>
            </span> Dashboard
        </a>

        <div class="nav-group-label">Workforce</div>
        <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </span> Employees
        </a>
        <a href="{{ route('attendance.index') }}" class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
            </span> Attendance
        </a>
        <a href="{{ route('leaves.index') }}" class="nav-link {{ request()->routeIs('leaves.*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
            </span> Leave Requests
        </a>

        <div class="nav-group-label">Payroll</div>
        <a href="{{ route('payroll.index') }}" class="nav-link {{ request()->routeIs('payroll.*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <line x1="12" y1="1" x2="12" y2="23"/>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
            </span> Payroll Periods
        </a>

        @if(auth()->user()?->isAdmin())
        <div class="nav-group-label">System</div>
        <a href="{{ route('auth.password-requests') }}" class="nav-link {{ request()->routeIs('auth.password-requests') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </span>
            Password Requests
            @php $pendingPwCount = \App\Models\PasswordChangeRequest::where('status','pending')->count(); @endphp
            @if($pendingPwCount > 0)
                <span style="margin-left:auto;background:var(--red);color:#fff;font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px">{{ $pendingPwCount }}</span>
            @endif
        </a>
        <a href="{{ route('settings') }}" class="nav-link {{ request()->routeIs('settings*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                </svg>
            </span> Settings
        </a>
        @else
        <div class="nav-group-label">Account</div>
        <a href="{{ route('settings') }}" class="nav-link {{ request()->routeIs('settings*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                </svg>
            </span> Settings
        </a>
        @endif
    </nav>

    <div class="sidebar-footer">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/>
            <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
        {{ now()->format('F Y') }}
    </div>
</aside>

{{-- Main --}}
<div class="main-wrap">
    <div class="topbar">
        <div class="topbar-left">
            <div class="topbar-title" id="page-title">@yield('page-title', 'Dashboard')</div>
        </div>
        <div class="topbar-right">
            <div class="topbar-name">{{ auth()->user()->name ?? 'User' }}</div>
            <div class="topbar-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}</div>
            <form method="POST" action="{{ route('logout') }}" style="margin:0">
                @csrf
                <button type="submit" style="background:transparent;border:1px solid var(--border);color:var(--text2);padding:5px 12px;border-radius:7px;font-size:12px;cursor:pointer;font-family:inherit;transition:all 0.15s"
                onmouseover="this.style.borderColor='var(--red)';this.style.color='var(--red)'"
                onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text2)'">
                    Logout
                </button>
            </form>
        </div>
    </div>

    <div class="content" id="ajax-content">
        @if(session('success'))
            <div class="alert alert-success">✓ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">✗ {{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</div>

<script>
// ── Clock ──────────────────────────────────────────────────
function updateClock() {
    const el = document.getElementById('live-clock');
    if (el) {
        const now = new Date();
        el.textContent = now.toLocaleTimeString('en-PH', { hour:'2-digit', minute:'2-digit', second:'2-digit' });
    }
}
setInterval(updateClock, 1000);
updateClock();

// ── AJAX Navigation ────────────────────────────────────────
const contentEl  = document.getElementById('ajax-content');
const titleEl    = document.getElementById('page-title');
const loadingBar = document.getElementById('loading-bar');

function showBar()  { loadingBar.style.display = 'block'; loadingBar.style.animation = 'loadingBar 0.6s ease forwards'; }
function hideBar()  { setTimeout(() => { loadingBar.style.display = 'none'; }, 300); }

function setActiveLink(url) {
    document.querySelectorAll('.nav-link').forEach(a => {
        a.classList.toggle('active', a.href === url);
    });
}

async function navigate(url, pushState = true) {
    // Force full page load for routes that need proper Blade rendering
    const fullPageRoutes = ['/users/', '/reset-password'];
    if (fullPageRoutes.some(r => url.includes(r))) {
        window.location.href = url;
        return;
    }

    showBar();

    try {
        const res  = await fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-AJAX-Nav': '1' }
        });

        if (!res.ok) { window.location.href = url; return; }

        const html   = await res.text();
        const parser = new DOMParser();
        const doc    = parser.parseFromString(html, 'text/html');

        // Swap content
        const newContent = doc.getElementById('ajax-content');
        const newTitle   = doc.getElementById('page-title');
        const newScripts = doc.querySelectorAll('script[data-page]');

        if (newContent) {
            contentEl.style.opacity = '0';
            contentEl.style.transform = 'translateY(6px)';

            setTimeout(() => {
                contentEl.innerHTML = newContent.innerHTML;
                contentEl.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
                contentEl.style.opacity = '1';
                contentEl.style.transform = 'translateY(0)';

                // Update page title in topbar
                if (newTitle && titleEl) {
                    titleEl.textContent = newTitle.textContent;
                }

                // Update browser URL and document title
                if (pushState) {
                    history.pushState({ url }, '', url);
                    document.title = doc.title || 'Cornelia Street Bistro';
                }

                // Re-run inline scripts in new content
                contentEl.querySelectorAll('script').forEach(oldScript => {
                    const s = document.createElement('script');
                    s.textContent = oldScript.textContent;
                    document.body.appendChild(s).remove();
                });

                // Re-init clock if on attendance page
                updateClock();
                setActiveLink(url);
                hideBar();

                // Scroll content to top
                contentEl.scrollTop = 0;
                window.scrollTo(0, 0);

            }, 120);
        } else {
            // Fallback to full reload if no ajax-content found
            window.location.href = url;
        }
    } catch(e) {
        window.location.href = url;
    }
}

// Intercept sidebar nav clicks
document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', e => {
        const href = link.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('javascript')) return;
        e.preventDefault();
        navigate(href);
    });
});

// Handle browser back/forward
window.addEventListener('popstate', e => {
    if (e.state && e.state.url) {
        navigate(e.state.url, false);
    }
});

// Set initial state
history.replaceState({ url: window.location.href }, '', window.location.href);

// Intercept form submits inside content area — show loading bar
document.addEventListener('submit', e => {
    const form = e.target;
    if (form.closest('#ajax-content') && form.method.toLowerCase() === 'post') {
        showBar();
    }
});
</script>
@stack('scripts')
</body>
</html>
