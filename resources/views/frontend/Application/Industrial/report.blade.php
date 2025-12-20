<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Industrial Registration Report - {{ $registration->registration_id }}</title>

    <link rel="icon" href="https://maharashtratourism.gov.in/wp-content/uploads/2025/01/mah-logo-300x277.png"
        sizes="32x32" type="image/png">

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

            .section-title {
                background-color: #ff6600 !important;
                color: #fff !important;
                border: 1px solid #000 !important;
            }

            .watermark img {
                max-width: 350px;
                height: auto;
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
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
            border-bottom: 2px solid #ff6600;
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

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 11px;
            position: relative;
            z-index: 1;
        }

        .info-table td,
        .info-table th {
            border: 1px solid #ddd;
            padding: 6px;
        }

        .info-table th {
            background-color: #f2f2f2;
            width: 25%;
            font-weight: bold;
            text-align: left;
        }

        .section-title {
            background-color: #ff6600;
            color: white;
            padding: 8px;
            text-align: center;
            font-weight: bold;
            margin: 15px 0 10px 0;
            font-size: 13px;
            position: relative;
            z-index: 1;
        }

        .print-buttons {
            text-align: center;
            margin: 20px 0;
            position: relative;
            z-index: 1;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            margin: 0 6px;
        }

        .btn-print {
            background-color: #28a745;
            color: white;
        }

        .btn-back {
            background-color: #6c757d;
            color: white;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }

        .status-submitted {
            background: #d4edda;
            color: #155724;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-approved {
            background: #d1ecf1;
            color: #0c5460;
        }

        .checkbox-item {
            display: inline-block;
            margin-right: 15px;
            margin-bottom: 5px;
        }

        .doc-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 10px;
        }

        .doc-table th,
        .doc-table td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
        }

        .doc-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .doc-uploaded {
            color: #28a745;
            font-weight: 600;
        }

        .doc-missing {
            color: #dc3545;
        }
    </style>
</head>

