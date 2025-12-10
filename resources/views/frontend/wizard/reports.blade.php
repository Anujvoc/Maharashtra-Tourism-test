<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $application_form->name ?? '' }} Report</title>

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

        /* Watermark on every page (screen + print) */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            opacity: 0.05;
            z-index: 0; /* behind main container */
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

        .badge {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 10px;
        }

        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .badge-primary {
            background-color: #cce5ff;
            color: #004085;
        }

        .nowrap {
            white-space: nowrap;
        }

        .pan-aadhar-table {
            width: 100%;
            border-collapse: collapse;
        }

        .pan-aadhar-table th,
        .pan-aadhar-table td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: center;
        }

        .pan-aadhar-table th {
            background-color: #f9f9f9;
        }

        .doc-placeholder {
            background:#eee;
            display:flex;
            align-items:center;
            justify-content:center;
            border:1px solid #ccc;
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
    </style>
</head>
<body>

    {{-- WATERMARK (fixed, appears on all printed pages) --}}
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
                    <div class="form-title">Application Form for the {{ $application_form->name ?? '' }}</div>
                </td>
                <td width="20%" style="text-align: center;">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/26/Seal_of_Maharashtra.png?20170924125820"
                         alt="Maharashtra Seal"
                         class="logo">
                </td>
            </tr>
        </table>

        @php
            $a = optional($application->applicant);

            $bizTypeName = null;
            if (isset($business_type) && $business_type instanceof \Illuminate\Support\Collection) {
                $biz = $business_type->firstWhere('id', $a->business_type);
                $bizTypeName = $biz ? $biz->name : ($a->business_type ?? null);
            } else {
                $bizTypeName = $a->business_type ?? null;
            }

            $ownershipUrl =  $a->ownership_proof ? asset('storage/'.$a->ownership_proof) : null;
            $rentalUrl    =  $a->rental_agreement ? asset('storage/'.$a->rental_agreement) : null;

            $pPhotos   = optional($application->photos);
            $imagePath = $pPhotos->applicant_image ?? null;
            $signPath  = $pPhotos->applicant_signature ?? null;

            $imageUrl  = $imagePath ? asset('storage/'.$imagePath) : null;
            $signUrl   = $signPath ? asset('storage/'.$signPath) : null;
        @endphp

        {{-- Applicant Info --}}
        <div class="section-title">Applicant Information</div>
        <table class="info-table">
            <tr>
                <th>Registration No:</th>
                <td>{{ $registration_id ?? '-' }}</td>
                <th>Date of Birth:</th>
                <td>—</td>
            </tr>
            <tr>
                <th>Candidate Name:</th>
                <td>{{ $a->name ?? '—' }}</td>
                <th>Gender:</th>
                <td></td>
            </tr>
            <tr>
                <th>Phone:</th>
                <td>{{ $a->phone ?? '—' }}</td>
                <th>Email:</th>
                <td>{{ $a->email ?? '—' }}</td>
            </tr>
            <tr>
                <th>Business Name:</th>
                <td>{{ $a->business_name ?? '—' }}</td>
                <th>Business Type:</th>
                <td>{{ $bizTypeName ?? '—' }}</td>
            </tr>
            <tr>
                <th>PAN:</th>
                <td>{{ $a->pan ?? '—' }}</td>
                <th>Business PAN:</th>
                <td>{{ $a->business_pan ?? '—' }}</td>
            </tr>
            <tr>
                <th>Aadhar No:</th>
                <td>{{ $a->aadhaar ?? '—' }}</td>
                <th>Udyam URN:</th>
                <td>{{ $a->udyam ?? '—' }}</td>
            </tr>
            <tr>
                <th>State:</th>
                <td>{{ $a->state ?? '—' }}</td>
                <th>District:</th>
                <td>{{ $a->district ?? '—' }}</td>
            </tr>
            <tr>
                <th>Is Property Rented:</th>
                <td>{{ isset($a->is_property_rented) ? ($a->is_property_rented ? 'Yes' : 'No') : '—' }}</td>
                <th>Operator Name:</th>
                <td>{{ $a->operator_name ?? '—' }}</td>
            </tr>
            <tr>
                <th>Ownership Proof Type:</th>
                <td>{{ $a->ownership_proof_type ?? '—' }}</td>
                <th>Photo & Signature:</th>
                <td style="text-align:right;">
                    @if($imageUrl)
                        <img src="{{ $imageUrl }}"
                             alt="Applicant Photo"
                             style="height:100px; display:block; margin-left:auto; margin-right:0;">
                    @else
                        <div style="height:100px; width:80px; background:#eee; margin-left:auto; margin-right:0;"></div>
                    @endif
                </td>
            </tr>
        </table>

        {{-- Property Info --}}
        @php
            $p = optional($application->property);
            $addressPath = $p->address_proof ?? null;
            $addressUrl  = $addressPath ? asset('storage/'.$addressPath) : null;
        @endphp

        <div class="section-title">Details of the Property</div>
        <table class="info-table">
            <tr>
                <th>Property Name:</th>
                <td>{{ $p->property_name ?? '—' }}</td>
                <th>District:</th>
                <td>{{ optional($p->district)->name ?? ($p->district_id ? $p->district_id : '—') }}</td>
            </tr>
            <tr>
                <th>Geo / Map Link:</th>
                <td colspan="3">{{ $p->geo_link ?? '—' }}</td>
            </tr>
            <tr>
                <th>Total Area (sq.ft):</th>
                <td>{{ $p->total_area_sqft ?? '—' }}</td>
                <th>Mahabooking Reg. No:</th>
                <td>{{ $p->mahabooking_reg_no ?? '—' }}</td>
            </tr>
            <tr>
                <th>Address Proof Type:</th>
                <td>{{ $p->address_proof_type ?? '—' }}</td>
                <th>Is Operational:</th>
                <td>{{ isset($p->is_operational) ? ($p->is_operational ? 'Yes' : 'No') : '—' }}</td>
            </tr>
            <tr>
                <th>Operational Since (Year):</th>
                <td>{{ $p->operational_since ?? '—' }}</td>
                <th>Guests till Mar 2025:</th>
                <td>{{ $p->guests_till_march ?? '—' }}</td>
            </tr>
        </table>

        {{-- Accommodation --}}
        @php
            $acc = optional($application->accommodation);

            $flatTypes = $acc->flat_types ?? [];
            if (is_string($flatTypes)) {
                $decoded = json_decode($flatTypes, true);
                $flatTypes = json_last_error() === JSON_ERROR_NONE && is_array($decoded) ? $decoded : [$flatTypes];
            }
            $flatTypes = is_array($flatTypes) ? $flatTypes : [];
        @endphp

        <div class="section-title">Accommodation Details</div>
        <table class="info-table">
            <tr>
                <th>Total Flats/Rooms:</th>
                <td>{{ $acc->flats_count ?? '—' }}</td>
                <th>Payment via Cash/UPI:</th>
                <td>{{ isset($acc->payment_upi) ? ($acc->payment_upi ? 'Yes' : 'No') : '—' }}</td>
            </tr>
            <tr>
                <th>Flat/Room Types:</th>
                <td colspan="3">
                    @if(!empty($flatTypes) && count($flatTypes))
                        {{ implode(', ', $flatTypes) }}
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Attached Toilet for each room:</th>
                <td>{{ isset($acc->attached_toilet) ? ($acc->attached_toilet ? 'Yes' : 'No') : '—' }}</td>
                <th>Dustbins for garbage disposal:</th>
                <td>{{ isset($acc->has_dustbins) ? ($acc->has_dustbins ? 'Yes' : 'No') : '—' }}</td>
            </tr>
            <tr>
                <th>Road Access:</th>
                <td>{{ isset($acc->road_access) ? ($acc->road_access ? 'Yes' : 'No') : '—' }}</td>
                <th>Food on Request:</th>
                <td>{{ isset($acc->food_on_request) ? ($acc->food_on_request ? 'Yes' : 'No') : '—' }}</td>
            </tr>
        </table>

        {{-- Facilities --}}
        @php
            $fRel = optional($application->facilities);
            $saved = $fRel->facilities ?? null;
            if (is_string($saved)) {
                $decoded = json_decode($saved, true);
                $saved   = json_last_error() === JSON_ERROR_NONE && is_array($decoded) ? $decoded : [$saved];
            }
            $selectedIds = is_array($saved) ? array_values(array_filter(array_map('intval', $saved))) : [];

            $facilityRows = \Illuminate\Support\Facades\DB::table('tourismfacilities')
                              ->whereIn('id', $selectedIds ?: [0])
                              ->orderBy('name')
                              ->get();

            $grasPaid = isset($fRel->gras_paid) ? (int)$fRel->gras_paid : null;
        @endphp

        <div class="section-title">Common Facilities</div>
        <table class="info-table">
            <tr>
                <th>Selected Facilities:</th>
                <td colspan="3">
                    @if(!empty($facilityRows) && count($facilityRows))
                        @foreach($facilityRows as $row)
                            <i class="{{ $row->icon ?? 'bi bi-check-circle' }}" aria-hidden="true"></i>
                            {{ $row->name }}@if(!$loop->last), @endif
                        @endforeach
                    @elseif(!empty($selectedIds) && count($selectedIds))
                        @foreach($selectedIds as $id)
                            ID: {{ $id }}@if(!$loop->last), @endif
                        @endforeach
                    @else
                        <span class="text-muted">No facilities selected</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>GRAS Payment (Rs. 500/-):</th>
                <td colspan="3">@if($grasPaid === 1) Yes @elseif($grasPaid === 0) No @else — @endif</td>
            </tr>
        </table>

        {{-- Ownership / Rental / Address Docs --}}
        @php
            $ownershipUrl =  $a->ownership_proof ? asset('storage/'.$a->ownership_proof) : null;
            $rentalUrl    =  $a->rental_agreement ? asset('storage/'.$a->rental_agreement) : null;
            $addressUrl   =  $addressPath ? asset('storage/'.$addressPath) : null;

            $docRows = [
                ['label' => 'Ownership Proof:',          'url' => $ownershipUrl],
                ['label' => 'Rental Agreement:',         'url' => $rentalUrl],
                ['label' => 'Property Address Proof:*',  'url' => $addressUrl],
            ];
        @endphp

        <div class="section-title">Uploaded Document</div>
        <table class="info-table w-100">
            <thead>
                <tr>
                    <th style="width:20px;">S.N.</th>
                    <th>Document</th>
                    <th width="300">Preview</th>
                </tr>
            </thead>
            <tbody>
                @foreach($docRows as $row)
                    <tr>
                        <td style="width:20px;">{{ $loop->iteration }}</td>
                        <td>{{ $row['label'] }}</td>
                        <td>
                            @if($row['url'])
                                @php
                                    $ext = strtolower(pathinfo($row['url'], PATHINFO_EXTENSION));
                                    $isImage = in_array($ext, ['jpg','jpeg','png','webp','gif']);
                                @endphp

                                @if($isImage)
                                    <a href="{{ $row['url'] }}" target="_blank" rel="noopener">
                                        <img src="{{ $row['url'] }}" alt="{{ $row['label'] }}" style="max-width:120px; max-height:120px; border-radius:4px; border:1px solid #ddd;">
                                    </a>
                                @else
                                    <a href="{{ $row['url'] }}" target="_blank" rel="noopener">
                                        View / Download
                                    </a>
                                @endif
                            @else
                                <span class="text-muted">Not uploaded</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Document Status --}}
        @php
            use Illuminate\Support\Facades\Storage;

            $docs = [
              'aadhar'          => 'Aadhaar Card of the Applicant*',
              'pan'             => 'PAN Card of the Applicant*',
              'business_pan'    => 'Business PAN Card (if applicable)',
              'udyam'           => 'Udyam Aadhaar (if applicable)',
              'business_reg'    => 'Business Registration Certificate*',
              'ownership'       => 'Proof of Ownership of Property*',
              'property_photos' => 'Photos of the Property (min 5)*',
              'character'       => 'Character Certificate from Police Station*',
              'society_noc'     => 'NOC from Society*',
              'building_perm'   => 'Building Permission/Completion Certificate*',
              'gras_copy'       => 'Copy of GRAS Challan*',
              'undertaking'     => 'Undertaking (Duly signed)*',
              'rental_agreement'=> 'Rental Agreement / Management Contract (if applicable)',
            ];

            $application->loadMissing('documents');
        @endphp

        <div class="section-title">Document Status</div>
        <table class="info-table w-100">
            <thead>
                <tr>
                    <th style="width:20px;">S.N.</th>
                    <th>Document</th>
                    <th width="200">Status</th>
                    <th width="300">Preview</th>
                </tr>
            </thead>
            <tbody>
                @foreach($docs as $key => $label)
                    @php
                        $existingDocs    = $application->documents->where('category', $key);
                        $isPropertyPhotos= $key === 'property_photos';
                        $first           = $existingDocs->first();
                    @endphp

                    <tr>
                        <td style="width:20px;">{{ $loop->iteration }}</td>

                        <td>
                            <strong>{{ str_replace('*','',$label) }}</strong>
                            @if(str_contains($label, '*'))
                                <div class="small-muted">Required</div>
                            @endif
                            @if($isPropertyPhotos)
                                <div class="small-muted mt-1">
                                    {{ $existingDocs->count() }} / 5 photos uploaded
                                </div>
                            @endif
                        </td>

                        <td>
                            @if($existingDocs->count() > 0)
                                <span class="status-badge status-uploaded">Uploaded</span>
                            @else
                                <span class="status-badge status-pending">Pending</span>
                            @endif
                        </td>

                        <td>
                            @if($existingDocs->count() > 0)
                                @if($isPropertyPhotos)
                                    <div class="photos-gallery">
                                        @foreach($existingDocs as $doc)
                                            @php
                                                $ext = strtolower(pathinfo($doc->original_name, PATHINFO_EXTENSION));
                                                $isImage = in_array($ext, ['jpg','jpeg','png','webp','gif']);
                                                $url = $doc->path ? asset('storage/'.$doc->path) : null;
                                            @endphp

                                            @if($isImage && $url)
                                                <div class="photo-item" style="display:inline-block; margin:3px;">
                                                    <img src="{{ $url }}" alt="{{ $doc->original_name }}" width="120">
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    @php
                                        $ext = strtolower(pathinfo($first->original_name ?? '', PATHINFO_EXTENSION));
                                        $isImage = in_array($ext, ['jpg','jpeg','png','webp','gif']);
                                        $url = $first && $first->path ? asset('storage/'.$first->path) : null;
                                    @endphp

                                    @if($isImage && $url)
                                        <img src="{{ $url }}" alt="{{ $first->original_name }}" width="120" class="preview-thumb">
                                    @elseif($url)
                                        <div style="display:flex; align-items:center; gap:6px;">
                                            <i class="bi bi-file-earmark-pdf text-danger" style="font-size:18px;"></i>
                                            <div>
                                                <div><a href="{{ $url }}" target="_blank" rel="noopener">{{ $first->original_name }}</a></div>
                                                <div class="small-muted">{{ number_format(($first->size ?? 0)/1024) }} KB</div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="no-file">File saved but not found on disk</div>
                                        <div class="small-muted">{{ $first->path ?? '' }}</div>
                                    @endif
                                @endif
                            @else
                                <div class="no-file">No file uploaded</div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Declaration + QR + Signature --}}
        <div class="section-title">Declaration</div>

        <table class="info-table">
            <tr>
                <td colspan="4" style="padding-top:15px; padding-bottom:10px;">
                    <div style="text-align:left; font-size:13px; line-height:1.6; border-top:1px solid #ddd; padding-top:10px;">
                        <label style="display:flex; align-items:flex-start; gap:8px; cursor:pointer;">
                            <input type="checkbox"
                                   id="declaration_check" value="1" checked
                                   style="margin-top:3px; width:16px; height:16px;">
                            <span>
                                I hereby declare that all the information provided in this application is true and correct to the best of my knowledge.
                                I understand that any false information may lead to rejection of my application.
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
                                    <div style="margin-top:8px; font-size:13px; color:#555;">
                                        <strong>Submitted on:</strong><br>
                                        {{ \Carbon\Carbon::parse($application->submitted_at)->format('d M Y') }}
                                    </div>
                                </div>
                            </td>

                            {{-- CENTER: EMPTY --}}
                            <td style="width:50%; text-align:center; vertical-align:middle;"></td>

                            {{-- RIGHT: SIGNATURE + DATE --}}
                            <td style="width:25%; text-align:right; vertical-align:top;">
                                <div style="display:inline-block; text-align:center; min-width:140px;">
                                    <div style="font-weight:bold; margin-bottom:4px; font-size:14px;">
                                        Applicant Signature
                                    </div>

                                    @if($signUrl)
                                        <img src="{{ $signUrl }}"
                                             alt="Applicant Signature"
                                             style="height:60px; margin-top:8px; display:block; margin-left:auto; margin-right:auto;">
                                    @else
                                        <div style="height:60px; width:140px; background:#f5f5f5; border:1px solid #ddd; margin:8px auto;"></div>
                                    @endif

                                    <div style="margin-top:8px; font-size:13px; color:#555;">
                                        <strong>Date:</strong><br>
                                        {{ \Carbon\Carbon::parse($application->submitted_at)->format('d M Y') }}
                                    </div>
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
