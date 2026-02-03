{{-- resources/views/frontend/stamp_duty/wizard_review.blade.php --}}

@extends('frontend.layouts2.master')

@section('title', 'Stamp Duty – Review & Submit')

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
    .card-header-orange {
        background-color:var(--brand);
        color:#ff6600;
        padding:.75rem 1rem;
        font-weight:700;
        display:flex;
        align-items:center;
        gap:.5rem;
    }
    .section-title {
        font-size:1rem;
        font-weight:700;
        margin-bottom:.75rem;
        display:flex;
        align-items:center;
        gap:.5rem;
        color:var(--brand);
    }
    .preview-row {
        display:flex;
        padding:.35rem 0;
        border-bottom:1px dashed #eee;
        font-size:0.92rem;
    }
    .preview-label {
        flex:0 0 230px;
        font-weight:600;
        color:#333;
    }
    .preview-value {
        flex:1;
        color:#555;
    }
    .preview-na {
        color:#999;
        font-style:italic;
    }
    .error {
        color:#dc3545;
        font-size:0.85rem;
        margin-top:0.25rem;
    }
</style>
@endpush

@section('content')
<section class="section">
    <div class="section-header d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-3">
        <h1 class="mb-2 mb-md-0">
            <i class="fa-solid fa-route" style="color:#ff6600;"></i>
            Application for Stamp Duty Exemption – Review
        </h1>
    </div>

    {{-- Stepper --}}
    @include('frontend.Application.stamp_duty._stepper', [
        'step'        => $step,      {{-- should be totalSteps --}}
        'application' => $application,
        'progress'    => $progress,
        'done'        => $done,
        'total'       => $total,
    ])

    <div class="card shadow-sm mb-4">
        <div class="card-header card-header-orange">
            <i class="fa-solid fa-eye"></i>
            <span>Review Your Application Before Final Submission</span>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success py-2 mb-3">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger py-2 mb-3">
                    Please accept the final declaration and fix any errors below.
                </div>
            @endif

            <div class="alert alert-warning mb-4" style="border-left:4px solid #ff6600;">
                <i class="fa-solid fa-triangle-exclamation me-2" style="color:#ff6600;"></i>
                Please carefully verify all details below. If you need to make changes, use the <strong>Back to Edit</strong> buttons for the respective step.
            </div>

            {{-- Basic Meta Info --}}
            <div class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="border rounded p-2">
                            <div class="small text-muted">Registration ID</div>
                            <div class="fw-bold">{{ $application->registration_id ?? '—' }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-2">
                            <div class="small text-muted">Slug</div>
                            <div class="fw-bold">{{ $application->slug_id ?? '—' }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-2">
                            <div class="small text-muted">Status</div>
                            <div class="fw-bold text-capitalize">{{ $application->status ?? 'draft' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION 1: General Details --}}
            <div class="card mb-3 border-0">
                <div class="card-header card-header-orange" style="border-radius:.5rem .5rem 0 0;">
                    <i class="fa-solid fa-user"></i>
                    <span>Step 1: General Details</span>
                    <a href="{{ route('stamp-duty.wizard', ['id' => $application->application_form_id, 'step' => 1, 'application' => $application->id]) }}"
                       class="btn btn-sm ms-auto"
                       style="background:#fff;color:#ff6600;border-radius:20px;border:none;">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>
                </div>
                <div class="card-body border border-top-0 rounded-bottom">
                    <div class="preview-row">
                        <div class="preview-label">Region</div>
                        <div class="preview-value">
                            {{ optional($regions[$application->region_id] ?? null)->name ?? '—' }}
                        </div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">District</div>
                        <div class="preview-value">
                            {{ optional($districts[$application->district_id] ?? null)->name ?? '—' }}
                        </div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Applicant / Company Name</div>
                        <div class="preview-value">{{ $application->company_name ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Registration No.</div>
                        <div class="preview-value">{{ $application->registration_no ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Application Date</div>
                        <div class="preview-value">
                            {{ optional($application->application_date)->format('d-m-Y') ?? '—' }}
                        </div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Type of Enterprise</div>
                        <div class="preview-value">{{ $applicant_type ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Agreement Type</div>
                        <div class="preview-value">{{ $application->agreement_type ?? '—' }}</div>
                    </div>
                </div>
            </div>

            {{-- SECTION 2: Correspondence & Project Site --}}
            <div class="card mb-3 border-0">
                <div class="card-header card-header-orange" style="border-radius:.5rem .5rem 0 0;">
                    <i class="fa-solid fa-address-book"></i>
                    <span>Step 2: Correspondence & Project Site Address</span>
                    <a href="{{ route('stamp-duty.wizard', ['id' => $application->application_form_id, 'step' => 2, 'application' => $application->id]) }}"
                       class="btn btn-sm ms-auto"
                       style="background:#fff;color:#ff6600;border-radius:20px;border:none;">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>
                </div>
                <div class="card-body border border-top-0 rounded-bottom">
                    <div class="section-title">
                        <i class="fa-solid fa-envelope-open-text"></i> Correspondence Address
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Address</div>
                        <div class="preview-value">{{ $application->c_address ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">City / Village</div>
                        <div class="preview-value">{{ $application->c_city ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Taluka</div>
                        <div class="preview-value">{{ $application->c_taluka ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">District</div>
                        <div class="preview-value">{{ $application->c_district ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">State</div>
                        <div class="preview-value">{{ $application->c_state ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Pin Code</div>
                        <div class="preview-value">{{ $application->c_pincode ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Mobile</div>
                        <div class="preview-value">{{ $application->c_mobile ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Telephone</div>
                        <div class="preview-value">{{ $application->c_phone ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Email</div>
                        <div class="preview-value">{{ $application->c_email ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Fax</div>
                        <div class="preview-value">{{ $application->c_fax ?? '—' }}</div>
                    </div>

                    <hr>

                    <div class="section-title">
                        <i class="fa-solid fa-location-dot"></i> Project Site Address
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Address (Gat / Survey)</div>
                        <div class="preview-value">{{ $application->p_address ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">City / Village</div>
                        <div class="preview-value">{{ $application->p_city ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Taluka</div>
                        <div class="preview-value">{{ $application->p_taluka ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">District</div>
                        <div class="preview-value">{{ $application->p_district ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">State</div>
                        <div class="preview-value">{{ $application->p_state ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Pin Code</div>
                        <div class="preview-value">{{ $application->p_pincode ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Mobile</div>
                        <div class="preview-value">{{ $application->p_mobile ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Telephone</div>
                        <div class="preview-value">{{ $application->p_phone ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Email</div>
                        <div class="preview-value">{{ $application->p_email ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Website</div>
                        <div class="preview-value">{{ $application->p_website ?? '—' }}</div>
                    </div>
                </div>
            </div>

            {{-- SECTION 3: Land --}}
            <div class="card mb-3 border-0">
                <div class="card-header card-header-orange" style="border-radius:.5rem .5rem 0 0;">
                    <i class="fa-solid fa-mountain-city"></i>
                    <span>Step 3: Land & Built-up Area</span>
                    <a href="{{ route('stamp-duty.wizard', ['id' => $application->application_form_id, 'step' => 3, 'application' => $application->id]) }}"
                       class="btn btn-sm ms-auto"
                       style="background:#fff;color:#ff6600;border-radius:20px;border:none;">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>
                </div>
                <div class="card-body border border-top-0 rounded-bottom">
                    <div class="section-title">
                        <i class="fa-solid fa-map-location-dot"></i> Land Details
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">CTS / Gat No.</div>
                        <div class="preview-value">{{ $application->land_gat ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Village</div>
                        <div class="preview-value">{{ $application->land_village ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Taluka</div>
                        <div class="preview-value">{{ $application->land_taluka ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">District</div>
                        <div class="preview-value">{{ $application->land_district ?? '—' }}</div>
                    </div>

                    <hr>

                    <div class="section-title">
                        <i class="fa-solid fa-ruler-combined"></i> Area Details (Sq. M.)
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">(A) Total land area</div>
                        <div class="preview-value">{{ $application->area_a ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">(B) Land & built-up area</div>
                        <div class="preview-value">{{ $application->area_b ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">(C) Land for tourism project</div>
                        <div class="preview-value">{{ $application->area_c ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">(D) Land for ancillary activity</div>
                        <div class="preview-value">{{ $application->area_d ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">(E) Vacant land</div>
                        <div class="preview-value">{{ $application->area_e ?? '—' }}</div>
                    </div>

                    <hr>

                    <div class="section-title">
                        <i class="fa-solid fa-seedling"></i> Non-Agricultural Land (if any)
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">NA CTS / Gat No.</div>
                        <div class="preview-value">{{ $application->na_gat ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">NA Village</div>
                        <div class="preview-value">{{ $application->na_village ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">NA Taluka</div>
                        <div class="preview-value">{{ $application->na_taluka ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">NA District</div>
                        <div class="preview-value">{{ $application->na_district ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Total NA Area</div>
                        <div class="preview-value">{{ $application->na_area ?? '—' }}</div>
                    </div>
                </div>
            </div>

            {{-- SECTION 4: Cost & Employment --}}
            <div class="card mb-3 border-0">
                <div class="card-header card-header-orange" style="border-radius:.5rem .5rem 0 0;">
                    <i class="fa-solid fa-indian-rupee-sign"></i>
                    <span>Step 4: Project Cost, Employment & Purpose</span>
                    <a href="{{ route('stamp-duty.wizard', ['id' => $application->application_form_id, 'step' => 4, 'application' => $application->id]) }}"
                       class="btn btn-sm ms-auto"
                       style="background:#fff;color:#ff6600;border-radius:20px;border:none;">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>
                </div>
                <div class="card-body border border-top-0 rounded-bottom">
                    <div class="preview-row">
                        <div class="preview-label">Estimated Project Cost (₹)</div>
                        <div class="preview-value">{{ $application->estimated_project_cost ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Proposed Employment</div>
                        <div class="preview-value">{{ $application->proposed_employment ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Tourism Activities / Facilities</div>
                        <div class="preview-value">{{ $application->tourism_activities ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Incentives Availed Earlier</div>
                        <div class="preview-value">{{ $application->incentives_availed ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Project existed before 18/07/2024?</div>
                        <div class="preview-value">
                            {{ ($application->existed_before ?? 0) ? 'Yes' : 'No' }}
                        </div>
                    </div>
                    @if($application->existed_before)
                        <div class="preview-row">
                            <div class="preview-label">Eligibility Certificate No.</div>
                            <div class="preview-value">{{ $application->eligibility_cert_no ?? '—' }}</div>
                        </div>
                        <div class="preview-row">
                            <div class="preview-label">Eligibility Date</div>
                            <div class="preview-value">
                                {{ optional($application->eligibility_date)->format('d-m-Y') ?? '—' }}
                            </div>
                        </div>
                        <div class="preview-row">
                            <div class="preview-label">Present Status</div>
                            <div class="preview-value">{{ $application->present_status ?? '—' }}</div>
                        </div>
                    @endif

                    <hr>

                    <div class="section-title">
                        <i class="fa-solid fa-table-list"></i> Project Cost (₹ in Lakhs)
                    </div>
                    @php
                        $costs = [
                            'cost_land'      => 'Land',
                            'cost_building'  => 'Building',
                            'cost_machinery' => 'Plant & Machinery',
                            'cost_electrical'=> 'Electrical Installations',
                            'cost_misc'      => 'Misc. Fixed Assets',
                            'cost_other'     => 'Other Expenses',
                        ];
                        $totalCost = 0;
                        foreach ($costs as $f => $l) {
                            $totalCost += (float)($application->{$f} ?? 0);
                        }
                    @endphp
                    @foreach($costs as $field => $label)
                        <div class="preview-row">
                            <div class="preview-label">{{ $label }}</div>
                            <div class="preview-value">{{ $application->{$field} ?? '0.00' }}</div>
                        </div>
                    @endforeach
                    <div class="preview-row">
                        <div class="preview-label fw-bold">Total Project Cost</div>
                        <div class="preview-value fw-bold">{{ number_format($totalCost, 2) }}</div>
                    </div>

                    <hr>

                    <div class="preview-row">
                        <div class="preview-label">Proposed Employment (for project)</div>
                        <div class="preview-value">{{ $application->project_employment ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Purpose of NOC</div>
                        <div class="preview-value">{{ $application->noc_purpose ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">NOC Authority</div>
                        <div class="preview-value">{{ $application->noc_authority ?? '—' }}</div>
                    </div>
                </div>
            </div>

            {{-- SECTION 5: Documents --}}
            <div class="card mb-3 border-0">
                <div class="card-header card-header-orange" style="border-radius:.5rem .5rem 0 0;">
                    <i class="fa-solid fa-file-arrow-up"></i>
                    <span>Step 5: Uploaded Documents</span>
                    <a href="{{ route('stamp-duty.wizard', ['id' => $application->application_form_id, 'step' => 5, 'application' => $application->id]) }}"
                       class="btn btn-sm ms-auto"
                       style="background:#fff;color:#ff6600;border-radius:20px;border:none;">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>
                </div>
                <div class="card-body border border-top-0 rounded-bottom">
                    @php
                        $docs = [
                            'doc_challan'           => 'Challan for processing fees',
                            'doc_affidavit'         => 'Affidavit',
                            'doc_registration'      => 'Registration proof (Company / Firm / Society)',
                            'doc_ror'               => 'Records of Right (RoR)',
                            'doc_land_map'          => 'Map of the land',
                            'doc_dpr'               => 'Detailed Project Report (DPR)',
                            'doc_agreement'         => 'Draft Agreement / Letter of Allotment',
                            'doc_construction_plan' => 'Proposed plan of constructions',
                            'doc_dp_remarks'        => 'D.P. remarks / Zone Certificate',
                        ];
                    @endphp
                    @foreach($docs as $field => $label)
                        <div class="preview-row">
                            <div class="preview-label">{{ $label }}</div>
                            <div class="preview-value">
                                @if(!empty($application->{$field}))
                                    <a href="{{ asset('storage/'.$application->{$field}) }}" target="_blank">
                                        <i class="fa-solid fa-eye" style="color:#ff6600;"></i>
                                        View document
                                    </a>
                                @else
                                    <span class="preview-na">Not uploaded</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- SECTION 6: Declaration & Affidavit --}}
            <div class="card mb-4 border-0">
                <div class="card-header card-header-orange" style="border-radius:.5rem .5rem 0 0;">
                    <i class="fa-solid fa-file-signature"></i>
                    <span>Step 6: Declaration & Affidavit</span>
                    <a href="{{ route('stamp-duty.wizard', ['id' => $application->application_form_id, 'step' => 6, 'application' => $application->id]) }}"
                       class="btn btn-sm ms-auto"
                       style="background:#fff;color:#ff6600;border-radius:20px;border:none;">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>
                </div>
                <div class="card-body border border-top-0 rounded-bottom">
                    <div class="preview-row">
                        <div class="preview-label">Name & Designation</div>
                        <div class="preview-value">{{ $application->name_designation ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Signature</div>
                        <div class="preview-value">
                            @if(!empty($application->signature_path))
                                <a href="{{ asset('storage/'.$application->signature_path) }}" target="_blank">
                                    <i class="fa-solid fa-eye" style="color:#ff6600;"></i> View signature
                                </a>
                            @else
                                <span class="preview-na">Not uploaded</span>
                            @endif
                        </div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Rubber Stamp</div>
                        <div class="preview-value">
                            @if(!empty($application->stamp_path))
                                <a href="{{ asset('storage/'.$application->stamp_path) }}" target="_blank">
                                    <i class="fa-solid fa-eye" style="color:#ff6600;"></i> View stamp
                                </a>
                            @else
                                <span class="preview-na">Not uploaded</span>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="section-title">
                        <i class="fa-solid fa-scroll"></i> Affidavit Details
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Affidavit Name</div>
                        <div class="preview-value">{{ $application->aff_name ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Company</div>
                        <div class="preview-value">{{ $application->aff_company ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Registered Office</div>
                        <div class="preview-value">{{ $application->aff_registered_office ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Land Area (Sq. M.)</div>
                        <div class="preview-value">{{ $application->aff_land_area ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">CTS / Gat No.</div>
                        <div class="preview-value">{{ $application->aff_cts ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Village</div>
                        <div class="preview-value">{{ $application->aff_village ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">Taluka</div>
                        <div class="preview-value">{{ $application->aff_taluka ?? '—' }}</div>
                    </div>
                    <div class="preview-row">
                        <div class="preview-label">District</div>
                        <div class="preview-value">{{ $application->aff_district ?? '—' }}</div>
                    </div>
                </div>
            </div>

            {{-- FINAL SUBMIT FORM --}}
            <form method="POST"
                  action="{{ route('stamp-duty.submit', $application->id) }}"
                  id="finalSubmitForm"
                  novalidate>
                @csrf

                <div class="border rounded p-3 mb-3" style="background:#fff7ec;border-left:4px solid #ff6600;">
                    <div class="form-check">
                        <input class="form-check-input"
                               type="checkbox"
                               value="1"
                               id="declaration_accept"
                               name="declaration_accept"
                               {{ old('declaration_accept') ? 'checked' : '' }}>
                        <label class="form-check-label" for="declaration_accept">
                            I hereby declare that all the information provided in this application is true and correct
                            to the best of my knowledge. I understand that any false information may lead to rejection
                            of my application and legal action.
                        </label>
                    </div>
                    <div class="error" id="declaration_accept_error">
                        @error('declaration_accept') {{ $message }} @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('stamp-duty.wizard', ['id' => $application->application_form_id, 'step' => 6, 'application' => $application->id]) }}"
                       class="btn"
                       style="background-color:#6c757d;color:#fff;padding:.5rem 1.5rem;border-radius:6px;border:none;">
                        <i class="fa-solid fa-arrow-left"></i> &nbsp; Back to Step 6
                    </a>

                    <button type="submit"
                            class="btn"
                            style="background-color:#ff6600;color:#fff;padding:.5rem 1.8rem;border-radius:6px;border:none;">
                        <i class="fa-solid fa-paper-plane me-1"></i>
                        Confirm & Submit Application
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
        $('#finalSubmitForm').validate({
            ignore: [],
            errorClass: 'is-invalid',
            validClass: 'is-valid',
            errorElement: 'div',
            errorPlacement: function (error, element) {
                if (element.attr('name') === 'declaration_accept') {
                    $('#declaration_accept_error').html(error.text());
                }
            },
            success: function (label, element) {
                if ($(element).attr('name') === 'declaration_accept') {
                    $('#declaration_accept_error').html('');
                }
            },
            rules: {
                declaration_accept: { required:true }
            },
            messages: {
                declaration_accept: "You must accept the declaration before final submission."
            }
        });
    });
</script>
@endpush
