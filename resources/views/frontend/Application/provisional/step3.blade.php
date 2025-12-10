{{-- resources/views/frontend/Application/provisional/step3.blade.php --}}

@extends('frontend.layouts2.master')

@section('title', 'Step 3: Investment Details')

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

    /* Table layout styling */
    table th {
        font-weight: 600;
        background-color: #f8f9fa;
        vertical-align: middle !important;
    }
    table td {
        background-color: #fff;
        vertical-align: middle;
    }
    .table-borderless tr.border-bottom td,
    .table-borderless tr.border-bottom th {
        border-bottom: 1px solid #dee2e6 !important;
    }

    .investment-total td {
        background: #f1f3f5 !important;
        font-weight: 700;
    }
</style>
@endpush

@section('content')
<section class="section">
    <div class="section-header form-header">
        <h1 class="fw-bold">Application Form for the {{ $application_form->name ?? '' }}</h1>
    </div>

    {{-- Stepper / Progress --}}
    @include('frontend.Application.provisional._stepper', ['step' => $step])

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
            <i class="bi bi-cash-stack form-icon"></i>
            <span>Step 3: Investment Details</span>
        </div>

        <div class="card-body">
            <form id="stepForm"
                  action="{{ route('provisional.wizard.save', [$application->id, $step]) }}"
                  method="POST"
                  novalidate>
                @csrf

                {{-- 10. Proposed Investment Details --}}
                <h5 class="mb-3 fw-semibold">
                    10. Proposed Investment Details
                </h5>

                {{-- a. Land Details --}}
                <div class="card p-3 mb-3 border-0 shadow-sm">
                    <label class="form-label fw-semibold mb-2">
                        a. Land Details:
                    </label>
                    <div class="row g-3 align-items-center">
                        <div class="col-md-6">
                            <label class="form-label">
                                Area (Sq. meters) <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                   class="form-control @error('land_area') is-invalid @enderror"
                                   name="land_area"
                                   placeholder="Enter area in Sq. meters"
                                   required
                                   min="1"
                                   step="0.01"
                                   value="{{ old('land_area', $registration->land_area) }}">
                            @error('land_area')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Ownership Type: <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex flex-wrap gap-3 mt-1">
                                @php
                                    $ownershipTypes = ['Owned', 'Leased', 'Rent'];
                                @endphp
                                @foreach($ownershipTypes as $type)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input @error('land_ownership_type') is-invalid @enderror"
                                               type="radio"
                                               name="land_ownership_type"
                                               id="land{{ $type }}"
                                               value="{{ $type }}"
                                               {{ old('land_ownership_type', $registration->land_ownership_type) == $type ? 'checked' : '' }}>
                                        <label class="form-check-label" for="land{{ $type }}">{{ $type }}</label>
                                    </div>
                                @endforeach
                            </div>
                            @error('land_ownership_type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- b. Building Details + c. Project Cost --}}
                <div class="card p-3 mb-3 border-0 shadow-sm">
                    <label class="form-label fw-semibold mb-2">
                        b. Building Details:
                    </label>
                    <div class="row g-3 align-items-center">
                        <div class="col-md-6">
                            <label class="form-label">
                                Ownership Type: <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex flex-wrap gap-3 mt-1">
                                @foreach($ownershipTypes as $type)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input @error('building_ownership_type') is-invalid @enderror"
                                               type="radio"
                                               name="building_ownership_type"
                                               id="building{{ $type }}"
                                               value="{{ $type }}"
                                               {{ old('building_ownership_type', $registration->building_ownership_type) == $type ? 'checked' : '' }}>
                                        <label class="form-check-label" for="building{{ $type }}">{{ $type }}</label>
                                    </div>
                                @endforeach
                            </div>
                            @error('building_ownership_type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                c. Project Cost (₹): <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                   class="form-control @error('project_cost') is-invalid @enderror"
                                   name="project_cost"
                                   placeholder="Enter total project cost in ₹"
                                   required
                                   min="0"
                                   step="0.01"
                                   value="{{ old('project_cost', $registration->project_cost) }}">
                            @error('project_cost')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- d. Total number of people employed --}}
                <div class="card p-3 mb-3 border-0 shadow-sm">
                    <label class="form-label fw-semibold">
                        d. Total number of people to be employed on the Tourism Project: <span class="text-danger">*</span>
                    </label>
                    <div class="row g-3 align-items-center">
                        <div class="col-md-6">
                            <input type="number"
                                   class="form-control @error('total_employees') is-invalid @enderror"
                                   name="total_employees"
                                   placeholder="Enter total number of employees"
                                   required
                                   min="1"
                                   value="{{ old('total_employees', $registration->total_employees) }}">
                            @error('total_employees')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- e. Means of Finance / Investment Details --}}
                <div class="mb-4 card border-0 shadow-sm">
                    <div class="card-body">
                        <label class="form-label fw-semibold mb-3">
                            e. Means of Finance / Investment Details
                        </label>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle text-center" id="investmentTable">
                                <thead class="table-primary">
                                    <tr>
                                        <th style="width: 30%;">Component</th>
                                        <th style="width: 35%;">Estimated Cost (₹ in Lakh)</th>
                                        <th style="width: 35%;">Investment already made (₹ in Lakh)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $investmentData = $registration->investment_components ?? [];
                                        $components = [
                                            'land'       => 'Land',
                                            'building'   => 'Building',
                                            'machinery'  => 'Plant & Machinery',
                                            'engineering'=> 'Engineering Fees',
                                            'preop'      => 'Preliminary, Pre-Operative Expense',
                                            'margin'     => 'Margin for Working Capital',
                                        ];
                                    @endphp

                                    @foreach($components as $key => $label)
                                        <tr>
                                            <td>{{ $label }}</td>
                                            <td>
                                                <input type="number"
                                                       class="form-control est-cost @error($key.'_est') is-invalid @enderror"
                                                       name="{{ $key }}_est"
                                                       placeholder="0.00"
                                                       step="0.01"
                                                       min="0"
                                                       value="{{ old($key.'_est', $investmentData[$key]['estimated'] ?? 0) }}">
                                                @error($key.'_est')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="number"
                                                       class="form-control inv-made @error($key.'_inv') is-invalid @enderror"
                                                       name="{{ $key }}_inv"
                                                       placeholder="0.00"
                                                       step="0.01"
                                                       min="0"
                                                       value="{{ old($key.'_inv', $investmentData[$key]['investment_made'] ?? 0) }}">
                                                @error($key.'_inv')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                        </tr>
                                    @endforeach

                                    {{-- Total Row --}}
                                    <tr class="table-secondary fw-bold investment-total">
                                        <td>Total</td>
                                        <td>
                                            <input type="text" class="form-control" id="totalEst" readonly value="0.00">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" id="totalInv" readonly value="0.00">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <small class="text-muted">
                            Note: Investment components are optional but must be valid numbers if filled.
                        </small>
                    </div>
                </div>

                {{-- Navigation Buttons --}}
                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('provisional.wizard.show', [$application, $step - 1]) }}"
                       class="btn btn-primary">
                        <i class="bi bi-arrow-left"></i> Previous
                    </a>

                    <button type="submit"
                            class="btn btn-primary"
                            style="
                                background-color: #ff6600;
                                border-color:#ff6600;
                                font-weight:600;
                            ">
                        Save & Continue <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script>
