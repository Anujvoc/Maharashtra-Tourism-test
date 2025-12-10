@extends('frontend.layouts2.master')

@section('title', 'Application Form - Tourist Apartment Registration')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

@push('styles')
<style>
    .preview-box {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
    }
    .preview-item {
      position: relative;
      display: inline-block;
    }
    .preview-item img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    .preview-item .remove-btn {
      position: absolute;
      top: -8px;
      right: -8px;
      border-radius: 50%;
      padding: 2px 6px;
      line-height: 1;
      font-size: 14px;
    }
    </style>

<style>
  body {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  .section-header {
    background: linear-gradient(135deg, #ff6600 0%, #ff6600 100%);
    color: #fff;
    padding: 12px 20px;
    border-radius: 8px;
    margin-bottom: 25px;
    font-weight: 600;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    position: relative;
  }

  .section-header::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 20px;
    width: 40px;
    height: 3px;
    background: rgba(255, 255, 255, 0.7);
    border-radius: 2px;
  }

  .card {
    border-radius: 12px;
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    overflow: hidden;
  }

  .card-header {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-bottom: 1px solid #e3e6f0;
    padding: 20px 25px;
  }

  .card-header h4 {
    color: #2e3a59;
    font-weight: 700;
    margin: 0;
  }

  .card-body {
    padding: 30px;
  }

  label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
  }

  .form-control, .form-select {
    border-radius: 8px;
    padding: 10px 15px;
    border: 1px solid #d1d3e2;
    transition: all 0.3s;
  }

  .form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
  }

  .text-danger {
    font-size: 0.85rem;
    margin-top: 5px;
    display: block;
  }

  .alert {
    border-radius: 8px;
    border: none;
  }

  .alert-danger {
    background-color: rgba(220, 53, 69, 0.1);
    color: #721c24;
  }

  .alert-success {
    background-color: rgba(25, 135, 84, 0.1);
    color: #0f5132;
  }

  .table {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
  }

  .table th {
    background-color: #f8f9fa;
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #e3e6f0;
  }

  .btn {
    border-radius: 8px;
    padding: 10px 25px;
    font-weight: 600;
    transition: all 0.3s;
  }

  .btn-success {
    background: linear-gradient(135deg, #198754 0%, #146c43 100%);
    border: none;
  }

  .btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(25, 135, 84, 0.3);
  }

  .btn-outline-danger {
    border-color: #dc3545;
    color: #dc3545;
  }

  .btn-outline-danger:hover {
    background-color: #dc3545;
    color: white;
  }

  .preview-col img {
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  .progress-indicator {
    display: flex;
    justify-content: space-between;
    margin-bottom: 30px;
    position: relative;
  }

  .progress-indicator::before {
    content: '';
    position: absolute;
    top: 15px;
    left: 0;
    right: 0;
    height: 3px;
    background: #e9ecef;
    z-index: 1;
  }

  .progress-step {
    position: relative;
    z-index: 2;
    text-align: center;
    flex: 1;
  }

  .step-circle {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 8px;
    font-weight: 600;
    transition: all 0.3s;
  }

  .step-label {
    font-size: 0.85rem;
    color: #6c757d;
    font-weight: 500;
  }

  .progress-step.active .step-circle {
    background: #0d6efd;
    color: white;
  }

  .progress-step.active .step-label {
    color: #0d6efd;
    font-weight: 600;
  }

  .progress-step.completed .step-circle {
    background: #28a745;
    color: #fff;
  }

  .progress-step.completed .step-label {
    color: #28a745;
    font-weight: 600;
  }

  .form-section {
    margin-bottom: 40px;
    padding-bottom: 25px;
    border-bottom: 1px solid #e3e6f0;
  }

  .form-section:last-of-type {
    border-bottom: none;
  }

  .required-field::after {
    content: '*';
    color: #dc3545;
    margin-left: 4px;
  }

  .custom-radio-group {
    display: flex;
    gap: 15px;
  }

  .custom-radio {
    position: relative;
    flex: 1;
  }

  .custom-radio input {
    position: absolute;
    opacity: 0;
  }

  .custom-radio label {
    display: block;
    padding: 12px 15px;
    background: #f8f9fa;
    border: 1px solid #d1d3e2;
    border-radius: 8px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
  }

  .custom-radio input:checked + label {
    background: rgba(13, 110, 253, 0.1);
    border-color: #0d6efd;
    color: #0d6efd;
    font-weight: 600;
  }

  .form-step {
    display: none;
  }

  .form-step.active {
    display: block;
  }

  .navigation-buttons {
    display: flex;
    justify-content: space-between;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e3e6f0;
  }

  .declaration-box {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 20px;
    margin: 20px 0;
  }

  .preview-item {
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e9ecef;
  }

  .preview-label {
    font-weight: 600;
    color: #495057;
  }

  .preview-value {
    color: #6c757d;
  }

  @media (max-width: 768px) {
    .card-body {
      padding: 20px;
    }

    .custom-radio-group {
      flex-direction: column;
    }

    .navigation-buttons {
      flex-direction: column;
      gap: 10px;
    }

    .navigation-buttons .btn {
      width: 100%;
    }
  }
</style>
@endpush

@section('content')
<!-- Main Content -->
<section class="section">
  <div class="section-header">
    <h1>{{ $application->name ?? 'Tourist Apartment Registration' }}</h1>
  </div>

  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card shadow-lg">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Create {{ $application->name ?? 'Application' }}</h4>
          </div>

          <div class="card-body">
            <!-- Progress Indicator -->
            <div class="progress-indicator">
              <div class="progress-step active" data-step="1">
                <div class="step-circle">1</div>
                <div class="step-label">Applicant</div>
              </div>
              <div class="progress-step" data-step="2">
                <div class="step-circle">2</div>
                <div class="step-label">Property</div>
              </div>
              <div class="progress-step" data-step="3">
                <div class="step-circle">3</div>
                <div class="step-label">Accommodation</div>
              </div>
              <div class="progress-step" data-step="4">
                <div class="step-circle">4</div>
                <div class="step-label">Facilities</div>
              </div>
              <div class="progress-step" data-step="5">
                <div class="step-circle">5</div>
                <div class="step-label">Documents</div>
              </div>
              <div class="progress-step" data-step="6">
                <div class="step-circle">6</div>
                <div class="step-label">Review</div>
              </div>
            </div>

            @if ($errors->any())
              <div class="alert alert-danger">
                <ul class="mb-0">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            @if (session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- FORM START --}}
            <form id="tourismForm" method="POST" action="{{ route('frontend.TourismApartments.store') }}" enctype="multipart/form-data">
              @csrf

              <!-- Step 1: Applicant Details -->
              <div class="form-step active" id="step-1">
                <div class="section-header">A) Details of the Applicant</div>
                <div class="row g-3">
                <input type="hidden" name="application_id" value="{{ $application->id ?? '' }}">

                  <div class="col-md-6">
                    <label class="required-field">Name of the Applicant (Owner of the Unit)</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ Auth::user()->name ?? '' }}" placeholder="Enter full name">
                    @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                  </div>

                  <div class="col-md-6">
                    <label class="required-field">Telephone/Mobile No of Applicant</label>
                    <input type="number" name="mno" class="form-control @error('mno') is-invalid @enderror" min="0" maxlength="10" value="{{ Auth::user()->phone ?? ''}}" placeholder="10-digit mobile number">
                    @error('mno')<span class="text-danger">{{ $message }}</span>@enderror
                  </div>

                  <div class="col-md-6">
                    <label class="required-field">E-Mail ID of Applicant</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ Auth::user()->email ?? '' }}" placeholder="Enter email address">
                    @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                  </div>

                  <div class="col-md-6">
                    <label class="required-field">Name of the Business</label>
                    <input type="text" name="business" class="form-control @error('business') is-invalid @enderror" value="{{ old('business') }}" placeholder="Business name">
                    @error('business')<span class="text-danger">{{ $message }}</span>@enderror
                  </div>

                  <div class="col-md-6">
                    <label for="type" class="required-field">Type of Business</label>
                    <select class="form-control" id="type" name="type" required>
                      <option value="" selected disabled>Select Business Type</option>
                      @foreach (['Proprietorship','Partnership','Pvt Ltd','LLP','Public Ltd','Society','Trust','Other'] as $opt)
                        <option value="{{ $opt }}" {{ old('type') == $opt ? 'selected' : '' }}>
                          {{ $opt }}
                        </option>
                      @endforeach
                    </select>
                    @error('type')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                  </div>

                  <div class="col-md-6">
                    <label class="required-field">PAN Card Number</label>
                    <input type="text" name="pan" class="form-control @error('pan') is-invalid @enderror" value="{{ old('pan') }}" placeholder="e.g., ABCDE1234F">
                    @error('pan')<span class="text-danger">{{ $message }}</span>@enderror
                  </div>

                  <div class="col-md-6">
                    <label>Business PAN Card Number (If applicable)</label>
                    <input type="text" name="bpan" class="form-control @error('bpan') is-invalid @enderror" value="{{ old('bpan') }}" placeholder="Business PAN">
                    @error('bpan')<span class="text-danger">{{ $message }}</span>@enderror
                  </div>

                  <div class="col-md-6">
                    <label class="required-field">Aadhar Number of Applicant</label>
                    <input type="number" name="aadhar" class="form-control @error('aadhar') is-invalid @enderror" min="0" value="{{ Auth::user()->aadhar ?? '' }}" placeholder="12-digit Aadhar">
                    @error('aadhar')<span class="text-danger">{{ $message }}</span>@enderror
                  </div>

                  <div class="col-md-6">
                    <label>Udyam Aadhar Number (If applicable)</label>
                    <input type="text" name="uaadhar" class="form-control @error('uaadhar') is-invalid @enderror" value="{{ old('uaadhar') }}" placeholder="Udyam Aadhar">
                    @error('uaadhar')<span class="text-danger">{{ $message }}</span>@enderror
                  </div>
                </div>

                <div class="navigation-buttons">
                  <div></div> <!-- Empty div for spacing -->
                  <button type="button" class="btn btn-primary next-step" data-next="2">Next <i class="bi bi-arrow-right"></i></button>
                </div>
              </div>

              <!-- Step 2: Property Details -->
              <div class="form-step" id="step-2">
                <div class="section-header">B) Details of the Property</div>
                <div class="row g-3">
                  <div class="col-md-6">
                    <div id="imageContainer" class="row mt-3 g-3 image-container"></div>

                    <label class="required-field">Proof of Ownership of Property</label>
                    <input type="file" name="proof" id="fileInput" class="form-control file-input file-preview-input @error('proof') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
                    @error('proof')<span class="text-danger">{{ $message }}</span>@enderror
                  </div>

                  <div class="col-md-6">
                    <label class="required-field">Is the property rented to an operator?</label>
                    <div class="custom-radio-group">
                      <div class="custom-radio">
                        <input type="radio" name="prop" id="prop-no" value="No" {{ old('prop','No')==='No' ? 'checked':'' }}>
                        <label for="prop-no">No</label>
                      </div>
                      <div class="custom-radio">
                        <input type="radio" name="prop" id="prop-yes" value="Yes" {{ old('prop')==='Yes' ? 'checked':'' }}>
                        <label for="prop-yes">Yes</label>
                      </div>
                    </div>
                    @error('prop')<span class="text-danger">{{ $message }}</span>@enderror
                  </div>

                  <div class="col-md-6" id="opname-field" style="display: {{ old('prop')==='Yes' ? 'block' : 'none' }};">
                    <label>If yes, Operator's Name</label>
                    <input type="text" name="opname" id="opname-input" class="form-control @error('opname') is-invalid @enderror" value="{{ old('opname') }}" placeholder="Operator name">
                    @error('opname')<span class="text-danger">{{ $message }}</span>@enderror
                  </div>

                  <div class="col-md-6">
                    <label>Upload Rental Agreement / Management Contract</label>
                    <input type="file" name="agreement" class="form-control @error('agreement') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
                    @error('agreement')<span class="text-danger">{{ $message }}</span>@enderror
                  </div>

                  <div class="col-md-6">
                    <label class="required-field">Name of the Property</label>
                    <input type="text" name="pname" class="form-control @error('pname') is-invalid @enderror" value="{{ old('pname') }}" placeholder="Property name">
                    @error('pname')<span class="text-danger">{{ $message }}</span>@enderror
                  </div>

                  <div class="col-md-6">
                    <label class="required-field">Complete Postal Address</label>
                    <input type="text" name="padd" id="padd" class="form-control @error('padd') is-invalid @enderror" value="{{ old('padd') }}" placeholder="Full address">
                    @error('padd')<span class="text-danger">{{ $message }}</span>@enderror
                  </div>

                  <div class="col-md-6">
                    <div id="imageContainer" class="row mt-3 g-3 image-container"></div>
                    <label class="required-field">Proof of Address</label>
                    <input type="file" name="pradd" id="pradd" class="form-control file-input @error('pradd') is-invalid @enderror" accept=".jpg,.jpeg,.png">
                    @error('pradd')<span class="text-danger">{{ $message }}</span>@enderror
                  </div>

                  <div class="col-md-6">
                    <label class="required-field">Geographic Coordinates / Google Map Link</label>
                    <input type="text" name="gc" class="form-control @error('gc') is-invalid @enderror" value="{{ old('gc') }}" placeholder="e.g., 19.0760, 72.8777 or Google Maps link">
                    @error('gc')<span class="text-danger">{{ $message }}</span>@enderror
                  </div>
                </div>

                <div class="navigation-buttons">
                  <button type="button" class="btn btn-secondary prev-step" data-prev="1"><i class="bi bi-arrow-left"></i> Previous</button>
                  <button type="button" class="btn btn-primary next-step" data-next="3">Next <i class="bi bi-arrow-right"></i></button>
                </div>
              </div>

              <!-- Step 3: Property Additional Details -->
              <div class="form-step" id="step-3">
                <div class="section-header">B) Details of the Property (Continued)</div>
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="required-field">Already Operational?</label>
                    <div class="custom-radio-group">
                      <div class="custom-radio">
                        <input type="radio" name="ops" id="ops-no" value="No" {{ old('ops','No')==='No' ? 'checked':'' }}>
                        <label for="ops-no">No</label>
                      </div>
                      <div class="custom-radio">
                        <input type="radio" name="ops" id="ops-yes" value="Yes" {{ old('ops')==='Yes' ? 'checked':'' }}>
                        <label for="ops-yes">Yes</label>
                      </div>
                    </div>
                    @error('ops')<span class="text-danger">{{ $message }}</span>@enderror
                  </div>

                  <div class="col-md-6" id="year-field" style="display: {{ old('ops')==='Yes' ? 'block' : 'none' }};">
                    <label class="required-field">If yes, since which year?</label>
                    <input type="number" name="year" id="year-input" class="form-control @error('year') is-invalid @enderror" min="0" value="{{ old('year') }}" placeholder="e.g., 2020">
                    @error('year')<span class="text-danger">{{ $message }}</span>@enderror
                  </div>

                  <div class="col-md-6">
                    <label class="required-field">Guests hosted till March 2025</label>
                    <input type="number" name="guestno" class="form-control @error('guestno') is-invalid @enderror" min="0" value="{{ old('guestno') }}" placeholder="Number of guests">
                    @error('guestno')<span class="text-danger">{{ $message }}</span>@enderror
                  </div>

                  <div class="col-md-6">
                    <label class="required-field">Total Area (sq.ft)</label>
                    <input type="number" name="area" class="form-control @error('area') is-invalid @enderror" min="0" value="{{ old('area') }}" placeholder="Area in square feet">
                    @error('area')<span class="text-danger">{{ $message }}</span>@enderror
                  </div>

                  <div class="col-md-6">
                    <label class="required-field">Mahabooking Registration Number</label>
                    <input type="number" name="regn" class="form-control @error('regn') is-invalid @enderror" min="0" value="{{ old('regn') }}" placeholder="Registration number">
                    @error('regn')<span class="text-danger">{{ $message }}</span>@enderror
                  </div>
                </div>

                <div class="navigation-buttons">
                  <button type="button" class="btn btn-secondary prev-step" data-prev="2"><i class="bi bi-arrow-left"></i> Previous</button>
                  <button type="button" class="btn btn-primary next-step" data-next="4">Next <i class="bi bi-arrow-right"></i></button>
                </div>
              </div>

              <!-- Step 4: Accommodation Details -->
              <div class="form-step" id="step-4">
                <div class="section-header">C) Details of the Accommodation</div>
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="required-field">Number of Flats for Tourists</label>
                    <input type="number" name="fno" class="form-control @error('fno') is-invalid @enderror" min="0" value="{{ old('fno') }}" placeholder="Number of flats">
                    @error('fno')<span class="text-danger">{{ $message }}</span>@enderror
                  </div>

                  <div class="col-md-6">
                    <label class="required-field">Type of Each Flat (1/2/3/4 BHK)</label>
                    <div class="custom-radio-group">
                      @foreach (['1 BHK','2 BHK','3 BHK','4 BHK'] as $t)
                        <div class="custom-radio">
                          <input type="radio" name="ftype" id="ftype-{{ $t }}" value="{{ $t }}" {{ old('ftype')===$t ? 'checked':'' }}>
                          <label for="ftype-{{ $t }}">{{ $t }}</label>
                        </div>
                      @endforeach
                    </div>
                    @error('ftype')<span class="text-danger">{{ $message }}</span>@enderror
                  </div>

                  <div class="col-md-6">
                    <label class="required-field">Area (sq.ft) of Each Flat</label>
                    <input type="number" name="farea" class="form-control @error('farea') is-invalid @enderror" min="0" value="{{ old('farea') }}" placeholder="Area per flat">
                    @error('farea')<span class="text-danger">{{ $message }}</span>@enderror
                  </div>

                  <div class="col-md-6">
                    <label class="required-field">Attached Toilet in Each Room?</label>
                    <div class="custom-radio-group">
                      <div class="custom-radio">
                        <input type="radio" name="atinfo" id="atinfo-yes" value="Yes" {{ old('atinfo')==='Yes' ? 'checked':'' }}>
                        <label for="atinfo-yes">Yes</label>
                      </div>
                      <div class="custom-radio">
                        <input type="radio" name="atinfo" id="atinfo-no" value="No" {{ old('atinfo')==='No' ? 'checked':'' }}>
                        <label for="atinfo-no">No</label>
                      </div>
                    </div>
                    @error('atinfo')<span class="text-danger">{{ $message }}</span>@enderror
                  </div>

                  <div class="col-md-6">
                    <label class="required-field">Rooms have Dustbins?</label>
                    <div class="custom-radio-group">
                      <div class="custom-radio">
                        <input type="radio" name="dbin" id="dbin-yes" value="Yes" {{ old('dbin')==='Yes' ? 'checked':'' }}>
                        <label for="dbin-yes">Yes</label>
                      </div>
                      <div class="custom-radio">
                        <input type="radio" name="dbin" id="dbin-no" value="No" {{ old('dbin')==='No' ? 'checked':'' }}>
                        <label for="dbin-no">No</label>
                      </div>
                    </div>
                    @error('dbin')<span class="text-danger">{{ $message }}</span>@enderror
                  </div>

                  <div class="col-md-6">
                    <label class="required-field">Accessible by Road?</label>
                    <div class="custom-radio-group">
                      <div class="custom-radio">
                        <input type="radio" name="aroad" id="aroad-yes" value="Yes" {{ old('aroad')==='Yes' ? 'checked':'' }}>
                        <label for="aroad-yes">Yes</label>
                      </div>
                      <div class="custom-radio">
                        <input type="radio" name="aroad" id="aroad-no" value="No" {{ old('aroad')==='No' ? 'checked':'' }}>
                        <label for="aroad-no">No</label>
                      </div>
                    </div>
                    @error('aroad')<span class="text-danger">{{ $message }}</span>@enderror
                  </div>

                  <div class="col-md-6">
                    <label class="required-field">Food Available on Request?</label>
                    <div class="custom-radio-group">
                      <div class="custom-radio">
                        <input type="radio" name="areq" id="areq-yes" value="Yes" {{ old('areq')==='Yes' ? 'checked':'' }}>
                        <label for="areq-yes">Yes</label>
                      </div>
                      <div class="custom-radio">
                        <input type="radio" name="areq" id="areq-no" value="No" {{ old('areq')==='No' ? 'checked':'' }}>
                        <label for="areq-no">No</label>
                      </div>
                    </div>
                    @error('areq')<span class="text-danger">{{ $message }}</span>@enderror
                  </div>

                  <div class="col-md-6">
                    <label class="required-field">Payment via Cash/UPI?</label>
                    <div class="custom-radio-group">
                      <div class="custom-radio">
                        <input type="radio" name="pay" id="pay-yes" value="Yes" {{ old('pay')==='Yes' ? 'checked':'' }}>
                        <label for="pay-yes">Yes</label>
                      </div>
                      <div class="custom-radio">
                        <input type="radio" name="pay" id="pay-no" value="No" {{ old('pay')==='No' ? 'checked':'' }}>
                        <label for="pay-no">No</label>
                      </div>
                    </div>
                    @error('pay')<span class="text-danger">{{ $message }}</span>@enderror
                  </div>
                </div>

                <div class="navigation-buttons">
                  <button type="button" class="btn btn-secondary prev-step" data-prev="3"><i class="bi bi-arrow-left"></i> Previous</button>
                  <button type="button" class="btn btn-primary next-step" data-next="5">Next <i class="bi bi-arrow-right"></i></button>
                </div>
              </div>

              <!-- Step 5: Facilities & Documents -->
              <div class="form-step" id="step-5">
                <div class="section-header">D) Common Facilities</div>
                <div class="row g-3">
                  @php
                    $facilities = [
                      'co' => 'Kitchen','ct' => 'Dining Hall','cth' => 'Garden','cf' => 'Parking',
                      'cfi' => 'EV Charging Station','cs' => 'Children Play Area','cse' => 'Swimming Pool',
                      'ce' => 'Wi-Fi','cb' => 'First Aid Box','cn' => 'Fire Safety Equipment',
                      'cte' => 'Water Purifier / RO','cel' => 'Rainwater Harvesting',
                      'ctw' => 'Use of Solar Power','cthr' => 'Other Renewable Energy',
                    ];
                  @endphp

                  @foreach ($facilities as $name => $label)
                    <div class="col-md-6">
                      <label class="required-field">{{ $label }}</label>
                      <div class="custom-radio-group">
                        <div class="custom-radio">
                          <input type="radio" name="{{ $name }}" id="{{ $name }}-yes" value="Yes" {{ old($name)==='Yes' ? 'checked':'' }}>
                          <label for="{{ $name }}-yes">Yes</label>
                        </div>
                        <div class="custom-radio">
                          <input type="radio" name="{{ $name }}" id="{{ $name }}-no" value="No" {{ old($name,'No')==='No' ? 'checked':'' }}>
                          <label for="{{ $name }}-no">No</label>
                        </div>
                      </div>
                      @error($name)<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                  @endforeach
                </div>

                <div class="section-header mt-4">Required Documents & Signature</div>
                <div class="row g-3 mb-4">
                  <div class="col-md-4">
                    <label class="required-field">Signature</label>
                    <input type="file" class="form-control @error('sign') is-invalid @enderror" name="sign" accept=".jpg,.jpeg,.png,.pdf">
                    @error('sign')<span class="text-danger">{{ $message }}</span>@enderror
                  </div>

                  <div class="col-md-4">
                    <label class="required-field">Name of Applicant</label>
                    <input type="text" class="form-control @error('aname') is-invalid @enderror" name="aname" value="{{ old('aname') }}" placeholder="Applicant name">
                    @error('aname')<span class="text-danger">{{ $message }}</span>@enderror
                  </div>

                  <div class="col-md-4">
                    <label class="required-field">Date</label>
                    <input type="date" class="form-control @error('date') is-invalid @enderror" name="date" value="{{ old('date') }}">
                    @error('date')<span class="text-danger">{{ $message }}</span>@enderror
                  </div>
                </div>

                <div class="section-header">Enclosures (Documents marked * must be submitted)</div>
                <div class="table-responsive">
                  <table class="table table-bordered align-middle">
                    <thead class="table-light">
                      <tr>
                        <th>S.N.</th>
                        <th>Document</th>
                        <th>Upload</th>
                        <th>Preview</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @php
                        $docs = [
                          ['Aadhar Card of the Applicant*','aadharcard', true],
                          ['PAN Card of the Applicant*','pancard', true],
                          ['Business PAN Card (If applicable)','bpancard', false],
                          ['Udyam Aadhar Certificate (If applicable)','uaadharcert', false],
                          ['Business Registration Certificate*','bregcert', true],
                          ['Proof of Ownership of Property*','profown', true],
                          ['Photos of the Property (minimum 5)*','prophoto[]', true, 'multiple' => true, 'min' => 5],
                          ['Character Certificate from Police Station*','charcert', true],
                          ['NOC from Society*','noc', true],
                          ['Building Permission/Completion Certificate*','permicert', true],
                          ['Copy of GRAS Challan*','graschallan', true],
                          ['Undertaking (Duly signed)*','utaking', true],
                          ['Rental Agreement / Management Contract (if applicable)','contract', false],
                        ];
                      @endphp

                      @foreach ($docs as $i => $d)
                        <tr>
                          <td>{{ $i+1 }}</td>
                          <td>{!! $d[0] !!}</td>
                          <td>
                            <input
                              type="file"
                              name="{{ $d[1] }}"
                              class="form-control @error($d[1]) is-invalid @enderror"
                              onchange="showPreview(this)"
                              @if(($d['multiple'] ?? false)) multiple @endif
                              accept=".jpg,.jpeg,.png,.pdf">
                            @error($d[1])<span class="text-danger">{{ $message }}</span>@enderror
                          </td>
                          <td class="preview-col"></td>
                          <td>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteFile(this)">
                              <i class="bi bi-trash"></i>
                            </button>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>

                <div class="navigation-buttons">
                  <button type="button" class="btn btn-secondary prev-step" data-prev="4"><i class="bi bi-arrow-left"></i> Previous</button>
                  <button type="button" class="btn btn-primary next-step" data-next="6">Next <i class="bi bi-arrow-right"></i></button>
                </div>
              </div>

              <!-- Step 6: Review & Submit -->
              <div class="form-step" id="step-6">
                <div class="section-header">Review Your Application</div>

                <div id="formPreview" class="mb-4">
                  <!-- Preview content will be populated by JavaScript -->
                </div>

                <div class="declaration-box">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="declaration" required>
                    <label class="form-check-label" for="declaration">
                      <strong>Declaration:</strong> I hereby declare that all the information provided in this application is true and correct to the best of my knowledge. I understand that any false information may lead to rejection of my application or cancellation of registration.
                    </label>
                  </div>
                </div>

                <div class="navigation-buttons">
                  <button type="button" class="btn btn-secondary prev-step" data-prev="5"><i class="bi bi-arrow-left"></i> Previous</button>
                  <button type="submit" class="btn btn-success" id="submitBtn" disabled>
                    <i class="bi bi-send-fill me-2"></i> Submit Application
                  </button>
                </div>
              </div>

            </form>
            {{-- FORM END --}}

          </div> <!-- card-body -->
        </div> <!-- card -->
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
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="previewModalContent">
        <!-- Preview content will be populated here -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="closePreviewAndSubmit()">Submit Application</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content bg-dark border-0">
        <div class="modal-body p-0 position-relative">
          <button type="button" class="btn btn-light position-absolute end-0 m-2" data-bs-dismiss="modal">
            Close
          </button>
          <img id="imagePreviewModalImg" class="img-fluid w-100" alt="Preview">
        </div>
        <div class="modal-footer bg-dark border-0 justify-content-between">
          <small class="text-white-50" id="imagePreviewModalName"></small>
        </div>
      </div>
    </div>
  </div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  $(document).ready(function() {
    let currentStep = 1;
    const totalSteps = 6;

    // Initialize form validation
    const validator = $("#tourismForm").validate({
      errorClass: "text-danger",
      highlight: function(element) {
        $(element).addClass("is-invalid");
      },
      unhighlight: function(element) {
        $(element).removeClass("is-invalid");
      },
      rules: {
        name: { required: true },
        mno: { required: true, digits: true, minlength: 10, maxlength: 10 },
        email: { required: true, email: true },
        business: { required: true },
        type: { required: true },
        pan: { required: true },
        aadhar: { required: true, digits: true, minlength: 12, maxlength: 12 },
        proof: { required: true },
        prop: { required: true },
        pname: { required: true },
        padd: { required: true },
        pradd: { required: true },
        gc: { required: true },
        ops: { required: true },
        guestno: { required: true, number: true, min: 0 },
        area: { required: true, number: true, min: 0 },
        regn: { required: true, number: true, min: 0 },
        fno: { required: true, number: true, min: 0 },
        ftype: { required: true },
        farea: { required: true, number: true, min: 0 },
        atinfo: { required: true },
        dbin: { required: true },
        aroad: { required: true },
        areq: { required: true },
        pay: { required: true },
        sign: { required: true },
        aname: { required: true },
        date: { required: true }
      },
      messages: {
        name: "Please enter your full name",
        mno: "Please enter a valid 10-digit mobile number",
        email: "Please enter a valid email address",
        business: "Please enter business name",
        type: "Please select business type",
        pan: "Please enter PAN number",
        aadhar: "Please enter 12-digit Aadhar number",
        proof: "Please upload proof of ownership",
        prop: "Please select if property is rented",
        pname: "Please enter property name",
        padd: "Please enter complete address",
        pradd: "Please upload proof of address",
        gc: "Please enter geographic coordinates or Google Maps link",
        ops: "Please select if already operational",
        guestno: "Please enter number of guests",
        area: "Please enter total area",
        regn: "Please enter registration number",
        fno: "Please enter number of flats",
        ftype: "Please select flat type",
        farea: "Please enter flat area",
        atinfo: "Please select toilet availability",
        dbin: "Please select dustbin availability",
        aroad: "Please select road accessibility",
        areq: "Please select food availability",
        pay: "Please select payment method",
        sign: "Please upload signature",
        aname: "Please enter applicant name",
        date: "Please select date"
      }
    });

    // Dynamic field show/hide
    $('input[name="prop"]').on('change', function() {
      if ($(this).val() === 'Yes') {
        $('#opname-field').show();
      } else {
        $('#opname-field').hide();
        $('#opname-input').val('');
      }
    });

    $('input[name="ops"]').on('change', function() {
      if ($(this).val() === 'Yes') {
        $('#year-field').show();
      } else {
        $('#year-field').hide();
        $('#year-input').val('');
      }
    });

    // Step navigation
    $('.next-step').on('click', function() {
      const nextStep = $(this).data('next');
      if (validateStep(currentStep)) {
        showStep(nextStep);
      }
    });

    $('.prev-step').on('click', function() {
      const prevStep = $(this).data('prev');
      showStep(prevStep);
    });

    // Declaration checkbox
    $('#declaration').on('change', function() {
      $('#submitBtn').prop('disabled', !$(this).is(':checked'));
    });

    function showStep(step) {
      $('.form-step').removeClass('active');
      $(`#step-${step}`).addClass('active');

      $('.progress-step').removeClass('active completed');
      $('.progress-step').each(function() {
        const stepNum = $(this).data('step');
        if (stepNum < step) {
          $(this).addClass('completed');
        } else if (stepNum == step) {
          $(this).addClass('active');
        }
      });

      currentStep = step;

      // Update preview when on review step
      if (step === 6) {
        updatePreview();
      }
    }

    function validateStep(step) {
      let isValid = true;
      $(`#step-${step} input, #step-${step} select`).each(function() {
        if (!validator.element(this)) {
          isValid = false;
        }
      });
      return isValid;
    }

    function updatePreview() {
      const formData = new FormData(document.getElementById('tourismForm'));
      let previewHtml = '';

      // Applicant Details
      previewHtml += '<div class="section-header mb-3">Applicant Details</div>';
      previewHtml += `<div class="preview-item"><span class="preview-label">Name:</span> <span class="preview-value">${$('input[name="name"]').val()}</span></div>`;
      previewHtml += `<div class="preview-item"><span class="preview-label">Mobile:</span> <span class="preview-value">${$('input[name="mno"]').val()}</span></div>`;
      previewHtml += `<div class="preview-item"><span class="preview-label">Email:</span> <span class="preview-value">${$('input[name="email"]').val()}</span></div>`;
      previewHtml += `<div class="preview-item"><span class="preview-label">Business:</span> <span class="preview-value">${$('input[name="business"]').val()}</span></div>`;
      previewHtml += `<div class="preview-item"><span class="preview-label">Business Type:</span> <span class="preview-value">${$('select[name="type"]').val()}</span></div>`;

      // Property Details
      previewHtml += '<div class="section-header mb-3 mt-4">Property Details</div>';
      previewHtml += `<div class="preview-item"><span class="preview-label">Property Name:</span> <span class="preview-value">${$('input[name="pname"]').val()}</span></div>`;
      previewHtml += `<div class="preview-item"><span class="preview-label">Address:</span> <span class="preview-value">${$('input[name="padd"]').val()}</span></div>`;
      previewHtml += `<div class="preview-item"><span class="preview-label">Coordinates:</span> <span class="preview-value">${$('input[name="gc"]').val()}</span></div>`;
      previewHtml += `<div class="preview-item"><span class="preview-label">Operational:</span> <span class="preview-value">${$('input[name="ops"]:checked').val()}</span></div>`;

      // Accommodation Details
      previewHtml += '<div class="section-header mb-3 mt-4">Accommodation Details</div>';
      previewHtml += `<div class="preview-item"><span class="preview-label">Number of Flats:</span> <span class="preview-value">${$('input[name="fno"]').val()}</span></div>`;
      previewHtml += `<div class="preview-item"><span class="preview-label">Flat Type:</span> <span class="preview-value">${$('input[name="ftype"]:checked').val()}</span></div>`;
      previewHtml += `<div class="preview-item"><span class="preview-label">Flat Area:</span> <span class="preview-value">${$('input[name="farea"]').val()} sq.ft</span></div>`;

      $('#formPreview').html(previewHtml);
    }

    // File preview functions
    function showPreview(input) {
      const previewCol = input.closest('tr').querySelector('.preview-col');
      previewCol.innerHTML = "";
      Array.from(input.files || []).forEach(file => {
        if (file.type.match('image.*')) {
          const reader = new FileReader();
          reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.width = "47px";
            img.style.height = "47px";
            img.style.objectFit = "cover";
            img.style.border = "1px solid #ddd";
            img.style.marginRight = "5px";
            img.style.marginTop = "5px";
            previewCol.appendChild(img);
          };
          reader.readAsDataURL(file);
        }
      });
    }

    function deleteFile(btn) {
      const row = btn.closest('tr');
      const input = row.querySelector('input[type="file"]');
      if (input) input.value = "";
      const preview = row.querySelector('.preview-col');
      if (preview) preview.innerHTML = "";
    }

    // Form submission
    $('#tourismForm').on('submit', function(e) {
      if (!validateStep(6) || !$('#declaration').is(':checked')) {
        e.preventDefault();
        alert('Please complete all required fields and accept the declaration.');
      }
    });
  });
