{{-- resources/views/frontend/Application/stamp_duty/wizard_step2.blade.php --}}

@extends('frontend.layouts2.master')

@section('title', 'Stamp Duty – Step 2: Address Details')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    :root{
        --brand:#ff6600;
        --brand-dark:#e25500;
    }
    textarea.form-control {
    resize: vertical; /* allow vertical resize only */
}

    .form-icon {
        color: var(--brand);
        margin-right:.35rem;
    }
    .required::after {
        content:" *";
        color:#dc3545;
        margin-left:0.15rem;
        font-weight:600;
    }
    .is-valid { border-color:#28a745 !important; }
    .is-invalid { border-color:#dc3545 !important; }
    .error {
        color:#dc3545;
        font-size:0.85rem;
        margin-top:0.25rem;
    }
    .card-header-orange {
        background-color:var(--brand);
        color:#ff6600;
        padding:.75rem 1rem;
        font-weight:700;
        display:flex;
        align-items:center;
        gap:.5rem;
    }
</style>
@endpush

@section('content')
<section class="section">
    <div class="section-header d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-3">
        <h1 class="mb-2 mb-md-0">
            <i class="fa-solid fa-route" style="color:#ff6600;"></i>
            Application for Stamp Duty Exemption
        </h1>
    </div>

    {{-- Stepper --}}
    @include('frontend.Application.stamp_duty._stepper', [
        'step'        => $step,
        'application' => $application,
        'progress'    => $progress,
        'done'        => $done,
        'total'       => $total,
    ])

    <div class="card shadow-sm mb-4">
        <div class="card-header card-header-orange">
            <i class="fa-solid fa-address-book"></i>
            <span>Step 2: Correspondence & Project Site Address</span>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success py-2 mb-3">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger py-2 mb-3">
                    Please fix the errors below.
                </div>
            @endif

            <form id="step2Form"
                  method="POST"
                  action="{{ route('stamp-duty.wizard.store', ['step' => 2]) }}"
                  novalidate>
                @csrf

                <input type="hidden" name="application_form_id" value="{{ $application_form->id }}">
                <input type="hidden" name="application_id" value="{{ $application->id ?? '' }}">

                {{-- Correspondence Address --}}
                <h5 class="mb-3" style="color:#ff6600;">
                    <i class="fa-solid fa-envelope-open-text form-icon"></i>
                    Correspondence Address
                </h5>

                <div class="row g-3 mb-3">
                    <div class="col-md-12 mb-2">
                        <label class="form-label required">Address</label>
                        <textarea name="c_address"
                                  rows="3"
                                  class="form-control @error('c_address') is-invalid @enderror"
                                  placeholder="Enter full address">{{ old('c_address', $application->c_address ?? '') }}</textarea>
                        <div class="error" id="c_address_error">@error('c_address') {{ $message }} @enderror</div>
                    </div>


                    <div class="col-md-4 ">
                        <label class="form-label required">Village / City</label>
                        <input type="text"
                               name="c_city"
                               class="form-control @error('c_city') is-invalid @enderror"
                               value="{{ old('c_city', $application->c_city ?? '') }}">
                        <div class="error" id="c_city_error">@error('c_city') {{ $message }} @enderror</div>
                    </div>

                    <div class="col-md-4 ">
                        <label class="form-label required">Taluka</label>
                        <input type="text"
                               name="c_taluka"
                               class="form-control @error('c_taluka') is-invalid @enderror"
                               value="{{ old('c_taluka', $application->c_taluka ?? '') }}">
                        <div class="error" id="c_taluka_error">@error('c_taluka') {{ $message }} @enderror</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label required">District</label>
                        <select name="c_district"
                                class="form-control @error('c_district') is-invalid @enderror">
                            <option value="" selected disabled>Select District</option>
                            @foreach($districts as $district)
                                <option value="{{ $district->name }}"
                                    {{ old('c_district', $application->c_district ?? '') == $district->name ? 'selected' : '' }}>
                                    {{ $district->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="error" id="c_district_error">@error('c_district') {{ $message }} @enderror</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label required">State</label>
                        <select name="c_state"
                                class="form-control @error('c_state') is-invalid @enderror">
                            <option value="" selected disabled>Select State</option>
                            @foreach($states as $state)
                                <option value="{{ $state->name }}"
                                    {{ old('c_state', $application->c_state ?? '') == $state->name ? 'selected' : '' }}>
                                    {{ $state->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="error" id="c_state_error">@error('c_state') {{ $message }} @enderror</div>
                    </div>


                    <div class="col-md-4">
                        <label class="form-label required">Pin Code</label>
                        <input type="number"
                               name="c_pincode"
                               maxlength="6"
                               class="form-control @error('c_pincode') is-invalid @enderror"
                               value="{{ old('c_pincode', $application->c_pincode ?? '') }}">
                        <div class="error" id="c_pincode_error">@error('c_pincode') {{ $message }} @enderror</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label required">Mobile No.</label>
                        <input type="number"
                               name="c_mobile"
                               maxlength="10"
                               class="form-control @error('c_mobile') is-invalid @enderror"
                               value="{{ old('c_mobile', $application->c_mobile ?? '') }}">
                        <div class="error" id="c_mobile_error">@error('c_mobile') {{ $message }} @enderror</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Telephone No.</label>
                        <input type="number"
                               name="c_phone"
                               class="form-control @error('c_phone') is-invalid @enderror"
                               value="{{ old('c_phone', $application->c_phone ?? '') }}">
                        <div class="error" id="c_phone_error">@error('c_phone') {{ $message }} @enderror</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label required">Email</label>
                        <input type="email"
                               name="c_email"
                               class="form-control @error('c_email') is-invalid @enderror"
                               value="{{ old('c_email', $application->c_email ?? '') }}">
                        <div class="error" id="c_email_error">@error('c_email') {{ $message }} @enderror</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Fax</label>
                        <input type="text"
                               name="c_fax"
                               class="form-control @error('c_fax') is-invalid @enderror"
                               value="{{ old('c_fax', $application->c_fax ?? '') }}">
                        <div class="error" id="c_fax_error">@error('c_fax') {{ $message }} @enderror</div>
                    </div>
                </div>

                {{-- Project Site Address --}}
                <hr class="my-4">
                <h5 class="mb-3" style="color:#ff6600;">
                    <i class="fa-solid fa-location-dot form-icon"></i>
                    Project Site Address
                </h5>

                <div class="row g-3 mb-3">
                    <div class="col-md-12 mb-2">
                        <label class="form-label required">Address (Gat No. / Survey No.)</label>
                        <textarea name="p_address"
                                  rows="3"
                                  class="form-control @error('p_address') is-invalid @enderror"
                                  placeholder="Enter full project site address including Gat No. / Survey No.">{{ old('p_address', $application->p_address ?? '') }}</textarea>
                        <div class="error" id="p_address_error">@error('p_address') {{ $message }} @enderror</div>
                    </div>


                    <div class="col-md-4">
                        <label class="form-label required">Village / City</label>
                        <input type="text"
                               name="p_city"
                               class="form-control @error('p_city') is-invalid @enderror"
                               value="{{ old('p_city', $application->p_city ?? '') }}">
                        <div class="error" id="p_city_error">@error('p_city') {{ $message }} @enderror</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label required">Taluka</label>
                        <input type="text"
                               name="p_taluka"
                               class="form-control @error('p_taluka') is-invalid @enderror"
                               value="{{ old('p_taluka', $application->p_taluka ?? '') }}">
                        <div class="error" id="p_taluka_error">@error('p_taluka') {{ $message }} @enderror</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label required">District</label>
                        <select name="p_district"
                                class="form-control @error('p_district') is-invalid @enderror">
                            <option value="" selected disabled>Select District</option>
                            @foreach($districts as $district)
                                <option value="{{ $district->name }}"
                                    {{ old('p_district', $application->p_district ?? '') == $district->name ? 'selected' : '' }}>
                                    {{ $district->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="error" id="p_district_error">@error('p_district') {{ $message }} @enderror</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label required">State</label>
                        <select name="p_state"
                                class="form-control @error('p_state') is-invalid @enderror">
                            <option value="" selected disabled>Select State</option>
                            @foreach($states as $state)
                                <option value="{{ $state->name }}"
                                    {{ old('p_state', $application->p_state ?? '') == $state->name ? 'selected' : '' }}>
                                    {{ $state->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="error" id="p_state_error">@error('p_state') {{ $message }} @enderror</div>
                    </div>


                    <div class="col-md-4">
                        <label class="form-label required">Pin Code</label>
                        <input type="number"
                               name="p_pincode"
                               maxlength="6"   oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,6);"
                               class="form-control @error('p_pincode') is-invalid @enderror"
                               value="{{ old('p_pincode', $application->p_pincode ?? '') }}">
                        <div class="error" id="p_pincode_error">@error('p_pincode') {{ $message }} @enderror</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label required">Mobile No.</label>
                        <input type="number"
                               name="p_mobile"
                               maxlength="10"
                               class="form-control @error('p_mobile') is-invalid @enderror"
                               value="{{ old('p_mobile', $application->p_mobile ?? '') }}">
                        <div class="error" id="p_mobile_error">@error('p_mobile') {{ $message }} @enderror</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Telephone No.</label>
                        <input type="number"
                               name="p_phone"
                               class="form-control @error('p_phone') is-invalid @enderror"
                               value="{{ old('p_phone', $application->p_phone ?? '') }}">
                        <div class="error" id="p_phone_error">@error('p_phone') {{ $message }} @enderror</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label required">Email</label>
                        <input type="email"
                               name="p_email"
                               class="form-control @error('p_email') is-invalid @enderror"
                               value="{{ old('p_email', $application->p_email ?? '') }}">
                        <div class="error" id="p_email_error">@error('p_email') {{ $message }} @enderror</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Website</label>
                        <input type="text"
                               name="p_website"
                               class="form-control @error('p_website') is-invalid @enderror"
                               value="{{ old('p_website', $application->p_website ?? '') }}"
                               placeholder="http://">
                        <div class="error" id="p_website_error">@error('p_website') {{ $message }} @enderror</div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('stamp-duty.wizard', ['id' => $application_form->id, 'step' => 1, 'application' => $application->id ?? null]) }}"
                       class="btn"
                       style="background-color:#6c757d;color:#fff;padding:.5rem 1.5rem;border-radius:6px;border:none;">
                        <i class="fa-solid fa-arrow-left"></i> &nbsp; Back
                    </a>

                    <button type="submit" class="btn"
                            style="background-color:#ff6600;color:#fff;padding:.5rem 1.5rem;border-radius:6px;border:none;">
                        Next &nbsp;<i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script>
    $(function () {
        const form = $('#step2Form');

        // Initialize jQuery Validate
        form.validate({
            ignore: [],
            errorClass: 'is-invalid',
            validClass: 'is-valid',
            errorElement: 'div',
            errorPlacement: function (error, element) {
                const id = element.attr('name') + '_error';
                $('#' + id).html(error.text());
            },
            success: function (label, element) {
                const id = $(element).attr('name') + '_error';
                $('#' + id).html('');
                $(element).removeClass('is-invalid').addClass('is-valid');
            },
            rules: {
                c_address: { required: true },
                c_city: { required: true, pattern: /^[A-Za-z\s]+$/ },
                c_taluka: { required: true, pattern: /^[A-Za-z\s]+$/ },
                c_district: { required: true, pattern: /^[A-Za-z\s]+$/ },
                c_state: { required: true, pattern: /^[A-Za-z\s]+$/ },
                c_pincode: { required: true, pattern: /^[1-9][0-9]{5}$/ },
                c_mobile: { required: true, pattern: /^[6-9][0-9]{9}$/ },
                c_email: { required: true, email: true },
                p_address: { required: true },
                p_city: { required: true, pattern: /^[A-Za-z\s]+$/ },
                p_taluka: { required: true, pattern: /^[A-Za-z\s]+$/ },
                p_district: { required: true, pattern: /^[A-Za-z\s]+$/ },
                p_state: { required: true, pattern: /^[A-Za-z\s]+$/ },
                p_pincode: { required: true, pattern: /^[1-9][0-9]{5}$/ },
                p_mobile: { required: true, pattern: /^[6-9][0-9]{9}$/ },
                p_email: { required: true, email: true },
            },
            messages: {
                c_city: { pattern: "Only letters and spaces allowed" },
                c_taluka: { pattern: "Only letters and spaces allowed" },
                c_district: { pattern: "Only letters and spaces allowed" },
                c_state: { pattern: "Only letters and spaces allowed" },
                c_pincode: { pattern: "Enter 6-digit pin code" },
                c_mobile: { pattern: "Enter valid 10-digit mobile number starting with 6–9" },
                p_city: { pattern: "Only letters and spaces allowed" },
                p_taluka: { pattern: "Only letters and spaces allowed" },
                p_district: { pattern: "Only letters and spaces allowed" },
                p_state: { pattern: "Only letters and spaces allowed" },
                p_pincode: { pattern: "Enter 6-digit pin code" },
                p_mobile: { pattern: "Enter valid 10-digit mobile number starting with 6–9" },
            }
        });

        // 🔥 Real-time validation on input/change for all fields
        form.find('input, textarea, select').on('input change blur', function () {
            $(this).valid(); // triggers individual field validation instantly
        });

        // 🔢 Optional: Restrict digits and live feedback for mobile/pincode
        $('input[name="c_mobile"], input[name="p_mobile"]').on('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
        });

        $('input[name="c_pincode"], input[name="p_pincode"]').on('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
        });
    });
    </script>

@endpush
