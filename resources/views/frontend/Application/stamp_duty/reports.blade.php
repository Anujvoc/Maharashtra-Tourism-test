<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stamp Duty Exemption Application Report</title>

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

        /* Stamp Duty Specific Styles */
        .preview-row {
            display: flex;
            padding: 4px 0;
            border-bottom: 1px dashed #eee;
            font-size: 11px;
        }

        .preview-label {
            flex: 0 0 230px;
            font-weight: 600;
            color: #333;
        }

        .preview-value {
            flex: 1;
            color: #555;
        }

        .preview-na {
            color: #999;
            font-style: italic;
        }

        .doc-link {
            color: #ff6600;
            text-decoration: none;
        }

        .doc-link:hover {
            text-decoration: underline;
        }

        .card-header-orange {
            background-color: #ff6600;
            color: white;
            padding: 8px 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
@php
    // Format dates
    $application_date = $registration->application_date ? \Carbon\Carbon::parse($registration->application_date)->format('d-m-Y') : '—';
    $eligibility_date = $registration->eligibility_date ? \Carbon\Carbon::parse($registration->eligibility_date)->format('d-m-Y') : '—';
    $submitted_at = $registration->submitted_at ? \Carbon\Carbon::parse($registration->submitted_at)->format('d-m-Y') : '—';

    // Calculate total project cost
    $total_cost = (
        (float)$registration->cost_land +
        (float)$registration->cost_building +
        (float)$registration->cost_machinery +
        (float)$registration->cost_electrical +
        (float)$registration->cost_misc +
        (float)$registration->cost_other
    );

    // Document paths
    $base_path = asset('storage/');
    $signature_path = $registration->signature_path ? $base_path . '/' . $registration->signature_path : null;
    $stamp_path = $registration->stamp_path ? $base_path . '/' . $registration->stamp_path : null;

    // Document list
    $documents = [
        'doc_challan' => 'Challan for processing fees',
        'doc_affidavit' => 'Affidavit',
        'doc_registration' => 'Registration proof (Company / Firm / Society)',
        'doc_ror' => 'Records of Right (RoR)',
        'doc_land_map' => 'Map of the land',
        'doc_dpr' => 'Detailed Project Report (DPR)',
        'doc_agreement' => 'Draft Agreement / Letter of Allotment',
        'doc_construction_plan' => 'Proposed plan of constructions',
        'doc_dp_remarks' => 'D.P. remarks / Zone Certificate',
    ];

    $uploaded_docs = 0;
    foreach ($documents as $field => $label) {
        if (!empty($registration->$field)) {
            $uploaded_docs++;
        }
    }
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
                    <div class="form-title">Department of Tourism</div>
                    <div class="form-title">Government of Maharashtra </div>
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

        {{-- General Details --}}
        <div class="section-title">Application Details</div>
        <table class="info-table">
            <tr>
                <th>Registration ID:</th>
                <td>{{ $registration->registration_id ?? '—' }}</td>
                <th>Application ID:</th>
                <td>{{ $registration->slug_id ?? '—' }}</td>
            </tr>
            <tr>
                <th>Applicant / Company Name:</th>
                <td>{{ $registration->company_name ?? '—' }}</td>
                <th>Registration No.:</th>
                <td>{{ $registration->registration_no ?? '—' }}</td>
            </tr>
            <tr>
                <th>Application Date:</th>
                <td>{{ $application_date ?? '' }}</td>
                <th>Type of Enterprise:</th>
                <td>{{ $applicant_types->name ?? '' }}</td>
            </tr>
            <tr>
                <th>Region:</th>
                <td>{{ $region->name ?? '—' }}</td>
                <th>District:</th>
                <td>{{ $district->name ?? '—' }}</td>
            </tr>
            <tr>
                <th>Agreement Type:</th>
                <td>{{ $registration->agreement_type ?? '—' }}</td>
                <th>Status:</th>
                <td>
                    @if($registration->status == 'submitted')
                        <span class="status-badge status-uploaded">Submitted</span>
                    @else
                        <span class="status-badge status-pending">{{ $registration->status ?? 'Pending' }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Submission Date:</th>
                <td>{{ $submitted_at }}</td>
                <th>Total Documents Uploaded:</th>
                <td>{{ $uploaded_docs }} / {{ count($documents) }}</td>
            </tr>
        </table>

        {{-- Correspondence Address --}}
        <div class="section-title">Correspondence Address</div>
        <table class="info-table">
            <tr>
                <th>Address:</th>
                <td colspan="3">{{ $registration->c_address ?? '—' }}</td>
            </tr>
            <tr>
                <th>City / Village:</th>
                <td>{{ $registration->c_city ?? '—' }}</td>
                <th>Taluka:</th>
                <td>{{ $registration->c_taluka ?? '—' }}</td>
            </tr>
            <tr>
                <th>District:</th>
                <td>{{ $registration->c_district ?? '—' }}</td>
                <th>State:</th>
                <td>{{ $registration->c_state ?? '—' }}</td>
            </tr>
            <tr>
                <th>Pin Code:</th>
                <td>{{ $registration->c_pincode ?? '—' }}</td>
                <th>Mobile:</th>
                <td>{{ $registration->c_mobile ?? '—' }}</td>
            </tr>
            <tr>
                <th>Telephone:</th>
                <td>{{ $registration->c_phone ?? '—' }}</td>
                <th>Email:</th>
                <td>{{ $registration->c_email ?? '—' }}</td>
            </tr>
            <tr>
                <th>Fax:</th>
                <td colspan="3">{{ $registration->c_fax ?? '—' }}</td>
            </tr>
        </table>

        {{-- Project Site Address --}}
        <div class="section-title">Project Site Address</div>
        <table class="info-table">
            <tr>
                <th>Address (Gat / Survey):</th>
                <td colspan="3">{{ $registration->p_address ?? '—' }}</td>
            </tr>
            <tr>
                <th>City / Village:</th>
                <td>{{ $registration->p_city ?? '—' }}</td>
                <th>Taluka:</th>
                <td>{{ $registration->p_taluka ?? '—' }}</td>
            </tr>
            <tr>
                <th>District:</th>
                <td>{{ $registration->p_district ?? '—' }}</td>
                <th>State:</th>
                <td>{{ $registration->p_state ?? '—' }}</td>
            </tr>
            <tr>
                <th>Pin Code:</th>
                <td>{{ $registration->p_pincode ?? '—' }}</td>
                <th>Mobile:</th>
                <td>{{ $registration->p_mobile ?? '—' }}</td>
            </tr>
            <tr>
                <th>Telephone:</th>
                <td>{{ $registration->p_phone ?? '—' }}</td>
                <th>Email:</th>
                <td>{{ $registration->p_email ?? '—' }}</td>
            </tr>
            <tr>
                <th>Website:</th>
                <td colspan="3">{{ $registration->p_website ?? '—' }}</td>
            </tr>
        </table>

        {{-- Land Details --}}
        <div class="section-title">Land Details</div>
        <table class="info-table">
            <tr>
                <th>CTS / Gat No.:</th>
                <td>{{ $registration->land_gat ?? '—' }}</td>
                <th>Village:</th>
                <td>{{ $registration->land_village ?? '—' }}</td>
            </tr>
            <tr>
                <th>Taluka:</th>
                <td>{{ $registration->land_taluka ?? '—' }}</td>
                <th>District:</th>
                <td>{{ $registration->land_district ?? '—' }}</td>
            </tr>
        </table>

        {{-- Area Details --}}
        <div class="section-title">Area Details (in Sq. M.)</div>
        <table class="project-details-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Area (Sq. M.)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-left">(A) Total land area</td>
                    <td>{{ $registration->area_a ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="text-left">(B) Land & built-up area</td>
                    <td>{{ $registration->area_b ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="text-left">(C) Land for tourism project</td>
                    <td>{{ $registration->area_c ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="text-left">(D) Land for ancillary activity</td>
                    <td>{{ $registration->area_d ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="text-left">(E) Vacant land</td>
                    <td>{{ $registration->area_e ?? '—' }}</td>
                </tr>
            </tbody>
        </table>

        {{-- Non-Agricultural Land --}}
        <div class="section-title">Non-Agricultural Land Details</div>
        <table class="info-table">
            <tr>
                <th>NA CTS / Gat No.:</th>
                <td>{{ $registration->na_gat ?? '—' }}</td>
                <th>NA Village:</th>
                <td>{{ $registration->na_village ?? '—' }}</td>
            </tr>
            <tr>
                <th>NA Taluka:</th>
                <td>{{ $registration->na_taluka ?? '—' }}</td>
                <th>NA District:</th>
                <td>{{ $registration->na_district ?? '—' }}</td>
            </tr>
            <tr>
                <th>Total NA Area:</th>
                <td colspan="3">{{ $registration->na_area ?? '—' }}</td>
            </tr>
        </table>

        {{-- Project Cost & Employment --}}
        <div class="section-title">Project Cost & Employment</div>
        <table class="info-table">
            <tr>
                <th>Estimated Project Cost (₹):</th>
                <td>{{ number_format($registration->estimated_project_cost ?? 0, 2) }}</td>
                <th>Proposed Employment:</th>
                <td>{{ $registration->proposed_employment ?? '—' }}</td>
            </tr>
            <tr>
                <th>Tourism Activities / Facilities:</th>
                <td colspan="3">{{ $registration->tourism_activities ?? '—' }}</td>
            </tr>
            <tr>
                <th>Incentives Availed Earlier:</th>
                <td colspan="3">{{ $registration->incentives_availed ?? '—' }}</td>
            </tr>
            <tr>
                <th>Project existed before 18/07/2024?</th>
                <td>{{ $registration->existed_before ? 'Yes' : 'No' }}</td>
                <th>Project Employment:</th>
                <td>{{ $registration->project_employment ?? '—' }}</td>
            </tr>
            @if($registration->existed_before)
            <tr>
                <th>Eligibility Certificate No.:</th>
                <td>{{ $registration->eligibility_cert_no ?? '—' }}</td>
                <th>Eligibility Date:</th>
                <td>{{ $eligibility_date }}</td>
            </tr>
            <tr>
                <th>Present Status:</th>
                <td colspan="3">{{ $registration->present_status ?? '—' }}</td>
            </tr>
            @endif
        </table>

        {{-- Project Cost Breakdown --}}
        <div class="section-title">Project Cost Breakdown (₹ in Lakhs)</div>
        <table class="project-details-table">
            <thead>
                <tr>
                    <th>Component</th>
                    <th>Amount (₹)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-left">Land</td>
                    <td>{{ $registration->cost_land ?? '0.00' }}</td>
                </tr>
                <tr>
                    <td class="text-left">Building</td>
                    <td>{{ $registration->cost_building ?? '0.00' }}</td>
                </tr>
                <tr>
                    <td class="text-left">Plant & Machinery</td>
                    <td>{{ $registration->cost_machinery ?? '0.00' }}</td>
                </tr>
                <tr>
                    <td class="text-left">Electrical Installations</td>
                    <td>{{ $registration->cost_electrical ?? '0.00' }}</td>
                </tr>
                <tr>
                    <td class="text-left">Misc. Fixed Assets</td>
                    <td>{{ $registration->cost_misc ?? '0.00' }}</td>
                </tr>
                <tr>
                    <td class="text-left">Other Expenses</td>
                    <td>{{ $registration->cost_other ?? '0.00' }}</td>
                </tr>
                <tr style="font-weight: bold; background-color: #f8f9fa;">
                    <td class="text-left">Total Project Cost</td>
                    <td>{{ number_format($total_cost, 2) }}</td>
                </tr>
            </tbody>
        </table>

        {{-- Purpose of NOC --}}
        <div class="section-title">Purpose of NOC</div>
        <table class="info-table">
            <tr>
                <th>Purpose of NOC:</th>
                <td>{{ $registration->noc_purpose ?? '—' }}</td>
            </tr>
            <tr>
                <th>NOC Authority:</th>
                <td>{{ $registration->noc_authority ?? '—' }}</td>
            </tr>
        </table>

        {{-- Uploaded Documents --}}
        <div class="section-title">Uploaded Documents</div>
        <table class="enclosures-table">
            <thead>
                <tr>
                    <th style="width:5%;">#</th>
                    <th style="width:55%;">Document Type</th>
                    <th style="width:20%;">File Name</th>
                    <th style="width:20%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @php $counter = 1; @endphp
                @foreach($documents as $field => $label)
                    @php
                        $file_path = $registration->$field;
                        $hasFile = !empty($file_path);
                        $file_name = $hasFile ? basename($file_path) : '—';
                    @endphp
                    <tr>
                        <td>{{ $counter++ }}</td>
                        <td class="text-left">{{ $label }}</td>
                        <td>
                            @if($hasFile)
                                <span class="small-muted">{{ $file_name }}</span>
                            @else
                                —
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
                    <th colspan="3" style="text-align: right;">Total Documents Uploaded:</th>
                    <th>{{ $uploaded_docs }} / {{ count($documents) }}</th>
                </tr>
            </tfoot>
        </table>

        {{-- Declaration & Signature --}}
        <div class="section-title">Declaration & Signature</div>
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
                            I/We hereby certify that the applicant has not been previously applied to Directorate of Tourism, Mumbai,
                        or any other department in Government of Maharashtra or Central Government and on the basis of that has
                        not availed any relief on payment of duty. Relief / Exemption from Stamp Duty & Registration fee have
                        started under New Tourism Policy 2024. If it is proved that entity has not started their business and
                        incentives are availed by them by supplying wrong information it will be my/our responsibility to return
                        the incentives along with the interest and to inform concerned authority of granting of exemption of stamp duty. <br>
                        I/We hereby certify that, land required by us for the purpose of Tourism Project will be as per Government
                        Rule for commencement of business.
                        </span>
                    </label>
                </div>
            </td>
        </tr>

        <table class="info-table" style="margin-top: 15px">

            <tr>
                <th>Name & Designation:</th>
                <td colspan="3">{{ $registration->name_designation ?? '—' }}</td>
            </tr>
            <tr>
                <td colspan="4" style="padding: 15px 0;">
                    <table style="width: 100%; border: none; border-collapse: collapse;">
                        <tr>
                            {{-- LEFT: QR CODE --}}
                            <td style="width: 25%; text-align: left; vertical-align: top;">
                                <div style="display: inline-block; text-align: center;">
                                    <div id="qrcode"
                                         style="width: 80px; height: 80px; border: 1px solid #ddd; padding: 3px; background: #fff; margin: auto;">
                                    </div>
                                    <div style="margin-top: 8px; font-size: 13px; color: #555;">
                                        <strong>Application ID:</strong><br>
                                        {{ $registration->slug_id ?? '—' }}
                                    </div>
                                </div>
                            </td>

                            {{-- CENTER: EMPTY --}}
                            <td style="width: 50%; text-align: center; vertical-align: middle;"></td>

                            {{-- RIGHT: SIGNATURE & STAMP --}}
                            <td style="width: 25%; text-align: right; vertical-align: top;">
                                <div style="display: inline-block; text-align: center; min-width: 140px;">
                                    <div style="font-weight: bold; margin-bottom: 4px; font-size: 14px;">
                                        Signature & Stamp
                                    </div>

                                    @if($signature_path)
                                        <img src="{{ $signature_path }}"
                                             alt="Applicant Signature"
                                             style="max-height: 40px; margin-top: 8px; display: block; margin-left: auto; margin-right: auto;">
                                    @else
                                        <div style="height: 40px; width: 140px; background: #f5f5f5; border: 1px solid #ddd; margin: 8px auto; line-height: 40px; color: #999;">
                                            No Signature
                                        </div>
                                    @endif

                                    @if($stamp_path)
                                        <img src="{{ $stamp_path }}"
                                             alt="Rubber Stamp"
                                             style="max-height: 40px; margin-top: 8px; display: block; margin-left: auto; margin-right: auto;">
                                    @else
                                        <div style="height: 40px; width: 140px; background: #f5f5f5; border: 1px solid #ddd; margin: 8px auto; line-height: 40px; color: #999;">
                                            No Stamp
                                        </div>
                                    @endif
                                </div>
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

            var qrText = "Application ID: {{ $registration->slug_id }}\nRegistration ID: {{ $registration->registration_id }}\nCompany: {{ $registration->company_name }}\nDate: {{ $submitted_at }}";
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
