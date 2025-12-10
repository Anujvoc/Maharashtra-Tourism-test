@extends('frontend.layouts2.master')
@section('title', 'Hotel Registration')
@push('styles')
<style>
  :root{
    --brand: #ff6600;   /* Orange color */
    --brand-dark: #e25500;
  }
  .form-icon {
    color: var(--brand);
    font-size: 1.5rem;
  }
  .form-icon{margin-right:.35rem;}

  .step-progress {
    display: flex;
    justify-content: space-between;
    margin-bottom: 30px;
    position: relative;
  }
  .step-progress::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 0;
    right: 0;
    height: 3px;
    background-color: #e9ecef;
    z-index: 1;
  }
  .step-progress .step {
    position: relative;
    z-index: 2;
    text-align: center;
    flex: 1;
  }
  .step-progress .step .step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    font-weight: bold;
  }
  .step-progress .step.active .step-circle {
    background-color: var(--brand);
    color: white;
  }
  .step-progress .step.completed .step-circle {
    background-color: #28a745;
    color: white;
  }
  .step-progress .step .step-label {
    font-size: 14px;
    color: #6c757d;
  }
  .step-progress .step.active .step-label {
    color: var(--brand);
    font-weight: bold;
  }
  .step-progress .step.completed .step-label {
    color: #28a745;
  }

  .step-content {
    display: none;
  }
  .step-content.active {
    display: block;
  }

  .card-header {
    background-color: var(--brand) !important;
    color: white !important;
    font-weight: bold;
  }

  .file-preview {
    margin-top: 10px;
    padding: 10px;
    border: 1px solid #dee2e6;
    border-radius: 5px;
  }

  .file-preview img {
    max-height: 120px;
  }

  .required:after {
    content: " *";
    color: red;
  }

  .checkbox-list {
    list-style-type: none;
    padding-left: 0;
  }

  .checkbox-list li {
    margin-bottom: 10px;
  }
