@extends('layouts.app')

@section('page-name','Add Employee')

@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
        <div>
            <h2 style="margin:0">Add Employee</h2>
            <div style="color:var(--muted);font-size:13px">Complete the Personal Data Sheet (PDS) below.</div>
        </div>
        <div>
            <a href="{{ route('employees.index') }}" class="btn" style="padding:8px 12px;border-radius:8px">Back to list</a>
        </div>
    </div>

    <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">
        @csrf
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
            .form-divider{ height:1px;background:#eef2f6;margin:18px 0;border-radius:2px }

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

            /* Helper text */
            .hint { color:#6b7280;font-size:13px;margin-top:6px }
        </style>

        <div style="display:grid;grid-template-columns:1fr 320px;gap:18px;align-items:start">
            <div>
                <section class="form-section">
                    <h3>Personal Information</h3>
                    <div class="section-sub">Tell us more about yourself.</div>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px">
                        <label>Last Name<input name="last_name" value="{{ old('last_name') }}" required></label>
                        <label>First Name<input name="first_name" value="{{ old('first_name') }}" required></label>
                        <label>Middle Name<input name="middle_name" value="{{ old('middle_name') }}"></label>
                    </div>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-top:8px">
                        <label>Suffix<input name="suffix" value="{{ old('suffix') }}"></label>
                        <label>Maiden Name<input name="maiden_name" value="{{ old('maiden_name') }}"></label>
                        <label>Sex<select name="sex"><option value="">Select</option><option value="Male" {{ old('sex')=='Male'?'selected':'' }}>Male</option><option value="Female" {{ old('sex')=='Female'?'selected':'' }}>Female</option></select></label>
                    </div>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-top:8px">
                        <label>Birthdate<input type="date" name="birthdate" value="{{ old('birthdate') }}"></label>
                        <label>Place of Birth<input name="place_of_birth" value="{{ old('place_of_birth') }}"></label>
                        <label>Civil Status<select name="civil_status"><option value="">Select</option><option value="Single" {{ old('civil_status')=='Single'?'selected':'' }}>Single</option><option value="Married" {{ old('civil_status')=='Married'?'selected':'' }}>Married</option><option value="Widowed" {{ old('civil_status')=='Widowed'?'selected':'' }}>Widowed</option><option value="Separated" {{ old('civil_status')=='Separated'?'selected':'' }}>Separated</option></select></label>
                    </div>
                </section>

                <section class="form-section">
                    <h3>Contact & Addresses</h3>
                    <div class="section-sub">Where we can reach you</div>
                    <div style="display:grid;gap:8px">
                        <label>Present Address<input name="present_address" placeholder="House no, Street" value="{{ old('present_address') }}"></label>
                        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px">
                            <input name="present_barangay" placeholder="Barangay" value="{{ old('present_barangay') }}">
                            <input name="present_city" placeholder="City/Municipality" value="{{ old('present_city') }}">
                            <input name="present_province" placeholder="Province" value="{{ old('present_province') }}">
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:6px">
                            <input name="present_zip" placeholder="ZIP" value="{{ old('present_zip') }}">
                            <input name="mobile" placeholder="Mobile Number" value="{{ old('mobile') }}">
                        </div>
                        <label>Permanent Address<input name="permanent_address" placeholder="(if different)" value="{{ old('permanent_address') }}"></label>
                    </div>
                </section>

                <section class="form-section">
                    <h3>Government IDs</h3>
                    <div class="section-sub">Official identification numbers</div>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px">
                        <input name="sss" placeholder="SSS No" value="{{ old('sss') }}">
                        <input name="tin" placeholder="TIN" value="{{ old('tin') }}">
                        <input name="philhealth" placeholder="PhilHealth" value="{{ old('philhealth') }}">
                    </div>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-top:8px">
                        <input name="pagibig" placeholder="Pag-IBIG" value="{{ old('pagibig') }}">
                        <input name="gsis" placeholder="GSIS" value="{{ old('gsis') }}">
                        <input name="email" placeholder="Email" value="{{ old('email') }}">
                    </div>
                </section>

                <section class="form-section">
                    <h3>Employment Details</h3>
                    <div class="section-sub">Role and compensation details</div>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px">
                        <input name="employee_number" placeholder="Employee No" value="{{ old('employee_number') }}">
                        <input name="position" placeholder="Position / Title" value="{{ old('position') }}">
                        <input name="department" placeholder="Department" value="{{ old('department') }}">
                    </div>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-top:8px">
                        <input name="section" placeholder="Section" value="{{ old('section') }}">
                        <input type="date" name="date_hired" placeholder="Date Hired" value="{{ old('date_hired') }}">
                        <label>Employment Type
                            <select name="employment_type">
                                <option value="">Select</option>
                                <option value="JO" {{ old('employment_type')=='JO'?'selected':'' }}>Job Order</option>
                                <option value="Permanent" {{ old('employment_type')=='Permanent'?'selected':'' }}>Permanent</option>
                            </select>
                        </label>
                    </div>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-top:8px">
                        <input name="salary_grade" placeholder="Salary Grade" value="{{ old('salary_grade') }}">
                        <input name="monthly_salary" placeholder="Monthly Salary" value="{{ old('monthly_salary') }}">
                        <input name="supervisor_id" placeholder="Supervisor ID" value="{{ old('supervisor_id') }}">
                    </div>
                </section>

                <section class="form-section">
                    <h3>Family / Education (brief)</h3>
                    <div class="section-sub">Immediate family and highest education</div>
                    <div style="display:grid;gap:8px">
                        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:8px">
                            <input name="spouse_name" placeholder="Spouse Name" value="{{ old('spouse_name') }}">
                            <input name="spouse_occupation" placeholder="Spouse Occupation" value="{{ old('spouse_occupation') }}">
                        </div>
                        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:8px;margin-top:8px">
                            <input name="father_name" placeholder="Father's Name" value="{{ old('father_name') }}">
                            <input name="mother_name" placeholder="Mother's Maiden Name" value="{{ old('mother_name') }}">
                        </div>
                        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:8px;margin-top:8px">
                            <input name="elem_school" placeholder="Elementary School" value="{{ old('elem_school') }}">
                            <input name="hs_school" placeholder="High School" value="{{ old('hs_school') }}">
                        </div>
                        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:8px;margin-top:8px">
                            <input name="college_school" placeholder="College / Degree" value="{{ old('college_school') }}">
                            <input name="grad_school" placeholder="Graduate Studies" value="{{ old('grad_school') }}">
                        </div>
                    </div>
                </section>

                <section class="form-section">
                    <h3>Remarks / Attachments</h3>
                    <div class="section-sub">Optional notes and supporting documents</div>
                    <textarea name="remarks" rows="4" placeholder="Notes or remarks">{{ old('remarks') }}</textarea>
                </section>

                <div style="display:flex;justify-content:flex-end;gap:8px">
                    <a href="{{ route('employees.index') }}" class="btn" style="padding:10px 14px">Cancel</a>
                    <button class="btn btn-primary" type="submit" style="padding:10px 14px">Save Employee</button>
                </div>
            </div>

            <aside style="position:relative">
                <div style="background:#fff;padding:14px;border-radius:10px;border:1px solid #f3f4f6;margin-bottom:12px;text-align:center">
                    <div style="width:140px;height:140px;background:#f3f4f6;border-radius:50%;margin:0 auto 12px;display:flex;align-items:center;justify-content:center">Photo</div>
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