<body>
    @php
        $registration_id = $registration->registration_id ?? 'INS-' . strtoupper(\Illuminate\Support\Str::random(8));

        // Decode JSON fields
        $generalRequirements = is_string($registration->general_requirements)
            ? json_decode($registration->general_requirements, true) ?? []
            : ($registration->general_requirements ?? []);

        $safetySecurityItems = is_string($registration->safety_security)
            ? json_decode($registration->safety_security, true) ?? []
            : ($registration->safety_security ?? []);

        $additionalFeaturesItems = is_string($registration->additional_features)
            ? json_decode($registration->additional_features, true) ?? []
            : ($registration->additional_features ?? []);

        $guestServicesItems = is_string($registration->guest_services)
            ? json_decode($registration->guest_services, true) ?? []
            : ($registration->guest_services ?? []);
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
                    <div class="form-title">Tourism Registrations & Certificates</div>
                    <div class="form-title">RIGHT TO SERVICES (RTS)</div>
                    <div class="form-title">{{ $application_form->name ?? 'Industrial Hotel Registration' }}</div>
                </td>
                <td width="20%" style="text-align: center;">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/26/Seal_of_Maharashtra.png?20170924125820"
                        alt="Maharashtra Seal" class="logo">
                </td>
            </tr>
        </table>

        {{-- General Information --}}
        <div class="section-title">GENERAL INFORMATION</div>
        <table class="info-table">
            <tr>
                <th>Registration ID:</th>
                <td>{{ $registration_id }}</td>
                <th>Status:</th>
                <td>
                    @if($registration->status == 'submitted')
                        <span class="status-badge status-submitted">Submitted</span>
                    @elseif($registration->status == 'approved')
                        <span class="status-badge status-approved">Approved</span>
                    @else
                        <span class="status-badge status-pending">{{ ucfirst($registration->status) }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Email:</th>
                <td>{{ $registration->email ?? '—' }}</td>
                <th>Mobile:</th>
                <td>{{ $registration->mobile ?? '—' }}</td>
            </tr>
            <tr>
                <th>Hotel Name:</th>
                <td colspan="3">{{ $registration->hotel_name ?? '—' }}</td>
            </tr>
            <tr>
                <th>Hotel Address:</th>
                <td colspan="3">{{ $registration->hotel_address ?? '—' }}</td>
            </tr>
            <tr>
                <th>Company Name:</th>
                <td colspan="3">{{ $registration->company_name ?? '—' }}</td>
            </tr>
            <tr>
                <th>Company Address:</th>
                <td colspan="3">{{ $registration->company_address ?? '—' }}</td>
            </tr>
            <tr>
                <th>Authorized Person:</th>
                <td>{{ $registration->authorized_person ?? '—' }}</td>
                <th>Applicant Type:</th>
                <td>{{ $registration->applicant_type ?? '—' }}</td>
            </tr>
            <tr>
                <th>Region:</th>
                <td>{{ $registration->region ?? '—' }}</td>
                <th>District:</th>
                <td>{{ $registration->district ?? '—' }}</td>
            </tr>
            <tr>
                <th>Pincode:</th>
                <td>{{ $registration->pincode ?? '—' }}</td>
                <th>Star Category:</th>
                <td>{{ $registration->star_category ?? '—' }}</td>
            </tr>
            <tr>
                <th>Total Area (sq ft):</th>
                <td>{{ $registration->total_area ?? '—' }}</td>
                <th>Total Employees:</th>
                <td>{{ $registration->total_employees ?? '—' }}</td>
            </tr>
            <tr>
                <th>Total Rooms:</th>
                <td>{{ $registration->total_rooms ?? '—' }}</td>
                <th>Commencement Date:</th>
                <td>
                    @if($registration->commencement_date)
                        {{ \Carbon\Carbon::parse($registration->commencement_date)->format('d-m-Y') }}
                    @else
                        —
                    @endif
                </td>
            </tr>
            <tr>
                <th>Emergency Contact:</th>
                <td>{{ $registration->emergency_contact ?? '—' }}</td>
                <th>MSEB Consumer Number:</th>
                <td>{{ $registration->mseb_consumer_number ?? '—' }}</td>
            </tr>
            <tr>
                <th>Electricity Company:</th>
                <td>{{ $registration->electricity_company ?? '—' }}</td>
                <th>Property Tax Dept:</th>
                <td>{{ $registration->property_tax_dept ?? '—' }}</td>
            </tr>
            <tr>
                <th>Water Bill Dept:</th>
                <td colspan="3">{{ $registration->water_bill_dept ?? '—' }}</td>
            </tr>
            <tr>
                <th>Submitted At:</th>
                <td colspan="3">
                    @if($registration->submitted_at)
                        {{ \Carbon\Carbon::parse($registration->submitted_at)->format('d M Y, h:i A') }}
                    @else
                        —
                    @endif
                </td>
            </tr>
        </table>

        {{-- Requirements & Facilities --}}
        <div class="section-title">REQUIREMENTS & FACILITIES</div>

        <h4 style="margin: 10px 0 5px 0; font-size: 12px;">General Requirements:</h4>
        <div style="padding: 5px 10px; background: #f9f9f9; border: 1px solid #ddd; margin-bottom: 10px;">
            @if(!empty($generalRequirements) && count($generalRequirements) > 0)
                @foreach($GeneralRequirement as $req)
                    @if(in_array($req->id, $generalRequirements))
                        <span class="checkbox-item">✓ {{ $req->name }}</span>
                    @endif
                @endforeach
            @else
                <em>No general requirements selected</em>
            @endif
        </div>

        <table class="info-table">
            <tr>
                <th>Bathroom Fixtures:</th>
                <td>{{ $registration->bathroom_fixtures ? 'Yes' : 'No' }}</td>
                <th>Full Time Operation:</th>
                <td>{{ $registration->full_time_operation ? 'Yes' : 'No' }}</td>
            </tr>
            <tr>
                <th>Elevators:</th>
                <td>{{ $registration->elevators ? 'Yes' : 'No' }}</td>
                <th>Emergency Lights:</th>
                <td>{{ $registration->emergency_lights ? 'Yes' : 'No' }}</td>
            </tr>
            <tr>
                <th>CCTV:</th>
                <td>{{ $registration->cctv ? 'Yes' : 'No' }}</td>
                <th>Disabled Access:</th>
                <td>{{ $registration->disabled_access ? 'Yes' : 'No' }}</td>
            </tr>
        </table>

        {{-- STEP 3: Additional Features & Services --}}
        <div class="section-title">STEP 3: ADDITIONAL FEATURES & SERVICES</div>

        <h4 style="margin: 10px 0 5px 0; font-size: 12px;">Bathroom Features:</h4>
        <table class="info-table">
            <tr>
                <th>Attached Bathrooms:</th>
                <td>{{ $registration->bath_attached ? 'Yes' : 'No' }}</td>
                <th>Hot & Cold Water:</th>
                <td>{{ $registration->bath_hot_cold ? 'Yes' : 'No' }}</td>
            </tr>
            <tr>
                <th>Water Saving Taps:</th>
                <td colspan="3">{{ $registration->water_saving_taps ? 'Yes' : 'No' }}</td>
            </tr>
        </table>

        <h4 style="margin: 10px 0 5px 0; font-size: 12px;">Safety & Security:</h4>
        <div style="padding: 5px 10px; background: #f9f9f9; border: 1px solid #ddd; margin-bottom: 10px;">
            @if(!empty($safetySecurityItems) && count($safetySecurityItems) > 0)
                @foreach($SafetyAndSecurity as $item)
                    @if(in_array($item->id, $safetySecurityItems))
                        <span class="checkbox-item">✓ {{ $item->name }}</span>
                    @endif
                @endforeach
            @else
                <em>No safety & security items selected</em>
            @endif
        </div>

        <h4 style="margin: 10px 0 5px 0; font-size: 12px;">Additional Features:</h4>
        <div style="padding: 5px 10px; background: #f9f9f9; border: 1px solid #ddd; margin-bottom: 10px;">
            @if(!empty($additionalFeaturesItems) && count($additionalFeaturesItems) > 0)
                @foreach($AdditionalFeature as $item)
                    @if(in_array($item->id, $additionalFeaturesItems))
                        <span class="checkbox-item">✓ {{ $item->name }}</span>
                    @endif
                @endforeach
            @else
                <em>No additional features selected</em>
            @endif
        </div>

        <h4 style="margin: 10px 0 5px 0; font-size: 12px;">Guest Services:</h4>
        <div style="padding: 5px 10px; background: #f9f9f9; border: 1px solid #ddd; margin-bottom: 10px;">
            @if(!empty($guestServicesItems) && count($guestServicesItems) > 0)
                @foreach($GuestServices as $item)
                    @if(in_array($item->id, $guestServicesItems))
                        <span class="checkbox-item">✓ {{ $item->name }}</span>
                    @endif
                @endforeach
            @else
                <em>No guest services selected</em>
            @endif
        </div>

        <h4 style="margin: 10px 0 5px 0; font-size: 12px;">Public Areas & Facilities:</h4>
        <table class="info-table">
            <tr>
                <th>Public Lobby/Lounge:</th>
                <td>{{ $registration->public_lobby ? 'Yes' : 'No' }}</td>
                <th>Reception:</th>
                <td>{{ $registration->reception ? 'Yes' : 'No' }}</td>
            </tr>
            <tr>
                <th>Public Restrooms:</th>
                <td>{{ $registration->public_restrooms ? 'Yes' : 'No' }}</td>
                <th>Disabled Room:</th>
                <td>{{ $registration->disabled_room ? 'Yes' : 'No' }}</td>
            </tr>
            <tr>
                <th>FSSAI Kitchen:</th>
                <td>{{ $registration->fssai_kitchen ? 'Yes' : 'No' }}</td>
                <th>Staff Uniforms:</th>
                <td>{{ $registration->uniforms ? 'Yes' : 'No' }}</td>
            </tr>
        </table>

        <h4 style="margin: 10px 0 5px 0; font-size: 12px;">Compliance & Safety:</h4>
        <table class="info-table">
            <tr>
                <th>Pledge Display:</th>
                <td>{{ $registration->pledge_display ? 'Yes' : 'No' }}</td>
                <th>Complaint Book:</th>
                <td>{{ $registration->complaint_book ? 'Yes' : 'No' }}</td>
            </tr>
            <tr>
                <th>Nodal Officer Info:</th>
                <td>{{ $registration->nodal_officer ? 'Yes' : 'No' }}</td>
                <th>Doctor on Call:</th>
                <td>{{ $registration->doctor_on_call ? 'Yes' : 'No' }}</td>
            </tr>
            <tr>
                <th>Police Verification:</th>
                <td>{{ $registration->police_verification ? 'Yes' : 'No' }}</td>
                <th>Fire Drills:</th>
                <td>{{ $registration->fire_drills ? 'Yes' : 'No' }}</td>
            </tr>
            <tr>
                <th>First Aid:</th>
                <td colspan="3">{{ $registration->first_aid ? 'Yes' : 'No' }}</td>
            </tr>
        </table>

        <h4 style="margin: 10px 0 5px 0; font-size: 12px;">Premium Amenities:</h4>
        <table class="info-table">
            <tr>
                <th>Suite Rooms:</th>
                <td>{{ $registration->suite ? 'Yes' : 'No' }}</td>
                <th>F&B Outlet:</th>
                <td>{{ $registration->fb_outlet ? 'Yes' : 'No' }}</td>
            </tr>
            <tr>
                <th>Iron Facility:</th>
                <td>{{ $registration->iron_facility ? 'Yes' : 'No' }}</td>
                <th>Paid Transport:</th>
                <td>{{ $registration->paid_transport ? 'Yes' : 'No' }}</td>
            </tr>
            <tr>
                <th>Business Center:</th>
                <td>{{ $registration->business_center ? 'Yes' : 'No' }}</td>
                <th>Conference Facilities:</th>
                <td>{{ $registration->conference_facilities ? 'Yes' : 'No' }}</td>
            </tr>
        </table>

        <h4 style="margin: 10px 0 5px 0; font-size: 12px;">Sustainability:</h4>
        <table class="info-table">
            <tr>
                <th>Sewage Treatment:</th>
                <td>{{ $registration->sewage_treatment ? 'Yes' : 'No' }}</td>
                <th>Rainwater Harvesting:</th>
                <td>{{ $registration->rainwater_harvesting ? 'Yes' : 'No' }}</td>
            </tr>
        </table>

        {{-- STEP 4: Documents --}}
        <div class="section-title">STEP 4: UPLOADED DOCUMENTS</div>
        <table class="doc-table">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 50%;">Document Name</th>
                    <th style="width: 45%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $documents = [
                        ['label' => 'PAN Card', 'path' => $registration->pan_card_path],
                        ['label' => 'Aadhaar Card', 'path' => $registration->aadhaar_card_path],
                        ['label' => 'GST Certificate', 'path' => $registration->gst_cert_path],
                        ['label' => 'FSSAI Certificate', 'path' => $registration->fssai_cert_path],
                        ['label' => 'Business Registration', 'path' => $registration->business_reg_path],
                        ['label' => 'Declaration Form', 'path' => $registration->declaration_path],
                        ['label' => 'MPCB Certificate', 'path' => $registration->mpcb_cert_path],
                        ['label' => 'Light Bill', 'path' => $registration->light_bill_path],
                        ['label' => 'Fire NOC', 'path' => $registration->fire_noc_path],
                        ['label' => 'Property Tax Receipt', 'path' => $registration->property_tax_path],
                        ['label' => 'Star Certificate', 'path' => $registration->star_cert_path],
                        ['label' => 'Water Bill', 'path' => $registration->water_bill_path],
                        ['label' => 'Electricity Bill', 'path' => $registration->electricity_bill_path],
                        ['label' => 'Building Certificate', 'path' => $registration->building_cert_path],
                    ];
                    $counter = 1;
                @endphp
                @foreach($documents as $doc)
                    <tr>
                        <td style="text-align: center;">{{ $counter++ }}</td>
                        <td>{{ $doc['label'] }}</td>
                        <td>
                            @if(!empty($doc['path']))
                                <span class="doc-uploaded">✓ Uploaded</span>
                            @else
                                <span class="doc-missing">✗ Not Uploaded</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Footer with QR Code --}}
        <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #ddd;">
            <table style="width: 100%; border: none;">
                <tr>
                    <td style="width: 30%; text-align: left; vertical-align: top;">
                        <div style="text-align: center;">
                            <div id="qrcode" style="width: 100px; height: 100px; margin: 0 auto;"></div>
                            <div style="margin-top: 8px; font-size: 11px;">
                                <strong>Scan to Verify</strong><br>
                                {{ $registration_id }}
                            </div>
                        </div>
                    </td>
                    <td style="width: 70%; text-align: right; vertical-align: bottom;">
                        <div style="font-size: 11px; color: #666;">
                            <strong>Generated on:</strong> {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}<br>
                            <strong>Maharashtra Tourism Department</strong>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="print-buttons no-print">
            <button class="btn btn-back" onclick="window.history.back()">
                <i class="fas fa-arrow-left"></i> Go Back
            </button>
            <button class="btn btn-print" onclick="window.print()">
                <i class="fas fa-print"></i> Print Report
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var container = document.getElementById('qrcode');
            if (!container) return;

            var qrText = "{{ $registration_id }}\nHotel: {{ $registration->hotel_name ?? 'N/A' }}\nStatus: {{ $registration->status }}";
            if (!qrText) return;

            // Generate QR code
            new QRCode(container, {
                text: qrText,
                width: 100,
                height: 100,
                correctLevel: QRCode.CorrectLevel.H
            });
        });
    </script>
</body>

</html>