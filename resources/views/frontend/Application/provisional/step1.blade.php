{{-- resources/views/frontend/Application/provisional/step1.blade.php --}}

@extends('frontend.layouts2.master')

@section('title', 'Step 1: General Details')

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
</style>
@endpush

@section('content')
<section class="section">
    <div class="section-header form-header">
        <h1 class="fw-bold">Application Form for the {{ $application_form->name ?? '' }}</h1>
        {{-- <p class="text-muted mb-0">Please fill out all required fields marked with an asterisk (*)</p> --}}
    </div>

    {{-- Stepper / Progress --}}
    @include('frontend.Application.provisional._stepper',['step' => $step])

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
            <i class="bi bi-person-badge"></i>
            <span>Step 1: Applicant / General Details</span>
        </div>

        <div class="card-body">
            <form id="stepForm"
                  action="{{ route('provisional.wizard.save', [$application, $step]) }}
                  "
                  method="POST" novalidate>
                @csrf

                <div class="row g-3">
                    <!-- 1. Applicant Name -->
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="bi bi-person form-icon"></i>
                            1. Applicant Name <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('applicant_name') is-invalid @enderror"
                               name="applicant_name"
                               required    title="Only letters and spaces are allowed"  onkeypress="return validateName(event)"
                               value="{{ old('applicant_name', $registration->applicant_name) }}">
                        @error('applicant_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- 2. Company Name -->
                    <div class="col-md-6">
                        <label class="form-label">
                            2. Company Name <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('company_name') is-invalid @enderror"
                               name="company_name" pattern="^[A-Za-z\s]+$"
                               required    title="Only letters and spaces are allowed"  onkeypress="return validateName(event)"
                               value="{{ old('company_name', $registration->company_name) }}">
                        @error('company_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                    </div>

                        <!-- 3. Type of Enterprise -->
                    <div class="col-md-6 ">
                        <label for="businessType" class="form-label required">
                          <i class="bi bi-diagram-3 form-icon"></i> Type of Enterprise
                        </label>

                        <select class="form-control @error('enterprise_type') is-invalid @enderror"
                                id="businessType"
                                name="enterprise_type"
                                required>
                          <option value="" disabled {{ old('enterprise_type',$application->business_type) ? '' : 'selected' }}>Select Business Type</option>

                          @foreach ($applicantTypes as $bt)
                            <option value="{{ $bt->id }}"
                              {{ old('enterprise_type', $registration->enterprise_type) == $bt->id ? 'selected' : '' }}>
                              {{ $bt->name }}
                            </option>
                          @endforeach
                        </select>

                        @error('enterprise_type') <div class="invalid-feedback">{{ $message }}</div> @enderror

                    </div>

                      <!-- 4. Aadhar Number -->


                    <div class="col-md-6">
                        <label class="form-label">
                            4. Aadhar Number <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('aadhar_number') is-invalid @enderror"
                               name="aadhar_number"
                               maxlength="12"
                               minlength="12"
                               required  maxlength="12" onkeypress="return validateAadhar(event, this)"
                               value="{{ old('aadhar_number', $registration->aadhar_number) }}">
                        @error('aadhar_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="region_id" class="form-label required">
                                    <i class="bi bi-map form-icon"></i> Select Region
                                </label>
                                <select id="region_id" name="region_id" class="form-control {{ $errors->has('region_id') ? 'is-invalid' : '' }}"  onchange="get_Region_District(this.value)">
                                    <option value="">Select Region</option>
                                    @foreach($regions as $r)
                                        <option value="{{ $r->id }}" {{ old('region_id', $registration->region_id ?? '') == $r->id ? 'selected' : '' }}>
                                            {{ $r->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('region_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="district_id" class="form-label required">
                                    <i class="bi bi-geo form-icon"></i> Select District
                                </label>
                                <select id="district_id" name="district_id" class="form-control district_id {{ $errors->has('district_id') ? 'is-invalid' : '' }}">
                                    <option value="" selected disabled>Select District</option>

                                </select>
                                @error('district_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="old_district"
                   value="{{ old('district_id', $registration->district_id ?? '') }}">

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="application_category" class="form-label required">
                                    <i class="bi bi-flag form-icon"></i> Application Category
                                </label>
                                <select id="application_category" name="application_category" class="form-control {{ $errors->has('application_category') ? 'is-invalid' : '' }}">
                                    <option value="">Select</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('application_category', $registration->application_category ?? '') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('application_category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>


                    <!-- 5. Application Category -->



                {{-- Navigation buttons --}}
                <div class="d-flex justify-content-between mt-4">
                    @if($step > 1)
                        <a href="{{ route('provisional.wizard.show', [$application, $step - 1]) }}"
                           class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Previous
                        </a>
                    @else
                        <a href="{{ route('applications.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Applications
                        </a>
                    @endif

                    <button type="submit" class="btn btn-primary">
                        Save & Continue <i class="bi bi-arrow-right"></i>
                    </button>
                </div>

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
