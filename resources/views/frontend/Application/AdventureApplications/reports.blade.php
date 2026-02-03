<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adventure Application Report</title>
    <link rel="icon" href="https://maharashtratourism.gov.in/wp-content/uploads/2025/01/mah-logo-300x277.png" sizes="32x32" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                padding: 0 !important;
                box-shadow: none !important;
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

            .watermark {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) rotate(-45deg);
                opacity: 0.08;
                z-index: -1;
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
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            position: relative;
        }

        .watermark {
            position: absolute;
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
    color: #000000;      /* black text */
    font-weight: bold;   /* bold text */
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
    </style>
    <style>
        
    </style>
</head>
<body>

    <div class="container">
        {{-- Watermark image instead of RTS text --}}
        <div class="watermark">
            <img src="{{ asset('backend/mah-logo-300x277.png') }}" alt="Watermark">
        </div>

        <!-- Header with Logo (left) and Maharashtra seal (right) -->
        <table class="header-table">
            <tr>
                <td width="20%">
                    <img src="{{ asset('backend/mah-logo-300x277.png') }}" alt="Logo" class="logo">
                </td>
                <td width="60%"  style="text-align: center;color:white">
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

        <!-- Basic Information -->
        <div class="section-title">Basic Information</div>
        <table class="info-table">
            <tr>
                <th>Registration No:</th>
                <td>{{ $registration_id ?? '—' }}</td>

                <th>Status:</th>
                <td>{{ $status ?? '—' }}</td>
            </tr>
            <tr>
                <th>Candidate Name:</th>
                <td>{{ $applicant_name ?? '—' }}</td>
                <th>Submitted Date:</th>
                <td>{{ $submitted_at ?? '—' }}</td>
            </tr>
            <tr>
                <th>Phone:</th>
                <td>{{ $mobile ?? '—' }}</td>
                <th>Email:</th>
                <td>{{ $email ?? '—' }}</td>
            </tr>
            <tr>
                <th>Whatsapp:</th>
                <td>{{ $whatsapp ?? '—' }}</td>
                <th>Business Name:</th>
                <td>{{ $company_name ?? '—' }}</td>

            </tr>
            <tr>
                <th>Address:</th>
                <td colspan="3">
                    {{ $applicant_address ?? '—' }}
                </td>
            </tr>



            {{-- <tr>
                <th>Aadhar No:</th>
                <td>—</td>
                <th>Udyam URN:</th>
                <td>—</td>
            </tr> --}}
            <tr>
                <th>Region:</th>
                <td>{{ $region ?? '—' }}</td>
                <th>District:</th>
                <td>{{ $district ?? '—' }}</td>
            </tr>

            {{-- PAN & Aadhar in clear tabular form --}}
            {{-- <tr>
                <th colspan="4" style="text-align:center;">Documents</th>
            </tr> --}}

        </table>

        <div class="section-title">Activity Details</div>

        <table class="info-table">
            <tr>
                <th class="nowrap">Adventure Activity Category:</th>
                <td>{{ $adventure_category ?? '—' }}</td>
                <th>Activity Name:</th>
                <td>{{ $activity_name ?? '—' }}</td>
            </tr>
            <tr>
                <th>Activity Location Address:</th>
                <td colspan="3">
                    {{ $activity_location ?? '—' }}
                </td>
            </tr>


        </table>
        <div class="section-title">Uploaded Documents</div>
        <table class="info-table">
            <tr>
                <td colspan="4" style="padding:0;">
                    <table class="pan-aadhar-table">
                        <tr>
                            <th>Pan</th>
                            <th>Aadhar</th>
                        </tr>
                        <tr>
                            <td>
                                @if(!empty($Adventure_Application_data['pan_file']))
                                    <img src="{{ asset('storage/' . $Adventure_Application_data['pan_file']) }}"
                                         alt="Applicant PAN"
                                         style="height:120px; border:1px solid #ccc; border-radius:8px;">
                                @else
                                    <div class="doc-placeholder" style="height:120px; width:100%;">
                                        <span>No PAN</span>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if(!empty($Adventure_Application_data['aadhar_file']))
                                    <img src="{{ asset('storage/' . $Adventure_Application_data['aadhar_file']) }}"
                                         alt="Applicant Aadhar"
                                         style="height:120px; border:1px solid #ccc; border-radius:8px;">
                                @else
                                    <div class="doc-placeholder" style="height:120px; width:100%;">
                                        <span>No Aadhar</span>
                                    </div>
                                @endif
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>
        </table>
        <tr>
            {{-- <th>QR Code:</th> --}}
            <td colspan="4" style="text-align:left;">
                <div id="qrcode"
                     style="width:80px; height:80px; border:1px solid #ddd; padding:3px; background:#fff;">
                </div>
            </td>
        </tr>
        {{-- <tr>
            <td colspan="4" style="text-align:left;">
                <div id="qrcode"
                     style="width:80px; height:80px; border:1px solid #ddd; padding:3px; background:#fff;">
                </div>


                <div style="margin-top:8px; font-size:11px; color:#333;">
                    <strong>Print Date:</strong> {{ \Carbon\Carbon::now()->format('d-m-Y') }}
                </div>
            </td>
        </tr> --}}




        <!-- Print Buttons -->
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

            var qrText = "{{ $registration_id ?? '' }}"; // QR me kya text chahiye

            if (!qrText) return;

            // Clear any old nodes
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
