@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    :root {
        --brand: #ff6600;
        --brand-dark: #e25500;
    }
    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #444;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 7px;
    }
    .form-label i { color: var(--brand); font-size: 15px; }
    .form-control, .form-select {
        border: 1px solid #dde1e7;
        border-radius: 8px;
        font-size: 13.5px;
        padding: 10px 14px;
        color: #333;
        background-color: #fdfdfd;
        transition: border-color .2s, box-shadow .2s;
        height: 44px;
    }
    textarea.form-control { height: auto; min-height: 90px; resize: vertical; }
    .form-control:focus, .form-select:focus {
        border-color: var(--brand);
        box-shadow: 0 0 0 3px rgba(255,102,0,0.1);
        outline: none;
    }
    .form-control[readonly] {
        background-color: #f5f5f5;
        color: #888;
        cursor: not-allowed;
    }
    .required::after { content: " *"; color: #dc3545; font-weight: 700; }
    .section-title {
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        color: var(--brand);
        border-bottom: 2px solid #ffe0cc;
        padding-bottom: 7px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .field-group {
        background: #fafafa;
        border: 1px solid #f0ece8;
        border-radius: 10px;
        padding: 20px 20px 8px 20px;
        margin-bottom: 24px;
    }
    .form-field-wrap { margin-bottom: 20px; }
    .btn-back {
        background-color: #3006ea;
        border: none; border-radius: 8px;
        padding: .65rem 2rem;
        color: #fff; font-weight: 600;
        font-size: 14px; text-decoration: none;
        display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-save {
        background-color: var(--brand);
        border: none; border-radius: 8px;
        padding: .65rem 2rem;
        color: #fff; font-weight: 600;
        font-size: 14px; cursor: pointer;
        display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-back:hover { background-color: #2404c0; color: #fff; }
    .btn-save:hover { background-color: var(--brand-dark); }
</style>
@endpush

<form id="adventureForm"
      action="{{ isset($application->id) ? route('frontend.adventure-applications.update', $application->id) : route('frontend.adventure-applications.store') }}"
      method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @if(isset($application->id)) @method('PUT') @endif
    <input type="hidden" name="id" value="{{ $id ?? '' }}">
    <input type="hidden" id="old_district" value="{{ old('district_id', $application->district_id ?? '') }}">

    <div class="card p-4 mb-4" style="border-top: 3px solid var(--brand); border-radius: 12px; overflow: hidden;">

        {{-- ══ SECTION 1: Account Information ══ --}}
        <div class="section-title">
            <i class="fa-solid fa-circle-user"></i> Account Information
        </div>
        <div class="field-group">
            <div class="row">

                <div class="col-md-6 form-field-wrap">
                    <label for="email" class="form-label required">
                        <i class="fa-solid fa-envelope"></i> Email ID
                    </label>
                    <input id="email" type="email" name="email" readonly
                           class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                           value="{{ old('email', $application->email ?? Auth::user()->email) }}">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 form-field-wrap">
                    <label for="mobile" class="form-label required">
                        <i class="fa-solid fa-mobile-screen"></i> Mobile No.
                    </label>
                    <input id="mobile" type="tel" name="mobile" maxlength="10" readonly
                           class="form-control {{ $errors->has('mobile') ? 'is-invalid' : '' }}"
                           value="{{ old('mobile', $application->mobile ?? Auth::user()->phone) }}">
                    @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 form-field-wrap">
                    <label for="applicant_name" class="form-label required">
                        <i class="fa-solid fa-user"></i> Name of Applicant
                    </label>
                    <input id="applicant_name" type="text" name="applicant_name" readonly
                           pattern="^[A-Za-z\s]+$" title="Only letters and spaces allowed"
                           onkeypress="return validateName(event)"
                           class="form-control {{ $errors->has('applicant_name') ? 'is-invalid' : '' }}"
                           value="{{ old('applicant_name', $application->applicant_name ?? Auth::user()->name) }}">
                    @error('applicant_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 form-field-wrap">
                    <label for="whatsapp" class="form-label">
                        <i class="fa-brands fa-whatsapp"></i> WhatsApp Number
                    </label>
                    <input id="whatsapp" type="tel" name="whatsapp" maxlength="10"
                           onkeypress="return validateWhatsAppInput(event)"
                           placeholder="Enter WhatsApp number"
                           class="form-control {{ $errors->has('whatsapp') ? 'is-invalid' : '' }}"
                           value="{{ old('whatsapp', $application->whatsapp ?? '') }}">
                    @error('whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

            </div>
        </div>

        {{-- ══ SECTION 2: Organization Details ══ --}}
        <div class="section-title">
            <i class="fa-solid fa-building"></i> Organization Details
        </div>
        <div class="field-group">
            <div class="row">

                <div class="col-md-6 form-field-wrap">
                    <label for="applicant_type" class="form-label required">
                        <i class="fa-solid fa-id-badge"></i> Applicant Type
                    </label>
                    <select id="applicant_type" name="applicant_type"
                            class="form-select {{ $errors->has('applicant_type') ? 'is-invalid' : '' }}">
                        <option value="" disabled selected>Select Applicant Type</option>
                        @foreach($enterprises as $r)
                            <option value="{{ $r->id }}"
                                {{ old('applicant_type', $application->applicant_type ?? '') == $r->id ? 'selected' : '' }}>
                                {{ $r->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('applicant_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 form-field-wrap">
                    <label for="applicant_category" class="form-label required">
                        <i class="fa-solid fa-layer-group"></i> Applicant Category
                    </label>
                    <select id="applicant_category" name="applicant_category"
                            class="form-select {{ $errors->has('applicant_category') ? 'is-invalid' : '' }}">
                        <option value="" disabled selected>Select Category</option>
                    </select>
                    @error('applicant_category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 form-field-wrap">
                    <label for="company_name" class="form-label required">
                        <i class="fa-solid fa-building-user"></i> Name of Individual / Company / Partners
                    </label>
                    <input id="company_name" type="text" name="company_name"
                           pattern="^[A-Za-z\s]+$" title="Only letters and spaces allowed"
                           onkeypress="return validateName(event)"
                           placeholder="Enter company or individual name"
                           class="form-control {{ $errors->has('company_name') ? 'is-invalid' : '' }}"
                           value="{{ old('company_name', $application->company_name ?? '') }}">
                    @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 form-field-wrap">
                    <label for="business_start_date" class="form-label">
                        <i class="fa-solid fa-calendar-days"></i> Business Start Date
                    </label>
                    <input id="business_start_date" type="date" name="business_start_date"
                           class="form-control {{ $errors->has('business_start_date') ? 'is-invalid' : '' }}"
                           value="{{ old('business_start_date', $application->business_start_date ?? '') }}">
                    @error('business_start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 form-field-wrap">
                    <label for="authorized_person" class="form-label">
                        <i class="fa-solid fa-user-tie"></i> Authorized Person Name & Designation
                    </label>
                    <input id="authorized_person" type="text" name="authorized_person"
                           pattern="^[A-Za-z\s]+$" title="Only letters and spaces allowed"
                           onkeypress="return validateName(event)"
                           placeholder="Full name and designation"
                           class="form-control {{ $errors->has('authorized_person') ? 'is-invalid' : '' }}"
                           value="{{ old('authorized_person', $application->authorized_person ?? '') }}">
                    @error('authorized_person')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

            </div>
        </div>

        {{-- ══ SECTION 3: Location Details ══ --}}
        <div class="section-title">
            <i class="fa-solid fa-location-dot"></i> Location Details
        </div>
        <div class="field-group">
            <div class="row">

                <div class="col-md-6 form-field-wrap">
                    <label for="region_id" class="form-label required">
                        <i class="fa-solid fa-map"></i> Select Region
                    </label>
                    <select id="region_id" name="region_id"
                            onchange="get_Region_District(this.value)"
                            class="form-select {{ $errors->has('region_id') ? 'is-invalid' : '' }}">
                        <option value="">Select Region</option>
                        @foreach($regions as $r)
                            <option value="{{ $r->id }}"
                                {{ old('region_id', $application->region_id ?? '') == $r->id ? 'selected' : '' }}>
                                {{ $r->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('region_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 form-field-wrap">
                    <label for="district_id" class="form-label required">
                        <i class="fa-solid fa-city"></i> Select District
                    </label>
                    <select id="district_id" name="district_id"
                            class="form-select {{ $errors->has('district_id') ? 'is-invalid' : '' }}">
                        <option value="" disabled selected>Select District</option>
                    </select>
                    @error('district_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 form-field-wrap">
                    <label for="applicant_address" class="form-label required">
                        <i class="fa-solid fa-house"></i> Applicant Address
                    </label>
                    <textarea id="applicant_address" name="applicant_address"
                              class="form-control {{ $errors->has('applicant_address') ? 'is-invalid' : '' }}"
                              placeholder="Enter full applicant address">{{ old('applicant_address', $application->applicant_address ?? '') }}</textarea>
                    @error('applicant_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 form-field-wrap">
                    <label for="correspondence_address" class="form-label">
                        <i class="fa-solid fa-envelope-open-text"></i> Correspondence Address
                        <small class="text-muted fw-normal ms-1">(if different)</small>
                    </label>
                    <textarea id="correspondence_address" name="correspondence_address"
                              class="form-control {{ $errors->has('correspondence_address') ? 'is-invalid' : '' }}"
                              placeholder="Enter correspondence address if different">{{ old('correspondence_address', $application->correspondence_address ?? '') }}</textarea>
                    @error('correspondence_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

            </div>
        </div>

        {{-- ══ SECTION 4: Online Presence ══ --}}
        <div class="section-title">
            <i class="fa-solid fa-globe"></i> Online Presence
        </div>
        <div class="field-group">
            <div class="row">

                <div class="col-md-6 form-field-wrap">
                    <label for="applicant_website" class="form-label">
                        <i class="fa-solid fa-globe"></i> Applicant Website
                    </label>
                    <input id="applicant_website" type="url" name="applicant_website"
                           placeholder="https://www.example.com"
                           class="form-control {{ $errors->has('applicant_website') ? 'is-invalid' : '' }}"
                           value="{{ old('applicant_website', $application->applicant_website ?? '') }}">
                    @error('applicant_website')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 form-field-wrap">
                    <label for="social_media_handles" class="form-label">
                        <i class="fa-solid fa-share-nodes"></i> Social Media Handles
                    </label>
                    <input id="social_media_handles" type="text" name="social_media_handles"
                           placeholder="@instagram, @facebook, @twitter"
                           class="form-control {{ $errors->has('social_media_handles') ? 'is-invalid' : '' }}"
                           value="{{ old('social_media_handles', $application->social_media_handles ?? '') }}">
                    @error('social_media_handles')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

            </div>
        </div>

        {{-- ══ Action Buttons ══ --}}
        <div class="d-flex justify-content-center align-items-center gap-3 mt-3">
            <a href="{{ url()->previous() }}" class="btn-back">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
            <button type="submit" class="btn-save">
                Save &amp; Continue <i class="fa-solid fa-arrow-right"></i>
            </button>
        </div>

    </div>
</form>