@extends('frontend.layouts2.master')
@section('title', 'Stamp Duty Exemption Application Form')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
    :root{
        --brand: #ff6600;
        --brand-dark: #e25500;
    }
    .form-icon {
        color: var(--brand);
        margin-right:.35rem;
    }
    .required::after {
        content: " *";
        color: #dc3545;
        margin-left: 0.15rem;
        font-weight: 600;
    }
    .no-underline,
    .no-underline:hover,
    .no-underline:focus,
    .no-underline:active {
        text-decoration: none !important;
    }
    .is-valid { border-color: #28a745 !important; }
    .is-invalid { border-color: #dc3545 !important; }

    .enc-preview img {
        max-height: 60px;
        cursor: pointer;
    }
    .enc-remove {
        padding: 0;
        border: none;
    }

    /* Preview Modal Styles */
    .preview-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        border-left: 4px solid var(--brand);
    }
    .preview-label {
        font-weight: 600;
        color: #333;
        min-width: 200px;
    }
    .preview-value {
        color: #555;
    }
    .preview-na {
        color: #6c757d;
        font-style: italic;
    }
    .preview-table {
        font-size: 0.9rem;
    }
    .preview-table th {
        background-color: #e9ecef;
        font-weight: 600;
    }

    label {
        font-weight: 600;
        margin-top: 10px;
    }

    .section-title {
        font-size: 20px;
        font-weight: 700;
        margin-top: 25px;
        padding-bottom: 5px;
        border-bottom: 2px solid #0d6efd;
    }

    table input {
        width: 100%;
        padding: 5px;
        border: 1px solid #ced4da;
        border-radius: 4px;
    }

    th {
        background: #0d6efd;
        color: white;
        text-align: center;
    }

    .title {
        font-size: 20px;
        font-weight: 700;
        margin-top: 20px;
        padding-bottom: 5px;
        border-bottom: 2px solid #0d6efd;
    }

    .total-field {
        font-weight: bold;
        background: #e9ecef;
    }

    .preview-box {
        width: 180px;
        height: 50px;
        border: 1px solid #ced4da;
        border-radius: 5px;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .preview-box img {
        width: 100%;
        height: auto;
    }

    .error {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
</style>
@endpush

@section('content')
<section class="section">
    <div class="section-header d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-3">
        <h1 class="mb-2 mb-md-0">
            <i class="fa-solid fa-route" style="color:#ff6600;"></i>
            Application for Stamp Duty Exemption
        </h1>

        <a href="{{ route('stamp-duty.index') }}"
           class="text-white fw-bold d-inline-flex align-items-center no-underline"
           style="background-color:#3006ea; border:none; border-radius:8px; padding:.4rem 1.3rem;">
            <i class="bi bi-arrow-left me-2 mx-2"></i> Back
        </a>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form id="stampDutyForm" method="POST"
                         {{-- action="{{ route('stamp-duty.store') }}" --}}
                         enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="application_type" value="stamp_duty">

                            <div class="container my-4">
                                <h2 class="text-center mb-4" style="color: blue">Application for NOC for getting exemption from Stamp Duty</h2>
                                <h3 class="text-center mb-4">(Under Tourism Policy 2024, vide No.TDS/2022/09/C.R.542/Tourism – 4, dt.18/07/2024 for New Eligible Tourism Project / Expansion of Existing Project.)</h3>

                                <!-- Region & District Selection -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6 mt-2">
                                        <div class="form-group mb-3">
                                            <label for="region_id" class="form-label required">
                                                <i class="bi bi-map form-icon"></i> Select Region
                                            </label>
                                            <select id="region_id" name="region_id"
                                                    class="form-control @error('region_id') is-invalid @enderror"
                                                    onchange="get_Region_District(this.value)">
                                                <option value="" selected disabled>Select Region</option>
                                                @foreach($regions as $r)
                                                    <option value="{{ $r->id }}" {{ old('region_id') == $r->id ? 'selected' : '' }}>
                                                        {{ $r->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="error" id="region_id_error"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="district_id" class="form-label required">
                                                <i class="bi bi-geo form-icon"></i> Select District
                                            </label>
                                            <select id="district_id" name="district_id" class="form-control district_id @error('district_id') is-invalid @enderror">
                                                <option value="" selected disabled>Select District</option>
                                            </select>
                                            <div class="error" id="district_id_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- SECTION A -->
                                <div class="section-title mb-3">Section A: General Details</div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label required">Applicant / Company Name</label>
                                        <input type="text" class="form-control" name="company_name"
                                               pattern="^[A-Za-z0-9\s\.,&'-]+$" title="Enter valid company name"
                                               value="{{ old('company_name') }}" required>
                                        <div class="error" id="company_name_error"></div>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label required">Registration No.</label>
                                        <input type="text" class="form-control" name="registration_no"
                                               value="{{ old('registration_no') }}" required>
                                        <div class="error" id="registration_no_error"></div>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label required">Date</label>
                                        <input type="date" class="form-control" name="application_date"
                                               value="{{ old('application_date', date('Y-m-d')) }}" required>
                                        <div class="error" id="application_date_error"></div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <label class="form-label required">Type of Enterprise</label>
                                    <select class="form-control" name="applicant_type" id="applicant_type" required>
                                        <option value="">Select</option>
                                        @foreach ($enterprises as $enterprise)
                                            <option value="{{ $enterprise['id'] }}" {{ old('applicant_type') == $enterprise['id'] ? 'selected' : '' }}>
                                                {{ $enterprise['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="error" id="applicant_type_error"></div>
                                </div>

                                <div class="mt-3">
                                    <label class="form-label required">Agreement to be made</label>
                                    <select class="form-select" name="agreement_type" required>
                                        <option value="">Select</option>
                                        <option value="Purchase Deed" {{ old('agreement_type') == 'Purchase Deed' ? 'selected' : '' }}>Purchase Deed</option>
                                        <option value="Lease Deed" {{ old('agreement_type') == 'Lease Deed' ? 'selected' : '' }}>Lease Deed</option>
                                        <option value="Mortgage" {{ old('agreement_type') == 'Mortgage' ? 'selected' : '' }}>Mortgage</option>
                                        <option value="Hypothecation" {{ old('agreement_type') == 'Hypothecation' ? 'selected' : '' }}>Hypothecation</option>
                                    </select>
                                    <div class="error" id="agreement_type_error"></div>
                                </div>

                                <!-- Correspondence Address -->
                                <div class="section-title mt-4 mb-3">Correspondence Address</div>

                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label required">Address</label>
                                        <input type="text" class="form-control" name="c_address"
                                               value="{{ old('c_address') }}" required>
                                        <div class="error" id="c_address_error"></div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label required">Village/City</label>
                                        <input type="text" class="form-control" name="c_city"
                                               pattern="^[A-Za-z\s]+$" title="Only letters and spaces allowed"
                                               onkeypress="return validateName(event)"
                                               value="{{ old('c_city') }}" required>
                                        <div class="error" id="c_city_error"></div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label required">Taluka</label>
                                        <input type="text" class="form-control" name="c_taluka"
                                               pattern="^[A-Za-z\s]+$" title="Only letters and spaces allowed"
                                               onkeypress="return validateName(event)"
                                               value="{{ old('c_taluka') }}" required>
                                        <div class="error" id="c_taluka_error"></div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label required">District</label>
                                        <input type="text" class="form-control" name="c_district"
                                               pattern="^[A-Za-z\s]+$" title="Only letters and spaces allowed"
                                               onkeypress="return validateName(event)"
                                               value="{{ old('c_district') }}" required>
                                        <div class="error" id="c_district_error"></div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label required">State</label>
                                        <input type="text" class="form-control" name="c_state"
                                               pattern="^[A-Za-z\s]+$" title="Only letters and spaces allowed"
                                               onkeypress="return validateName(event)"
                                               value="{{ old('c_state') }}" required>
                                        <div class="error" id="c_state_error"></div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label required">Pin Code</label>
                                        <input type="text" class="form-control" name="c_pincode"
                                               pattern="^[1-9][0-9]{5}$" title="Enter 6-digit pin code"
                                               maxlength="6" value="{{ old('c_pincode') }}" required>
                                        <div class="error" id="c_pincode_error"></div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label required">Cell Phone No.</label>
                                        <input type="text" class="form-control" name="c_mobile"
                                               pattern="^[6-9][0-9]{9}$" title="Enter valid 10-digit mobile number"
                                               maxlength="10" onkeypress="return validatePhoneInput(event)"
                                               value="{{ old('c_mobile') }}" required>
                                        <div class="error" id="c_mobile_error"></div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Telephone No.</label>
                                        <input type="text" class="form-control" name="c_phone"
                                               pattern="^[0-9+\-\s]+$" title="Enter valid phone number"
                                               value="{{ old('c_phone') }}">
                                        <div class="error" id="c_phone_error"></div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label required">Email ID</label>
                                        <input type="email" class="form-control" name="c_email"
                                               value="{{ old('c_email') }}" required>
                                        <div class="error" id="c_email_error"></div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Fax</label>
                                        <input type="text" class="form-control" name="c_fax"
                                               value="{{ old('c_fax') }}">
                                        <div class="error" id="c_fax_error"></div>
                                    </div>
                                </div>

                                <!-- Project Site Address -->
                                <div class="section-title mt-4 mb-3">Project Site Address</div>

                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label required">Address (Gat No./ Survey No.)</label>
                                        <input type="text" class="form-control" name="p_address"
                                               value="{{ old('p_address') }}" required>
                                        <div class="error" id="p_address_error"></div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label required">Village/City</label>
                                        <input type="text" class="form-control" name="p_city"
                                               pattern="^[A-Za-z\s]+$" title="Only letters and spaces allowed"
                                               onkeypress="return validateName(event)"
                                               value="{{ old('p_city') }}" required>
                                        <div class="error" id="p_city_error"></div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label required">Taluka</label>
                                        <input type="text" class="form-control" name="p_taluka"
                                               pattern="^[A-Za-z\s]+$" title="Only letters and spaces allowed"
                                               onkeypress="return validateName(event)"
                                               value="{{ old('p_taluka') }}" required>
                                        <div class="error" id="p_taluka_error"></div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label required">District</label>
                                        <input type="text" class="form-control" name="p_district"
                                               pattern="^[A-Za-z\s]+$" title="Only letters and spaces allowed"
                                               onkeypress="return validateName(event)"
                                               value="{{ old('p_district') }}" required>
                                        <div class="error" id="p_district_error"></div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label required">State</label>
                                        <input type="text" class="form-control" name="p_state"
                                               pattern="^[A-Za-z\s]+$" title="Only letters and spaces allowed"
                                               onkeypress="return validateName(event)"
                                               value="{{ old('p_state') }}" required>
                                        <div class="error" id="p_state_error"></div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label required">Pin Code</label>
                                        <input type="text" class="form-control" name="p_pincode"
                                               pattern="^[1-9][0-9]{5}$" title="Enter 6-digit pin code"
                                               maxlength="6" value="{{ old('p_pincode') }}" required>
                                        <div class="error" id="p_pincode_error"></div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label required">Cell Phone No.</label>
                                        <input type="text" class="form-control" name="p_mobile"
                                               pattern="^[6-9][0-9]{9}$" title="Enter valid 10-digit mobile number"
                                               maxlength="10" onkeypress="return validatePhoneInput(event)"
                                               value="{{ old('p_mobile') }}" required>
                                        <div class="error" id="p_mobile_error"></div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Telephone No.</label>
                                        <input type="text" class="form-control" name="p_phone"
                                               pattern="^[0-9+\-\s]+$" title="Enter valid phone number"
                                               value="{{ old('p_phone') }}">
                                        <div class="error" id="p_phone_error"></div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label required">Email ID</label>
                                        <input type="email" class="form-control" name="p_email"
                                               value="{{ old('p_email') }}" required>
                                        <div class="error" id="p_email_error"></div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Website</label>
                                        <input type="text" class="form-control" name="p_website"
                                               placeholder="http://" value="{{ old('p_website') }}">
                                        <div class="error" id="p_website_error"></div>
                                    </div>
                                </div>

                                <!-- Additional Section A Info -->
                                <div class="mt-4">
                                    <label class="form-label required">Estimated Project Cost (₹)</label>
                                    <input type="number" class="form-control" name="estimated_project_cost"
                                           step="0.01" min="0" value="{{ old('estimated_project_cost') }}" required>
                                    <div class="error" id="estimated_project_cost_error"></div>
                                </div>

                                <div class="mt-3">
                                    <label class="form-label required">Proposed Employment Generation</label>
                                    <input type="number" class="form-control" name="proposed_employment"
                                           min="0" value="{{ old('proposed_employment') }}" required>
                                    <div class="error" id="proposed_employment_error"></div>
                                </div>

                                <div class="mt-3">
                                    <label class="form-label required">Tourism Activities / Facilities</label>
                                    <textarea class="form-control" rows="3" name="tourism_activities" required>{{ old('tourism_activities') }}</textarea>
                                    <div class="error" id="tourism_activities_error"></div>
                                </div>

                                <div class="mt-3">
                                    <label class="form-label">Details of incentives availed earlier</label>
                                    <textarea class="form-control" rows="3" name="incentives_availed">{{ old('incentives_availed') }}</textarea>
                                    <div class="error" id="incentives_availed_error"></div>
                                </div>

                                <div class="mt-3">
                                    <label class="form-label required">Tourism Project existed before 18/07/2024?</label>
                                    <select class="form-select" name="existed_before" id="existedBefore" required>
                                        <option value="0" {{ old('existed_before') == '0' ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ old('existed_before') == '1' ? 'selected' : '' }}>Yes</option>
                                    </select>
                                    <div class="error" id="existed_before_error"></div>
                                </div>

                                <div id="eligibilitySection" style="display:none;" class="mt-3">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label required">Eligibility Certificate No.</label>
                                            <input type="text" class="form-control" name="eligibility_cert_no"
                                                   value="{{ old('eligibility_cert_no') }}">
                                            <div class="error" id="eligibility_cert_no_error"></div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label required">Eligibility Date</label>
                                            <input type="date" class="form-control" name="eligibility_date"
                                                   value="{{ old('eligibility_date') }}">
                                            <div class="error" id="eligibility_date_error"></div>
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <label class="form-label required">Present Status of Project</label>
                                        <textarea class="form-control" rows="2" name="present_status">{{ old('present_status') }}</textarea>
                                        <div class="error" id="present_status_error"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section B: Land Details -->
                            <div class="container my-4">
                                <div class="section-title mb-3">Section B: Land & Built-up Area Details</div>

                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle">
                                        <thead>
                                            <tr>
                                                <th style="width: 30%;">Component</th>
                                                <th>Particulars</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Land or Built-up area to be purchased for Tourism Project</td>
                                                <td>
                                                    <div class="row g-3">
                                                        <div class="col-md-4">
                                                            <label class="form-label required">CTS / Gat No.</label>
                                                            <input type="text" class="form-control" name="land_gat"
                                                                   value="{{ old('land_gat') }}" required>
                                                            <div class="error" id="land_gat_error"></div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label required">Village</label>
                                                            <input type="text" class="form-control" name="land_village"
                                                                   pattern="^[A-Za-z\s]+$" title="Only letters and spaces allowed"
                                                                   onkeypress="return validateName(event)"
                                                                   value="{{ old('land_village') }}" required>
                                                            <div class="error" id="land_village_error"></div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label required">Taluka</label>
                                                            <input type="text" class="form-control" name="land_taluka"
                                                                   pattern="^[A-Za-z\s]+$" title="Only letters and spaces allowed"
                                                                   onkeypress="return validateName(event)"
                                                                   value="{{ old('land_taluka') }}" required>
                                                            <div class="error" id="land_taluka_error"></div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label class="form-label required">District</label>
                                                            <input type="text" class="form-control" name="land_district"
                                                                   pattern="^[A-Za-z\s]+$" title="Only letters and spaces allowed"
                                                                   onkeypress="return validateName(event)"
                                                                   value="{{ old('land_district') }}" required>
                                                            <div class="error" id="land_district_error"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>(A) Total Area to be purchase/lease of Land: (Sq. Metres)</td>
                                                <td>
                                                    <input type="number" class="form-control" name="area_a"
                                                           step="0.01" min="0" value="{{ old('area_a') }}" required>
                                                    <div class="error" id="area_a_error"></div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>(B) Total Area to be purchase/lease of Land & Built up area: (Sq. Metres)</td>
                                                <td>
                                                    <input type="number" class="form-control" name="area_b"
                                                           step="0.01" min="0" value="{{ old('area_b') }}" required>
                                                    <div class="error" id="area_b_error"></div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>(C) Out of (A) total area of land to be used for tourism project (Sq. Metres)</td>
                                                <td>
                                                    <input type="number" class="form-control" name="area_c"
                                                           step="0.01" min="0" value="{{ old('area_c') }}" required>
                                                    <div class="error" id="area_c_error"></div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>(D) Out of (A) actual land area required for ancillary activity (godown/office/lab etc.)(Sq. Metres)</td>
                                                <td>
                                                    <input type="number" class="form-control" name="area_d"
                                                           step="0.01" min="0" value="{{ old('area_d') }}" required>
                                                    <div class="error" id="area_d_error"></div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>(E) Total area of vacant land out of purchase / lease land (Sq. Metres)</td>
                                                <td>
                                                    <input type="number" class="form-control" name="area_e"
                                                           step="0.01" min="0" value="{{ old('area_e') }}" required>
                                                    <div class="error" id="area_e_error"></div>
                                                </td>
                                            </tr>

                                            <!-- NON AGRICULTURAL LAND -->
                                            <tr>
                                                <td>Details of Non-Agricultural Land</td>
                                                <td>
                                                    <div class="row g-3">
                                                        <div class="col-md-4">
                                                            <label class="form-label">CTS / Gat No.</label>
                                                            <input type="text" class="form-control" name="na_gat"
                                                                   value="{{ old('na_gat') }}">
                                                            <div class="error" id="na_gat_error"></div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Village</label>
                                                            <input type="text" class="form-control" name="na_village"
                                                                   pattern="^[A-Za-z\s]+$" title="Only letters and spaces allowed"
                                                                   onkeypress="return validateName(event)"
                                                                   value="{{ old('na_village') }}">
                                                            <div class="error" id="na_village_error"></div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Taluka</label>
                                                            <input type="text" class="form-control" name="na_taluka"
                                                                   pattern="^[A-Za-z\s]+$" title="Only letters and spaces allowed"
                                                                   onkeypress="return validateName(event)"
                                                                   value="{{ old('na_taluka') }}">
                                                            <div class="error" id="na_taluka_error"></div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label class="form-label">District</label>
                                                            <input type="text" class="form-control" name="na_district"
                                                                   pattern="^[A-Za-z\s]+$" title="Only letters and spaces allowed"
                                                                   onkeypress="return validateName(event)"
                                                                   value="{{ old('na_district') }}">
                                                            <div class="error" id="na_district_error"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Total Area of land to be converted to NA (Sq. Metres)</td>
                                                <td>
                                                    <input type="number" class="form-control" name="na_area"
                                                           step="0.01" min="0" value="{{ old('na_area') }}">
                                                    <div class="error" id="na_area_error"></div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Project Cost Details -->
                            <div class="container my-4">
                                <h4 class="mb-3">Project Cost (in Lakhs):</h4>

                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle">
                                        <thead>
                                            <tr>
                                                <th style="width: 50%;">Particulars</th>
                                                <th>Amount (₹ in Lakhs)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Land</td>
                                                <td>
                                                    <input type="number" class="cost-input" name="cost_land"
                                                           step="0.01" min="0" value="{{ old('cost_land', 0) }}" required>
                                                    <div class="error" id="cost_land_error"></div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Building</td>
                                                <td>
                                                    <input type="number" class="cost-input" name="cost_building"
                                                           step="0.01" min="0" value="{{ old('cost_building', 0) }}" required>
                                                    <div class="error" id="cost_building_error"></div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Plant & Machinery</td>
                                                <td>
                                                    <input type="number" class="cost-input" name="cost_machinery"
                                                           step="0.01" min="0" value="{{ old('cost_machinery', 0) }}" required>
                                                    <div class="error" id="cost_machinery_error"></div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Electrical Installations</td>
                                                <td>
                                                    <input type="number" class="cost-input" name="cost_electrical"
                                                           step="0.01" min="0" value="{{ old('cost_electrical', 0) }}" required>
                                                    <div class="error" id="cost_electrical_error"></div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Misc. Fixed Assets</td>
                                                <td>
                                                    <input type="number" class="cost-input" name="cost_misc"
                                                           step="0.01" min="0" value="{{ old('cost_misc', 0) }}" required>
                                                    <div class="error" id="cost_misc_error"></div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Other Expenses</td>
                                                <td>
                                                    <input type="number" class="cost-input" name="cost_other"
                                                           step="0.01" min="0" value="{{ old('cost_other', 0) }}" required>
                                                    <div class="error" id="cost_other_error"></div>
                                                </td>
                                            </tr>

                                            <tr class="table-secondary">
                                                <td class="fw-bold">Total</td>
                                                <td>
                                                    <input type="text" id="total_cost" class="total-field" readonly value="0.00">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="container my-4">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label required">* Proposed Employment Generation for this project.</label>
                                        <input type="number" class="form-control" name="project_employment"
                                               min="0" value="{{ old('project_employment') }}" required>
                                        <div class="error" id="project_employment_error"></div>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label required">The purpose for which NOC will be utilized:</label>
                                        <textarea class="form-control" name="noc_purpose" rows="2" required>{{ old('noc_purpose') }}</textarea>
                                        <div class="error" id="noc_purpose_error"></div>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label required">The name and address of authority to which this NOC will be submitted: (e.g. Sub Registrar)</label>
                                        <textarea class="form-control" name="noc_authority" rows="2" required>{{ old('noc_authority') }}</textarea>
                                        <div class="error" id="noc_authority_error"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Declaration Section -->
                            <div class="container my-4">
                                <div class="section-title mb-3">Declaration</div>

                                <div class="border p-3 rounded">
                                    <p>
                                        I/We hereby certify that the applicant has not been previously applied to Directorate of Tourism, Mumbai,
                                        or any other department in Government of Maharashtra or Central Government and on the basis of that has
                                        not availed any relief on payment of duty. Relief / Exemption from Stamp Duty & Registration fee have
                                        started under New Tourism Policy 2024. If it is proved that entity has not started their business and
                                        incentives are availed by them by supplying wrong information it will be my/our responsibility to return
                                        the incentives along with the interest and to inform concerned authority of granting of exemption of stamp duty.
                                    </p>

                                    <p>
                                        I/We hereby certify that, land required by us for the purpose of Tourism Project will be as per Government
                                        Rule for commencement of business.
                                    </p>

                                    <div class="row g-3 mt-3">
                                        <div class="col-md-6">
                                            <label class="form-label required">Name & Designation</label>
                                            <input type="text" class="form-control" name="name_designation"
                                                   pattern="^[A-Za-z\s\.]+$" title="Only letters, spaces and dots allowed"
                                                   onkeypress="return validateName(event)"
                                                   value="{{ old('name_designation') }}" required>
                                            <div class="error" id="name_designation_error"></div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label required">Upload Signature</label>
                                            <input type="file" class="form-control" id="signature" name="signature" accept="image/*,.pdf" required>
                                            <div class="preview-box mt-2" id="signaturePreview">
                                                <span class="text-muted small">Signature Preview</span>
                                            </div>
                                            <div class="error" id="signature_error"></div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Upload Rubber Stamp</label>
                                            <input type="file" class="form-control" id="stamp" name="stamp" accept="image/*,.pdf">
                                            <div class="preview-box mt-2" id="stampPreview">
                                                <span class="text-muted small">Stamp Preview</span>
                                            </div>
                                            <div class="error" id="stamp_error"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Document Upload Section -->
                            <div class="container my-4">
                                <h4 class="mb-3">Section C: List of Documents</h4>

                                <div class="border p-3 rounded">
                                    <p class="mb-2"><strong>Note:</strong> All documents should be self-attested. Maximum file size: 2MB each.</p>

                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle">
                                            <thead>
                                                <tr>
                                                    <th style="width: 5%; text-align: center;">Sr.</th>
                                                    <th style="width: 45%;">Document</th>
                                                    <th>Upload</th>
                                                    <th style="width: 15%;">Preview</th>
                                                    <th style="width: 10%;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center">1</td>
                                                    <td>Copy of challan for online processing fees of Rs.5,000/- paid on
                                                        <a href="https://www.gras.mahakosh.gov.in" target="_blank">www.gras.mahakosh.gov.in</a></td>
                                                    <td><input type="file" class="form-control doc-upload" name="doc_challan" accept="image/*,.pdf"></td>
                                                    <td><div class="preview-box doc-preview" style="width: 100%; height: 60px;"></div></td>
                                                    <td><button type="button" class="btn btn-sm btn-danger btn-remove" style="display:none;">Remove</button></td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">2</td>
                                                    <td>Affidavits (as per the specified format below)</td>
                                                    <td><input type="file" class="form-control doc-upload" name="doc_affidavit" accept="image/*,.pdf"></td>
                                                    <td><div class="preview-box doc-preview" style="width: 100%; height: 60px;"></div></td>
                                                    <td><button type="button" class="btn btn-sm btn-danger btn-remove" style="display:none;">Remove</button></td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">3</td>
                                                    <td>Registration proof for Company / Partnership firm / Co-op. Society etc.</td>
                                                    <td><input type="file" class="form-control doc-upload" name="doc_registration" accept="image/*,.pdf"></td>
                                                    <td><div class="preview-box doc-preview" style="width: 100%; height: 60px;"></div></td>
                                                    <td><button type="button" class="btn btn-sm btn-danger btn-remove" style="display:none;">Remove</button></td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">4</td>
                                                    <td>Records of Right (RoR)</td>
                                                    <td><input type="file" class="form-control doc-upload" name="doc_ror" accept="image/*,.pdf"></td>
                                                    <td><div class="preview-box doc-preview" style="width: 100%; height: 60px;"></div></td>
                                                    <td><button type="button" class="btn btn-sm btn-danger btn-remove" style="display:none;">Remove</button></td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">5</td>
                                                    <td>Map of the land</td>
                                                    <td><input type="file" class="form-control doc-upload" name="doc_land_map" accept="image/*,.pdf"></td>
                                                    <td><div class="preview-box doc-preview" style="width: 100%; height: 60px;"></div></td>
                                                    <td><button type="button" class="btn btn-sm btn-danger btn-remove" style="display:none;">Remove</button></td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">6</td>
                                                    <td>Detailed Project Report (DPR)</td>
                                                    <td><input type="file" class="form-control doc-upload" name="doc_dpr" accept="image/*,.pdf"></td>
                                                    <td><div class="preview-box doc-preview" style="width: 100%; height: 60px;"></div></td>
                                                    <td><button type="button" class="btn btn-sm btn-danger btn-remove" style="display:none;">Remove</button></td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">7</td>
                                                    <td>Certified true copy of Draft Agreement to Sale / Letter of Allotment from Government or any authority</td>
                                                    <td><input type="file" class="form-control doc-upload" name="doc_agreement" accept="image/*,.pdf"></td>
                                                    <td><div class="preview-box doc-preview" style="width: 100%; height: 60px;"></div></td>
                                                    <td><button type="button" class="btn btn-sm btn-danger btn-remove" style="display:none;">Remove</button></td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">8</td>
                                                    <td>Copy of proposed plan of constructions</td>
                                                    <td><input type="file" class="form-control doc-upload" name="doc_construction_plan" accept="image/*,.pdf"></td>
                                                    <td><div class="preview-box doc-preview" style="width: 100%; height: 60px;"></div></td>
                                                    <td><button type="button" class="btn btn-sm btn-danger btn-remove" style="display:none;">Remove</button></td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">9</td>
                                                    <td>D.P. remarks from Local Planning Authority / Zone Certificate</td>
                                                    <td><input type="file" class="form-control doc-upload" name="doc_dp_remarks" accept="image/*,.pdf"></td>
                                                    <td><div class="preview-box doc-preview" style="width: 100%; height: 60px;"></div></td>
                                                    <td><button type="button" class="btn btn-sm btn-danger btn-remove" style="display:none;">Remove</button></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Affidavit Section -->
                            <div class="container my-4">
                                <div class="section-title mb-3">Affidavit</div>

                                <div class="border p-3 rounded">
                                    <p class="fw-bold">(On stamp paper of INR 500/-)</p>
                                    <p class="fw-bold">(To obtain Certificate for Proposed Tourism Project under Stamp Duty Act 1958)</p>

                                    <p>
                                        I, Shri/Mrs
                                        <input type="text" class="form-control d-inline-block w-auto required"
                                               style="min-width: 200px;" name="aff_name"
                                               pattern="^[A-Za-z\s\.]+$" title="Only letters, spaces and dots allowed"
                                               onkeypress="return validateName(event)"
                                               value="{{ old('aff_name') }}" required>
                                        , Director of M/s.
                                        <input type="text" class="form-control d-inline-block w-auto required"
                                               style="min-width: 200px;" name="aff_company"
                                               value="{{ old('aff_company') }}" required>
                                        , a Firm/company incorporated under LLP Act / Partnership Act / Companies Act of 1956/2013,
                                        having its registered office at
                                        <input type="text" class="form-control mt-2 required" name="aff_registered_office"
                                               value="{{ old('aff_registered_office') }}" required>
                                        , solemnly declare on oath that I have submitted an application along with relevant documents
                                        under Tourism Policy 2024 started by Government of Maharashtra to promote tourism in the state.
                                    </p>

                                    <p>
                                        I further state and undertake that as provided in section 14.4 of the Tourism Policy 2024, I am
                                        willing to take all the initial effective steps to become eligible for registration as a Tourism
                                        Unit and as a part of this I am applying for a certificate from Directorate of Tourism to enable me
                                        to claim exemption from payment of stamp duty on registration of deed of conveyance in respect of
                                        adjacent piece of land admeasuring
                                        <input type="number" class="form-control d-inline-block w-auto required"
                                               style="min-width: 100px;" name="aff_land_area" step="0.01" min="0"
                                               value="{{ old('aff_land_area') }}" required>
                                        sq. meters,

                                        bearing C.T.S. / Gat No.
                                        <input type="text" class="form-control d-inline-block w-auto required"
                                               style="min-width: 120px;" name="aff_cts"
                                               value="{{ old('aff_cts') }}" required>
                                        , Village -
                                        <input type="text" class="form-control d-inline-block w-auto required"
                                               style="min-width: 150px;" name="aff_village"
                                               pattern="^[A-Za-z\s]+$" title="Only letters and spaces allowed"
                                               onkeypress="return validateName(event)"
                                               value="{{ old('aff_village') }}" required>
                                        , Taluka -
                                        <input type="text" class="form-control d-inline-block w-auto required"
                                               style="min-width: 150px;" name="aff_taluka"
                                               pattern="^[A-Za-z\s]+$" title="Only letters and spaces allowed"
                                               onkeypress="return validateName(event)"
                                               value="{{ old('aff_taluka') }}" required>
                                        , District -
                                        <input type="text" class="form-control d-inline-block w-auto required"
                                               style="min-width: 150px;" name="aff_district"
                                               pattern="^[A-Za-z\s]+$" title="Only letters and spaces allowed"
                                               onkeypress="return validateName(event)"
                                               value="{{ old('aff_district') }}" required>
                                        .
                                    </p>

                                    <p>
                                        I also state on oath that I shall complete the proposed tourism project in respect of the aforesaid
                                        land within the stipulated period of three years, failing which the Certificate to be issued by the
                                        Directorate of Tourism regarding entitlement for exemption of stamp duty shall automatically stand cancelled.
                                    </p>

                                    <p>
                                        I also state on oath that in the event of exemption to pay stamp duty being granted I shall abide by
                                        the terms and conditions laid down by the Government of Maharashtra in the Notification dated
                                        15/10/2024 issued by the Revenue & Forest Department and I also undertake that the land so purchased
                                        shall be used for developing the said "Tourism Project" and I am fully aware that failure to do so
                                        shall entail action by the State Government or any other competent authority which may include refund
                                        of the amount exempted and fines etc.
                                    </p>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-center mt-4 mb-5">
                                <button type="button" id="previewBtn" class="btn btn-warning px-5 py-2 me-3">
                                    <i class="bi bi-eye me-2"></i>Preview Application
                                </button>
                                <button type="submit" id="submitBtn" class="btn btn-primary px-5 py-2">
                                    <i class="bi bi-save me-2"></i>Save Draft
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Application Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="previewContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="confirmSubmitBtn" class="btn btn-primary">Submit Application</button>
            </div>
        </div>
    </div>
</div>

<!-- Declaration Modal -->
<div class="modal fade" id="declarationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Final Declaration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="declarationCheck">
                    <label class="form-check-label" for="declarationCheck">
                        I hereby declare that all the information provided in this application is true and correct to the best of my knowledge.
                        I understand that any false information may lead to rejection of my application and legal action.
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="finalSubmitBtn" class="btn btn-primary" disabled>Confirm & Submit</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Validation functions
function validateName(event) {
    var char = String.fromCharCode(event.which);
    var pattern = /^[A-Za-z\s]$/;
    if (!pattern.test(char)) {
        event.preventDefault();
        return false;
    }
    return true;
}

function validatePhoneInput(event) {
    var char = String.fromCharCode(event.which);
    var pattern = /^[0-9]$/;
    if (!pattern.test(char)) {
        event.preventDefault();
        return false;
    }
    return true;
}

function validateNumeric(event) {
    var char = String.fromCharCode(event.which);
    var pattern = /^[0-9]$/;
    if (!pattern.test(char)) {
        event.preventDefault();
        return false;
    }
    return true;
}



$(document).ready(function() {
    // Show/hide eligibility section
    $('#existedBefore').change(function() {
        if ($(this).val() == '1') {
            $('#eligibilitySection').show();
            $('[name="eligibility_cert_no"], [name="eligibility_date"], [name="present_status"]').attr('required', true);
        } else {
            $('#eligibilitySection').hide();
            $('[name="eligibility_cert_no"], [name="eligibility_date"], [name="present_status"]').attr('required', false);
        }
    }).trigger('change');

    // Calculate total cost
    function calculateTotal() {
        let total = 0;
        $('.cost-input').each(function() {
            let value = parseFloat($(this).val()) || 0;
            total += value;
        });
        $('#total_cost').val(total.toFixed(2));
    }

    $('.cost-input').on('input', calculateTotal);
    calculateTotal(); // Initial calculation

    // File preview for signature and stamp
    $('#signature').change(function(event) {
        previewFile(this, '#signaturePreview');
    });

    $('#stamp').change(function(event) {
        previewFile(this, '#stampPreview');
    });

    function previewFile(input, previewSelector) {
        const file = input.files[0];
        const preview = $(previewSelector);

        if (file) {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.html(`<img src="${e.target.result}" class="img-fluid">`);
                };
                reader.readAsDataURL(file);
            } else if (file.type === 'application/pdf') {
                preview.html(`<div class="text-danger"><i class="bi bi-file-earmark-pdf"></i> PDF File</div>`);
            }
        }
    }

    // Document upload preview
    $(document).on('change', '.doc-upload', function() {
        const file = this.files[0];
        const row = $(this).closest('tr');
        const preview = row.find('.doc-preview');
        const removeBtn = row.find('.btn-remove');

        if (file) {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.html(`<img src="${e.target.result}" style="max-width: 100%; max-height: 100%;">`);
                };
                reader.readAsDataURL(file);
            } else if (file.type === 'application/pdf') {
                preview.html(`<div class="text-danger d-flex align-items-center justify-content-center h-100">
                    <i class="bi bi-file-earmark-pdf fs-4"></i>
                </div>`);
            }
            removeBtn.show();
        }
    });

    // Remove document
    $(document).on('click', '.btn-remove', function() {
        const row = $(this).closest('tr');
        row.find('.doc-upload').val('');
        row.find('.doc-preview').html('');
        $(this).hide();
    });

    // jQuery Validation
    const validator = $('#stampDutyForm').validate({
        ignore: [],
        errorClass: 'is-invalid',
        validClass: 'is-valid',
        errorElement: 'div',
        errorPlacement: function(error, element) {
            const errorId = element.attr('name') + '_error';
            $('#' + errorId).html(error.text());
        },
        success: function(label, element) {
            const errorId = $(element).attr('name') + '_error';
            $('#' + errorId).html('');
            $(element).removeClass('is-invalid').addClass('is-valid');
        },
        highlight: function(element) {
            $(element).addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid').addClass('is-valid');
        },
        rules: {
            company_name: {
                required: true,
                pattern: /^[A-Za-z0-9\s\.,&'-]+$/
            },
            registration_no: 'required',
            application_date: 'required',
            applicant_type: 'required',
            agreement_type: 'required',
            c_address: 'required',
            c_city: {
                required: true,
                pattern: /^[A-Za-z\s]+$/
            },
            c_taluka: {
                required: true,
                pattern: /^[A-Za-z\s]+$/
            },
            c_district: {
                required: true,
                pattern: /^[A-Za-z\s]+$/
            },
            c_state: {
                required: true,
                pattern: /^[A-Za-z\s]+$/
            },
            c_pincode: {
                required: true,
                pattern: /^[1-9][0-9]{5}$/
            },
            c_mobile: {
                required: true,
                pattern: /^[6-9][0-9]{9}$/
            },
            c_email: {
                required: true,
                email: true
            },
            p_address: 'required',
            p_city: {
                required: true,
                pattern: /^[A-Za-z\s]+$/
            },
            p_taluka: {
                required: true,
                pattern: /^[A-Za-z\s]+$/
            },
            p_district: {
                required: true,
                pattern: /^[A-Za-z\s]+$/
            },
            p_state: {
                required: true,
                pattern: /^[A-Za-z\s]+$/
            },
            p_pincode: {
                required: true,
                pattern: /^[1-9][0-9]{5}$/
            },
            p_mobile: {
                required: true,
                pattern: /^[6-9][0-9]{9}$/
            },
            p_email: {
                required: true,
                email: true
            },
            estimated_project_cost: {
                required: true,
                number: true,
                min: 0
            },
            proposed_employment: {
                required: true,
                digits: true,
                min: 0
            },
            tourism_activities: 'required',
            land_gat: 'required',
            land_village: {
                required: true,
                pattern: /^[A-Za-z\s]+$/
            },
            land_taluka: {
                required: true,
                pattern: /^[A-Za-z\s]+$/
            },
            land_district: {
                required: true,
                pattern: /^[A-Za-z\s]+$/
            },
            area_a: {
                required: true,
                number: true,
                min: 0
            },
            area_b: {
                required: true,
                number: true,
                min: 0
            },
            area_c: {
                required: true,
                number: true,
                min: 0
            },
            area_d: {
                required: true,
                number: true,
                min: 0
            },
            area_e: {
                required: true,
                number: true,
                min: 0
            },
            cost_land: {
                required: true,
                number: true,
                min: 0
            },
            cost_building: {
                required: true,
                number: true,
                min: 0
            },
            cost_machinery: {
                required: true,
                number: true,
                min: 0
            },
            cost_electrical: {
                required: true,
                number: true,
                min: 0
            },
            cost_misc: {
                required: true,
                number: true,
                min: 0
            },
            cost_other: {
                required: true,
                number: true,
                min: 0
            },
            project_employment: {
                required: true,
                digits: true,
                min: 0
            },
            noc_purpose: 'required',
            noc_authority: 'required',
            name_designation: {
                required: true,
                pattern: /^[A-Za-z\s\.]+$/
            },
            signature: 'required',
            aff_name: {
                required: true,
                pattern: /^[A-Za-z\s\.]+$/
            },
            aff_company: 'required',
            aff_registered_office: 'required',
            aff_land_area: {
                required: true,
                number: true,
                min: 0
            },
            aff_cts: 'required',
            aff_village: {
                required: true,
                pattern: /^[A-Za-z\s]+$/
            },
            aff_taluka: {
                required: true,
                pattern: /^[A-Za-z\s]+$/
            },
            aff_district: {
                required: true,
                pattern: /^[A-Za-z\s]+$/
            },
            region_id: 'required',
            district_id: 'required'
        },
        messages: {
            company_name: {
                required: "Please enter company name",
                pattern: "Enter valid company name (letters, numbers, spaces, .,&,-)"
            },
            c_city: {
                pattern: "Only letters and spaces allowed"
            },
            c_taluka: {
                pattern: "Only letters and spaces allowed"
            },
            c_district: {
                pattern: "Only letters and spaces allowed"
            },
            c_state: {
                pattern: "Only letters and spaces allowed"
            },
            c_pincode: {
                pattern: "Enter 6-digit pin code"
            },
            c_mobile: {
                pattern: "Enter valid 10-digit mobile number starting with 6-9"
            },
            p_city: {
                pattern: "Only letters and spaces allowed"
            },
            p_taluka: {
                pattern: "Only letters and spaces allowed"
            },
            p_district: {
                pattern: "Only letters and spaces allowed"
            },
            p_state: {
                pattern: "Only letters and spaces allowed"
            },
            p_pincode: {
                pattern: "Enter 6-digit pin code"
            },
            p_mobile: {
                pattern: "Enter valid 10-digit mobile number starting with 6-9"
            },
            land_village: {
                pattern: "Only letters and spaces allowed"
            },
            land_taluka: {
                pattern: "Only letters and spaces allowed"
            },
            land_district: {
                pattern: "Only letters and spaces allowed"
            },
            name_designation: {
                pattern: "Only letters, spaces and dots allowed"
            },
            aff_name: {
                pattern: "Only letters, spaces and dots allowed"
            },
            aff_village: {
                pattern: "Only letters and spaces allowed"
            },
            aff_taluka: {
                pattern: "Only letters and spaces allowed"
            },
            aff_district: {
                pattern: "Only letters and spaces allowed"
            }
        },
        submitHandler: function(form) {
            saveAsDraft(form);
        }
    });

    // Save as draft
    function saveAsDraft(form) {
        const formData = new FormData(form);

        $('#submitBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');

        $.ajax({
            url: $(form).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Saved!',
                        text: response.message,
                        confirmButtonColor: '#ff6600',
                    }).then(() => {
                        window.location.href = response.redirect_url;
                    });
                }
            },
            error: function(xhr) {
                $('#submitBtn').prop('disabled', false).html('<i class="bi bi-save me-2"></i>Save Draft');

                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('#' + key + '_error').html(value[0]);
                        $('[name="' + key + '"]').addClass('is-invalid');
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please correct the highlighted fields.',
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred. Please try again.',
                    });
                }
            }
        });
    }

    // Preview button click
    $('#previewBtn').click(function() {
        if (!$('#stampDutyForm').valid()) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please fill all required fields correctly before preview.',
            });
            return;
        }

        // Collect form data for preview
        const formData = new FormData($('#stampDutyForm')[0]);

        $.ajax({
            url: '{{ route("stamp-duty.preview.ajax") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#previewContent').html(response);
                $('#previewModal').modal('show');
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to generate preview.',
                });
            }
        });
    });

    // Confirm submit button in preview modal
    $('#confirmSubmitBtn').click(function() {
        $('#previewModal').modal('hide');
        $('#declarationModal').modal('show');
    });

    // Enable/disable final submit based on checkbox
    $('#declarationCheck').change(function() {
        $('#finalSubmitBtn').prop('disabled', !$(this).is(':checked'));
    });

    // Final submit
    $('#finalSubmitBtn').click(function() {
        const formData = new FormData($('#stampDutyForm')[0]);

        $('#finalSubmitBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Submitting...');

        $.ajax({
            url: '{{ route("stamp-duty.submit.ajax") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status === 'success') {
                    $('#declarationModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Submitted!',
                        text: response.message,
                        confirmButtonColor: '#ff6600',
                    }).then(() => {
                        window.location.href = response.redirect_url;
                    });
                }
            },
            error: function(xhr) {
                $('#finalSubmitBtn').prop('disabled', false).html('Confirm & Submit');

                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('#' + key + '_error').html(value[0]);
                        $('[name="' + key + '"]').addClass('is-invalid');
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please correct the highlighted fields.',
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred. Please try again.',
                    });
                }
            }
        });
    });
});
</script>
@endpush
