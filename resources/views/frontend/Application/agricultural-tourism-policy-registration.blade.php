@extends('frontend.layouts2.master')
@section('title', 'Agro-tourism Center Registration Form')

@push('styles')

<style>
  :root{
    --brand: #ff6600;   /* Orange color */
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
  a.no-underline { text-decoration: none !important; }
  a.no-underline:hover { text-decoration: none !important; }

  .file-preview {
    margin-top: 8px;
    padding: 8px;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    background: #f8f9fa;
    min-height: 40px;
  }
  .file-preview img {
    max-height: 120px;
    max-width: 100%;
    display: block;
  }
  .file-preview a {
    display: inline-block;
    margin-top: 5px;
  }

  /* full screen loading overlay */
  .loading-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.7);
    z-index: 9999;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    color: white;
  }
  .loading-spinner {
    border: 5px solid #f3f3f3;
    border-top: 5px solid var(--brand);
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 0.8s linear infinite;
    margin-bottom: 15px;
  }
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
</style>
@endpush

@section('content')

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
    <p>Submitting your application, please wait...</p>
</div>

<!-- Main Content -->
<section class="section">
    <div class="section-header">
        <h1>{{ $application_form->name ?? 'Agro-tourism Center Registration' }}</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="row">
                        <div class="card-header w-100 d-flex flex-wrap justify-content-between align-items-center">
                            <div class="col-md-6 col-12">
                                <h3 class="mb-3">Agro Tourism Registration</h3>
                            </div>

                            <!-- RIGHT SIDE BACK BUTTON -->
                            <div class="col-md-6 col-12 d-flex justify-content-md-end justify-content-start mt-2 mt-md-0">
                                <a href="{{ route('agriculture-registrations.index') }}"
                                   class="text-white fw-bold d-inline-block no-underline"
                                   style="background-color:#3006ea; border:none; border-radius:8px; padding:.4rem 1.3rem;">
                                    <i class="bi bi-arrow-left me-2 mx-2"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="container py-4">

                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <form id="agriForm" action="{{ route('agriculture-registrations.store') }}"
                                  method="POST" enctype="multipart/form-data" novalidate>
                                @csrf

                                <!-- Basic Information Section -->
                                <div class="card mb-4">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0">Basic Information</h5>
                                    </div>
                                    <div class="card-body">

                                        {{-- Row: Email + Mobile --}}
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="email" class="form-label required">
                                                        Email Id
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fa fa-envelope form-icon"></i></span>
                                                        <input id="email" type="email" name="email" readonly
                                                               class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                                               value="{{ old('email', Auth::user()->email) }}">
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
                                                        <span class="input-group-text"><i class="fa fa-phone form-icon"></i></span>
                                                        <input id="mobile" type="text" name="mobile"
                                                               class="form-control {{ $errors->has('mobile') ? 'is-invalid' : '' }}"
                                                               value="{{ old('mobile', Auth::user()->phone) }}"
                                                               onkeypress="return validateWhatsAppInput(event)" readonly>
                                                        @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Full name --}}
                                        <div class="mb-3">
                                            <label class="form-label required">
                                                Full Name of Applicant/Farmer/Company/Firm/Farmer Co-operative Society And Authorized Person
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-user form-icon"></i></span>
                                                <input type="text" name="applicant_name" class="form-control"
                                                       pattern="^[A-Za-z\s]+$"
                                                       title="Only letters and spaces are allowed"
                                                       onkeypress="return validateName(event)" required>
                                            </div>
                                        </div>
                                        <input type="hidden" name="id" value="{{ $id ?? ''}}">
                                        {{-- Center name --}}
                                        <div class="mb-3">
                                            <label class="form-label required">Name of Agro Tourism Center</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-tree form-icon"></i></span>
                                                <input type="text" name="center_name"
                                                       class="form-control"
                                                       pattern="^[A-Za-z\s]+$"
                                                       title="Only letters and spaces are allowed"
                                                       onkeypress="return validateName(event)" required>
                                            </div>
                                        </div>

                                        {{-- Applicant type + Region + District --}}
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label class="form-label">Select Applicant Type</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fa fa-user-tag form-icon"></i></span>
                                                    <select name="applicant_type_id" class="form-control">
                                                        <option value="" selected disabled>Select Applicant Type</option>
                                                        @foreach($applicantTypes as $type)
                                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Select Region</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fa fa-globe form-icon"></i></span>
                                                    <select name="region_id"
                                                            class="form-control region_id {{ $errors->has('region_id') ? 'is-invalid' : '' }}"
                                                            onchange="get_Region_District(this.value)">
                                                        <option value="" selected disabled>Select Region</option>
                                                        @foreach($regions as $r)
                                                            <option value="{{ $r->id }}">{{ $r->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('region_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Select District</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fa fa-map form-icon"></i></span>
                                                    <select name="district_id"
                                                            class="form-control district_id {{ $errors->has('district_id') ? 'is-invalid' : '' }}">
                                                        <option value="">Select District</option>
                                                    </select>
                                                    @error('district_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Addresses --}}
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <label class="form-label required">
                                                    <i class="fa fa-map-marker-alt form-icon"></i>Full Address of Applicant
                                                </label>
                                                <textarea name="applicant_address" class="form-control" rows="2" required></textarea>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <label class="form-label">
                                                    <i class="fa fa-map-marker-alt form-icon"></i>
                                                    Full Address of Agrotourism Center if Different from Applicant Address
                                                </label>
                                                <textarea name="center_address" class="form-control" rows="2"></textarea>
                                            </div>
                                        </div>

                                        {{-- Land description --}}
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <label class="form-label required">
                                                    <i class="fa fa-landmark form-icon"></i>Land Description
                                                </label>
                                                <textarea name="land_description" class="form-control" rows="2" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Type of Agro-tourism Center --}}
                                <div class="card mb-4">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0">Type of Agro-tourism Center: Details of Additional Facilities if Available</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-2">
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="facility_day_trip" id="facility_day_trip">
                                                    <label class="form-check-label" for="facility_day_trip">Day Trip Service</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="facility_accommodation" id="facility_accommodation">
                                                    <label class="form-check-label" for="facility_accommodation">Accommodation</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-md-3 mt-2">
                                                <h5>Recreational Service</h5>
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="facility_play_area_children" id="facility_play_area_children">
                                                    <label class="form-check-label" for="facility_play_area_children">Play Area For Children</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="facility_adventure_games" id="facility_adventure_games">
                                                    <label class="form-check-label" for="facility_adventure_games">Adventure Games</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="facility_rural_games" id="facility_rural_games">
                                                    <label class="form-check-label" for="facility_rural_games">Rural Games</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="facility_agricultural_camping" id="facility_agricultural_camping">
                                                    <label class="form-check-label" for="facility_agricultural_camping">Agricultural Camping</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="facility_horticulture_product_sale" id="facility_horticulture_product_sale">
                                                    <label class="form-check-label" for="facility_horticulture_product_sale">Horticulture and Product Sale</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            <label class="form-label">Do Applicant live in Agro-tourism place?</label>
                                            <div class="input-group" style="max-width:200px;">
                                                <span class="input-group-text"><i class="fa fa-home form-icon"></i></span>
                                                <select name="applicant_live_in_place" class="form-control">
                                                    <option value="yes">Yes</option>
                                                    <option value="no">No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Other Activities --}}
                                <div class="card mb-4">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0">Other Activities Related to Agriculture</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-2">
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="activity_green_house" id="activity_green_house">
                                                    <label class="form-check-label" for="activity_green_house">Green House</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="activity_milk_business" id="activity_milk_business">
                                                    <label class="form-check-label" for="activity_milk_business">Milk Business</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="activity_fisheries" id="activity_fisheries">
                                                    <label class="form-check-label" for="activity_fisheries">Fisheries</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="activity_rop_vatika" id="activity_rop_vatika">
                                                    <label class="form-check-label" for="activity_rop_vatika">RopVatika</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="activity_animal_bird_rearing" id="activity_animal_bird_rearing">
                                                    <label class="form-check-label" for="activity_animal_bird_rearing">Animal-Bird Rearing</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="activity_nature_adventure_tourism" id="activity_nature_adventure_tourism">
                                                    <label class="form-check-label" for="activity_nature_adventure_tourism">Nature Adventure Tourism</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="activity_other" id="activity_other">
                                                    <label class="form-check-label" for="activity_other">Other</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fa fa-edit form-icon"></i></span>
                                                    <input type="text" name="activity_other_text" class="form-control" placeholder="If other, specify">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            <label class="form-label required">If Agro-tourism Center already exists, Information on when it started</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-calendar form-icon"></i></span>
                                                <input type="text" name="center_started_on" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Files --}}
                                <div class="card mb-4">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0">Upload Documents (Max size 5 MB each)</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered align-middle">
                                            <tbody>
                                                <tr>
                                                    <th class="required">Applicants Signature / Stamp</th>
                                                    <td>
                                                        <div class="input-group mb-1">
                                                            <span class="input-group-text"><i class="fa fa-file-signature form-icon"></i></span>
                                                            <input type="file" name="file_signature_stamp"
                                                                   class="form-control file-input" data-preview="preview_signature" required>
                                                        </div>
                                                        <div id="preview_signature" class="file-preview"></div>
                                                    </td>

                                                    <th class="required">Land Documents of the Applicant (7/12 excerpts, 8a)</th>
                                                    <td>
                                                        <div class="input-group mb-1">
                                                            <span class="input-group-text"><i class="fa fa-file-alt form-icon"></i></span>
                                                            <input type="file" name="file_land_documents"
                                                                   class="form-control file-input" data-preview="preview_land_docs" required>
                                                        </div>
                                                        <div id="preview_land_docs" class="file-preview"></div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th>Certificate of Registration</th>
                                                    <td>
                                                        <div class="input-group mb-1">
                                                            <span class="input-group-text"><i class="fa fa-file form-icon"></i></span>
                                                            <input type="file" name="file_registration_certificate"
                                                                   class="form-control file-input" data-preview="preview_reg_cert">
                                                        </div>
                                                        <div id="preview_reg_cert" class="file-preview"></div>
                                                    </td>

                                                    <th>Authorization Letter</th>
                                                    <td>
                                                        <div class="input-group mb-1">
                                                            <span class="input-group-text"><i class="fa fa-file-contract form-icon"></i></span>
                                                            <input type="file" name="file_authorization_letter"
                                                                   class="form-control file-input" data-preview="preview_auth_letter">
                                                        </div>
                                                        <div id="preview_auth_letter" class="file-preview"></div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th class="required">PAN Card</th>
                                                    <td>
                                                        <div class="input-group mb-1">
                                                            <span class="input-group-text"><i class="fa fa-id-card form-icon"></i></span>
                                                            <input type="file" name="file_pan_card"
                                                                   class="form-control file-input" data-preview="preview_pan" required>
                                                        </div>
                                                        <div id="preview_pan" class="file-preview"></div>
                                                    </td>

                                                    <th class="required">Aadhar Card</th>
                                                    <td>
                                                        <div class="input-group mb-1">
                                                            <span class="input-group-text"><i class="fa fa-id-badge form-icon"></i></span>
                                                            <input type="file" name="file_aadhar_card"
                                                                   class="form-control file-input" data-preview="preview_aadhar" required>
                                                        </div>
                                                        <div id="preview_aadhar" class="file-preview"></div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th class="required">Registration Fee Challan</th>
                                                    <td>
                                                        <div class="input-group mb-1">
                                                            <span class="input-group-text"><i class="fa fa-receipt form-icon"></i></span>
                                                            <input type="file" name="file_registration_fee_challan"
                                                                   class="form-control file-input" data-preview="preview_fee" required>
                                                        </div>
                                                        <div id="preview_fee" class="file-preview"></div>
                                                    </td>

                                                    <th class="required">Electricity Bill</th>
                                                    <td>
                                                        <div class="input-group mb-1">
                                                            <span class="input-group-text"><i class="fa fa-bolt form-icon"></i></span>
                                                            <input type="file" name="file_electricity_bill"
                                                                   class="form-control file-input" data-preview="preview_electricity" required>
                                                        </div>
                                                        <div id="preview_electricity" class="file-preview"></div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th class="required">Licence under Food Security Act</th>
                                                    <td>
                                                        <div class="input-group mb-1">
                                                            <span class="input-group-text"><i class="fa fa-utensils form-icon"></i></span>
                                                            <input type="file" name="file_food_security_licence"
                                                                   class="form-control file-input" data-preview="preview_food_lic" required>
                                                        </div>
                                                        <div id="preview_food_lic" class="file-preview"></div>
                                                    </td>

                                                    <th>Building Permission</th>
                                                    <td>
                                                        <div class="input-group mb-1">
                                                            <span class="input-group-text"><i class="fa fa-building form-icon"></i></span>
                                                            <input type="file" name="file_building_permission"
                                                                   class="form-control file-input" data-preview="preview_building">
                                                        </div>
                                                        <div id="preview_building" class="file-preview"></div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th class="required">Declaration Form</th>
                                                    <td>
                                                        <div class="input-group mb-1">
                                                            <span class="input-group-text"><i class="fa fa-file-signature form-icon"></i></span>
                                                            <input type="file" name="file_declaration_form"
                                                                   class="form-control file-input" data-preview="preview_declaration" required>
                                                        </div>
                                                        <div id="preview_declaration" class="file-preview">
                                                            <a href="#" target="_blank" class="text-primary">Download Format</a>
                                                        </div>
                                                    </td>

                                                    <th>Zone Certificate</th>
                                                    <td>
                                                        <div class="input-group mb-1">
                                                            <span class="input-group-text"><i class="fa fa-map form-icon"></i></span>
                                                            <input type="file" name="file_zone_certificate"
                                                                   class="form-control file-input" data-preview="preview_zone">
                                                        </div>
                                                        <div id="preview_zone" class="file-preview"></div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                {{-- Buttons --}}
                                <div class="text-center mb-5">
                                    <button type="button"
                                            class="btn btn-primary1 px-4"
                                            onclick="openPreview()"
                                            style="background-color:#ff6600; color:#fff; font-weight:700; border:none; border-radius:8px; padding:.6rem 1.5rem; cursor:pointer;">
                                        <i class="fa fa-search me-2"></i> Preview
                                    </button>
                                    <button type="button" class="btn btn-primary" id="openmodel">
                                       model
                                      </button>
                                    <a href="{{ route('agriculture-registrations.index') }}"
                                       class="btn btn-primary px-4"
                                       style="background-color:#4210d8; color:#fff; font-weight:700; border:none; border-radius:8px; padding:.6rem 1.5rem; cursor:pointer;">
                                        <i class="fa fa-arrow-left me-2"></i> BACK
                                    </a>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade" tabindex="-1" role="dialog" id="exampleModal">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Modal body text goes here.</p>
        </div>
        <div class="modal-footer bg-whitesmoke br">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
