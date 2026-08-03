@extends('layouts.app')

@section('title', 'Accounts')
@section('page-name', 'Accounts')

@push('styles')
<style>
    .account-index {
        width: 100%;
        display: grid;
        gap: 18px;
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
        gap: 10px;
        flex-wrap: wrap;
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
        grid-template-columns: 1.5fr auto;
        gap: 10px;
        margin-bottom: 14px;
    }

    .search-box,
    .filter-select {
        width: 100%;
        min-height: 44px;
        border: 1px solid #dce5ef;
        border-radius: 12px;
        padding: 12px 14px;
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
        min-width: 860px;
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

    .badge--pending { background: #fff0c9; color: #854d0e; }
    .badge--approved { background: #dff5e8; color: #166534; }
    .badge--rejected { background: #fee2e2; color: #991b1b; }
    .badge--default { background: #e5e7eb; color: #374151; }

    .actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .icon-btn,
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 34px;
        border-radius: 10px;
        border: 1px solid #dce5ef;
        background: #fff;
        color: #122033;
        text-decoration: none;
        padding: 0 10px;
        cursor: pointer;
        font: inherit;
        font-weight: 700;
    }

    .icon-btn:hover,
    .action-btn:hover {
        border-color: #cdd9eb;
        box-shadow: 0 8px 18px rgba(18, 32, 51, 0.08);
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

    @media (max-width: 1100px) {
        .toolbar {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 720px) {
        .hero-card {
            flex-direction: column;
        }

        .hero-actions {
            justify-content: flex-start;
        }
    }
</style>
@endpush

@section('content')
@php
    $statusLabels = [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
    ];

    $statusClasses = [
        'pending' => 'badge--pending',
        'approved' => 'badge--approved',
        'rejected' => 'badge--rejected',
    ];
@endphp

<div class="account-index">
    <section class="hero-card">
        <div>
            <div class="eyebrow">Administration</div>
            <div class="hero-title">Accounts</div>
            <div class="hero-sub">Review account requests, approve access, reject applications, and keep the admin panel aligned with the rest of the system.</div>
        </div>
        <div class="hero-actions">
            <a class="btn btn-secondary" href="{{ route('employees.index') }}">Employees</a>
            <a class="btn btn-primary" href="{{ route('reports.index') }}">Incident Reports</a>
        </div>
    </section>

    <section class="panel-card">
        <div class="panel-head">
            <div>
                <h2 class="panel-title">Account Requests</h2>
                <div class="panel-sub">Use the filter and search to review account status quickly.</div>
            </div>
        </div>

        <div class="toolbar">
            <input id="account-search" class="search-box" type="search" placeholder="Search name or email..." aria-label="Search accounts">
            <form method="GET" style="display:flex;gap:10px;align-items:center;justify-content:flex-end;flex-wrap:wrap">
                <select name="filter" class="filter-select" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="pending" {{ request('filter') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('filter') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('filter') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </form>
        </div>

        @if(session('success'))
            <div style="padding:10px 12px;background:#dff5e8;border:1px solid #bfe8cd;border-radius:12px;margin-bottom:12px;color:#166534;font-weight:700;">{{ session('success') }}</div>
        @endif

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Registered</th>
                        <th>Status</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        @php $status = $u->status ?? 'approved'; @endphp
                        <tr class="account-row" data-search="{{ strtolower($u->name . ' ' . $u->email) }}">
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>{{ $u->created_at->diffForHumans() }}</td>
                            <td><span class="badge {{ $statusClasses[$status] ?? 'badge--default' }}">{{ $statusLabels[$status] ?? ucfirst($status) }}</span></td>
                            <td>
                                <div class="actions">
                                    @if($status === 'pending')
                                        <form method="POST" action="{{ route('accounts.approve', $u) }}" onsubmit="event.stopPropagation();">
                                            @csrf
                                            <button type="submit" class="action-btn" style="background:#0f62fe;color:#fff;border-color:#0f62fe;">Approve</button>
                                        </form>
                                        <button type="button" class="action-btn" onclick="document.getElementById('reject-{{ $u->id }}').style.display='block'">Reject</button>
                                        <form id="reject-{{ $u->id }}" method="POST" action="{{ route('accounts.reject', $u) }}" style="display:none;min-width:280px" onsubmit="event.stopPropagation();">
                                            @csrf
                                            <input name="reason" placeholder="Optional rejection reason" class="search-box" style="margin-bottom:8px;">
                                            <div style="display:flex;gap:8px;justify-content:flex-end;">
                                                <button type="submit" class="action-btn" style="background:#ef4444;color:#fff;border-color:#ef4444;">Confirm</button>
                                                <button type="button" class="action-btn" onclick="this.closest('form').style.display='none'">Cancel</button>
                                            </div>
                                        </form>
                                    @endif

                                    @if(in_array($status, ['pending', 'approved'], true))
                                        <form method="POST" action="{{ route('accounts.destroy', $u) }}" onsubmit="return confirm('Are you sure you want to delete this account?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn" style="color:#991b1b;">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5"><div class="empty-state">No accounts found.</div></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">
            <div>Showing {{ $users->count() }} of {{ $users->total() }} accounts</div>
            <div>{{ $users->links() }}</div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    const accountSearch = document.getElementById('account-search');
    const accountRows = Array.from(document.querySelectorAll('.account-row'));

    accountSearch?.addEventListener('input', () => {
        const query = accountSearch.value.trim().toLowerCase();
        accountRows.forEach((row) => {
            row.style.display = row.dataset.search.includes(query) ? '' : 'none';
        });
    });
</script>
@endpush