</script>
<script>
    function openImageModal(src, name) {
      const imgEl = document.getElementById('imagePreviewModalImg');
      const nameEl = document.getElementById('imagePreviewModalName');
      imgEl.src = src;
      nameEl.textContent = name || '';
      const modal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
      modal.show();
    }

    /* Robust image check: use MIME OR file extension */
    function isImageFile(file){
      return /^image\//i.test(file.type) || /\.(jpg|jpeg|png|gif|webp)$/i.test(file.name || '');
    }

    function showPreview(input) {
      const previewCol = input.closest('tr').querySelector('.preview-col');
      previewCol.innerHTML = "";

      const files = Array.from(input.files || []);
      files.forEach(file => {
        if (isImageFile(file)) {
          const reader = new FileReader();
          reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.alt = file.name;
            img.title = file.name;
            img.style.width = "47px";
            img.style.height = "47px";
            img.style.objectFit = "cover";
            img.style.border = "1px solid #ddd";
            img.style.marginRight = "5px";
            img.style.marginTop = "5px";
            img.style.cursor = "zoom-in";
            img.addEventListener('click', () => openImageModal(img.src, file.name));
            previewCol.appendChild(img);
          };
          reader.readAsDataURL(file);
        } else {
          // Show a badge for non-images (e.g., PDF)
          const badge = document.createElement('span');
          badge.textContent = file.name;
          badge.className = "badge text-bg-secondary me-2 mt-2 d-inline-block";
          previewCol.appendChild(badge);
        }
      });
    }
