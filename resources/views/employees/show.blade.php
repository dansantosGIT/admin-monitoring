@extends('layouts.app')

@section('page-name','Employee Details')

@section('content')
<div class="card">
    <style>
        .btn { display:inline-flex;align-items:center;justify-content:center;padding:8px 12px;border-radius:8px;border:none;background:#111;color:white;text-decoration:none; cursor:pointer; transition:transform .12s ease,box-shadow .12s ease }
        .btn:hover{ transform:translateY(-3px); box-shadow:0 10px 30px rgba(13,30,60,0.12) }
        .btn.secondary{ background:#f3f4f6;color:#111 }
    </style>

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
        <div>
            <h2 style="margin:0">{{ $employee->last_name }}, {{ $employee->first_name }} {{ $employee->middle_name }}</h2>
            <div style="color:var(--muted);font-size:13px">{{ $employee->position }} — {{ $employee->department }}</div>
        </div>
        <div style="display:flex;gap:8px">
            <a href="{{ route('employees.index') }}" class="btn secondary" style="padding:8px 12px;border-radius:8px">Back</a>
            <a href="{{ route('employees.edit', $employee) }}" class="btn" style="padding:8px 12px;border-radius:8px">Edit</a>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:240px 1fr;gap:18px">
        <aside style="background:#fff;padding:14px;border-radius:10px;border:1px solid #f3f4f6;text-align:center">
            @if($employee->photo_path)
                <img src="{{ asset('storage/'.$employee->photo_path) }}" alt="photo" style="width:160px;height:160px;border-radius:50%;object-fit:cover;margin:0 auto 12px">
            @else
                <div style="width:160px;height:160px;background:#f3f4f6;border-radius:50%;margin:0 auto 12px;display:flex;align-items:center;justify-content:center">No photo</div>
            @endif
            <div style="font-weight:700">{{ $employee->first_name }} {{ $employee->last_name }}</div>
            <div style="color:var(--muted);font-size:13px;margin-top:6px">Employee No: {{ $employee->employee_number ?? '-' }}</div>
            <div style="color:var(--muted);font-size:13px;margin-top:4px">Status: {{ $employee->status }}</div>
        </aside>

        <div>
            <section style="background:#fff;padding:14px;border-radius:10px;border:1px solid #f3f4f6;margin-bottom:12px">
                <h3 style="margin-top:0">Personal Information</h3>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                    <div><strong>Full name</strong><div>{{ $employee->first_name }} {{ $employee->middle_name }} {{ $employee->last_name }} {{ $employee->suffix }}</div></div>
                    <div><strong>Birthdate</strong><div>{{ optional($employee->birthdate)->format('F j, Y') }}</div></div>
                    <div><strong>Place of birth</strong><div>{{ $employee->place_of_birth }}</div></div>
                    <div><strong>Civil status</strong><div>{{ $employee->civil_status }}</div></div>
                </div>
            </section>

            <section style="background:#fff;padding:14px;border-radius:10px;border:1px solid #f3f4f6;margin-bottom:12px">
                <h3 style="margin-top:0">Contact & Addresses</h3>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                    <div><strong>Mobile</strong><div>{{ $employee->mobile }}</div></div>
                    <div><strong>Email</strong><div>{{ $employee->email }}</div></div>
                </div>
                <div style="margin-top:8px">
                    <strong>Present Address</strong>
                    <div>{{ optional($employee->present_address)['address'] ?? '' }} {{ optional($employee->present_address)['barangay'] ?? '' }}, {{ optional($employee->present_address)['city'] ?? '' }}, {{ optional($employee->present_address)['province'] ?? '' }} {{ optional($employee->present_address)['zip'] ?? '' }}</div>
                </div>
            </section>

            <section style="background:#fff;padding:14px;border-radius:10px;border:1px solid #f3f4f6;margin-bottom:12px">
                <h3 style="margin-top:0">Employment Details</h3>
                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:8px">
                    <div><strong>Position</strong><div>{{ $employee->position }}</div></div>
                    <div><strong>Department</strong><div>{{ $employee->department }}</div></div>
                </div>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-top:8px">
                    <div><strong>Date Hired</strong><div>{{ optional($employee->date_hired)->format('F j, Y') }}</div></div>
                    <div><strong>Employment Type</strong><div>{{ $employee->employment_type }}</div></div>
                    <div><strong>Monthly Salary</strong><div>{{ $employee->monthly_salary }}</div></div>
                </div>
            </section>

            <section style="background:#fff;padding:14px;border-radius:10px;border:1px solid #f3f4f6;margin-bottom:12px">
                <h3 style="margin-top:0">Government IDs</h3>
                <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:8px">
                    <div><strong>SSS</strong><div>{{ $employee->sss }}</div></div>
                    <div><strong>TIN</strong><div>{{ $employee->tin }}</div></div>
                    <div><strong>PhilHealth</strong><div>{{ $employee->philhealth }}</div></div>
                    <div><strong>Pag-IBIG</strong><div>{{ $employee->pagibig }}</div></div>
                </div>
            </section>

        </div>
    </div>
</div>

@endsection
