@extends('layouts.app')

@section('title', 'Incident Reports')
@section('page-name', 'Incident Reports')

@push('styles')
<style>
    :root {
        --red: #C0172B;
        --red-dark: #8B0F1E;
        --red-light: #F9E9EB;
        --white: #ffffff;
        --gray-50: #F6F7FB;
        --gray-100: #EEF2F7;
        --gray-200: #E5EAF1;
        --gray-400: #8B97AC;
        --gray-500: #607086;
        --gray-800: #162033;
        --green: #1A7A4A;
        --green-light: #E9F7F0;
        --amber: #A35C00;
        --amber-light: #FFF5E8;
        --blue: #1A4FA3;
        --blue-light: #EEF5FF;
        --shadow: 0 18px 36px rgba(15, 23, 42, 0.08);
        --radius: 18px;
    }

    .incidents-page {
        width: 100%;
        padding: 4px 0 24px;
        color: var(--gray-800);
        font-family: 'Inter', Arial, sans-serif;
    }

    .page-shell {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .hero-card {
        background: linear-gradient(135deg, rgba(255,255,255,0.98) 0%, rgba(255,247,247,0.98) 100%);
        border: 1px solid rgba(192, 23, 43, 0.10);
        border-radius: var(--radius);
        padding: 18px 18px 16px;
        box-shadow: var(--shadow);
    }

    .hero-row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
    }

    .eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        font-size: 11px;
        font-weight: 800;
        color: var(--red-dark);
    }

    .hero-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--gray-800);
        line-height: 1.15;
        margin-top: 4px;
    }

    .hero-sub {
        margin-top: 4px;
        font-size: 13px;
        color: var(--gray-500);
        max-width: 780px;
        line-height: 1.45;
    }

    .hero-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }

    .chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 10px;
        border-radius: 999px;
        border: 1px solid #f1d8dd;
        background: rgba(255,255,255,0.96);
        color: var(--gray-600);
        font-size: 11px;
        font-weight: 700;
        box-shadow: 0 8px 18px rgba(192,23,43,0.06);
    }

    .chip strong { color: var(--red-dark); }

    .btn-row {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        border: 1px solid transparent;
        cursor: pointer;
        transition: transform 0.15s, box-shadow 0.15s, background 0.15s;
    }

    .btn:hover { transform: translateY(-1px); }

    .btn--ghost {
        background: #fff;
        color: var(--gray-800);
        border-color: #e4e8ef;
        box-shadow: 0 10px 18px rgba(15, 23, 42, 0.04);
    }

    .btn--ghost:hover { background: #f8fafc; border-color: #cfd8e3; }

    .btn--primary {
        color: #fff;
        background: linear-gradient(135deg, var(--red) 0%, #d83a52 100%);
        box-shadow: 0 10px 18px rgba(192,23,43,0.18);
    }

    .btn--primary:hover { box-shadow: 0 14px 24px rgba(192,23,43,0.24); }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
    }

    .stat-card {
        background: #fff;
        border: 1px solid #edf1f6;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 16px;
    }

    .stat-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-icon svg { width: 18px; height: 18px; }
    .stat-icon--total { background: var(--blue-light); color: var(--blue); }
    .stat-icon--open { background: var(--red-light); color: var(--red); }
    .stat-icon--pending { background: var(--amber-light); color: var(--amber); }
    .stat-icon--resolved { background: var(--green-light); color: var(--green); }

    .stat-tag {
        font-size: 11px;
        font-weight: 700;
        color: var(--gray-500);
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 800;
        color: var(--gray-800);
        line-height: 1;
        margin-bottom: 2px;
    }

    .stat-label {
        font-size: 12px;
        color: var(--gray-500);
    }

    .content-grid {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 18px;
        align-items: start;
    }

    .panel-card {
        background: #fff;
        border: 1px solid #edf1f6;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .panel-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 16px;
        border-bottom: 1px solid #edf1f6;
        background: linear-gradient(135deg, #fff 0%, #fffafc 100%);
    }

    .panel-title {
        font-size: 14px;
        font-weight: 800;
        color: var(--gray-800);
    }

    .panel-sub {
        font-size: 12px;
        color: var(--gray-500);
        margin-top: 2px;
    }

    .panel-body { padding: 14px 16px 16px; }

    .toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
        margin-bottom: 12px;
    }

    .search-box,
    .filter-select {
        border: 1px solid #e3e8ef;
        border-radius: 10px;
        background: #fff;
        color: var(--gray-800);
        font-size: 13px;
        padding: 10px 12px;
        min-height: 40px;
    }

    .search-box {
        display: flex;
        align-items: center;
        gap: 8px;
        flex: 1;
        min-width: 240px;
    }

    .search-box input {
        border: none;
        outline: none;
        width: 100%;
        background: transparent;
        color: var(--gray-800);
        font-size: 13px;
    }

    .filter-select { min-width: 140px; }

    .table-wrap { overflow-x: auto; }
    .table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .table th,
    .table td {
        padding: 12px 10px;
        text-align: left;
        border-bottom: 1px solid #f1f4f8;
        vertical-align: middle;
    }

    .table th {
        color: var(--gray-500);
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-weight: 800;
        background: #fbfcff;
    }

    .table tr:hover { background: #fff9fa; }

    .emp-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .emp-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        color: var(--gray-800);
        font-size: 11px;
        flex-shrink: 0;
    }

    .emp-avatar--r { background: #ffe7ea; color: var(--red); }
    .emp-avatar--b { background: #edf5ff; color: var(--blue); }
    .emp-avatar--g { background: #eefaf4; color: var(--green); }

    .emp-name { font-weight: 700; color: var(--gray-800); }
    .emp-type { font-size: 12px; color: var(--gray-500); margin-top: 1px; }

    .badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 8px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .badge--open { background: #fee2e2; color: #991b1b; }
    .badge--pending { background: #fff7db; color: #a16207; }
    .badge--review { background: #e0f2fe; color: #075985; }
    .badge--resolved { background: #dcfce7; color: #166534; }

    .actions { display: flex; gap: 8px; justify-content: flex-end; }

    .icon-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #edf1f6;
        background: #fff;
        color: var(--gray-600);
        cursor: pointer;
        transition: background 0.15s, color 0.15s;
    }

    .icon-btn:hover { background: #fff5f6; color: var(--red-dark); }

    .mini-stack { display: flex; flex-direction: column; gap: 14px; }

    .mini-card {
        background: #fff;
        border: 1px solid #edf1f6;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 14px 16px;
    }

    .mini-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--gray-500);
        font-weight: 800;
    }

    .mini-note {
        font-size: 13px;
        color: var(--gray-800);
        margin-top: 6px;
        line-height: 1.45;
    }

    .mini-list { display: flex; flex-direction: column; gap: 10px; margin-top: 8px; }
    .mini-row {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 10px 0;
        border-top: 1px solid #f1f4f8;
    }
    .mini-row:first-child { border-top: none; padding-top: 0; }
    .mini-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-top: 6px;
        flex-shrink: 0;
    }

    .pagination-wrap {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        padding-top: 12px;
        color: var(--gray-500);
        font-size: 12px;
    }

    .page-btns { display: flex; gap: 6px; align-items: center; }
    .page-btn {
        min-width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: 1px solid #edf1f6;
        background: #fff;
        color: var(--gray-800);
        text-decoration: none;
        font-weight: 700;
    }
    .page-btn.active { background: var(--red); border-color: var(--red); color: #fff; }

    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.45);
        display: none;
        align-items: center;
        justify-content: center;
        padding: 18px;
        z-index: 1000;
    }
    .modal-overlay.open { display: flex; }
    .modal {
        width: min(960px, 100%);
        max-height: 90vh;
        overflow: auto;
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 24px 48px rgba(15, 23, 42, 0.18);
        border: 1px solid #edf1f6;
    }
    .modal-header,
    .modal-footer,
    .modal-body { padding: 14px 16px; }
    .modal-header { border-bottom: 1px solid #edf1f6; display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; }
    .modal-title { font-size: 16px; font-weight: 800; color: var(--gray-800); }
    .modal-sub { font-size: 12px; color: var(--gray-500); margin-top: 2px; }
    .modal-close { border: none; background: #fff5f6; color: var(--red-dark); width: 32px; height: 32px; border-radius: 10px; cursor: pointer; }
    .meta-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .meta-item { border: 1px solid #edf1f6; border-radius: 12px; background: #fff; padding: 10px; }
    .meta-label { font-size: 11px; text-transform: uppercase; letter-spacing: 0.08em; color: var(--gray-500); font-weight: 800; }
    .meta-value { font-size: 13px; color: var(--gray-800); margin-top: 4px; font-weight: 700; }
    .description-box { border: 1px solid #edf1f6; border-radius: 12px; padding: 10px; color: var(--gray-700); font-size: 13px; background: #fcfdff; }
    .pdf-preview { display: flex; align-items: center; gap: 10px; border: 1px dashed #f0cfd5; border-radius: 14px; padding: 10px; background: #fff9fa; color: var(--gray-700); }
    .pdf-preview svg { width: 26px; height: 26px; color: var(--red); }
    .pdf-name { font-weight: 700; color: var(--gray-800); }
    .pdf-sub { font-size: 12px; color: var(--gray-500); }

    @media (max-width: 1120px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .content-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 780px) {
        .hero-row { flex-direction: column; }
        .btn-row { justify-content: flex-start; }
        .stats-grid { grid-template-columns: 1fr; }
        .meta-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<main class="incidents-page">
    <div class="page-shell">

        <section class="hero-card">
            <div class="hero-row">
                <div>
                    <div class="eyebrow">Incident Management</div>
                    <div class="hero-title">Incident Reports</div>
                    <div class="hero-sub">Track, review, and resolve incident records with the same modern dashboard experience used across the app.</div>
                    <div class="hero-badges">
                        <span class="chip"><strong>Live</strong> status tracking</span>
                        <span class="chip"><strong>Modern</strong> dashboard UI</span>
                        <span class="chip"><strong>Fast</strong> review workflow</span>
                    </div>
                </div>
                <div class="btn-row">
                    <a href="{{ route('reports.index') }}" class="btn btn--ghost">View Reports</a>
                    <button type="button" class="btn btn--primary" onclick="openSubmit()">+ New Incident</button>
                </div>
            </div>
        </section>

        <section class="stats-grid">
            <article class="stat-card">
                <div class="stat-top"><div class="stat-icon stat-icon--total"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div><span class="stat-tag">Total</span></div>
                <div class="stat-value">7</div>
                <div class="stat-label">Total incident reports</div>
            </article>
            <article class="stat-card">
                <div class="stat-top"><div class="stat-icon stat-icon--open"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></div><span class="stat-tag">Open</span></div>
                <div class="stat-value">2</div>
                <div class="stat-label">Open / unresolved</div>
            </article>
            <article class="stat-card">
                <div class="stat-top"><div class="stat-icon stat-icon--pending"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div><span class="stat-tag">Pending</span></div>
                <div class="stat-value">2</div>
                <div class="stat-label">Awaiting review</div>
            </article>
            <article class="stat-card">
                <div class="stat-top"><div class="stat-icon stat-icon--resolved"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></div><span class="stat-tag">Resolved</span></div>
                <div class="stat-value">1</div>
                <div class="stat-label">Completed cases</div>
            </article>
        </section>

        <section class="content-grid">
            <article class="panel-card">
                <div class="panel-head">
                    <div>
                        <div class="panel-title">Recent Incident Records</div>
                        <div class="panel-sub">Monitor case status and file actions from one place.</div>
                    </div>
                    <button type="button" class="btn btn--ghost" onclick="openSubmit()">+ Add</button>
                </div>
                <div class="panel-body">
                    <div class="toolbar">
                        <label class="search-box" aria-label="Search incidents">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            <input type="text" placeholder="Search by employee, type, or status" />
                        </label>
                        <select class="filter-select"><option>All Status</option><option>Open</option><option>Pending</option><option>Under Review</option><option>Resolved</option></select>
                        <select class="filter-select"><option>All Types</option><option>Workplace Incident</option><option>Attendance Violation</option><option>Policy Violation</option></select>
                    </div>

                    <div class="table-wrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>IR No.</th>
                                    <th>Employee</th>
                                    <th>Type</th>
                                    <th>Date Filed</th>
                                    <th>Status</th>
                                    <th style="text-align:right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>IR-0001</td>
                                    <td>
                                        <div class="emp-cell">
                                            <div class="emp-avatar emp-avatar--r">JD</div>
                                            <div><div class="emp-name">Juan Dela Cruz</div><div class="emp-type">Staff</div></div>
                                        </div>
                                    </td>
                                    <td>Workplace Incident</td>
                                    <td>Jun 10, 2026</td>
                                    <td><span class="badge badge--open">Open</span></td>
                                    <td>
                                        <div class="actions">
                                            <button type="button" class="icon-btn" title="View" onclick="openView('IR-0001','Jun 10, 2026','Juan Dela Cruz','Staff','Workplace Incident','1st Offense','Verbal Warning','Placeholder description for IR-0001','No file attached','open','Open')">👁️</button>
                                            <button type="button" class="icon-btn" title="Download">⬇️</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>IR-0002</td>
                                    <td>
                                        <div class="emp-cell">
                                            <div class="emp-avatar emp-avatar--b">MS</div>
                                            <div><div class="emp-name">Maria Santos</div><div class="emp-type">Supervisor</div></div>
                                        </div>
                                    </td>
                                    <td>Attendance Violation</td>
                                    <td>Jun 09, 2026</td>
                                    <td><span class="badge badge--pending">Pending</span></td>
                                    <td>
                                        <div class="actions">
                                            <button type="button" class="icon-btn" title="View" onclick="openView('IR-0002','Jun 09, 2026','Maria Santos','Supervisor','Attendance Violation','2nd Offense','Written Warning','Placeholder description for IR-0002','report-2.pdf','pending','Pending')">👁️</button>
                                            <button type="button" class="icon-btn" title="Download">⬇️</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>IR-0003</td>
                                    <td>
                                        <div class="emp-cell">
                                            <div class="emp-avatar emp-avatar--g">PR</div>
                                            <div><div class="emp-name">Pedro Reyes</div><div class="emp-type">Manager</div></div>
                                        </div>
                                    </td>
                                    <td>Policy Violation</td>
                                    <td>Jun 08, 2026</td>
                                    <td><span class="badge badge--review">Under Review</span></td>
                                    <td>
                                        <div class="actions">
                                            <button type="button" class="icon-btn" title="View" onclick="openView('IR-0003','Jun 08, 2026','Pedro Reyes','Manager','Policy Violation','3rd Offense','Suspension','Placeholder description for IR-0003','No file attached','review','Under Review')">👁️</button>
                                            <button type="button" class="icon-btn" title="Download">⬇️</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination-wrap">
                        <div>Showing 1–3 of 7 records</div>
                        <div class="page-btns">
                            <a class="page-btn" href="#">‹</a>
                            <a class="page-btn active" href="#">1</a>
                            <a class="page-btn" href="#">2</a>
                            <a class="page-btn" href="#">›</a>
                        </div>
                    </div>
                </div>
            </article>

            <aside class="mini-stack">
                <article class="mini-card">
                    <div class="mini-label">Review Focus</div>
                    <div class="mini-note">Prioritize open and pending cases to improve resolution speed and maintain audit readiness.</div>
                    <div class="mini-list">
                        <div class="mini-row"><span class="mini-dot" style="background:var(--red)"></span><div><strong>2 open cases</strong><br><span style="color:var(--gray-500);font-size:12px;">Require immediate follow-up</span></div></div>
                        <div class="mini-row"><span class="mini-dot" style="background:var(--amber)"></span><div><strong>2 pending reviews</strong><br><span style="color:var(--gray-500);font-size:12px;">Waiting for supervisor action</span></div></div>
                        <div class="mini-row"><span class="mini-dot" style="background:var(--green)"></span><div><strong>1 resolved</strong><br><span style="color:var(--gray-500);font-size:12px;">Ready for final documentation</span></div></div>
                    </div>
                </article>

                <article class="mini-card">
                    <div class="mini-label">Quick Notes</div>
                    <div class="mini-note">Use the same card-based experience across reports, employees, and incidents for visual consistency.</div>
                </article>
            </aside>
        </section>
    </div>
</main>

<div class="modal-overlay" id="viewModal">
    <div class="modal">
        <div class="modal-header">
            <div>
                <div class="modal-title" id="modalIRNum"></div>
                <div class="modal-sub" id="modalDate"></div>
            </div>
            <button class="modal-close" type="button" onclick="closeView()">✕</button>
        </div>
        <div class="modal-body">
            <div class="meta-grid">
                <div class="meta-item"><div class="meta-label">Employee</div><div class="meta-value" id="modalEmp"></div></div>
                <div class="meta-item"><div class="meta-label">Employment Type</div><div class="meta-value" id="modalEmpType"></div></div>
                <div class="meta-item"><div class="meta-label">IR Type</div><div class="meta-value" id="modalIRType"></div></div>
                <div class="meta-item"><div class="meta-label">Offense Level</div><div class="meta-value" id="modalOffense"></div></div>
                <div class="meta-item"><div class="meta-label">Sanction</div><div class="meta-value" id="modalSanction"></div></div>
                <div class="meta-item"><div class="meta-label">Filed by</div><div class="meta-value">Juan Dela Cruz</div></div>
            </div>
            <div style="margin-top:10px;">
                <div class="meta-label" style="margin-bottom:8px;">Description</div>
                <div class="description-box" id="modalDesc"></div>
            </div>
            <div style="margin-top:10px;">
                <div class="meta-label" style="margin-bottom:8px;">Attached File</div>
                <div class="pdf-preview"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="13" x2="15" y2="13"/><line x1="9" y1="17" x2="15" y2="17"/></svg><div><div class="pdf-name" id="modalFileName"></div><div class="pdf-sub">Click below to download</div></div></div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn--ghost" type="button" onclick="closeView()">Close</button>
            <button class="btn btn--primary" type="button">Download PDF</button>
        </div>
    </div>
</div>

<div class="modal-overlay" id="submitModal">
    <div class="modal">
        <div class="modal-header">
            <div>
                <div class="modal-title">Submit New Incident Report</div>
                <div class="modal-sub">Add a new report using the same polished workflow.</div>
            </div>
            <button class="modal-close" type="button" onclick="closeSubmit()">✕</button>
        </div>
        <div class="modal-body">
            <div class="meta-grid">
                <div class="meta-item"><div class="meta-label">Employee</div><select class="filter-select" style="width:100%;margin-top:4px;"><option>Juan Dela Cruz</option></select></div>
                <div class="meta-item"><div class="meta-label">Date of Incident</div><input type="date" class="filter-select" style="width:100%;margin-top:4px;" /></div>
                <div class="meta-item"><div class="meta-label">IR Type</div><select class="filter-select" style="width:100%;margin-top:4px;"><option>Workplace Incident</option></select></div>
                <div class="meta-item"><div class="meta-label">Offense Level</div><select class="filter-select" style="width:100%;margin-top:4px;"><option>1st Offense</option></select></div>
                <div class="meta-item"><div class="meta-label">Sanction Given</div><select class="filter-select" style="width:100%;margin-top:4px;"><option>Verbal Warning</option></select></div>
                <div class="meta-item"><div class="meta-label">Status</div><select class="filter-select" style="width:100%;margin-top:4px;"><option>Open</option></select></div>
            </div>
            <div style="margin-top:10px;" class="meta-item"><div class="meta-label">Description</div><textarea class="filter-select" style="width:100%;min-height:88px;margin-top:4px;" placeholder="Describe the incident in detail…"></textarea></div>
        </div>
        <div class="modal-footer">
            <button class="btn btn--ghost" type="button" onclick="closeSubmit()">Cancel</button>
            <button class="btn btn--primary" type="button">Submit IR</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openView(irNum, date, emp, empType, irType, offense, sanction, desc, fileName, badgeClass, badgeLabel) {
        document.getElementById('modalIRNum').textContent = irNum;
        document.getElementById('modalDate').textContent = 'Filed ' + date;
        document.getElementById('modalEmp').textContent = emp;
        document.getElementById('modalEmpType').textContent = empType;
        document.getElementById('modalIRType').textContent = irType;
        document.getElementById('modalOffense').textContent = offense;
        document.getElementById('modalSanction').textContent = sanction;
        document.getElementById('modalDesc').textContent = desc;
        document.getElementById('modalFileName').textContent = fileName;
        document.getElementById('viewModal').classList.add('open');
    }

    function closeView() { document.getElementById('viewModal').classList.remove('open'); }
    function openSubmit() { document.getElementById('submitModal').classList.add('open'); }
    function closeSubmit() { document.getElementById('submitModal').classList.remove('open'); }

    document.getElementById('viewModal')?.addEventListener('click', function (e) { if (e.target === this) closeView(); });
    document.getElementById('submitModal')?.addEventListener('click', function (e) { if (e.target === this) closeSubmit(); });
</script>
@endpush