</script>
<script>
    // Auto attach to all file inputs with class 'file-preview-input'
    document.querySelectorAll('.file-preview-input').forEach(input => {
      input.addEventListener('change', function() {
        const previewId = this.id + 'Preview';
        const previewBox = document.getElementById(previewId);
        previewBox.innerHTML = ''; // clear old previews

        Array.from(this.files).forEach(file => {
          const reader = new FileReader();

          reader.onload = e => {
            // For images
            if (file.type.startsWith('image/')) {
              const item = document.createElement('div');
              item.classList.add('preview-item');

              item.innerHTML = `
                <img src="${e.target.result}" alt="${file.name}">
                <button type="button" class="btn btn-danger btn-sm remove-btn">&times;</button>
              `;

              // Add remove event
              item.querySelector('.remove-btn').addEventListener('click', () => {
                item.remove();
                // If all removed, clear input
                if (!previewBox.querySelectorAll('.preview-item').length) input.value = '';
              });

              previewBox.appendChild(item);
            } else {
              // For PDFs or others
              const link = document.createElement('a');
              link.textContent = file.name;
              link.href = '#';
              link.className = 'badge bg-secondary text-decoration-none';
              previewBox.appendChild(link);
            }
          };

          reader.readAsDataURL(file);
        });
      });
    });

    // Manually clear image preview (for existing uploaded images)
    function removeImage(inputId, previewId) {
      document.getElementById(inputId).value = '';
      document.getElementById(previewId).innerHTML = '';
    }
  </script>

<script>
    $(document).on('change', '.file-input', function () {
        const files = this.files;
        const $container = $(this).closest('div').find('.image-container');
        $container.empty(); 

        if (!files.length) return;

        Array.from(files).forEach(file => {
            if (!file.type.startsWith('image/')) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                const imgBox = `
                    <div class="col-12 col-md-12 col-lg-12 position-relative">
                        <img src="${e.target.result}"
                             class="img-preview rounded border"
                             alt="Preview"
                             style="width:100%; height:150px; object-fit:cover; border:1px solid #ccc;">
                        <button type="button"
                                class="btn btn-sm btn-danger remove-img position-absolute top-0 end-0 m-1"
                                title="Remove">&times;</button>
                    </div>`;
                $container.append(imgBox);
            };
            reader.readAsDataURL(file);
        });
    });

    // Remove clicked image preview
    $(document).on('click', '.remove-img', function () {
        const $container = $(this).closest('.image-container');
        const $input = $container.closest('div').find('.file-input');
        $input.val(''); // reset file input
        $container.empty(); // clear previews
    });
    </script>

@endpush
