@extends('frontend.layouts2.master')

@section('title', 'Stamp Duty – Step 1: General Details')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    :root{
        --brand:#ff6600;
        --brand-dark:#e25500;
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

    <div class="card shadow-sm ">
        <h2 class="text-center mt-2" style="color: blue">Application for NOC for getting exemption from Stamp Duty</h2>
        <h3 class="text-center mb-4">(Under Tourism Policy 2024, vide No.TDS/2022/09/C.R.542/Tourism – 4, dt.18/07/2024 for New Eligible Tourism Project / Expansion of Existing Project.)</h3>
        <div class="card-header card-header-orange">
            <i class="fa-solid fa-user"></i>
            <span>Step 1: General Details</span>

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



            <form id="step1Form"
                  method="POST"
                  action="{{ route('stamp-duty.wizard.store', ['step' => 1]) }}"
                  novalidate>
                @csrf
                <input type="hidden" name="application_form_id" value="{{ $application_form->id }}">
                <input type="hidden" name="application_id" value="{{ $application->id ?? '' }}">

                <div class="row g-3 mb-3">

                    <div class="col-md-6">
                        <label class="form-label required">
                            <i class="fa-solid fa-map form-icon"></i> Select Region
                        </label>
                        <select name="region_id" id="region_id"
                                class="form-control @error('region_id') is-invalid @enderror"  onchange="get_Region_District(this.value)">
                            <option value="" selected disabled>Select Region</option>
                            @foreach($regions as $r)
                                <option value="{{ $r->id }}"
                                    {{ old('region_id', $application->region_id ?? '') == $r->id ? 'selected' : '' }}>
                                    {{ $r->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="error" id="region_id_error">@error('region_id') {{ $message }} @enderror</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label required">
                            <i class="fa-solid fa-location-dot form-icon"></i> Select District
                        </label>
                            <select id="district_id" name="district_id" class="form-control district_id {{ $errors->has('district_id') ? 'is-invalid' : '' }}">
                                <option value="" selected disabled>Select District</option>

                        </select>
                        <div class="error" id="district_id_error">@error('district_id') {{ $message }} @enderror</div>
                    </div>
                </div>

                <input type="hidden" id="old_district" class="old_district"
       value="{{ old('district_id', $application->district_id ?? '') }}">


                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label required">Applicant / Company Name</label>
                        <input type="text"
                               name="company_name"
                               class="form-control @error('company_name') is-invalid @enderror"
                               value="{{ old('company_name', $application->company_name ?? '') }}">
                        <div class="error" id="company_name_error">@error('company_name') {{ $message }} @enderror</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label required">Registration No.</label>
                        <input type="text"
                               name="registration_no"
                               class="form-control @error('registration_no') is-invalid @enderror"
                               value="{{ old('registration_no', $application->registration_no ?? '') }}">
                        <div class="error" id="registration_no_error">@error('registration_no') {{ $message }} @enderror</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label required">Date</label>
                        <input type="date"
                        name="application_date"
                        class="form-control @error('application_date') is-invalid @enderror"
                        value="{{ old('application_date', $application?->application_date?->format('Y-m-d') ?? date('Y-m-d')) }}">
                        <div class="error" id="application_date_error">@error('application_date') {{ $message }} @enderror</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label required">Type of Enterprise</label>
                    <select name="applicant_type"
                            class="form-control @error('applicant_type') is-invalid @enderror">
                        <option value="">Select</option>
                        @foreach($enterprises as $enterprise)
                            <option value="{{ $enterprise['id'] }}"
                                {{ old('applicant_type', $application->applicant_type ?? '') == $enterprise['id'] ? 'selected' : '' }}>
                                {{ $enterprise['name'] }}
                            </option>
                        @endforeach
                    </select>
                    <div class="error" id="applicant_type_error">@error('applicant_type') {{ $message }} @enderror</div>
                </div>

                <div class="mb-3">
                    <label class="form-label required">Agreement to be made</label>
                    <select name="agreement_type"
                            class="form-control @error('agreement_type') is-invalid @enderror">
                        <option value="">Select</option>
                        @foreach (['Purchase Deed','Lease Deed','Mortgage','Hypothecation'] as $opt)
                            <option value="{{ $opt }}"
                                {{ old('agreement_type', $application->agreement_type ?? '') == $opt ? 'selected' : '' }}>
                                {{ $opt }}
                            </option>
                        @endforeach
                    </select>
                    <div class="error" id="agreement_type_error">@error('agreement_type') {{ $message }} @enderror</div>
                </div>

                <div class="d-flex justify-content-end mt-4">
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
        $('#step1Form').validate({
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
                region_id: { required:true },
                district_id: { required:true },
                company_name: {
                    required:true,
                    pattern:/^[A-Za-z0-9\s\.,&'-]+$/
                },
                registration_no: { required:true },
                application_date: { required:true },
                applicant_type: { required:true },
                agreement_type: { required:true },
            },
            messages: {
                company_name: {
                    required: "Please enter company name",
                    pattern: "Enter valid company name (letters, numbers, spaces, .,&,-)"
                }
            }
        });
    });
</script>
<script>
    function get_Region_District(id) {

        if (!id) {
            $(".district_id").html('<option value="">Select District</option>');
            return;
        }

        const custom_url = "{{ route('frontend.get_Region_District', ['id' => ':id']) }}"
            .replace(':id', id);

        $(".district_id").html('<option value="">Loading...</option>');

        $.ajax({
            url: custom_url,
            type: 'GET',
            dataType: 'json',
            success: function(resp) {

                $(".district_id").html('');

                if (Array.isArray(resp) && resp.length > 0) {

                    $(".district_id").append('<option value="">Select District</option>');

                    let oldDistrict = $("#old_district").val(); // hidden se value

                    $.each(resp, function(index, item) {

                        let selected = (oldDistrict == item.id) ? 'selected' : '';

                        $(".district_id").append(
                            `<option value="${item.id}" ${selected}>${item.name}</option>`
                        );
                    });

                } else {
                    $(".district_id").html('<option value="">No District found</option>');
                }
            },
            error: function() {
                $(".district_id").html('<option value="">Error loading districts</option>');
            }
        });
    }

    $(document).ready(function() {
        let selectedRegion = $("#region_id").val();  // 👈 ab yahan value milegi
        if (selectedRegion) {
            get_Region_District(selectedRegion);
        }
    });
</script>


@endpush
