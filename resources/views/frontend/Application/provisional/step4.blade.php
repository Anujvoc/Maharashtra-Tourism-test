{{-- resources/views/frontend/Application/provisional/step4.blade.php --}}

@extends('frontend.layouts2.master')

@section('title', 'Step 4: Means of Finance')

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
    .finance-total td {
        background: #f1f3f5 !important;
        font-weight: 700;
    }
    .grand-total-row td {
        background: #e7f1ff !important;
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
            <span>Step 4: Means of Finance</span>
        </div>

        <div class="card-body">
            <form id="stepForm"
                  action="{{ route('provisional.wizard.save', [$application->id, $step]) }}"
                  method="POST"
                  novalidate>
                @csrf

                @php
                    $financeData = $registration->means_of_finance ?? [];
                    $shareData   = $financeData['share_capital'] ?? [];
                    $loanData    = $financeData['loans'] ?? [];
                @endphp

                <div class="mb-3">
                    <p class="mb-1 fw-semibold">Please provide the breakup of project finance:</p>
                    <small class="text-muted">
                        Amounts are in <strong>₹ Lakh</strong>. All fields are optional, but if filled, they must be valid non-negative numbers.
                    </small>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle" id="financeTable">
                        <thead class="table-secondary">
                            <tr>
                                <th style="width: 25%;">Category</th>
                                <th style="width: 35%;">Details</th>
                                <th style="width: 40%;">Amount (₹ in Lakh)</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Share Capital --}}
                            <tr>
                                <td rowspan="4" class="align-middle fw-semibold">Share Capital</td>
                                <td>Promoters</td>
                                <td>
                                    <input type="number"
                                           class="form-control share-input @error('share_promoters') is-invalid @enderror"
                                           name="share_promoters"
                                           min="0"
                                           step="0.01"
                                           inputmode="decimal"
                                           value="{{ old('share_promoters', $shareData['promoters'] ?? 0) }}">
                                    @error('share_promoters')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <td>Financial Institutions</td>
                                <td>
                                    <input type="number"
                                           class="form-control share-input @error('share_financial') is-invalid @enderror"
                                           name="share_financial"
                                           min="0"
                                           step="0.01"
                                           inputmode="decimal"
                                           value="{{ old('share_financial', $shareData['financial_institutions'] ?? 0) }}">
                                    @error('share_financial')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <td>Public</td>
                                <td>
                                    <input type="number"
                                           class="form-control share-input @error('share_public') is-invalid @enderror"
                                           name="share_public"
                                           min="0"
                                           step="0.01"
                                           inputmode="decimal"
                                           value="{{ old('share_public', $shareData['public'] ?? 0) }}">
                                    @error('share_public')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                            </tr>
                            <tr class="finance-total">
                                <td><strong>Total Share Capital</strong></td>
                                <td>
                                    <input type="text"
                                           class="form-control share-total"
                                           readonly
                                           value="0.00">
                                </td>
                            </tr>

                            {{-- Loans --}}
                            <tr>
                                <td rowspan="4" class="align-middle fw-semibold">Loans</td>
                                <td>Financial Institutions</td>
                                <td>
                                    <input type="number"
                                           class="form-control loan-input @error('loan_financial') is-invalid @enderror"
                                           name="loan_financial"
                                           min="0"
                                           step="0.01"
                                           inputmode="decimal"
                                           value="{{ old('loan_financial', $loanData['financial_institutions'] ?? 0) }}">
                                    @error('loan_financial')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <td>Banks</td>
                                <td>
                                    <input type="number"
                                           class="form-control loan-input @error('loan_banks') is-invalid @enderror"
                                           name="loan_banks"
                                           min="0"
                                           step="0.01"
                                           inputmode="decimal"
                                           value="{{ old('loan_banks', $loanData['banks'] ?? 0) }}">
                                    @error('loan_banks')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <td>Others (If any)</td>
                                <td>
                                    <input type="number"
                                           class="form-control loan-input @error('loan_others') is-invalid @enderror"
                                           name="loan_others"
                                           min="0"
                                           step="0.01"
                                           inputmode="decimal"
                                           value="{{ old('loan_others', $loanData['others'] ?? 0) }}">
                                    @error('loan_others')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                            </tr>
                            <tr class="finance-total">
                                <td><strong>Total Loans</strong></td>
                                <td>
                                    <input type="text"
                                           class="form-control loan-total"
                                           readonly
                                           value="0.00">
                                </td>
                            </tr>

                            {{-- Grand Total --}}
                            <tr class="grand-total-row">
                                <td colspan="2" class="text-end">Grand Total (Share Capital + Loans):</td>
                                <td>
                                    <input type="text"
                                           class="form-control grand-total"
                                           readonly
                                           value="0.00">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Note --}}
                <div class="alert alert-info mt-3 mb-0">
                    <i class="bi bi-info-circle"></i>
                    <strong>Note:</strong> Ideally, the Grand Total should match the Project Cost entered in Step 3.
                </div>

                {{-- Navigation Buttons --}}
                <div class="d-flex justify-content-between mt-4">
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

    // -----------------------------
    // Auto totals calculation
    // -----------------------------
    function calculateTotals() {
        let shareTotal = 0;
        let loanTotal  = 0;

        $('.share-input').each(function() {
            shareTotal += parseFloat($(this).val()) || 0;
        });
        $('.share-total').val(shareTotal.toFixed(2));

        $('.loan-input').each(function() {
            loanTotal += parseFloat($(this).val()) || 0;
        });
        $('.loan-total').val(loanTotal.toFixed(2));

        const grandTotal = shareTotal + loanTotal;
        $('.grand-total').val(grandTotal.toFixed(2));
    }

    // Recalculate on input
    $(document).on('input', '.share-input, .loan-input', calculateTotals);

    // Initial calc on load
    calculateTotals();

    // -----------------------------
    // jQuery Validation
    // -----------------------------
    $("#stepForm").validate({
        ignore: [],
        errorElement: "div",
        errorClass: "invalid-feedback",
        highlight: function (element) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid");
        },
        errorPlacement: function(error, element) {
            if (element.parent(".input-group").length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        rules: {
            share_promoters: {
                number: true,
                min: 0
            },
            share_financial: {
                number: true,
                min: 0
            },
            share_public: {
                number: true,
                min: 0
            },
            loan_financial: {
                number: true,
                min: 0
            },
            loan_banks: {
                number: true,
                min: 0
            },
            loan_others: {
                number: true,
                min: 0
            }
        },
        messages: {
            share_promoters: {
                number: "Please enter a valid number.",
                min: "Amount cannot be negative."
            },
            share_financial: {
                number: "Please enter a valid number.",
                min: "Amount cannot be negative."
            },
            share_public: {
                number: "Please enter a valid number.",
                min: "Amount cannot be negative."
            },
            loan_financial: {
                number: "Please enter a valid number.",
                min: "Amount cannot be negative."
            },
            loan_banks: {
                number: "Please enter a valid number.",
                min: "Amount cannot be negative."
            },
            loan_others: {
                number: "Please enter a valid number.",
                min: "Amount cannot be negative."
            }
        }
    });

});
</script>
@endpush