<div class="modal " id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="previewModalLabel">Preview Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    {{-- Basic Information --}}
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Basic Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Email Id</label>
                                    <span id="preview-email" class="d-block border p-2 rounded bg-light"></span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Mobile No.</label>
                                    <span id="preview-mobile" class="d-block border p-2 rounded bg-light"></span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Full Name of Applicant</label>
                                <span id="preview-applicant-name" class="d-block border p-2 rounded bg-light"></span>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Name of Agro Tourism Center</label>
                                <span id="preview-center-name" class="d-block border p-2 rounded bg-light"></span>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Applicant Type</label>
                                    <span id="preview-applicant-type" class="d-block border p-2 rounded bg-light"></span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Region</label>
                                    <span id="preview-region" class="d-block border p-2 rounded bg-light"></span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">District</label>
                                    <span id="preview-district" class="d-block border p-2 rounded bg-light"></span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Full Address of Applicant</label>
                                <span id="preview-applicant-address" class="d-block border p-2 rounded bg-light"></span>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Full Address of Agrotourism Center</label>
                                <span id="preview-center-address" class="d-block border p-2 rounded bg-light"></span>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Land Description</label>
                                <span id="preview-land-description" class="d-block border p-2 rounded bg-light"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Type of Agro-tourism Center --}}
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Type of Agro-tourism Center</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Day Trip Service</label>
                                    <span id="preview-facility-day-trip" class="d-block border p-2 rounded bg-light"></span>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Accommodation</label>
                                    <span id="preview-facility-accommodation" class="d-block border p-2 rounded bg-light"></span>
                                </div>
                            </div>

                            <h6 class="mt-3">Recreational Service</h6>

                            <div class="row mb-2">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Play Area For Children</label>
                                    <span id="preview-facility-play-area-children" class="d-block border p-2 rounded bg-light"></span>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Adventure Games</label>
                                    <span id="preview-facility-adventure-games" class="d-block border p-2 rounded bg-light"></span>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Rural Games</label>
                                    <span id="preview-facility-rural-games" class="d-block border p-2 rounded bg-light"></span>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Agricultural Camping</label>
                                    <span id="preview-facility-agricultural-camping" class="d-block border p-2 rounded bg-light"></span>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Horticulture & Product Sale</label>
                                    <span id="preview-facility-horticulture-product-sale" class="d-block border p-2 rounded bg-light"></span>
                                </div>
                            </div>

                            <div class="mt-3">
                                <label class="form-label fw-bold">Does Applicant live in Agro-tourism place?</label>
                                <span id="preview-applicant-live-in-place" class="d-block border p-2 rounded bg-light"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Other Activities --}}
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Other Activities Related to Agriculture</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Green House</label>
                                    <span id="preview-activity-green-house" class="d-block border p-2 rounded bg-light"></span>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Milk Business</label>
                                    <span id="preview-activity-milk-business" class="d-block border p-2 rounded bg-light"></span>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Fisheries</label>
                                    <span id="preview-activity-fisheries" class="d-block border p-2 rounded bg-light"></span>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">RopVatika</label>
                                    <span id="preview-activity-rop-vatika" class="d-block border p-2 rounded bg-light"></span>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Animal-Bird Rearing</label>
                                    <span id="preview-activity-animal-bird-rearing" class="d-block border p-2 rounded bg-light"></span>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Nature Adventure Tourism</label>
                                    <span id="preview-activity-nature-adventure-tourism" class="d-block border p-2 rounded bg-light"></span>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Other</label>
                                    <span id="preview-activity-other" class="d-block border p-2 rounded bg-light"></span>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Other Activity Details</label>
                                    <span id="preview-activity-other-text" class="d-block border p-2 rounded bg-light"></span>
                                </div>
                            </div>

                            <div class="mt-3">
                                <label class="form-label fw-bold">
                                    If Agro-tourism Center already exists, Information on when it started
                                </label>
                                <span id="preview-center-started-on" class="d-block border p-2 rounded bg-light"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Uploaded Files --}}
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Uploaded Documents</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead class="table-secondary text-center">
                                        <tr>
                                            <th>Document Name</th>
                                            <th>Preview</th>
                                            <th>View / Download</th>
                                        </tr>
                                    </thead>
                                    <tbody id="uploadedFilesTable">
                                        {{-- Filled by JS --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Back</button>
                <button type="button" class="btn btn-primary" id="saveApplication">Save Application</button>
            </div>
        </div>
    </div>
</div>

@endsection
{{-- PREVIEW MODAL (XL) --}}

@push('scripts')

<script>
    $(document).ready(function() {
      // Jab button click ho tab modal show karo
      $('#openmodel').on('click', function() {
        $('#exampleModal').modal('show');
      });
    });
</script>

<script>
$(function () {

    // ------------- FILE PREVIEW IN FORM (IMAGE / PDF / WORD) -------------
    $('.file-input').on('change', function (e) {
        const files     = this.files;
        const previewId = $(this).data('preview');
        const $preview  = $('#' + previewId);

        if (!$preview.length) return;

        $preview.empty();

        if (!files || !files.length) return;

        const file    = files[0];
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

        const url  = URL.createObjectURL(file);
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
                'class': 'img-thumbnail',
                css: { maxHeight: '120px' }
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


    // ------------- PREVIEW BUTTON -> MODAL (XL) -------------
    window.openPreview = function () {
        const form = $('#agriForm')[0];

        // HTML5 validation
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        fillPreviewModal(form);
        $('#previewModal').modal('show');  // bootstrap + jQuery
    };


    // ------------- FILL MODAL DATA (TEXT + FILE TABLE) -------------
    function fillPreviewModal(form) {
        const $f = $(form);

        // helper
        const setText = (id, val) => $('#' + id).text(val || '');

        // basic info
        setText('preview-email',           $f.find('[name=email]').val());
        setText('preview-mobile',          $f.find('[name=mobile]').val());
        setText('preview-applicant-name',  $f.find('[name=applicant_name]').val());
        setText('preview-center-name',     $f.find('[name=center_name]').val());

        const appType = $f.find('[name=applicant_type_id] option:selected').text();
        const region  = $f.find('[name=region_id] option:selected').text();
        const district= $f.find('[name=district_id] option:selected').text();

        setText('preview-applicant-type',  appType === 'Select Applicant Type' ? '' : appType);
        setText('preview-region',          region  === 'Select Region' ? '' : region);
        setText('preview-district',        district=== 'Select District' ? '' : district);

        setText('preview-applicant-address', $f.find('[name=applicant_address]').val());
        setText('preview-center-address',    $f.find('[name=center_address]').val());
        setText('preview-land-description',  $f.find('[name=land_description]').val());

        // yes/no helper
        const yn = (checked) => checked ? 'Yes' : 'No';

        // facilities
        setText('preview-facility-day-trip',               yn(form.facility_day_trip.checked));
        setText('preview-facility-accommodation',          yn(form.facility_accommodation.checked));
        setText('preview-facility-play-area-children',     yn(form.facility_play_area_children.checked));
        setText('preview-facility-adventure-games',        yn(form.facility_adventure_games.checked));
        setText('preview-facility-rural-games',            yn(form.facility_rural_games.checked));
        setText('preview-facility-agricultural-camping',   yn(form.facility_agricultural_camping.checked));
        setText('preview-facility-horticulture-product-sale', yn(form.facility_horticulture_product_sale.checked));

        setText('preview-applicant-live-in-place', $f.find('[name=applicant_live_in_place]').val());

        // activities
        setText('preview-activity-green-house',           yn(form.activity_green_house.checked));
        setText('preview-activity-milk-business',         yn(form.activity_milk_business.checked));
        setText('preview-activity-fisheries',             yn(form.activity_fisheries.checked));
        setText('preview-activity-rop-vatika',            yn(form.activity_rop_vatika.checked));
        setText('preview-activity-animal-bird-rearing',   yn(form.activity_animal_bird_rearing.checked));
        setText('preview-activity-nature-adventure-tourism', yn(form.activity_nature_adventure_tourism.checked));
        setText('preview-activity-other',                 yn(form.activity_other.checked));
        setText('preview-activity-other-text',            $f.find('[name=activity_other_text]').val());

        setText('preview-center-started-on', $f.find('[name=center_started_on]').val());

        // --------- FILES IN TABULAR FORM (IMAGE/PDF PREVIEW) ---------
        const $filesTbody = $('#uploadedFilesTable');
        $filesTbody.empty();

        const uploadedConfig = [
            { label: 'Applicants Signature / Stamp', field: 'file_signature_stamp' },
            { label: 'Land Documents',               field: 'file_land_documents' },
            { label: 'Registration Certificate',     field: 'file_registration_certificate' },
            { label: 'Authorization Letter',         field: 'file_authorization_letter' },
            { label: 'PAN Card',                     field: 'file_pan_card' },
            { label: 'Aadhar Card',                  field: 'file_aadhar_card' },
            { label: 'Registration Fee Challan',     field: 'file_registration_fee_challan' },
            { label: 'Electricity Bill',             field: 'file_electricity_bill' },
            { label: 'Food Security Licence',        field: 'file_food_security_licence' },
            { label: 'Building Permission',          field: 'file_building_permission' },
            { label: 'Declaration Form',             field: 'file_declaration_form' },
            { label: 'Zone Certificate',             field: 'file_zone_certificate' },
        ];

        uploadedConfig.forEach(cfg => {
            const input = form[cfg.field];

            // Agar file hi nahi select ki toh "Not uploaded" row
            if (!input || !input.files || !input.files[0]) {
                $filesTbody.append(`
                    <tr>
                        <td>${cfg.label}</td>
                        <td class="text-center text-muted">Not uploaded</td>
                        <td class="text-center">-</td>
                    </tr>
                `);
                return;
            }

            const file = input.files[0];
            const url  = URL.createObjectURL(file);
            const name = file.name;
            const mime = file.type;
            const ext  = name.split('.').pop().toLowerCase();

            let previewHtml = '';

            if (mime.startsWith('image/')) {
                previewHtml = `<img src="${url}" alt="${name}" class="img-thumbnail" style="max-width:100px; max-height:100px;">`;
            } else if (mime === 'application/pdf') {
                previewHtml = `<embed src="${url}" type="application/pdf" width="100" height="100">`;
            } else {
                previewHtml = `<span class="text-muted">No preview</span>`;
            }

            const actionHtml = `
                <a href="${url}" target="_blank" class="btn btn-sm btn-outline-primary me-1">View</a>
                <a href="${url}" download="${name}" class="btn btn-sm btn-outline-secondary">Download</a>
            `;

            $filesTbody.append(`
                <tr>
                    <td>${cfg.label}</td>
                    <td class="text-center">${previewHtml}</td>
                    <td class="text-center">${actionHtml}</td>
                </tr>
            `);
        });
    }


    // ------------- SAVE BUTTON IN MODAL -------------
    $('#saveApplication').on('click', function (e) {
    e.preventDefault(); // direct submit rok do

    Swal.fire({
        title: 'Are you sure you want to submit?',
        text: 'Please review all the fields before submitting. After submit you cannot change the data.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, submit application',
        cancelButtonText: 'No, let me check again'
    }).then((result) => {
        if (result.isConfirmed) {
            // user ne confirm kar diya
            $('#previewModal').modal('hide');
            $('#loadingOverlay').css('display', 'flex');
            $('#agriForm')[0].submit();
        }
        // agar cancel kare to kuch mat karo, user modal me hi rahega
    });
});


    // ------------- VALIDATION HELPERS (GLOBAL FOR onkeypress) -------------
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

});
</script>
@endpush
