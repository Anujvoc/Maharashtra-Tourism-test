{{-- resources/views/frontend/Application/provisional/step6.blade.php --}}
@extends('frontend.layouts2.master')

@section('title', 'Step 6: Declaration & Review')

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

    .review-card {
        border: 1px solid #efefef;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 1rem;
    }
    .review-card-header {
        background: #ff6600;
        color: #fff;
        padding: .75rem 1rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: .5rem;
    }
    .review-card-body {
        padding: 1rem;
        background: #fff;
    }
    .signature-preview {
        max-height: 150px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 10px;
        margin-top: 10px;
    }
    .declaration-list {
        list-style-type: decimal;
        padding-left: 20px;
    }
    .declaration-list li {
        margin-bottom: 10px;
    }

    /* Preview styling */
    .preview-row {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }
    .preview-col {
        flex: 1 1 320px;
        min-width: 220px;
    }
    .preview-item {
        margin-bottom: .6rem;
    }
    .preview-label {
        font-weight: 600;
        color: #666;
        font-size: 0.9rem;
    }
    .preview-value {
        color: #333;
        padding: .25rem 0;
        word-break: break-word;
    }
    .edit-btn {
        background: #2d06bc;
        border: 1px solid #ddd;
        padding: .45rem .8rem;
        border-radius: 6px;
        color: #ffffff;
        text-decoration: none;
        display: inline-block;
        font-size: 0.9rem;
    }
    .edit-btn:hover {
        background: #1e048a;
        color: #fff;
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
            <i class="bi bi-check-circle form-icon"></i>
            <span>Step 6: Application Review Summary & Declaration</span>
        </div>

        <div class="card-body">
            {{-- Success Alert --}}
            <div class="alert alert-success mb-4">
                <i class="bi bi-check-lg me-2"></i> All previous steps have been completed successfully. Please review your information before submitting.
            </div>

            {{-- =============================================
                STEP 1: GENERAL DETAILS PREVIEW
            ============================================= --}}
            <div class="review-card">
                <div class="review-card-header">
                    <i class="bi bi-person-badge"></i>
                    Step 1: Applicant / General Details
                </div>
                <div class="review-card-body">
                    <div class="preview-row">
                        <div class="preview-col">
                            <div class="preview-item">
                                <div class="preview-label">Applicant Name:</div>
                                <div class="preview-value">{{ $registration->applicant_name ?? 'Not provided' }}</div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label">Company Name:</div>
                                <div class="preview-value">{{ $registration->company_name ?? 'Not provided' }}</div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label">Enterprise Type:</div>
                                <div class="preview-value">
                                    @if($registration->enterprise_type)
                                        {{ $applicantTypes->firstWhere('id', $registration->enterprise_type)->name ?? $registration->enterprise_type }}
                                    @else
                                        Not provided
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="preview-col">
                            <div class="preview-item">
                                <div class="preview-label">Aadhar Number:</div>
                                <div class="preview-value">{{ $registration->aadhar_number ?? 'Not provided' }}</div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label">Application Category:</div>
                                <div class="preview-value">
                                    @if($registration->application_category)
                                        {{ $categories->firstWhere('id', $registration->application_category)->name ?? $registration->application_category }}
                                    @else
                                        Not provided
                                    @endif
                                </div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label">District:</div>
                                <div class="preview-value">{{ $registration->district_id ? ($districts->firstWhere('id', $registration->district_id)->name ?? '') : '' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-2">
                        <a href="{{ route('provisional.wizard.show', [$application, 'step' => 1]) }}" class="edit-btn">
                            <i class="bi bi-pencil me-1"></i> Edit Step 1
                        </a>
                    </div>
                </div>
            </div>

            {{-- =============================================
                STEP 2: PROJECT DETAILS PREVIEW
            ============================================= --}}
            <div class="review-card">
                <div class="review-card-header">
                    <i class="bi bi-geo-alt"></i>
                    Step 2: Project Details
                </div>
                <div class="review-card-body">
                    @php
                        $siteAddress = $registration->site_address ?? [];
                    @endphp

                    <div class="preview-row">
                        <div class="preview-col">
                            <div class="preview-item">
                                <div class="preview-label">Site Address:</div>
                                <div class="preview-value">
                                    @if(!empty($siteAddress['survey_type']) && !empty($siteAddress['survey_number']))
                                        {{ $siteAddress['survey_type'] }}: {{ $siteAddress['survey_number'] }}
                                    @else
                                        Not provided
                                    @endif
                                </div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label">Village/City:</div>
                                <div class="preview-value">{{ $siteAddress['village_city'] ?? 'Not provided' }}</div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label">Taluka:</div>
                                <div class="preview-value">{{ $siteAddress['taluka'] ?? 'Not provided' }}</div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label">District:</div>
                                <div class="preview-value">{{ $siteAddress['district'] ?? 'Not provided' }}</div>
                            </div>
                        </div>

                        <div class="preview-col">
                            <div class="preview-item">
                                <div class="preview-label">State:</div>
                                <div class="preview-value">{{ $siteAddress['state'] ?? 'Not provided' }}</div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label">Pincode:</div>
                                <div class="preview-value">{{ $siteAddress['pincode'] ?? 'Not provided' }}</div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label">Mobile:</div>
                                <div class="preview-value">{{ $siteAddress['mobile'] ?? 'Not provided' }}</div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label">Email:</div>
                                <div class="preview-value">{{ $siteAddress['email'] ?? 'Not provided' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="preview-row">
                        <div class="preview-col">
                            <div class="preview-item">
                                <div class="preview-label">Zone:</div>
                                <div class="preview-value">{{ $registration->zone ?? 'Not provided' }}</div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label">Project Type:</div>
                                <div class="preview-value">{{ $registration->project_type ?? 'Not provided' }}</div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label">Project Category:</div>
                                <div class="preview-value">
                                    {{ $registration->project_category ?? 'Not provided' }}
                                    @if($registration->project_category == 'Others' && $registration->other_category)
                                        ({{ $registration->other_category }})
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="preview-col">
                            <div class="preview-item">
                                <div class="preview-label">Project Subcategory:</div>
                                <div class="preview-value">{{ $registration->project_subcategory ?? 'Not provided' }}</div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label">Udyog Aadhar:</div>
                                <div class="preview-value">{{ $registration->udyog_aadhar ?? 'Not provided' }}</div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label">GST Number:</div>
                                <div class="preview-value">{{ $registration->gst_number ?? 'Not provided' }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Entrepreneurs Preview --}}
                    @php
                        $entrepreneurs = $registration->entrepreneurs_profile ?? [];
                    @endphp
                    @if(!empty($entrepreneurs) && count($entrepreneurs) > 0)
                        <div class="mt-3">
                            <div class="preview-label mb-2">Entrepreneurs Profile:</div>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Designation</th>
                                            <th>Ownership %</th>
                                            <th>Gender</th>
                                            <th>Age</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($entrepreneurs as $entrepreneur)
                                            @if(!empty($entrepreneur['name']) || !empty($entrepreneur['designation']))
                                                <tr>
                                                    <td>{{ $entrepreneur['name'] ?? '-' }}</td>
                                                    <td>{{ $entrepreneur['designation'] ?? '-' }}</td>
                                                    <td>{{ $entrepreneur['ownership'] ?? '-' }}</td>
                                                    <td>{{ $entrepreneur['gender'] ?? '-' }}</td>
                                                    <td>{{ $entrepreneur['age'] ?? '-' }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    {{-- Project Description --}}
                    @if($registration->project_description)
                        <div class="preview-item mt-3">
                            <div class="preview-label">Project Description:</div>
                            <div class="preview-value" style="white-space: pre-wrap;">{{ $registration->project_description }}</div>
                        </div>
                    @endif

                    <div class="text-end mt-2">
                        <a href="{{ route('provisional.wizard.show', [$application, 'step' => 2]) }}" class="edit-btn">
                            <i class="bi bi-pencil me-1"></i> Edit Step 2
                        </a>
                    </div>
                </div>
            </div>

            {{-- =============================================
                STEP 3: INVESTMENT DETAILS PREVIEW
            ============================================= --}}
            <div class="review-card">
                <div class="review-card-header">
                    <i class="bi bi-cash-stack"></i>
                    Step 3: Investment Details
                </div>
                <div class="review-card-body">
                    <div class="preview-row">
                        <div class="preview-col">
                            <div class="preview-item">
                                <div class="preview-label">Land Area (Sq. meters):</div>
                                <div class="preview-value">{{ number_format($registration->land_area ?? 0, 2) }}</div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label">Land Ownership Type:</div>
                                <div class="preview-value">{{ $registration->land_ownership_type ?? 'Not provided' }}</div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label">Building Ownership Type:</div>
                                <div class="preview-value">{{ $registration->building_ownership_type ?? 'Not provided' }}</div>
                            </div>
                        </div>

                        <div class="preview-col">
                            <div class="preview-item">
                                <div class="preview-label">Project Cost (₹):</div>
                                <div class="preview-value">₹{{ number_format($registration->project_cost ?? 0, 2) }}</div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label">Total Employees:</div>
                                <div class="preview-value">{{ $registration->total_employees ?? 'Not provided' }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Investment Components Preview --}}
                    @php
                        $investmentData = $registration->investment_components ?? [];
                    @endphp
                    @if(!empty($investmentData))
                        <div class="mt-3">
                            <div class="preview-label mb-2">Investment Components (₹ in Lakh):</div>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Component</th>
                                            <th>Estimated Cost</th>
                                            <th>Investment Made</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach([
                                            'land' => 'Land',
                                            'building' => 'Building',
                                            'machinery' => 'Plant & Machinery',
                                            'engineering' => 'Engineering Fees',
                                            'preop' => 'Preliminary Expense',
                                            'margin' => 'Working Capital Margin'
                                        ] as $key => $label)
                                            @if(isset($investmentData[$key]))
                                                <tr>
                                                    <td>{{ $label }}</td>
                                                    <td>₹{{ number_format($investmentData[$key]['estimated'] ?? 0, 2) }}</td>
                                                    <td>₹{{ number_format($investmentData[$key]['investment_made'] ?? 0, 2) }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <div class="text-end mt-2">
                        <a href="{{ route('provisional.wizard.show', [$application, 'step' => 3]) }}" class="edit-btn">
                            <i class="bi bi-pencil me-1"></i> Edit Step 3
                        </a>
                    </div>
                </div>
            </div>

            {{-- =============================================
                STEP 4: MEANS OF FINANCE PREVIEW
            ============================================= --}}
            <div class="review-card">
                <div class="review-card-header">
                    <i class="bi bi-bank"></i>
                    Step 4: Means of Finance
                </div>
                <div class="review-card-body">
                    @php
                        $financeData = $registration->means_of_finance ?? [];
                        $shareData = $financeData['share_capital'] ?? [];
                        $loanData = $financeData['loans'] ?? [];
                    @endphp

                    <div class="preview-row">
                        <div class="preview-col">
                            <div class="preview-item">
                                <div class="preview-label">Share Capital - Promoters (₹ Lakh):</div>
                                <div class="preview-value">₹{{ number_format($shareData['promoters'] ?? 0, 2) }}</div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label">Share Capital - Financial Institutions (₹ Lakh):</div>
                                <div class="preview-value">₹{{ number_format($shareData['financial_institutions'] ?? 0, 2) }}</div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label">Share Capital - Public (₹ Lakh):</div>
                                <div class="preview-value">₹{{ number_format($shareData['public'] ?? 0, 2) }}</div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label"><strong>Total Share Capital (₹ Lakh):</strong></div>
                                <div class="preview-value"><strong>₹{{ number_format(($shareData['promoters'] ?? 0) + ($shareData['financial_institutions'] ?? 0) + ($shareData['public'] ?? 0), 2) }}</strong></div>
                            </div>
                        </div>

                        <div class="preview-col">
                            <div class="preview-item">
                                <div class="preview-label">Loans - Financial Institutions (₹ Lakh):</div>
                                <div class="preview-value">₹{{ number_format($loanData['financial_institutions'] ?? 0, 2) }}</div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label">Loans - Banks (₹ Lakh):</div>
                                <div class="preview-value">₹{{ number_format($loanData['banks'] ?? 0, 2) }}</div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label">Loans - Others (₹ Lakh):</div>
                                <div class="preview-value">₹{{ number_format($loanData['others'] ?? 0, 2) }}</div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label"><strong>Total Loans (₹ Lakh):</strong></div>
                                <div class="preview-value"><strong>₹{{ number_format(($loanData['financial_institutions'] ?? 0) + ($loanData['banks'] ?? 0) + ($loanData['others'] ?? 0), 2) }}</strong></div>
                            </div>
                        </div>
                    </div>

                    <div class="preview-item mt-3" style="background: #e7f1ff; padding: .75rem; border-radius: 4px;">
                        <div class="preview-label" style="font-size: 1rem;">Grand Total (Share Capital + Loans):</div>
                        <div class="preview-value" style="font-size: 1.2rem; font-weight: bold;">
                            ₹{{ number_format(
                                (($shareData['promoters'] ?? 0) + ($shareData['financial_institutions'] ?? 0) + ($shareData['public'] ?? 0)) +
                                (($loanData['financial_institutions'] ?? 0) + ($loanData['banks'] ?? 0) + ($loanData['others'] ?? 0))
                            , 2) }}
                        </div>
                    </div>

                    <div class="text-end mt-2">
                        <a href="{{ route('provisional.wizard.show', [$application, 'step' => 4]) }}" class="edit-btn">
                            <i class="bi bi-pencil me-1"></i> Edit Step 4
                        </a>
                    </div>
                </div>
            </div>



       {{-- =============================================
    STEP 5: DOCUMENTS PREVIEW (TABULAR)
============================================= --}}
@php
$enclosures     = $registration->enclosures ?? [];
$otherDocuments = $registration->other_documents ?? [];

$documents = [
    'commencement_certificate' => 'Commencement Certificate / Plan Sanction Letter',
    'sanctioned_plan'          => 'Copy of Sanctioned Plan of Construction',
    'proof_of_identity'        => 'Proof of Identity',
    'proof_of_address'         => 'Proof of Address',
    'land_ownership'           => 'Land Ownership Document',
    'project_report'           => 'Project Report',
    'incorporation_documents'  => 'Incorporation Documents',
    'gst_registration'         => 'GST Registration',
    'special_category_proof'   => 'Special Category Proof',
    'ca_certificate'           => 'CA Certificate on Project Cost including investments already made',
    'processing_fee_challan'   => 'Processing Fee Challan (₹10,000) — paid on www.gras.mahakosh.gov.in',
];

$enclosureCount = collect($enclosures)->filter(function($item) {
    return !empty($item['file_path'] ?? null);
})->count();

$otherDocsCount = collect($otherDocuments)->filter(function($item) {
    return !empty($item['file_path'] ?? null);
})->count();

$totalDocs = $enclosureCount + $otherDocsCount;
@endphp

<div class="review-card">
<div class="review-card-header">
    <i class="bi bi-file-earmark-arrow-up"></i>
    Step 5: Documents
</div>

<div class="review-card-body">

    {{-- ================= ENCLSOSURES TABLE ================= --}}
    <h6 class="mb-3">Enclosures:</h6>
    <p class="text-muted mb-3">
        Tabular preview of the documents uploaded in Step 5.
    </p>

    <div class="table-responsive mb-4">
        <table class="table table-bordered table-striped align-middle text-center">
            <thead class="table-primary">
                <tr>
                    <th style="width:5%;">Select</th>
                    <th style="width:30%;">Document Type</th>
                    <th style="width:15%;">Doc No.</th>
                    <th style="width:15%;">Date of Issue</th>
                    <th style="width:20%;">Preview</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documents as $key => $label)
                    @php
                        $item     = $enclosures[$key] ?? null;
                        $path     = $item['file_path'] ?? null;
                        $docNo    = $item['doc_no'] ?? null;
                        $issue    = $item['issue_date'] ?? null;
                        $url      = $path ? asset('storage/'.$path) : null;
                        $ext      = $path ? strtolower(pathinfo($path, PATHINFO_EXTENSION)) : null;
                        $uploaded = $path && $url;
                    @endphp

                    <tr>
                        {{-- Select icon --}}
                        <td>
                            @if($uploaded)
                                <i class="bi bi-check-circle-fill text-success" title="Uploaded"></i>
                            @else
                                <i class="bi bi-x-circle-fill text-danger" title="Not Uploaded"></i>
                            @endif
                        </td>

                        {{-- Document Type --}}
                        <td class="text-start">
                            <strong>{{ $label }}</strong>
                        </td>

                        {{-- Doc No --}}
                        <td>
                            @if($uploaded && $docNo)
                                <span>{{ $docNo }}</span>
                            @elseif($uploaded)
                                <span class="text-muted">—</span>
                            @else
                                <span class="text-muted">Not Provided</span>
                            @endif
                        </td>

                        {{-- Date of Issue --}}
                        <td>
                            @if($uploaded && $issue)
                                <span>{{ \Illuminate\Support\Carbon::parse($issue)->format('d-m-Y') }}</span>
                            @elseif($uploaded)
                                <span class="text-muted">—</span>
                            @else
                                <span class="text-muted">Not Provided</span>
                            @endif
                        </td>

                        {{-- Preview --}}
                        <td>
                            @if($uploaded)
                                @if(in_array($ext, ['jpg','jpeg','png']))
                                    <img src="{{ $url }}"
                                         alt="{{ $label }}"
                                         class="img-thumbnail review-doc-thumb"
                                         data-full="{{ $url }}"
                                         style="max-height:45px;cursor:pointer;">
                                    <small class="text-muted d-block">Click to enlarge</small>
                                @elseif($ext === 'pdf')
                                    <a href="{{ $url }}" target="_blank"
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-file-earmark-pdf"></i> View PDF
                                    </a>
                                @else
                                    <a href="{{ $url }}" target="_blank"
                                       class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-box-arrow-up-right"></i> Open
                                    </a>
                                @endif
                            @else
                                <span class="text-muted">No file uploaded</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-muted">No enclosures configuration found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ================= OTHER DOCUMENTS TABLE ================= --}}
    <div class="mb-4">
        <h6 class="mb-3">Other Documents</h6>
        <p class="text-muted mb-3">Additional documents uploaded in Step 5.</p>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle" id="otherDocsPreview">
                <thead class="table-primary">
                    <tr>
                        <th>Document Name</th>
                        <th>Document No</th>
                        <th>Issue Date</th>
                        <th>Validity Date</th>
                        <th>Preview</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($otherDocuments as $doc)
                        @php
                            $name    = $doc['name'] ?? null;
                            $docNo   = $doc['doc_no'] ?? null;
                            $issue   = $doc['issue_date'] ?? null;
                            $valid   = $doc['validity_date'] ?? null;
                            $path    = $doc['file_path'] ?? null;
                            $url     = $path ? asset('storage/'.$path) : null;
                            $ext     = $path ? strtolower(pathinfo($path, PATHINFO_EXTENSION)) : null;
                            $uploaded = $path && $url;
                        @endphp
                        <tr>
                            <td>{{ $name ?: '—' }}</td>
                            <td>{{ $docNo ?: '—' }}</td>
                            <td>
                                @if($issue)
                                    {{ \Illuminate\Support\Carbon::parse($issue)->format('d-m-Y') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @if($valid)
                                    {{ \Illuminate\Support\Carbon::parse($valid)->format('d-m-Y') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @if($uploaded)
                                    @if(in_array($ext, ['jpg','jpeg','png']))
                                        <img src="{{ $url }}"
                                             alt="Other Document"
                                             class="img-thumbnail review-doc-thumb"
                                             data-full="{{ $url }}"
                                             style="max-height:45px;cursor:pointer;">
                                        <small class="text-muted d-block">Click to enlarge</small>
                                    @elseif($ext === 'pdf')
                                        <a href="{{ $url }}" target="_blank"
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-file-earmark-pdf"></i> View PDF
                                        </a>
                                    @else
                                        <a href="{{ $url }}" target="_blank"
                                           class="btn btn-outline-secondary btn-sm">
                                            <i class="bi bi-box-arrow-up-right"></i> Open
                                        </a>
                                    @endif
                                @else
                                    <span class="text-muted">No file uploaded</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-muted">No other documents uploaded.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- TOTAL DOCS + EDIT BUTTON --}}
    <div class="d-flex justify-content-between align-items-center mt-2">
        <div>
            <strong>Total Documents Uploaded:</strong>
            <span>{{ $totalDocs }} document(s)</span>
        </div>
        <div>
            <a href="{{ route('provisional.wizard.show', [$application, 'step' => 5]) }}"
               class="edit-btn">
                <i class="bi bi-pencil me-1"></i> Edit Step 5
            </a>
        </div>
    </div>
</div>
</div>


{{-- Image preview modal for review page --}}
{{-- Image preview modal for Step 5 review --}}




            {{-- =============================================
                DECLARATION FORM
            ============================================= --}}
            <form id="stepForm" action="{{ route('provisional.wizard.save', [$application->id, $step]) }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf

                <div class="review-card mt-4">
                    <div class="review-card-header">
                        <i class="bi bi-file-text"></i>
                        Declaration
                    </div>
                    <div class="review-card-body">
                        <!-- Declaration Text -->
                        <div class="mb-4">
                            <h6 class="mb-3">Declaration</h6>

                            @php
                            $terms_and_conditions = DB::table('terms_and_conditions')
                                ->where('form_id', $registration->application_form_id)
                                ->first();
                        @endphp

                        {!! $terms_and_conditions->description ?? '' !!}


                        </div>

                        <!-- Declaration Checkbox -->
                        <div class="form-check mb-4">
                            <input class="form-check-input @error('declaration') is-invalid @enderror"
                                   type="checkbox"
                                   id="declaration"
                                   name="declaration"
                                   required
                                   {{ old('declaration', $registration->declaration_accepted ? 'checked' : '') }}>
                            <label class="form-check-label fw-semibold" for="declaration">
                                I hereby agree to the above declaration. <span class="text-danger">*</span>
                            </label>
                            @error('declaration')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Place and Date -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label" for="place">
                                    Place <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       class="form-control @error('place') is-invalid @enderror"
                                       id="place"
                                       name="place"
                                       required
                                       value="{{ old('place', $registration->place) }}"
                                       placeholder="Enter city/town name">
                                @error('place')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="date">
                                    Date <span class="text-danger">*</span>
                                </label>
                                <input type="date"
                                       class="form-control @error('date') is-invalid @enderror"
                                       id="date"
                                       name="date"
                                       required
                                       value="{{ old('date', $registration->date ?? date('Y-m-d')) }}"
                                       max="{{ date('Y-m-d') }}">
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @if($registration->signature_path)
                                <div class="mt-3">
                                    <p class="mb-1"><strong>Existing Signature:</strong></p>
                                    <img src="{{ asset('storage/' . $registration->signature_path) }}"
                                         alt="Signature"
                                         class="img-fluid signature-preview"
                                         style="max-height: 150px;">
                                </div>
                            @endif
                                {{-- Signature Preview --}}
                                <div id="signaturePreviewContainer" class="mt-2" style="display:none;">
                                    <img id="signaturePreview" src="#" alt="Signature Preview"
                                        class="img-thumbnail shadow-sm"
                                        style="max-height:120px; cursor:pointer;">
                                    <div><small class="text-muted">Click to enlarge</small></div>
                                </div>

                                {{-- Message if PDF --}}
                                <div id="signaturePdfMessage" class="text-muted mt-2" style="display:none;">
                                    <i class="bi bi-file-earmark-pdf text-danger"></i>
                                    PDF file selected — preview not available.
                                </div>
                        <div class="mb-3">
                            <label class="form-label" for="signature">
                                Signature of Applicant (Proprietor / Partner / Director / Trustee)
                                <span class="text-danger">*</span>
                            </label>

                            <input type="file"
                                   class="form-control @error('signature') is-invalid @enderror"
                                   id="signature"
                                   name="signature"
                                   accept="image/*,.pdf"
                                   required>

                            @error('signature')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror


                        </div>

                        {{-- Modal for full-size signature --}}





                        <!-- Note -->
                        <div class="alert alert-secondary small fst-italic" role="alert">
                            (This application shall be signed only by any one of the persons indicated above with appropriate rubber stamp of the applicant and designation of the signatory.)
                        </div>

                        <!-- Final Submission Warning -->
                        <div class="alert alert-warning">
                            <h6 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Important</h6>
                            <p class="mb-0">Once you submit this application, you will not be able to make further changes. Please review all information carefully before submitting.</p>
                        </div>
                    </div>
                </div>

                {{-- Navigation Buttons --}}
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('provisional.wizard.show', [$application->id, $step - 1]) }}"
                       class="btn btn-primary">
                        <i class="bi bi-arrow-left"></i> Previous
                    </a>

                    <button type="submit" class="btn btn-success" id="submitBtn" style="background-color:#ff6600;border-color:#ff6600;font-weight:600;"
                    >
                        <i class="bi bi-check-circle"></i> Submit Application
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

{{-- Modal for image preview --}}
<div class="modal fade" id="docPreviewModal" tabindex="-1" aria-labelledby="docPreviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="docPreviewLabel">Document Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="docPreviewImage" src="" alt="Preview" class="img-fluid rounded shadow-sm">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="reviewDocPreviewModal" tabindex="-1" aria-labelledby="reviewDocPreviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewDocPreviewLabel">Document Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="reviewDocPreviewImage" src="" alt="Preview" class="img-fluid rounded shadow-sm">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="signatureModal" tabindex="-1" aria-labelledby="signatureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="signatureModalLabel">Signature Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="signatureModalImg" src="#" alt="Signature Full View" class="img-fluid rounded shadow-sm">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script>
$(document).ready(function() {
    // Preview signature
    function previewSignature(event) {
        const input = event.target;
        const preview = document.getElementById('signaturePreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            const file = input.files[0];
            const fileType = file.type;

            reader.onload = function(e) {
                if (fileType.startsWith('image/')) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Signature Preview" class="img-fluid rounded">`;
                } else if (fileType === 'application/pdf') {
                    preview.innerHTML = `
                        <div class="text-center">
                            <i class="bi bi-file-earmark-pdf" style="font-size: 48px; color: #dc3545;"></i>
                            <p class="mt-2">${file.name}</p>
                            <a href="${e.target.result}" target="_blank" class="btn btn-sm btn-outline-primary">
                                View PDF
                            </a>
                        </div>
                    `;
                } else {
                    preview.innerHTML = `<p class="text-muted">File: ${file.name}</p>`;
                }
                preview.style.display = 'block';
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    // Form validation
    $("#stepForm").validate({
        rules: {
            declaration: {
                required: true
            },
            place: {
                required: true,
                minlength: 2
            },
            date: {
                required: true,
                date: true
            },
            signature: {
                required: true,
                accept: "image/*,application/pdf",
                filesize: 5120 // 5MB
            }
        },
        messages: {
            declaration: {
                required: "You must agree to the declaration"
            },
            place: {
                required: "Please enter the place",
                minlength: "Place must be at least 2 characters"
            },
            date: {
                required: "Please select the date",
                date: "Please enter a valid date"
            },
            signature: {
                required: "Please upload your signature",
                accept: "Please upload an image (JPG, PNG, etc.) or PDF file",
                filesize: "File size must be less than 5MB"
            }
        },
        errorClass: "text-danger small mt-1",
        errorElement: "div",
        highlight: function (element) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid");
        },
        submitHandler: function(form) {
            // Show confirmation dialog
            if (confirm('Are you sure you want to submit this application?\n\nOnce submitted, you will not be able to make changes.')) {
                // Disable submit button to prevent double submission
                $('#submitBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...');
                form.submit();
            }
            return false;
        }
    });

    // Custom validation method for file size
    $.validator.addMethod("filesize", function(value, element, param) {
        if (element.files.length > 0) {
            const fileSize = element.files[0].size / 1024; // KB
            return this.optional(element) || (fileSize <= param);
        }
        return true;
    }, "File size must be less than {0} KB");
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        $(document).on('click', '.review-doc-thumb', function () {
            const src = $(this).data('full');
            if (!src) return;

            $('#reviewDocPreviewImage').attr('src', src);
            const modal = new bootstrap.Modal(document.getElementById('reviewDocPreviewModal'));
            modal.show();
        });
    });
</script>

<script>
$(document).ready(function() {

    // When user selects a signature file
    $('#signature').on('change', function(event) {
        const file = event.target.files[0];
        const $previewContainer = $('#signaturePreviewContainer');
        const $previewImg = $('#signaturePreview');
        const $pdfMessage = $('#signaturePdfMessage');

        // Reset everything first
        $previewContainer.hide();
        $pdfMessage.hide();

        if (!file) return;

        const fileType = file.type.toLowerCase();

        // If file is image (jpg/png/jpeg/webp etc)
        if (fileType.startsWith('image/')) {
            const imgURL = URL.createObjectURL(file);
            $previewImg.attr('src', imgURL);
            $previewContainer.show();
        }
        // If file is PDF
        else if (fileType === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf')) {
            $pdfMessage.show();
        }
        // Any other unsupported format
        else {
            alert('Only image or PDF files are allowed.');
            $(this).val('');
        }
    });

    // On clicking the small signature preview → show modal
    $('#signaturePreview').on('click', function() {
        const src = $(this).attr('src');
        if (src) {
            $('#signatureModalImg').attr('src', src);
            const modal = new bootstrap.Modal(document.getElementById('signatureModal'));
            modal.show();
        }
    });
});
</script>


@endpush
