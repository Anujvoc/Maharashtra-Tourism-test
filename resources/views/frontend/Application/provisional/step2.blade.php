{{-- resources/views/frontend/Application/provisional/step2.blade.php --}}

@extends('frontend.layouts2.master')

@section('title', 'Step 2: Project Details')

@push('styles')

<style>
    .form-icon {
        color: var(--brand, #ff6600);
        font-size: 1.1rem;
        margin-right: .35rem;
    }
    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }
    .step {
        text-align: center;
        flex: 1;
    }
    .step-label {
        font-size: 0.9rem;
        font-weight: 600;
    }
    .table input {
        min-width: 100px;
    }
</style>
@endpush

@push('styles')
<style>
    table th {
        width: 30%;
        font-weight: 600;
        background-color: #f8f9fa;
        vertical-align: middle !important;
    }
    table td {
        background-color: #fff;
    }
    .table-borderless tr.border-bottom td,
    .table-borderless tr.border-bottom th {
        border-bottom: 1px solid #dee2e6 !important;
    }
</style>
@endpush


@section('content')
<section class="section">
    <div class="section-header form-header">
        <h1 class="fw-bold">Application Form for the {{ $application_form->name ?? '' }}</h1>
    </div>

    {{-- Stepper / Progress --}}
    @include('frontend.Application.provisional._stepper', ['step' => $step])

    {{-- MAIN CARD --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header"
             style="background:#ff6600;
                    color:#fff;
                    padding:.75rem 1rem;
                    font-weight:700;
                    display:flex;
                    align-items:center;
                    gap:.5rem;">
            <i class="bi bi-geo-alt form-icon"></i>
            <span>Step 2: Project Details</span>
        </div>

        <div class="card-body">
            <form id="stepForm"
                  action="{{ route('provisional.wizard.save', [$application, $step]) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  novalidate>
                @csrf

                @php
                    $siteAddress = $registration->site_address ?? [];
                @endphp

                {{-- 1. Site Address --}}
                <h6 class="mb-3 fw-semibold">
                    <i class="bi bi-pin-map form-icon"></i> 1. Site Address
                </h6>
                {{-- ===========================
     Site Address (Table Form)
===========================
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-light fw-bold text-dark">
        <i class="bi bi-geo-alt text-primary me-1"></i> 1. Site Address
    </div>

    <div class="card-body p-0">
        <table class="table table-borderless align-middle mb-0">
            <tbody style="vertical-align: middle;">

                <tr class="border-bottom">
                    <th style="width: 30%;" class="py-3 ps-4">
                        Survey No. / CTS No. / Gat No. <span class="text-danger">*</span>
                    </th>
                    <td class="py-3 pe-4">
                        <div class="d-flex gap-2">
                            <select class="form-select w-auto @error('survey_type') is-invalid @enderror"
                                    name="survey_type" required>
                                <option value="">Select Type</option>
                                <option value="Survey No." {{ old('survey_type', $siteAddress['survey_type'] ?? '') == 'Survey No.' ? 'selected' : '' }}>Survey No.</option>
                                <option value="CTS No."    {{ old('survey_type', $siteAddress['survey_type'] ?? '') == 'CTS No.'    ? 'selected' : '' }}>CTS No.</option>
                                <option value="Gat No."    {{ old('survey_type', $siteAddress['survey_type'] ?? '') == 'Gat No.'    ? 'selected' : '' }}>Gat No.</option>
                            </select>

                            <input type="text"
                                   class="form-control flex-fill @error('survey_number') is-invalid @enderror"
                                   name="survey_number"
                                   placeholder="Enter Number"
                                   required
                                   value="{{ old('survey_number', $siteAddress['survey_number'] ?? '') }}">
                        </div>
                        @error('survey_type') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        @error('survey_number') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </td>
                </tr>


                <tr class="border-bottom">
                    <th class="py-3 ps-4">Village / City <span class="text-danger">*</span></th>
                    <td class="py-3 pe-4">
                        <input type="text"
                               class="form-control @error('village_city') is-invalid @enderror"
                               name="village_city"
                               required
                               value="{{ old('village_city', $siteAddress['village_city'] ?? '') }}">
                        @error('village_city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </td>
                </tr>


                <tr class="border-bottom">
                    <th class="py-3 ps-4">Taluka <span class="text-danger">*</span></th>
                    <td class="py-3 pe-4">
                        <input type="text"
                               class="form-control @error('taluka') is-invalid @enderror"
                               name="taluka"
                               required
                               value="{{ old('taluka', $siteAddress['taluka'] ?? '') }}">
                        @error('taluka') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </td>
                </tr>


                <tr class="border-bottom">
                    <th class="py-3 ps-4">District <span class="text-danger">*</span></th>
                    <td class="py-3 pe-4">
                        <select name="district"
                                class="form-select @error('district') is-invalid @enderror"
                                required>
                            <option value="">Select District</option>
                            @foreach($districts as $district)
                                <option value="{{ $district->name }}"
                                    {{ old('district', $siteAddress['district'] ?? '') == $district->name ? 'selected' : '' }}>
                                    {{ $district->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('district') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </td>
                </tr>

                <tr class="border-bottom">
                    <th class="py-3 ps-4">State <span class="text-danger">*</span></th>
                    <td class="py-3 pe-4">
                        <select name="state"
                                class="form-select @error('state') is-invalid @enderror"
                                required>
                            <option value="">Select State</option>
                            @foreach($states as $state)
                                <option value="{{ $state->name }}"
                                    {{ old('state', $siteAddress['state'] ?? '') == $state->name ? 'selected' : '' }}>
                                    {{ $state->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('state') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </td>
                </tr>


                <tr class="border-bottom">
                    <th class="py-3 ps-4">Pincode <span class="text-danger">*</span></th>
                    <td class="py-3 pe-4">
                        <input type="text"
                               class="form-control @error('pincode') is-invalid @enderror"
                               name="pincode"
                               maxlength="6"
                               inputmode="numeric"
                               required
                               value="{{ old('pincode', $siteAddress['pincode'] ?? '') }}">
                        @error('pincode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </td>
                </tr>


                <tr class="border-bottom">
                    <th class="py-3 ps-4">Mobile Number <span class="text-danger">*</span></th>
                    <td class="py-3 pe-4">
                        <input type="text"
                               class="form-control @error('mobile') is-invalid @enderror"
                               name="mobile"
                               maxlength="10"
                               inputmode="numeric"
                               pattern="^[6-9][0-9]{9}$"
                               required
                               value="{{ old('mobile', $siteAddress['mobile'] ?? '') }}">
                        <div class="form-text">Must be 10 digits and start with 6–9.</div>
                        @error('mobile') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </td>
                </tr>

                <tr class="border-bottom">
                    <th class="py-3 ps-4">Email <span class="text-danger">*</span></th>
                    <td class="py-3 pe-4">
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               name="email"
                               required
                               value="{{ old('email', $siteAddress['email'] ?? '') }}">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </td>
                </tr>


                <tr>
                    <th class="py-3 ps-4">Website</th>
                    <td class="py-3 pe-4">
                        <input type="url"
                               class="form-control @error('website') is-invalid @enderror"
                               name="website"
                               placeholder="https://example.com"
                               value="{{ old('website', $siteAddress['website'] ?? '') }}">
                        @error('website') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</div>
--}}
                <div class="row g-3 mb-4">
                    {{-- Survey Type + Number --}}
                    <div class="col-md-6">
                        <label class="form-label">
                            Survey No. / CTS No. / Gat No. <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <select class="form-control @error('survey_type') is-invalid @enderror"
                                    name="survey_type" required>
                                <option value="">Select Type</option>
                                <option value="Survey No." {{ old('survey_type', $siteAddress['survey_type'] ?? '') == 'Survey No.' ? 'selected' : '' }}>Survey No.</option>
                                <option value="CTS No."    {{ old('survey_type', $siteAddress['survey_type'] ?? '') == 'CTS No.'    ? 'selected' : '' }}>CTS No.</option>
                                <option value="Gat No."    {{ old('survey_type', $siteAddress['survey_type'] ?? '') == 'Gat No.'    ? 'selected' : '' }}>Gat No.</option>
                            </select>
                            <input type="text"
                                   class="form-control @error('survey_number') is-invalid @enderror"
                                   name="survey_number"
                                   placeholder="Enter Number"
                                   required
                                   value="{{ old('survey_number', $siteAddress['survey_number'] ?? '') }}">
                        </div>
                        @error('survey_type')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @error('survey_number')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Village / City --}}
                    <div class="col-md-4">
                        <label class="form-label">Village / City <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('village_city') is-invalid @enderror"
                               name="village_city"
                               required title="Only letters and spaces are allowed"  onkeypress="return validateName(event)"
                               value="{{ old('village_city', $siteAddress['village_city'] ?? '') }}">
                        @error('village_city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Taluka --}}
                    <div class="col-md-4">
                        <label class="form-label">Taluka <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('taluka') is-invalid @enderror"
                               name="taluka"
                               required title="Only letters and spaces are allowed"  onkeypress="return validateName(event)"
                               value="{{ old('taluka', $siteAddress['taluka'] ?? '') }}">
                        @error('taluka')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">District <span class="text-danger">*</span></label>
                        <select name="district"
                                class="form-control @error('district') is-invalid @enderror"
                                required>
                            <option value="">Select District</option>
                            @foreach($districts as $district)
                                <option value="{{ $district->name }}"
                                    {{ old('district', $siteAddress['district'] ?? '') == $district->name ? 'selected' : '' }}>
                                    {{ $district->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('district')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- State - using $states --}}
                    <div class="col-md-4">
                        <label class="form-label">State <span class="text-danger">*</span></label>
                        <select name="state"
                                class="form-control @error('state') is-invalid @enderror"
                                required>
                            <option value="">Select State</option>
                            @foreach($states as $state)
                                <option value="{{ $state->name }}"
                                    {{ old('state', $siteAddress['state'] ?? '') == $state->name ? 'selected' : '' }}>
                                    {{ $state->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('state')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Pincode --}}
                    <div class="col-md-4">
                        <label class="form-label">Pincode <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('pincode') is-invalid @enderror"
                               name="pincode"
                               maxlength="6"
                               inputmode="numeric"
                               required
                               value="{{ old('pincode', $siteAddress['pincode'] ?? '') }}">
                        @error('pincode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Mobile --}}
                    <div class="col-md-4">
                        <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('mobile') is-invalid @enderror"
                               name="mobile"
                               maxlength="10"
                               inputmode="numeric"
                               pattern="^[6-9][0-9]{9}$"
                               required
                               value="{{ old('mobile', $siteAddress['mobile'] ?? '') }}">
                        <div class="form-text text-danger">Must be 10 digits and start with 6, 7, 8 or 9.</div>
                        @error('mobile')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               name="email"
                               required
                               value="{{ old('email', $siteAddress['email'] ?? '') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Website --}}
                    <div class="col-md-6">
                        <label class="form-label">Website</label>
                        <input type="url"
                               class="form-control @error('website') is-invalid @enderror"
                               name="website"
                               placeholder="https://example.com"
                               value="{{ old('website', $siteAddress['website'] ?? '') }}">
                        @error('website')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- 2 & 3. Udyog Aadhar / GST --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">
                            2. Udyog Aadhar Number <small class="text-muted">(If applicable)</small>
                        </label>
                        <input type="text"
                               class="form-control @error('udyog_aadhar') is-invalid @enderror"
                               name="udyog_aadhar"
                               value="{{ old('udyog_aadhar', $registration->udyog_aadhar) }}">
                        @error('udyog_aadhar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            3. GST Registration Number <small class="text-muted">(If available)</small>
                        </label>
                        <input type="text"
                               class="form-control @error('gst_number') is-invalid @enderror"
                               name="gst_number"
                               value="{{ old('gst_number', $registration->gst_number) }}">
                        @error('gst_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- 4. Zone --}}
                <div class="row mb-4">
                    <div class="col-12">
                        <label class="form-label d-block">
                            4. Zone as per MTP 2024 <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex flex-wrap gap-3">
                            @php $zones = ['A', 'B', 'C', 'STZ/STD', 'Entire State']; @endphp
                            @foreach($zones as $zone)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input @error('zone') is-invalid @enderror"
                                           type="radio"
                                           name="zone"
                                           id="zone{{ \Str::slug($zone, '') }}"
                                           value="{{ $zone }}"
                                           {{ old('zone', $registration->zone) == $zone ? 'checked' : '' }}>
                                    <label class="form-check-label" for="zone{{ \Str::slug($zone, '') }}">{{ $zone }}</label>
                                </div>
                            @endforeach
                        </div>
                        @error('zone')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- 5. Project Type --}}
                <div class="row mb-4">
                    <div class="col-12">
                        <label class="form-label d-block">
                            5. Type of Project <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex flex-wrap gap-3 mb-2">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('project_type') is-invalid @enderror"
                                       type="radio"
                                       name="project_type"
                                       id="projectNew"
                                       value="New"
                                       {{ old('project_type', $registration->project_type) == 'New' ? 'checked' : '' }}>
                                <label class="form-check-label" for="projectNew">New</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('project_type') is-invalid @enderror"
                                       type="radio"
                                       name="project_type"
                                       id="projectExpansion"
                                       value="Expansion"
                                       {{ old('project_type', $registration->project_type) == 'Expansion' ? 'checked' : '' }}>
                                <label class="form-check-label" for="projectExpansion">Expansion</label>
                            </div>
                        </div>
                        @error('project_type')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        {{-- Expansion Details --}}
                        <div id="expansionDetails"
                             class="mt-3"
                             style="display: {{ old('project_type', $registration->project_type) == 'Expansion' ? 'block' : 'none' }};">

                            <label class="form-label">
                                Eligibility Certificate Number <small class="text-muted">(If Applicable)</small>
                            </label>
                            <input type="text"
                                   class="form-control mb-3 @error('eligibility_certificate') is-invalid @enderror"
                                   name="eligibility_certificate"
                                   placeholder="Enter Eligibility Certificate Number"
                                   value="{{ old('eligibility_certificate', $registration->eligibility_certificate) }}">
                            @error('eligibility_certificate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <label class="form-label">Expansion Details</label>
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle" id="expansionTable">
                                    <thead class="table-secondary">
                                        <tr>
                                            <th rowspan="2">#</th>
                                            <th colspan="2">Existing Capacity</th>
                                            <th colspan="2">Expansion Details</th>
                                            <th rowspan="2">Action</th>
                                        </tr>
                                        <tr>
                                            <th>Facilities</th>
                                            <th>Employment</th>
                                            <th>Facilities</th>
                                            <th>Employment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $expansionData = $registration->expansion_details
                                                ?? [['existing_facilities' => '', 'existing_employment' => '', 'expansion_facilities' => '', 'expansion_employment' => '']];
                                        @endphp
                                        @foreach($expansionData as $index => $row)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <input type="text"
                                                           class="form-control @error('existing_facilities.' . $index) is-invalid @enderror"
                                                           name="existing_facilities[]"
                                                           value="{{ old('existing_facilities.' . $index, $row['existing_facilities'] ?? '') }}">
                                                    @error('existing_facilities.' . $index)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="number"
                                                           class="form-control @error('existing_employment.' . $index) is-invalid @enderror"
                                                           name="existing_employment[]"
                                                           value="{{ old('existing_employment.' . $index, $row['existing_employment'] ?? '') }}">
                                                    @error('existing_employment.' . $index)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="text"
                                                           class="form-control @error('expansion_facilities.' . $index) is-invalid @enderror"
                                                           name="expansion_facilities[]"
                                                           value="{{ old('expansion_facilities.' . $index, $row['expansion_facilities'] ?? '') }}">
                                                    @error('expansion_facilities.' . $index)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="number"
                                                           class="form-control @error('expansion_employment.' . $index) is-invalid @enderror"
                                                           name="expansion_employment[]"
                                                           value="{{ old('expansion_employment.' . $index, $row['expansion_employment'] ?? '') }}">
                                                    @error('expansion_employment.' . $index)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <button type="button" class="btn btn-primary btn-sm addExpansionRow">
                                                            <i class="bi bi-plus-circle"></i>
                                                        </button>
                                                        @if($index > 0)
                                                            <button type="button" class="btn btn-danger btn-sm removeExpansionRow">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 6. Entrepreneurs Profile --}}
                <div class="row mb-4">
                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            6. Entrepreneurs Profile (Of All Partner/Directors of the Organization)
                        </label>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="entrepreneurTable">
                                <thead class="table-primary">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Designation</th>
                                        <th>Ownership %</th>
                                        <th>Gender</th>
                                        <th>Age</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $entrepreneurs = $registration->entrepreneurs_profile
                                            ?? [['name' => '', 'designation' => '', 'ownership' => '', 'gender' => '', 'age' => '']];
                                    @endphp
                                    @foreach($entrepreneurs as $index => $entrepreneur)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <input type="text"
                                                       class="form-control @error('entre_name.' . $index) is-invalid @enderror"
                                                       name="entre_name[]"
                                                       placeholder="Enter Name"
                                                       value="{{ old('entre_name.' . $index, $entrepreneur['name'] ?? '') }}">
                                                @error('entre_name.' . $index)
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="text"
                                                       class="form-control @error('entre_designation.' . $index) is-invalid @enderror"
                                                       name="entre_designation[]"
                                                       placeholder="Enter Designation"
                                                       value="{{ old('entre_designation.' . $index, $entrepreneur['designation'] ?? '') }}">
                                                @error('entre_designation.' . $index)
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="number"
                                                       class="form-control @error('entre_ownership.' . $index) is-invalid @enderror"
                                                       name="entre_ownership[]"
                                                       placeholder="%"
                                                       value="{{ old('entre_ownership.' . $index, $entrepreneur['ownership'] ?? '') }}">
                                                @error('entre_ownership.' . $index)
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td >
                                                <select class="form-control @error('entre_gender.' . $index) is-invalid @enderror"
                                                        name="entre_gender[]" style="width: 120px">
                                                    <option value="" selected disabled>Select</option>
                                                    <option value="Male"   {{ old('entre_gender.' . $index, $entrepreneur['gender'] ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                                                    <option value="Female" {{ old('entre_gender.' . $index, $entrepreneur['gender'] ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                                                    <option value="Other"  {{ old('entre_gender.' . $index, $entrepreneur['gender'] ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                                @error('entre_gender.' . $index)
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="number"
                                                       class="form-control @error('entre_age.' . $index) is-invalid @enderror"
                                                       name="entre_age[]"
                                                       placeholder="Enter Age"
                                                       value="{{ old('entre_age.' . $index, $entrepreneur['age'] ?? '') }}">
                                                @error('entre_age.' . $index)
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <button type="button" class="btn btn-primary btn-sm addEntreRow">
                                                        <i class="bi bi-plus-circle"></i>
                                                    </button>
                                                    @if($index > 0)
                                                        <button type="button" class="btn btn-danger btn-sm removeEntreRow">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <p class="text-muted mt-2 mb-0">
                            *If more profiles, kindly provide the details on a blank page in the same table format.
                        </p>
                    </div>
                </div>

                {{-- 7. Project Category --}}
                <div class="row mb-4">
                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            7. Project Category: <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex flex-wrap gap-3 mt-2">
                            @php
                                $projectCategories = [
                                    'Accommodation A'          => 'categoryA',
                                    'Accommodation B'          => 'categoryB',
                                    'F&B'                      => 'categoryFB',
                                    'Travel and Tourism'       => 'categoryTravel',
                                    'Entertainment and Recreation' => 'categoryEntertainment',
                                    'Others'                   => 'categoryOthers'
                                ];
                            @endphp
                            @foreach($projectCategories as $category => $id)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input @error('project_category') is-invalid @enderror"
                                           type="radio"
                                           name="project_category"
                                           id="{{ $id }}"
                                           value="{{ $category }}"
                                           {{ old('project_category', $registration->project_category) == $category ? 'checked' : '' }}>
                                    <label class="form-check-label" for="{{ $id }}">
                                        {{ $category }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('project_category')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        {{-- Other Category Field --}}
                        <div id="otherCategoryField"
                             class="mt-3"
                             style="display: {{ old('project_category', $registration->project_category) == 'Others' ? 'block' : 'none' }};">
                            <label class="form-label">Please specify other category:</label>
                            <input type="text"
                                   class="form-control @error('other_category') is-invalid @enderror"
                                   name="other_category"
                                   placeholder="Enter category name"
                                   value="{{ old('other_category', $registration->other_category) }}">
                            @error('other_category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- 8. Project Subcategory --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            8. Project Subcategory: <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('project_subcategory') is-invalid @enderror"
                               name="project_subcategory"
                               placeholder="Enter project subcategory"
                               required
                               value="{{ old('project_subcategory', $registration->project_subcategory) }}">
                        @error('project_subcategory')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- 9. Project Description --}}
                <div class="row mb-4">
                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            9. Project Description: <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control @error('project_description') is-invalid @enderror"
                                  name="project_description"
                                  rows="4"
                                  required
                                  placeholder="Enter detailed description of the project including purpose, objectives, and scope...">{{ old('project_description', $registration->project_description) }}</textarea>
                        @error('project_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Navigation buttons --}}
                <div class="d-flex justify-content-between mt-2">
                    <a href="{{ route('provisional.wizard.show', [$application, $step - 1]) }}"
                       class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Previous
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Save & Continue <i class="bi bi-arrow-right"></i>
                    </button>
                </div>

            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script>
$(document).ready(function() {

    // Toggle expansion details
    $('input[name="project_type"]').on('change', function() {
        $('#expansionDetails').toggle(this.value === 'Expansion');
    });

    // Toggle "Other" category field
    $('input[name="project_category"]').on('change', function() {
        $('#otherCategoryField').toggle(this.value === 'Others');
    });

    // Add expansion row
    $(document).on('click', '.addExpansionRow', function() {
        const rowCount = $('#expansionTable tbody tr').length + 1;
        const newRow = `
            <tr>
                <td>${rowCount}</td>
                <td><input type="text" class="form-control" name="existing_facilities[]"></td>
                <td><input type="number" class="form-control" name="existing_employment[]"></td>
                <td><input type="text" class="form-control" name="expansion_facilities[]"></td>
                <td><input type="number" class="form-control" name="expansion_employment[]"></td>
                <td>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-primary btn-sm addExpansionRow">
                            <i class="bi bi-plus-circle"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm removeExpansionRow">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        $('#expansionTable tbody').append(newRow);
    });

    // Remove expansion row
    $(document).on('click', '.removeExpansionRow', function() {
        const $tbody = $('#expansionTable tbody');
        if ($tbody.find('tr').length > 1) {
            $(this).closest('tr').remove();
            $tbody.find('tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
        }
    });

    // Add entrepreneur row
    $(document).on('click', '.addEntreRow', function() {
        const rowCount = $('#entrepreneurTable tbody tr').length + 1;
        const newRow = `
            <tr>
                <td>${rowCount}</td>
                <td><input type="text" class="form-control" name="entre_name[]" placeholder="Enter Name"></td>
                <td><input type="text" class="form-control" name="entre_designation[]" placeholder="Enter Designation"></td>
                <td><input type="number" class="form-control" name="entre_ownership[]" placeholder="%"></td>
                <td>
                    <select class="form-control" name="entre_gender[]">
                        <option value="">Select</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </td>
                <td><input type="number" class="form-control" name="entre_age[]" placeholder="Enter Age"></td>
                <td>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-primary btn-sm addEntreRow">
                            <i class="bi bi-plus-circle"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm removeEntreRow">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        $('#entrepreneurTable tbody').append(newRow);
    });

    // Remove entrepreneur row
    $(document).on('click', '.removeEntreRow', function() {
        const $tbody = $('#entrepreneurTable tbody');
        if ($tbody.find('tr').length > 1) {
            $(this).closest('tr').remove();
            $tbody.find('tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
        }
    });

    // Custom validator: Indian mobile (6–9, total 10 digits)
    $.validator.addMethod("indianMobile", function(value, element) {
        return this.optional(element) || /^[6-9][0-9]{9}$/.test(value);
    }, "Please enter a valid 10-digit mobile number starting with 6, 7, 8 or 9.");

    // jQuery Validation setup
    $("#stepForm").validate({
        rules: {
            survey_type: { required: true },
            survey_number: { required: true },
            village_city: { required: true },
            taluka: { required: true },
            district: { required: true },
            state: { required: true },
            pincode: {
                required: true,
                digits: true,
                minlength: 6,
                maxlength: 6
            },
            mobile: {
                required: true,
                indianMobile: true
            },
            email: {
                required: true,
                email: true
            },
            zone: { required: true },
            project_type: { required: true },
            project_category: { required: true },
            project_subcategory: {
                required: true,
                minlength: 3
            },
            project_description: {
                required: true,
                minlength: 20
            }
        },
        messages: {
            pincode: {
                required: "Pincode is required",
                digits: "Please enter only digits",
                minlength: "Pincode must be 6 digits",
                maxlength: "Pincode must be 6 digits"
            },
            mobile: {
                required: "Mobile number is required"
            },
            email: {
                required: "Email is required",
                email: "Please enter a valid email address"
            },
            zone: {
                required: "Please select a zone"
            },
            project_type: {
                required: "Please select project type"
            },
            project_category: {
                required: "Please select project category"
            },
            project_subcategory: {
                required: "Please enter project subcategory",
                minlength: "Project subcategory must be at least 3 characters"
            },
            project_description: {
                required: "Please enter project description",
                minlength: "Description must be at least 20 characters"
            }
        },
        errorClass: "text-danger small mt-1",
        errorElement: "div",
        highlight: function (element) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid");
        }
    });
});
</script>
{{--
<script>
    $(document).ready(function() {



        $.validator.addMethod("mastFormat", function(value, element) {
            return this.optional(element) || /^(0?[1-9]|[1-9][0-9])\/(0?[0-9]{2}|[0-9]{3})$/.test(
                value);
        }, "Format must be 01-99/001-999");

        $.validator.addMethod("phoneNumber", function(value, element) {
            return this.optional(element) || /^[0-9]{10}$/.test(value);
        }, "Enter a valid 10-digit mobile number");

        $.validator.addMethod("onlyNumbers", function(value, element) {
            return this.optional(element) || /^[0-9]+$/.test(value);
        }, "Numbers only");

        $.validator.addMethod("fileSize", function(value, element, param) {
            if (element.files.length === 0) return true;
            for (let i = 0; i < element.files.length; i++) {
                if (element.files[i].size > param) return false;
            }
            return true;
        }, "Each file must be less than 10MB");

        $.validator.addMethod("fileExtensions", function(value, element, param) {
            if (element.files.length === 0) return true;
            let allowedExt = param.split('|');
            for (let i = 0; i < element.files.length; i++) {
                let ext = element.files[i].name.split('.').pop().toLowerCase();
                if (!allowedExt.includes(ext)) return false;
            }
            return true;
        }, "Invalid file type");
        $.validator.addMethod("allChecked", function(value, element) {
            return $("input[name='instructions[]']").length ===
                $("input[name='instructions[]']:checked").length;
        }, "Please select ALL instruction checkboxes.");
        // Custom validation: exchanged PN and entered PN should not be the same
        $.validator.addMethod("notSamePn", function(value, element, params) {
            var otherValue = $(params).val();
            return value !== otherValue;
        }, "Exchanged PN and entered PN must not be the same.");
        $.validator.addMethod("notEqualGeneratedPn", function(value, element, param) {
            return value !== $(param).val();
        }, "Entered PN must be different from generated PN.");

        // Add rules for maintenance_staff_pn and station_pn

        /* ─────────────────────────────────────────────
            VALIDATION INITIALIZATION
        ───────────────────────────────────────────── */

        $("#ptwtrackForm").validate({

            ignore: [],

            rules: {
                epic_name: {
                    required: true,
                },
                employee_no: {
                    required: true
                },
                designation_id: {
                    required: true
                },
                department_id: {
                    required: true
                },
                organization_id: {
                    required: true
                },
                station_id: {
                    required: true
                },
                request_eng_possession: {
                    required: true
                },
                power_block_required: {
                    required: true
                },
                permit_to_work_for: {
                    required: true
                },
                level_of_work: {
                    required: true
                },
                location_of_work: {
                    required: true
                },
                work_area: {
                    required: true
                },

                work_sec_between_from_station_id: {
                    required: true
                },
                work_sec_between_to_station_id: {
                    required: true
                },

                work_location_from_mast_no: {
                    /// required: true,
                    mastFormat: true
                },
                work_location_to_mast_no: {
                    //  required: true,
                    mastFormat: true
                },

                integrated_blocked_required: {
                    required: true
                },

                location_from: {
                    required: true
                },
                location_to: {
                    required: true
                },

                no_of_sub_epic: {
                    required: true,
                    digits: true,
                    min: 0
                },

                shadow_power_block: {
                    required: true
                },
                shadow_mast_from: {
                    required: true,
                    mastFormat: true
                },
                shadow_mast_to: {
                    required: true,
                    mastFormat: true
                },

                earthrod_mast: {
                    required: true,
                    mastFormat: true
                },
                maintenance_staff_pn: {
                    required: true,
                    digits: true,
                    minlength: 2,
                    maxlength: 2,
                    notEqualGeneratedPn: "#generatedPnValue"
                },
                station_pn: {
                    required: true,
                    digits: true,
                    minlength: 2,
                    maxlength: 2,
                    notEqualGeneratedPn: "#generatedStationPnValue"
                },
                exchanged_maintenance_staff_pn: {
                    required: true,
                    digits: true,
                    minlength: 2,
                    maxlength: 2
                },
                exchanged_station_pn: {
                    required: true,
                    digits: true,
                    minlength: 2,
                    maxlength: 2
                },
                ptw_work_from_time: {
                    required: true
                },
                ptw_work_to_time: {
                    required: true
                },
                ptw_work_on: {
                    required: true
                },
                ptw_closed_station: {
                    required: true
                },

                worktype: {
                    required: true
                },

                cml_staff: {
                    required: true,
                    digits: true,
                    min: 0
                },
                contractor_staff: {
                    required: true,
                    digits: true,
                    min: 0
                },

                epic_mobile_no: {
                    required: true,
                    phoneNumber: true
                },
                tetra_no: {
                    required: true,
                    onlyNumbers: true
                },

                "materials_images[]": {
                    fileSize: 10485760,
                    fileExtensions: "jpg|jpeg|png|pdf"
                },
                "instructions[]": {
                    allChecked: true
                },
                workdescription: {
                    required: true
                },
                listofmat: {
                    required: true
                },
            },

            messages: {
                epic_name: "Epic name must be numeric",
                employee_no: "Employee number required",
                designation_id: "Select designation",
                department_id: "Select department",
                organization_id: "Select organization",
                station_id: "Select station",
                request_eng_possession: "Choose an option",
                power_block_required: "Choose an option",
                permit_to_work_for: "Required",
                level_of_work: "Required",
                location_of_work: "Required",
                work_area: "Required",

                work_sec_between_from_station_id: "Select from station",
                work_sec_between_to_station_id: "Select to station",

                // work_location_from_mast_no: "Invalid mast format",
                // work_location_to_mast_no: "Invalid mast format",

                integrated_blocked_required: "Choose an option",

                location_from: "Enter location from",
                location_to: "Enter location to",

                no_of_sub_epic: {
                    required: "Required",
                    digits: "Digits only",
                    min: "Must be 0 or more"
                },

                shadow_power_block: "Required",

                // shadow_mast_from: "Invalid mast format",
                // shadow_mast_to: "Invalid mast format",

                // earthrod_mast: "Invalid mast format",

                maintenance_staff_pn: {
                    required: "Maintenance Staff PN is required",
                    digits: "Maintenance Staff PN must be a two-digit number",
                    minlength: "Maintenance Staff PN must be exactly 2 digits",
                    maxlength: "Maintenance Staff PN must be exactly 2 digits",
                    notEqualGeneratedPn: "Entered PN must be DIFFERENT from the generated PN"
                },
                station_pn: {
                    required: "Station PN is required",
                    digits: "Station PN must be a two-digit number",
                    minlength: "Station PN must be exactly 2 digits",
                    maxlength: "Station PN must be exactly 2 digits",
                    notEqualGeneratedPn: "Entered PN must be DIFFERENT from the generated PN"
                },
                exchanged_maintenance_staff_pn: {
                    required: "Exchanged Maintenance Staff PN is required",
                    digits: "Exchanged Maintenance Staff PN must be a two-digit number",
                    minlength: "Exchanged Maintenance Staff PN must be exactly 2 digits",
                    maxlength: "Exchanged Maintenance Staff PN must be exactly 2 digits"
                },
                exchanged_station_pn: {
                    required: "Exchanged Station PN is required",
                    digits: "Exchanged Station PN must be a two-digit number",
                    minlength: "Exchanged Station PN must be exactly 2 digits",
                    maxlength: "Exchanged Station PN must be exactly 2 digits"
                },
                ptw_work_from_time: "Required",
                ptw_work_to_time: "Required",
                ptw_work_on: "Select date",
                ptw_closed_station: "Select station",

                worktype: "Select work type",

                cml_staff: "Digits only",
                contractor_staff: "Digits only",

                epic_mobile_no: "Invalid mobile number",
                tetra_no: "Numbers only",

                "materials_images[]": "Invalid file or file too large",
                "instructions[]": "You must select all the instructions."
            },

            errorElement: "div",
            errorClass: "text-danger",

            errorPlacement: function(error, element) {
                if (element.hasClass("select2-hidden-accessible")) {
                    error.insertAfter(element.next(".select2"));
                } else {
                    error.insertAfter(element);
                }
            },

            highlight: function(element) {
                $(element).addClass("is-invalid");
            },

            unhighlight: function(element) {
                $(element).removeClass("is-invalid");
            },
            highlight: function(element) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function(element) {
                $(element).removeClass("is-invalid").addClass("is-valid");
            }

        });
    });
</script>
--}}
<script>
    function validateAadhar(e, input) {
        const key = e.key || String.fromCharCode(e.which || e.keyCode);

        // Allow control keys
        if (
            key === 'Backspace' ||
            key === 'Tab' ||
            key === 'ArrowLeft' ||
            key === 'ArrowRight' ||
            key === 'Delete'
        ) {
            return true;
        }

        // Allow only digits (0–9)
        if (!/^[0-9]$/.test(key)) {
            return false;
        }

        // Limit to 12 digits
        if (input.value.length >= 12) {
            return false;
        }

        return true;
    }
</script>

<script>
    function validateAadhar(e, input) {
        const key = e.key || String.fromCharCode(e.which || e.keyCode);

        // Allow control keys
        if (
            key === 'Backspace' ||
            key === 'Tab' ||
            key === 'ArrowLeft' ||
            key === 'ArrowRight' ||
            key === 'Delete'
        ) {
            return true;
        }

        // Allow only digits (0–9)
        if (!/^[0-9]$/.test(key)) {
            return false;
        }

        // Limit to 12 digits
        if (input.value.length >= 12) {
            return false;
        }

        return true;
    }
</script>
@endpush