$(document).ready(function() {

    // ------------------------------
    // Auto total calculation
    // ------------------------------
    function calculateTotals() {
        let totalEst = 0;
        let totalInv = 0;

        $('.est-cost').each(function() {
            totalEst += parseFloat($(this).val()) || 0;
        });

        $('.inv-made').each(function() {
            totalInv += parseFloat($(this).val()) || 0;
        });

        $('#totalEst').val(totalEst.toFixed(2));
        $('#totalInv').val(totalInv.toFixed(2));
    }

    // Recalculate on input
    $(document).on('input', '.est-cost, .inv-made', calculateTotals);

    // Initial calculation
    calculateTotals();

    // ------------------------------
    // jQuery Validation
    // ------------------------------
    $("#stepForm").validate({
        ignore: [], // hidden fields bhi validate hon (agar koi ho)
        errorElement: "div",
        errorClass: "invalid-feedback",
        highlight: function (element) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid");
        },
        errorPlacement: function(error, element) {
            // Radio groups: land_ownership_type, building_ownership_type
            if (element.attr("name") === "land_ownership_type") {
                error.insertAfter(element.closest('.d-flex'));
            } else if (element.attr("name") === "building_ownership_type") {
                error.insertAfter(element.closest('.d-flex'));
            } else {
                if (element.parent(".input-group").length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            }
        },
        rules: {
            land_area: {
                required: true,
                number: true,
                min: 1
            },
            land_ownership_type: {
                required: true
            },
            building_ownership_type: {
                required: true
            },
            project_cost: {
                required: true,
                number: true,
                min: 0
            },
            total_employees: {
                required: true,
                number: true,
                min: 1
            },

            // Optional but must be valid if filled
            @foreach($components as $key => $label)
            "{{ $key }}_est": {
                number: true,
                min: 0
            },
            "{{ $key }}_inv": {
                number: true,
                min: 0
            },
            @endforeach
        },
        messages: {
            land_area: {
                required: "Please enter the land area.",
                number: "Please enter a valid number.",
                min: "Area must be greater than zero."
            },
            land_ownership_type: {
                required: "Please select land ownership type."
            },
            building_ownership_type: {
                required: "Please select building ownership type."
            },
            project_cost: {
                required: "Please enter the project cost.",
                number: "Enter a valid numeric amount.",
                min: "Project cost must be zero or greater."
            },
            total_employees: {
                required: "Please enter the total number of employees.",
                number: "Enter a valid numeric value.",
                min: "Number of employees must be at least 1."
            }
            // Component fields can use default messages
        }
    });

});
</script>
@endpush
