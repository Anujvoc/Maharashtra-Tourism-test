@extends('frontend.layouts2.master')

@section('title', 'Application Form - Tourist Apartment Registration')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
@push('styles')

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

  @media (max-width: 768px) {
    .card-body {
      padding: 20px;
    }

    .custom-radio-group {
      flex-direction: column;
    }
  }
</style>
<style>
    /* add this to your existing styles */
    .progress-step.completed .step-circle{
      background:#28a745;
      color:#fff;
    }
    .progress-step.completed .step-label{
      color:#28a745;
      font-weight:600;
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
                <div class="progress-step active">
                  <div class="step-circle">1</div>
                  <div class="step-label">Applicant</div>
                </div>
                <div class="progress-step">
                  <div class="step-circle">2</div>
                  <div class="step-label">Property</div>
                </div>
                <div class="progress-step">
                  <div class="step-circle">3</div>
                  <div class="step-label">Accommodation</div>
                </div>
                <div class="progress-step">
                  <div class="step-circle">4</div>
                  <div class="step-label">Facilities</div>
                </div>
                <div class="progress-step">
                  <div class="step-circle">5</div>
                  <div class="step-label">Documents</div>
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

                {{-- A) Applicant --}}
                <div class="form-section">
                  <div class="section-header">A) Details of the Applicant</div>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="required-field">Name of the Applicant (Owner of the Unit)</label>
                      <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Enter full name">
                      @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="col-md-6">
                      <label class="required-field">Telephone/Mobile No of Applicant</label>
                      <input type="number" name="mno" class="form-control @error('mno') is-invalid @enderror" min="0" value="{{ old('mno') }}" placeholder="10-digit mobile number">
                      @error('mno')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="col-md-6">
                      <label class="required-field">E-Mail ID of Applicant</label>
                      <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Enter email address">
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
                      <input type="number" name="aadhar" class="form-control @error('aadhar') is-invalid @enderror" min="0" value="{{ old('aadhar') }}" placeholder="12-digit Aadhar">
                      @error('aadhar')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="col-md-6">
                      <label>Udyam Aadhar Number (If applicable)</label>
                      <input type="text" name="uaadhar" class="form-control @error('uaadhar') is-invalid @enderror" value="{{ old('uaadhar') }}" placeholder="Udyam Aadhar">
                      @error('uaadhar')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="col-md-6">
                      <label class="required-field">Proof of Ownership of Property</label>
                      <input type="file" name="proof" class="form-control @error('proof') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
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
                  </div>
                </div>

                {{-- B) Property --}}
                <div class="form-section">
                  <div class="section-header">B) Details of the Property</div>
                  <div class="row g-3">
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
                      <label class="required-field">Proof of Address</label>
                      <input type="file" name="pradd" id="pradd" class="form-control @error('pradd') is-invalid @enderror" accept=".jpg,.jpeg,.png">
                      @error('pradd')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="col-md-6">
                      <label class="required-field">Geographic Coordinates / Google Map Link</label>
                      <input type="text" name="gc" class="form-control @error('gc') is-invalid @enderror" value="{{ old('gc') }}" placeholder="e.g., 19.0760, 72.8777 or Google Maps link">
                      @error('gc')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

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
                </div>

                {{-- C) Accommodation --}}
                <div class="form-section">
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
                </div>

                {{-- D) Common Facilities --}}
                <div class="form-section">
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
                </div>

                {{-- Enclosures, Signature etc. --}}
                <div class="form-section">
                  <div class="section-header">Required Documents & Signature</div>

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
                </div>

                <div class="text-center mt-4">
                  <button type="submit" class="btn btn-success px-5 py-2">
                    <i class="bi bi-send-fill me-2"></i> Submit Application
                  </button>
                </div>

              </form>
              {{-- FORM END --}}

            </div> <!-- card-body -->
          </div> <!-- card -->
        </div>
      </div>
    </div>
  </section>
@endsection

