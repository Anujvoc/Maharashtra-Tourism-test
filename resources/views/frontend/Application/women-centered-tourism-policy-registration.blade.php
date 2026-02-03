@extends('frontend.layouts2.master')
@section('title', 'Women-Centered Tourism Registration Form')

@push('styles')
<style>
  :root{
    --brand: #ff6600;
    --brand-dark: #e25500;
  }
  .form-icon {
      color: var(--brand);
      font-size: 1.2rem;
      margin-right:.35rem;
  }
  .required::after {
    content: " *";
    color: #dc3545;
    margin-left: 0.15rem;
    font-weight: 600;
  }

  /* Wizard */
  .wizard-steps {
      display:flex;
      justify-content:space-between;
      margin-bottom:1.5rem;
      gap:.75rem;
      flex-wrap:wrap;
  }
  .wizard-step {
      flex:1;
      min-width:170px;
      text-align:center;
      padding:.75rem .5rem;
      border-radius:999px;
      border:1px solid #dee2e6;
      background:#f8f9fa;
      font-size:.9rem;
      position:relative;
  }
  .wizard-step.active {
      background:var(--brand);
      color:#fff;
      border-color:var(--brand);
      box-shadow:0 0.25rem 0.6rem rgba(0,0,0,.08);
  }
  .wizard-step span.step-number{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      width:26px;height:26px;
      border-radius:50%;
      border:2px solid currentColor;
      margin-right:.35rem;
      font-weight:600;
      font-size:.85rem;
  }
  .wizard-step .step-label{
      font-weight:600;
  }
  .wizard-section{ display:none; }
  .wizard-section.active{ display:block; }

  .btn-brand,
  .btn-brand:focus,
  .btn-brand:hover,
  .btn-brand:active {
      background:var(--brand);
      border-color:var(--brand);
      color:#ffffff !important;
      box-shadow:none;
  }

  .review-label{ font-weight:600; }
  .review-item{ padding:.35rem 0; border-bottom:1px dashed #e0e0e0; }

  /* File upload styles */
  .file-upload-container {
      border: 2px dashed #dee2e6;
      border-radius: 8px;
      padding: 1.5rem;
      text-align: center;
      background: #f8f9fa;
      transition: all 0.3s;
  }
  .file-upload-container:hover {
      border-color: var(--brand);
      background: rgba(255, 102, 0, 0.05);
  }
  .file-upload-container.dragover {
      border-color: var(--brand);
      background: rgba(255, 102, 0, 0.1);
  }
  .file-preview {
      margin-top: 1rem;
      display: none;
  }
  .file-preview img {
      max-width: 100%;
      max-height: 200px;
      border-radius: 4px;
      box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
      cursor: pointer;
      transition: transform 0.3s;
  }
  .file-preview img:hover {
      transform: scale(1.02);
  }
  .file-info {
      margin-top: 0.5rem;
      font-size: 0.875rem;
      color: #6c757d;
  }
  .file-success {
      color: #198754;
      font-weight: 500;
  }
  .file-error {
      color: #dc3545;
      font-weight: 500;
  }

  /* Modal styles for image preview */
  .preview-modal .modal-content {
      border-radius: 12px;
      overflow: hidden;
  }
  .preview-modal .modal-body {
      padding: 0;
      text-align: center;
      background: #f8f9fa;
  }
  .preview-modal .modal-body img {
      max-width: 100%;
      max-height: 80vh;
  }
</style>
@endpush

@section('content')
<main class="main-wrapper">
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="row">
                        <div class="card-header w-100 d-flex flex-wrap justify-content-between align-items-center">
                            <div class="col-md-6 col-12">
                                <h3 class="mb-3">Women-Centered Tourism Registration</h3>
                            </div>

                            <div class="col-md-6 col-12 d-flex justify-content-md-end justify-content-start mt-2 mt-md-0">
                                <a href="{{ route('frontend.women-tourism-registrations.index') }}"
                                   class="text-white fw-bold d-inline-block no-underline"
                                   style="background-color:#3006ea; border:none; border-radius:8px; padding:.4rem 1.3rem;">
                                    <i class="bi bi-arrow-left me-2 mx-2"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="container py-3">

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    Please fix the errors below and try again.
                                </div>
                            @endif

                            {{-- Wizard Steps --}}
                            <div class="wizard-steps mb-4">
                                <div class="wizard-step active" data-step="1">
                                    <span class="step-number">1</span>
                                    <span class="step-label">Basic Information</span>
                                </div>
                                <div class="wizard-step" data-step="2">
                                    <span class="step-number">2</span>
                                    <span class="step-label">Business Details</span>
                                </div>
                                <div class="wizard-step" data-step="3">
                                    <span class="step-number">3</span>
                                    <span class="step-label">Bank & Operation</span>
                                </div>
                                <div class="wizard-step" data-step="4">
                                    <span class="step-number">4</span>
                                    <span class="step-label">Photo & Signature</span>
                                </div>
                                <div class="wizard-step" data-step="5">
                                    <span class="step-number">5</span>
                                    <span class="step-label">Review & Submit</span>
                                </div>
                            </div>

                            <form id="agriForm"
                                  action="{{ route('frontend.women-tourism-registrations.store') }}"
                                  method="POST" enctype="multipart/form-data" novalidate>
                                @csrf

                                {{-- STEP 1 --}}
                                <div class="wizard-section active" id="step-1">
                                    <div class="card mb-3">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">Applicant Details</h5>
                                        </div>
                                        <div class="card-body">

                                            {{-- Email + Mobile --}}
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="email" class="form-label required">
                                                            Email Id
                                                        </label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-envelope form-icon"></i>
                                                            </span>
                                                            <input id="email" type="email" name="email"
                                                                   class="form-control @error('email') is-invalid @enderror"
                                                                   value="{{ old('email', $user->email) }}"
                                                                   readonly required>
                                                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="mobile" class="form-label required">
                                                            Mobile No.
                                                        </label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-phone form-icon"></i>
                                                            </span>
                                                            <input id="mobile" type="text" name="mobile"
                                                                   class="form-control @error('mobile') is-invalid @enderror"
                                                                   value="{{ old('mobile', $user->phone) }}"
                                                                   readonly required>
                                                            @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Applicant Name + Business Name --}}
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label required">Applicant's Name</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-user form-icon"></i>
                                                            </span>
                                                            <input type="text" name="applicant_name"
                                                                   class="form-control @error('applicant_name') is-invalid @enderror"
                                                                   value="{{ old('applicant_name', $user->name) }}"
                                                                   required>
                                                            @error('applicant_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label required">Business Name</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-briefcase form-icon"></i>
                                                            </span>
                                                            <input type="text" name="business_name"
                                                                   class="form-control @error('business_name') is-invalid @enderror" pattern="^[A-Za-z\s]+$"
                                                                   title="Only letters and spaces are allowed"  onkeypress="return validateName(event)"
                                                                   value="{{ old('business_name') }}"
                                                                   required>
                                                            @error('business_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="id" value="{{ $id ?? ''}}">
                                            {{-- Organisation Type + Gender + DOB --}}
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label required">Type of Organisation</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-user-tag form-icon"></i>
                                                            </span>
                                                            <select name="organisation_type"
                                                                    class="form-control @error('organisation_type') is-invalid @enderror"
                                                                    required>
                                                                <option value="" disabled {{ old('organisation_type') ? '' : 'selected' }}>-- Select Organisation --</option>
                                                                @foreach($applicantTypes as $type)
                                                                    <option value="{{ $type->id }}" {{ old('organisation_type') == $type->id ? 'selected' : '' }}>
                                                                        {{ $type->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('organisation_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label required">Gender</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-venus-mars form-icon"></i>
                                                            </span>
                                                            <select name="gender"
                                                                    class="form-control @error('gender') is-invalid @enderror"
                                                                    required>
                                                                <option value="">-- Select --</option>
                                                                <option value="male" {{ old('gender')=='male'?'selected':'' }}>Male</option>
                                                                <option value="female" {{ old('gender')=='female'?'selected':'' }}>Female</option>
                                                            </select>
                                                            @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label required">Date of Birth</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-calendar form-icon"></i>
                                                            </span>
                                                            <input type="date" name="dob" id="dob"
                                                                   class="form-control @error('dob') is-invalid @enderror"
                                                                   value="{{ old('dob') }}" required>
                                                            @error('dob')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Age + Landline --}}
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label required">Age</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-hourglass-half form-icon"></i>
                                                            </span>
                                                            <input type="number" name="age" id="age"
                                                                   class="form-control @error('age') is-invalid @enderror"
                                                                   value="{{ old('age') }}" required>
                                                            @error('age')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Landline No.</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-phone-volume form-icon"></i>
                                                            </span>
                                                            <input type="text" name="landline"
                                                                   class="form-control @error('landline') is-invalid @enderror"
                                                                   value="{{ old('landline') }}">
                                                            @error('landline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Addresses --}}
                                            <div class="mb-3">
                                                <div class="form-group mb-3">
                                                    <label class="form-label required">Residential Address</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">
                                                            <i class="fa fa-home form-icon"></i>
                                                        </span>
                                                        <textarea name="residential_address" rows="2"
                                                                  class="form-control @error('residential_address') is-invalid @enderror"
                                                                  required>{{ old('residential_address') }}</textarea>
                                                        @error('residential_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <div class="form-group mb-3">
                                                    <label class="form-label required">Business Address</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">
                                                            <i class="fa fa-building form-icon"></i>
                                                        </span>
                                                        <textarea name="business_address" rows="2"
                                                                  class="form-control @error('business_address') is-invalid @enderror"
                                                                  required>{{ old('business_address') }}</textarea>
                                                        @error('business_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-brand" id="btnNext1">
                                            Next <i class="bi bi-arrow-right-circle ms-1"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- STEP 2 --}}
                                <div class="wizard-section" id="step-2">
                                    <div class="card mb-3">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">Business Details</h5>
                                        </div>
                                        <div class="card-body">

                                            <div class="mb-3">
                                                <div class="form-group mb-3">
                                                    <label class="form-label required">Business Type in Tourism Sector</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">
                                                            <i class="fa fa-suitcase-rolling form-icon"></i>
                                                        </span>
                                                        <select name="tourism_business_type"
                                                                class="form-control @error('tourism_business_type') is-invalid @enderror"
                                                                required>
                                                            <option value="">-- Select --</option>
                                                            @php $tbt = old('tourism_business_type'); @endphp
                                                            <option {{ $tbt=='Agro Tourism Center'?'selected':'' }}>Agro Tourism Center</option>
                                                            <option {{ $tbt=='Home Stay'?'selected':'' }}>Home Stay</option>
                                                            <option {{ $tbt=='Resort'?'selected':'' }}>Resort</option>
                                                            <option {{ $tbt=='Hotel'?'selected':'' }}>Hotel</option>
                                                            <option {{ $tbt=='Caravan / Adventure Tourism'?'selected':'' }}>Caravan / Adventure Tourism</option>
                                                            <option {{ $tbt=='Restaurant / Café'?'selected':'' }}>Restaurant / Café</option>
                                                            <option {{ $tbt=='Tour Operator / Travel Agent'?'selected':'' }}>Tour Operator / Travel Agent</option>
                                                            <option {{ $tbt=='Other'?'selected':'' }}>Other</option>
                                                        </select>
                                                        @error('tourism_business_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <div class="form-group mb-3">
                                                    <label class="form-label required">Tourism Business Name</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">
                                                            <i class="fa fa-store form-icon"></i>
                                                        </span>
                                                        <input type="text" name="tourism_business_name" pattern="^[A-Za-z\s]+$"
                                                        title="Only letters and spaces are allowed"  onkeypress="return validateName(event)"
                                                               class="form-control @error('tourism_business_name') is-invalid @enderror"
                                                               value="{{ old('tourism_business_name') }}" required>
                                                        @error('tourism_business_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label required">Aadhar Card No</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-id-card form-icon"></i>
                                                            </span>
                                                            <input type="text" name="aadhar_no"  maxlength="12" onkeypress="return validateAadhar(event, this)"
                                                                   class="form-control @error('aadhar_no') is-invalid @enderror"
                                                                   value="{{ old('aadhar_no') }}" required>
                                                            @error('aadhar_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label required">PAN Card No</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-id-badge form-icon"></i>
                                                            </span>
                                                            <input type="text" name="pan_no" maxlength="10" onkeypress="return validatePan(event, this);" text-uppercase
                                                            pattern="^[A-Z]{5}[0-9]{4}[A-Z]$"
                                                            oninput="
                                                              this.value = this.value.toUpperCase();
                                                              this.value = this.value.replace(/[^A-Z0-9]/g, '');
                                                              if (this.value.length > 10) this.value = this.value.slice(0, 10);
                                                            "
                                                            maxlength="10" required
                                                                   class="form-control @error('pan_no') is-invalid @enderror"
                                                                   value="{{ old('pan_no') }}" required>

                                                                   @error('pan_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                                </div>
                                                                <div class="info-text text-info">Format: 5 letters, 4 digits, 1 letter (e.g., ABCDE1234F)</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Company / Firm PAN (if any)</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-building-columns form-icon"></i>
                                                            </span>
                                                            <input type="text" name="company_pan_no"
                                                                   class="form-control @error('company_pan_no') is-invalid @enderror"
                                                                   value="{{ old('company_pan_no') }}">
                                                            @error('company_pan_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label required">Caste</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-users form-icon"></i>
                                                            </span>
                                                            <select name="caste"
                                                                    class="form-control @error('caste') is-invalid @enderror"
                                                                    required>
                                                                <option value="" disabled {{ old('caste') ? '' : 'selected' }}>-- Select --</option>
                                                                @foreach($categories as $type)
                                                                    <option value="{{ $type->id }}" {{ old('caste') == $type->id ? 'selected' : '' }}>
                                                                        {{ $type->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('caste')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Udyog Aadhar (If Available)</label>
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox"
                                                                   id="has_udyog_aadhar" name="has_udyog_aadhar"
                                                                   value="1" {{ old('has_udyog_aadhar') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="has_udyog_aadhar">
                                                                I have Udyog Aadhar
                                                            </label>
                                                        </div>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-industry form-icon"></i>
                                                            </span>
                                                            <input type="text" name="udyog_aadhar_no" maxlength="12" onkeypress="return validateAadhar(event, this)"
                                                                   class="form-control @error('udyog_aadhar_no') is-invalid @enderror"
                                                                   placeholder="Udyog Aadhar No."
                                                                   value="{{ old('udyog_aadhar_no') }}">
                                                            @error('udyog_aadhar_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">GST No (If Available)</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-file-invoice-dollar form-icon"></i>
                                                            </span>
                                                            <input type="text" name="gst_no"
                                                                   class="form-control @error('gst_no') is-invalid @enderror"
                                                                   value="{{ old('gst_no') }}">
                                                            @error('gst_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">No. of Female Employees</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-female form-icon"></i>
                                                            </span>
                                                            <input type="number" name="female_employees"
                                                                   class="form-control @error('female_employees') is-invalid @enderror"
                                                                   value="{{ old('female_employees') }}">
                                                            @error('female_employees')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Total No. of Employees</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-users form-icon"></i>
                                                            </span>
                                                            <input type="number" name="total_employees"
                                                                   class="form-control @error('total_employees') is-invalid @enderror"
                                                                   value="{{ old('total_employees') }}">
                                                            @error('total_employees')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <div class="form-group mb-3">
                                                    <label class="form-label required">Total Project Cost (excluding land)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">
                                                            <i class="fa fa-rupee-sign form-icon"></i>
                                                        </span>
                                                        <input type="number" step="0.01" name="total_project_cost"
                                                               class="form-control @error('total_project_cost') is-invalid @enderror"
                                                               value="{{ old('total_project_cost') }}" required>
                                                        @error('total_project_cost')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <div class="form-group mb-3">
                                                    <label class="form-label required">Information of Project (Minimum 500 words)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text align-items-start">
                                                            <i class="fa fa-align-left form-icon"></i>
                                                        </span>
                                                        <textarea name="project_information" rows="5"
                                                                  class="form-control @error('project_information') is-invalid @enderror"
                                                                  placeholder="Describe your project in detail..."
                                                                  required>{{ old('project_information') }}</textarea>
                                                        @error('project_information')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-outline-secondary" data-prev="1">
                                            <i class="bi bi-arrow-left-circle me-1"></i> Previous
                                        </button>
                                        <button type="button" class="btn btn-brand" data-next="3">
                                            Next <i class="bi bi-arrow-right-circle ms-1"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- STEP 3 --}}
                                <div class="wizard-section" id="step-3">
                                    <div class="card mb-3">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">Bank Details & Business Operation</h5>
                                        </div>
                                        <div class="card-body">

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label required">Account Holder Name</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-user-circle form-icon"></i>
                                                            </span>
                                                            <input type="text" name="bank_account_holder"
                                                                   class="form-control @error('bank_account_holder') is-invalid @enderror"
                                                                   value="{{ old('bank_account_holder') }}" required>
                                                            @error('bank_account_holder')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label required">Account No</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-hashtag form-icon"></i>
                                                            </span>
                                                            <input type="text" name="bank_account_no"
                                                                   class="form-control @error('bank_account_no') is-invalid @enderror"
                                                                   value="{{ old('bank_account_no') }}" required>
                                                            @error('bank_account_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Type of Account</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-list form-icon"></i>
                                                            </span>
                                                            <input type="text" name="bank_account_type"
                                                                   class="form-control @error('bank_account_type') is-invalid @enderror"
                                                                   placeholder="Savings / Current"
                                                                   value="{{ old('bank_account_type') }}">
                                                            @error('bank_account_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label required">Bank Name</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-university form-icon"></i>
                                                            </span>
                                                            <input type="text" name="bank_name"
                                                                   class="form-control @error('bank_name') is-invalid @enderror"
                                                                   value="{{ old('bank_name') }}" required>
                                                            @error('bank_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label required">IFSC Code</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-code form-icon"></i>
                                                            </span>
                                                            <input type="text" name="bank_ifsc"
                                                                   class="form-control @error('bank_ifsc') is-invalid @enderror"
                                                                   value="{{ old('bank_ifsc') }}" required>
                                                            @error('bank_ifsc')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Is Business in Operation?</label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                           id="business_in_operation" name="business_in_operation" value="1"
                                                           {{ old('business_in_operation') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="business_in_operation">
                                                        Yes, business is already in operation
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">If YES, Since when?</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-calendar-check form-icon"></i>
                                                            </span>
                                                            <input type="date" name="business_operation_since"
                                                                   class="form-control @error('business_operation_since') is-invalid @enderror"
                                                                   value="{{ old('business_operation_since') }}">
                                                            @error('business_operation_since')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">If NO, Expected Start Date</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                <i class="fa fa-calendar-plus form-icon"></i>
                                                            </span>
                                                            <input type="date" name="business_expected_start"
                                                                   class="form-control @error('business_expected_start') is-invalid @enderror"
                                                                   value="{{ old('business_expected_start') }}">
                                                            @error('business_expected_start')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-outline-secondary" data-prev="2">
                                            <i class="bi bi-arrow-left-circle me-1"></i> Previous
                                        </button>
                                        <button type="button" class="btn btn-brand" data-next="4">
                                            Next <i class="bi bi-arrow-right-circle ms-1"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- STEP 4: Photo & Signature --}}
                                <div class="wizard-section" id="step-4">
                                    <div class="card mb-3">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">Upload Photo & Signature</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-4">
                                                        <label class="form-label required">Applicant's Photo</label>
                                                        <div class="file-upload-container" id="photoUploadContainer">
                                                            <div class="text-center mb-3">
                                                                <i class="fa fa-camera fa-3x text-muted mb-2"></i>
                                                                <p class="mb-1">Click to upload or drag and drop</p>
                                                                <p class="small text-muted">JPG, JPEG or PNG (Max. 200KB)</p>
                                                            </div>
                                                            <input type="file" name="applicant_image" id="applicant_image"
                                                                   class="d-none" accept="image/jpeg,image/jpg,image/png">
                                                            <button type="button" class="btn btn-outline-primary"
                                                                    onclick="document.getElementById('applicant_image').click()">
                                                                Choose Photo
                                                            </button>
                                                        </div>
                                                        <div class="file-preview mt-3" id="applicant_image_preview">
                                                            <div class="text-center">
                                                                <img id="applicant_image_preview_img" src="" alt="Photo Preview"
                                                                     class="img-thumbnail mb-2" style="max-height: 200px; cursor: pointer;"
                                                                     data-bs-toggle="modal" data-bs-target="#imagePreviewModal">
                                                                <div class="file-info">
                                                                    <div id="applicant_image_info"></div>
                                                                    <div id="applicant_image_success" class="file-success d-none"></div>
                                                                    <div id="applicant_image_error" class="file-error d-none"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-4">
                                                        <label class="form-label required">Applicant's Signature</label>
                                                        <div class="file-upload-container" id="signatureUploadContainer">
                                                            <div class="text-center mb-3">
                                                                <i class="fa fa-signature fa-3x text-muted mb-2"></i>
                                                                <p class="mb-1">Click to upload or drag and drop</p>
                                                                <p class="small text-muted">JPG, JPEG or PNG (Max. 50KB)</p>
                                                            </div>
                                                            <input type="file" name="applicant_signature" id="applicant_signature"
                                                                   class="d-none" accept="image/jpeg,image/jpg,image/png">
                                                            <button type="button" class="btn btn-outline-primary"
                                                                    onclick="document.getElementById('applicant_signature').click()">
                                                                Choose Signature
                                                            </button>
                                                        </div>
                                                        <div class="file-preview mt-3" id="applicant_signature_preview">
                                                            <div class="text-center">
                                                                <img id="applicant_signature_preview_img" src="" alt="Signature Preview"
                                                                     class="img-thumbnail mb-2" style="max-height: 150px; cursor: pointer;"
                                                                     data-bs-toggle="modal" data-bs-target="#imagePreviewModal">
                                                                <div class="file-info">
                                                                    <div id="applicant_signature_info"></div>
                                                                    <div id="applicant_signature_success" class="file-success d-none"></div>
                                                                    <div id="applicant_signature_error" class="file-error d-none"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-outline-secondary" data-prev="3">
                                            <i class="bi bi-arrow-left-circle me-1"></i> Previous
                                        </button>
                                        <button type="button" class="btn btn-brand" data-next="5" id="goToReview">
                                            Next (Review) <i class="bi bi-arrow-right-circle ms-1"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- STEP 5: REVIEW --}}
                                <div class="wizard-section" id="step-5">
                                    <div class="alert alert-warning">
                                        <strong>कृपया एक बार सभी जानकारी ध्यान से जाँच लें।</strong><br>
                                        Submit करने के बाद आप इस फॉर्म में बदलाव नहीं कर पाएंगे।
                                    </div>

                                    <div class="card mb-3">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">Review Your Details</h5>
                                        </div>
                                        <div class="card-body" id="reviewContainer">
                                        </div>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="confirmCheckbox">
                                        <label class="form-check-label" for="confirmCheckbox">
                                            I have checked all the details carefully. They are correct and I understand that
                                            I cannot change them after submission.
                                        </label>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-outline-secondary" data-prev="4">
                                            <i class="bi bi-arrow-left-circle me-1"></i> Edit Again
                                        </button>
                                        <button type="submit" class="btn btn-success" id="finalSubmitBtn" disabled>
                                            <i class="bi bi-check-circle me-1"></i> Confirm & Submit
                                        </button>
                                    </div>
                                </div>

                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>

<!-- Modal for Image Preview -->
<div class="modal fade preview-modal" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imagePreviewModalLabel">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img id="modalPreviewImage" src="" alt="Preview" class="img-fluid">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Constants for file validation
    const MAX_PHOTO_SIZE = 200 * 1024; // 200KB
    const MAX_SIGNATURE_SIZE = 50 * 1024; // 50KB
    const VALID_IMAGE_TYPES = ['image/jpeg', 'image/jpg', 'image/png'];

    function setStep(step){
        document.querySelectorAll('.wizard-section').forEach(el=>{
            el.classList.remove('active');
        });
        document.querySelector('#step-' + step).classList.add('active');

        document.querySelectorAll('.wizard-step').forEach(el=>{
            el.classList.remove('active');
            if(el.dataset.step == step){ el.classList.add('active'); }
        });

        window.scrollTo({top:0, behavior:'smooth'});
    }

    // Frontend validation per step (only required fields)
    function validateStep(step) {
        const section = document.querySelector('#step-' + step);
        const requiredFields = section.querySelectorAll('[required]');
        let valid = true;

        for (const field of requiredFields) {
            // Trigger HTML5 validation
            if (!field.checkValidity() || !field.value.trim()) {
                field.classList.add('is-invalid');
                field.reportValidity();
                if (valid) {
                    field.focus();
                }
                valid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        }
        return valid;
    }

    document.getElementById('btnNext1').addEventListener('click', function(){
        if (!validateStep(1)) return;
        setStep(2);
    });

    document.querySelectorAll('[data-next]').forEach(btn=>{
        btn.addEventListener('click', function(){
            const currentSection = this.closest('.wizard-section');
            if (!currentSection) return;
            const currentStep = parseInt(currentSection.id.split('-')[1]);

            if (!validateStep(currentStep)) return;

            const step = this.getAttribute('data-next');
            if (this.id === 'goToReview') {
                fillReview();
            }
            setStep(step);
        });
    });

    document.querySelectorAll('[data-prev]').forEach(btn=>{
        btn.addEventListener('click', function(){
            const step = this.getAttribute('data-prev');
            setStep(step);
        });
    });

    // Build Review content
    function fillReview(){
        const review = document.getElementById('reviewContainer');
        const form = document.getElementById('agriForm');
        const getVal = name => form.querySelector('[name="'+name+'"]')?.value || '';
        const getSelectText = name => {
            const select = form.querySelector('[name="'+name+'"]');
            return select ? select.options[select.selectedIndex]?.text || '' : '';
        };

        const yesNo = (name) => form.querySelector('[name="'+name+'"]')?.checked ? 'Yes' : 'No';

        review.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <div class="review-item"><span class="review-label">Email:</span> ${getVal('email')}</div>
                    <div class="review-item"><span class="review-label">Mobile:</span> ${getVal('mobile')}</div>
                    <div class="review-item"><span class="review-label">Applicant Name:</span> ${getVal('applicant_name')}</div>
                    <div class="review-item"><span class="review-label">Business Name:</span> ${getVal('business_name')}</div>
                    <div class="review-item"><span class="review-label">Organisation Type:</span> ${getSelectText('organisation_type')}</div>
                    <div class="review-item"><span class="review-label">Gender:</span> ${getVal('gender')}</div>
                    <div class="review-item"><span class="review-label">DOB:</span> ${getVal('dob')}</div>
                    <div class="review-item"><span class="review-label">Age:</span> ${getVal('age')}</div>
                    <div class="review-item"><span class="review-label">Landline No:</span> ${getVal('landline') || 'N/A'}</div>
                    <div class="review-item"><span class="review-label">Residential Address:</span> ${getVal('residential_address')}</div>
                    <div class="review-item"><span class="review-label">Business Address:</span> ${getVal('business_address')}</div>
                    <div class="review-item"><span class="review-label">Tourism Business Type:</span> ${getVal('tourism_business_type')}</div>
                    <div class="review-item"><span class="review-label">Tourism Business Name:</span> ${getVal('tourism_business_name')}</div>
                    <div class="review-item"><span class="review-label">Aadhar No:</span> ${getVal('aadhar_no')}</div>
                    <div class="review-item"><span class="review-label">PAN No:</span> ${getVal('pan_no')}</div>
                </div>
                <div class="col-md-6">
                    <div class="review-item"><span class="review-label">Company PAN No:</span> ${getVal('company_pan_no') || 'N/A'}</div>
                    <div class="review-item"><span class="review-label">Caste:</span> ${getSelectText('caste')}</div>
                    <div class="review-item"><span class="review-label">Udyog Aadhar:</span> ${yesNo('has_udyog_aadhar')} ${getVal('has_udyog_aadhar') ? '(' + getVal('udyog_aadhar_no') + ')' : ''}</div>
                    <div class="review-item"><span class="review-label">GST No:</span> ${getVal('gst_no') || 'N/A'}</div>
                    <div class="review-item"><span class="review-label">Female Employees:</span> ${getVal('female_employees') || 'N/A'}</div>
                    <div class="review-item"><span class="review-label">Total Employees:</span> ${getVal('total_employees') || 'N/A'}</div>
                    <div class="review-item"><span class="review-label">Total Project Cost:</span> ${getVal('total_project_cost')}</div>
                    <div class="review-item"><span class="review-label">Account Holder Name:</span> ${getVal('bank_account_holder')}</div>
                    <div class="review-item"><span class="review-label">Account No:</span> ${getVal('bank_account_no')}</div>
                    <div class="review-item"><span class="review-label">Account Type:</span> ${getVal('bank_account_type') || 'N/A'}</div>
                    <div class="review-item"><span class="review-label">Bank Name:</span> ${getVal('bank_name')}</div>
                    <div class="review-item"><span class="review-label">IFSC Code:</span> ${getVal('bank_ifsc')}</div>
                    <div class="review-item"><span class="review-label">Business in Operation:</span> ${yesNo('business_in_operation')}</div>
                    <div class="review-item"><span class="review-label">Business Operation Since:</span> ${getVal('business_operation_since') || 'N/A'}</div>
                    <div class="review-item"><span class="review-label">Business Expected Start:</span> ${getVal('business_expected_start') || 'N/A'}</div>
                </div>
            </div>
            <div class="mt-3">
                <div class="review-item">
                    <span class="review-label">Project Information:</span><br>
                    ${getVal('project_information').replace(/\n/g,'<br>')}
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="review-item">
                        <span class="review-label">Applicant Photo:</span><br>
                        <div id="reviewPhotoPreview" class="mt-2"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="review-item">
                        <span class="review-label">Applicant Signature:</span><br>
                        <div id="reviewSignaturePreview" class="mt-2"></div>
                    </div>
                </div>
            </div>
        `;

        // Add photo and signature previews to review
        const photoPreview = document.getElementById('applicant_image_preview_img');
        const signaturePreview = document.getElementById('applicant_signature_preview_img');

        if (photoPreview && photoPreview.src) {
            document.getElementById('reviewPhotoPreview').innerHTML =
                `<img src="${photoPreview.src}" alt="Photo Preview" class="img-thumbnail" style="max-height: 150px;">`;
        }

        if (signaturePreview && signaturePreview.src) {
            document.getElementById('reviewSignaturePreview').innerHTML =
                `<img src="${signaturePreview.src}" alt="Signature Preview" class="img-thumbnail" style="max-height: 100px;">`;
        }
    }

    // enable final submit only if checkbox checked
    document.getElementById('confirmCheckbox').addEventListener('change', function(){
        document.getElementById('finalSubmitBtn').disabled = !this.checked;
    });

    // Optional: auto-calc age from DOB
    document.getElementById('dob').addEventListener('change', function(){
        const dob = new Date(this.value);
        if(!isNaN(dob)){
            const diffMs = Date.now() - dob.getTime();
            const ageDt = new Date(diffMs);
            const age = Math.abs(ageDt.getUTCFullYear() - 1970);
            document.getElementById('age').value = age;
        }
    });

    // File upload handling
    function handleFilePreview(inputEl, $container, $img, fileType) {
        const files = inputEl.files;
        const $error = $(`#${inputEl.id}_error`);
        const $success = $(`#${inputEl.id}_success`);
        const $info = $(`#${inputEl.id}_info`);

        if (!files || !files.length) {
            $container.hide();
            $error.addClass('d-none');
            $success.addClass('d-none');
            $info.text('');
            return;
        }

        const file = files[0];
        const maxSize = fileType === 'photo' ? MAX_PHOTO_SIZE : MAX_SIGNATURE_SIZE;
        const maxSizeText = fileType === 'photo' ? '200KB' : '50KB';

        // Reset previous messages
        $error.addClass('d-none');
        $success.addClass('d-none');
        $info.text('');

        // Type validation
        if (!VALID_IMAGE_TYPES.includes(file.type)) {
            $error.text('❌ Only JPG, JPEG, or PNG files are allowed').removeClass('d-none');
            inputEl.value = '';
            $container.hide();
            return;
        }

        // Size validation
        if (file.size > maxSize) {
            $error.text('❌ File size too large! Maximum ' + maxSizeText + ' allowed').removeClass('d-none');
            inputEl.value = '';
            $container.hide();
            return;
        }

        // Show file info
        $info.text(`File: ${file.name} (${(file.size / 1024).toFixed(2)} KB)`);

        // Show preview
        const reader = new FileReader();
        reader.onload = function (e) {
            $img.attr('src', e.target.result);
            $container.show();
            $success.text('✓ File is valid').removeClass('d-none');

            // Set up modal preview
            $img.off('click').on('click', function() {
                $('#modalPreviewImage').attr('src', e.target.result);
                $('#imagePreviewModalLabel').text(fileType === 'photo' ? 'Photo Preview' : 'Signature Preview');
            });
        };
        reader.readAsDataURL(file);
    }

    // Set up file upload handlers
    $('#applicant_image').on('change', function(){
        handleFilePreview(this, $('#applicant_image_preview'), $('#applicant_image_preview_img'), 'photo');
    });

    $('#applicant_signature').on('change', function(){
        handleFilePreview(this, $('#applicant_signature_preview'), $('#applicant_signature_preview_img'), 'signature');
    });

    // Drag and drop functionality
    function setupDragDrop(containerId, inputId) {
        const container = document.getElementById(containerId);
        const input = document.getElementById(inputId);

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            container.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            container.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            container.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            container.classList.add('dragover');
        }

        function unhighlight() {
            container.classList.remove('dragover');
        }

        container.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            input.files = files;

            // Trigger change event
            const event = new Event('change', { bubbles: true });
            input.dispatchEvent(event);
        }
    }

    // Initialize drag and drop
    setupDragDrop('photoUploadContainer', 'applicant_image');
    setupDragDrop('signatureUploadContainer', 'applicant_signature');

    // Click on upload container to trigger file input
    document.getElementById('photoUploadContainer').addEventListener('click', function(e) {
        if (e.target.tagName !== 'INPUT' && e.target.tagName !== 'BUTTON') {
            document.getElementById('applicant_image').click();
        }
    });

    document.getElementById('signatureUploadContainer').addEventListener('click', function(e) {
        if (e.target.tagName !== 'INPUT' && e.target.tagName !== 'BUTTON') {
            document.getElementById('applicant_signature').click();
        }
    });

    // Input validation functions
    window.validateWhatsAppInput = function (event) {
        const charCode   = event.which ? event.which : event.keyCode;
        const curValue   = event.target.value || '';

        if (charCode < 48 || charCode > 57) { // 0-9 only
            event.preventDefault();
            return false;
        }
        if (curValue.length === 0 && (charCode < 54 || charCode > 57)) { // first digit 6-9
            event.preventDefault();
            return false;
        }
        if (curValue.length >= 10) { // max 10 digits
            event.preventDefault();
            return false;
        }
        return true;
    };

    window.validateName = function (event) {
        const charCode = event.which ? event.which : event.keyCode;
        if ((charCode >= 65 && charCode <= 90) ||   // A-Z
            (charCode >= 97 && charCode <= 122) ||  // a-z
            charCode === 32) {                      // space
            return true;
        }
        event.preventDefault();
        return false;
    };


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
