{{-- resources/views/frontend/Application/stamp_duty/wizard_step4.blade.php --}}

@extends('frontend.layouts2.master')

@section('title', 'Stamp Duty – Step 4: Project Cost & Employment')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    :root{
        --brand:#ff6600;
        --brand-dark:#e25500;
    }
    .form-icon { color: var(--brand); margin-right:.35rem; }
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
    .total-field {
        font-weight:bold;
        background:#f8f9fa;
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
            <i class="fa-solid fa-indian-rupee-sign"></i>
            <span>Step 4: Project Cost, Employment & Purpose</span>
        </div>

        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger py-2 mb-3">
                    Please fix the errors below.
                </div>
            @endif

            <form id="step4Form"
                  method="POST"
                  action="{{ route('stamp-duty.wizard.store', ['step' => 4]) }}"
                  novalidate>
                @csrf
                <input type="hidden" name="application_form_id" value="{{ $application_form->id }}">
                <input type="hidden" name="application_id" value="{{ $application->id ?? '' }}">

                {{-- Estimated project cost & employment --}}
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label required">Estimated Project Cost (₹)</label>
                        <input type="number"
                               step="0.01"
                               min="0"
                               name="estimated_project_cost"
                               class="form-control @error('estimated_project_cost') is-invalid @enderror"
                               value="{{ old('estimated_project_cost', $application->estimated_project_cost ?? '') }}">
                        <div class="error" id="estimated_project_cost_error">@error('estimated_project_cost') {{ $message }} @enderror</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">Proposed Employment Generation</label>
                        <input type="number"
                               min="0"
                               name="proposed_employment"
                               class="form-control @error('proposed_employment') is-invalid @enderror"
                               value="{{ old('proposed_employment', $application->proposed_employment ?? '') }}">
                        <div class="error" id="proposed_employment_error">@error('proposed_employment') {{ $message }} @enderror</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label required">Tourism Activities / Facilities(for example, No,of Rooms & Other Facilities)</label>
                    <textarea name="tourism_activities"
                              rows="3"
                              class="form-control @error('tourism_activities') is-invalid @enderror">{{ old('tourism_activities', $application->tourism_activities ?? '') }}</textarea>
                    <div class="error" id="tourism_activities_error">@error('tourism_activities') {{ $message }} @enderror</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Details of incentives availed earlier Scheeme of Tourism Policy or any other scheme of State Goverment(in case of existing unit)</label>
                    <textarea name="incentives_availed"
                              rows="3"
                              class="form-control @error('incentives_availed') is-invalid @enderror">{{ old('incentives_availed', $application->incentives_availed ?? '') }}</textarea>
                    <div class="error" id="incentives_availed_error">@error('incentives_availed') {{ $message }} @enderror</div>
                </div>

                {{-- Existence before --}}
                <div class="mb-3">
                    <label class="form-label required">Tourism Project existed before 18/07/2024?</label>
                    <select name="existed_before"
                            id="existed_before"
                            class="form-control @error('existed_before') is-invalid @enderror">
                        <option value="0" {{ old('existed_before', $application->existed_before ?? 0) == 0 ? 'selected' : '' }}>No</option>
                        <option value="1" {{ old('existed_before', $application->existed_before ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                    </select>
                    <div class="error" id="existed_before_error">@error('existed_before') {{ $message }} @enderror</div>
                </div>

                <div id="eligibilitySection" style="display:none;">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Eligibility Certificate No.</label>
                            <input type="text"
                                   name="eligibility_cert_no"
                                   class="form-control @error('eligibility_cert_no') is-invalid @enderror"
                                   value="{{ old('eligibility_cert_no', $application->eligibility_cert_no ?? '') }}">
                            <div class="error" id="eligibility_cert_no_error">@error('eligibility_cert_no') {{ $message }} @enderror</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Eligibility Date</label>
                            <input type="date"
                                   name="eligibility_date"
                                   class="form-control @error('eligibility_date') is-invalid @enderror"
                                   value="{{ old('eligibility_date', optional($application->eligibility_date ?? null)?->format('Y-m-d')) }}">
                            <div class="error" id="eligibility_date_error">@error('eligibility_date') {{ $message }} @enderror</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Present Status of Project</label>
                        <textarea name="present_status"
                                  rows="2"
                                  class="form-control @error('present_status') is-invalid @enderror">{{ old('present_status', $application->present_status ?? '') }}</textarea>
                        <div class="error" id="present_status_error">@error('present_status') {{ $message }} @enderror</div>
                    </div>
                </div>

                <hr class="my-4">

                {{-- Project cost breakup --}}
                <h5 class="mb-3" style="color:#ff6600;">
                    <i class="fa-solid fa-table-list form-icon"></i>
                    Project Cost (₹ in Lakhs)
                </h5>

                <div class="table-responsive mb-3">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th style="width: 50%;">Particulars</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $costs = [
                                    'cost_land'      => 'Land',
                                    'cost_building'  => 'Building',
                                    'cost_machinery' => 'Plant & Machinery',
                                    'cost_electrical'=> 'Electrical Installations',
                                    'cost_misc'      => 'Misc. Fixed Assets',
                                    'cost_other'     => 'Other Expenses',
                                ];
                            @endphp
                            @foreach($costs as $field => $label)
                                <tr>
                                    <td>{{ $label }}</td>
                                    <td>
                                        <input type="number"
                                               step="0.01"
                                               min="0"
                                               name="{{ $field }}"
                                               class="form-control cost-input @error($field) is-invalid @enderror"
                                               value="{{ old($field, $application->{$field} ?? 0) }}">
                                        <div class="error" id="{{ $field }}_error">@error($field) {{ $message }} @enderror</div>
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="table-secondary">
                                <td class="fw-bold">Total</td>
                                <td>
                                    <input type="text" id="total_cost" class="form-control total-field" readonly
                                           value="0.00">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mb-3">
                    <label class="form-label required">Proposed Employment Generation for this project</label>
                    <input type="number"
                           min="0"
                           name="project_employment"
                           class="form-control @error('project_employment') is-invalid @enderror"
                           value="{{ old('project_employment', $application->project_employment ?? '') }}">
                    <div class="error" id="project_employment_error">@error('project_employment') {{ $message }} @enderror</div>
                </div>

                <div class="mb-3">
                    <label class="form-label required">The purpose for which NOC will be utilized</label>
                    <textarea name="noc_purpose"
                              rows="2"
                              class="form-control @error('noc_purpose') is-invalid @enderror">{{ old('noc_purpose', $application->noc_purpose ?? '') }}</textarea>
                    <div class="error" id="noc_purpose_error">@error('noc_purpose') {{ $message }} @enderror</div>
                </div>

                <div class="mb-3">
                    <label class="form-label required">Name & address of authority to which this NOC will be submitted:(e.g Sub Register)</label>
                    <textarea name="noc_authority"
                              rows="2"
                              class="form-control @error('noc_authority') is-invalid @enderror">{{ old('noc_authority', $application->noc_authority ?? '') }}</textarea>
                    <div class="error" id="noc_authority_error">@error('noc_authority') {{ $message }} @enderror</div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('stamp-duty.wizard', ['id' => $application_form->id, 'step' => 3, 'application' => $application->id ?? null]) }}"
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

        function toggleEligibilitySection() {
            const val = $('#existed_before').val();
            if (val === '1') {
                $('#eligibilitySection').slideDown(150);
            } else {
                $('#eligibilitySection').slideUp(150);
            }
        }

        $('#existed_before').on('change', toggleEligibilitySection);
        toggleEligibilitySection();

        function calculateTotal() {
            let total = 0;
            $('.cost-input').each(function () {
                let v = parseFloat($(this).val()) || 0;
                total += v;
            });
            $('#total_cost').val(total.toFixed(2));
        }

        $('.cost-input').on('input', calculateTotal);
        calculateTotal();

        $('#step4Form').validate({
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
                estimated_project_cost: { required:true, number:true, min:0 },
                proposed_employment:    { required:true, digits:true, min:0 },
                tourism_activities:     { required:true },
                incentives_availed:     { },
                existed_before:         { required:true },
                eligibility_cert_no:    { required:function(){ return $('#existed_before').val() === '1'; } },
                eligibility_date:       { required:function(){ return $('#existed_before').val() === '1'; } },
                present_status:         { required:function(){ return $('#existed_before').val() === '1'; } },
                cost_land:              { required:true, number:true, min:0 },
                cost_building:          { required:true, number:true, min:0 },
                cost_machinery:         { required:true, number:true, min:0 },
                cost_electrical:        { required:true, number:true, min:0 },
                cost_misc:              { required:true, number:true, min:0 },
                cost_other:             { required:true, number:true, min:0 },
                project_employment:     { required:true, digits:true, min:0 },
                noc_purpose:            { required:true },
                noc_authority:          { required:true },
            }
        });
    });
</script>
@endpush
