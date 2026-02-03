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

@php
    // $application is passed (new or existing)
@endphp

<form id="adventureForm"
      action="{{ isset($application->id) ? route('frontend.adventure-applications.update', $application->id) : route('frontend.adventure-applications.store') }}"
      method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @if(isset($application->id))
        @method('PUT')
    @endif

    <div class="card p-3 mb-4">

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


        <!-- Row: Applicant Type + WhatsApp -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="applicant_type" class="form-label required">
                        <i class="bi bi-person-check form-icon"></i> Applicant Type
                    </label>
                    <select id="applicant_type" name="applicant_type"
                            class="form-control {{ $errors->has('applicant_type') ? 'is-invalid' : '' }}">
                        <option value="" selected disabled>Select Applicant Type</option>
                        @foreach($enterprises as $r)
                        <option value="{{ $r->id }}" {{ old('applicant_type', $application->applicant_type ?? '') == $r->id ? 'selected' : '' }}>
                            {{ $r->name }}
                        </option>
                    @endforeach
                    </select>
                    @error('applicant_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <input type="hidden" name="id" value="{{ $id ?? ''}}">

            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="whatsapp" class="form-label ">
                        <i class="bi bi-whatsapp form-icon"></i> WhatsApp Number
                    </label>
                    <input id="whatsapp" type="number" name="whatsapp" pattern="^[6-9][0-9]{9}$" maxlength="10"
                           class="form-control {{ $errors->has('whatsapp') ? 'is-invalid' : '' }}"
                           value="{{ old('whatsapp', $application->whatsapp ?? '') }}" onkeypress="return validateWhatsAppInput(event)">
                    @error('whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Row: Applicant Name + Company Name -->
        <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="applicant_name" class="form-label required">
                            <i class="bi bi-person form-icon"></i> Name of Applicant
                        </label>
                        <input id="applicant_name" type="text" name="applicant_name" readonly  pattern="^[A-Za-z\s]+$"
                        title="Only letters and spaces are allowed"  onkeypress="return validateName(event)"
                               class="form-control {{ $errors->has('applicant_name') ? 'is-invalid' : '' }}"
                               value="{{ old('applicant_name', $application->applicant_name ?? Auth::user()->name) }}">
                        @error('applicant_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>



            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="company_name" class="form-label required">
                        <i class="bi bi-buildings form-icon"></i> Name of Company
                    </label>
                    <input id="company_name" type="text" name="company_name"  pattern="^[A-Za-z\s]+$"
                    title="Only letters and spaces are allowed" required  onkeypress="return validateName(event)"
                           class="form-control {{ $errors->has('company_name') ? 'is-invalid' : '' }}"
                           value="{{ old('company_name', $application->company_name ?? '') }}">
                    @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Applicant Address (full width) -->
        <div class="form-group mb-3">
            <label for="applicant_address" class="form-label required">
                <i class="bi bi-geo-alt form-icon"></i> Applicant Address
            </label>
            <textarea id="applicant_address" name="applicant_address" rows="3"
                      class="form-control {{ $errors->has('applicant_address') ? 'is-invalid' : '' }}"
                      placeholder="Enter full address">{{ old('applicant_address', $application->applicant_address ?? '') }}</textarea>
            @error('applicant_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <!-- Row: Region + District -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="region_id" class="form-label required">
                        <i class="bi bi-map form-icon"></i> Select Region
                    </label>
                    <select id="region_id" name="region_id" class="form-control {{ $errors->has('region_id') ? 'is-invalid' : '' }}"  onchange="get_Region_District(this.value)">
                        <option value="">Select Region</option>
                        @foreach($regions as $r)
                            <option value="{{ $r->id }}" {{ old('region_id', $application->region_id ?? '') == $r->id ? 'selected' : '' }}>
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
       value="{{ old('district_id', $application->district_id ?? '') }}">

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
                        <i class="bi bi-card-text form-icon"></i> Activity Name
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
            <input id="activity_location" type="text" name="activity_location"
                   class="form-control {{ $errors->has('activity_location') ? 'is-invalid' : '' }}"
                   value="{{ old('activity_location', $application->activity_location ?? '') }}">
            @error('activity_location')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

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



        <!-- Row: PAN + Aadhar (file inputs) -->
<div class="row mb-3">
    <!-- PAN -->
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="pan_file" class="form-label required">
                <i class="bi bi-file-earmark-text form-icon"></i> PAN Card (Max 5 MB)
            </label>
            <input id="pan_file" type="file" name="pan_file" accept="image/*,application/pdf"
                   class="form-control {{ $errors->has('pan_file') ? 'is-invalid' : '' }}">
            @if(!empty($application->pan_file))
                <div class="small mt-1" id="pan_existing">Current: <a href="{{ Storage::url($application->pan_file) }}" target="_blank">View</a></div>
            @endif
            @error('pan_file')<div class="text-danger small">{{ $message }}</div>@enderror

            <div class="preview-container" id="pan_preview_container" style="display:none;">
                <div class="small text-muted mb-1" id="pan_filename"></div>
                <img id="pan_preview_img" alt="PAN preview" style="max-width:100%; max-height:220px; display:none; border-radius:6px; box-shadow:0 2px 6px rgba(0,0,0,.08);" />
                <div id="pan_preview_pdf" class="preview-pdf" style="display:none;">
                    <a id="pan_preview_pdf_link" target="_blank" class="d-inline-block">Open PDF</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Aadhar -->
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="aadhar_file" class="form-label required">
                <i class="bi bi-file-earmark-person form-icon"></i> Aadhar Card (Max 5 MB)
            </label>
            <input id="aadhar_file" type="file" name="aadhar_file" accept="image/*,application/pdf"
                   class="form-control {{ $errors->has('aadhar_file') ? 'is-invalid' : '' }}">
            @if(!empty($application->aadhar_file))
                <div class="small mt-1" id="aadhar_existing">Current: <a href="{{ Storage::url($application->aadhar_file) }}" target="_blank">View</a></div>
            @endif
            @error('aadhar_file')<div class="text-danger small">{{ $message }}</div>@enderror

            <div class="preview-container" id="aadhar_preview_container" style="display:none;">
                <div class="small text-muted mb-1" id="aadhar_filename"></div>
                <img id="aadhar_preview_img" alt="Aadhar preview" style="max-width:100%; max-height:220px; display:none; border-radius:6px; box-shadow:0 2px 6px rgba(0,0,0,.08);" />
                <div id="aadhar_preview_pdf" class="preview-pdf" style="display:none;">
                    <a id="aadhar_preview_pdf_link" target="_blank" class="d-inline-block">Open PDF</a>
                </div>
            </div>
        </div>
    </div>
</div>

        <!-- Buttons (centered){{ route('frontend.adventure-applications.index') }} -->
        <div class="row mt-4">
            <div class="col-12 d-flex justify-content-center align-items-center">
                <a href="#"
                   class="mx-1 text-white fw-bold d-inline-block no-underline"
                   style="background-color:#3006ea; border:none; border-radius:8px; padding:.6rem 1.5rem;">
                    <i class="bi bi-arrow-left me-2"></i> Back
                </a>

                <button type="submit"
                        class="mx-1 text-white fw-bold d-inline-block"
                        style="background-color:var(--brand); border:none; border-radius:8px; padding:.6rem 1.5rem;">
                    Save <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </div>
        </div>

    </div>
</form>
