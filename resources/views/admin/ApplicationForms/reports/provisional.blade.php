<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Provisional Registration Report</title>
    
    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 14px;
            color: #333;
            line-height: 1.5;
            background: #fff;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
        }

        /* Header Table */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #000;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .logo {
            max-height: 80px;
            max-width: 100%;
        }
        .form-title {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin: 2px 0;
            text-transform: uppercase;
        }

        /* Section Titles */
        .section-title {
            background-color: #eee;
            padding: 8px 10px;
            font-weight: bold;
            font-size: 15px;
            border-left: 5px solid #ff6600;
            margin-top: 20px;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        /* Info Tables */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .info-table th, .info-table td {
            border: 1px solid #ddd;
            padding: 8px;
            vertical-align: top;
        }
        .info-table th {
            width: 25%;
            background-color: #f9f9f9;
            font-weight: bold;
            text-align: left;
            color: #555;
        }
        .info-table td {
            width: 25%;
        }

        /* Badges */
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-uploaded { background-color: #d1e7dd; color: #0f5132; }
        .status-pending { background-color: #f8d7da; color: #842029; }

        .current-stage-banner {
            background-color: #fff3cd;
            color: #664d03;
            border: 1px solid #ffecb5;
            padding: 10px;
            text-align: center;
            font-weight: bold;
            font-size: 1.1em;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.1;
            z-index: -1;
            pointer-events: none;
            width: 60%;
        }
        .watermark img {
            width: 100%;
        }

        .btn-print {
            background-color: #333;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-print:hover {
            background-color: #000;
        }

        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
            .container { max-width: 100%; }
        }
    </style>
</head>
<body>
@php
    // Helper variables (same data as step 6 page)
    $registration_id = $registration->registration_id ?? $registration->id;
    $siteAddress = $registration->site_address ?? [];
    $entrepreneurs = $registration->entrepreneurs_profile ?? [];
    $investmentData = $registration->investment_components ?? [];
    $financeData = $registration->means_of_finance ?? [];
    $shareData = $financeData['share_capital'] ?? [];
    $loanData  = $financeData['loans'] ?? [];

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

    $submittedAt = $registration->submitted_at ?? $registration->created_at;
    $signUrl = $registration->signature_path
        ? asset('storage/' . $registration->signature_path)
        : null;

    $terms_and_conditions = DB::table('terms_and_conditions')
        ->where('form_id', $registration->application_form_id)
        ->first();
@endphp

    {{-- WATERMARK (fixed, appears on all printed pages) --}}
    <div class="watermark">
        <img src="{{ asset('frontend/mah-logo-300x277.png') }}" alt="Watermark">
    </div>

    <div class="container">

        <!-- Header -->
        <table class="header-table">
            <tr>
                <td width="20%">
                    <img src="{{ asset('frontend/mah-logo-300x277.png') }}" alt="Logo" class="logo">
                </td>
                <td width="60%" style="text-align: center;">
                    <div class="form-title">Tourism Registrations &amp; Certificates</div>
                    <div class="form-title">RIGHT TO SERVICES (RTS)</div>
                    <div class="form-title">Application Form for the {{ $application_form->name ?? '' }}</div>
                </td>
                <td width="20%" style="text-align: center;">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/26/Seal_of_Maharashtra.png?20170924125820"
                         alt="Maharashtra Seal"
                         class="logo">
                </td>
            </tr>
        </table>
        
        <div class="current-stage-banner">
            Current Stage: {{ ucwords($registration->current_stage ?? 'Submitted') }}
        </div>

        {{-- Applicant / General Details (STEP 1) --}}
        <div class="section-title">Step 1: Applicant / General Details</div>
        <table class="info-table">
            <tr>
                <th>Registration No:</th>
                <td>{{ $registration_id ?? '—' }}</td>
                <th>Date of Birth:</th>
                <td>—</td>
            </tr>
            <tr>
                <th>Applicant Name:</th>
                <td>{{ $registration->applicant_name ?? '—' }}</td>
                <th>Gender:</th>
                <td>—</td>
            </tr>
            <tr>
                <th>Company Name:</th>
                <td>{{ $registration->company_name ?? '—' }}</td>
                <th>Enterprise Type:</th>
                <td>
                    @if($enterprise)
                        {{ $enterprise->name }}
                    @else
                        —
                    @endif
                </td>
            </tr>
            <tr>
                <th>Aadhar No:</th>
                <td>{{ $registration->aadhar_number ?? '—' }}</td>
                <th>Application Category:</th>
                <td>
                    @if($Category_name)
                        {{ $Category_name }}
                    @else
                        —
                    @endif
                </td>
            </tr>
            <tr>
                <th>Division / Zone:</th>
                <td>{{ $division->name ?? '—' }}</td>
                <th>District:</th>
                <td>
                    @if(!empty($districtsData))
                        {{ $districtsData->name }}
                    @else
                        —
                    @endif
                </td>
            </tr>
        </table>

        {{-- Project Details (STEP 2) --}}
        <div class="section-title">Step 2: Project Details</div>
        <table class="info-table">
            <tr>
                <th>Site Address:</th>
                <td>
                    @if(!empty($siteAddress['survey_type']) && !empty($siteAddress['survey_number']))
                        {{ $siteAddress['survey_type'] }}: {{ $siteAddress['survey_number'] }}
                    @else
                        —
                    @endif
                </td>
                <th>Village / City:</th>
                <td>{{ $siteAddress['village_city'] ?? '—' }}</td>
            </tr>
            <tr>
                <th>Taluka:</th>
                <td>{{ $siteAddress['taluka'] ?? '—' }}</td>
                <th>District:</th>
                <td>{{ $siteAddress['district'] ?? '—' }}</td>
            </tr>
            <tr>
                <th>State:</th>
                <td>{{ $siteAddress['state'] ?? '—' }}</td>
                <th>Pincode:</th>
                <td>{{ $siteAddress['pincode'] ?? '—' }}</td>
            </tr>
            <tr>
                <th>Mobile:</th>
                <td>{{ $siteAddress['mobile'] ?? '—' }}</td>
                <th>Email:</th>
                <td>{{ $siteAddress['email'] ?? '—' }}</td>
            </tr>
            <tr>
                <th>Project Type:</th>
                <td>{{ $registration->project_type ?? '—' }}</td>
                <th>Project Category:</th>
                <td>
                    {{ $registration->project_category ?? '—' }}
                    @if($registration->project_category == 'Others' && $registration->other_category)
                        ({{ $registration->other_category }})
                    @endif
                </td>
            </tr>
            <tr>
                <th>Project Subcategory:</th>
                <td>{{ $registration->project_subcategory ?? '—' }}</td>
                <th>Udyog Aadhar:</th>
                <td>{{ $registration->udyog_aadhar ?? '—' }}</td>
            </tr>
            <tr>
                <th>GST Number:</th>
                <td>{{ $registration->gst_number ?? '—' }}</td>
                <th></th>
                <td></td>
            </tr>
        </table>

        {{-- Entrepreneurs Profile --}}
        @if(!empty($entrepreneurs) && count($entrepreneurs) > 0)
            <table class="info-table">
                <tr>
                    <th colspan="5" style="text-align:left;">Entrepreneurs Profile</th>
                </tr>
                <tr>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Ownership %</th>
                    <th>Gender</th>
                    <th>Age</th>
                </tr>
                @foreach($entrepreneurs as $entrepreneur)
                    @if(!empty($entrepreneur['name']) || !empty($entrepreneur['designation']))
                        <tr>
                            <td>{{ $entrepreneur['name'] ?? '—' }}</td>
                            <td>{{ $entrepreneur['designation'] ?? '—' }}</td>
                            <td>{{ $entrepreneur['ownership'] ?? '—' }}</td>
                            <td>{{ $entrepreneur['gender'] ?? '—' }}</td>
                            <td>{{ $entrepreneur['age'] ?? '—' }}</td>
                        </tr>
                    @endif
                @endforeach
            </table>
        @endif

        {{-- Project Description --}}
        @if($registration->project_description)
            <table class="info-table">
                <tr>
                    <th style="width:20%;">Project Description:</th>
                    <td style="white-space: pre-wrap;">{{ $registration->project_description }}</td>
                </tr>
            </table>
        @endif

        {{-- Investment Details (STEP 3) --}}
        <div class="section-title">Step 3: Investment Details</div>
        <table class="info-table">
            <tr>
                <th>Land Area (Sq. meters):</th>
                <td>{{ number_format($registration->land_area ?? 0, 2) }}</td>
                <th>Land Ownership Type:</th>
                <td>{{ $registration->land_ownership_type ?? '—' }}</td>
            </tr>
            <tr>
                <th>Building Ownership Type:</th>
                <td>{{ $registration->building_ownership_type ?? '—' }}</td>
                <th>Project Cost (₹):</th>
                <td>₹{{ number_format($registration->project_cost ?? 0, 2) }}</td>
            </tr>
            <tr>
                <th>Total Employees:</th>
                <td>{{ $registration->total_employees ?? '—' }}</td>
                <th></th>
                <td></td>
            </tr>
        </table>

        {{-- Investment Components Table --}}
        @if(!empty($investmentData))
            <table class="info-table">
                <tr>
                    <th colspan="3" style="text-align:left;">Investment Components (₹ in Lakh)</th>
                </tr>
                <tr>
                    <th>Component</th>
                    <th>Estimated Cost</th>
                    <th>Investment Made</th>
                </tr>
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
            </table>
        @endif

        {{-- Means of Finance (STEP 4) --}}
        <div class="section-title">Step 4: Means of Finance</div>
        <table class="info-table">
            <tr>
                <th colspan="2">Share Capital (₹ Lakh)</th>
                <th colspan="2">Loans (₹ Lakh)</th>
            </tr>
            <tr>
                <th>Promoters</th>
                <td>₹{{ number_format($shareData['promoters'] ?? 0, 2) }}</td>
                <th>Financial Institutions</th>
                <td>₹{{ number_format($loanData['financial_institutions'] ?? 0, 2) }}</td>
            </tr>
            <tr>
                <th>Financial Institutions</th>
                <td>₹{{ number_format($shareData['financial_institutions'] ?? 0, 2) }}</td>
                <th>Banks</th>
                <td>₹{{ number_format($loanData['banks'] ?? 0, 2) }}</td>
            </tr>
            <tr>
                <th>Public</th>
                <td>₹{{ number_format($shareData['public'] ?? 0, 2) }}</td>
                <th>Others</th>
                <td>₹{{ number_format($loanData['others'] ?? 0, 2) }}</td>
            </tr>
            <tr>
                <th><strong>Total Share Capital</strong></th>
                <td>
                    <strong>
                        ₹{{ number_format(
                            ($shareData['promoters'] ?? 0)
                            + ($shareData['financial_institutions'] ?? 0)
                            + ($shareData['public'] ?? 0),
                            2
                        ) }}
                    </strong>
                </td>
                <th><strong>Total Loans</strong></th>
                <td>
                    <strong>
                        ₹{{ number_format(
                            ($loanData['financial_institutions'] ?? 0)
                            + ($loanData['banks'] ?? 0)
                            + ($loanData['others'] ?? 0),
                            2
                        ) }}
                    </strong>
                </td>
            </tr>
            <tr>
                <th colspan="2"><strong>Grand Total (Share Capital + Loans)</strong></th>
                <td colspan="2">
                    <strong>
                        ₹{{ number_format(
                            (($shareData['promoters'] ?? 0)
                            + ($shareData['financial_institutions'] ?? 0)
                            + ($shareData['public'] ?? 0))
                            +
                            (($loanData['financial_institutions'] ?? 0)
                            + ($loanData['banks'] ?? 0)
                            + ($loanData['others'] ?? 0)),
                            2
                        ) }}
                    </strong>
                </td>
            </tr>
        </table>

        {{-- Documents (STEP 5) --}}
        <div class="section-title">Step 5: Documents (Enclosures & Other Documents)</div>

        {{-- Enclosures Table --}}
        <table class="info-table">
            <tr>
                <th colspan="5" style="text-align:left;">Enclosures</th>
            </tr>
            <tr>
                <th style="width:7%;">Status</th>
                <th style="width:35%;">Document Type</th>
                <th style="width:18%;">Doc No.</th>
                <th style="width:18%;">Date of Issue</th>
                <th style="width:22%;">Remarks</th>
            </tr>
            @forelse($documents as $key => $label)
                @php
                    $item  = $enclosures[$key] ?? null;
                    $path  = $item['file_path'] ?? null;
                    $docNo = $item['doc_no'] ?? null;
                    $issue = $item['issue_date'] ?? null;
                    $uploaded = !empty($path);
                @endphp
                <tr>
                    <td style="text-align:center;">
                        @if($uploaded)
                            <span class="status-badge status-uploaded">Uploaded</span>
                        @else
                            <span class="status-badge status-pending">Not Uploaded</span>
                        @endif
                    </td>
                    <td>{{ $label }}</td>
                    <td>
                        @if($uploaded && $docNo)
                            {{ $docNo }}
                        @elseif($uploaded)
                            —
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        @if($uploaded && $issue)
                            {{ \Carbon\Carbon::parse($issue)->format('d-m-Y') }}
                        @elseif($uploaded)
                            —
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        @if($uploaded)
                             <a href="{{ asset('storage/'.$path) }}" target="_blank">View File</a>
                        @else
                            No file uploaded
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="small-muted">No enclosures configuration found.</td>
                </tr>
            @endforelse
        </table>

        {{-- Other Documents Table --}}
        <table class="info-table">
            <tr>
                <th colspan="5" style="text-align:left;">Other Documents</th>
            </tr>
            <tr>
                <th>Document Name</th>
                <th>Document No</th>
                <th>Issue Date</th>
                <th>Validity Date</th>
                <th>Status</th>
            </tr>
            @forelse($otherDocuments as $doc)
                @php
                    $name  = $doc['name'] ?? null;
                    $docNo = $doc['doc_no'] ?? null;
                    $issue = $doc['issue_date'] ?? null;
                    $valid = $doc['validity_date'] ?? null;
                    $path  = $doc['file_path'] ?? null;
                    $uploaded = !empty($path);
                @endphp
                <tr>
                    <td>{{ $name ?: '—' }}</td>
                    <td>{{ $docNo ?: '—' }}</td>
                    <td>
                        @if($issue)
                            {{ \Carbon\Carbon::parse($issue)->format('d-m-Y') }}
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        @if($valid)
                            {{ \Carbon\Carbon::parse($valid)->format('d-m-Y') }}
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        @if($uploaded)
                            <a href="{{ asset('storage/'.$path) }}" target="_blank" class="status-badge status-uploaded">View File</a>
                        @else
                            <span class="status-badge status-pending">Not Uploaded</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="small-muted">No other documents uploaded.</td>
                </tr>
            @endforelse
            <tr>
                <th colspan="4" style="text-align:right;">Total Documents Uploaded:</th>
                <td><strong>{{ $totalDocs }}</strong></td>
            </tr>
        </table>

        {{-- Declaration + QR + Signature (STEP 6) --}}
        <div class="section-title">Step 6: Declaration & Submission Summary</div>

        <table class="info-table">
            <tr>
                <td colspan="4">
                    {!! $terms_and_conditions->description ?? '' !!}
                </td>
            </tr>
            <tr>
                <td colspan="4" style="padding-top:15px; padding-bottom:10px;">
                    <div style="text-align:left; font-size:13px; line-height:1.6; border-top:1px solid #ddd; padding-top:10px;">
                        <label style="display:flex; align-items:flex-start; gap:8px; cursor:default;">
                            <input type="checkbox"
                                   id="declaration_check"
                                   value="1"
                                   @if($registration->declaration_accepted) checked @endif
                                   style="margin-top:3px; width:16px; height:16px;"
                                   disabled>
                            <span>
                                I hereby agree to the above declaration.
                            </span>
                        </label>
                    </div>
                </td>
            </tr>


            <tr>
                <td colspan="4" style="padding-top:20px;">
                    <table style="width:100%; border:none; border-collapse:collapse;">
                        <tr>
                            {{-- LEFT: QR CODE + DATE --}}
                            <td style="width:25%; text-align:left; vertical-align:top;">
                                <div style="display:inline-block; text-align:center;">
                                    <div id="qrcode"
                                         style="width:80px; height:80px; border:1px solid #ddd; padding:3px; background:#fff; margin:auto;">
                                    </div>
                                    <div style="margin-top:8px; font-size:13px; color: #555;">
                                        <strong>Submitted on:</strong><br>
                                        @if(!empty($registration->date))
                                         {{ \Carbon\Carbon::parse($registration->date)->format('d M Y') }}
                                        @else
                                            —
                                        @endif

                                    </div>
                                </div>
                            </td>

                            {{-- CENTER: EMPTY --}}
                            <td style="width:50%; text-align:center; vertical-align:middle;"></td>

                            {{-- RIGHT: SIGNATURE + DATE --}}
                            <td style="width:25%; text-align:right; vertical-align:top;">
                                <div style="display:inline-block; text-align:center; min-width:140px;">
                                    <div style="font-weight:bold; margin-bottom:4px; font-size:14px;">
                                        Signature of the Applicant Proprietor <br>/Partner/Director/Trustee
                                    </div>

                                    @if($signUrl)
                                        <img src="{{ $signUrl }}"
                                             alt="Applicant Signature"
                                             style="height:60px; margin-top:8px; display:block; margin-left:auto; margin-right:auto;">
                                    @else
                                        <div style="height:60px; width:140px; background:#f5f5f5; border:1px solid #ddd; margin:8px auto;"></div>
                                    @endif


                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>

                <td colspan="4" style="padding-top:15px;">
                    <table style="width:100%; border:none; border-collapse:collapse;">
                        <tr>
                            <th style="width:15%; text-align:left; padding:5px;">Place:</th>
                            <td style="width:35%; border:1px solid #ddd; padding:5px;">
                                {{ $registration->place ?? '—' }}
                            </td>
                            <th style="width:15%; text-align:left; padding:5px;">Date:</th>
                            <td style="width:35%; border:1px solid #ddd; padding:5px;">
                                @if(!empty($registration->date))
                                    {{ \Carbon\Carbon::parse($registration->date)->format('d M Y') }}
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="print-buttons no-print">
            <button class="btn btn-print" onclick="window.print()">
                <i class="fas fa-print me-1"></i> Print
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var container = document.getElementById('qrcode');
            if (!container) return;

            var qrText = "{{ $registration_id ?? '' }}";
            if (!qrText) return;

            Array.from(container.children).forEach(function (ch) {
                if (ch.tagName === 'CANVAS' || ch.tagName === 'IMG') {
                    container.removeChild(ch);
                }
            });

            new QRCode(container, {
                text: qrText,
                width: 80,
                height: 80,
                correctLevel: QRCode.CorrectLevel.H
            });
        });
    </script>
</body>
</html>