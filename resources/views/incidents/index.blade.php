@extends('layouts.app')

@section('title', 'Incident Reports')

@push('styles')
<style>
    /* MAIN */
    .main { margin-left: var(--sidebar-w); padding-top: var(--topbar-h); flex: 1; }
    @push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --red: #C0172B;
            --red-dark: #8B0F1E;
            --red-light: #F9E9EB;
            --white: #ffffff;
            --gray-50: #F6F6F6;
            --gray-100: #EEEEEE;
            --gray-200: #DDDDDD;
            --gray-400: #999999;
            --gray-600: #555555;
            --gray-800: #222222;
            --green: #1A7A4A;
            --green-light: #E6F4ED;
            --amber: #A35C00;
            --amber-light: #FEF3E2;
            --blue: #1A4FA3;
            --blue-light: #E8EFFE;
            --font: 'Inter', sans-serif;
            --sidebar-w: 230px;
            --topbar-h: 60px;
        }

        body { font-family: var(--font); background: var(--gray-50); color: var(--gray-800); }

        /* SIDEBAR and TOPBAR styles are provided by layout partials; these rules adapt content area to match the screenshot */

        .main { margin-left: var(--sidebar-w); padding-top: var(--topbar-h); flex: 1; min-height: 100vh; }
        .content { padding: 24px; }

        /* STAT CARDS */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 20px; }
        .stat-card { background: var(--white); border: 1px solid var(--gray-100); border-radius: 12px; padding: 18px 20px; display: flex; flex-direction: column; gap: 10px; }
        .stat-card-top { display:flex; align-items:center; justify-content:space-between; }
        .stat-icon { width:36px; height:36px; border-radius:9px; display:flex; align-items:center; justify-content:center; }
        .stat-icon svg { width:18px; height:18px; }
        .stat-number { font-size:28px; font-weight:700; color:var(--gray-800); }
        .stat-label { font-size:12px; color:var(--gray-400); }

        /* TWO / THREE COLUMN LAYOUT */
        .two-col { display:grid; grid-template-columns: 1fr 340px; gap:16px; margin-bottom:16px; }
        .three-col { display:grid; grid-template-columns: 1fr 1fr 1fr; gap:16px; margin-bottom:16px; }

        .panel { background: var(--white); border:1px solid var(--gray-100); border-radius:12px; overflow:hidden; }
        .panel-header { display:flex; align-items:center; justify-content:space-between; padding:16px 20px; border-bottom:1px solid var(--gray-100); }
        .panel-title { font-size:13px; font-weight:600; color:var(--gray-800); }
        .panel-sub { font-size:11px; color:var(--gray-400); margin-top:1px; }

        /* Chart / attendance */
        .chart-area { padding:20px; }
        .bar-chart { display:flex; align-items:flex-end; gap:8px; height:120px; }
        .bar-group { flex:1; display:flex; flex-direction:column; align-items:center; gap:4px; }
        .bars { display:flex; gap:3px; align-items:flex-end; height:100px; }
        .bar { width:10px; border-radius:4px 4px 0 0; }
        .bar.present { background:var(--red); }
        .bar.absent { background:var(--gray-200); }
        .bar.leave { background:var(--amber-light); border:1px solid #F0C06A; }
        .bar-label { font-size:10px; color:var(--gray-400); }

        /* Employee list */
        .emp-list { padding:0; }
        .emp-row { display:flex; align-items:center; gap:12px; padding:12px 20px; border-bottom:1px solid var(--gray-50); }
        .emp-row:hover { background:var(--gray-50); }
        .emp-avatar { width:34px; height:34px; border-radius:50%; background:var(--gray-100); color:var(--gray-600); font-size:12px; font-weight:700; display:flex; align-items:center; justify-content:center; }
        .emp-name { font-size:13px; font-weight:600; color:var(--gray-800); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }

        /* Violations */
        .vio-row { display:flex; align-items:center; gap:12px; padding:11px 20px; border-bottom:1px solid var(--gray-50); }
        .vio-num { width:22px; height:22px; border-radius:6px; background:var(--red-light); color:var(--red); font-size:11px; font-weight:700; display:flex; align-items:center; justify-content:center; }

        /* Remarks */
        .remarks-list { padding:12px 20px; display:flex; flex-direction:column; gap:10px; }

        /* Top red accent bar (keeps look consistent with screenshot) */
        .red-topbar { height:4px; background:var(--red); position:fixed; top:0; left:0; right:0; z-index:200; }

        /* small helpers */
        .btn-secondary { display:flex; align-items:center; gap:6px; background:var(--white); color:var(--gray-800); border:1px solid var(--gray-200); border-radius:8px; padding:8px 12px; font-size:13px; font-weight:600; cursor:pointer; }

    </style>
    @endpush
    .emp-av { width: 28px; height: 28px; font-size: 12px; }
    .action-btn svg { width: 12px !important; height: 12px !important; }
    .btn-primary svg { width: 12px !important; height: 12px !important; }
    .chart-plot { font-size: 13px; font-weight:600; color:var(--gray-400); }
</style>
@endpush

@section('content')
<!-- Static placeholders (no backend yet) -->
@php
    $totalIRs = 7;
    $openIRs = 2;
    $pendingIRs = 2;
    $resolvedIRs = 1;
    $employees = collect([
        (object)['id'=>1,'full_name'=>'Juan Dela Cruz','employment_type'=>'Staff'],
        (object)['id'=>2,'full_name'=>'Maria Santos','employment_type'=>'Supervisor'],
        (object)['id'=>3,'full_name'=>'Pedro Reyes','employment_type'=>'Manager'],
    ]);
@endphp
<main class="main">
    <div class="content">

        {{-- STAT CARDS --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon blue">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <span class="stat-trend neutral">Total</span>
                </div>
                <div class="stat-number">7</div>
                <div class="stat-label">Total IRs</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon red">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    </div>
                    <span class="stat-trend red">Open</span>
                </div>
                <div class="stat-number">2</div>
                <div class="stat-label">Open / Unresolved</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon amber">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <span class="stat-trend amber">Pending</span>
                </div>
                <div class="stat-number">2</div>
                <div class="stat-label">Pending Review</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon green">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <span class="stat-trend green">Resolved</span>
                </div>
                <div class="stat-number">1</div>
                <div class="stat-label">Resolved</div>
            </div>
        </div>

        {{-- DASHBOARD CHART --}}
        <div class="chart-card">
            <div class="chart-title">
                <div style="font-weight:700;color:var(--gray-800);">Visitor Trends</div>
                <div style="display:flex;gap:8px;align-items:center;">
                    <button class="btn-secondary" style="background:transparent;border:1px solid var(--gray-100);">Last 3 months</button>
                    <button class="btn-secondary" style="background:transparent;border:1px solid var(--gray-100);">Last 30 days</button>
                    <button class="btn-secondary" style="background:transparent;border:1px solid var(--gray-100);">Last 7 days</button>
                </div>
            </div>
            <div class="chart-plot">[ Chart placeholder ]</div>
        </div>

        <div class="two-col">
            <div class="top-list">
                <div class="top-header">Top Visited Office</div>
                <div class="top-body">
                    <ol>
                        <li style="margin-bottom:8px;">1. Mayor's Office <span style="float:right;color:var(--gray-500);">45 visitors</span></li>
                        <li style="margin-bottom:8px;">2. Treasurer's Office <span style="float:right;color:var(--gray-500);">38 visitors</span></li>
                        <li style="margin-bottom:8px;">3. Business Permits <span style="float:right;color:var(--gray-500);">32 visitors</span></li>
                    </ol>
                </div>
            </div>
            <div class="alerts">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;font-weight:700;color:var(--gray-700);">Alerts <button class="btn-secondary" style="padding:6px 8px;">⋯</button></div>
                <div class="alert-item"><div class="alert-left"><div class="alert-dot"></div><div><div style="font-weight:600;">Pending Approval Requests</div><div style="font-size:12px;color:var(--gray-500);">Some visitor requests need review.</div></div></div><div style="font-size:12px;color:var(--gray-400);">1:00 PM</div></div>
                <div class="alert-item"><div class="alert-left"><div class="alert-dot" style="background:var(--amber);"></div><div><div style="font-weight:600;">Expired Visitor ID</div><div style="font-size:12px;color:var(--gray-500);">Uploaded ID requires verification.</div></div></div><div style="font-size:12px;color:var(--gray-400);">1:00 PM</div></div>
                <div class="alert-item"><div class="alert-left"><div class="alert-dot" style="background:var(--red);"></div><div><div style="font-weight:600;">Blacklisted Visitor Attempt</div><div style="font-size:12px;color:var(--gray-500);">Flagged visitor tried to register.</div></div></div><div style="font-size:12px;color:var(--gray-400);">1:00 PM</div></div>
            </div>
        </div>

        {{-- TOOLBAR --}}
        <div class="toolbar">
            <div class="search-box">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" id="searchInput" placeholder="Search by name, IR number, type…" />
            </div>
            <select class="filter-select" id="filterStatus">
                <option value="">All Status</option>
                <option value="open">Open</option>
                <option value="pending">Pending</option>
                <option value="review">Under Review</option>
                <option value="resolved">Resolved</option>
            </select>
            <select class="filter-select" id="filterType">
                <option value="">All Types</option>
                <option>Workplace Incident</option>
                <option>Attendance Violation</option>
                <option>Policy Violation</option>
                <option>Equipment Damage</option>
                <option>Misconduct</option>
            </select>
            <select class="filter-select" id="filterDate">
                <option value="">All Time</option>
                <option>This Month</option>
                <option>Last 3 Months</option>
                <option>This Year</option>
            </select>
            <button class="btn-primary" onclick="openSubmit()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Submit New IR
            </button>
        </div>

        {{-- IR TABLE --}}
        <div class="panel">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Incident Reports</div>
                    <div class="panel-sub">{{ $totalIRs }} total records</div>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>IR No.</th>
                        <th>Employee</th>
                        <th>Type</th>
                        <th>Date Filed</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="ir-id">IR-0001</span></td>
                        <td>
                            <div class="emp-cell">
                                <div class="emp-av r">JD</div>
                                <div>
                                    <div class="emp-name">Juan Dela Cruz</div>
                                    <div class="emp-type">Staff</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="ir-type-text">Workplace Incident</span></td>
                        <td><span class="ir-date">Jun 10, 2026</span></td>
                        <td><span class="badge open">Open</span></td>
                        <td>
                            <div class="action-btns">
                                <div class="action-btn" title="View" onclick="openView('IR-0001','Jun 10, 2026','Juan Dela Cruz','Staff','Workplace Incident','1st Offense','Verbal Warning','Placeholder description for IR-0001','No file attached','open','Open')">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </div>
                                <div class="action-btn" title="No file" style="opacity:0.4;cursor:default;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="ir-id">IR-0002</span></td>
                        <td>
                            <div class="emp-cell">
                                <div class="emp-av b">MS</div>
                                <div>
                                    <div class="emp-name">Maria Santos</div>
                                    <div class="emp-type">Supervisor</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="ir-type-text">Attendance Violation</span></td>
                        <td><span class="ir-date">Jun 09, 2026</span></td>
                        <td><span class="badge pending">Pending</span></td>
                        <td>
                            <div class="action-btns">
                                <div class="action-btn" title="View" onclick="openView('IR-0002','Jun 09, 2026','Maria Santos','Supervisor','Attendance Violation','2nd Offense','Written Warning','Placeholder description for IR-0002','report-2.pdf','pending','Pending')">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </div>
                                <a class="action-btn" title="Download" href="#">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="ir-id">IR-0003</span></td>
                        <td>
                            <div class="emp-cell">
                                <div class="emp-av g">PR</div>
                                <div>
                                    <div class="emp-name">Pedro Reyes</div>
                                    <div class="emp-type">Manager</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="ir-type-text">Policy Violation</span></td>
                        <td><span class="ir-date">Jun 08, 2026</span></td>
                        <td><span class="badge review">Under Review</span></td>
                        <td>
                            <div class="action-btns">
                                <div class="action-btn" title="View" onclick="openView('IR-0003','Jun 08, 2026','Pedro Reyes','Manager','Policy Violation','3rd Offense','Suspension','Placeholder description for IR-0003','No file attached','review','Under Review')">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </div>
                                <div class="action-btn" title="No file" style="opacity:0.4;cursor:default;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="ir-id">IR-0004</span></td>
                        <td>
                            <div class="emp-cell">
                                <div class="emp-av r">JD</div>
                                <div>
                                    <div class="emp-name">Juan Dela Cruz</div>
                                    <div class="emp-type">Staff</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="ir-type-text">Equipment Damage</span></td>
                        <td><span class="ir-date">Jun 07, 2026</span></td>
                        <td><span class="badge resolved">Resolved</span></td>
                        <td>
                            <div class="action-btns">
                                <div class="action-btn" title="View" onclick="openView('IR-0004','Jun 07, 2026','Juan Dela Cruz','Staff','Equipment Damage','1st Offense','Under Review','Placeholder description for IR-0004','report-4.pdf','resolved','Resolved')">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </div>
                                <a class="action-btn" title="Download" href="#">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="ir-id">IR-0005</span></td>
                        <td>
                            <div class="emp-cell">
                                <div class="emp-av b">MS</div>
                                <div>
                                    <div class="emp-name">Maria Santos</div>
                                    <div class="emp-type">Supervisor</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="ir-type-text">Misconduct</span></td>
                        <td><span class="ir-date">Jun 06, 2026</span></td>
                        <td><span class="badge pending">Pending</span></td>
                        <td>
                            <div class="action-btns">
                                <div class="action-btn" title="View" onclick="openView('IR-0005','Jun 06, 2026','Maria Santos','Supervisor','Misconduct','2nd Offense','Written Warning','Placeholder description for IR-0005','No file attached','pending','Pending')">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </div>
                                <div class="action-btn" title="No file" style="opacity:0.4;cursor:default;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            {{-- PAGINATION --}}
            <div class="pagination-wrap">
                <div class="page-info">
                    Showing 1–5 of 7 records
                </div>
                <div class="page-btns">
                    <span class="page-btn" style="opacity:0.4;">‹</span>
                    <a class="page-btn active" href="#">1</a>
                    <a class="page-btn" href="#">2</a>
                    <span class="page-btn" style="opacity:0.4;">›</span>
                </div>
            </div>
        </div>

    </div>
</main>

{{-- VIEW IR MODAL --}}
<div class="modal-overlay" id="viewModal">
    <div class="modal">
        <div class="modal-header">
            <div>
                <div class="modal-title" id="modalIRNum"></div>
                <div style="font-size:12px;color:var(--gray-400);margin-top:2px;" id="modalDate"></div>
            </div>
            <div style="display:flex;gap:8px;align-items:center;">
                <span class="badge" id="modalBadge"></span>
                <button class="modal-close" onclick="closeView()">✕</button>
            </div>
        </div>
        <div class="modal-body">
            <div class="meta-grid">
                <div class="meta-item">
                    <div class="meta-label">Employee</div>
                    <div class="meta-value" id="modalEmp"></div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Employment Type</div>
                    <div class="meta-value" id="modalEmpType"></div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">IR Type</div>
                    <div class="meta-value" id="modalIRType"></div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Offense Level</div>
                    <div class="meta-value" id="modalOffense"></div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Sanction</div>
                    <div class="meta-value" id="modalSanction"></div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Filed by</div>
                    <div class="meta-value">Juan Dela Cruz</div>
                </div>
            </div>
            <div>
                <div class="meta-label" style="margin-bottom:8px;">Description</div>
                <div class="description-box" id="modalDesc"></div>
            </div>
            <div>
                <div class="meta-label" style="margin-bottom:8px;">Attached File</div>
                <div class="pdf-preview">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="13" x2="15" y2="13"/><line x1="9" y1="17" x2="15" y2="17"/></svg>
                    <div class="pdf-name" id="modalFileName"></div>
                    <div class="pdf-sub">Click below to download</div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="closeView()">Close</button>
            <button class="btn-secondary" id="modalDownloadBtn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Download PDF
            </button>
            <button class="btn-resolve">Mark as Resolved</button>
        </div>
    </div>
</div>

{{-- SUBMIT IR MODAL --}}
<div class="modal-overlay" id="submitModal">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Submit New Incident Report</div>
            <button class="modal-close" onclick="closeSubmit()">✕</button>
        </div>
        <form action="#" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Employee</label>
                        <select class="form-input" name="employee_id" required>
                            <option value="">Select employee…</option>
                            <option value="1">Juan Dela Cruz</option>
                            <option value="2">Maria Santos</option>
                            <option value="3">Pedro Reyes</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date of Incident</label>
                        <input class="form-input" type="date" name="date_filed" required />
                    </div>
                    <div class="form-group">
                        <label class="form-label">IR Type</label>
                        <select class="form-input" name="type" required>
                            <option value="">Select type…</option>
                            <option>Workplace Incident</option>
                            <option>Attendance Violation</option>
                            <option>Policy Violation</option>
                            <option>Equipment Damage</option>
                            <option>Misconduct</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Offense Level</label>
                        <select class="form-input" name="offense_level" required>
                            <option value="">Select level…</option>
                            <option>1st Offense</option>
                            <option>2nd Offense</option>
                            <option>3rd Offense</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sanction Given</label>
                        <select class="form-input" name="sanction">
                            <option value="">Select sanction…</option>
                            <option>Verbal Warning</option>
                            <option>Written Warning</option>
                            <option>Suspension</option>
                            <option>Termination</option>
                            <option>Under Review</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select class="form-input" name="status">
                            <option value="open">Open</option>
                            <option value="pending">Pending Review</option>
                            <option value="review">Under Review</option>
                            <option value="resolved">Resolved</option>
                        </select>
                    </div>
                    <div class="form-group full">
                        <label class="form-label">Description</label>
                        <textarea class="form-input" name="description" placeholder="Describe the incident in detail…"></textarea>
                    </div>
                    <div class="form-group full">
                        <label class="form-label">Attach IR File (PDF)</label>
                        <label class="file-drop" for="irFile">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            Click to upload or drag and drop<br>
                            <span style="font-size:11px;">PDF, DOCX up to 10MB</span>
                        </label>
                        <input type="file" id="irFile" name="file" accept=".pdf,.docx" style="display:none;" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeSubmit()">Cancel</button>
                <button type="submit" class="btn-primary" style="margin-left:0;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Submit IR
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openView(irNum, date, emp, empType, irType, offense, sanction, desc, fileName, badgeClass, badgeLabel) {
        document.getElementById('modalIRNum').textContent    = irNum;
        document.getElementById('modalDate').textContent     = 'Filed ' + date;
        document.getElementById('modalEmp').textContent      = emp;
        document.getElementById('modalEmpType').textContent  = empType;
        document.getElementById('modalIRType').textContent   = irType;
        document.getElementById('modalOffense').textContent  = offense;
        document.getElementById('modalSanction').textContent = sanction;
        document.getElementById('modalDesc').textContent     = desc;
        document.getElementById('modalFileName').textContent = fileName;
        const badge = document.getElementById('modalBadge');
        badge.className   = 'badge ' + badgeClass;
        badge.textContent = badgeLabel;
        document.getElementById('viewModal').classList.add('open');
    }

    function closeView()   { document.getElementById('viewModal').classList.remove('open'); }
    function openSubmit()  { document.getElementById('submitModal').classList.add('open'); }
    function closeSubmit() { document.getElementById('submitModal').classList.remove('open'); }

    document.getElementById('viewModal').addEventListener('click',   function(e){ if (e.target === this) closeView(); });
    document.getElementById('submitModal').addEventListener('click', function(e){ if (e.target === this) closeSubmit(); });
</script>
@endpush