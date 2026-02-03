<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eligibility Certificate Registration Report</title>

    <link rel="icon" href="https://maharashtratourism.gov.in/wp-content/uploads/2025/01/mah-logo-300x277.png" sizes="32x32" type="image/png">

    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    {{-- QR Code --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            body {
                margin: 0;
                padding: 0;
                font-size: 10pt;
                background: white !important;
                color: black !important;
            }

            .container {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 15px !important;
                box-shadow: none !important;
                background-color: transparent !important;
            }

            table {
                page-break-inside: avoid;
                font-size: 9pt;
            }

            .badge {
                border: 1px solid #000 !important;
                color: #000 !important;
                background-color: transparent !important;
            }

            .section-title {
                background-color: #2c3e50 !important;
                color: #fff !important;
                border: 1px solid #000 !important;
            }

            .header-main-cell {
                background-color: #2c3e50 !important;
                color: #fff !important;
            }

            .watermark img {
                max-width: 350px;
                height: auto;
            }

            .project-details-table {
                border: 2px solid #000 !important;
            }

            .project-details-table th,
            .project-details-table td {
                border: 1px solid #000 !important;
            }
        }

        body {
            font-family: Verdana, Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
            font-size: 12px;
            position: relative;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        /* Watermark */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            opacity: 0.05;
            z-index: 0;
            pointer-events: none;
        }

        .watermark img {
            max-width: 350px;
            height: auto;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border-bottom: 2px solid #2c3e50;
            position: relative;
            z-index: 1;
        }

        .header-table td {
            vertical-align: middle;
            padding: 10px 0;
        }

        .logo {
            max-width: 100px;
            height: auto;
        }

        .form-title {
            font-family: 'Times New Roman', Times, serif;
            font-size: 16px;
            text-transform: uppercase;
            margin: 5px 0;
            color: #000000;
            font-weight: bold;
        }

        .header-main-cell {
            background-color: #2c3e50;
            color: #fff;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 11px;
            position: relative;
            z-index: 1;
        }

        .info-table td, .info-table th {
            border: 1px solid #ddd;
            padding: 5px;
        }

        .info-table th {
            background-color: #f2f2f2;
            width: 20%;
            font-weight: bold;
        }

        .section-title {
            background-color: #2c3e50;
            color: white;
            padding: 5px;
            text-align: center;
            font-weight: bold;
            margin: 10px 0 6px 0;
            font-size: 12px;
            position: relative;
            z-index: 1;
        }

        .project-details-table {
            width: 100%;
            border: 2px solid #000;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 10px;
        }

        .project-details-table th,
        .project-details-table td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            vertical-align: middle;
        }

        .project-details-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .project-details-table .component-header {
            text-align: left;
            font-weight: 600;
            background-color: #e9ecef;
        }

        .checkbox-list {
            text-align: left;
            font-size: 9px;
        }

        .print-buttons {
            text-align: center;
            margin: 20px 0;
            position: relative;
            z-index: 1;
        }

        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            margin: 0 6px;
        }

        .btn-print {
            background-color: #28a745;
            color: white;
        }

        .small-muted {
            font-size: 10px;
            color: #777;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
        }

        .status-uploaded {
            background:#d4edda;
            color:#155724;
        }

        .status-pending {
            background:#f8d7da;
            color:#721c24;
        }

        .text-left {
            text-align: left !important;
        }

        .enclosures-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 10px;
        }

        .enclosures-table th,
        .enclosures-table td {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: center;
        }

        .enclosures-table th {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
@php



    $registration_id = $registration->registration_id ?? 'ELIG-' . strtoupper(\Illuminate\Support\Str::random(8));

    // Arrays from JSON fields
    $entrepreneurs = is_array($registration->entrepreneurs) ? $registration->entrepreneurs : json_decode($registration->entrepreneurs, true) ?? [];
    $costComponent = is_array($registration->cost_component) ? $registration->cost_component : json_decode($registration->cost_component, true) ?? [];
    $assetAge = is_array($registration->asset_age) ? $registration->asset_age : json_decode($registration->asset_age, true) ?? [];
    $ownership = is_array($registration->ownership) ? $registration->ownership : json_decode($registration->ownership, true) ?? [];
    $enclosures = is_array($registration->enclosures) ? $registration->enclosures : json_decode($registration->enclosures, true) ?? [];
    $other_docs = is_array($registration->other_docs) ? $registration->other_docs : json_decode($registration->other_docs, true) ?? [];

    // Get region and district names
   

    // Signature path
    $signaturePath = $registration->signature_path
        ? asset('storage/' . $registration->signature_path)
        : null;

    // Document counts
    $enclosureCount = collect($enclosures)->filter(function($item) {
        return !empty($item['file_path'] ?? null);
    })->count();

    $otherDocsCount = collect($other_docs)->filter(function($item) {
        return !empty($item['file_path'] ?? null);
    })->count();

    $totalDocs = $enclosureCount + $otherDocsCount;

    // Define enclosure labels
    $enclosureLabels = [
        'travel_life' => 'Travel for LiFE Certificate',
        'ca_certificate' => 'CA Certificate of Capital Investment',
        'project_report' => 'Project Report',
        'shop_licence' => 'Copy of Licence under Shop & Establishment Act / Food & Drug Administration',
        'star_classification' => 'Star Classification Certificate',
        'mpcb_noc' => 'NOC from Maharashtra Pollution Control Board',
        'balance_sheets' => 'Audited Balance Sheets (from commencement year up to date)',
        'proof_commercial' => 'Proof of commencement of commercial operation (First Sale Bill / Excise Register)',
        'declaration_commencement' => 'Declaration by Director / Partner / Proprietor / Trustee regarding commencement date (on letterhead)',
        'completion_certificate' => 'Certificate of completion and permissions from respective authorities',
        'gst_registration' => 'Maharashtra State GST Registration Certificate',
        'processing_fee' => 'Processing Fee Challan (₹10,000) paid on www.gras.mahakosh.gov.in',
    ];

    // Project components for table
    $projectComponents = [
        'structures_buildings' => 'Structures and Buildings; Trees',
        'machinery_equipment' => 'Indigenous and Imported Machinery and Equipment',
        'material_handling' => 'Material Handling Equipment',
        'mep_installations' => 'Mechanical, Electrical and Plumbing Installations',
        'fixtures_furniture' => 'Fixtures, Furniture\'s and Fittings',
        'waste_treatment' => 'Waste Treatment Facilities',
        'transformer_generator' => 'Transformer Generator',
        'captive_power' => 'Captive Power Plants / Renewable Energy Sources',
        'utility_installation' => 'Utility and Installation Charges',
        'quality_investments' => 'Quality Related Investments',
        'sustainability_initiatives' => 'Sustainability Initiatives',
    ];
@endphp

    {{-- WATERMARK --}}
    <div class="watermark">
        <img src="{{ asset('backend/mah-logo-300x277.png') }}" alt="Watermark">
    </div>

    <div class="container">

        <!-- Header -->
        <table class="header-table">
            <tr>
                <td width="20%">
                    <img src="{{ asset('backend/mah-logo-300x277.png') }}" alt="Logo" class="logo">
                </td>
                <td width="60%" style="text-align: center;">
                    <div class="form-title">Tourism Registrations &amp; Certificates</div>
                    <div class="form-title">RIGHT TO SERVICES (RTS)</div>
                    <div class="form-title">Application for {{ $application_form->name ?? 'Eligibility Certificate Registration' }}</div>
                </td>
                <td width="20%" style="text-align: center;">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/26/Seal_of_Maharashtra.png?20170924125820"
                         alt="Maharashtra Seal"
                         class="logo">
                </td>
            </tr>
        </table>

        {{-- General Details --}}
        <div class="section-title">General Details</div>
        <table class="info-table">
            <tr>
                <th>Registration ID:</th>
                <td>{{ $registration_id }}</td>
                <th>Application Form:</th>
                <td>{{ $application_form->name ?? 'Eligibility Certificate' }}</td>
            </tr>
            <tr>
                <th>Applicant Name / Tourism Unit:</th>
                <td>{{ $registration->applicant_name ?? '—' }}</td>
                <th>Provisional Certificate No:</th>
                <td>{{ $registration->provisional_number ?? '—' }}</td>
            </tr>
            <tr>
                <th>GST Number:</th>
                <td>{{ $registration->gst_number ?? '—' }}</td>
                <th>Region:</th>
                <td>{{ $region->name ?? '—' }}</td>
            </tr>
            <tr>
                <th>District:</th>
                <td>{{ $district->name ?? '—' }}</td>
                <th>Date of Commercial Commencement:</th>
                <td>
                    @if($registration->commencement_date)
                        {{ \Carbon\Carbon::parse($registration->commencement_date)->format('d-m-Y') }}
                    @else
                        —
                    @endif
                </td>
            </tr>
            <tr>
                <th>Details of Operations:</th>
                <td>{{ $registration->operation_details ?? '—' }}</td>
                <th>Submission Date:</th>
                <td>
                    @if($registration->submitted_at)
                        {{ \Carbon\Carbon::parse($registration->submitted_at)->format('d-m-Y') }}
                    @else
                        —
                    @endif
                </td>
            </tr>
            <tr>
                <th>Status:</th>
                <td>
                    @if($registration->status == 'submitted')
                        <span class="status-badge status-uploaded">Submitted</span>
                    @else
                        <span class="status-badge status-pending">{{ $registration->status ?? 'Pending' }}</span>
                    @endif
                </td>
                <th>Is Applied:</th>
                <td>{{ $registration->is_apply ? 'Yes' : 'No' }}</td>
            </tr>
        </table>

        {{-- Entrepreneur's Profile --}}
        <div class="section-title">Entrepreneur's Profile (All Partner/Directors of the Organization)</div>
        @if(!empty($entrepreneurs) && count($entrepreneurs) > 0)
            <table class="info-table">
                <thead>
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
                                <td>{{ $entrepreneur['name'] ?? '—' }}</td>
                                <td>{{ $entrepreneur['designation'] ?? '—' }}</td>
                                <td>{{ $entrepreneur['ownership'] ?? '—' }}</td>
                                <td>{{ $entrepreneur['gender'] ?? '—' }}</td>
                                <td>{{ $entrepreneur['age'] ?? '—' }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="small-muted">No entrepreneur data available.</p>
        @endif

        {{-- Project Description --}}
        <div class="section-title">Project Description</div>
        <table class="info-table">
            <tr>
                <td style="white-space: pre-wrap;">{{ $registration->project_description ?? '—' }}</td>
            </tr>
        </table>

        {{-- Project Details --}}
        <div class="section-title">Project Details</div>
        <table class="project-details-table">
            <thead>
                <tr>
                    <th style="width:25%;">Component</th>
                    <th style="width:25%;">Eligible Capital Investment</th>
                    <th style="width:20%;">Cost Component</th>
                    <th style="width:15%;">Asset Age / Residual Age</th>
                    <th style="width:15%;">Ownership Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projectComponents as $key => $label)
                    @php
                        $cost = $costComponent[$key] ?? '—';
                        $age = $assetAge[$key] ?? '—';
                        $ownershipStatus = $ownership[$key] ?? [];
                        $ownershipText = is_array($ownershipStatus) ? implode(', ', $ownershipStatus) : $ownershipStatus;
                    @endphp

                    @if(in_array($key, ['structures_buildings', 'quality_investments', 'sustainability_initiatives']))
                        @if($key == 'structures_buildings')
                            <tr>
                                <td class="component-header" rowspan="9">Capital Cost of the Project (₹ in Lakh)</td>
                                <td class="text-left">{{ $label }}</td>
                                <td>{{ $cost }}</td>
                                <td>{{ $age }}</td>
                                <td>{{ $ownershipText ?: '—' }}</td>
                            </tr>
                        @elseif($key == 'quality_investments')
                            <tr>
                                <td class="component-header" rowspan="2">Other Investments</td>
                                <td class="text-left">{{ $label }}</td>
                                <td>{{ $cost }}</td>
                                <td>{{ $age }}</td>
                                <td>{{ $ownershipText ?: '—' }}</td>
                            </tr>
                        @elseif($key == 'sustainability_initiatives')
                            <tr>
                                <td class="text-left">{{ $label }}</td>
                                <td>{{ $cost }}</td>
                                <td>{{ $age }}</td>
                                <td>{{ $ownershipText ?: '—' }}</td>
                            </tr>
                        @endif
                    @else
                        <tr>
                            <td class="text-left">{{ $label }}</td>
                            <td>{{ $cost }}</td>
                            <td>{{ $age }}</td>
                            <td>{{ $ownershipText ?: '—' }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        {{-- Enclosures --}}
        <div class="section-title">Enclosures</div>
        <table class="enclosures-table">
            <thead>
                <tr>
                    <th style="width:5%;">#</th>
                    <th style="width:40%;">Document Type</th>
                    <th style="width:15%;">Doc No.</th>
                    <th style="width:15%;">Date of Issue</th>
                    <th style="width:25%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @php $counter = 1; @endphp
                @foreach($enclosureLabels as $key => $label)
                    @php
                        $enc = $enclosures[$key] ?? null;
                        $hasFile = !empty($enc['file_path'] ?? null);
                        $docNo = $enc['doc_no'] ?? '—';
                        $issueDate = $enc['issue_date'] ?? '—';
                    @endphp
                    <tr>
                        <td>{{ $counter++ }}</td>
                        <td class="text-left">{{ $label }}</td>
                        <td>{{ $docNo }}</td>
                        <td>
                            @if($issueDate != '—' && $issueDate)
                                {{ \Carbon\Carbon::parse($issueDate)->format('d-m-Y') }}
                            @else
                                {{ $issueDate }}
                            @endif
                        </td>
                        <td>
                            @if($hasFile)
                                <span class="status-badge status-uploaded">Uploaded</span>
                            @else
                                <span class="status-badge status-pending">Not Uploaded</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Other Documents --}}
        <div class="section-title">Other Documents</div>
        @if(!empty($other_docs) && count($other_docs) > 0)
            <table class="enclosures-table">
                <thead>
                    <tr>
                        <th>Document Name</th>
                        <th>Doc No.</th>
                        <th>Issue Date</th>
                        <th>Validity Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($other_docs as $doc)
                        @php
                            $hasFile = !empty($doc['file_path'] ?? null);
                            $name = $doc['name'] ?? '—';
                            $docNo = $doc['doc_no'] ?? '—';
                            $issueDate = $doc['issue_date'] ?? '—';
                            $validityDate = $doc['validity_date'] ?? '—';
                        @endphp
                        <tr>
                            <td class="text-left">{{ $name }}</td>
                            <td>{{ $docNo }}</td>
                            <td>
                                @if($issueDate != '—' && $issueDate)
                                    {{ \Carbon\Carbon::parse($issueDate)->format('d-m-Y') }}
                                @else
                                    {{ $issueDate }}
                                @endif
                            </td>
                            <td>
                                @if($validityDate != '—' && $validityDate)
                                    {{ \Carbon\Carbon::parse($validityDate)->format('d-m-Y') }}
                                @else
                                    {{ $validityDate }}
                                @endif
                            </td>
                            <td>
                                @if($hasFile)
                                    <span class="status-badge status-uploaded">Uploaded</span>
                                @else
                                    <span class="status-badge status-pending">Not Uploaded</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" style="text-align: right;">Total Documents Uploaded:</th>
                        <th>{{ $totalDocs }}</th>
                    </tr>
                </tfoot>
            </table>
        @else
            <p class="small-muted">No other documents uploaded.</p>
        @endif

        {{-- Declaration --}}
        <div class="section-title">Declaration</div>
        <table class="info-table">
            <tr>
                <td colspan="4">
                    <ol style="margin-left: 1rem; line-height: 1.6;">
                        <li>Certified that no claim for incentives has been sanctioned or disbursed...</li>
                        <li>Certified that the information / statement contained in this application are true...</li>
                        <li>Declared that no Government enquiry has been instituted against the applicant unit...</li>
                        <li>We hereby agree to abide by the terms and conditions...</li>
                        <li>We hereby agree that the Certificate of Entitlement / claim sanction letter issued on the basis of the above statements is liable to be cancelled if information is found incorrect.</li>
                    </ol>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <div style="text-align: left; font-size: 13px; line-height: 1.6; padding-top: 10px;">
                        <label style="display: flex; align-items: flex-start; gap: 8px; cursor: default;">
                            <input type="checkbox"
                                   value="1"
                                   checked
                                   style="margin-top: 3px; width: 16px; height: 16px;"
                                   disabled>
                            <span>
                                I have read and understood the above declaration.
                            </span>
                        </label>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="padding-top: 20px;">
                    <table style="width: 100%; border: none; border-collapse: collapse;">
                        <tr>
                            {{-- LEFT: QR CODE --}}
                            <td style="width: 25%; text-align: left; vertical-align: top;">
                                <div style="display: inline-block; text-align: center;">
                                    <div id="qrcode"
                                         style="width: 80px; height: 80px; border: 1px solid #ddd; padding: 3px; background: #fff; margin: auto;">
                                    </div>
                                    <div style="margin-top: 8px; font-size: 13px; color: #555;">
                                        <strong>Registration ID:</strong><br>
                                        {{ $registration_id }}
                                    </div>
                                </div>
                            </td>

                            {{-- CENTER: EMPTY --}}
                            <td style="width: 50%; text-align: center; vertical-align: middle;"></td>

                            {{-- RIGHT: SIGNATURE --}}
                            <td style="width: 25%; text-align: right; vertical-align: top;">
                                <div style="display: inline-block; text-align: center; min-width: 140px;">
                                    <div style="font-weight: bold; margin-bottom: 4px; font-size: 14px;">
                                        Signature of the Applicant / Proprietor / Partner / Director / Trustee
                                    </div>

                                    @if($signaturePath)
                                        <img src="{{ $signaturePath }}"
                                             alt="Applicant Signature"
                                             style="max-height: 60px; margin-top: 8px; display: block; margin-left: auto; margin-right: auto;">
                                    @else
                                        <div style="height: 60px; width: 140px; background: #f5f5f5; border: 1px solid #ddd; margin: 8px auto;"></div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="padding-top: 15px;">
                    <table style="width: 100%; border: none; border-collapse: collapse;">
                        <tr>
                            <th style="width: 15%; text-align: left; padding: 5px;">Place:</th>
                            <td style="width: 35%; border: 1px solid #ddd; padding: 5px;">
                                {{ $registration->declaration_place ?? '—' }}
                            </td>
                            <th style="width: 15%; text-align: left; padding: 5px;">Date:</th>
                            <td style="width: 35%; border: 1px solid #ddd; padding: 5px;">
                                @if($registration->declaration_date)
                                    {{ \Carbon\Carbon::parse($registration->declaration_date)->format('d M Y') }}
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

            var qrText = "{{ $registration_id }}";
            if (!qrText) return;

            // Clear existing QR code
            Array.from(container.children).forEach(function (ch) {
                if (ch.tagName === 'CANVAS' || ch.tagName === 'IMG') {
                    container.removeChild(ch);
                }
            });

            // Generate new QR code
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
