@extends('layouts.app')

@section('page-name','Accounts')

@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
        <h3 style="margin:0">Account Requests</h3>
        <div>
            <form method="GET" style="display:flex;gap:8px;align-items:center">
                <select name="filter" onchange="this.form.submit()" style="padding:6px;border-radius:6px">
                    <option value="">All</option>
                    <option value="pending" {{ request('filter')=='pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('filter')=='approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('filter')=='rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </form>
        </div>
    </div>

    <style>
        .accounts-table-wrap{background:#f5f7fb;padding:12px;border-radius:10px}
        .accounts-table{width:100%;border-collapse:collapse;font-size:14px;background:#ffffff;border-radius:8px;overflow:hidden}
        .accounts-table thead th{background:transparent;color:var(--muted);font-weight:600;padding:12px 12px}
        .accounts-table tbody tr{transition:background .12s ease,transform .06s ease}
        .accounts-table tbody tr:hover{background:#f6fbff;transform:translateY(-1px)}
        .accounts-table td{padding:12px 12px;border-bottom:1px solid rgba(0,0,0,0.04)}
        .accounts-summary{color:var(--muted);font-size:13px;margin-bottom:8px}
        .action-btn{padding:8px 10px;border-radius:6px;border:none;cursor:pointer}

        /* Pagination */
        .pagination{display:flex;gap:6px;padding:0;margin:0;list-style:none}
        .pagination li{display:inline-block}
        .pagination li a, .pagination li span{display:inline-block;padding:8px 10px;border-radius:6px;border:1px solid rgba(0,0,0,0.06);color:var(--muted);text-decoration:none}
        .pagination li a:hover{background:#eef6ff;border-color:#d0e6ff;color:#1f8ef1}
        .pagination .active span{background:#1f8ef1;color:white;border-color:#1f8ef1}
        /* Status badges */
        .badge{display:inline-block;padding:6px 9px;border-radius:999px;font-weight:600;font-size:13px}
        .badge-pending{background:#fff7e6;color:#b07b00}
        .badge-approved{background:#e6fff0;color:#126a2c}
        .badge-rejected{background:#fff0f0;color:#b71c1c}

        /* Modal styling */
        .account-modal .modal-inner{background:#ffffff;padding:22px;border-radius:12px;box-shadow:0 20px 50px rgba(13,30,60,0.12);max-width:620px;width:96%}
        .account-modal .modal-row{display:flex;gap:10px;padding:8px 0}
        .account-modal .modal-row .label{width:120px;color:var(--muted);font-weight:600}
        .account-modal .modal-row .value{flex:1;color:#111}
        .account-modal .close-btn{position:absolute;right:12px;top:12px;width:36px;height:36px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;border:none;background:transparent;cursor:pointer}
        .account-modal .close-btn:hover{background:#f2f6fb}
    </style>

    <div class="accounts-summary">Showing {{ $users->count() }} of {{ $users->total() }} accounts</div>

    @if(session('success'))
        <div style="padding:8px;background:#e6ffed;border:1px solid #b7f0c6;border-radius:6px;margin-bottom:12px">{{ session('success') }}</div>
    @endif

    <div class="accounts-table-wrap">
    <table class="accounts-table">
        <thead style="text-align:left;color:var(--muted)">
            <tr>
                <th style="padding:10px 8px">Name</th>
                <th style="padding:10px 8px">Email</th>
                <th style="padding:10px 8px">Registered</th>
                <th style="padding:10px 8px">Status</th>
                <th style="padding:10px 8px;text-align:right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $u)
            <tr style="border-top:1px solid rgba(0,0,0,0.06);cursor:pointer" onclick="showDetails(this)"
                data-name="{{ e($u->name) }}"
                data-email="{{ e($u->email) }}"
                data-registered="{{ $u->created_at->setTimezone(config('app.timezone'))->toDateTimeString() }}"
                data-status="{{ $u->status ?? 'approved' }}"
                data-role="{{ $u->role ?? '' }}"
                data-rejection="{{ $u->rejection_reason ?? '' }}">
                <td style="padding:12px 8px">{{ $u->name }}</td>
                <td style="padding:12px 8px">{{ $u->email }}</td>
                <td style="padding:12px 8px">{{ $u->created_at->diffForHumans() }}</td>
                <td style="padding:12px 8px">
                    @php $st = $u->status ?? 'approved'; @endphp
                    @if($st === 'pending')
                        <span class="badge badge-pending">Pending</span>
                    @elseif($st === 'approved')
                        <span class="badge badge-approved">Approved</span>
                    @elseif($st === 'rejected')
                        <span class="badge badge-rejected">Rejected</span>
                    @else
                        <span class="badge">{{ ucfirst($st) }}</span>
                    @endif
                </td>
                <td style="padding:12px 8px;text-align:right">
                    @if($u->status === 'pending')
                        <form method="POST" action="{{ route('accounts.approve', $u) }}" style="display:inline-block" onsubmit="event.stopPropagation();">
                            @csrf
                            <button class="btn btn-primary" style="padding:8px 10px;border-radius:6px;background:#1f8ef1;border:none;color:white;margin-right:8px">Approve</button>
                        </form>
                        <button onclick="event.stopPropagation();document.getElementById('reject-{{ $u->id }}').style.display='block'" style="padding:8px 10px;border-radius:6px;border:1px solid #eee;background:#fff;color:#333">Reject</button>

                        <form id="reject-{{ $u->id }}" method="POST" action="{{ route('accounts.reject', $u) }}" style="display:none;margin-top:8px" onsubmit="event.stopPropagation();">
                            @csrf
                            <input name="reason" placeholder="Optional rejection reason" style="width:100%;padding:8px;margin-top:6px;border-radius:6px;border:1px solid #ddd">
                            <div style="margin-top:6px">
                                <button type="submit" style="padding:8px 10px;border-radius:6px;background:#ff6b6b;border:none;color:white">Confirm Reject</button>
                                <button type="button" onclick="event.stopPropagation();document.getElementById('reject-{{ $u->id }}').style.display='none'" style="padding:8px 10px;border-radius:6px;margin-left:6px">Cancel</button>
                            </div>
                        </form>

                        <form method="POST" action="{{ route('accounts.destroy', $u) }}" style="display:inline-block;margin-left:8px" onsubmit="return confirmDeletion(event, this)">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="padding:8px 10px;border-radius:6px;border:1px solid #eee;background:#fff;color:#d23">Delete</button>
                        </form>
                    @elseif(($u->status ?? '') === 'approved')
                        <form method="POST" action="{{ route('accounts.destroy', $u) }}" style="display:inline-block;margin-left:8px" onsubmit="return confirmDeletion(event, this)">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="padding:8px 10px;border-radius:6px;border:1px solid #eee;background:#fff;color:#d23">Delete</button>
                        </form>
                    @else
                        <span style="color:var(--muted)">No actions</span>
                    @endif
                </td>
            </tr>
            @empty
                <tr><td colspan="5" style="padding:12px">No accounts found.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:12px;display:flex;align-items:center;justify-content:center">
        <div>{{ $users->links() }}</div>
    </div>
    </div>
</div>
 
<!-- Details modal -->
<div id="account-details-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.4);align-items:center;justify-content:center">
    <div class="account-modal" style="display:flex;align-items:center;justify-content:center">
        <div class="modal-inner" style="position:relative">
            <button class="close-btn" onclick="document.getElementById('account-details-modal').style.display='none'" aria-label="Close">×</button>
            <h3 id="modal-name" style="margin-top:0">Request details</h3>
            <div class="modal-row"><div class="label">Email</div><div class="value" id="modal-email"></div></div>
            <div class="modal-row"><div class="label">Registered</div><div class="value" id="modal-registered"></div></div>
            <div class="modal-row"><div class="label">Role</div><div class="value" id="modal-role"></div></div>
            <div class="modal-row"><div class="label">Status</div><div class="value" id="modal-status"></div></div>
            <div id="modal-rejection" style="display:none;margin-top:6px"><div class="label">Rejection reason</div><div class="value"><span></span></div></div>
            <div style="text-align:right;margin-top:12px">
                <button onclick="document.getElementById('account-details-modal').style.display='none'" style="padding:8px 12px;border-radius:6px">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function showDetails(el){
    var name = el.dataset.name || '';
    var email = el.dataset.email || '';
    var registered = el.dataset.registered || '';
    var status = el.dataset.status || '';
    var role = el.dataset.role || '';
    var rejection = el.dataset.rejection || '';

    document.getElementById('modal-name').textContent = name;
    document.getElementById('modal-email').textContent = email;
    document.getElementById('modal-registered').textContent = registered;
    document.getElementById('modal-status').textContent = status;
    document.getElementById('modal-role').textContent = role;
    if(rejection){
        var rej = document.getElementById('modal-rejection');
        rej.style.display='block';
        rej.querySelector('span').textContent = rejection;
    } else {
        document.getElementById('modal-rejection').style.display='none';
    }

    document.getElementById('account-details-modal').style.display='flex';
}

function confirmDeletion(e, form){
    e.stopPropagation();
    if(!confirm('Are you sure you want to delete this account? This action cannot be undone.')){
        return false;
    }
    return true;
}
</script>
@endsection
