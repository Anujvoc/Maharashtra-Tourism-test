{{-- resources/views/frontend/Application/provisional/step1.blade.php --}}

@extends('frontend.layouts2.master')

@section('title', 'Step 2: General Details')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
</style>
@endpush
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
</style>
@endpush

@section('content')
<section class="section">
<section class="section">
    <div class="section-header d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-3">
        <h1 class="mb-2 mb-md-0">
            <i class="fa-solid fa-route" style="color:#ff6600;"></i>
            Application for {{ $application_form->name ?? 'Adventure Tourism Provisional Certificate Registration' }}
        </h1>
        <a href="{{ url()->previous() }}"
           class="text-white fw-bold d-inline-flex align-items-center no-underline"
           style="background-color:#3006ea; border:none; border-radius:8px; padding:.4rem 1.3rem;">
            <i class="bi bi-arrow-left me-2 mx-2"></i> Back
        </a>
    </div>
</section>

    {{-- Stepper / Progress --}}
    @include('frontend.Application.AdventureApplications.provisional._stepper',['step' => $step])

    {{-- MAIN CARD --}}
    <div class="card shadow-sm mb-4" > 
        <div class="card-header"
             style="background:#ff6600;
                    color:#fff;
                    padding:.65rem 1rem;
                    font-weight:700;
                    display:flex;
                    align-items:center;
                    gap:.5rem;">
            <i class="bi bi-person-badge"></i>
            <span>Step 2: Applicant / General Details</span>
        </div>

          <div class="card-body" style="border-top: 3px solid var(--brand); border-radius: 12px; overflow: hidden;">


                   
      <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="adventure_category" class="form-label required">
                        <i class="bi bi-flag form-icon"></i> Adventure Activity Category
                    </label>
                    <select id="adventure_category" name="adventure_category" class="form-control {{ $errors->has('adventure_category') ? 'is-invalid' : '' }}">
                        <option value="">Select</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('adventure_category', $application->adventure_category ?? '') == $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                    @error('adventure_category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="activity_name" class="form-label">
                        <i class="bi bi-card-text form-icon"></i> Activity Name Beach/Location Name (if applicable)
                    </label>
                    <input id="activity_name" type="text" name="activity_name" pattern="^[A-Za-z\s]+$"
                    title="Only letters and spaces are allowed" required  onkeypress="return validateName(event)"
                           class="form-control {{ $errors->has('activity_name') ? 'is-invalid' : '' }}"
                           value="{{ old('activity_name', $application->activity_name ?? '') }}">
                    @error('activity_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

         <!-- Activity Location (full width) -->
        

        <div class="form-group mb-3">
            <label for="activity_location" class="form-label">
                <i class="bi bi-geo-fill form-icon"></i> Activity Location Address
            </label>
            <textarea id="activity_location" name="activity_location" required
                      rows="3"
                      minlength="10"
                      maxlength="500"
                      class="form-control {{ $errors->has('activity_location') ? 'is-invalid' : '' }}"
                      placeholder="Enter complete address including street, city, state, and pin code , ">{{ old('activity_location', $application->activity_location ?? '') }}</textarea>
            @error('activity_location')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <small class="text-muted float-end">
                <span id="charCounter" class="text-success fw-bold">Applicant can add multiple addresses by numbering them as 1, 2, 3 ...</span>
            </small>
        </div>

        <div class="form-group mb-3">
            <label for="activity_location" class="form-label">
                <i class="bi bi-geo-fill form-icon"></i> Specify Your Activity (if not listed above)
            </label>
            <input id="activity_location" type="text" name="specify_activity"
                   class="form-control {{ $errors->has('activity_location') ? 'is-invalid' : '' }}"
                   value="{{ old('activity_location', $application->activity_location ?? '') }}">
            @error('activity_location')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

          {{-- ══ SECTION 2: Organization Details ══ --}}
        <div class="section-title">
            <i class="fa-solid fa-building"></i> Land Activity Documents (File Size 5 MB)<span class="text-danger">(Please Upload PDF Only *)</span>
        </div>
        
                <div class="card-body" style="border-top: 3px solid var(--brand); border-radius: 12px; overflow: hidden;">
<div class="row mb-3">
            

           <div class="col-md-4">
        <div class="form-group mb-3">
            <label for="land_trainer_first_aid_provider_certificate_file" class="form-label required">
                <i class="bi bi-file-earmark-person form-icon"></i> Trainer First Aid Provider Certificate 
            </label>
            <input id="land_trainer_first_aid_provider_certificate" type="file" name="land_trainer_first_aid_provider_certificate" accept="image/*,application/pdf"
                   class="form-control {{ $errors->has('land_trainer_first_aid_provider_certificate') ? 'is-invalid' : '' }}">
            @if(!empty($application->aadhar_file))
                <div class="small mt-1" id="land_trainer_first_aid_provider_certificate_existing">Current: <a href="" target="_blank">View</a></div>
            @endif
            @error('aadhar_file')<div class="text-danger small">{{ $message }}</div>@enderror
            <div class="preview-container" id="land_trainer_first_aid_provider_certificate_preview_container" style="display:none;">
                <div class="small text-muted mb-1" id="land_deposit_challan_filename"></div>
                <img id="land_trainer_first_aid_provider_certificate_preview_img" alt="Trainer Aid Provider preview" style="max-width:100%; max-height:220px; display:none; border-radius:6px; box-shadow:0 2px 6px rgba(0,0,0,.08);" />
                <div id="land_trainer_first_aid_provider_certificate_preview_pdf" class="preview-pdf" style="display:none;">
                    <a id="land_trainer_first_aid_provider_certificate_preview_pdf_link" target="_blank" class="d-inline-block">Open PDF</a>
                </div>
            </div>
        </div>
    </div>
           <div class="col-md-4">
        <div class="form-group mb-3">
            <label for="land_deposit_challan_file" class="form-label required">
                <i class="bi bi-file-earmark-person form-icon"></i> Deposit Challan (File Size 5 MB)
            </label>
            <input id="land_deposit_challan" type="file" name="land_deposit_challan" accept="image/*,application/pdf"
                   class="form-control {{ $errors->has('land_deposit_challan') ? 'is-invalid' : '' }}">
            @if(!empty($application->aadhar_file))
                <div class="small mt-1" id="land_deposit_challan_existing">Current: <a href="" target="_blank">View</a></div>
            @endif
            @error('aadhar_file')<div class="text-danger small">{{ $message }}</div>@enderror
            <div class="preview-container" id="land_deposit_challan_preview_container" style="display:none;">
                <div class="small text-muted mb-1" id="land_deposit_challan_filename"></div>
                <img id="land_deposit_challan_preview_img" alt="Deposite Challan preview" style="max-width:100%; max-height:220px; display:none; border-radius:6px; box-shadow:0 2px 6px rgba(0,0,0,.08);" />
                <div id="land_deposit_challan_preview_pdf" class="preview-pdf" style="display:none;">
                    <a id="land_deposit_challan_preview_pdf_link" target="_blank" class="d-inline-block">Open PDF</a>
                </div>
            </div>
        </div>
    </div>
           <div class="col-md-4">
        <div class="form-group mb-3">
            <label for="land_declaration_trainer_aid_provider_file" class="form-label required">
                <i class="bi bi-file-earmark-person form-icon"></i> Declaration Form For Trainers First Aid Providers 
            </label>
            <input id="land_declaration_trainer_aid_provider" type="file" name="land_declaration_trainer_aid_provider" accept="image/*,application/pdf"
                   class="form-control {{ $errors->has('land_declaration_trainer_aid_provider') ? 'is-invalid' : '' }}">
            @if(!empty($application->aadhar_file))
                <div class="small mt-1" id="land_declaration_trainer_aid_provider_existing">Current: <a href="" target="_blank">View</a></div>
            @endif
            @error('aadhar_file')<div class="text-danger small">{{ $message }}</div>@enderror
            <div class="preview-container" id="land_deposit_challan_preview_container" style="display:none;">
                <div class="small text-muted mb-1" id="land_deposit_challan_filename"></div>
                <img id="land_declaration_trainer_aid_provider_preview_img" alt="Decleration preview" style="max-width:100%; max-height:220px; display:none; border-radius:6px; box-shadow:0 2px 6px rgba(0,0,0,.08);" />
                <div id="land_declaration_trainer_aid_provider_preview_pdf" class="preview-pdf" style="display:none;">
                    <a id="land_declaration_trainer_aid_provider_preview_pdf_link" target="_blank" class="d-inline-block">Open PDF</a>
                </div>
            </div>
        </div>
    </div>
             </div>
             
        <div class="section-title">
            <i class="fa-solid fa-building"></i> Air Activity Documents (File Size 5 MB) <span class="text-danger">(Please Upload PDF Only *)</span>
        </div>
        
                <div class="card-body" style="border-top: 3px solid var(--brand); border-radius: 12px; overflow: hidden;">
<div class="row mb-3">
            

           <div class="col-md-5">
        <div class="form-group mb-3">
            <label for="land_trainer_first_aid_provider_certificate_file" class="form-label ">
                <i class="bi bi-file-earmark-person form-icon"></i>First Aid to the Injured Conducted Training Certificate 
            </label>
            <input id="land_trainer_first_aid_provider_certificate" type="file" name="air_first_aid_injured_conduct_training_certificate" accept="image/*,application/pdf"
                   class="form-control {{ $errors->has('land_trainer_first_aid_provider_certificate') ? 'is-invalid' : '' }}">
            @if(!empty($application->aadhar_file))
                <div class="small mt-1" id="land_trainer_first_aid_provider_certificate_existing">Current: <a href="" target="_blank">View</a></div>
            @endif
            @error('aadhar_file')<div class="text-danger small">{{ $message }}</div>@enderror
            <div class="preview-container" id="land_trainer_first_aid_provider_certificate_preview_container" style="display:none;">
                <div class="small text-muted mb-1" id="land_deposit_challan_filename"></div>
                <img id="land_trainer_first_aid_provider_certificate_preview_img" alt="Trainer Aid Provider preview" style="max-width:100%; max-height:220px; display:none; border-radius:6px; box-shadow:0 2px 6px rgba(0,0,0,.08);" />
                <div id="land_trainer_first_aid_provider_certificate_preview_pdf" class="preview-pdf" style="display:none;">
                    <a id="land_trainer_first_aid_provider_certificate_preview_pdf_link" target="_blank" class="d-inline-block">Open PDF</a>
                </div>
            </div>
        </div>
    </div>
           <div class="col-md-3">
        <div class="form-group mb-3">
            <label for="land_deposit_challan_file" class="form-label ">
                <i class="bi bi-file-earmark-person form-icon"></i> UDYAM Registration Certificate 
            </label>
            <input id="land_deposit_challan" type="file" name="air_adyam_reg_certificate" accept="image/*,application/pdf"
                   class="form-control {{ $errors->has('land_deposit_challan') ? 'is-invalid' : '' }}">
            @if(!empty($application->aadhar_file))
                <div class="small mt-1" id="land_deposit_challan_existing">Current: <a href="" target="_blank">View</a></div>
            @endif
            @error('aadhar_file')<div class="text-danger small">{{ $message }}</div>@enderror
            <div class="preview-container" id="land_deposit_challan_preview_container" style="display:none;">
                <div class="small text-muted mb-1" id="land_deposit_challan_filename"></div>
                <img id="land_deposit_challan_preview_img" alt="Deposite Challan preview" style="max-width:100%; max-height:220px; display:none; border-radius:6px; box-shadow:0 2px 6px rgba(0,0,0,.08);" />
                <div id="land_deposit_challan_preview_pdf" class="preview-pdf" style="display:none;">
                    <a id="land_deposit_challan_preview_pdf_link" target="_blank" class="d-inline-block">Open PDF</a>
                </div>
            </div>
        </div>
    </div>
           <div class="col-md-4">
        <div class="form-group mb-3">
            <label for="land_declaration_trainer_aid_provider_file" class="form-label required">
                <i class="bi bi-file-earmark-person form-icon"></i> Declaration Form For Trainers First Aid Providers 
            </label>
            <input id="land_declaration_trainer_aid_provider" type="file" name="air_declaration_trainer_aid_provider" accept="image/*,application/pdf"
                   class="form-control {{ $errors->has('land_declaration_trainer_aid_provider') ? 'is-invalid' : '' }}">
            @if(!empty($application->aadhar_file))
                <div class="small mt-1" id="land_declaration_trainer_aid_provider_existing">Current: <a href="" target="_blank">View</a></div>
            @endif
            @error('aadhar_file')<div class="text-danger small">{{ $message }}</div>@enderror
            <div class="preview-container" id="land_deposit_challan_preview_container" style="display:none;">
                <div class="small text-muted mb-1" id="land_deposit_challan_filename"></div>
                <img id="land_declaration_trainer_aid_provider_preview_img" alt="Decleration preview" style="max-width:100%; max-height:220px; display:none; border-radius:6px; box-shadow:0 2px 6px rgba(0,0,0,.08);" />
                <div id="land_declaration_trainer_aid_provider_preview_pdf" class="preview-pdf" style="display:none;">
                    <a id="land_declaration_trainer_aid_provider_preview_pdf_link" target="_blank" class="d-inline-block">Open PDF</a>
                </div>
            </div>
        </div>
    </div>
             </div>
            </div>

            <div class="section-title">
            <i class="fa-solid fa-building"></i> Water Activity Documents (File Size 5 MB) <span class="text-danger">(Please Upload PDF Only *)</span>
        </div>
        
                <div class="card-body" style="border-top: 3px solid var(--brand); border-radius: 12px; overflow: hidden;">
<div class="row mb-3">
            

           <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="land_trainer_first_aid_provider_certificate_file" class="form-label required">
                <i class="bi bi-file-earmark-person form-icon"></i> Boat Registration File
            </label>
            <input id="land_trainer_first_aid_provider_certificate" type="file" name="water_boat_registration_certificate" accept="image/*,application/pdf"
                   class="form-control {{ $errors->has('land_trainer_first_aid_provider_certificate') ? 'is-invalid' : '' }}">
            @if(!empty($application->aadhar_file))
                <div class="small mt-1" id="land_trainer_first_aid_provider_certificate_existing">Current: <a href="" target="_blank">View</a></div>
            @endif
            @error('aadhar_file')<div class="text-danger small">{{ $message }}</div>@enderror
            <div class="preview-container" id="land_trainer_first_aid_provider_certificate_preview_container" style="display:none;">
                <div class="small text-muted mb-1" id="land_deposit_challan_filename"></div>
                <img id="land_trainer_first_aid_provider_certificate_preview_img" alt="Trainer Aid Provider preview" style="max-width:100%; max-height:220px; display:none; border-radius:6px; box-shadow:0 2px 6px rgba(0,0,0,.08);" />
                <div id="land_trainer_first_aid_provider_certificate_preview_pdf" class="preview-pdf" style="display:none;">
                    <a id="land_trainer_first_aid_provider_certificate_preview_pdf_link" target="_blank" class="d-inline-block">Open PDF</a>
                </div>
            </div>
        </div>
    </div>
           <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="land_trainer_first_aid_provider_certificate_file" class="form-label required">
                <i class="bi bi-file-earmark-person form-icon"></i> PCC Police Clearance Certificate 
            </label>
            <input id="land_trainer_first_aid_provider_certificate" type="file" name="water_pcc_clearance_certificate" accept="image/*,application/pdf"
                   class="form-control {{ $errors->has('land_trainer_first_aid_provider_certificate') ? 'is-invalid' : '' }}">
            @if(!empty($application->aadhar_file))
                <div class="small mt-1" id="land_trainer_first_aid_provider_certificate_existing">Current: <a href="" target="_blank">View</a></div>
            @endif
            @error('aadhar_file')<div class="text-danger small">{{ $message }}</div>@enderror
            <div class="preview-container" id="land_trainer_first_aid_provider_certificate_preview_container" style="display:none;">
                <div class="small text-muted mb-1" id="land_deposit_challan_filename"></div>
                <img id="land_trainer_first_aid_provider_certificate_preview_img" alt="Trainer Aid Provider preview" style="max-width:100%; max-height:220px; display:none; border-radius:6px; box-shadow:0 2px 6px rgba(0,0,0,.08);" />
                <div id="land_trainer_first_aid_provider_certificate_preview_pdf" class="preview-pdf" style="display:none;">
                    <a id="land_trainer_first_aid_provider_certificate_preview_pdf_link" target="_blank" class="d-inline-block">Open PDF</a>
                </div>
            </div>
        </div>
    </div>
           <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="land_deposit_challan_file" class="form-label required">
                <i class="bi bi-file-earmark-person form-icon"></i> Annual Fee Challan
            </label>
            <input id="land_deposit_challan" type="file" name="water_annual_fee__challan" accept="image/*,application/pdf"
                   class="form-control {{ $errors->has('land_deposit_challan') ? 'is-invalid' : '' }}">
            @if(!empty($application->aadhar_file))
                <div class="small mt-1" id="land_deposit_challan_existing">Current: <a href="" target="_blank">View</a></div>
            @endif
            @error('aadhar_file')<div class="text-danger small">{{ $message }}</div>@enderror
            <div class="preview-container" id="land_deposit_challan_preview_container" style="display:none;">
                <div class="small text-muted mb-1" id="land_deposit_challan_filename"></div>
                <img id="land_deposit_challan_preview_img" alt="Deposite Challan preview" style="max-width:100%; max-height:220px; display:none; border-radius:6px; box-shadow:0 2px 6px rgba(0,0,0,.08);" />
                <div id="land_deposit_challan_preview_pdf" class="preview-pdf" style="display:none;">
                    <a id="land_deposit_challan_preview_pdf_link" target="_blank" class="d-inline-block">Open PDF</a>
                </div>
            </div>
        </div>
    </div>
           <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="land_declaration_trainer_aid_provider_file" class="form-label required">
                <i class="bi bi-file-earmark-person form-icon"></i> Declaration Form For Trainers First Aid Providers 
            </label>
            <input id="land_declaration_trainer_aid_provider" type="file" name="water_declaration_trainer_aid_provider" accept="image/*,application/pdf"
                   class="form-control {{ $errors->has('land_declaration_trainer_aid_provider') ? 'is-invalid' : '' }}">
            @if(!empty($application->aadhar_file))
                <div class="small mt-1" id="land_declaration_trainer_aid_provider_existing">Current: <a href="" target="_blank">View</a></div>
            @endif
            @error('aadhar_file')<div class="text-danger small">{{ $message }}</div>@enderror
            <div class="preview-container" id="land_deposit_challan_preview_container" style="display:none;">
                <div class="small text-muted mb-1" id="land_deposit_challan_filename"></div>
                <img id="land_declaration_trainer_aid_provider_preview_img" alt="Decleration preview" style="max-width:100%; max-height:220px; display:none; border-radius:6px; box-shadow:0 2px 6px rgba(0,0,0,.08);" />
                <div id="land_declaration_trainer_aid_provider_preview_pdf" class="preview-pdf" style="display:none;">
                    <a id="land_declaration_trainer_aid_provider_preview_pdf_link" target="_blank" class="d-inline-block">Open PDF</a>
                </div>
            </div>
        </div>
    </div>
             </div>
            </div>

      


                {{-- Navigation buttons 
                <div class="d-flex justify-content-between mt-4">
                    @if($step > 1)
                        <a href="{{ route('provisional.wizard.show', [$application, $step - 1]) }}"
                           class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Previous
                        </a>
                    @else
                        <a href="{{ route('applications.index') }}" class="btn btn-primary">
                            <i class="bi bi-arrow-left"></i> Back to Applications
                        </a>
                    @endif


                    <button type="submit" class="btn btn-primary1" style="background:#ff6600;
                    color:#fff;
                    font-weight:700;
                    display:flex;
                    align-items:center;
                    "
                    >
                        Save & Continue <i class="bi bi-arrow-right"></i>
                    </button>
                </div>

                --}}

            </form>
        </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script>
$(document).ready(function() {
    $("#stepForm").validate({
        rules: {
            applicant_name: {
                required: true,
                minlength: 3,
                pattern: /^[a-zA-Z\s]+$/
            },
            company_name: {
                required: true,
                minlength: 2
            },
            enterprise_type: {
                required: true
            },
            aadhar_number: {
                required: true,
                digits: true,
                minlength: 12,
                maxlength: 12
            },
            application_category: {
                required: true
            }
        },
        messages: {
            applicant_name: {
                required: "Applicant Name is required.",
                minlength: "Please enter at least 3 characters.",
                pattern: "Please enter only alphabets and spaces."
            },
            company_name: {
                required: "Company Name is required.",
                minlength: "Please enter at least 2 characters."
            },
            aadhar_number: {
                required: "Aadhar Number is required.",
                digits: "Aadhar Number must contain only digits.",
                minlength: "Aadhar Number must be exactly 12 digits.",
                maxlength: "Aadhar Number must be exactly 12 digits."
            },
            application_category: {
                required: "Please select an application category."
            },
            enterprise_type: {
                required: "Please select the type of enterprise."
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
<script>
     window.validateName = function (event) {
        const charCode = event.which ? event.which : event.keyCode;
        if ((charCode >= 65 && charCode <= 90) ||
            (charCode >= 97 && charCode <= 122) ||
            charCode === 32) {
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
