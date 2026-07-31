@extends('layouts.app')

@section('title', 'Incident Report Details')
@section('page-name', 'Incident Report Details')

@push('styles')
<style>
    .detail-shell {
        width: 100%;
        max-width: 1180px;
        display: grid;
        gap: 18px;
    }

    .panel-card {
        background: #fff;
        border: 1px solid #dde7f2;
        border-radius: 20px;
        box-shadow: 0 18px 48px rgba(18, 32, 51, 0.08);
        padding: 18px;
    }

    .panel-head {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: flex-start;
        margin-bottom: 16px;
    }

    .title {
        margin: 0;
        font-size: 26px;
        font-weight: 800;
    }

    .subtitle {
        margin-top: 6px;
        color: #607086;
        font-size: 14px;
    }

    .grid-2 {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .item {
        background: #f8fbff;
        border: 1px solid #edf2f7;
        border-radius: 14px;
        padding: 14px;
    }

    .label {
        display: block;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #607086;
        font-weight: 800;
        margin-bottom: 6px;
    }

    .value {
        font-size: 14px;
        font-weight: 700;
        color: #122033;
        line-height: 1.5;
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
        gap: 10px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 44px;
        padding: 0 16px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        border: 1px solid transparent;
        cursor: pointer;
    }

    .btn-primary { background: #0f62fe; color: #fff; }
    .btn-secondary { background: #fff; color: #122033; border-color: #dce5ef; }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 12px;
    }

    .gallery-item {
        display: grid;
        gap: 8px;
        background: #f8fbff;
        border: 1px solid #edf2f7;
        border-radius: 14px;
        padding: 10px;
        text-decoration: none;
        color: inherit;
    }

    .gallery-item img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 10px;
    }

    .empty-note {
        color: #607086;
        font-size: 13px;
    }

    @media (max-width: 860px) {
        .grid-2 { grid-template-columns: 1fr; }
        .actions { justify-content: stretch; }
        .actions .btn { width: 100%; }
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

<div class="detail-shell">
    <section class="panel-card">
        <div class="panel-head">
            <div>
                <h1 class="title">{{ $report->incident_code }}</h1>
                <div class="subtitle">Filed on {{ optional($report->created_at)->format('M d, Y h:i A') ?? '—' }}</div>
            </div>
            <div class="actions">
                <a class="btn btn-secondary" href="{{ route('reports.index') }}">Back</a>
                <a class="btn btn-secondary" href="{{ route('reports.export-pdf', $report) }}">Print PDF</a>
                <a class="btn btn-primary" href="{{ route('reports.edit', $report) }}">Edit</a>
            </div>
        </div>

        <div class="grid-2">
            <div class="item"><span class="label">Employee</span><div class="value">{{ $report->employee_name }}</div></div>
            <div class="item"><span class="label">Department</span><div class="value">{{ $report->department }}</div></div>
            <div class="item"><span class="label">Incident Type</span><div class="value">{{ $typeLabels[$report->incident_type] ?? ucwords(str_replace('_', ' ', $report->incident_type)) }}</div></div>
            <div class="item"><span class="label">Location</span><div class="value">{{ $report->location }}</div></div>
            <div class="item"><span class="label">Item Name</span><div class="value">{{ $report->item_name }}</div></div>
            <div class="item"><span class="label">Property / Serial No.</span><div class="value">{{ $report->property_serial_no ?: 'N/A' }}</div></div>
            <div class="item"><span class="label">Severity</span><div class="value"><span class="badge {{ $severityClasses[$report->severity] ?? 'badge--minor' }}">{{ ucfirst($report->severity) }}</span></div></div>
            <div class="item"><span class="label">Status</span><div class="value"><span class="badge {{ $statusClasses[$report->status] ?? 'badge--pending' }}">{{ ucwords(str_replace('_', ' ', $report->status)) }}</span></div></div>
            <div class="item"><span class="label">Estimated Cost</span><div class="value">{{ $report->estimated_cost !== null ? number_format((float) $report->estimated_cost, 2) : 'N/A' }}</div></div>
            <div class="item"><span class="label">Reported By</span><div class="value">{{ optional($report->reportedBy)->name ?? 'System' }}</div></div>
        </div>
    </section>

    <section class="panel-card">
        <div class="panel-head">
            <div>
                <h2 class="title" style="font-size:20px;">Narrative</h2>
                <div class="subtitle">Incident description, action taken, and remarks.</div>
            </div>
        </div>

        <div class="grid-2">
            <div class="item" style="grid-column:1 / -1;">
                <span class="label">Description</span>
                <div class="value">{{ $report->description }}</div>
            </div>
            <div class="item">
                <span class="label">Action Taken</span>
                <div class="value">{{ $report->action_taken ?: 'No action recorded yet.' }}</div>
            </div>
            <div class="item">
                <span class="label">Remarks</span>
                <div class="value">{{ $report->remarks ?: 'No remarks provided.' }}</div>
            </div>
        </div>
    </section>

    <section class="panel-card">
        <div class="panel-head">
            <div>
                <h2 class="title" style="font-size:20px;">Attachments</h2>
                <div class="subtitle">Photos and PDFs stored with the incident record.</div>
            </div>
        </div>

        <div class="gallery-grid">
            @forelse ($report->attachments as $attachment)
                <a class="gallery-item" href="/storage/{{ $attachment->file_path }}" target="_blank" rel="noopener">
                    @if (str_contains(strtolower($attachment->original_filename), '.pdf'))
                        <div class="empty-note" style="font-weight:800; text-align:center; padding:36px 0;">PDF</div>
                    @else
                        <img src="/storage/{{ $attachment->file_path }}" alt="{{ $attachment->original_filename }}">
                    @endif
                    <div>{{ $attachment->original_filename }}</div>
                </a>
            @empty
                <div class="empty-note">No attachments have been uploaded for this report.</div>
            @endforelse
        </div>
    </section>
</div>
@endsection
