@extends('layouts.app')

@section('page-name','Edit Employee')

@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
        <div>
            <h2 style="margin:0">Edit Employee</h2>
            <div style="color:var(--muted);font-size:13px">Update the Personal Data Sheet (PDS).</div>
        </div>
        <div>
            <a href="{{ route('employees.show', $employee) }}" class="btn" style="padding:8px 12px;border-radius:8px">Back</a>
        </div>
    </div>

    <form method="POST" action="{{ route('employees.update', $employee) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <style>
            /* Buttons */
            .btn { display:inline-flex;align-items:center;justify-content:center;padding:8px 12px;border-radius:8px;border:none;background:#374151;color:white;text-decoration:none; cursor:pointer; transition:transform .12s ease,box-shadow .12s ease }
            .btn:hover{ transform:translateY(-3px); box-shadow:0 10px 30px rgba(13,30,60,0.08) }
            .btn-primary{ background:#0b6df0 }
            .btn.secondary{ background:#f3f4f6;color:#111 }

            /* Form sections */
            .form-section{ background:#fff;padding:22px;border-radius:8px;border:1px solid #eef2f6;margin-bottom:18px;box-shadow:0 1px 0 rgba(16,24,40,0.02) }
            .form-section h3{ text-align:center;margin:0;font-size:18px;color:#111 }
            .form-section .section-sub{ text-align:center;color:#6b7280;font-size:13px;margin-top:6px;margin-bottom:14px }

            /* Fields */
            /* Box each labeled field so label + input share a visible container */
            label { display:block;font-size:13px;color:#374151;font-weight:600; padding:10px; border:1px solid #e6e9ee; border-radius:8px; background:#ffffff }
            label > input, label > select, label > textarea { display:block;margin-top:8px;border:none;background:transparent;padding:0;height:44px;font-size:14px;color:#0f172a }

            /* Standalone inputs (when there's no label wrapper) keep their own boxed style */
            input[type="text"], input[type="date"], input[type="email"], input[type="search"], input[type="number"], select, textarea {
                width:100%;
                padding:10px 12px;
                height:44px;
                border:1px solid #e6e9ee;
                border-radius:8px;
                background:#fff;
                box-shadow:none;
                outline:none;
                transition:box-shadow .12s ease, border-color .12s ease;
                font-size:14px;
                color:#0f172a;
            }
            textarea{ height:auto; min-height:100px;padding:10px }
            input::placeholder, textarea::placeholder { color:#9ca3af }
            input:focus, select:focus, textarea:focus { border-color:#0b6df0; box-shadow:0 6px 22px rgba(11,109,240,0.06) }
        </style>
        @if($errors->any())
            <div style="padding:10px;background:#fff5f5;border:1px solid #f5c6cb;border-radius:6px;margin-bottom:12px;color:#842029">
                <strong>There were some problems with your input:</strong>
                <ul style="margin:8px 0 0 16px">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div style="display:grid;grid-template-columns:1fr 320px;gap:18px;align-items:start">
            <div>
                <section style="background:#fff;padding:14px;border-radius:10px;border:1px solid #f3f4f6;margin-bottom:12px">
                    <h3 style="margin-top:0">Personal Information</h3>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px">
                        <label>Last Name<input name="last_name" value="{{ old('last_name', $employee->last_name) }}" required></label>
                        <label>First Name<input name="first_name" value="{{ old('first_name', $employee->first_name) }}" required></label>
                        <label>Middle Name<input name="middle_name" value="{{ old('middle_name', $employee->middle_name) }}"></label>
                    </div>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-top:8px">
                        <label>Suffix<input name="suffix" value="{{ old('suffix', $employee->suffix) }}"></label>
                        <label>Maiden Name<input name="maiden_name" value="{{ old('maiden_name', $employee->maiden_name) }}"></label>
                        <label>Sex<select name="sex"><option value="">Select</option><option value="Male" {{ old('sex', $employee->sex)=='Male'?'selected':'' }}>Male</option><option value="Female" {{ old('sex', $employee->sex)=='Female'?'selected':'' }}>Female</option></select></label>
                    </div>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-top:8px">
                        <label>Birthdate<input type="date" name="birthdate" value="{{ old('birthdate', optional($employee->birthdate)->format('Y-m-d')) }}"></label>
                        <label>Place of Birth<input name="place_of_birth" value="{{ old('place_of_birth', $employee->place_of_birth) }}"></label>
                        <label>Civil Status<select name="civil_status"><option value="">Select</option><option value="Single" {{ old('civil_status', $employee->civil_status)=='Single'?'selected':'' }}>Single</option><option value="Married" {{ old('civil_status', $employee->civil_status)=='Married'?'selected':'' }}>Married</option><option value="Widowed" {{ old('civil_status', $employee->civil_status)=='Widowed'?'selected':'' }}>Widowed</option><option value="Separated" {{ old('civil_status', $employee->civil_status)=='Separated'?'selected':'' }}>Separated</option></select></label>
                    </div>
                </section>

                <section style="background:#fff;padding:14px;border-radius:10px;border:1px solid #f3f4f6;margin-bottom:12px">
                    <h3 style="margin-top:0">Contact & Addresses</h3>
                    <div style="display:grid;gap:8px">
                        <label>Present Address<input name="present_address" placeholder="House no, Street" value="{{ old('present_address', optional($employee->present_address)['address'] ?? '') }}"></label>
                        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px">
                            <input name="present_barangay" placeholder="Barangay" value="{{ old('present_barangay', optional($employee->present_address)['barangay'] ?? '') }}">
                            <input name="present_city" placeholder="City/Municipality" value="{{ old('present_city', optional($employee->present_address)['city'] ?? '') }}">
                            <input name="present_province" placeholder="Province" value="{{ old('present_province', optional($employee->present_address)['province'] ?? '') }}">
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:6px">
                            <input name="present_zip" placeholder="ZIP" value="{{ old('present_zip', optional($employee->present_address)['zip'] ?? '') }}">
                            <input name="mobile" placeholder="Mobile Number" value="{{ old('mobile', $employee->mobile) }}">
                        </div>
                        <label>Permanent Address<input name="permanent_address" placeholder="(if different)" value="{{ old('permanent_address', optional($employee->permanent_address)['address'] ?? '') }}"></label>
                    </div>
                </section>

                <section style="background:#fff;padding:14px;border-radius:10px;border:1px solid #f3f4f6;margin-bottom:12px">
                    <h3 style="margin-top:0">Government IDs</h3>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px">
                        <input name="sss" placeholder="SSS No" value="{{ old('sss', $employee->sss) }}">
                        <input name="tin" placeholder="TIN" value="{{ old('tin', $employee->tin) }}">
                        <input name="philhealth" placeholder="PhilHealth" value="{{ old('philhealth', $employee->philhealth) }}">
                    </div>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-top:8px">
                        <input name="pagibig" placeholder="Pag-IBIG" value="{{ old('pagibig', $employee->pagibig) }}">
                        <input name="gsis" placeholder="GSIS" value="{{ old('gsis', $employee->gsis) }}">
                        <input name="email" placeholder="Email" value="{{ old('email', $employee->email) }}">
                    </div>
                </section>

                <section style="background:#fff;padding:14px;border-radius:10px;border:1px solid #f3f4f6;margin-bottom:12px">
                    <h3 style="margin-top:0">Employment Details</h3>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px">
                        <input name="employee_number" placeholder="Employee No" value="{{ old('employee_number', $employee->employee_number) }}">
                        <input name="position" placeholder="Position / Title" value="{{ old('position', $employee->position) }}">
                        <input name="department" placeholder="Department" value="{{ old('department', $employee->department) }}">
                    </div>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-top:8px">
                        <input name="section" placeholder="Section" value="{{ old('section', $employee->section) }}">
                        <input type="date" name="date_hired" placeholder="Date Hired" value="{{ old('date_hired', optional($employee->date_hired)->format('Y-m-d')) }}">
                        <label>Employment Type
                            <select name="employment_type">
                                <option value="">Select</option>
                                <option value="JO" {{ old('employment_type', $employee->employment_type)=='JO'?'selected':'' }}>Job Order</option>
                                <option value="Permanent" {{ old('employment_type', $employee->employment_type)=='Permanent'?'selected':'' }}>Permanent</option>
                            </select>
                        </label>
                    </div>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-top:8px">
                        <input name="salary_grade" placeholder="Salary Grade" value="{{ old('salary_grade', $employee->salary_grade) }}">
                        <input name="monthly_salary" placeholder="Monthly Salary" value="{{ old('monthly_salary', $employee->monthly_salary) }}">
                        <input name="supervisor_id" placeholder="Supervisor ID" value="{{ old('supervisor_id', $employee->supervisor_id) }}">
                    </div>
                </section>

                <section style="background:#fff;padding:14px;border-radius:10px;border:1px solid #f3f4f6;margin-bottom:12px">
                    <h3 style="margin-top:0">Family / Education (brief)</h3>
                    <div style="display:grid;gap:8px">
                        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:8px">
                            <input name="spouse_name" placeholder="Spouse Name" value="{{ old('spouse_name', optional($employee->spouse)['name'] ?? '') }}">
                            <input name="spouse_occupation" placeholder="Spouse Occupation" value="{{ old('spouse_occupation', optional($employee->spouse)['occupation'] ?? '') }}">
                        </div>
                        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:8px;margin-top:8px">
                            <input name="father_name" placeholder="Father's Name" value="{{ old('father_name', optional($employee->parents)['father'] ?? '') }}">
                            <input name="mother_name" placeholder="Mother's Maiden Name" value="{{ old('mother_name', optional($employee->parents)['mother'] ?? '') }}">
                        </div>
                        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:8px;margin-top:8px">
                            <input name="elem_school" placeholder="Elementary School" value="{{ old('elem_school', optional($employee->education)['elementary'] ?? '') }}">
                            <input name="hs_school" placeholder="High School" value="{{ old('hs_school', optional($employee->education)['secondary'] ?? '') }}">
                        </div>
                        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:8px;margin-top:8px">
                            <input name="college_school" placeholder="College / Degree" value="{{ old('college_school', optional($employee->education)['college'] ?? '') }}">
                            <input name="grad_school" placeholder="Graduate Studies" value="{{ old('grad_school', optional($employee->education)['graduate'] ?? '') }}">
                        </div>
                    </div>
                </section>

                <section style="background:#fff;padding:14px;border-radius:10px;border:1px solid #f3f4f6;margin-bottom:12px">
                    <h3 style="margin-top:0">Remarks / Attachments</h3>
                    <textarea name="remarks" rows="4" placeholder="Notes or remarks" style="width:100%;padding:10px;border-radius:8px;border:1px solid #eee">{{ old('remarks', $employee->remarks) }}</textarea>
                </section>

                <div style="display:flex;justify-content:flex-end;gap:8px">
                    <a href="{{ route('employees.show', $employee) }}" class="btn" style="padding:10px 14px">Cancel</a>
                    <button class="btn btn-primary" type="submit" style="padding:10px 14px">Save Employee</button>
                </div>
            </div>

            <aside style="position:relative">
                <div style="background:#fff;padding:14px;border-radius:10px;border:1px solid #f3f4f6;margin-bottom:12px;text-align:center">
                    @if($employee->photo_path)
                        <img src="{{ asset('storage/'.$employee->photo_path) }}" alt="photo" style="width:160px;height:160px;border-radius:50%;object-fit:cover;margin:0 auto 12px">
                    @else
                        <div style="width:160px;height:160px;background:#f3f4f6;border-radius:50%;margin:0 auto 12px;display:flex;align-items:center;justify-content:center">No photo</div>
                    @endif
                    <input type="file" name="photo" accept="image/*" style="display:block;margin:0 auto">
                    <div style="color:var(--muted);font-size:13px;margin-top:8px">Upload a photo (jpg, png)</div>
                </div>

                <div style="background:#fff;padding:14px;border-radius:10px;border:1px solid #f3f4f6;">
                    <h4 style="margin:0 0 8px">Quick Info</h4>
                    <div style="color:var(--muted);font-size:13px">Provide core fields: name, birthdate, contact, position, department, and date hired.</div>
                </div>
            </aside>
        </div>
    </form>
</div>

@endsection