<!-- Image Preview Modal -->
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

  @push('scripts')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

  <script>
    /* ---------- Modal helpers (unchanged behavior) ---------- */
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

    function deleteFile(btn) {
      const row = btn.closest('tr');
      const input = row.querySelector('input[type="file"]');
      if (input) input.value = "";
      const preview = row.querySelector('.preview-col');
      if (preview) preview.innerHTML = "";
      // trigger re-validation after clearing
      $(input).trigger('change');
    }

    $(function () {
      /* ---------- Custom validators (no additional-methods needed) ---------- */
      $.validator.addMethod("lettersonly", function (v, el) {
        return this.optional(el) || /^[a-zA-Z\s]+$/.test(v || "");
      }, "Letters only");

      $.validator.addMethod("validPAN", function (v, el) {
        return this.optional(el) || /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/.test((v||"").toUpperCase());
      }, "Invalid PAN (e.g., ABCDE1234F)");

      $.validator.addMethod("validAadhar", function (v, el) {
        return this.optional(el) || /^\d{12}$/.test((v||"").replace(/\s+/g,''));
      }, "Enter 12-digit Aadhaar");

      $.validator.addMethod("coordsOrUrl", function(v, el){
        if (this.optional(el)) return true;
        v = (v||"").trim();
        const latlng = /^-?\d{1,2}\.\d{3,},?\s*-?\d{1,3}\.\d{3,}$/; // loose geocoord
        const gmap  = /(google\.[a-z.]+\/maps|goo\.gl\/maps)/i;
        return latlng.test(v) || gmap.test(v) || /^https?:\/\//i.test(v);
      }, "Enter lat,long (e.g., 19.0760, 72.8777) or a URL");

      $.validator.addMethod("minFiles", function(_, el, param){
        return (el.files?.length || 0) >= (param||0);
      }, "Select at least {0} files");

      // file extension validator (since we didn't include additional-methods.js)
      $.validator.addMethod("fileExt", function(value, el, param){
        if (this.optional(el) || !(el.files && el.files.length)) return true;
        const exts = (param || "jpg|jpeg|png|pdf").toLowerCase().split("|");
        return Array.from(el.files).every(f => {
          const name = (f.name || "").toLowerCase();
          return exts.some(ext => name.endsWith("." + ext));
        });
      }, function(param){ return "Allowed file types: " + (param || "jpg|jpeg|png|pdf"); });

      /* ---------- Dynamic show/hide ---------- */
      $('input[name="prop"]').on('change', function(){
        if ($(this).val()==='Yes') $('#opname-field').show(); else { $('#opname-field').hide(); $('#opname-input').val(''); }
        $('#opname-input, input[name="agreement"]').valid(); // recheck dependent fields
        updateProgress(); // may influence step completion
      });

      $('input[name="ops"]').on('change', function(){
        if ($(this).val()==='Yes') $('#year-field').show(); else { $('#year-field').hide(); $('#year-input').val(''); }
        $('#year-input').valid();
        updateProgress();
      });

      /* ---------- Form validation ---------- */
      const validator = $("#tourismForm").validate({
        errorClass: "text-danger",
        highlight: el => $(el).addClass("is-invalid"),
        unhighlight: el => $(el).removeClass("is-invalid"),

        // make it "live" while typing/changing
        onkeyup: function(element){ $(element).valid(); updateProgress(); },
        onfocusout: function(element){ $(element).valid(); updateProgress(); },
        onclick: function(element){ $(element).valid(); updateProgress(); },

        rules: {
          name:{required:true,lettersonly:true},
          mno:{required:true,digits:true,minlength:10,maxlength:10},
          email:{required:true,email:true},
          business:{required:true,lettersonly:true},
          type:{required:true},
          pan:{required:true,validPAN:true},
          bpan:{validPAN:true},
          aadhar:{required:true,validAadhar:true},
          uaadhar:{alphanumeric:true}, // harmless if not present; ignore if you didn't add this field
          proof:{required:true,fileExt:"pdf|jpg|jpeg|png"},

          prop:{required:true},
          agreement:{
            required:function(){ return $('input[name="prop"]:checked').val()==='Yes'; },
            fileExt:"pdf|jpg|jpeg|png"
          },
          opname:{ required:function(){ return $('input[name="prop"]:checked').val()==='Yes'; }, lettersonly:true },

          pname:{required:true},
          padd:{required:true,minlength:10},
          pradd:{required:true,fileExt:"jpg|jpeg|png"},
          gc:{required:true,coordsOrUrl:true},

          ops:{required:true},
          year:{ required:function(){ return $('input[name="ops"]:checked').val()==='Yes'; }, number:true, minlength:4, maxlength:4 },

          guestno:{required:true,number:true,min:0},
          area:{required:true,number:true,min:0},
          regn:{required:true,number:true,min:0},

          fno:{required:true,number:true,min:0},
          ftype:{required:true},
          farea:{required:true,number:true,min:0},
          atinfo:{required:true},
          dbin:{required:true},
          aroad:{required:true},
          areq:{required:true},
          pay:{required:true},

          // facilities (Yes/No)
          co:{required:true}, ct:{required:true}, cth:{required:true}, cf:{required:true}, cfi:{required:true},
          cs:{required:true}, cse:{required:true}, ce:{required:true}, cb:{required:true}, cn:{required:true},
          cte:{required:true}, cel:{required:true}, ctw:{required:true}, cthr:{required:true},

          // documents
          aadharcard:{required:true,fileExt:"jpg|jpeg|png|pdf"},
          pancard:{required:true,fileExt:"jpg|jpeg|png|pdf"},
          bpancard:{fileExt:"jpg|jpeg|png|pdf"},
          uaadharcert:{fileExt:"jpg|jpeg|png|pdf"},
          bregcert:{required:true,fileExt:"jpg|jpeg|png|pdf"},
          profown:{required:true,fileExt:"jpg|jpeg|png|pdf"},
          charcert:{required:true,fileExt:"jpg|jpeg|png|pdf"},
          noc:{required:true,fileExt:"jpg|jpeg|png|pdf"},
          permicert:{required:true,fileExt:"jpg|jpeg|png|pdf"},
          graschallan:{required:true,fileExt:"jpg|jpeg|png|pdf"},
          utaking:{required:true,fileExt:"jpg|jpeg|png|pdf"},
          "prophoto[]":{required:true,minFiles:5,fileExt:"jpg|jpeg|png|pdf"},

          sign:{required:true,fileExt:"jpg|jpeg|png|pdf"},
          aname:{required:true,minlength:3,lettersonly:true},
          date:{required:true,date:true},
        },

        // update progress after each overall validation pass too
        invalidHandler: function(){ updateProgress(); },
        submitHandler: function(form){ updateProgress(); form.submit(); }
      });

      /* ---------- Progress indicator logic ---------- */
      // Sections are in order in your markup:
      // 0: Applicant, 1: Property, 2: Accommodation, 3: Facilities, 4: Documents
      const $steps = $('.progress-step');
      const $sections = $('.form-section');

      function sectionValid(idx){
        let ok = true;
        // validate only inputs inside the section (quietly mark errors as needed)
        $sections.eq(idx).find(':input[name]').each(function(){
          // call .valid() (runs element validation without re-showing messages beyond normal behavior)
          if(!$(this).valid()){ ok = false; }
        });
        return ok;
      }

      function updateProgress(){
        // compute each section’s validity and mark classes
        $steps.removeClass('active completed');
        $steps.each(function(i){
          const ok = sectionValid(i);
          if(ok){ $(this).addClass('completed'); }
        });

        // set the "current" active step as the first non-completed one, else last
        let current = $steps.toArray().findIndex(s => !$(s).hasClass('completed'));
        if(current === -1) current = $steps.length - 1;
        $steps.eq(current).addClass('active');
      }

      // run once at load
      updateProgress();

      $('#tourismForm').on('input change', ':input', function(){

        updateProgress();
      });

    });
  </script>
  @endpush

