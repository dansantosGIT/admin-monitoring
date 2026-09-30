@extends('layouts.app')

@section('title', 'Vehicle Monitoring')
@section('page-name', 'Vehicle Monitoring')

@section('content')
@push('styles')
<style>
    .vehicle-list-head{align-items:center}.vehicle-toolbar-actions{display:flex;gap:8px;align-items:center}.vehicle-insight-bar{display:flex;align-items:center;gap:12px;padding:0 18px 14px}.vehicle-insight{display:inline-flex;align-items:center;gap:9px;border:0;background:#fff7ed;color:#9a3412;padding:8px 10px;border-radius:10px;text-align:left;cursor:pointer}.vehicle-insight small{display:block;color:#c2410c;font-size:11px;margin-top:2px}.vehicle-insight-icon{display:grid;place-items:center;width:22px;height:22px;border-radius:50%;background:#fed7aa;font-weight:800}.active-filter-count{font-size:12px;font-weight:800;color:#0f62fe;background:#dbe7ff;border-radius:999px;padding:5px 9px}.clear-filters{border:0;background:none;color:#0f62fe;font:inherit;font-size:12px;font-weight:700;cursor:pointer}.vehicle-card-grid{display:grid;gap:8px;padding:0 18px 18px}.vehicle-list-headings{display:grid;grid-template-columns:minmax(180px,1.5fr) 120px minmax(140px,1fr) minmax(150px,1fr) minmax(150px,1fr) 260px;gap:14px;padding:0 18px 8px;color:#607086;font-size:10px;font-weight:800;letter-spacing:.08em;text-transform:uppercase}.vehicle-row{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:12px;align-items:center;padding:12px;border:1px solid #dde7f2;border-radius:12px;background:#fff;transition:border-color .15s ease,box-shadow .15s ease}.vehicle-row:hover{border-color:#9bbcfb;box-shadow:0 8px 18px rgba(18,32,51,.07)}.vehicle-row-main{display:grid;grid-template-columns:8px minmax(150px,1.5fr) 120px minmax(140px,1fr) minmax(150px,1fr) minmax(150px,1fr) minmax(150px,1fr);gap:14px;align-items:center;color:inherit;text-decoration:none;min-width:0}.vehicle-status-dot{width:8px;height:8px;border-radius:50%;display:block}.vehicle-status-dot--active{background:#16a34a;box-shadow:0 0 0 4px #dcfce7}.vehicle-status-dot--idle{background:#d97706;box-shadow:0 0 0 4px #fef3c7}.vehicle-status-dot--offline{background:#dc2626;box-shadow:0 0 0 4px #fee2e2}.vehicle-row-identity{display:grid;gap:3px;min-width:0}.vehicle-row-identity .vehicle-card-call-sign{font-size:16px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.vehicle-row-identity .vehicle-card-type{white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.vehicle-row-detail,.vehicle-maintenance{display:grid;gap:4px;min-width:0}.vehicle-row-detail small,.vehicle-maintenance small{color:#607086;font-size:10px;font-weight:800;letter-spacing:.08em;text-transform:uppercase}.vehicle-row-detail strong,.vehicle-maintenance strong{font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.vehicle-maintenance.is-overdue strong{color:#b91c1c}.driver-badge{display:flex;align-items:center;gap:6px;min-width:0;font-size:13px;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.driver-badge span{display:grid;place-items:center;width:24px;height:24px;flex:0 0 24px;border-radius:50%;background:#dbe7ff;color:#1d4ed8;font-size:10px}.vehicle-row-actions{display:flex;align-items:center;justify-content:flex-end;gap:6px}.vehicle-quick-status{min-height:34px;border:1px solid #dce5ef;border-radius:8px;background:#fff;padding:0 7px;color:#122033;font:inherit;font-size:12px}.ui-button--small{min-height:34px;padding:0 10px;font-size:12px}.vehicle-select{width:16px;height:16px;accent-color:#0f62fe}.vehicle-overflow{position:relative}.vehicle-overflow summary{display:grid;place-items:center;width:34px;height:34px;border:1px solid #dce5ef;border-radius:8px;color:#607086;cursor:pointer;list-style:none;font-weight:800}.vehicle-overflow summary::-webkit-details-marker{display:none}.vehicle-overflow-menu{position:absolute;right:0;top:40px;z-index:5;display:grid;min-width:130px;padding:5px;border:1px solid #dce5ef;border-radius:8px;background:#fff;box-shadow:0 12px 28px rgba(18,32,51,.14)}.vehicle-overflow-menu button{border:0;background:none;text-align:left;padding:8px;border-radius:6px;color:#122033;cursor:pointer;font:inherit;font-size:12px}.vehicle-overflow-menu button:hover{background:#f3f6fb}.bulk-actions{display:flex;align-items:center;gap:8px;padding:0 18px 12px;font-size:12px;font-weight:700}.vehicle-pagination{display:flex;justify-content:center;gap:6px;padding:0 18px 18px}.vehicle-page-button{min-width:32px;height:32px;border:1px solid #dce5ef;border-radius:8px;background:#fff;color:#122033;cursor:pointer}.vehicle-page-button.active{background:#0f62fe;color:#fff;border-color:#0f62fe}.activity-list{display:grid;gap:2px;padding:0 18px 18px}.activity-item{display:grid;grid-template-columns:8px 1fr auto;gap:10px;align-items:start;padding:10px 0;border-bottom:1px solid #edf2f7}.activity-item:last-child{border-bottom:0}.activity-dot{width:8px;height:8px;margin-top:5px;border-radius:50%;background:#0f62fe}.activity-item strong{font-size:13px}.activity-item p{margin:3px 0 0;color:#607086;font-size:12px}.activity-item time{color:#607086;font-size:11px;white-space:nowrap}.monitoring-empty[hidden],.bulk-actions[hidden],.clear-filters[hidden],.active-filter-count[hidden]{display:none}.monitoring-filters{grid-template-columns:1.8fr 1fr 1fr 1fr auto}@media (max-width:1100px){.vehicle-list-headings{display:none}.vehicle-row-main{grid-template-columns:8px minmax(150px,1.4fr) 110px minmax(130px,1fr) minmax(130px,1fr)}}@media (max-width:760px){.monitoring-stats{grid-template-columns:repeat(2,minmax(0,1fr))}.monitoring-filters{grid-template-columns:1fr 1fr}.monitoring-filters .ui-input,.monitoring-filters button{grid-column:1/-1}.vehicle-list-head{display:grid;gap:12px}.vehicle-toolbar-actions{flex-wrap:wrap}.vehicle-row{grid-template-columns:1fr;gap:10px}.vehicle-row-main{grid-template-columns:8px minmax(0,1fr) auto;gap:8px}.vehicle-row-main>.vehicle-row-detail,.vehicle-row-main>.vehicle-maintenance{grid-column:2/-1}.vehicle-row-actions{justify-content:flex-start;flex-wrap:wrap}.activity-item{grid-template-columns:8px 1fr}.activity-item time{grid-column:2}.vehicle-insight-bar{flex-wrap:wrap}}
</style>
@endpush

@push('styles')
<style>
    .vehicle-header-button{display:inline-flex;align-items:center;justify-content:center;gap:6px;min-height:38px;padding:0 13px;border:1px solid transparent;border-radius:10px;text-decoration:none;font-size:13px;font-weight:800;white-space:nowrap}.vehicle-header-button.ui-button--primary{background:#0f62fe;color:#fff}.vehicle-header-button.ui-button--primary:hover{background:#0b4dcc}.vehicle-header-button.ui-button--secondary{background:#fff;color:#122033;border-color:#cbd8e8}.vehicle-header-button.ui-button--secondary:hover{background:#f3f6fb;border-color:#9bbcfb}
</style>
@endpush

@push('styles')
<style>
    .vehicle-list-panel .vehicle-row{grid-template-columns:minmax(0,1fr) minmax(330px,auto);min-width:0}.vehicle-list-panel .vehicle-row-main{grid-template-columns:88px 8px minmax(160px,1.5fr) minmax(150px,auto) minmax(125px,1fr) minmax(135px,1fr) minmax(165px,1.1fr) minmax(155px,1fr) minmax(165px,1fr);min-width:0;min-height:88px}.vehicle-list-panel .vehicle-thumbnail{width:88px;height:88px;border-radius:11px;font-size:30px}.vehicle-list-panel .vehicle-row-identity{align-content:center}.vehicle-list-panel .vehicle-status-pill{width:max-content;min-width:150px;max-width:100%;padding:5px 10px;line-height:1.2;white-space:normal;overflow-wrap:anywhere}.vehicle-list-panel .vehicle-row-detail:last-of-type{min-width:155px}.vehicle-list-panel .vehicle-maintenance{min-width:165px}.vehicle-list-panel .vehicle-row-actions{min-width:330px;flex-wrap:nowrap;gap:9px;padding-left:12px;border-left:1px solid #dce5ef}.vehicle-list-panel .vehicle-row-actions .vehicle-quick-status{min-width:112px;margin-left:0}.vehicle-list-panel .vehicle-row-actions .vehicle-select{margin:0 3px 0 0;flex:0 0 16px}@media(max-width:1250px){.vehicle-list-panel .vehicle-row{grid-template-columns:minmax(0,1fr);gap:10px}.vehicle-list-panel .vehicle-row-actions{min-width:0;justify-content:flex-end;padding:10px 0 0;border-left:0;border-top:1px solid #edf2f7}.vehicle-list-panel .vehicle-row-main{grid-template-columns:88px 8px minmax(150px,1.4fr) minmax(145px,auto) minmax(125px,1fr) minmax(130px,1fr) minmax(155px,1fr) minmax(150px,1fr) minmax(155px,1fr)}}@media(max-width:760px){.vehicle-list-panel .vehicle-row-main{grid-template-columns:64px 8px minmax(0,1fr) auto;min-height:64px}.vehicle-list-panel .vehicle-thumbnail{width:64px;height:64px}.vehicle-list-panel .vehicle-row-main>.vehicle-row-detail,.vehicle-list-panel .vehicle-row-main>.vehicle-maintenance{grid-column:3/-1}.vehicle-list-panel .vehicle-row-main .vehicle-status-pill{grid-column:4;grid-row:1;min-width:0}.vehicle-list-panel .vehicle-row-actions{justify-content:flex-start;flex-wrap:wrap}}
</style>
@endpush

@push('styles')
<style>
    .vehicle-list-panel .vehicle-row{grid-template-columns:1fr}.vehicle-list-panel .vehicle-row-main{grid-template-columns:88px 8px minmax(120px,1.4fr) minmax(135px,auto) minmax(90px,1fr) minmax(100px,1fr) minmax(130px,1.1fr) minmax(120px,1fr) minmax(140px,1fr)}.vehicle-list-panel .vehicle-row-actions{min-width:0;justify-content:flex-end;flex-wrap:nowrap;padding:10px 0 0;border-left:0;border-top:1px solid #edf2f7}.vehicle-list-panel .vehicle-row-actions .vehicle-quick-status{min-width:112px}@media(max-width:1400px){.vehicle-list-panel .vehicle-row-main{grid-template-columns:76px 8px minmax(110px,1.4fr) minmax(130px,auto) minmax(85px,1fr) minmax(90px,1fr) minmax(110px,1.1fr) minmax(110px,1fr) minmax(120px,1fr)}.vehicle-list-panel .vehicle-thumbnail{width:76px;height:76px}}@media(max-width:760px){.vehicle-list-panel .vehicle-row-main{grid-template-columns:64px 8px minmax(0,1fr) auto;min-height:64px}.vehicle-list-panel .vehicle-thumbnail{width:64px;height:64px}.vehicle-list-panel .vehicle-row-main>.vehicle-row-detail,.vehicle-list-panel .vehicle-row-main>.vehicle-maintenance{grid-column:3/-1}.vehicle-list-panel .vehicle-row-main .vehicle-status-pill{grid-column:4;grid-row:1;min-width:0}.vehicle-list-panel .vehicle-row-actions{justify-content:flex-start;flex-wrap:wrap}}
</style>
@endpush

@push('styles')
<style>
    .vehicle-list-panel .vehicle-row-updated{min-width:130px}.vehicle-list-panel .vehicle-maintenance{min-width:140px}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const panel = document.querySelector('[data-vehicle-panel]');
    if (!panel) return;

    const form = panel.querySelector('[data-vehicle-filters]');
    const list = panel.querySelector('[data-vehicle-list]');
    const empty = panel.querySelector('[data-vehicle-empty]');
    const count = panel.querySelector('#vehicleCount');
    const pagination = panel.querySelector('[data-pagination]');
    const filterCount = panel.querySelector('[data-filter-count]');
    const clearFilters = panel.querySelector('[data-clear-filters]');
    const exportLink = panel.querySelector('[data-export-link]');
    const errorNotice = panel.querySelector('[data-vehicle-error]');
    const baseUrl = panel.dataset.baseUrl;
    const csrf = panel.dataset.csrf;
    const canManage = panel.dataset.canManage === '1';
    let page = 1;
    let currentVehicles = [];
    let debounceTimer;

    const escapeHtml = (value) => String(value ?? '').replace(/[&<>'"]/g, character => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;','"':'&quot;'}[character]));
    const initials = (name) => escapeHtml((name || 'U').split(' ').map(part => part[0]).join('').slice(0, 2).toUpperCase());
    const statusLabel = status => status === 'offline' ? 'Offline / Under Repair' : status.charAt(0).toUpperCase() + status.slice(1);

    function rowTemplate(vehicle) {
        const manage = canManage ? `<input type="checkbox" class="vehicle-select" data-vehicle-select value="${vehicle.id}" aria-label="Select ${escapeHtml(vehicle.call_sign)}"><select class="vehicle-quick-status" data-status-url="${baseUrl}/${vehicle.id}/status" aria-label="Change ${escapeHtml(vehicle.call_sign)} status">${['active','idle','offline'].map(status => `<option value="${status}" ${vehicle.status === status ? 'selected' : ''}>${statusLabel(status).replace(' / Under Repair', '')}</option>`).join('')}</select>` : '';
        const actions = canManage ? `<a class="ui-button ui-button--small ui-button--secondary" href="${baseUrl}/${vehicle.id}/edit">Edit</a><details class="vehicle-overflow"><summary aria-label="More actions for ${escapeHtml(vehicle.call_sign)}">...</summary><div class="vehicle-overflow-menu"><button type="button" data-quick-status-url="${baseUrl}/${vehicle.id}/status" data-quick-status="active">Mark Active</button><button type="button" data-quick-status-url="${baseUrl}/${vehicle.id}/status" data-quick-status="offline">Mark Offline</button><button type="button" data-duplicate-url="${baseUrl}/${vehicle.id}/duplicate">Duplicate</button><button type="button" data-delete-url="${baseUrl}/${vehicle.id}">Delete</button></div></details>` : '';
        return `<article class="vehicle-row" data-vehicle-id="${vehicle.id}"><a class="vehicle-row-main" href="${baseUrl}/${vehicle.id}"><span class="vehicle-thumbnail">${vehicle.photo_url ? `<img src="${escapeHtml(vehicle.photo_url)}" alt="${escapeHtml(vehicle.call_sign)} vehicle photo">` : '<span aria-label="No vehicle photo">&#128663;</span>'}</span><span class="vehicle-status-dot vehicle-status-dot--${vehicle.status_class}"></span><div class="vehicle-row-identity"><span class="vehicle-card-label">Vehicle</span><strong class="vehicle-card-call-sign">${escapeHtml(vehicle.call_sign)}</strong><span class="vehicle-card-type">${escapeHtml(vehicle.vehicle_type)}${vehicle.vehicle_model ? ' · ' + escapeHtml(vehicle.vehicle_model) : ''}</span></div><span class="vehicle-status-pill vehicle-status-pill--${vehicle.status_class}">${escapeHtml(vehicle.status_label)}</span><div class="vehicle-row-detail"><small>Plate No.</small><strong>${escapeHtml(vehicle.plate_number)}</strong></div><div class="vehicle-row-detail"><small>Team</small><strong>${escapeHtml(vehicle.team)}</strong></div><div class="vehicle-row-detail vehicle-row-driver"><small>Driver / Responsible</small>${vehicle.driver ? `<span class="driver-badge"><span>${initials(vehicle.driver)}</span>${escapeHtml(vehicle.driver)}</span>` : '<strong>Unassigned</strong>'}</div><div class="vehicle-row-detail vehicle-row-updated"><small>Last updated</small><strong>${escapeHtml(vehicle.last_updated)}</strong></div><div class="vehicle-maintenance ${vehicle.needs_attention && vehicle.next_due_date ? 'is-overdue' : ''}"><small>Maintenance</small><strong>${escapeHtml(vehicle.maintenance_label)}</strong></div></a><div class="vehicle-row-actions">${manage}${actions}<a class="ui-button ui-button--small ui-button--secondary" href="${baseUrl}/${vehicle.id}">View</a></div></article>`;
    }

    function render() {
        const perPage = 10;
        const totalPages = Math.max(1, Math.ceil(currentVehicles.length / perPage));
        page = Math.min(page, totalPages);
        const pageVehicles = currentVehicles.slice((page - 1) * perPage, page * perPage);
        list.innerHTML = pageVehicles.map(rowTemplate).join('');
        list.querySelectorAll('.vehicle-row').forEach(row => { const statusSelect = row.querySelector('.vehicle-quick-status'); if (statusSelect) row.dataset.vehicleStatus = statusSelect.value; });
        empty.hidden = pageVehicles.length > 0;
        const hasVehicles = Number(panel.dataset.total || 0) > 0;
        empty.querySelector('[data-empty-title]').textContent = hasVehicles ? 'No vehicles match these filters.' : 'No vehicle data available yet.';
        empty.querySelector('[data-empty-message]').textContent = hasVehicles ? 'Try clearing a filter or changing your search.' : 'Add a vehicle to begin monitoring the fleet.';
        const addFirst = empty.querySelector('[data-add-first]'); if (addFirst) addFirst.hidden = hasVehicles;
        count.textContent = `Showing ${currentVehicles.length} of ${panel.dataset.total || currentVehicles.length} vehicles`;
        const stats = panel.dataset.stats ? JSON.parse(panel.dataset.stats) : null;
        if (stats) { panel.querySelector('.monitoring-stat:nth-child(2) strong').textContent = stats.active; panel.querySelector('.monitoring-stat:nth-child(3) strong').textContent = stats.idle; panel.querySelector('.monitoring-stat:nth-child(4) strong').textContent = stats.offline; panel.querySelector('.vehicle-insight strong').textContent = `${stats.attention} vehicles need attention`; }
        pagination.innerHTML = totalPages <= 1 ? '' : Array.from({length: totalPages}, (_, index) => `<button type="button" class="vehicle-page-button ${index + 1 === page ? 'active' : ''}" data-page="${index + 1}">${index + 1}</button>`).join('');
        const params = new URLSearchParams(new FormData(form));
        const active = ['search', 'team', 'status', 'attention'].filter(key => params.get(key)).length;
        filterCount.hidden = active === 0;
        filterCount.textContent = `${active} filter${active === 1 ? '' : 's'} active`;
        clearFilters.hidden = active === 0;
        if (exportLink) {
            params.delete('ajax');
            exportLink.href = `${baseUrl}/export?${params}`;
        }
        updateBulkBar();
        bindRowActions();
    }

    async function refresh() {
        const params = new URLSearchParams(new FormData(form));
        params.set('ajax', '1');
        const response = await fetch(`${baseUrl}?${params}`, {headers: {'Accept': 'application/json'}});
        if (!response.ok) { if (errorNotice) errorNotice.hidden = false; return; }
        const payload = await response.json();
        if (errorNotice) errorNotice.hidden = true;
        currentVehicles = payload.vehicles || [];
        panel.dataset.total = payload.total;
        panel.dataset.stats = JSON.stringify(payload.stats || {});
        page = 1;
        render();
    }

    function bindRowActions() {
        panel.querySelectorAll('[data-status-url]').forEach(select => select.addEventListener('change', async event => {
            const nextStatus = event.target.value;
            event.target.closest('.vehicle-row')?.setAttribute('data-vehicle-status', nextStatus);
            if (nextStatus === 'offline' && !window.confirm('Mark this vehicle offline?')) { refresh(); return; }
            const response = await fetch(event.target.dataset.statusUrl, {method: 'PATCH', headers: {'Accept':'application/json','Content-Type':'application/json','X-CSRF-TOKEN':csrf}, body: JSON.stringify({status: nextStatus})});
            if (!response.ok) { if (errorNotice) errorNotice.hidden = false; return; }
            refresh();
        }));
        panel.querySelectorAll('[data-quick-status-url]').forEach(button => button.addEventListener('click', async () => {
            if (button.dataset.quickStatus === 'offline' && !window.confirm('Mark this vehicle offline?')) return;
            const response = await fetch(button.dataset.quickStatusUrl, {method:'PATCH', headers:{'Accept':'application/json','Content-Type':'application/json','X-CSRF-TOKEN':csrf}, body:JSON.stringify({status:button.dataset.quickStatus})});
            if (!response.ok) { if (errorNotice) errorNotice.hidden = false; return; }
            refresh();
        }));
        panel.querySelectorAll('[data-duplicate-url]').forEach(button => button.addEventListener('click', async () => { const response = await fetch(button.dataset.duplicateUrl, {method:'POST', headers:{'Accept':'application/json','X-CSRF-TOKEN':csrf}}); if (!response.ok) { if (errorNotice) errorNotice.hidden = false; return; } refresh(); }));
        panel.querySelectorAll('[data-delete-url]').forEach(button => button.addEventListener('click', async () => { if (!window.confirm('Delete this vehicle? Related task and service records will also be deleted.')) return; const response = await fetch(button.dataset.deleteUrl, {method:'DELETE', headers:{'Accept':'application/json','X-CSRF-TOKEN':csrf}}); if (!response.ok) { if (errorNotice) errorNotice.hidden = false; return; } refresh(); }));
        panel.querySelectorAll('[data-vehicle-select]').forEach(input => input.addEventListener('change', updateBulkBar));
    }

    function updateBulkBar() { const selected = panel.querySelectorAll('[data-vehicle-select]:checked'); const bar = panel.querySelector('[data-bulk-actions]'); if (!bar) return; bar.hidden = selected.length < 1; bar.querySelector('[data-selected-count]').textContent = `${selected.length} selected`; }
    form.addEventListener('submit', event => { event.preventDefault(); refresh(); });
    form.querySelector('[name="search"]').addEventListener('input', () => { clearTimeout(debounceTimer); debounceTimer = setTimeout(refresh, 300); });
    form.querySelectorAll('select').forEach(select => select.addEventListener('change', refresh));
    pagination.addEventListener('click', event => { if (event.target.dataset.page) { page = Number(event.target.dataset.page); render(); } });
    clearFilters.addEventListener('click', () => { form.reset(); refresh(); });
    panel.querySelector('[data-attention-filter]').addEventListener('click', () => { const field = form.querySelector('[name="attention"]') || Object.assign(document.createElement('input'), {type:'hidden', name:'attention'}); field.value = field.value === '1' ? '' : '1'; if (!field.parentElement) form.appendChild(field); refresh(); });
    const bulkButton = panel.querySelector('[data-apply-bulk]');
    bulkButton?.addEventListener('click', async () => { const ids = [...panel.querySelectorAll('[data-vehicle-select]:checked')].map(input => input.value); const status = panel.querySelector('[data-bulk-status]').value; if (status === 'offline' && !window.confirm('Mark selected vehicles offline?')) return; const response = await fetch(`${baseUrl}/bulk-status`, {method:'POST', headers:{'Accept':'application/json','Content-Type':'application/json','X-CSRF-TOKEN':csrf}, body:JSON.stringify({vehicle_ids:ids,status})}); if (!response.ok) { if (errorNotice) errorNotice.hidden = false; return; } refresh(); });
    refresh();
});
</script>
@endpush
<div class="ui-shell vehicle-monitoring-page">
    <section class="ui-card monitoring-hero">
        <div><div class="monitoring-eyebrow">Reports / Vehicle Monitoring</div><h1 class="ui-title monitoring-title">Vehicle Monitoring</h1><p class="ui-subtitle">Track fleet status and location updates from one place.</p></div>
        <span class="ui-badge ui-badge--neutral">Manual encoding</span>
    </section>

    <section class="monitoring-stats" aria-label="Vehicle status summary">
        <article class="ui-card monitoring-stat"><span class="monitoring-stat-label">Total vehicles</span><strong>{{ $stats['total'] }}</strong><span class="monitoring-stat-note">No vehicle records yet</span></article>
        <article class="ui-card monitoring-stat"><span class="monitoring-stat-label">Active</span><strong class="monitoring-stat-value--green">{{ $stats['active'] }}</strong><span class="monitoring-stat-note">Currently in service</span></article>
        <article class="ui-card monitoring-stat"><span class="monitoring-stat-label">Idle</span><strong class="monitoring-stat-value--yellow">{{ $stats['idle'] }}</strong><span class="monitoring-stat-note">Awaiting assignment</span></article>
        <article class="ui-card monitoring-stat"><span class="monitoring-stat-label">Offline</span><strong class="monitoring-stat-value--red">{{ $stats['offline'] }}</strong><span class="monitoring-stat-note">No recent signal</span></article>
    </section>

    <section class="ui-card vehicle-list-panel" data-vehicle-panel data-can-manage="{{ $canManage ? '1' : '0' }}" data-base-url="{{ url('/reports/vehicle-monitoring') }}" data-csrf="{{ csrf_token() }}">
        <div class="ui-card-head vehicle-list-head">
            <div><h2 class="ui-title">Vehicles</h2><p class="ui-subtitle" id="vehicleCount">Showing {{ $vehicles->count() }} of {{ $stats['total'] }} vehicles</p></div>
            <div class="vehicle-toolbar-actions">
                <a class="ui-button ui-button--info vehicle-header-button" href="{{ route('reports.vehicle-monitoring.export', request()->query()) }}" data-export-link><span aria-hidden="true">↓</span> Export CSV</a>
                @if($canManage)<a class="ui-button ui-button--primary vehicle-header-button" href="{{ route('reports.vehicle-monitoring.create') }}"><span aria-hidden="true">+</span> Add Vehicle</a>@endif
            </div>
        </div>
        <div class="vehicle-insight-bar"><button type="button" class="vehicle-insight" data-attention-filter><span class="vehicle-insight-icon">!</span><span><strong>{{ $stats['attention'] ?? 0 }} vehicles need attention</strong><small>Offline or maintenance overdue</small></span></button><span class="active-filter-count" data-filter-count hidden></span><button type="button" class="clear-filters" data-clear-filters hidden>Clear filters</button></div>
        <form method="GET" class="ui-toolbar monitoring-filters" data-vehicle-filters>
            <input class="ui-input" type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search call sign, plate, team..." aria-label="Search vehicles">
            <select class="ui-select" name="team" aria-label="Filter by team"><option value="">All teams</option>@foreach($teams as $team)<option value="{{ $team }}" @selected(($filters['team'] ?? '') === $team)>{{ $team }}</option>@endforeach</select>
            <select class="ui-select" name="status" aria-label="Filter by status"><option value="">All statuses</option>@foreach($statuses as $status)<option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ $status === 'offline' ? 'Offline / Under Repair' : ucfirst($status) }}</option>@endforeach</select>
            <select class="ui-select" name="sort" aria-label="Sort vehicles"><option value="call_sign">Sort: Call Sign</option><option value="status">Sort: Status</option><option value="team">Sort: Team</option><option value="last_updated">Sort: Last Updated</option></select>
            <button class="ui-button ui-button--secondary" type="submit">Filter</button>
        </form>
        @if($canManage)<div class="bulk-actions" data-bulk-actions hidden><span data-selected-count>0 selected</span><select data-bulk-status aria-label="Bulk status"><option value="active">Set Active</option><option value="idle">Set Idle</option><option value="offline">Set Offline</option></select><button class="ui-button ui-button--secondary" type="button" data-apply-bulk>Apply</button></div>@endif
        <div class="vehicle-list-headings"><span>Vehicle</span><span>Status</span><span>Plate / Team</span><span>Assignment</span><span>Updated / Maintenance</span><span></span></div>
        <div class="vehicle-card-grid" data-vehicle-list>@foreach($vehicles as $vehicle)<x-vehicle-card :vehicle="$vehicle" :can-manage="$canManage" />@endforeach</div>
        <div class="monitoring-empty" data-vehicle-empty @if($vehicles->isNotEmpty()) hidden @endif><strong data-empty-title>{{ $stats['total'] ? 'No vehicles match these filters.' : 'No vehicle data available yet.' }}</strong><span data-empty-message>{{ $stats['total'] ? 'Try clearing a filter or changing your search.' : 'Add a vehicle to begin monitoring the fleet.' }}</span>@if($canManage)<a data-add-first @if($stats['total']) hidden @endif href="{{ route('reports.vehicle-monitoring.create') }}">Add the first vehicle</a>@endif</div>
        <div class="form-alert form-alert--error" data-vehicle-error hidden role="alert">Vehicle data could not be refreshed. Please try again.</div>
        <div class="vehicle-pagination" data-pagination></div>
    </section>

    <section class="ui-card activity-panel"><div class="ui-card-head"><div><h2 class="ui-title">Recent Activity</h2><p class="ui-subtitle">Latest fleet changes and service notes.</p></div></div><div class="activity-list">@forelse($activities as $activity)<div class="activity-item"><span class="activity-dot"></span><div><strong>{{ $activity->vehicle?->call_sign ?? 'Vehicle' }}</strong><p>{{ $activity->description }}</p></div><time datetime="{{ $activity->created_at->toIso8601String() }}">{{ $activity->created_at->diffForHumans() }}</time></div>@empty<div class="monitoring-empty"><span>No vehicle activity recorded yet.</span></div>@endforelse</div></section>

    <section class="ui-card"><div class="ui-card-head"><div><h2 class="ui-title">Live map</h2><p class="ui-subtitle">Map integration will appear here when vehicle location data is available.</p></div><span class="ui-badge ui-badge--neutral">Coming soon</span></div><div class="monitoring-map-placeholder" role="status" aria-label="Live map placeholder"><div class="monitoring-map-icon" aria-hidden="true">+</div><strong>Live vehicle map placeholder</strong><span>Connect a location provider to display vehicle positions.</span></div></section>