</style>
@endpush
@section('content')
<!-- Main Content -->
<section class="section">
  <div class="section-header">
    <h1>{{ $application_form->name ?? 'Hotel Registration' }}</h1>
  </div>

  <div class="section-body">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="row">
            <div class="card-header">
              <div class="col-md-6 col-12">
                <h4 class="m-0">Create {{ $application_form->name ?? 'Hotel Registration' }}</h4>
              </div>

              <!-- RIGHT SIDE BACK BUTTON -->
              <div class="col-md-6 col-12 d-flex justify-content-md-end justify-content-start mt-2 mt-md-0">
                <a href="#"
                   class="text-white fw-bold d-inline-block no-underline"
                   style="background-color:#3006ea; border:none; border-radius:8px; padding:.4rem 1.3rem;">
                  <i class="bi bi-arrow-left me-2 mx-2"></i> Back
                </a>
              </div>
            </div>
          </div>

          <div class="card-body">
            <!-- Progress Steps -->
            <div class="step-progress">
              <div class="step completed" data-step="1">
                <div class="step-circle">1</div>
                <div class="step-label">Basic Information</div>
              </div>
              <div class="step active" data-step="2">
                <div class="step-circle">2</div>
                <div class="step-label">Facilities & Services</div>
              </div>
              <div class="step" data-step="3">
                <div class="step-circle">3</div>
                <div class="step-label">Documents Upload</div>
              </div>
              <div class="step" data-step="4">
                <div class="step-circle">4</div>
                <div class="step-label">Review & Submit</div>
              </div>
            </div>

            <form id="hotelRegistrationForm"
                  {{-- action="{{ isset($application->id) ? route('frontend.industrial-registrations.update', $application->id) : route('frontend.industrial-registrations.store') }}" --}}
                  method="POST" enctype="multipart/form-data" novalidate>
              @csrf
              @if(isset($application->id))
                @method('PUT')
              @endif

              <!-- Step 1: Basic Information -->
              <div class="step-content active" id="step1">
                <div class="card p-3 mb-4">
                  <div class="card-header bg-orange text-white fw-bold">
                    <h5 class="m-0">Basic Information</h5>
                  </div>
                  <div class="card-body">
                    <!-- Row: Email + Mobile -->
                    <div class="row mb-3">
                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="email" class="form-label required">
                            <i class="bi bi-envelope form-icon"></i> Email Id
                          </label>
                          <input id="email" type="email" name="email" readonly
                                 class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                 value="{{ old('email', $application->email ?? Auth::user()->email) }}">
                          @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="mobile" class="form-label required">
                            <i class="bi bi-phone form-icon"></i> Mobile No.
                          </label>
                          <input id="mobile" type="number" name="mobile" pattern="^[6-9][0-9]{9}$" maxlength="10"
                                 class="form-control {{ $errors->has('mobile') ? 'is-invalid' : '' }}" readonly
                                 value="{{ old('mobile', $application->mobile ?? Auth::user()->phone) }}">
                          @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                      </div>
                    </div>

                    <!-- Row: Hotel Name + Company Name -->
                    <div class="row mb-3">
                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="hotel_name" class="form-label required">
                            <i class="bi bi-building form-icon"></i> Name of Hotel and Resort
                          </label>
                          <input id="hotel_name" type="text" name="hotel_name"
                                 class="form-control {{ $errors->has('hotel_name') ? 'is-invalid' : '' }}"
                                 value="{{ old('hotel_name', $application->hotel_name ?? '') }}"
                                 required>
                          @error('hotel_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="company_name" class="form-label required">
                            <i class="bi bi-briefcase form-icon"></i> Company Name
                          </label>
                          <input id="company_name" type="text" name="company_name"
                                 class="form-control {{ $errors->has('company_name') ? 'is-invalid' : '' }}"
                                 value="{{ old('company_name', $application->company_name ?? '') }}"
                                 required>
                          @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                      </div>
                    </div>

                    <!-- Row: Authorized Person + Region -->
                    <div class="row mb-3">
                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="authorized_person" class="form-label required">
                            <i class="bi bi-person form-icon"></i> Name of Authorized Person
                          </label>
                          <input id="authorized_person" type="text" name="authorized_person"
                                 class="form-control"
                                 pattern="^[A-Za-z\s]+$"
                                 title="Only letters and spaces are allowed"
                                 onkeypress="return validateName(event)"
                                 value="{{ old('authorized_person', $application->authorized_person ?? '') }}"
                                 required>
                          @error('authorized_person')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="region" class="form-label required">
                            <i class="bi bi-geo-alt form-icon"></i> Select Region
                          </label>
                          <select id="region" name="region"
                                  class="form-control {{ $errors->has('region') ? 'is-invalid' : '' }}"
                                  required>
                            <option value="">Select Region</option>
                            <option value="north" {{ old('region', $application->region ?? '') == 'north' ? 'selected' : '' }}>North</option>
                            <option value="south" {{ old('region', $application->region ?? '') == 'south' ? 'selected' : '' }}>South</option>
                            <option value="east" {{ old('region', $application->region ?? '') == 'east' ? 'selected' : '' }}>East</option>
                            <option value="west" {{ old('region', $application->region ?? '') == 'west' ? 'selected' : '' }}>West</option>
                          </select>
                          @error('region')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                      </div>
                    </div>

                    <!-- Row: Pin Code + Total Area -->
                    <div class="row mb-3">
                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="pincode" class="form-label required">
                            <i class="bi bi-pin-map form-icon"></i> Pin Code
                          </label>
                          <input id="pincode" type="text" name="pincode" maxlength="6"
                                 class="form-control {{ $errors->has('pincode') ? 'is-invalid' : '' }}"
                                 value="{{ old('pincode', $application->pincode ?? '') }}"
                                 required>
                          @error('pincode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="total_area" class="form-label required">
                            <i class="bi bi-arrows-angle-expand form-icon"></i> Total Area in sq.M
                          </label>
                          <input id="total_area" type="number" name="total_area" step="0.01"
                                 class="form-control {{ $errors->has('total_area') ? 'is-invalid' : '' }}"
                                 value="{{ old('total_area', $application->total_area ?? '') }}"
                                 required>
                          @error('total_area')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                      </div>
                    </div>

                    <!-- Row: Total Employees + Total Rooms -->
                    <div class="row mb-3">
                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="total_employees" class="form-label required">
                            <i class="bi bi-people form-icon"></i> Total Number of Employees
                          </label>
                          <input id="total_employees" type="number" name="total_employees"
                                 class="form-control {{ $errors->has('total_employees') ? 'is-invalid' : '' }}"
                                 value="{{ old('total_employees', $application->total_employees ?? '') }}"
                                 required>
                          @error('total_employees')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="total_rooms" class="form-label required">
                            <i class="bi bi-door-closed form-icon"></i> Total Number of Rooms
                          </label>
                          <input id="total_rooms" type="number" name="total_rooms"
                                 class="form-control {{ $errors->has('total_rooms') ? 'is-invalid' : '' }}"
                                 value="{{ old('total_rooms', $application->total_rooms ?? '') }}"
                                 required>
                          @error('total_rooms')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                      </div>
                    </div>

                    <!-- Row: Hotel Address + Company Address -->
                    <div class="row mb-3">
                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="hotel_address" class="form-label required">
                            <i class="bi bi-geo form-icon"></i> Hotel Address
                          </label>
                          <textarea id="hotel_address" name="hotel_address" rows="3"
                                    class="form-control {{ $errors->has('hotel_address') ? 'is-invalid' : '' }}"
                                    required>{{ old('hotel_address', $application->hotel_address ?? '') }}</textarea>
                          @error('hotel_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="company_address" class="form-label required">
                            <i class="bi bi-geo form-icon"></i> Company Address
                          </label>
                          <textarea id="company_address" name="company_address" rows="3"
                                    class="form-control {{ $errors->has('company_address') ? 'is-invalid' : '' }}"
                                    required>{{ old('company_address', $application->company_address ?? '') }}</textarea>
                          @error('company_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                      </div>
                    </div>

                    <!-- Row: District + Applicant Type -->
                    <div class="row mb-3">
                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="district" class="form-label required">
                            <i class="bi bi-map form-icon"></i> Select District
                          </label>
                          <select id="district" name="district"
                                  class="form-control {{ $errors->has('district') ? 'is-invalid' : '' }}"
                                  required>
                            <option value="">Select District</option>
                            <option value="district1" {{ old('district', $application->district ?? '') == 'district1' ? 'selected' : '' }}>District 1</option>
                            <option value="district2" {{ old('district', $application->district ?? '') == 'district2' ? 'selected' : '' }}>District 2</option>
                            <option value="district3" {{ old('district', $application->district ?? '') == 'district3' ? 'selected' : '' }}>District 3</option>
                          </select>
                          @error('district')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="applicant_type" class="form-label required">
                            <i class="bi bi-person-badge form-icon"></i> Select Applicant Type
                          </label>
                          <select id="applicant_type" name="applicant_type"
                                  class="form-control {{ $errors->has('applicant_type') ? 'is-invalid' : '' }}"
                                  required>
                            <option value="">Select Applicant Type</option>
                            <option value="individual" {{ old('applicant_type', $application->applicant_type ?? '') == 'individual' ? 'selected' : '' }}>Individual</option>
                            <option value="company" {{ old('applicant_type', $application->applicant_type ?? '') == 'company' ? 'selected' : '' }}>Company</option>
                            <option value="partnership" {{ old('applicant_type', $application->applicant_type ?? '') == 'partnership' ? 'selected' : '' }}>Partnership</option>
                          </select>
                          @error('applicant_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                      </div>
                    </div>

                    <!-- Row: Commencement Date + Emergency Contact -->
                    <div class="row mb-3">
                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="commencement_date" class="form-label required">
                            <i class="bi bi-calendar form-icon"></i> Commencement Date
                          </label>
                          <input id="commencement_date" type="date" name="commencement_date"
                                 class="form-control {{ $errors->has('commencement_date') ? 'is-invalid' : '' }}"
                                 value="{{ old('commencement_date', $application->commencement_date ?? '2025-06-11') }}"
                                 required>
                          @error('commencement_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="emergency_contact" class="form-label required">
                            <i class="bi bi-telephone form-icon"></i> Emergency Contact No.
                          </label>
                          <input id="emergency_contact" type="text" name="emergency_contact"
                                 pattern="^[6-9][0-9]{9}$" maxlength="10"
                                 class="form-control {{ $errors->has('emergency_contact') ? 'is-invalid' : '' }}"
                                 value="{{ old('emergency_contact', $application->emergency_contact ?? '') }}"
                                 required>
                          @error('emergency_contact')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                      </div>
                    </div>

                    <!-- MSEB Consumer Number + Star Category -->
                    <div class="row mb-3">
                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="mseb_consumer_number" class="form-label required">
                            <i class="bi bi-lightning form-icon"></i> MSEB Consumer Number
                          </label>
                          <input id="mseb_consumer_number" type="text" name="mseb_consumer_number"
                                 class="form-control {{ $errors->has('mseb_consumer_number') ? 'is-invalid' : '' }}"
                                 value="{{ old('mseb_consumer_number', $application->mseb_consumer_number ?? '') }}"
                                 required>
                          @error('mseb_consumer_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="star_category" class="form-label required">
                            <i class="bi bi-star form-icon"></i> Select Star Category
                          </label>
                          <select id="star_category" name="star_category"
                                  class="form-control {{ $errors->has('star_category') ? 'is-invalid' : '' }}"
                                  required>
                            <option value="">Select Star Category</option>
                            <option value="1" {{ old('star_category', $application->star_category ?? '') == '1' ? 'selected' : '' }}>1 Star</option>
                            <option value="2" {{ old('star_category', $application->star_category ?? '') == '2' ? 'selected' : '' }}>2 Star</option>
                            <option value="3" {{ old('star_category', $application->star_category ?? '') == '3' ? 'selected' : '' }}>3 Star</option>
                            <option value="4" {{ old('star_category', $application->star_category ?? '') == '4' ? 'selected' : '' }}>4 Star</option>
                            <option value="5" {{ old('star_category', $application->star_category ?? '') == '5' ? 'selected' : '' }}>5 Star</option>
                          </select>
                          @error('star_category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                      </div>
                    </div>

                    <!-- Electricity Company + Property Tax Dept -->
                    <div class="row mb-3">
                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="electricity_company" class="form-label required">
                            <i class="bi bi-building form-icon"></i> Electricity Company Name And Address
                          </label>
                          <textarea id="electricity_company" name="electricity_company" rows="3"
                                    class="form-control {{ $errors->has('electricity_company') ? 'is-invalid' : '' }}"
                                    required>{{ old('electricity_company', $application->electricity_company ?? '') }}</textarea>
                          @error('electricity_company')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="property_tax_dept" class="form-label required">
                            <i class="bi bi-building form-icon"></i> Property Tax Dept Name And Address
                          </label>
                          <textarea id="property_tax_dept" name="property_tax_dept" rows="3"
                                    class="form-control {{ $errors->has('property_tax_dept') ? 'is-invalid' : '' }}"
                                    required>{{ old('property_tax_dept', $application->property_tax_dept ?? '') }}</textarea>
                          @error('property_tax_dept')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                      </div>
                    </div>

                    <!-- Water Bill Dept -->
                    <div class="row mb-3">
                      <div class="col-md-12">
                        <div class="form-group mb-3">
                          <label for="water_bill_dept" class="form-label required">
                            <i class="bi bi-droplet form-icon"></i> Water Bill Dept Name And Address
                          </label>
                          <textarea id="water_bill_dept" name="water_bill_dept" rows="3"
                                    class="form-control {{ $errors->has('water_bill_dept') ? 'is-invalid' : '' }}"
                                    required>{{ old('water_bill_dept', $application->water_bill_dept ?? '') }}</textarea>
                          @error('water_bill_dept')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                      </div>
                    </div>

                    <!-- General Requirements -->
                    <div class="row mb-3">
                      <div class="col-md-12">
                        <div class="card">
                          <div class="card-header bg-orange text-white fw-bold">
                            <h5 class="m-0">General Requirements</h5>
                          </div>
                          <div class="card-body">
                            <ul class="checkbox-list">
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="min_rooms" name="min_rooms"
                                         {{ old('min_rooms', $application->min_rooms ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="min_rooms">
                                    Minimum 6 lettable rooms, all rooms with outside windows / ventilation
                                  </label>
                                </div>
                              </li>
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="room_size" name="room_size"
                                         {{ old('room_size', $application->room_size ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="room_size">
                                    Minimum Rooms size should be as follows (Room size excludes bathroom) All rooms must have attached bathroom mandatorily - Single - 80 sq. ft. and Double - 120 sq. ft.
                                  </label>
                                </div>
                              </li>
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="bathroom_size" name="bathroom_size"
                                         {{ old('bathroom_size', $application->bathroom_size ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="bathroom_size">
                                    Minimum Bathroom size should be 30 sq. ft.
                                  </label>
                                </div>
                              </li>
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="bathroom_fixtures" name="bathroom_fixtures"
                                         {{ old('bathroom_fixtures', $application->bathroom_fixtures ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="bathroom_fixtures">
                                    Bathroom Sanitary Fixtures Toilets must be well ventilated. Each western WC toilet should have a seat with lid and toilet paper. Post toilet hygiene facilities - toilet paper, soap, sanitary bin, 24-hour running water.
                                  </label>
                                </div>
                              </li>
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="full_time_operation" name="full_time_operation"
                                         {{ old('full_time_operation', $application->full_time_operation ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="full_time_operation">
                                    Full time operation 7 days a week
                                  </label>
                                </div>
                              </li>
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="elevators" name="elevators"
                                         {{ old('elevators', $application->elevators ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="elevators">
                                    24 hrs. elevators for buildings higher than ground plus four floors or as per the prevailing local building norms applicable
                                  </label>
                                </div>
                              </li>
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="electricity_availability" name="electricity_availability"
                                         {{ old('electricity_availability', $application->electricity_availability ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="electricity_availability">
                                    24x7 availability of electricity
                                  </label>
                                </div>
                              </li>
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="emergency_lights" name="emergency_lights"
                                         {{ old('emergency_lights', $application->emergency_lights ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="emergency_lights">
                                    Emergency lights available in the public areas
                                  </label>
                                </div>
                              </li>
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="cctv" name="cctv"
                                         {{ old('cctv', $application->cctv ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="cctv">
                                    Hotel Entrances and all common areas are controlled by CCTV Cameras 24 hours a day
                                  </label>
                                </div>
                              </li>
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="disabled_access" name="disabled_access"
                                         {{ old('disabled_access', $application->disabled_access ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="disabled_access">
                                    Differently Abled Guest Friendly Access at the entrance
                                  </label>
                                </div>
                              </li>
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="security_guards" name="security_guards"
                                         {{ old('security_guards', $application->security_guards ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="security_guards">
                                    Security Guards available 24 hours a day
                                  </label>
                                </div>
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="row mt-4">
                      <div class="col-md-12 d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" id="prevBtn" style="display:none;">Previous</button>
                        <button type="button" class="btn btn-primary" id="nextBtn1">Next</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Step 2: Facilities & Services -->
              <div class="step-content" id="step2">
                <div class="card p-3 mb-4">
                  <div class="card-header bg-orange text-white fw-bold">
                    <h5 class="m-0">Facilities & Services</h5>
                  </div>
                  <div class="card-body">
                    <!-- Bathroom Facilities -->
                    <div class="row mb-4">
                      <div class="col-md-12">
                        <div class="card">
                          <div class="card-header">
                            <h5 class="m-0">Bathroom</h5>
                          </div>
                          <div class="card-body">
                            <ul class="checkbox-list">
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="attached_bathrooms" name="attached_bathrooms"
                                         {{ old('attached_bathrooms', $application->attached_bathrooms ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="attached_bathrooms">
                                    Rooms with attached bathrooms
                                  </label>
                                </div>
                              </li>
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="hot_cold_water" name="hot_cold_water"
                                         {{ old('hot_cold_water', $application->hot_cold_water ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="hot_cold_water">
                                    Availability of Hot and Cold running water
                                  </label>
                                </div>
                              </li>
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="water_saving_taps" name="water_saving_taps"
                                         {{ old('water_saving_taps', $application->water_saving_taps ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="water_saving_taps">
                                    Water saving taps and showers
                                  </label>
                                </div>
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Public Area -->
                    <div class="row mb-4">
                      <div class="col-md-12">
                        <div class="card">
                          <div class="card-header">
                            <h5 class="m-0">Public Area</h5>
                          </div>
                          <div class="card-body">
                            <ul class="checkbox-list">
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="lounge" name="lounge"
                                         {{ old('lounge', $application->lounge ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="lounge">
                                    Lounge or seating area in the lobby
                                  </label>
                                </div>
                              </li>
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="reception" name="reception"
                                         {{ old('reception', $application->reception ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="reception">
                                    Reception facility
                                  </label>
                                </div>
                              </li>
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="public_restrooms" name="public_restrooms"
                                         {{ old('public_restrooms', $application->public_restrooms ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="public_restrooms">
                                    Public restrooms with a wash basin, a mirror, a sanitary bin with lid in unisex toilet
                                  </label>
                                </div>
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Differently Abled Guests -->
                    <div class="row mb-4">
                      <div class="col-md-12">
                        <div class="card">
                          <div class="card-header">
                            <h5 class="m-0">Room And Facilities for the Differently Abled Guests</h5>
                          </div>
                          <div class="card-body">
                            <ul class="checkbox-list">
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="disabled_room" name="disabled_room"
                                         {{ old('disabled_room', $application->disabled_room ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="disabled_room">
                                    Atleast one room for the differently able guest
                                  </label>
                                </div>
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Kitchen / Food Production Area -->
                    <div class="row mb-4">
                      <div class="col-md-12">
                        <div class="card">
                          <div class="card-header">
                            <h5 class="m-0">Kitchen / Food Production Area</h5>
                          </div>
                          <div class="card-body">
                            <ul class="checkbox-list">
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="fssai_kitchen" name="fssai_kitchen"
                                         {{ old('fssai_kitchen', $application->fssai_kitchen ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="fssai_kitchen">
                                    FSSAI registration / Licensed Kitchen
                                  </label>
                                </div>
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Hotel Staff And Related Facilities -->
                    <div class="row mb-4">
                      <div class="col-md-12">
                        <div class="card">
                          <div class="card-header">
                            <h5 class="m-0">Hotel Staff And Related Facilities</h5>
                          </div>
                          <div class="card-body">
                            <ul class="checkbox-list">
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="uniforms" name="uniforms"
                                         {{ old('uniforms', $application->uniforms ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="uniforms">
                                    Uniforms for front house staff
                                  </label>
                                </div>
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Code of Conduct for Safe And Honorable Tourism -->
                    <div class="row mb-4">
                      <div class="col-md-12">
                        <div class="card">
                          <div class="card-header">
                            <h5 class="m-0">Code of Conduct for Safe And Honorable Tourism</h5>
                          </div>
                          <div class="card-body">
                            <ul class="checkbox-list">
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="pledge_display" name="pledge_display"
                                         {{ old('pledge_display', $application->pledge_display ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="pledge_display">
                                    Display of pledge
                                  </label>
                                </div>
                              </li>
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="complaint_book" name="complaint_book"
                                         {{ old('complaint_book', $application->complaint_book ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="complaint_book">
                                    Maintenance of Complaint book and Suggestion Book
                                  </label>
                                </div>
                              </li>
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="nodal_officer" name="nodal_officer"
                                         {{ old('nodal_officer', $application->nodal_officer ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="nodal_officer">
                                    Appointment of Nodal officers and display of information of Nodal officer at reception
                                  </label>
                                </div>
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Guest Services -->
                    <div class="row mb-4">
                      <div class="col-md-12">
                        <div class="card">
                          <div class="card-header">
                            <h5 class="m-0">Guest Services</h5>
                          </div>
                          <div class="card-body">
                            <ul class="checkbox-list">
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="doctor_on_call" name="doctor_on_call"
                                         {{ old('doctor_on_call', $application->doctor_on_call ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="doctor_on_call">
                                    Availability of Doctor-on-call service and name address and telephone number of doctors with front desk
                                  </label>
                                </div>
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Safety And Security -->
                    <div class="row mb-4">
                      <div class="col-md-12">
                        <div class="card">
                          <div class="card-header">
                            <h5 class="m-0">Safety And Security</h5>
                          </div>
                          <div class="card-body">
                            <ul class="checkbox-list">
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="police_verification" name="police_verification"
                                         {{ old('police_verification', $application->police_verification ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="police_verification">
                                    Police Verification for employees
                                  </label>
                                </div>
                              </li>
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="fire_drills" name="fire_drills"
                                         {{ old('fire_drills', $application->fire_drills ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="fire_drills">
                                    Conducting regular fire fighting drills and adherence to norms of the Fire department
                                  </label>
                                </div>
                              </li>
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="first_aid" name="first_aid"
                                         {{ old('first_aid', $application->first_aid ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="first_aid">
                                    First aid kit at the front desk
                                  </label>
                                </div>
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Additional Features -->
                    <div class="row mb-4">
                      <div class="col-md-12">
                        <div class="card">
                          <div class="card-header">
                            <h5 class="m-0">Additional Features</h5>
                          </div>
                          <div class="card-body">
                            <ul class="checkbox-list">
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="suite" name="suite"
                                         {{ old('suite', $application->suite ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="suite">
                                    Suite (2 rooms or 2 room- bays having a bedroom and separate sitting area, having one bathroom and one powder room)
                                  </label>
                                </div>
                              </li>
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="fb_outlet" name="fb_outlet"
                                         {{ old('fb_outlet', $application->fb_outlet ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="fb_outlet">
                                    F and B Outlet
                                  </label>
                                </div>
                              </li>
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="iron_facility" name="iron_facility"
                                         {{ old('iron_facility', $application->iron_facility ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="iron_facility">
                                    Iron and Iron Board facility
                                  </label>
                                </div>
                              </li>
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="paid_transport" name="paid_transport"
                                         {{ old('paid_transport', $application->paid_transport ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="paid_transport">
                                    Paid transportation on call
                                  </label>
                                </div>
                              </li>
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="business_center" name="business_center"
                                         {{ old('business_center', $application->business_center ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="business_center">
                                    Business Center
                                  </label>
                                </div>
                              </li>
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="conference_facilities" name="conference_facilities"
                                         {{ old('conference_facilities', $application->conference_facilities ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="conference_facilities">
                                    Conference Facilities
                                  </label>
                                </div>
                              </li>
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="sewage_treatment" name="sewage_treatment"
                                         {{ old('sewage_treatment', $application->sewage_treatment ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="sewage_treatment">
                                    Sewage Treatment Plant
                                  </label>
                                </div>
                              </li>
                              <li>
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="rainwater_harvesting" name="rainwater_harvesting"
                                         {{ old('rainwater_harvesting', $application->rainwater_harvesting ?? '') ? 'checked' : '' }}>
                                  <label class="form-check-label" for="rainwater_harvesting">
                                    Rainwater Harvesting
                                  </label>
                                </div>
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="row mt-4">
                      <div class="col-md-12 d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" id="prevBtn2">Previous</button>
                        <button type="button" class="btn btn-primary" id="nextBtn2">Next</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Step 3: Documents Upload -->
              <div class="step-content" id="step3">
                <div class="card p-3 mb-4">
                  <div class="card-header bg-orange text-white fw-bold">
                    <h5 class="m-0">Documents Upload</h5>
                  </div>
                  <div class="card-body">
                    <p class="text-muted mb-4">All documents must be less than 5 MB in size.</p>

                    <div class="row">
                      <!-- PAN Card -->
                      <div class="col-md-6 mb-3">
                        <div class="form-group">
                          <label for="pan_card" class="form-label required">PAN Card</label>
                          <input type="file" class="form-control file-input" id="pan_card" name="pan_card"
                                 data-preview="pan_preview" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                          <div id="pan_preview" class="file-preview"></div>
                        </div>
                      </div>

                      <!-- Business Entity Registration -->
                      <div class="col-md-6 mb-3">
                        <div class="form-group">
                          <label for="business_registration" class="form-label required">Business Entity Registration Copy</label>
                          <input type="file" class="form-control file-input" id="business_registration" name="business_registration"
                                 data-preview="business_preview" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                          <div id="business_preview" class="file-preview"></div>
                        </div>
                      </div>

                      <!-- FSSAI Registration -->
                      <div class="col-md-6 mb-3">
                        <div class="form-group">
                          <label for="fssai_registration" class="form-label required">Copy of FSSAI registration / Licensed Kitchen</label>
                          <input type="file" class="form-control file-input" id="fssai_registration" name="fssai_registration"
                                 data-preview="fssai_preview" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                          <div id="fssai_preview" class="file-preview"></div>
                        </div>
                      </div>

                      <!-- Declaration Form -->
                      <div class="col-md-6 mb-3">
                        <div class="form-group">
                          <label for="declaration_form" class="form-label required">Declaration Form</label>
                          <input type="file" class="form-control file-input" id="declaration_form" name="declaration_form"
                                 data-preview="declaration_preview" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                          <div id="declaration_preview" class="file-preview"></div>
                        </div>
                      </div>

                      <!-- MPCB Certificate -->
                      <div class="col-md-6 mb-3">
                        <div class="form-group">
                          <label for="mpcb_certificate" class="form-label required">MPCB Certificate</label>
                          <input type="file" class="form-control file-input" id="mpcb_certificate" name="mpcb_certificate"
                                 data-preview="mpcb_preview" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                          <div id="mpcb_preview" class="file-pview"></div>
                        </div>
                      </div>

                      <!-- Star Classified Certificate -->
                      <div class="col-md-6 mb-3">
                        <div class="form-group">
                          <label for="star_certificate" class="form-label required">Star Classified Certificate</label>
                          <input type="file" class="form-control file-input" id="star_certificate" name="star_certificate"
                                 data-preview="star_preview" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                          <div id="star_preview" class="file-preview"></div>
                        </div>
                      </div>

                      <!-- Water Bill -->
                      <div class="col-md-6 mb-3">
                        <div class="form-group">
                          <label for="water_bill" class="form-label required">Water Bill</label>
                          <input type="file" class="form-control file-input" id="water_bill" name="water_bill"
                                 data-preview="water_preview" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                          <div id="water_preview" class="file-preview"></div>
                        </div>
                      </div>

                      <!-- Aadhar Card -->
                      <div class="col-md-6 mb-3">
                        <div class="form-group">
                          <label for="aadhar_card" class="form-label required">Aadhar Card</label>
                          <input type="file" class="form-control file-input" id="aadhar_card" name="aadhar_card"
                                 data-preview="aadhar_preview" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                          <div id="aadhar_preview" class="file-preview"></div>
                        </div>
                      </div>

                      <!-- GST Registration -->
                      <div class="col-md-6 mb-3">
                        <div class="form-group">
                          <label for="gst_registration" class="form-label required">GST Registration Certificate Copy</label>
                          <input type="file" class="form-control file-input" id="gst_registration" name="gst_registration"
                                 data-preview="gst_preview" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                          <div id="gst_preview" class="file-preview"></div>
                        </div>
                      </div>

                      <!-- Building Certificate -->
                      <div class="col-md-6 mb-3">
                        <div class="form-group">
                          <label for="building_certificate" class="form-label required">Copy of completion of building certificate from competent authority OR Building Permission/Sanctioned Plan Copy</label>
                          <input type="file" class="form-control file-input" id="building_certificate" name="building_certificate"
                                 data-preview="building_preview" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                          <div id="building_preview" class="file-preview"></div>
                        </div>
                      </div>

                      <!-- Light Bill -->
                      <div class="col-md-6 mb-3">
                        <div class="form-group">
                          <label for="light_bill" class="form-label required">Light Bill</label>
                          <input type="file" class="form-control file-input" id="light_bill" name="light_bill"
                                 data-preview="light_preview" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                          <div id="light_preview" class="file-preview"></div>
                        </div>
                      </div>

                      <!-- Fire NOC -->
                      <div class="col-md-6 mb-3">
                        <div class="form-group">
                          <label for="fire_noc" class="form-label required">Fire NOC</label>
                          <input type="file" class="form-control file-input" id="fire_noc" name="fire_noc"
                                 data-preview="fire_preview" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                          <div id="fire_preview" class="file-preview"></div>
                        </div>
                      </div>

                      <!-- Property Tax -->
                      <div class="col-md-6 mb-3">
                        <div class="form-group">
                          <label for="property_tax" class="form-label required">Property Tax</label>
                          <input type="file" class="form-control file-input" id="property_tax" name="property_tax"
                                 data-preview="property_preview" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                          <div id="property_preview" class="file-preview"></div>
                        </div>
                      </div>

                      <!-- Electricity Bill -->
                      <div class="col-md-6 mb-3">
                        <div class="form-group">
                          <label for="electricity_bill" class="form-label required">Electricity Bill</label>
                          <input type="file" class="form-control file-input" id="electricity_bill" name="electricity_bill"
                                 data-preview="electricity_preview" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                          <div id="electricity_preview" class="file-preview"></div>
                        </div>
                      </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="row mt-4">
                      <div class="col-md-12 d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" id="prevBtn3">Previous</button>
                        <button type="button" class="btn btn-primary" id="nextBtn3">Next</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Step 4: Review & Submit -->
              <div class="step-content" id="step4">
                <div class="card p-3 mb-4">
                  <div class="card-header bg-orange text-white fw-bold">
                    <h5 class="m-0">Review & Submit</h5>
                  </div>
                  <div class="card-body">
                    <div class="alert alert-info">
                      <h5><i class="bi bi-info-circle me-2"></i>Please review all information before submitting</h5>
                      <p class="mb-0">Once submitted, you will not be able to make changes to this application.</p>
                    </div>

                    <!-- Review Summary -->
                    <div class="row mb-4">
                      <div class="col-md-12">
                        <h5 class="mb-3">Application Summary</h5>

                        <!-- Basic Information Review -->
                        <div class="card mb-3">
                          <div class="card-header">
                            <h6 class="m-0">Basic Information</h6>
                          </div>
                          <div class="card-body">
                            <div class="row">
                              <div class="col-md-6">
                                <p><strong>Hotel Name:</strong> <span id="review_hotel_name"></span></p>
                                <p><strong>Company Name:</strong> <span id="review_company_name"></span></p>
                                <p><strong>Authorized Person:</strong> <span id="review_authorized_person"></span></p>
                                <p><strong>Region:</strong> <span id="review_region"></span></p>
                                <p><strong>Pin Code:</strong> <span id="review_pincode"></span></p>
                              </div>
                              <div class="col-md-6">
                                <p><strong>Total Area:</strong> <span id="review_total_area"></span> sq.M</p>
                                <p><strong>Total Employees:</strong> <span id="review_total_employees"></span></p>
                                <p><strong>Total Rooms:</strong> <span id="review_total_rooms"></span></p>
                                <p><strong>Star Category:</strong> <span id="review_star_category"></span></p>
                                <p><strong>Commencement Date:</strong> <span id="review_commencement_date"></span></p>
                              </div>
                            </div>
                          </div>
                        </div>

                        <!-- Facilities Review -->
                        <div class="card mb-3">
                          <div class="card-header">
                            <h6 class="m-0">Selected Facilities</h6>
                          </div>
                          <div class="card-body">
                            <div id="review_facilities"></div>
                          </div>
                        </div>

                        <!-- Documents Review -->
                        <div class="card mb-3">
                          <div class="card-header">
                            <h6 class="m-0">Uploaded Documents</h6>
                          </div>
                          <div class="card-body">
                            <div id="review_documents"></div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Declaration -->
                    <div class="row mb-4">
                      <div class="col-md-12">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="declaration" name="declaration">
                          <label class="form-check-label" for="declaration">
                            I hereby declare that all the information provided in this application is true and correct to the best of my knowledge. I understand that any false information may lead to rejection of my application.
                          </label>
                        </div>
                      </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="row mt-4">
                      <div class="col-md-12 d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" id="prevBtn4">Previous</button>
                        <button type="submit" class="btn btn-success" id="submitBtn" disabled>Submit Application</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Modal for Image Preview -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imagePreviewModalLabel">Document Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <img id="modalImage" src="" class="img-fluid" alt="Document Preview">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
  let currentStep = 1;
  const totalSteps = 4;

  // Initialize form validation
  $("#hotelRegistrationForm").validate({
    rules: {
      hotel_name: "required",
      company_name: "required",
      authorized_person: {
        required: true,
        pattern: /^[A-Za-z\s]+$/
      },
      region: "required",
      pincode: {
        required: true,
        digits: true,
        minlength: 6,
        maxlength: 6
      },
      total_area: {
        required: true,
        number: true,
        min: 1
      },
      total_employees: {
        required: true,
        digits: true,
        min: 1
      },
      total_rooms: {
        required: true,
        digits: true,
        min: 6
      },
      hotel_address: "required",
      company_address: "required",
      district: "required",
      applicant_type: "required",
      commencement_date: "required",
      emergency_contact: {
        required: true,
        digits: true,
        minlength: 10,
        maxlength: 10
      },
      mseb_consumer_number: "required",
      star_category: "required",
      electricity_company: "required",
      property_tax_dept: "required",
      water_bill_dept: "required",
      pan_card: "required",
      business_registration: "required",
      fssai_registration: "required",
      declaration_form: "required",
      mpcb_certificate: "required",
      star_certificate: "required",
      water_bill: "required",
      aadhar_card: "required",
      gst_registration: "required",
      building_certificate: "required",
      light_bill: "required",
      fire_noc: "required",
      property_tax: "required",
      electricity_bill: "required"
    },
    messages: {
      authorized_person: {
        pattern: "Only letters and spaces are allowed"
      },
      pincode: {
        digits: "Please enter a valid 6-digit pincode",
        minlength: "Please enter a valid 6-digit pincode",
        maxlength: "Please enter a valid 6-digit pincode"
      },
      total_rooms: {
        min: "Minimum 6 rooms required"
      },
      emergency_contact: {
        digits: "Please enter a valid 10-digit mobile number",
        minlength: "Please enter a valid 10-digit mobile number",
        maxlength: "Please enter a valid 10-digit mobile number"
      }
    },
    errorElement: 'div',
    errorPlacement: function(error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight: function(element, errorClass, validClass) {
      $(element).addClass('is-invalid').removeClass('is-valid');
    },
    unhighlight: function(element, errorClass, validClass) {
      $(element).removeClass('is-invalid').addClass('is-valid');
    }
  });

  // Step Navigation
  function showStep(step) {
    $('.step-content').removeClass('active');
    $('#step' + step).addClass('active');

    // Update progress steps
    $('.step').removeClass('active completed');
    for (let i = 1; i <= step; i++) {
      if (i < step) {
        $('#step' + i).addClass('completed');
        $('.step[data-step="' + i + '"]').addClass('completed');
      } else {
        $('.step[data-step="' + i + '"]').addClass('active');
      }
    }

    // Show/hide navigation buttons
    if (step === 1) {
      $('#prevBtn').hide();
    } else {
      $('#prevBtn').show();
    }

    currentStep = step;
  }

  // Next button click handlers
  $('#nextBtn1').click(function() {
    if ($('#step1').find('input, select, textarea').valid()) {
      showStep(2);
    }
  });

  $('#nextBtn2').click(function() {
    showStep(3);
  });

  $('#nextBtn3').click(function() {
    // Update review section before showing step 4
    updateReviewSection();
    showStep(4);
  });

  // Previous button click handlers
  $('#prevBtn2').click(function() {
    showStep(1);
  });

  $('#prevBtn3').click(function() {
    showStep(2);
  });

  $('#prevBtn4').click(function() {
    showStep(3);
  });

  // Step progress click handlers
  $('.step').click(function() {
    const step = $(this).data('step');
    // Only allow navigation to completed steps or the current step
    if (step <= currentStep) {
      showStep(step);
    }
  });

  // File preview functionality
  $('.file-input').on('change', function (e) {
    const files = this.files;
    const previewId = $(this).data('preview');
    const $preview = $('#' + previewId);

    if (!$preview.length) return;

    $preview.empty();

    if (!files || !files.length) return;

    const file = files[0];
    const maxSize = 5 * 1024 * 1024; // 5MB

    // size check
    if (file.size > maxSize) {
      Swal.fire({
        title: 'File too large',
        text: 'File must be less than 5 MB',
        icon: 'warning',
        confirmButtonText: 'OK'
      });
      this.value = '';
      return;
    }

    const url = URL.createObjectURL(file);
    const mime = file.type;

    // file name
    const fileName = $('<div/>', {
      'class': 'mb-2',
      html: '<strong>Selected file:</strong> ' + file.name
    });
    $preview.append(fileName);

    // preview
    if (mime.startsWith('image/')) {
      const $img = $('<img/>', {
        src: url,
        'class': 'img-thumbnail preview-image',
        css: { maxHeight: '120px', cursor: 'pointer' },
        'data-bs-toggle': 'modal',
        'data-bs-target': '#imagePreviewModal'
      }).click(function() {
        $('#modalImage').attr('src', url);
      });
      $preview.append($img);
    } else if (mime === 'application/pdf') {
      const $link = $('<a/>', {
        href: url,
        target: '_blank',
        'class': 'btn btn-sm btn-outline-primary mt-1'
      }).html('<i class="fa fa-file-pdf me-1"></i> View PDF');
      $preview.append($link);
    } else if (
      mime.includes('word') ||
      file.name.toLowerCase().endsWith('.doc') ||
      file.name.toLowerCase().endsWith('.docx')
    ) {
      const $link = $('<a/>', {
        href: url,
        target: '_blank',
        'class': 'btn btn-sm btn-outline-primary mt-1'
      }).html('<i class="fa fa-file-word me-1"></i> View Document');
      $preview.append($link);
    } else {
      $preview.append(
        $('<div/>', {
          'class': 'text-muted mt-1',
          text: 'Preview not available for this file type'
        })
      );
    }

    // download link
    const $download = $('<a/>', {
      href: url,
      download: file.name,
      'class': 'btn btn-sm btn-outline-secondary mt-2'
    }).html('<i class="fa fa-download me-1"></i> Download');
    $preview.append($download);
  });

  // Declaration checkbox to enable submit button
  $('#declaration').change(function() {
    $('#submitBtn').prop('disabled', !$(this).is(':checked'));
  });

  // Update review section
  function updateReviewSection() {
    // Basic Information
    $('#review_hotel_name').text($('#hotel_name').val());
    $('#review_company_name').text($('#company_name').val());
    $('#review_authorized_person').text($('#authorized_person').val());
    $('#review_region').text($('#region option:selected').text());
    $('#review_pincode').text($('#pincode').val());
    $('#review_total_area').text($('#total_area').val());
    $('#review_total_employees').text($('#total_employees').val());
    $('#review_total_rooms').text($('#total_rooms').val());
    $('#review_star_category').text($('#star_category option:selected').text());
    $('#review_commencement_date').text($('#commencement_date').val());

    // Facilities
    let facilitiesHtml = '<ul class="list-unstyled">';
    $('input[type="checkbox"]:checked').each(function() {
      const label = $(this).closest('.form-check').find('label').text();
      facilitiesHtml += '<li><i class="bi bi-check-circle text-success me-2"></i>' + label + '</li>';
    });
    facilitiesHtml += '</ul>';
    $('#review_facilities').html(facilitiesHtml);

    // Documents
    let documentsHtml = '<ul class="list-unstyled">';
    $('.file-input').each(function() {
      if (this.files.length > 0) {
        const fileName = this.files[0].name;
        const label = $(this).closest('.form-group').find('label').text();
        documentsHtml += '<li><i class="bi bi-file-earmark-text me-2"></i>' + label + ': ' + fileName + '</li>';
      }
    });
    documentsHtml += '</ul>';
    $('#review_documents').html(documentsHtml);
  }

  // Form submission
  $('#hotelRegistrationForm').on('submit', function(e) {
    if (!$('#declaration').is(':checked')) {
      e.preventDefault();
      Swal.fire({
        title: 'Declaration Required',
        text: 'Please accept the declaration before submitting the application.',
        icon: 'warning',
        confirmButtonText: 'OK'
      });
      return false;
    }

    // Show loading state
    $('#submitBtn').html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Submitting...').prop('disabled', true);
  });

  // Name validation function
  window.validateName = function(event) {
    const char = String.fromCharCode(event.which);
    const pattern = /^[A-Za-z\s]$/;
    return pattern.test(char);
  };
});
</script>
@endpush
