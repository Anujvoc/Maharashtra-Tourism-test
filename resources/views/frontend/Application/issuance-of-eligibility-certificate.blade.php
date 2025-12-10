@extends('frontend.layouts2.master')
@section('title', 'Eligibility Certificate Registration Form')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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
</style>
@endpush

@section('content')
<section class="section">
    <div class="section-header d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-3">
        <h1 class="mb-2 mb-md-0">
            <i class="fa-solid fa-route" style="color:#ff6600;"></i>
            Application for {{ $application_form->name ?? 'Eligibility Certificate Registration' }}
        </h1>

        <a href="{{ url()->previous() }}"
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

                        <form id="eligibilityForm"
                               action="{{ route('eligibility-registrations.store') }}"
                              method="POST"
                              enctype="multipart/form-data"
                              novalidate>
                            @csrf
                            <input type="hidden" name="application_form_id" value="{{ $id ?? '' }}">

                            <!-- General Details -->
                            <h5 class="fw-semibold mt-4 mb-3 text-primary">
                                <i class="fa-solid fa-circle-info form-icon"></i>
                                General Details
                            </h5>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label required">
                                        <i class="fa-solid fa-user form-icon"></i>
                                        1. Name of Applicant/Tourism Unit
                                    </label>
                                    <input type="text"
                                           name="applicant_name"
                                           class="form-control @error('applicant_name') is-invalid @enderror"
                                           value="{{ old('applicant_name') }}" required>
                                    @error('applicant_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label required">
                                        <i class="fa-solid fa-file-signature form-icon"></i>
                                        2. Provisional Certificate Number
                                    </label>
                                    <input type="text"
                                           name="provisional_number"
                                           class="form-control @error('provisional_number') is-invalid @enderror"
                                           value="{{ old('provisional_number') }}" required>
                                    @error('provisional_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mt-2">
                                    <label class="form-label required">
                                        <i class="fa-solid fa-receipt form-icon"></i>
                                        3. GST Number
                                    </label>
                                    <input type="text"
                                           name="gst_number"
                                           class="form-control @error('gst_number') is-invalid @enderror"
                                           maxlength="15"
                                           value="{{ old('gst_number') }}" required>
                                    @error('gst_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

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
                                        @error('region_id')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 ">
                                    <div class="form-group mb-3">
                                        <label for="district_id" class="form-label required">
                                            <i class="bi bi-geo form-icon"></i> Select District
                                        </label>
                                        <select id="district_id" name="district_id" class="form-control district_id @error('district_id') is-invalid @enderror">
                                            <option value="" selected disabled>Select District</option>

                                        </select>
                                        @error('district_id')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>



                            <!-- Entrepreneur Profile -->
                            <div class="mt-4">
                                <label class="form-label fw-semibold">
                                    <i class="fa-solid fa-users form-icon"></i>
                                    4. Entrepreneur’s Profile (Of All Partner/Directors of the Organization)
                                    <span class="text-danger">*</span>
                                </label>

                                <table class="table table-bordered mt-2" id="entrepreneurTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Designation</th>
                                            <th>Ownership %</th>
                                            <th>Gender</th>
                                            <th>Age</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input type="text"
                                                       name="entrepreneurs[0][name]"
                                                       class="form-control">
                                            </td>
                                            <td>
                                                <input type="text"
                                                       name="entrepreneurs[0][designation]"
                                                       class="form-control">
                                            </td>
                                            <td>
                                                <input type="number"
                                                       name="entrepreneurs[0][ownership]"
                                                       class="form-control"
                                                       min="0" max="100">
                                            </td>
                                            <td>
                                                <select name="entrepreneurs[0][gender]" class="form-control">
                                                    <option value="">Select</option>
                                                    <option>Male</option>
                                                    <option>Female</option>
                                                    <option>Other</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number"
                                                       name="entrepreneurs[0][age]"
                                                       class="form-control">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm removeRow">X</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-primary btn-sm" id="addEntrepreneur">+ Add Row</button>
                                <div id="entrepreneur_error" class="text-danger small mt-1 d-none">
                                    At least one entrepreneur row must be completely filled.
                                </div>
                            </div>

                            <!-- Project Description -->
                            <div class="mt-5">
                                <label class="form-label fw-semibold required">
                                    <i class="fa-solid fa-file-lines form-icon"></i>
                                    Project Description
                                </label>
                                <textarea name="project_description"
                                          class="form-control @error('project_description') is-invalid @enderror"
                                          rows="4"
                                          required>{{ old('project_description') }}</textarea>
                                @error('project_description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row g-3 mt-4">
                                <div class="col-md-6">
                                    <label class="form-label required">
                                        <i class="fa-solid fa-calendar-day form-icon"></i>
                                        5. Date of Commercial Commencement
                                    </label>
                                    <input type="date"
                                           name="commencement_date"
                                           class="form-control @error('commencement_date') is-invalid @enderror"
                                           value="{{ old('commencement_date') }}" required>
                                    @error('commencement_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label required">
                                        <i class="fa-solid fa-gear form-icon"></i>
                                        6. Details of Operations
                                    </label>
                                    <select class="form-control @error('operation_details') is-invalid @enderror"
                                            name="operation_details" required>
                                        <option value="" selected disabled>Select</option>
                                        <option value="Owner" {{ old('operation_details')=='Owner' ? 'selected':'' }}>Owner</option>
                                        <option value="Third Party" {{ old('operation_details')=='Third Party' ? 'selected':'' }}>Third Party</option>
                                    </select>
                                    @error('operation_details')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- ============================= -->
                            <!-- PROJECT DETAILS – FULL TABLE -->
                            <!-- ============================= -->
                            <h5 class="fw-semibold mt-5 mb-3 text-primary">Project Details</h5>

                            <div class="table-responsive">
                                <table class="table text-center align-middle"
                                    style="width:100%; border:2px solid #000; border-collapse:collapse;">
                                    <thead style="background-color:#f8f9fa;">
                                        <tr>
                                            <th style="border:2px solid #000; width:20%;">Component</th>
                                            <th style="border:2px solid #000; width:25%;">Eligible Capital Investment</th>
                                            <th style="border:2px solid #000; width:20%;">Cost Component</th>
                                            <th style="border:2px solid #000; width:20%;">Asset Age / Residual Age (If applicable)</th>
                                            <th style="border:2px solid #000; width:18%;">Ownership Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            use Illuminate\Support\Str;
                                        @endphp
                                        <!-- Capital Cost of the Project (₹ in Lakh) covering 9 rows -->
                                        <tr>
                                            <td style="border:2px solid #000; text-align:left; font-weight:600;" rowspan="9">
                                                Capital Cost of the Project (₹ in Lakh)
                                            </td>
                                            <td style="border:2px solid #000; text-align:left;">Structures and Buildings; Trees</td>
                                            <td style="border:2px solid #000;">
                                                <input type="text" name="cost_component[structures_buildings]" class="form-control"
                                                    placeholder="Enter Cost Component">
                                            </td>
                                            <td style="border:2px solid #000;">
                                                <input type="text" name="asset_age[structures_buildings]" class="form-control"
                                                    placeholder="Enter Asset Age / Residual Age">
                                            </td>
                                            <td style="border:2px solid #000; text-align:left;">
                                                @foreach(['Owned','Leased','Not Applicable'] as $val)
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        name="ownership[structures_buildings][]"
                                                        value="{{ $val }}" id="structures_{{ Str::slug($val) }}">
                                                    <label class="form-check-label" for="structures_{{ Str::slug($val) }}">{{ $val }}</label>
                                                </div>
                                                @endforeach
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="border:2px solid #000; text-align:left;">Indigenous and Imported Machinery and Equipment</td>
                                            <td style="border:2px solid #000;">
                                                <input type="text" name="cost_component[machinery_equipment]" class="form-control"
                                                    placeholder="Enter Cost Component">
                                            </td>
                                            <td style="border:2px solid #000;">
                                                <input type="text" name="asset_age[machinery_equipment]" class="form-control"
                                                    placeholder="Enter Asset Age / Residual Age">
                                            </td>
                                            <td style="border:2px solid #000; text-align:left;">
                                                @foreach(['Owned','Leased','Not Applicable'] as $val)
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        name="ownership[machinery_equipment][]"
                                                        value="{{ $val }}" id="machinery_{{ Str::slug($val) }}">
                                                    <label class="form-check-label" for="machinery_{{ Str::slug($val) }}">{{ $val }}</label>
                                                </div>
                                                @endforeach
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="border:2px solid #000; text-align:left;">Material Handling Equipment</td>
                                            <td style="border:2px solid #000;">
                                                <input type="text" name="cost_component[material_handling]" class="form-control"
                                                    placeholder="Enter Cost Component">
                                            </td>
                                            <td style="border:2px solid #000;">
                                                <input type="text" name="asset_age[material_handling]" class="form-control"
                                                    placeholder="Enter Asset Age / Residual Age">
                                            </td>
                                            <td style="border:2px solid #000; text-align:left;">
                                                @foreach(['Owned','Leased','Not Applicable'] as $val)
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        name="ownership[material_handling][]"
                                                        value="{{ $val }}" id="material_{{ Str::slug($val) }}">
                                                    <label class="form-check-label" for="material_{{ Str::slug($val) }}">{{ $val }}</label>
                                                </div>
                                                @endforeach
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="border:2px solid #000; text-align:left;">Mechanical, Electrical and Plumbing Installations</td>
                                            <td style="border:2px solid #000;">
                                                <input type="text" name="cost_component[mep_installations]" class="form-control"
                                                    placeholder="Enter Cost Component">
                                            </td>
                                            <td style="border:2px solid #000;">
                                                <input type="text" name="asset_age[mep_installations]" class="form-control"
                                                    placeholder="Enter Asset Age / Residual Age">
                                            </td>
                                            <td style="border:2px solid #000; text-align:left;">
                                                @foreach(['Owned','Leased','Not Applicable'] as $val)
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        name="ownership[mep_installations][]"
                                                        value="{{ $val }}" id="mep_{{ Str::slug($val) }}">
                                                    <label class="form-check-label" for="mep_{{ Str::slug($val) }}">{{ $val }}</label>
                                                </div>
                                                @endforeach
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="border:2px solid #000; text-align:left;">Fixtures, Furniture’s and Fittings</td>
                                            <td style="border:2px solid #000;">
                                                <input type="text" name="cost_component[fixtures_furniture]" class="form-control"
                                                    placeholder="Enter Cost Component">
                                            </td>
                                            <td style="border:2px solid #000;">
                                                <input type="text" name="asset_age[fixtures_furniture]" class="form-control"
                                                    placeholder="Enter Asset Age / Residual Age">
                                            </td>
                                            <td style="border:2px solid #000; text-align:left;">
                                                @foreach(['Owned','Leased','Not Applicable'] as $val)
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        name="ownership[fixtures_furniture][]"
                                                        value="{{ $val }}" id="fixtures_{{ Str::slug($val) }}">
                                                    <label class="form-check-label" for="fixtures_{{ Str::slug($val) }}">{{ $val }}</label>
                                                </div>
                                                @endforeach
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="border:2px solid #000; text-align:left;">Waste Treatment Facilities</td>
                                            <td style="border:2px solid #000;">
                                                <input type="text" name="cost_component[waste_treatment]" class="form-control"
                                                    placeholder="Enter Cost Component">
                                            </td>
                                            <td style="border:2px solid #000;">
                                                <input type="text" name="asset_age[waste_treatment]" class="form-control"
                                                    placeholder="Enter Asset Age / Residual Age">
                                            </td>
                                            <td style="border:2px solid #000; text-align:left;">
                                                @foreach(['Owned','Leased','Not Applicable'] as $val)
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        name="ownership[waste_treatment][]"
                                                        value="{{ $val }}" id="waste_{{ Str::slug($val) }}">
                                                    <label class="form-check-label" for="waste_{{ Str::slug($val) }}">{{ $val }}</label>
                                                </div>
                                                @endforeach
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="border:2px solid #000; text-align:left;">Transformer Generator</td>
                                            <td style="border:2px solid #000;">
                                                <input type="text" name="cost_component[transformer_generator]" class="form-control"
                                                    placeholder="Enter Cost Component">
                                            </td>
                                            <td style="border:2px solid #000;">
                                                <input type="text" name="asset_age[transformer_generator]" class="form-control"
                                                    placeholder="Enter Asset Age / Residual Age">
                                            </td>
                                            <td style="border:2px solid #000; text-align:left;">
                                                @foreach(['Owned','Leased','Not Applicable'] as $val)
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        name="ownership[transformer_generator][]"
                                                        value="{{ $val }}" id="tg_{{ Str::slug($val) }}">
                                                    <label class="form-check-label" for="tg_{{ Str::slug($val) }}">{{ $val }}</label>
                                                </div>
                                                @endforeach
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="border:2px solid #000; text-align:left;">Captive Power Plants / Renewable Energy Sources</td>
                                            <td style="border:2px solid #000;">
                                                <input type="text" name="cost_component[captive_power]" class="form-control"
                                                    placeholder="Enter Cost Component">
                                            </td>
                                            <td style="border:2px solid #000;">
                                                <input type="text" name="asset_age[captive_power]" class="form-control"
                                                    placeholder="Enter Asset Age / Residual Age">
                                            </td>
                                            <td style="border:2px solid #000; text-align:left;">
                                                @foreach(['Owned','Leased','Not Applicable'] as $val)
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        name="ownership[captive_power][]"
                                                        value="{{ $val }}" id="captive_{{ Str::slug($val) }}">
                                                    <label class="form-check-label" for="captive_{{ Str::slug($val) }}">{{ $val }}</label>
                                                </div>
                                                @endforeach
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="border:2px solid #000; text-align:left;">Utility and Installation Charges</td>
                                            <td style="border:2px solid #000;">
                                                <input type="text" name="cost_component[utility_installation]" class="form-control"
                                                    placeholder="Enter Cost Component">
                                            </td>
                                            <td style="border:2px solid #000;">
                                                <input type="text" name="asset_age[utility_installation]" class="form-control"
                                                    placeholder="Enter Asset Age / Residual Age">
                                            </td>
                                            <td style="border:2px solid #000; text-align:left;">
                                                @foreach(['Owned','Leased','Not Applicable'] as $val)
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        name="ownership[utility_installation][]"
                                                        value="{{ $val }}" id="utility_{{ Str::slug($val) }}">
                                                    <label class="form-check-label" for="utility_{{ Str::slug($val) }}">{{ $val }}</label>
                                                </div>
                                                @endforeach
                                            </td>
                                        </tr>

                                        <!-- Other Investments (2-row span) -->
                                        <tr>
                                            <td style="border:2px solid #000; text-align:left; font-weight:600;" rowspan="2">
                                                Other Investments
                                            </td>
                                            <td style="border:2px solid #000; text-align:left;">Quality Related Investments</td>
                                            <td style="border:2px solid #000;">
                                                <input type="text" name="cost_component[quality_investments]" class="form-control"
                                                    placeholder="Enter Cost Component">
                                            </td>
                                            <td style="border:2px solid #000;">
                                                <input type="text" name="asset_age[quality_investments]" class="form-control"
                                                    placeholder="Enter Asset Age / Residual Age">
                                            </td>
                                            <td style="border:2px solid #000; text-align:left;">
                                                @foreach(['Owned','Leased','Not Applicable'] as $val)
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        name="ownership[quality_investments][]"
                                                        value="{{ $val }}" id="quality_{{ Str::slug($val) }}">
                                                    <label class="form-check-label" for="quality_{{ Str::slug($val) }}">{{ $val }}</label>
                                                </div>
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border:2px solid #000; text-align:left;">Sustainability Initiatives</td>
                                            <td style="border:2px solid #000;">
                                                <input type="text" name="cost_component[sustainability_initiatives]"
                                                    class="form-control" placeholder="Enter Cost Component">
                                            </td>
                                            <td style="border:2px solid #000;">
                                                <input type="text" name="asset_age[sustainability_initiatives]" class="form-control"
                                                    placeholder="Enter Asset Age / Residual Age">
                                            </td>
                                            <td style="border:2px solid #000; text-align:left;">
                                                @foreach(['Owned','Leased','Not Applicable'] as $val)
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        name="ownership[sustainability_initiatives][]"
                                                        value="{{ $val }}" id="sustain_{{ Str::slug($val) }}">
                                                    <label class="form-check-label" for="sustain_{{ Str::slug($val) }}">{{ $val }}</label>
                                                </div>
                                                @endforeach
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- ============================= -->
                            <!-- ENCLOSURES – FULL TABLE -->
                            <!-- ============================= -->
                            <h5 class="fw-semibold mt-5 mb-3 text-primary">Enclosures</h5>
                            <p class="fw-semibold mb-2">Tick mark necessary documents enclosed with the application form</p>

                            <div class="table-responsive">
                                <table class="table text-center align-middle"
                                    style="width:100%; border:2px solid #000; border-collapse:collapse;">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="border:2px solid #000; width:5%;">Select</th>
                                            <th style="border:2px solid #000; width:30%;">Document Type</th>
                                            <th style="border:2px solid #000; width:15%;">Doc No.</th>
                                            <th style="border:2px solid #000; width:15%;">Date of Issue</th>
                                            <th style="border:2px solid #000; width:15%;">Upload File</th>
                                            <th style="border:2px solid #000; width:20%;">Preview</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Row 1 -->
                                        <tr>
                                            <td style="border:2px solid #000;">
                                                <input type="checkbox" class="form-check-input enclosure-check">
                                            </td>
                                            <td style="border:2px solid #000; text-align:left;">
                                                Travel for LiFE Certificate
                                                <input type="hidden" name="enclosures[travel_life][label]" value="Travel for LiFE Certificate">
                                            </td>
                                            <td style="border:2px solid #000;">
                                                <input type="text" name="enclosures[travel_life][doc_no]"
                                                       class="form-control enclosure-docno" placeholder="Enter Doc No" disabled>
                                            </td>
                                            <td style="border:2px solid #000;">
                                                <input type="date" name="enclosures[travel_life][issue_date]"
                                                       class="form-control enclosure-date" disabled>
                                            </td>
                                            <td style="border:2px solid #000;">
                                                <input type="file" name="enclosures[travel_life][file]"
                                                       class="form-control enclosure-file" accept=".pdf,.jpg,.png,.jpeg" disabled>
                                            </td>
                                            <td style="border:2px solid #000;">
                                                <div class="enc-preview"></div>
                                                <button type="button" class="btn btn-link text-danger enc-remove d-none">
                                                    <i class="fa-solid fa-circle-xmark"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Row 2 (Sustainability) -->
                                        <tr>
                                            <td style="border:2px solid #000;">
                                                <input type="checkbox" id="sustainabilityCheckbox" class="form-check-input">
                                            </td>
                                            <td colspan="5" style="border:2px solid #000; text-align:left;">
                                                Bills of Sustainability Investments — <strong>Kindly provide the details below under other
                                                documents.</strong>
                                            </td>
                                        </tr>

                                        <!-- Other Standard Rows -->
                                        @php
                                            $encRows = [
                                                'ca_certificate'           => 'CA Certificate of Capital Investment',
                                                'project_report'           => 'Project Report',
                                                'shop_licence'             => 'Copy of Licence under Shop & Establishment Act / Food & Drug Administration',
                                                'star_classification'      => 'Star Classification Certificate',
                                                'mpcb_noc'                 => 'NOC from Maharashtra Pollution Control Board',
                                                'balance_sheets'           => 'Audited Balance Sheets (from commencement year up to date)',
                                                'proof_commercial'         => 'Proof of commencement of commercial operation (First Sale Bill / Excise Register)',
                                                'declaration_commencement' => 'Declaration by Director / Partner / Proprietor / Trustee regarding commencement date (on letterhead)',
                                                'completion_certificate'   => 'Certificate of completion and permissions from respective authorities',
                                                'gst_registration'         => 'Maharashtra State GST Registration Certificate',
                                                'processing_fee'           => 'Processing Fee Challan (₹10,000) paid on www.gras.mahakosh.gov.in',
                                            ];
                                        @endphp

                                        @foreach($encRows as $key => $label)
                                        <tr>
                                            <td style="border:2px solid #000;">
                                                <input type="checkbox" class="form-check-input enclosure-check">
                                            </td>
                                            <td style="border:2px solid #000; text-align:left;">
                                                {{ $label }}
                                                <input type="hidden" name="enclosures[{{ $key }}][label]" value="{{ $label }}">
                                            </td>
                                            <td style="border:2px solid #000;">
                                                <input type="text" name="enclosures[{{ $key }}][doc_no]"
                                                       class="form-control enclosure-docno" placeholder="Enter Doc No" disabled>
                                            </td>
                                            <td style="border:2px solid #000;">
                                                <input type="date" name="enclosures[{{ $key }}][issue_date]"
                                                       class="form-control enclosure-date" disabled>
                                            </td>
                                            <td style="border:2px solid #000;">
                                                <input type="file" name="enclosures[{{ $key }}][file]"
                                                       class="form-control enclosure-file" accept=".pdf,.jpg,.png,.jpeg" disabled>
                                            </td>
                                            <td style="border:2px solid #000;">
                                                <div class="enc-preview"></div>
                                                <button type="button" class="btn btn-link text-danger enc-remove d-none">
                                                    <i class="fa-solid fa-circle-xmark"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- OTHER DOCUMENTS -->
                            <h6 class="fw-semibold mt-4 mb-2 text-primary">
                                Other Documents (Specify name and other details)
                            </h6>

                            <div class="table-responsive">
                                <table class="table text-center align-middle" id="otherDocsTable"
                                    style="width:100%; border:2px solid #000; border-collapse:collapse;">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="border:2px solid #000; width:5%;">#</th>
                                            <th style="border:2px solid #000; width:30%;">Document Name</th>
                                            <th style="border:2px solid #000; width:15%;">Doc No.</th>
                                            <th style="border:2px solid #000; width:15%;">Issue Date</th>
                                            <th style="border:2px solid #000; width:15%;">Validity Date</th>
                                            <th style="border:2px solid #000; width:15%;">Upload File</th>
                                            <th style="border:2px solid #000; width:15%;">Preview</th>
                                            <th style="border:2px solid #000; width:5%;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="border:2px solid #000;">1</td>
                                            <td style="border:2px solid #000;">
                                                <input type="text" name="other_docs[0][name]"
                                                       class="form-control" placeholder="Document Name">
                                            </td>
                                            <td style="border:2px solid #000;">
                                                <input type="text" name="other_docs[0][doc_no]"
                                                       class="form-control" placeholder="Doc No">
                                            </td>
                                            <td style="border:2px solid #000;">
                                                <input type="date" name="other_docs[0][issue_date]"
                                                       class="form-control">
                                            </td>
                                            <td style="border:2px solid #000;">
                                                <input type="date" name="other_docs[0][validity_date]"
                                                       class="form-control">
                                            </td>
                                            <td style="border:2px solid #000;">
                                                <input type="file" name="other_docs[0][file]"
                                                       class="form-control other-doc-file" accept=".pdf,.jpg,.png,.jpeg">
                                            </td>
                                            <td style="border:2px solid #000;" class="preview-td"></td>
                                            <td style="border:2px solid #000;">
                                                <button type="button" class="btn btn-danger btn-sm removeRow">X</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" id="addOtherDoc">+ Add Row</button>

                            <!-- NOTES -->
                            <div class="mt-3">
                                <p class="fw-semibold mb-1">Note:</p>
                                <ol style="margin-left:1rem;">
                                    <li>All documents should be self-attested by the applicant.</li>
                                    <li>In case of multiple NOC/Certificate/Insurance please fill details in "Other Documents" section.</li>
                                    <li>Fields marked with * are mandatory.</li>
                                </ol>
                            </div>

                            <!-- DECLARATION -->
                            <h5 class="fw-semibold mt-5 mb-3 text-primary">
                                <i class="fa-solid fa-scroll form-icon"></i>
                                Declaration
                            </h5>

                            <div class="border p-3" style="border:2px solid #000; border-radius:6px; line-height:1.6;">
                                <ol class="mb-4">
                                    <li>Certified that no claim for incentives has been sanctioned or disbursed...</li>
                                    <li>Certified that the information / statement contained in this application are true...</li>
                                    <li>Declared that no Government enquiry has been instituted against the applicant unit...</li>
                                    <li>We hereby agree to abide by the terms and conditions...</li>
                                    <li>We hereby agree that the Certificate of Entitlement / claim sanction letter issued on the basis of the above statements is liable to be cancelled if information is found incorrect.</li>
                                </ol>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="declaration_check">
                                    <label class="form-check-label" for="declaration_check">
                                        I have read and understood the above declaration.
                                    </label>
                                </div>

                                <div class="row g-3 align-items-end">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold required">
                                            <i class="fa-solid fa-signature form-icon"></i>
                                            Signature of the Applicant / Proprietor / Partner / Director / Trustee
                                        </label>
                                        <input type="file"
                                               name="signature_upload"
                                               id="signature_upload"
                                               class="form-control @error('signature_upload') is-invalid @enderror"
                                               accept=".jpg,.jpeg,.png,.pdf"
                                               required>
                                        @error('signature_upload')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror

                                        <div id="signaturePreviewWrapper" class="mt-2" style="display:none;">
                                            <img id="signaturePreviewImg" src="" class="img-thumbnail"
                                                 style="max-height:120px; cursor:pointer;">
                                            <div><small class="text-muted">Click to enlarge</small></div>
                                        </div>
                                        <div id="signaturePdfInfo" class="mt-2 text-muted" style="display:none;"></div>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold required">
                                            <i class="fa-solid fa-location-dot form-icon"></i>
                                            Place
                                        </label>
                                        <input type="text"
                                               name="declaration_place"
                                               class="form-control @error('declaration_place') is-invalid @enderror"
                                               value="{{ old('declaration_place') }}"
                                               required>
                                        @error('declaration_place')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold required">
                                            <i class="fa-solid fa-calendar-day form-icon"></i>
                                            Date
                                        </label>
                                        <input type="date"
                                            name="declaration_date"
                                            class="form-control @error('declaration_date') is-invalid @enderror"
                                            value="{{ old('declaration_date', now()->format('Y-m-d')) }}"
                                            required>
                                        @error('declaration_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror

                                    </div>
                                </div>
                            </div>

                            <p class="mt-4 mb-0 fst-italic text-center" style="font-size: 0.95rem;">
                                (This application shall be signed only by any one of the persons indicated above with appropriate rubber
                                stamps of the applicant and designation of the signatory).
                            </p>

                            <div class="text-center mt-5">
                                <button type="button"
                                        id="previewSubmitBtn"
                                        class="btn btn-primary mb-5 px-5 py-2"
                                        disabled>
                                    Preview & Submit
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
<div class="modal fade" id="formPreviewModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Your Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="formPreviewBody"
                 style="max-height: 70vh; overflow-y: auto;"></div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmSubmitBtn" disabled>
                    <span class="spinner-border spinner-border-sm me-2 d-none" id="submitLoader"></span>
                    Final Submit
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Image preview modal -->
<div class="modal fade" id="previewImageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-0 text-center">
                <img id="previewImageModalImg" src="" class="img-fluid rounded">
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
$(function () {

    // Letters only rule
    $.validator.addMethod("lettersOnly", function(value, element) {
        return this.optional(element) || /^[A-Za-z\s]+$/.test(value);
    }, "Only letters and spaces are allowed.");

    // jQuery Validate
    let validator = $('#eligibilityForm').validate({
        ignore: [],
        errorClass: 'is-invalid',
        validClass: 'is-valid',
        errorElement: 'div',
        errorPlacement: function(error, element) {
            error.addClass('text-danger small mt-1');
            error.insertAfter(element);
        },
        highlight: function(element) {
            $(element).addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid').addClass('is-valid');
        },
        rules: {
            applicant_name: {
                required: true,
                minlength: 2,
                lettersOnly: true
            },
            provisional_number: {
                required: true
            },
            gst_number: {
                required: true,
                minlength: 15,
                maxlength: 15
            },
            project_description: {
                required: true,
                minlength: 10
            },
            commencement_date: {
                required: true
            },
            operation_details: {
                required: true
            },
            declaration_place: {
                required: true,
                lettersOnly: true
            },
            declaration_date:  {
                required: true
            },
            signature_upload:  {
                required: true
            },
            'entrepreneurs[0][name]': {
                required: true,
                lettersOnly: true
            },
            'entrepreneurs[0][designation]': {
                required: true
            },
            'entrepreneurs[0][ownership]': {
                required: true,
                number: true,
                min: 0,
                max: 100
            },
            'entrepreneurs[0][gender]': {
                required: true
            },
            'entrepreneurs[0][age]': {
                required: true,
                number: true,
                min: 18
            }
        },
        messages: {
            applicant_name: {
                required: "Please enter the Name of Applicant/Tourism Unit.",
                minlength: "Name must be at least 2 characters."
            },
            provisional_number: {
                required: "Please enter Provisional Certificate Number."
            },
            gst_number: {
                required: "Please enter GST Number.",
                minlength: "GST Number must be 15 characters.",
                maxlength: "GST Number must be 15 characters."
            },
            project_description: {
                required: "Please enter project description.",
                minlength: "Description must be at least 10 characters."
            },
            commencement_date: {
                required: "Please select date of commercial commencement."
            },
            operation_details: {
                required: "Please select operation details."
            },
            declaration_place: {
                required: "Please enter the place."
            },
            declaration_date:  "Please select the date.",
            signature_upload:  "Please upload signature.",
            'entrepreneurs[0][name]': "Name is required.",
            'entrepreneurs[0][designation]': "Designation is required.",
            'entrepreneurs[0][ownership]': "Ownership % is required (0-100).",
            'entrepreneurs[0][gender]': "Select gender.",
            'entrepreneurs[0][age]': "Age is required (min 18)."
        }
    });

    // Entrepreneur dynamic rows
    let entrepreneurIndex = 1;
    $('#addEntrepreneur').on('click', function () {
        const row = `
            <tr>
                <td><input type="text" name="entrepreneurs[${entrepreneurIndex}][name]" class="form-control"></td>
                <td><input type="text" name="entrepreneurs[${entrepreneurIndex}][designation]" class="form-control"></td>
                <td><input type="number" name="entrepreneurs[${entrepreneurIndex}][ownership]" class="form-control" min="0" max="100"></td>
                <td>
                    <select name="entrepreneurs[${entrepreneurIndex}][gender]" class="form-control">
                        <option value="">Select</option>
                        <option>Male</option>
                        <option>Female</option>
                        <option>Other</option>
                    </select>
                </td>
                <td><input type="number" name="entrepreneurs[${entrepreneurIndex}][age]" class="form-control"></td>
                <td><button type="button" class="btn btn-danger btn-sm removeRow">X</button></td>
            </tr>
        `;
        $('#entrepreneurTable tbody').append(row);
        entrepreneurIndex++;
    });

    $(document).on('click', '.removeRow', function () {
        $(this).closest('tr').remove();
    });

    // Enclosures enable/disable + validation rules
    $('.enclosure-check').on('change', function () {
        const row = $(this).closest('tr');
        const enabled = $(this).is(':checked');

        const docInput  = row.find('.enclosure-docno');
        const dateInput = row.find('.enclosure-date');
        const fileInput = row.find('.enclosure-file');

        row.find('.enclosure-docno, .enclosure-date, .enclosure-file')
            .prop('disabled', !enabled);

        if (enabled) {
            docInput.rules('add',  { required: true, messages: { required: "Doc No is required." }});
            dateInput.rules('add', { required: true, messages: { required: "Issue date is required." }});
            fileInput.rules('add', { required: true, messages: { required: "File is required." }});
        } else {
            docInput.rules('remove', 'required');
            dateInput.rules('remove', 'required');
            fileInput.rules('remove', 'required');

            docInput.val('');
            dateInput.val('');
            fileInput.val('');
            row.find('.enc-preview').empty();
            row.find('.enc-remove').addClass('d-none');
        }
    });

    // Enclosure file preview (image/pdf) + remove
    $(document).on('change', '.enclosure-file', function () {
        const file = this.files[0];
        const row = $(this).closest('tr');
        const previewBox = row.find('.enc-preview');
        const removeBtn  = row.find('.enc-remove');

        previewBox.empty();
        removeBtn.addClass('d-none');

        if (!file) return;

        const type = file.type.toLowerCase();

        if (type.startsWith('image/')) {
            const url = URL.createObjectURL(file);
            const img = $('<img/>', {
                src: url,
                class: 'img-thumbnail'
            });

            img.on('click', function () {
                $('#previewImageModalImg').attr('src', url);
                const m = new bootstrap.Modal(document.getElementById('previewImageModal'));
                m.show();
            });

            previewBox.append(img);
        } else if (type === 'application/pdf') {
            const url = URL.createObjectURL(file);
            previewBox.html(`
                <i class="fa-solid fa-file-pdf fa-lg text-danger"></i>
                <a href="${url}" target="_blank" class="ms-2">View</a>
            `);
        } else {
            previewBox.html('<span class="text-danger small">Unsupported file</span>');
        }

        removeBtn.removeClass('d-none');
    });

    $(document).on('click', '.enc-remove', function () {
        const row = $(this).closest('tr');
        row.find('.enclosure-file').val('');
        row.find('.enclosure-docno').val('');
        row.find('.enclosure-date').val('');
        row.find('.enc-preview').empty();
        row.find('.enclosure-check').prop('checked', false).trigger('change');
        $(this).addClass('d-none');
    });

    // Other docs dynamic rows
    let otherDocIndex = 1;
    $('#addOtherDoc').on('click', function () {
        const row = `
            <tr>
                <td style="border:2px solid #000;">${otherDocIndex + 1}</td>
                <td style="border:2px solid #000;">
                    <input type="text" name="other_docs[${otherDocIndex}][name]"
                           class="form-control" placeholder="Document Name">
                </td>
                <td style="border:2px solid #000;">
                    <input type="text" name="other_docs[${otherDocIndex}][doc_no]"
                           class="form-control" placeholder="Doc No">
                </td>
                <td style="border:2px solid #000;">
                    <input type="date" name="other_docs[${otherDocIndex}][issue_date]"
                           class="form-control">
                </td>
                <td style="border:2px solid #000;">
                    <input type="date" name="other_docs[${otherDocIndex}][validity_date]"
                           class="form-control">
                </td>
                <td style="border:2px solid #000;">
                    <input type="file" name="other_docs[${otherDocIndex}][file]"
                           class="form-control other-doc-file" accept=".pdf,.jpg,.png,.jpeg">
                </td>
                <td style="border:2px solid #000;" class="preview-td"></td>
                <td style="border:2px solid #000;">
                    <button type="button" class="btn btn-danger btn-sm removeRow">X</button>
                </td>
            </tr>
        `;
        $('#otherDocsTable tbody').append(row);
        otherDocIndex++;
    });

    // Signature preview (image/pdf)
    $('#signature_upload').on('change', function () {
        const file = this.files[0];
        const imgBox = $('#signaturePreviewWrapper');
        const imgEl  = $('#signaturePreviewImg');
        const pdfInfo = $('#signaturePdfInfo');

        imgBox.hide();
        pdfInfo.hide().empty();

        if (!file) return;

        const type = file.type.toLowerCase();

        if (type.startsWith('image/')) {
            const url = URL.createObjectURL(file);
            imgEl.attr('src', url);
            imgBox.show();

            imgEl.off('click').on('click', function () {
                $('#previewImageModalImg').attr('src', url);
                const modal = new bootstrap.Modal(document.getElementById('previewImageModal'));
                modal.show();
            });

        } else if (type === 'application/pdf') {
            const url = URL.createObjectURL(file);
            pdfInfo.html(`
                <i class="fa-solid fa-file-pdf fa-lg text-danger"></i>
                <a href="${url}" target="_blank" class="ms-2">View PDF</a>
            `).show();
        }
    });

    // Other docs image/pdf preview
    $(document).on('change', '.other-doc-file', function () {
        const file = this.files[0];
        const td = $(this).closest('tr').find('.preview-td');
        td.empty();

        if (!file) return;

        const type = file.type.toLowerCase();

        if (type.startsWith('image/')) {
            const url = URL.createObjectURL(file);
            const img = $('<img/>', {
                src: url,
                class: 'img-thumbnail',
                style: 'max-height:60px;cursor:pointer;'
            });
            img.on('click', function () {
                $('#previewImageModalImg').attr('src', url);
                const m = new bootstrap.Modal(document.getElementById('previewImageModal'));
                m.show();
            });
            td.append(img);
        } else if (type === 'application/pdf') {
            const url = URL.createObjectURL(file);
            td.html(`
                <i class="fa-solid fa-file-pdf fa-lg text-danger"></i>
                <a href="${url}" target="_blank" class="ms-2">Open PDF</a>
            `);
        }
    });

    // Declaration checkbox: SweetAlert + enable Preview btn
    $('#previewSubmitBtn').prop('disabled', true);

    $('#declaration_check').on('change', function () {
        const checkbox = this;

        if (checkbox.checked) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you confirm that all the declaration points are true?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff6600',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, I agree',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#previewSubmitBtn').prop('disabled', false);
                } else {
                    checkbox.checked = false;
                    $('#previewSubmitBtn').prop('disabled', true);
                }
            });
        } else {
            $('#previewSubmitBtn').prop('disabled', true);
        }
    });

    // Preview & Submit - Enhanced with ALL data
    $('#previewSubmitBtn').on('click', function () {
        const form = $('#eligibilityForm');

        if (!form.valid()) {
            form[0].reportValidity && form[0].reportValidity();
            return;
        }

        // Helper function to display value or N/A
        function displayValue(value) {
            if (!value || value.toString().trim() === '') {
                return '<span class="preview-na">N/A</span>';
            }
            return value;
        }

        let html = '<div class="preview-section">';
        html += '<h6 class="text-primary mb-3"><i class="fas fa-info-circle me-2"></i>General Details</h6>';
        html += '<table class="table table-bordered preview-table">';
        html += `<tr><td class="preview-label">Applicant Name</td><td class="preview-value">${displayValue($('input[name="applicant_name"]').val())}</td></tr>`;
        html += `<tr><td class="preview-label">Provisional Certificate Number</td><td class="preview-value">${displayValue($('input[name="provisional_number"]').val())}</td></tr>`;
        html += `<tr><td class="preview-label">GST Number</td><td class="preview-value">${displayValue($('input[name="gst_number"]').val())}</td></tr>`;
        html += `<tr><td class="preview-label">Date of Commercial Commencement</td><td class="preview-value">${displayValue($('input[name="commencement_date"]').val())}</td></tr>`;
        html += `<tr><td class="preview-label">Details of Operations</td><td class="preview-value">${displayValue($('select[name="operation_details"] option:selected').text())}</td></tr>`;
        html += '</table></div>';

        // Entrepreneur Profile
        html += '<div class="preview-section">';
        html += '<h6 class="text-primary mb-3"><i class="fas fa-users me-2"></i>Entrepreneur Profile</h6>';

        const entrepreneurRows = $('#entrepreneurTable tbody tr');
        if (entrepreneurRows.length > 0) {
            html += '<table class="table table-bordered preview-table">';
            html += '<thead><tr><th>Name</th><th>Designation</th><th>Ownership %</th><th>Gender</th><th>Age</th></tr></thead><tbody>';

            entrepreneurRows.each(function(index) {
                const name = $(this).find('input[name*="[name]"]').val();
                const designation = $(this).find('input[name*="[designation]"]').val();
                const ownership = $(this).find('input[name*="[ownership]"]').val();
                const gender = $(this).find('select[name*="[gender]"]').val();
                const age = $(this).find('input[name*="[age]"]').val();

                // Check if row has any data
                if (name || designation || ownership || gender || age) {
                    html += `<tr>
                        <td>${displayValue(name)}</td>
                        <td>${displayValue(designation)}</td>
                        <td>${displayValue(ownership)}</td>
                        <td>${displayValue(gender)}</td>
                        <td>${displayValue(age)}</td>
                    </tr>`;
                } else {
                    html += '<tr><td colspan="5" class="text-center preview-na">No entrepreneur data entered</td></tr>';
                }
            });

            html += '</tbody></table>';
        } else {
            html += '<p class="preview-na">No entrepreneur data entered</p>';
        }
        html += '</div>';

        // Project Description
        html += '<div class="preview-section">';
        html += '<h6 class="text-primary mb-3"><i class="fas fa-file-alt me-2"></i>Project Description</h6>';
        html += `<p class="preview-value">${displayValue($('textarea[name="project_description"]').val())}</p>`;
        html += '</div>';

        // Project Details
        html += '<div class="preview-section">';
        html += '<h6 class="text-primary mb-3"><i class="fas fa-project-diagram me-2"></i>Project Details</h6>';
        html += '<table class="table table-bordered preview-table">';
        html += '<thead><tr><th>Component</th><th>Cost Component</th><th>Asset Age</th><th>Ownership Status</th></tr></thead><tbody>';

        // Project components array
        const components = [
            { key: 'structures_buildings', label: 'Structures and Buildings; Trees' },
            { key: 'machinery_equipment', label: 'Indigenous and Imported Machinery and Equipment' },
            { key: 'material_handling', label: 'Material Handling Equipment' },
            { key: 'mep_installations', label: 'Mechanical, Electrical and Plumbing Installations' },
            { key: 'fixtures_furniture', label: 'Fixtures, Furniture\'s and Fittings' },
            { key: 'waste_treatment', label: 'Waste Treatment Facilities' },
            { key: 'transformer_generator', label: 'Transformer Generator' },
            { key: 'captive_power', label: 'Captive Power Plants / Renewable Energy Sources' },
            { key: 'utility_installation', label: 'Utility and Installation Charges' },
            { key: 'quality_investments', label: 'Quality Related Investments' },
            { key: 'sustainability_initiatives', label: 'Sustainability Initiatives' }
        ];

        components.forEach(comp => {
            const cost = $(`input[name="cost_component[${comp.key}]"]`).val();
            const age = $(`input[name="asset_age[${comp.key}]"]`).val();
            const ownership = [];
            $(`input[name="ownership[${comp.key}][]"]:checked`).each(function() {
                ownership.push($(this).val());
            });

            html += `<tr>
                <td>${comp.label}</td>
                <td>${displayValue(cost)}</td>
                <td>${displayValue(age)}</td>
                <td>${displayValue(ownership.join(', ') || 'N/A')}</td>
            </tr>`;
        });

        html += '</tbody></table></div>';

        // Enclosures
        html += '<div class="preview-section">';
        html += '<h6 class="text-primary mb-3"><i class="fas fa-folder-open me-2"></i>Enclosures</h6>';

        const checkedEnclosures = $('.enclosure-check:checked').closest('tr');
        if (checkedEnclosures.length > 0) {
            html += '<table class="table table-bordered preview-table">';
            html += '<thead><tr><th>Document</th><th>Doc No</th><th>Issue Date</th><th>File</th></tr></thead><tbody>';

            checkedEnclosures.each(function() {
                const label = $(this).find('td:nth-child(2)').text().trim();
                const docNo = $(this).find('.enclosure-docno').val();
                const issueDate = $(this).find('.enclosure-date').val();
                const fileName = $(this).find('.enclosure-file')[0].files[0]?.name || 'No file selected';

                html += `<tr>
                    <td>${displayValue(label)}</td>
                    <td>${displayValue(docNo)}</td>
                    <td>${displayValue(issueDate)}</td>
                    <td>${displayValue(fileName)}</td>
                </tr>`;
            });

            html += '</tbody></table>';
        } else {
            html += '<p class="preview-na">No enclosures selected</p>';
        }
        html += '</div>';

        // Other Documents
        html += '<div class="preview-section">';
        html += '<h6 class="text-primary mb-3"><i class="fas fa-file me-2"></i>Other Documents</h6>';

        const otherDocsRows = $('#otherDocsTable tbody tr');
        let hasOtherDocs = false;

        otherDocsRows.each(function(index) {
            const name = $(this).find('input[name*="[name]"]').val();
            const docNo = $(this).find('input[name*="[doc_no]"]').val();
            const issueDate = $(this).find('input[name*="[issue_date]"]').val();
            const validityDate = $(this).find('input[name*="[validity_date]"]').val();
            const fileName = $(this).find('.other-doc-file')[0].files[0]?.name;

            if (name || docNo || issueDate || validityDate || fileName) {
                if (!hasOtherDocs) {
                    html += '<table class="table table-bordered preview-table">';
                    html += '<thead><tr><th>Document Name</th><th>Doc No</th><th>Issue Date</th><th>Validity Date</th><th>File</th></tr></thead><tbody>';
                    hasOtherDocs = true;
                }

                html += `<tr>
                    <td>${displayValue(name)}</td>
                    <td>${displayValue(docNo)}</td>
                    <td>${displayValue(issueDate)}</td>
                    <td>${displayValue(validityDate)}</td>
                    <td>${displayValue(fileName)}</td>
                </tr>`;
            }
        });

        if (hasOtherDocs) {
            html += '</tbody></table>';
        } else {
            html += '<p class="preview-na">No other documents added</p>';
        }
        html += '</div>';

        // Declaration
        html += '<div class="preview-section">';
        html += '<h6 class="text-primary mb-3"><i class="fas fa-scroll me-2"></i>Declaration</h6>';
        html += '<table class="table table-bordered preview-table">';
        html += `<tr><td class="preview-label">Place</td><td class="preview-value">${displayValue($('input[name="declaration_place"]').val())}</td></tr>`;
        html += `<tr><td class="preview-label">Date</td><td class="preview-value">${displayValue($('input[name="declaration_date"]').val())}</td></tr>`;
        html += `<tr><td class="preview-label">Signature File</td><td class="preview-value">${displayValue($('#signature_upload')[0].files[0]?.name)}</td></tr>`;
        html += '</table></div>';

        // Add confirmation checkbox
        html += `<div class="preview-section">
            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" id="previewConfirmCheck">
                <label class="form-check-label" for="previewConfirmCheck">
                    I confirm that all the above details are correct and complete.
                </label>
            </div>
        </div>`;

        $('#formPreviewBody').html(html);

        $('#confirmSubmitBtn').prop('disabled', true);
        $('#previewConfirmCheck').off('change').on('change', function () {
            $('#confirmSubmitBtn').prop('disabled', !this.checked);
        });

        new bootstrap.Modal(document.getElementById('formPreviewModal')).show();
    });

    // AJAX Final Submit
    $('#confirmSubmitBtn').on('click', function () {
        const form = $('#eligibilityForm')[0];
        const $btn = $('#confirmSubmitBtn');

        if (!$('#eligibilityForm').valid()) {
            return;
        }

        const formData = new FormData(form);

        $btn.prop('disabled', true);
        $('#submitLoader').removeClass('d-none');

        $.ajax({
            url: $('#eligibilityForm').attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
            success: function (resp) {
                $('#submitLoader').addClass('d-none');
                $btn.prop('disabled', false);

                if (resp.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Submitted!',
                        text: resp.message,
                        confirmButtonColor: '#ff6600',
                    }).then(() => {
                        if (resp.redirect_url) {
                            window.location.href = resp.redirect_url;
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong while submitting.',
                    });
                }
            },
            error: function (xhr) {
                $('#submitLoader').addClass('d-none');
                $btn.prop('disabled', false);

                if (xhr.status === 422) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please correct the highlighted fields.',
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Server Error',
                        text: 'Please try again later.',
                    });
                }
            }
        });
    });
});
</script>
@endpush
