@extends('frontend.layouts2.master')
@section('title', 'Applicant Details')
@push('styles')
@include('frontend.wizard.css')
<style>
    .file-preview {
      background: #f8f9fa;
      text-align: center;
      min-height: 56px;
    }
    .file-preview img {
      max-height: 180px;
      width: auto;
      margin: 5px auto;
      display: block;
      object-fit: contain;
    }
    </style>

@endpush
@section('content')
<section class="section">
    <div class="section-header form-header">
      <h1 class="fw-bold">Application Form for the {{ $application_form->name ?? '' }}</h1>
    </div>

  @include('frontend.wizard._stepper')

    {{-- <div class="card-header bg-success text-white fw-bold">
        <i class="fas fa-info-circle me-2"></i>Overview of Project
    </div>
    <div class="form-step active border border-warning border-10 p-3" id="step1"> --}}

        <h4 class="section-title">
            <i class="bi bi-person-badge"></i>
             Details of the Applicant
        </h4>
        <form id="villaRegistrationForm" data-wizard method="POST"  enctype="multipart/form-data"
        action="{{ route('wizard.applicant.save', $application) }}"
        class="card card-wizard p-4 border-0 shadow-sm">
    @csrf

        <input type="hidden" name="application_id" value="{{ $application->id ?? '' }}">

        <div class="row mb-3">
            <div class="col-md-6 form-group">
                <label for="applicantName" class="form-label required">
                    <i class="bi bi-person form-icon"></i> Name of the Applicant (Owner of the Unit)
                </label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" readonly
                    id="applicantName" name="name"
                    value="{{ old('name', Auth::user()->name ?? optional($application->applicant)->name ?? '') }}"
                    inputmode="text" autocomplete="organization"
                    oninput="
                     this.value = this.value.replace(/[^A-Za-z\s.'-]/g, '');
                    this.value = this.value.replace(/\b\w/g, function(l) { return l.toUpperCase(); });
                    "
                    pattern="[A-Za-z\s.'-]{2,120}"
                    title="Only letters, spaces, dot (.), apostrophe (') and hyphen (-) allowed"
                    maxlength="120" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6 form-group">
                <label for="phone" class="form-label required">
                    <i class="bi bi-telephone form-icon"></i> Telephone/Mobile No of Applicant
                </label>
                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                    id="applicantPhone" pattern="^[6-9][0-9]{9}$"
                    maxlength="10" name="phone"
                    value="{{ old('phone', Auth::user()->phone ?? optional($application->applicant)->phone ?? '') }}"
                    required>
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6 form-group">
                <label for="applicantEmail" class="form-label required">
                    <i class="bi bi-envelope form-icon"></i> E-Mail ID of Applicant
                </label>
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                    id="email" name="email"
                    value="{{ old('email', Auth::user()->email ?? optional($application->applicant)->email ?? '') }}"
                    required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6 form-group">
                <label for="businessName" class="form-label required">
                    <i class="bi bi-building form-icon"></i> Name of the Business
                </label>
                <input type="text" class="form-control @error('business_name') is-invalid @enderror"
                    id="businessName" name="business_name"
                    value="{{ old('business_name', optional($application->applicant)->business_name) }}"
                    inputmode="text" autocomplete="organization"
                    oninput="
                    this.value = this.value.replace(/[^A-Za-z\s.'-]/g, '');
                    this.value = this.value.replace(/\b\w/g, function(l) { return l.toUpperCase(); });"
                    pattern="[A-Za-z\s.'-]{2,120}"
                    title="Only letters, spaces, dot (.), apostrophe (') and hyphen (-) allowed"
                    maxlength="120" required>
                @error('business_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6 form-group">
                <label for="businessType" class="form-label required">
                    <i class="bi bi-diagram-3 form-icon"></i> Type of Business
                </label>

                <select class="form-control @error('business_type') is-invalid @enderror"
                        id="businessType"
                        name="business_type"
                        required>

                    <option value="" disabled
                        {{ old('business_type', optional($application->applicant)->business_type) ? '' : 'selected' }}>
                        Select Business Type
                    </option>

                    @foreach ($business_type as $bt)
                        <option value="{{ $bt->id }}"
                            {{ old('business_type', optional($application->applicant)->business_type) == $bt->id ? 'selected' : '' }}>
                            {{ $bt->name }}
                        </option>
                    @endforeach

                </select>

                @error('business_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 form-group">
                <label for="panNumber" class="form-label required">
                    <i class="bi bi-credit-card form-icon"></i> PAN Card Number
                </label>
                <input type="text" class="form-control @error('pan') is-invalid @enderror text-uppercase"
                    id="panNumber" name="pan"
                    value="{{ old('pan', optional($application->applicant)->pan) }}"
                    pattern="^[A-Z]{5}[0-9]{4}[A-Z]$"
                    title="Enter a valid 10-character PAN, e.g., ABCDE1234F"
                    oninput="
                    this.value = this.value.toUpperCase();
                    this.value = this.value.replace(/[^A-Z0-9]/g, '');
                    if (this.value.length > 10) this.value = this.value.slice(0, 10);"
                    maxlength="10" required>
                <div class="info-text text-info">Format: 5 letters, 4 digits, 1 letter (e.g., ABCDE1234F)</div>
                @error('pan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6 form-group">
                <label for="businessPanNumber" class="form-label">
                    <i class="bi bi-building form-icon"></i> Business PAN Card Number (If applicable)
                </label>
                <input type="text" class="form-control @error('business_pan') is-invalid @enderror"
                    id="businessPanNumber" name="business_pan"
                    value="{{ old('business_pan', optional($application->applicant)->business_pan) }}"
                    pattern="^[A-Z]{5}[0-9]{4}[A-Z]$"
                    title="Enter a valid 10-character PAN, e.g., ABCDE1234F"
                    oninput="
                    this.value = this.value.toUpperCase();
                    this.value = this.value.replace(/[^A-Z0-9]/g, '');
                    if (this.value.length > 10) this.value = this.value.slice(0, 10);"
                    maxlength="10">
                <div class="info-text text-info">Format: 5 letters, 4 digits, 1 letter (e.g., ABCDE1234F)</div>
                @error('business_pan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 form-group">
                <label for="aadharNumber" class="form-label required">
                    <i class="bi bi-person-vcard form-icon"></i> Aadhar Number of Applicant
                </label>
                <input type="number" class="form-control @error('aadhaar') is-invalid @enderror"
                    id="aadharNumber" name="aadhaar"
                    pattern="^\d{12}$" maxlength="12"
                    value="{{ old('aadhaar', Auth::user()->aadhar ?? optional($application->applicant)->aadhaar ?? '') }}"
                    required>
                @error('aadhaar')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6 form-group">
                <label for="udyamAadharNumber" class="form-label">
                    <i class="bi bi-file-earmark-text form-icon"></i> Udyam Registration Number (URN) (If applicable)
                </label>
                <input type="text" class="form-control @error('udyam') is-invalid @enderror"
                    id="udyamAadharNumber" name="udyam"
                    value="{{ old('udyam', optional($application->applicant)->udyam) }}">
                <div class="info-text text-info">Leave blank if not applicable/(URN) UDYAM-MH-19-0054448</div>
                @error('udyam')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 form-group">
                <label for="ownershipProof" class="form-label required">
                    <i class="bi bi-house-door form-icon"></i> Proof of Ownership of the Property
                </label>
                <select class="form-control @error('ownership_proof_type') is-invalid @enderror"
                    id="ownershipProof" name="ownership_proof_type" required>
                    <option value="" selected disabled>Select Proof Type</option>
                    <option value="7/12 document" {{ old('ownershipProof', optional($application->applicant)->ownership_proof_type) == '7/12 document' ? 'selected' : '' }}>
                        7/12 document
                    </option>
                    <option value="Property Tax Receipts" {{ old('ownership_proof_type', optional($application->applicant)->ownership_proof_type) == 'Property Tax Receipts' ? 'selected' : '' }}>
                        Property Tax Receipts
                    </option>
                    <option value="Property Card" {{ old('ownership_proof_type', optional($application->applicant)->ownership_proof_type) == 'Property Card' ? 'selected' : '' }}>
                        Property Card
                    </option>
                    <option value="Other" {{ old('ownership_proof_type', optional($application->applicant)->ownership_proof_type) == 'Other' ? 'selected' : '' }}>
                        Other
                    </option>
                </select>
                @error('ownershipProof')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- <div class="row mb-3">
            <div class="col-md-6 form-group">
                <label for="ownershipProofFile" class="form-label required">
                    <i class="bi bi-house-door form-icon"></i> Ownership Proof (PDF/Image)
                </label>
                <input type="file" name="ownership_proof" class="form-control @error('ownership_proof') is-invalid @enderror"
                    accept="image/*,.pdf">
                @error('ownership_proof')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div> --}}

        {{-- State, District & Ownership Proof (PDF/Image) --}}
<div class="row mb-3 align-items-start">

    <div class="col-md-4 form-group">
        <label for="state" class="form-label required">
            <i class="bi bi-geo-alt form-icon"></i> State
        </label>

        <select id="state" name="state" class="form-control @error('state') is-invalid @enderror" required>
            <option value="" disabled>Select State</option>

            <option value="{{ $States->name }}" selected>
                {{ $States->name }}
            </option>
        </select>

        @error('state')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4 form-group">
        <label for="district" class="form-label required">
            <i class="bi bi-map form-icon"></i> District
        </label>

        <select id="district"
                name="district"
                class="form-control @error('district') is-invalid @enderror"
                required>

            <option value="" disabled
                {{ old('district', optional($application->applicant)->district) ? '' : 'selected' }}>
                Select District
            </option>

            @foreach ($Districts as $dist)
                <option value="{{ $dist->name }}"
                    {{ old('district', optional($application->applicant)->district) == $dist->name ? 'selected' : '' }}>
                    {{ $dist->name }}
                </option>
            @endforeach

        </select>

        @error('district')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


    {{-- Ownership Proof --}}
    <div class="col-md-4 form-group">
      <label for="ownershipProofFile" class="form-label required">
        <i class="bi bi-house-door form-icon"></i> Ownership Proof (PDF/Image)
      </label>
      <input type="file"
             id="ownershipProofFile"
             name="ownership_proof"
             class="form-control @error('ownership_proof') is-invalid @enderror"
             accept=".pdf,.jpg,.jpeg,.png">
      @error('ownership_proof')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror

      {{-- Preview box --}}
      <div id="ownershipPreview" class="file-preview border rounded mt-2 p-2 d-none">
        {{-- Filled via JS --}}
      </div>
      
    </div>
  </div>


        <div class="row mb-3">
            <div class="col-md-6 form-group">
                @php($rented = old('is_property_rented', optional($application->applicant)->is_property_rented))
                <label for="is_property_rented" class="form-label required">
                    <i class="bi bi-house-gear form-icon"></i> Is the property rented to an operator?
                </label>
                <select class="form-control @error('is_property_rented') is-invalid @enderror"
                    id="is_property_rented" name="is_property_rented" required>
                    <option value="" selected disabled>Select Yes/No</option>
                    <option value="1" {{ old('is_property_rented', optional($application->applicant)->is_property_rented) == '1' ? 'selected' : '' }}>
                        Yes
                    </option>
                    <option value="0" {{ old('is_property_rented', optional($application->applicant)->is_property_rented) == '0' ? 'selected' : '' }}>
                        No
                    </option>
                </select>
                @error('is_property_rented')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6 form-group" id="operatorNameContainer" style="display: {{ $rented ? 'block' : 'none' }};">
                <label for="operatorName" class="form-label">
                    <i class="bi bi-person-gear form-icon"></i> Operator's Name
                </label>
                <input type="text" class="form-control @error('operator_name') is-invalid @enderror"
                    id="operatorName" name="operator_name"
                    value="{{ old('operator_name', optional($application->applicant)->operator_name) }}"
                    oninput="
                    this.value = this.value.replace(/[^A-Za-z\s.'-]/g, '');
                    this.value = this.value.replace(/\b\w/g, function(l) { return l.toUpperCase(); });">
                @error('operator_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3 form-group" id="rentalAgreementContainer" style="display: {{ $rented ? 'block' : 'none' }};">
            <label for="rentalAgreement" class="form-label">
                <i class="bi bi-file-earmark-arrow-up form-icon"></i> Share copy the rental agreement / management contract executed with the operator
            </label>
            <div class="upload-area @error('rental_agreement') is-invalid @enderror" id="rentalAgreementUpload" role="button" tabindex="0">
                <i class="bi bi-cloud-arrow-up upload-icon"></i>
                <p>Click to upload or drag and drop</p>
                <p class="info-text text-info">Supported formats: PDF, JPG, PNG (Max 5MB)</p>
                <span id="raFileName" class="small text-muted d-block"></span>
                <input type="file" class="d-none" id="rentalAgreement" name="rental_agreement" accept=".pdf,.jpg,.jpeg,.png">
              </div>
            @error('rental_agreement')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="sticky-actions d-flex justify-content-between mt-2">
            <a href="{{ route('applications.index') }}" class="btn btn-light">
                <i class="bi bi-arrow-left"></i> Back
            </a>
            <button type="submit" class="btn btn-primary" style="
                background-color: #ff6600;
                color: #fff;
                font-weight: 700;
                border: none;
                border-radius: 8px;
                padding: 0.6rem 1.5rem;
                cursor: pointer;
                transition: none !important;
            ">
                Save & Next <i class="bi bi-arrow-right"></i>
            </button>
        </div>
    </div>
  </form>
  </div>
</section>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script>
$(document).ready(function() {
    // Toggle operator name and rental agreement based on property rented selection
    $('#is_property_rented').on('change', function() {
        const isRented = $(this).val() === '1';
        $('#operatorNameContainer, #rentalAgreementContainer').toggle(isRented);

        if (!isRented) {
            $('#operatorName').val('');
        }
    });

    // File upload functionality
    const rentalAgreementUpload = document.getElementById('rentalAgreementUpload');
    const rentalAgreementInput = document.getElementById('rentalAgreement');

    if (rentalAgreementUpload) {
        rentalAgreementUpload.addEventListener('click', function() {
            rentalAgreementInput.click();
        });

        rentalAgreementInput.addEventListener('change', function () {
  if (this.files.length > 0) {
    // save reference to the SAME input element (it holds the selected file)
    const inputEl = this;

    rentalAgreementUpload.innerHTML = `
      <i class="bi bi-file-check text-success upload-icon"></i>
      <p>File selected: ${inputEl.files[0].name}</p>
      <p class="info-text text-info">Click to change file</p>
    `;

    // put the SAME input back into the DOM so it submits
    inputEl.classList.add('d-none');
    inputEl.accept = ".pdf,.jpg,.jpeg,.png";
    rentalAgreementUpload.appendChild(inputEl);     // ✅ re-attach

    // re-bind click to reopen picker
    rentalAgreementUpload.addEventListener('click', () => inputEl.click());
  }
});

    }




    // jQuery Validation
    const validator = $("#villaRegistrationForm").validate({
        ignore: [], // hidden fields भी validate होंगे जब depends true होगा
        errorElement: "div",
        errorClass: "invalid-feedback",
        highlight: function(el) {
            $(el).addClass("is-invalid");
        },
        unhighlight: function(el) {
            $(el).removeClass("is-invalid");
        },
        errorPlacement: function(error, element) {
            if (element.parent(".input-group").length) {
                error.insertAfter(element.parent());
            } else if (element.attr("type") === "file") {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },

        /* ---------- RULES ---------- */
        rules: {
            // Step 1 - Applicant Details
            name: {
                required: true,
                minlength: 2,
                maxlength: 120,
                pattern: /^[A-Za-z\s.'-]+$/
            },
            phone: {
                required: true,
                pattern: /^[6-9][0-9]{9}$/,
                minlength: 10,
                maxlength: 10
            },
            email: {
                required: true,
                email: true
            },
            business_name: {
                required: true,
                minlength: 2,
                maxlength: 120,
                pattern: /^[A-Za-z\s.'-]+$/
            },
            businessType: {
                required: true
            },
            pan: {
                required: true,
                pattern: /^[A-Z]{5}[0-9]{4}[A-Z]$/
            },
            business_pan: {
                pattern: /^$|^[A-Z]{5}[0-9]{4}[A-Z]$/
            },
            aadhaar: {
                required: true,
                pattern: /^\d{12}$/,
                minlength: 12,
                maxlength: 12
            },
            udyam: {
                pattern: /^UDYAM-[A-Z]{2}-\d{2}-\d{7}$/i,
                minlength: 19,
                maxlength: 19
            },
            ownershipProof: {
                required: true
            },
            ownership_proof: {
                required: true,
                accept: "image/*,.pdf",
                filesize: 5120 // 5MB in KB
            },
            is_property_rented: {
                required: true
            },
            operator_name: {
                required: function() {
                    return $('#is_property_rented').val() === '1';
                },
                minlength: 2,
                maxlength: 120,
                pattern: /^[A-Za-z\s.'-]+$/
            },
            rental_agreement: {
                required: function() {
                    return $('#is_property_rented').val() === '1';
                },
                accept: "image/*,.pdf",
                filesize: 5120
            }
        },

        /* ---------- MESSAGES ---------- */
        messages: {
            name: {
                required: "Please enter applicant name",
                minlength: "Name must be at least 2 characters long",
                pattern: "Only letters, spaces, dot (.), apostrophe (') and hyphen (-) allowed"
            },
            phone: {
                required: "Please enter mobile number",
                pattern: "Please enter a valid 10-digit mobile number starting with 6-9"
            },
            email: {
                required: "Please enter email address",
                email: "Please enter a valid email address"
            },
            business_name: {
                required: "Please enter business name",
                minlength: "Business name must be at least 2 characters long",
                pattern: "Only letters, spaces, dot (.), apostrophe (') and hyphen (-) allowed"
            },
            businessType: {
                required: "Please select business type"
            },
            pan: {
                required: "Please enter PAN number",
                pattern: "Please enter a valid PAN number (e.g., ABCDE1234F)"
            },
            business_pan: {
                pattern: "Please enter a valid PAN number (e.g., ABCDE1234F)"
            },
            aadhaar: {
                required: "Please enter Aadhar number",
                pattern: "Please enter a valid 12-digit Aadhar number",
                minlength: "Aadhar number must be exactly 12 digits",
                maxlength: "Aadhar number must be exactly 12 digits"
            },
            udyam: {

                minlength: "Udyam Aadhar must be exactly 19 digits",
                maxlength: "Udyam Aadhar must be exactly 19 digits"
            },
            ownershipProof: {
                required: "Please select ownership proof type"
            },
            ownership_proof: {
                required: "Please upload ownership proof document",
                accept: "Please upload only PDF, JPG, or PNG files",
                filesize: "File size must be less than 5MB"
            },
            is_property_rented: {
                required: "Please select if property is rented"
            },
            operator_name: {
                required: "Please enter operator name",
                minlength: "Operator name must be at least 2 characters long",
                pattern: "Only letters, spaces, dot (.), apostrophe (') and hyphen (-) allowed"
            },
            rental_agreement: {
                required: "Please upload rental agreement",
                accept: "Please upload only PDF, JPG, or PNG files",
                filesize: "File size must be less than 5MB"
            }
        },

        /* ---------- CUSTOM VALIDATION METHODS ---------- */
        methods: {
            filesize: function(value, element, param) {
                if (element.files.length > 0) {
                    const fileSize = element.files[0].size / 1024; // in KB
                    return this.optional(element) || (fileSize <= param);
                }
                return true;
            }
        }
    });

    // Add custom validation method for file size
    $.validator.addMethod("filesize", function(value, element, param) {
        if (element.files.length > 0) {
            const fileSize = element.files[0].size / 1024; // in KB
            return this.optional(element) || (fileSize <= param);
        }
        return true;
    }, "File size must be less than {0} KB");

    // Real-time validation on input change
    $('input, select').on('blur change', function() {
        validator.element(this);
    });
});
</script>


    <script>
$(function () {
  const $input   = $('#ownershipProofFile');
  const $preview = $('#ownershipPreview');
  let lastObjectURL = null;

  function resetPreview() {
    if (lastObjectURL) URL.revokeObjectURL(lastObjectURL);
    lastObjectURL = null;
    $preview.empty().addClass('d-none');
  }

  $input.on('change', function () {
    resetPreview();
    const file = this.files && this.files[0] ? this.files[0] : null;
    if (!file) return;

    const type = file.type.toLowerCase();
    const url  = URL.createObjectURL(file);
    lastObjectURL = url;

    if (type.startsWith('image/')) {
      // Show image
      $preview
        .removeClass('d-none')
        .html('<img alt="Preview" class="img-fluid rounded shadow-sm">')
        .find('img').attr('src', url);
    } else if (type === 'application/pdf' || /\.pdf$/i.test(file.name)) {
      // Show PDF link
      $preview
        .removeClass('d-none')
        .html(
          `<a href="${url}" target="_blank" class="btn btn-outline-primary btn-sm">
             <i class="bi bi-file-earmark-pdf"></i> Open PDF in new tab
           </a>`
        );
    } else {
      resetPreview();
    }
  });

  $(window).on('beforeunload', resetPreview);
});
</script>


@endpush
