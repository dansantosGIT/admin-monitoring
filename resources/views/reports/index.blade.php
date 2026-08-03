@extends('layouts.app')

@section('title', 'Incident Reports')
@section('page-name', 'Incident Reports')

@push('styles')
<style>
    .incident-index {
        width: 100%;
        display: grid;
        gap: 18px;
        color: #122033;
    }

    .hero-card,
    .panel-card,
    .stat-card {
        background: #ffffff;
        border: 1px solid #dde7f2;
        border-radius: 20px;
        box-shadow: 0 18px 48px rgba(18, 32, 51, 0.08);
    }

    .hero-card {
        padding: 20px;
        display: flex;
        justify-content: space-between;
        gap: 16px;
        align-items: flex-start;
        background: linear-gradient(135deg, #ffffff 0%, #f6f9ff 100%);
    }

    .eyebrow {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #0f62fe;
    }

    .hero-title {
        font-size: 26px;
        font-weight: 800;
        margin: 6px 0 8px;
    }

    .hero-sub {
        color: #607086;
        max-width: 760px;
        line-height: 1.6;
        font-size: 14px;
    }

    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: flex-end;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        padding: 10px 14px;
        font-weight: 700;
        font-size: 13px;
        text-decoration: none;
        border: 1px solid transparent;
        cursor: pointer;
    }

    .btn-primary {
        background: #0f62fe;
        color: #fff;
    }

    .btn-secondary {
        background: #fff;
        border-color: #dce5ef;
        color: #122033;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }

    .stat-card {
        padding: 16px;
    }

    .stat-label {
        font-size: 12px;
        color: #607086;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .stat-value {
        margin-top: 8px;
        font-size: 28px;
        font-weight: 800;
        color: #122033;
    }

    .stat-note {
        margin-top: 4px;
        font-size: 13px;
        color: #607086;
    }

    .panel-card {
        padding: 16px;
    }

    .panel-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 14px;
    }

    .panel-title {
        font-size: 18px;
        font-weight: 800;
        margin: 0;
    }

    .panel-sub {
        margin-top: 4px;
        font-size: 13px;
        color: #607086;
    }

    .toolbar {
        display: grid;
        grid-template-columns: 1.5fr repeat(3, minmax(140px, 1fr));
        gap: 10px;
        margin-bottom: 14px;
    }

    .input,
    .select {
        width: 100%;
        min-height: 44px;
        border: 1px solid #dce5ef;
        border-radius: 12px;
        padding: 10px 12px;
        font: inherit;
        background: #fff;
        color: #122033;
    }

    .table-wrap {
        overflow-x: auto;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        min-width: 960px;
    }

    .table th,
    .table td {
        padding: 14px 12px;
        border-bottom: 1px solid #edf2f7;
        text-align: left;
        vertical-align: middle;
        font-size: 13px;
    }

    .table th {
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-size: 11px;
        color: #607086;
        font-weight: 800;
        background: #f8fbff;
    }

    .row-link {
        text-decoration: none;
        color: inherit;
    }

    .employee-cell {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .employee-name {
        font-weight: 700;
    }

    .employee-sub,
    .muted {
        color: #607086;
        font-size: 12px;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .badge--minor { background: #dff5e8; color: #166534; }
    .badge--major { background: #fff0c9; color: #854d0e; }
    .badge--critical { background: #fee2e2; color: #991b1b; }
    .badge--pending { background: #fee2e2; color: #991b1b; }
    .badge--under_investigation { background: #fff0c9; color: #854d0e; }
    .badge--resolved { background: #dff5e8; color: #166534; }
    .badge--closed { background: #e5e7eb; color: #374151; }

    .actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .icon-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 10px;
        border: 1px solid #dce5ef;
        background: #fff;
        color: #122033;
        text-decoration: none;
    }

    .empty-state {
        padding: 32px 18px;
        text-align: center;
        color: #607086;
    }

    .pagination-wrap {
        margin-top: 14px;
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
        color: #607086;
        font-size: 13px;
    }

    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(18, 32, 51, 0.56);
        display: none;
        align-items: center;
        justify-content: center;
        padding: 18px;
        z-index: 1200;
    }

    .modal-overlay.open {
        display: flex;
    }

    .modal {
        width: min(1040px, 100%);
        max-height: 92vh;
        overflow: auto;
        background: #fff;
        border-radius: 22px;
        border: 1px solid #dde7f2;
        box-shadow: 0 28px 70px rgba(18, 32, 51, 0.24);
    }

    .modal-head {
        position: sticky;
        top: 0;
        z-index: 2;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        padding: 18px;
        background: linear-gradient(135deg, #ffffff 0%, #f6f9ff 100%);
        border-bottom: 1px solid #edf2f7;
    }

    .modal-title {
        margin: 0;
        font-size: 22px;
        font-weight: 800;
        color: #122033;
    }

    .modal-sub {
        margin-top: 4px;
        color: #607086;
        font-size: 13px;
        line-height: 1.5;
    }

    .modal-close-x {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: 1px solid #dce5ef;
        background: #fff;
        color: #122033;
        cursor: pointer;
        flex-shrink: 0;
    }

    .modal-body {
        padding: 18px;
        display: grid;
        gap: 14px;
    }

    .modal-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .modal-card {
        background: #f8fbff;
        border: 1px solid #edf2f7;
        border-radius: 16px;
        padding: 14px;
    }

    .modal-label {
        display: block;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #607086;
        font-weight: 800;
        margin-bottom: 6px;
    }

    .modal-value {
        font-size: 14px;
        font-weight: 700;
        color: #122033;
        line-height: 1.5;
        word-break: break-word;
    }

    .modal-section-title {
        font-size: 15px;
        font-weight: 800;
        color: #122033;
        margin: 0 0 8px;
    }

    .modal-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 12px;
    }

    .modal-thumb {
        display: grid;
        gap: 8px;
        background: #fff;
        border: 1px solid #dce5ef;
        border-radius: 14px;
        padding: 10px;
        text-decoration: none;
        color: inherit;
    }

    .modal-thumb img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 10px;
    }

    .modal-footer {
        position: sticky;
        bottom: 0;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 14px 18px 18px;
        background: linear-gradient(180deg, rgba(255,255,255,0.78) 0%, #fff 30%);
        border-top: 1px solid #edf2f7;
    }

    .modal-empty {
        color: #607086;
        font-size: 13px;
    }

    .table-row-clickable {
        cursor: pointer;
    }

    .table-row-clickable:hover {
        background: #fbfdff;
    }

    .preview-link {
        cursor: zoom-in;
    }

    @media (max-width: 1100px) {
        .stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .toolbar {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 720px) {
        .hero-card {
            flex-direction: column;
        }

        .hero-actions {
            justify-content: flex-start;
        }

        .stats-grid,
        .toolbar {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
@php
    $severityClasses = [
        'minor' => 'badge--minor',
        'major' => 'badge--major',
        'critical' => 'badge--critical',
    ];

    $statusClasses = [
        'pending' => 'badge--pending',
        'under_investigation' => 'badge--under_investigation',
        'resolved' => 'badge--resolved',
        'closed' => 'badge--closed',
    ];

    $typeLabels = [
        'equipment_damage' => 'Equipment Damage',
        'equipment_loss' => 'Equipment Loss',
        'vehicle_incident' => 'Vehicle Incident',
        'other' => 'Other',
    ];
@endphp

<div class="incident-index">
    <section class="hero-card">
        <div>
            <div class="eyebrow">Incident Management</div>
            <div class="hero-title">Incident Reports</div>
            <div class="hero-sub">Track property, equipment, and vehicle incidents with filters, review states, and attachment-ready case records.</div>
        </div>
        <div class="hero-actions">
            <a class="btn btn-secondary" href="{{ route('reports.index') }}">Reports Home</a>
            <a class="btn btn-primary" href="{{ route('reports.create') }}">+ New Report</a>
        </div>
    </section>

    <section class="stats-grid">
        <article class="stat-card">
            <div class="stat-label">Total</div>
            <div class="stat-value">{{ $reports->total() }}</div>
            <div class="stat-note">All incident reports</div>
        </article>
        <article class="stat-card">
            <div class="stat-label">Pending</div>
            <div class="stat-value">{{ $reports->where('status', 'pending')->count() }}</div>
            <div class="stat-note">Awaiting action</div>
        </article>
        <article class="stat-card">
            <div class="stat-label">Resolved</div>
            <div class="stat-value">{{ $reports->where('status', 'resolved')->count() }}</div>
            <div class="stat-note">Closed out cases</div>
        </article>
        <article class="stat-card">
            <div class="stat-label">Critical</div>
            <div class="stat-value">{{ $reports->where('severity', 'critical')->count() }}</div>
            <div class="stat-note">Priority cases</div>
        </article>
    </section>

    <section class="panel-card">
        <div class="panel-head">
            <div>
                <h2 class="panel-title">Incident Log</h2>
                <div class="panel-sub">Filter by status, severity, incident type, or search by code, employee, or location.</div>
            </div>
        </div>

        <form method="GET" action="{{ route('reports.index') }}" class="toolbar">
            <input class="input" type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search incident code, employee, department, or location">
            <select class="select" name="status">
                <option value="">All Statuses</option>
                @foreach (['pending' => 'Pending', 'under_investigation' => 'Under Investigation', 'resolved' => 'Resolved', 'closed' => 'Closed'] as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <select class="select" name="severity">
                <option value="">All Severity</option>
                @foreach (['minor' => 'Minor', 'major' => 'Major', 'critical' => 'Critical'] as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['severity'] ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <select class="select" name="incident_type">
                <option value="">All Types</option>
                @foreach ($typeLabels as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['incident_type'] ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <button class="btn btn-primary" type="submit">Apply Filters</button>
        </form>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Employee</th>
                        <th>Type</th>
                        <th>Item</th>
                        <th>Severity</th>
                        <th>Status</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reports as $report)
                        <tr class="table-row-clickable" data-report-url="{{ route('reports.show', $report) }}" data-edit-url="{{ route('reports.edit', $report) }}">
                            <td>{{ $report->incident_code }}</td>
                            <td>{{ optional($report->date_of_incident)->format('M d, Y') ?? '—' }}</td>
                            <td>
                                <div class="employee-cell">
                                    <span class="employee-name">{{ $report->employee_name }}</span>
                                    <span class="employee-sub">{{ $report->department ?: 'No department' }}</span>
                                </div>
                            </td>
                            <td>{{ $typeLabels[$report->incident_type] ?? ucwords(str_replace('_', ' ', $report->incident_type)) }}</td>
                            <td>{{ $report->item_name }}</td>
                            <td><span class="badge {{ $severityClasses[$report->severity] ?? 'badge--minor' }}">{{ ucfirst($report->severity) }}</span></td>
                            <td><span class="badge {{ $statusClasses[$report->status] ?? 'badge--pending' }}">{{ ucwords(str_replace('_', ' ', $report->status)) }}</span></td>
                            <td>
                                <div class="actions">
                                    <button class="icon-btn preview-trigger" type="button" title="Preview">↗</button>
                                    <a class="icon-btn" href="{{ route('reports.edit', $report) }}" title="Edit">✎</a>
                                    <a class="icon-btn" href="{{ route('reports.export-pdf', $report) }}" title="PDF">PDF</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    No incident reports found. Create the first report to start tracking incidents.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">
            <div>Showing {{ $reports->firstItem() ?? 0 }} to {{ $reports->lastItem() ?? 0 }} of {{ $reports->total() }} records</div>
            <div>{{ $reports->links() }}</div>
        </div>
    </section>
</div>

<div class="modal-overlay" id="reportModal" aria-hidden="true">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="reportModalTitle">
        <div class="modal-head">
            <div>
                <h2 class="modal-title" id="reportModalTitle">Incident Report</h2>
                <div class="modal-sub" id="reportModalSub">Loading report details...</div>
            </div>
            <button type="button" class="modal-close-x" id="reportModalClose" aria-label="Close modal">✕</button>
        </div>

        <div class="modal-body" id="reportModalBody">
            <div class="modal-empty">Select a report to preview it here.</div>
        </div>

        <div class="modal-footer">
            <a class="btn btn-secondary" id="reportModalEdit" href="#">Edit</a>
            <button class="btn btn-primary" type="button" id="reportModalCloseBtn">Close Modal</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const modal = document.getElementById('reportModal');
        const modalTitle = document.getElementById('reportModalTitle');
        const modalSub = document.getElementById('reportModalSub');
        const modalBody = document.getElementById('reportModalBody');
        const modalClose = document.getElementById('reportModalClose');
        const modalCloseBtn = document.getElementById('reportModalCloseBtn');
        const modalEdit = document.getElementById('reportModalEdit');
        const rows = Array.from(document.querySelectorAll('.table-row-clickable'));

        const severityClasses = {
            minor: 'badge--minor',
            major: 'badge--major',
            critical: 'badge--critical',
        };

        const statusClasses = {
            pending: 'badge--pending',
            under_investigation: 'badge--under_investigation',
            resolved: 'badge--resolved',
            closed: 'badge--closed',
        };

        const typeLabels = {
            equipment_damage: 'Equipment Damage',
            equipment_loss: 'Equipment Loss',
            vehicle_incident: 'Vehicle Incident',
            other: 'Other',
        };

        function openModal() {
            modal.classList.add('open');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modal.classList.remove('open');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        function setCard(label, value) {
            return `
                <div class="modal-card">
                    <span class="modal-label">${label}</span>
                    <div class="modal-value">${value || '—'}</div>
                </div>
            `;
        }

        function renderGallery(items) {
            if (!items.length) {
                return '<div class="modal-empty">No attachments uploaded for this report.</div>';
            }

            return `
                <div>
                    <h3 class="modal-section-title">Attachments</h3>
                    <div class="modal-gallery">
                        ${items.map((item) => `
                            <a class="modal-thumb preview-link" href="${item.url}" target="_blank" rel="noopener">
                                ${item.isPdf ? '<div class="modal-empty" style="font-weight:800;text-align:center;padding:34px 0;">PDF</div>' : `<img src="${item.url}" alt="${item.name}">`}
                                <div class="modal-value" style="font-size:12px;font-weight:600;">${item.name}</div>
                            </a>
                        `).join('')}
                    </div>
                </div>
            `;
        }

        async function loadReport(url, editUrl) {
            modalTitle.textContent = 'Incident Report';
            modalSub.textContent = 'Loading report details...';
            modalBody.innerHTML = '<div class="modal-empty">Loading details...</div>';
            modalEdit.href = editUrl;
            openModal();

            try {
                const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (!response.ok) {
                    throw new Error('Failed to load report details.');
                }

                const html = await response.text();
                const doc = new DOMParser().parseFromString(html, 'text/html');
                const title = doc.querySelector('.title')?.textContent?.trim() || 'Incident Report';
                const subtitle = doc.querySelector('.subtitle')?.textContent?.trim() || '';
                const fieldMap = new Map();

                doc.querySelectorAll('.item').forEach((item) => {
                    const label = item.querySelector('.label')?.textContent?.trim().replace(/\*$/, '') || '';
                    const value = item.querySelector('.value')?.textContent?.trim() || '';
                    if (label) {
                        fieldMap.set(label, value);
                    }
                });

                const incidentCode = title;
                const incidentDate = subtitle.replace(/^Filed on\s*/i, '');

                const gallery = Array.from(doc.querySelectorAll('.gallery-item')).map((item) => {
                    const link = item.getAttribute('href') || '#';
                    const name = item.textContent.trim().replace(/\s+/g, ' ');
                    const img = item.querySelector('img');
                    const isPdf = !img;

                    return {
                        url: link,
                        name,
                        isPdf,
                    };
                });

                modalTitle.textContent = incidentCode;
                modalSub.textContent = incidentDate ? `Filed on ${incidentDate}` : '';

                const severity = (fieldMap.get('Severity') || '').toLowerCase();
                const status = (fieldMap.get('Status') || '').toLowerCase().replace(/\s+/g, '_');
                const incidentType = fieldMap.get('Incident Type') || '';

                modalBody.innerHTML = `
                    <div class="modal-grid">
                        ${setCard('Incident Code', incidentCode)}
                        ${setCard('Date', incidentDate)}
                        ${setCard('Employee', fieldMap.get('Employee'))}
                        ${setCard('Department', fieldMap.get('Department'))}
                        ${setCard('Incident Type', incidentType ? incidentType : '—')}
                        ${setCard('Item Name', fieldMap.get('Item Name'))}
                        ${setCard('Property / Serial No.', fieldMap.get('Property / Serial No.') || fieldMap.get('Property / Serial No'))}
                        ${setCard('Location', fieldMap.get('Location'))}
                        ${setCard('Severity', `<span class="badge ${severityClasses[severity] || 'badge--minor'}">${fieldMap.get('Severity') || '—'}</span>`)}
                        ${setCard('Status', `<span class="badge ${statusClasses[status] || 'badge--pending'}">${fieldMap.get('Status') || '—'}</span>`)}
                        ${setCard('Estimated Cost', fieldMap.get('Estimated Cost'))}
                        ${setCard('Reported By', fieldMap.get('Reported By'))}
                    </div>
                    <div class="modal-card">
                        <h3 class="modal-section-title">Description</h3>
                        <div class="modal-value">${fieldMap.get('Description') || '—'}</div>
                    </div>
                    <div class="modal-grid">
                        <div class="modal-card">
                            <h3 class="modal-section-title">Action Taken</h3>
                            <div class="modal-value">${fieldMap.get('Action Taken') || 'No action recorded yet.'}</div>
                        </div>
                        <div class="modal-card">
                            <h3 class="modal-section-title">Remarks</h3>
                            <div class="modal-value">${fieldMap.get('Remarks') || 'No remarks provided.'}</div>
                        </div>
                    </div>
                    ${renderGallery(gallery)}
                `;
            } catch (error) {
                modalSub.textContent = 'Unable to load report.';
                modalBody.innerHTML = `<div class="modal-empty">${error.message}</div>`;
            }
        }

        rows.forEach((row) => {
            row.addEventListener('click', (event) => {
                if (event.target.closest('a, button, form')) {
                    return;
                }

                const reportUrl = row.dataset.reportUrl;
                const editUrl = row.dataset.editUrl;
                if (reportUrl && editUrl) {
                    loadReport(reportUrl, editUrl);
                }
            });

            const previewTrigger = row.querySelector('.preview-trigger');
            previewTrigger?.addEventListener('click', (event) => {
                event.preventDefault();
                event.stopPropagation();
                const reportUrl = row.dataset.reportUrl;
                const editUrl = row.dataset.editUrl;
                if (reportUrl && editUrl) {
                    loadReport(reportUrl, editUrl);
                }
            });
        });

        modalClose?.addEventListener('click', closeModal);
        modalCloseBtn?.addEventListener('click', closeModal);
        modal?.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModal();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && modal.classList.contains('open')) {
                closeModal();
            }
        });
    })();
</script>
@endpush
