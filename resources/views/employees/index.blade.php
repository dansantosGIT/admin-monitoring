@extends('layouts.app')

@section('page-name', 'Employees')

@section('content')
<div class="dashboard-wrapper">
    <div class="card">
        <div class="card__header">
            <div>
                <h2 class="card__title">Employees</h2>
                <p class="card__subtitle">Add and manage employee record details for the monitoring system.</p>
            </div>
            <button id="openEmployeeForm" class="btn btn--primary" type="button">Add Employee Details</button>
        </div>

        <div style="padding:1.25rem; display:grid; gap:1rem;">
            <div class="mini-summary-grid">
                <article class="mini-summary-card">
                    <span>Registered Employees</span>
                    <strong>0</strong>
                </article>
                <article class="mini-summary-card">
                    <span>Active Records</span>
                    <strong>0</strong>
                </article>
                <article class="mini-summary-card">
                    <span>Pending Review</span>
                    <strong>0</strong>
                </article>
            </div>

            <div class="placeholder-box">
                <h3 style="margin:0 0 0.35rem; font-size:1rem;">Employee record section</h3>
                <p style="margin:0; color:#6b7280; font-size:0.92rem;">Use the button above to capture employee details for records until the backend save flow is connected.</p>
            </div>

            <div id="recordStatus" class="record-status" aria-live="polite">Ready to add a new employee record.</div>
        </div>
    </div>
</div>

<div class="employee-modal-overlay" id="employeeModal" aria-hidden="true">
    <div class="employee-modal" role="dialog" aria-modal="true" aria-labelledby="employeeFormTitle">
        <div class="employee-modal__header">
            <div>
                <div id="employeeFormTitle" class="employee-modal__title">Add Employee Details</div>
                <p class="employee-modal__subtitle">Fill in the employee record section for monitoring and file maintenance.</p>
            </div>
            <button id="closeEmployeeForm" class="btn btn--ghost" type="button">Close</button>
        </div>

        <form id="employeeRecordForm" class="employee-form">
            <div class="employee-form__grid">
                <label>Last Name<input type="text" name="last_name" placeholder="Pedro" required></label>
                <label>First Name<input type="text" name="first_name" placeholder="John" required></label>
                <label>Middle Initial<input type="text" name="middle_name" placeholder="F." maxlength="10"></label>
                <label>Suffix<input type="text" name="suffix" placeholder="Jr." maxlength="10"></label>
                <label>Section<input type="text" name="section" placeholder="Operations" required></label>
                <label>Department<select name="department" required>
                    <option value="">Select department</option>
                    <option value="OPERATIONS">OPERATIONS</option>
                    <option value="CEDOC">CEDOC</option>
                    <option value="PLANNING">PLANNING</option>
                    <option value="ADMIN">ADMIN</option>
                </select></label>
                <label>Age<input type="number" name="age" min="18" max="80" placeholder="32"></label>
                <label>Birthdate<input type="date" name="birthdate"></label>
                <label>Employment Type<select name="employment_type">
                    <option value="permanent">Permanent</option>
                    <option value="job_order">Job Order</option>
                </select></label>
                <label>Status<select name="status">
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                    <option value="Separated">Separated</option>
                </select></label>
            </div>
            <label>Remarks<textarea name="remarks" rows="3" placeholder="Add notes for the employee record"></textarea></label>
            <div class="employee-form__actions">
                <button class="btn btn--ghost" type="button" id="cancelEmployeeForm">Cancel</button>
                <button class="btn btn--primary" type="submit">Save Employee Details</button>
            </div>
        </form>
    </div>
</div>

<style>
.mini-summary-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; }
@media (max-width: 800px) { .mini-summary-grid { grid-template-columns:1fr; } }
.mini-summary-card { background:#fff; border:1px solid #f3f4f6; border-radius:12px; padding:12px; box-shadow:0 1px 3px rgba(0,0,0,0.05); }
.mini-summary-card span { display:block; font-size:12px; color:#6b7280; text-transform:uppercase; letter-spacing:.08em; }
.mini-summary-card strong { display:block; font-size:1.35rem; color:#111827; margin-top:6px; }
.placeholder-box { background:#fff7f7; border:1px solid #fde2e2; border-radius:12px; padding:1rem; }
.record-status { color:#c0392b; font-size:0.92rem; font-weight:600; }
.employee-modal-overlay { position:fixed; inset:0; display:none; align-items:center; justify-content:center; background:rgba(2,6,23,0.45); z-index:1000; }
.employee-modal-overlay.open { display:flex; }
.employee-modal { width:min(860px, 96%); max-height:88vh; overflow:auto; background:#fff; border-radius:14px; box-shadow:0 12px 30px rgba(2,6,23,0.18); padding:14px; }
.employee-modal__header { display:flex; align-items:flex-start; justify-content:space-between; gap:12px; border-bottom:1px solid #f3f4f6; padding-bottom:12px; margin-bottom:12px; }
.employee-modal__title { font-size:1.08rem; font-weight:800; color:#111827; }
.employee-modal__subtitle { margin:4px 0 0; color:#6b7280; font-size:0.92rem; }
.employee-form__grid { display:grid; grid-template-columns:repeat(2,1fr); gap:12px; }
@media (max-width: 700px) { .employee-form__grid { grid-template-columns:1fr; } }
.employee-form label { display:grid; gap:6px; font-size:0.92rem; color:#374151; font-weight:600; }
.employee-form input, .employee-form select, .employee-form textarea { border:1px solid #e5e7eb; border-radius:10px; padding:10px; font-size:0.95rem; color:#111827; background:#fff; }
.employee-form textarea { resize:vertical; }
.employee-form__actions { display:flex; justify-content:flex-end; gap:8px; margin-top:12px; }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const openBtn = document.getElementById('openEmployeeForm');
        const closeBtn = document.getElementById('closeEmployeeForm');
        const cancelBtn = document.getElementById('cancelEmployeeForm');
        const overlay = document.getElementById('employeeModal');
        const form = document.getElementById('employeeRecordForm');
        const status = document.getElementById('recordStatus');

        function openModal() { overlay.classList.add('open'); overlay.setAttribute('aria-hidden', 'false'); }
        function closeModal() { overlay.classList.remove('open'); overlay.setAttribute('aria-hidden', 'true'); }

        openBtn && openBtn.addEventListener('click', openModal);
        closeBtn && closeBtn.addEventListener('click', closeModal);
        cancelBtn && cancelBtn.addEventListener('click', closeModal);
        overlay && overlay.addEventListener('click', function (e) { if (e.target === overlay) closeModal(); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeModal(); });

        form && form.addEventListener('submit', function (e) {
            e.preventDefault();
            const data = Object.fromEntries(new FormData(form).entries());
            status.textContent = `Placeholder saved: ${data.first_name || 'Employee'} ${data.last_name || ''} for ${data.section || 'section'} records.`;
            form.reset();
            closeModal();
        });
    });
</script>
@endpush
@endsection