</div>
@endsection

@push('styles')
<style>
    .vehicle-monitoring-page .ui-button{min-height:36px;border-radius:9px;font-weight:800;transition:background .15s ease,border-color .15s ease,color .15s ease,box-shadow .15s ease}.vehicle-monitoring-page .ui-button:focus-visible,.vehicle-monitoring-page button:focus-visible,.vehicle-monitoring-page select:focus-visible{outline:3px solid rgba(15,98,254,.25);outline-offset:2px}.vehicle-monitoring-page .ui-button--primary{background:#0f62fe;border-color:#0f62fe;color:#fff}.vehicle-monitoring-page .ui-button--primary:hover{background:#0b4dcc;border-color:#0b4dcc}.vehicle-monitoring-page .ui-button--secondary{background:#fff;border-color:#cbd8e8;color:#122033}.vehicle-monitoring-page .ui-button--secondary:hover{background:#f3f6fb;border-color:#9bbcfb}.vehicle-monitoring-page .ui-button--info{background:#e7f3f5;border-color:#9ac8cf;color:#155e63}.vehicle-monitoring-page .ui-button--info:hover{background:#cfe8eb;border-color:#5da8b2}.vehicle-monitoring-page .vehicle-thumbnail{height:auto;aspect-ratio:4/3}.vehicle-monitoring-page .vehicle-quick-status{font-weight:800}.vehicle-monitoring-page .vehicle-row[data-vehicle-status="active"] .vehicle-quick-status{border-color:#86efac;background:#f0fdf4;color:#166534}.vehicle-monitoring-page .vehicle-row[data-vehicle-status="idle"] .vehicle-quick-status{border-color:#fcd34d;background:#fffbeb;color:#854d0e}.vehicle-monitoring-page .vehicle-row[data-vehicle-status="offline"] .vehicle-quick-status{border-color:#fca5a5;background:#fff1f2;color:#991b1b}.vehicle-monitoring-page .vehicle-overflow-menu button{width:100%;border:0;background:transparent;color:#122033;text-align:left;padding:8px 10px;font:inherit;font-size:12px;font-weight:700;cursor:pointer}.vehicle-monitoring-page .vehicle-overflow-menu button:hover{background:#f3f6fb}.vehicle-monitoring-page .vehicle-overflow-menu .status-action--active{color:#166534}.vehicle-monitoring-page .vehicle-overflow-menu .status-action--idle{color:#854d0e}.vehicle-monitoring-page .vehicle-overflow-menu .status-action--offline,.vehicle-monitoring-page .vehicle-overflow-menu .delete-action{color:#b91c1c}.vehicle-monitoring-page .vehicle-overflow-menu .delete-action:hover{background:#fff1f2;color:#991b1b}.vehicle-monitoring-page .vehicle-header-button.ui-button--info{background:#e7f3f5;border-color:#9ac8cf;color:#155e63}
</style>
@endpush

@push('styles')
<style>
    .vehicle-monitoring-page{color:#122033}.monitoring-hero{display:flex;justify-content:space-between;align-items:flex-start;gap:16px;background:linear-gradient(135deg,#fff 0%,#f6f9ff 100%)}.monitoring-eyebrow{color:#0f62fe;font-size:11px;font-weight:800;letter-spacing:.12em;text-transform:uppercase}.monitoring-title{margin-top:6px}.monitoring-stats{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px}.monitoring-stat{display:grid;gap:6px}.monitoring-stat strong{font-size:28px;line-height:1}.monitoring-stat-label,.monitoring-stat-note{color:#607086;font-size:12px}.monitoring-stat-label{font-weight:800;letter-spacing:.08em;text-transform:uppercase}.monitoring-stat-value--green{color:#166534}.monitoring-stat-value--yellow{color:#854d0e}.monitoring-stat-value--red{color:#991b1b}.monitoring-filters{grid-template-columns:1.8fr 1fr 1fr auto}.vehicle-card-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}.vehicle-card{display:grid;gap:14px;padding:16px;border:1px solid #dde7f2;border-radius:14px;background:#fff;color:inherit;text-decoration:none;transition:transform .15s ease,box-shadow .15s ease,border-color .15s ease}.vehicle-card:hover,.vehicle-card:focus-visible{border-color:#9bbcfb;box-shadow:0 12px 26px rgba(18,32,51,.1);transform:translateY(-2px);outline:none}.vehicle-card-top{display:flex;justify-content:space-between;gap:10px;align-items:flex-start}.vehicle-card-label{display:block;color:#607086;font-size:10px;font-weight:800;letter-spacing:.1em;text-transform:uppercase}.vehicle-card-call-sign{display:block;margin-top:4px;font-size:20px;letter-spacing:.04em}.vehicle-card-type{color:#607086;font-size:13px}.vehicle-card-details{display:grid;grid-template-columns:1fr 1fr;gap:10px;border-top:1px solid #edf2f7;padding-top:12px}.vehicle-card-details span{display:grid;gap:3px;font-size:13px;font-weight:700}.vehicle-card-details small{color:#607086;font-size:10px;font-weight:800;letter-spacing:.08em;text-transform:uppercase}.vehicle-status-pill{display:inline-flex;align-items:center;border-radius:999px;padding:5px 9px;font-size:10px;font-weight:800;letter-spacing:.04em;text-transform:uppercase;white-space:nowrap}.vehicle-status-pill--active{background:#dcfce7;color:#166534}.vehicle-status-pill--idle{background:#fef3c7;color:#854d0e}.vehicle-status-pill--offline{background:#fee2e2;color:#991b1b}.monitoring-empty{display:grid;justify-items:center;gap:7px;color:#607086;padding:34px 12px;text-align:center}.monitoring-empty strong{color:#122033}.monitoring-empty a{color:#0f62fe;font-weight:700}.monitoring-map-placeholder{min-height:260px;display:grid;place-content:center;justify-items:center;gap:8px;border:1px dashed #cbd8e8;border-radius:14px;background:#f8fbff;color:#607086;text-align:center}.monitoring-map-placeholder strong{color:#122033}.monitoring-map-icon{width:42px;height:42px;display:grid;place-items:center;border:1px solid #cbd8e8;border-radius:50%;color:#0f62fe;font-size:24px}@media(max-width:900px){.vehicle-card-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:860px){.monitoring-stats{grid-template-columns:repeat(2,minmax(0,1fr))}.monitoring-hero{flex-direction:column}.monitoring-filters{grid-template-columns:1fr 1fr}.monitoring-filters .ui-input{grid-column:1/-1}}@media(max-width:560px){.monitoring-stats,.vehicle-card-grid,.monitoring-filters{grid-template-columns:1fr}.monitoring-filters .ui-input{grid-column:auto}}
</style>
@endpush

@push('styles')
<style>
    .monitoring-filters{grid-template-columns:1.8fr 1fr 1fr 1fr auto}.vehicle-card-grid{display:grid;grid-template-columns:1fr;gap:8px;padding:0 18px 18px}.vehicle-card-grid .vehicle-row{display:grid}.vehicle-list-headings{display:grid}.vehicle-list-panel .vehicle-card{display:grid;grid-template-columns:1fr}.vehicle-list-panel .vehicle-card-grid{grid-template-columns:1fr}.vehicle-row-main{grid-template-columns:56px 8px minmax(150px,1.5fr) minmax(132px,auto) minmax(130px,1fr) minmax(145px,1fr) minmax(160px,1fr) minmax(160px,1fr)}.vehicle-thumbnail{width:52px;height:52px;display:grid;place-items:center;overflow:hidden;border:1px solid #dce5ef;border-radius:10px;background:#f8fbff;color:#94a3b8;font-size:22px}.vehicle-thumbnail img{width:100%;height:100%;object-fit:cover}.vehicle-row-main .vehicle-status-pill{width:max-content;max-width:100%;min-width:132px;padding:5px 10px;line-height:1.2;text-align:center;white-space:normal;overflow-wrap:anywhere}.vehicle-list-panel .vehicle-status-pill{display:inline-flex;align-items:center;justify-content:center;box-sizing:border-box}.vehicle-row-actions{gap:9px;padding-left:8px;border-left:1px solid #edf2f7}.vehicle-row-actions .vehicle-quick-status{margin-left:3px}.vehicle-row-actions .ui-button{white-space:nowrap}.vehicle-row-actions .vehicle-overflow{margin-left:1px}@media(max-width:1100px){.vehicle-list-headings{display:none}.vehicle-row-main{grid-template-columns:56px 8px minmax(150px,1.4fr) minmax(132px,auto) minmax(130px,1fr) minmax(130px,1fr) minmax(145px,1fr)}}@media(max-width:760px){.monitoring-filters{grid-template-columns:1fr 1fr}.monitoring-filters .ui-input,.monitoring-filters button{grid-column:1/-1}.vehicle-row{grid-template-columns:1fr}.vehicle-row-main{grid-template-columns:52px 8px minmax(0,1fr) auto}.vehicle-row-main>.vehicle-row-detail,.vehicle-row-main>.vehicle-maintenance{grid-column:3/-1}.vehicle-row-main .vehicle-status-pill{grid-column:4;grid-row:1;min-width:0}.vehicle-row-actions{border-left:0;border-top:1px solid #edf2f7;padding:10px 0 0}.vehicle-thumbnail{width:48px;height:48px}}
</style>
@endpush
