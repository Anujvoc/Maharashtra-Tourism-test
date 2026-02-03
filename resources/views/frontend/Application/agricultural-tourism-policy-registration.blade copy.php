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
  }
  .form-icon{margin-right:.35rem;}
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
    animation: spin 2s linear infinite;
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
                        <div class="card-header">
                            <div class="col-md-6 col-12">
                                <h3 class="mb-3">Agro Tourism Registration</h3>
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
                                                            <input id="mobile" type="number" name="mobile" pattern="^[6-9][0-9]{9}$" maxlength="10"
                                                                   class="form-control {{ $errors->has('mobile') ? 'is-invalid' : '' }}" readonly
                                                                   value="{{ old('mobile', Auth::user()->phone) }}" onkeypress="return validateWhatsAppInput(event)">
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
                                                    <input type="text" name="applicant_name" class="form-control" pattern="^[A-Za-z\s]+$"
                                                    title="Only letters and spaces are allowed" required  onkeypress="return validateName(event)">
                                                </div>
                                            </div>

                                            {{-- Center name --}}
                                            <div class="mb-3">
                                                <label class="form-label required">Name of Agro Tourism Center</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fa fa-tree form-icon"></i></span>
                                                    <input type="text" name="center_name" onkeypress="return validateName(event)" pattern="^[A-Za-z\s]+$"  title="Only letters and spaces are allowed"  class="form-control" required>
                                                </div>
                                            </div>

                                            {{-- Applicant type --}}
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
                                                        <select name="region_id" class="form-control region_id {{ $errors->has('region_id') ? 'is-invalid' : '' }}"  onchange="get_Region_District(this.value)">
                                                            <option value="" selected disabled>Select Region</option>
                                                            @foreach($regions as $r)
                                                                <option value="{{ $r->id }}" {{ old('region_id', $application->region_id ?? '') == $r->id ? 'selected' : '' }}>
                                                                    {{ $r->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('region_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Select District</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fa fa-map form-icon"></i></span>
                                                        <select name="district_id" class="form-control district_id {{ $errors->has('district_id') ? 'is-invalid' : '' }}">
                                                            <option value="">Select District</option>

                                                        </select>
                                                        @error('district_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-12">
                                                        <label class="form-label required"><i class="fa fa-map-marker-alt form-icon"></i>Full Address of Applicant</label>


                                                            <textarea name="applicant_address" class="form-control" rows="2" required></textarea>

                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-12">
                                                        <label class="form-label"><i class="fa fa-map-marker-alt form-icon"></i>Full Address of Agrotourism Center if Different from Applicant Address</label>


                                                            <textarea name="center_address" class="form-control" rows="2"></textarea>

                                                    </div>
                                                </div>



                                            {{-- Region, District, Land description --}}
                                            <div class="row mb-3">

                                                <div class="col-md-12">
                                                    <label class="form-label required"><i class="fa fa-landmark form-icon"></i>Land Description</label>


                                                        <textarea name="land_description" class="form-control" rows="2" required></textarea>

                                                </div>
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
                                                    <label class="form-check-label" for="facility_accommodation">Accomodation</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-md-3 mt-2">
                                              <h5> Recreational Service</h5>
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
                                    <button type="submit" class="btn btn-primary1 px-4" style="background-color:#ff6600; color:#fff; font-weight:700; border:none; border-radius:8px; padding:.6rem 1.5rem; cursor:pointer;">
                                        <i class="fa fa-save me-2"></i> Preview
                                    </button>
                                    <a href="{{ route('agriculture-registrations.index') }}" class="btn btn-primary px-4" style="background-color:#4210d8; color:#fff; font-weight:700; border:none; border-radius:8px; padding:.6rem 1.5rem; cursor:pointer;">
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
@endsection

@push('scripts')
<script>
    // File preview functionality
    document.querySelectorAll('.file-input').forEach(function(input) {
        input.addEventListener('change', function(e) {
            const files = e.target.files;
            const previewId = e.target.getAttribute('data-preview');
            const preview = document.getElementById(previewId);

            if (!preview) return;

            // Clear previous preview
            preview.innerHTML = '';

            if (!files || files.length === 0) return;

            const file = files[0];
            const maxSize = 5 * 1024 * 1024; // 5MB

            // Check file size
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

            // Create file name display
            const fileName = document.createElement('div');
            fileName.className = 'mb-2';
            fileName.innerHTML = `<strong>Selected file:</strong> ${file.name}`;
            preview.appendChild(fileName);

            // Create preview based on file type
            if (mime.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = url;
                img.className = 'img-thumbnail';
                preview.appendChild(img);
            } else if (mime === 'application/pdf') {
                const link = document.createElement('a');
                link.href = url;
                link.target = '_blank';
                link.className = 'btn btn-sm btn-outline-primary';
                link.innerHTML = '<i class="fa fa-file-pdf me-1"></i> View PDF';
                preview.appendChild(link);
            } else if (mime.includes('word') || file.name.toLowerCase().endsWith('.doc') || file.name.toLowerCase().endsWith('.docx')) {
                const link = document.createElement('a');
                link.href = url;
                link.target = '_blank';
                link.className = 'btn btn-sm btn-outline-primary';
                link.innerHTML = '<i class="fa fa-file-word me-1"></i> View Document';
                preview.appendChild(link);
            } else {
                const text = document.createElement('div');
                text.className = 'text-muted';
                text.textContent = 'Preview not available for this file type';
                preview.appendChild(text);
            }

            // Add download link
            const downloadLink = document.createElement('a');
            downloadLink.href = url;
            downloadLink.download = file.name;
            downloadLink.className = 'btn btn-sm btn-outline-secondary mt-2';
            downloadLink.innerHTML = '<i class="fa fa-download me-1"></i> Download';
            preview.appendChild(downloadLink);
        });
    });

    // Form validation and submission
    document.getElementById('agriForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;

        // Check form validity
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Get form data for preview
        const email = form.email.value;
        const mobile = form.mobile.value;
        const name = form.applicant_name.value;
        const center = form.center_name.value;

        // Create preview HTML
        const htmlPreview = `
            <div style="text-align:left;">
                <p><strong>Applicant:</strong> ${name}</p>
                <p><strong>Email:</strong> ${email}</p>
                <p><strong>Mobile:</strong> ${mobile}</p>
                <p><strong>Center:</strong> ${center}</p>
                <p class="text-muted">Please confirm all details are correct before final submission.</p>
            </div>
        `;

        // Show confirmation dialog
        Swal.fire({
            title: 'Do you want to submit?',
            html: htmlPreview,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, submit',
            cancelButtonText: 'Cancel',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading overlay
                document.getElementById('loadingOverlay').style.display = 'flex';

                // Submit the form
                form.submit();
            }
        });
    });

    // Input validation functions
    function validateWhatsAppInput(event) {
        const charCode = event.which ? event.which : event.keyCode;
        const currentValue = event.target.value;

        // Allow only numeric characters (0-9)
        if (charCode < 48 || charCode > 57) {
            event.preventDefault();
            return false;
        }

        // If it's the first character, ensure it's between 6-9
        if (currentValue.length === 0 && (charCode < 54 || charCode > 57)) {
            event.preventDefault();
            return false;
        }

        // Restrict to 10 digits total
        if (currentValue.length >= 10) {
            event.preventDefault();
            return false;
        }

        return true;
    }

    function validateName(event) {
        const charCode = event.which ? event.which : event.keyCode;

        if ((charCode >= 65 && charCode <= 90) ||
            (charCode >= 97 && charCode <= 122) ||
            (charCode === 32)) {
            return true;
        }

        event.preventDefault();
        return false;
    }
</script>
@endpush
