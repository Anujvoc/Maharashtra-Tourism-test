{{-- resources/views/frontend/Application/stamp_duty/wizard_step3.blade.php --}}

@extends('frontend.layouts2.master')

@section('title', 'Stamp Duty – Step 3: Land & Built-up Area')

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

    @include('frontend.Application.stamp_duty._stepper', [
        'step'        => $step,
        'application' => $application,
        'progress'    => $progress,
        'done'        => $done,
        'total'       => $total,
    ])

    <div class="card shadow-sm mb-4">
        <div class="card-header card-header-orange">
            <i class="fa-solid fa-mountain-city"></i>
            <span>Step 3: Land & Built-up Area Details</span>
        </div>

        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger py-2 mb-3">
                    Please fix the errors below.
                </div>
            @endif

            <form id="step3Form"
                  method="POST"
                  action="{{ route('stamp-duty.wizard.store', ['step' => 3]) }}"
                  novalidate>
                @csrf
                <input type="hidden" name="application_form_id" value="{{ $application_form->id }}">
                <input type="hidden" name="application_id" value="{{ $application->id ?? '' }}">

                <h5 class="mb-3" style="color:#ff6600;">
                    <i class="fa-solid fa-map-location-dot form-icon"></i>
                    Land to be purchased / leased for Tourism Project
                </h5>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label required">CTS / Gat No.</label>
                        <input type="text"
                               name="land_gat"
                               class="form-control @error('land_gat') is-invalid @enderror"
                               value="{{ old('land_gat', $application->land_gat ?? '') }}">
                        <div class="error" id="land_gat_error">@error('land_gat') {{ $message }} @enderror</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">Village</label>
                        <input type="text"
                               name="land_village"
                               class="form-control @error('land_village') is-invalid @enderror"
                               value="{{ old('land_village', $application->land_village ?? '') }}">
                        <div class="error" id="land_village_error">@error('land_village') {{ $message }} @enderror</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">Taluka</label>
                        <input type="text"
                               name="land_taluka"
                               class="form-control @error('land_taluka') is-invalid @enderror"
                               value="{{ old('land_taluka', $application->land_taluka ?? '') }}">
                        <div class="error" id="land_taluka_error">@error('land_taluka') {{ $message }} @enderror</div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label required">District</label>
                        <select name="land_district"
                                class="form-control @error('land_district') is-invalid @enderror">
                            <option value="" selected disabled>Select District</option>
                            @foreach($districts as $district)
                                <option value="{{ $district->name }}"
                                    {{ old('land_district', $application->land_district ?? '') == $district->name ? 'selected' : '' }}>
                                    {{ $district->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="error" id="land_district_error">@error('land_district') {{ $message }} @enderror</div>
                    </div>

                </div>

                <hr class="my-4">

                <h5 class="mb-3" style="color:#ff6600;">
                    <i class="fa-solid fa-ruler-combined form-icon"></i>
                    Area Details (Sq. Metres)
                </h5>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label required">(A) Total area to be purchased / lease of land</label>
                        <input type="number"
                               step="0.01"
                               min="0"
                               name="area_a"
                               class="form-control @error('area_a') is-invalid @enderror"
                               value="{{ old('area_a', $application->area_a ?? '') }}">
                        <div class="error" id="area_a_error">@error('area_a') {{ $message }} @enderror</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">(B) Total area of land & built-up area</label>
                        <input type="number"
                               step="0.01"
                               min="0"
                               name="area_b"
                               class="form-control @error('area_b') is-invalid @enderror"
                               value="{{ old('area_b', $application->area_b ?? '') }}">
                        <div class="error" id="area_b_error">@error('area_b') {{ $message }} @enderror</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">(C) Area of land for tourism project</label>
                        <input type="number"
                               step="0.01"
                               min="0"
                               name="area_c"
                               class="form-control @error('area_c') is-invalid @enderror"
                               value="{{ old('area_c', $application->area_c ?? '') }}">
                        <div class="error" id="area_c_error">@error('area_c') {{ $message }} @enderror</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">(D) Area for ancillary activity</label>
                        <input type="number"
                               step="0.01"
                               min="0"
                               name="area_d"
                               class="form-control @error('area_d') is-invalid @enderror"
                               value="{{ old('area_d', $application->area_d ?? '') }}">
                        <div class="error" id="area_d_error">@error('area_d') {{ $message }} @enderror</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">(E) Total vacant land out of purchase / lease land</label>
                        <input type="number"
                               step="0.01"
                               min="0"
                               name="area_e"
                               class="form-control @error('area_e') is-invalid @enderror"
                               value="{{ old('area_e', $application->area_e ?? '') }}">
                        <div class="error" id="area_e_error">@error('area_e') {{ $message }} @enderror</div>
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="mb-3" style="color:#ff6600;">
                    <i class="fa-solid fa-seedling form-icon"></i>
                    Details of Non-Agricultural Land (if applicable)
                </h5>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label">CTS / Gat No.</label>
                        <input type="text"
                               name="na_gat"
                               class="form-control @error('na_gat') is-invalid @enderror"
                               value="{{ old('na_gat', $application->na_gat ?? '') }}">
                        <div class="error" id="na_gat_error">@error('na_gat') {{ $message }} @enderror</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Village</label>
                        <input type="text"
                               name="na_village"
                               class="form-control @error('na_village') is-invalid @enderror"
                               value="{{ old('na_village', $application->na_village ?? '') }}">
                        <div class="error" id="na_village_error">@error('na_village') {{ $message }} @enderror</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Taluka</label>
                        <input type="text"
                               name="na_taluka"
                               class="form-control @error('na_taluka') is-invalid @enderror"
                               value="{{ old('na_taluka', $application->na_taluka ?? '') }}">
                        <div class="error" id="na_taluka_error">@error('na_taluka') {{ $message }} @enderror</div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">District</label>
                        <select name="na_district"
                                class="form-control @error('na_district') is-invalid @enderror">
                            <option value="" selected disabled>Select District</option>
                            @foreach($districts as $district)
                                <option value="{{ $district->name }}"
                                    {{ old('na_district', $application->na_district ?? '') == $district->name ? 'selected' : '' }}>
                                    {{ $district->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="error" id="na_district_error">@error('na_district') {{ $message }} @enderror</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Total Area to be converted to NA (Sq. Metres)</label>
                        <input type="number"
                               step="0.01"
                               min="0"
                               name="na_area"
                               class="form-control @error('na_area') is-invalid @enderror"
                               value="{{ old('na_area', $application->na_area ?? '') }}">
                        <div class="error" id="na_area_error">@error('na_area') {{ $message }} @enderror</div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('stamp-duty.wizard', ['id' => $application_form->id, 'step' => 2, 'application' => $application->id ?? null]) }}"
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
        $('#step3Form').validate({
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
                land_gat:      { required:true },
                land_village:  { required:true, pattern:/^[A-Za-z\s]+$/ },
                land_taluka:   { required:true, pattern:/^[A-Za-z\s]+$/ },
                land_district: { required:true, pattern:/^[A-Za-z\s]+$/ },
                area_a:        { required:true, number:true, min:0 },
                area_b:        { required:true, number:true, min:0 },
                area_c:        { required:true, number:true, min:0 },
                area_d:        { required:true, number:true, min:0 },
                area_e:        { required:true, number:true, min:0 },
                na_area:       { number:true, min:0 },
            },
            messages: {
                land_village:  { pattern:"Only letters and spaces allowed" },
                land_taluka:   { pattern:"Only letters and spaces allowed" },
                land_district: { pattern:"Only letters and spaces allowed" },
            }
        });
    });
</script>
@endpush
